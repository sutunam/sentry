<?php

/*
 * Sutunam
 *
 * @copyright Copyright (c) 2023 Sutunam (https://www.sutunam.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sutunam\Sentry\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use GuzzleHttp\Client;

/**
 * Proxy to send requests to Sentry
 * Prevent requests from being blocked by browsers
 *
 * https://docs.sentry.io/platforms/javascript/troubleshooting/#dealing-with-ad-blockers
 * @see view/frontend/templates/sentry.phtml
 * @see Sentry.init "tunnel" option
 */
class Index extends Action implements CsrfAwareActionInterface
{
    /**
     * Constructor
     *
     * @param Context $context
     * @param Client $client
     */
    public function __construct(
        Context $context,
        Client $client
    ) {
        $this->client = $client;
        parent::__construct($context);
    }

    /**
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @param RequestInterface $request
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $host = "sentry.io";

        $envelope = $this->getRequest()->getContent();

        $pieces = explode("\n", $envelope, 2);

        $header = json_decode($pieces[0], true);
        if (isset($header["dsn"])) {
            $path = explode('://', $header["dsn"])[1];
            $path = explode('/', $path)[1];
            $path = '/' . $path;
            $project_id = (int) trim($path, "/");

            $this->client->post("https://$host/api/$project_id/envelope/", [
                'headers' => [
                    'Content-Type' => 'application/x-sentry-envelope',
                ],
                'body' => $envelope
            ]);
        }
    }
}

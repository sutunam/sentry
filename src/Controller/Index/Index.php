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
use Magento\Framework\App\PlainTextRequestInterface;
use Magento\Framework\MessageQueue\PublisherInterface;
use Sutunam\Sentry\Model\Data\SentryRequest;

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
    /** @var PublisherInterface */
    private PublisherInterface $publisher;

    /** @var SentryRequest */
    private SentryRequest $sentryRequest;

    /**
     * @param Context $context
     * @param PublisherInterface $publisher
     * @param SentryRequest $sentryRequest
     */
    public function __construct(
        Context $context,
        PublisherInterface $publisher,
        SentryRequest $sentryRequest
    ) {
        $this->publisher = $publisher;
        $this->sentryRequest = $sentryRequest;
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $request = $this->getRequest();
        /** @var PlainTextRequestInterface $request */
        $envelope = $request->getContent();
        $this->sentryRequest->setEnvelope($envelope);
        $this->publisher->publish('sutunam.sentry.request', $this->sentryRequest);

        return $this->getResponse();
    }
}

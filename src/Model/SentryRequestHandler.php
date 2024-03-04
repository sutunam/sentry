<?php

/*
 * Sutunam
 *
 * @copyright Copyright (c) 2024 Sutunam (https://www.sutunam.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sutunam\Sentry\Model;

use GuzzleHttp\Client;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Sutunam\Sentry\Model\Data\SentryRequest;

class SentryRequestHandler
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var RemoteAddress
     */
    protected $remoteAddress;

    /**
     * SentryRequestHandler constructor.
     *
     * @param Client $client
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(
        Client $client,
        RemoteAddress $remoteAddress
    ) {
        $this->client = $client;
        $this->remoteAddress = $remoteAddress;
    }

    /**
     * Process request.
     *
     * @param SentryRequest $request
     * @return void
     */
    public function process(SentryRequest $request): void
    {
        $envelope = $request->getEnvelope();

        $host = "sentry.io";
        $pieces = explode("\n", $envelope, 2);

        $header = json_decode($pieces[0], true);
        if (isset($header["dsn"])) {
            $path = explode('://', $header["dsn"])[1];
            $path = explode('/', $path)[1];
            $path = '/' . $path;
            $projectId = (int) trim($path, "/");

            $this->client->post("https://$host/api/$projectId/envelope/", [
                'headers' => [
                    'Content-Type' => 'application/x-sentry-envelope',
                    'X-Forwarded-For' => $this->remoteAddress->getRemoteAddress()
                ],
                'body' => $envelope
            ]);
        }
    }
}

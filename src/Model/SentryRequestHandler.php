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
use Sutunam\Sentry\Model\Data\SentryRequest;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class SentryRequestHandler
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * SentryRequestHandler constructor.
     *
     * @param Client $client
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Client $client,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
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
        $remoteIp = $request->getCustomerIp();

        if ($this->isLoggingEnabled()) {
            // Log customer remote IP and envelope data
            $this->logger->info('Processing Sentry request', [
                'remote_ip' => $remoteIp,
                'envelope' => $envelope
            ]);
        }

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
                    'X-Forwarded-For' => $remoteIp
                ],
                'body' => $envelope
            ]);
        }
    }

    /**
     * Check if logging is enabled.
     *
     * @return bool
     */
    private function isLoggingEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            'web/sentry/enable_logging',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}

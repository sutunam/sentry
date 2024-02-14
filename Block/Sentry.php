<?php

/*
 * Sutunam
 *
 * @copyright Copyright (c) 2023 Sutunam (https://www.sutunam.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sutunam\Sentry\Block;

use Magento\Framework\App\View\Deployment\Version\Storage\File;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Session;

class Sentry extends \Magento\Framework\View\Element\Template
{
    private const SENTRY_ENVIRONMENT_CONFIG_PATH = "web/sentry/environment";
    private const SENTRY_PROJECT_NAME_CONFIG_PATH = "web/sentry/project_name";
    private const SENTRY_VERSION_CONFIG_PATH = "web/sentry/version";
    private const SENTRY_DSN_CONFIG_PATH = "web/sentry/dsn";

    /** @var File */
    private File $deployedVersion;

    /** @var ScopeConfigInterface */
    private ScopeConfigInterface $scopeConfig;

    /** @var Session */
    private Session $customerSession;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param File $deployedVersion
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ScopeConfigInterface $scopeConfig,
        File $deployedVersion,
        Session $customerSession,
        array $data = []
    ) {
        $this->deployedVersion = $deployedVersion;
        $this->scopeConfig = $scopeConfig;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * Check if customer is logged in
     *
     * @return bool
     */
    public function isCustomerLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    /**
     * Get customer id
     *
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customerSession->getCustomer()->getId();
    }

    /**
     * Check if required configurations are set
     *
     * @return bool
     */
    public function canShowTag(): bool
    {
        return null !== $this->getEnvironment() &&
            null !== $this->getProjectName() &&
            null !== $this->getDsn();
    }

    /**
     * Format release string, if release configuration is not set, we use Magento deployed version
     *
     * @return string
     */
    public function getRelease(): string
    {
        $release = $this->getProjectName();
        if ($config = $this->getReleaseConfig()) {
            $release .= '@' . $config;
        } else {
            $release .= '@' . $this->loadDeployedVersion();
        }

        return $release;
    }

    /**
     * Read pub/static/deployed_version.txt content
     *
     * @return string
     */
    private function loadDeployedVersion(): string
    {
        return $this->deployedVersion->load();
    }

    /**
     * Get environment configuration value
     *
     * @return ?string
     */
    public function getEnvironment(): ?string
    {
        return $this->scopeConfig->getValue(self::SENTRY_ENVIRONMENT_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get DSN configuration value
     *
     * @return ?string
     */
    public function getDsn(): ?string
    {
        return $this->scopeConfig->getValue(self::SENTRY_DSN_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get project name configuration value
     *
     * @return ?string
     */
    private function getProjectName(): ?string
    {
        return $this->scopeConfig->getValue(self::SENTRY_PROJECT_NAME_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get release configuration value
     *
     * @return ?string
     */
    private function getReleaseConfig(): ?string
    {
        return $this->scopeConfig->getValue(self::SENTRY_VERSION_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
}

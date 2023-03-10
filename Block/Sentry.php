<?php
namespace Sutunam\Sentry\Block;

use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

class Sentry extends \Magento\Framework\View\Element\Template
{
    private const SENTRY_PROJECT_NAME_CONFIG_PATH = "web/sentry/project_name";
    private const SENTRY_VERSION_CONFIG_PATH = "web/sentry/version";
    private const SENTRY_DSN_CONFIG_PATH = "web/sentry/dsn";

    /**
     * Contructor
     *
     * @param Template\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    /**
     * Comment
     *
     * @return mixed
     */
    public function getProjectName()
    {
        return $this->scopeConfig->getValue(self::SENTRY_PROJECT_NAME_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Comment
     *
     * @return mixed
     */
    public function getReleaseConfig()
    {
        return $this->scopeConfig->getValue(self::SENTRY_VERSION_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Comment
     *
     * @return mixed
     */
    public function getDsn()
    {
        return $this->scopeConfig->getValue(self::SENTRY_DSN_CONFIG_PATH, ScopeInterface::SCOPE_STORE);
    }
}

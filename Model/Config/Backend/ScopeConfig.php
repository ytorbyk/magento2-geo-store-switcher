<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\Backend;

use Magento\Store\Model\ScopeInterface;

class ScopeConfig extends \Tobai\GeoStoreSwitcher\Model\Config\ScopeConfig
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner
     */
    private $scopeDefiner;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $appScopeConfig
     * @param \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner $scopeDefiner
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $appScopeConfig,
        \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner $scopeDefiner
    ) {
        $this->scopeDefiner = $scopeDefiner;
        parent::__construct($appScopeConfig);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getStoreValue($path)
    {
        return $this->appScopeConfig->getValue($path, $this->scopeDefiner->getScope(), $this->scopeDefiner->getScopeValue());
    }

    /**
     * @param string $path
     * @return string
     */
    public function getWebsiteValue($path)
    {
        return $this->scopeDefiner->getScope() != ScopeInterface::SCOPE_STORE
            ? $this->appScopeConfig->getValue($path, $this->scopeDefiner->getScope(), $this->scopeDefiner->getScopeValue())
            : '';
    }
}

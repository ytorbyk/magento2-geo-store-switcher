<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config;

use Magento\Store\Model\ScopeInterface;

class ScopeConfig
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $appScopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $appScopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $appScopeConfig
    ) {
        $this->appScopeConfig = $appScopeConfig;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getStoreValue($path)
    {
        return $this->appScopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getWebsiteValue($path)
    {
        return $this->appScopeConfig->getValue($path, ScopeInterface::SCOPE_WEBSITE);
    }
}

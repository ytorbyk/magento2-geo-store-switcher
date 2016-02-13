<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config;

use Magento\Store\Model\ScopeInterface;
use Magento\Store\Api\Data\StoreInterface;

class ScopeConfig
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $appScopeConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner
     */
    protected $scopeDefiner;

    /**
     * @var \Tobai\GeoStoreSwitcher\Helper\Config\AppState
     */
    protected $appStateHelper;

    /**
     * @var int
     */
    protected $originStoreId;

    /**
     * @var int
     */
    protected $originWebsiteId;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $appScopeConfig
     * @param \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner $scopeDefiner
     * @param \Tobai\GeoStoreSwitcher\Helper\Config\AppState $appStateHelper
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $appScopeConfig,
        \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner $scopeDefiner,
        \Tobai\GeoStoreSwitcher\Helper\Config\AppState $appStateHelper
    ) {
        $this->appScopeConfig = $appScopeConfig;
        $this->scopeDefiner = $scopeDefiner;
        $this->appStateHelper = $appStateHelper;
    }

    /**
     * @param \Magento\Store\Api\Data\StoreInterface $store
     * @return $this
     */
    public function setOriginStore(StoreInterface $store)
    {
        if (null === $this->originStoreId) {
            $this->originStoreId = $store->getId();
            $this->originWebsiteId = $store->getWebsiteId();
        }
        return $this;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getDefaultValue($path)
    {
        return $this->appScopeConfig->getValue($path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getStoreValue($path)
    {
        return $this->appScopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $this->originStoreId);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getFrontendStoreOrBackendValue($path)
    {
        return $this->appStateHelper->isFrontendArea()
            ? $this->getStoreValue($path)
            : $this->appScopeConfig->getValue($path, $this->scopeDefiner->getScope(), $this->scopeDefiner->getScopeValue());
    }

    /**
     * @param string $path
     * @return string
     */
    public function getWebsiteValue($path)
    {
        return $this->appScopeConfig->getValue($path, ScopeInterface::SCOPE_WEBSITE, $this->originWebsiteId);
    }

    /**
     * @param string $path
     * @return string
     */
    public function getFrontendWebsiteOrBackendValue($path)
    {
        $value = '';
        if ($this->appStateHelper->isFrontendArea()) {
            $value = $this->getWebsiteValue($path);
        } else if ($this->scopeDefiner->getScope() != ScopeInterface::SCOPE_STORE) {
            $value = $this->appScopeConfig->getValue($path, $this->scopeDefiner->getScope(), $this->scopeDefiner->getScopeValue());
        }
        return $value;
    }
}

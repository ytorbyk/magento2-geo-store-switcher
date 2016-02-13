<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config;

use Magento\Store\Api\Data\StoreInterface;

class General
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeConfig
     */
    protected $scopeConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Helper\Config\AppState
     */
    protected $appStateHelper;

    /**
     * @var \Tobai\GeoStoreSwitcher\Helper\Config\Request
     */
    protected $requestHelper;

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\Config\ScopeConfig $scopeConfig
     * @param \Tobai\GeoStoreSwitcher\Helper\Config\AppState $appStateHelper
     * @param \Tobai\GeoStoreSwitcher\Helper\Config\Request $requestHelper
     */
    public function __construct(
        \Tobai\GeoStoreSwitcher\Model\Config\ScopeConfig $scopeConfig,
        \Tobai\GeoStoreSwitcher\Helper\Config\AppState $appStateHelper,
        \Tobai\GeoStoreSwitcher\Helper\Config\Request $requestHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->appStateHelper = $appStateHelper;
        $this->requestHelper = $requestHelper;
    }

    /**
     * @param \Magento\Store\Api\Data\StoreInterface $store
     * @return $this
     */
    public function setOriginStore(StoreInterface $store)
    {
        $this->scopeConfig->setOriginStore($store);
        return $this;
    }

    /**
     * @return bool
     */
    public function isAvailable()
    {
        return $this->appStateHelper->isFrontendArea()
            && !$this->requestHelper->isCurrentIp($this->getWhiteIps())
            && !$this->requestHelper->isCurrentUserAgent($this->getWhiteUa())
            && $this->isActive();
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->scopeConfig->getFrontendStoreOrBackendValue('tobai_geo_store_switcher/general/active');
    }

    /**
     * @return array
     */
    public function getWhiteIps()
    {
        $whiteIps = $this->scopeConfig->getStoreValue('tobai_geo_store_switcher/general/white_ips');
        return !empty($whiteIps) ? preg_split('#\s*,\s*#', $whiteIps, null, PREG_SPLIT_NO_EMPTY) : [];
    }

    /**
     * @return string
     */
    public function getWhiteUa()
    {
        return $this->scopeConfig->getStoreValue('tobai_geo_store_switcher/general/white_ua');
    }

    /**
     * @return bool
     */
    public function isOverwriteDefault()
    {
        return (bool)$this->scopeConfig->getWebsiteValue('tobai_geo_store_switcher/general/overwrite_default');
    }

    /**
     * @return string|bool
     */
    public function getDefaultStore()
    {
        return $this->isOverwriteDefault()
            ? $this->scopeConfig->getWebsiteValue('tobai_geo_store_switcher/general/default_store')
            : false;
    }

    /**
     * @return bool
     */
    public function isMappingSore()
    {
        return (bool)$this->scopeConfig->getWebsiteValue('tobai_geo_store_switcher/general/mapping_sore');
    }

    /**
     * @return bool
     */
    public function isCountries()
    {
        return $this->isActive()
            && $this->scopeConfig->getFrontendWebsiteOrBackendValue('tobai_geo_store_switcher/general/by_countries');
    }

    /**
     * @return array
     */
    public function getCountryList()
    {
        $countriesData = $this->scopeConfig->getFrontendWebsiteOrBackendValue('tobai_geo_store_switcher/general/country_list');
        $countries = $this->isCountries() && !empty($countriesData) ? explode(',', $countriesData) : [];
        return $countries;
    }

    /**
     * @param string $countryCode
     * @return string
     */
    public function getCountryStore($countryCode)
    {
        return $this->scopeConfig->getWebsiteValue("tobai_geo_store_switcher/{$countryCode}/store");
    }

    /**
     * @return int
     */
    public function getGroupCount()
    {
        return $this->isActive()
            ? (int)$this->scopeConfig->getFrontendWebsiteOrBackendValue('tobai_geo_store_switcher/general/by_groups')
            : 0;
    }

    /**
     * @param int $group
     * @return array
     */
    public function getGroupCountryList($group)
    {
        $countriesData = $this->scopeConfig->getFrontendWebsiteOrBackendValue("tobai_geo_store_switcher/group_{$group}/country_list");
        $countries = !empty($countriesData) ? explode(',', $countriesData) : [];
        return $countries;
    }

    /**
     * @param int $group
     * @return string
     */
    public function getGroupStore($group)
    {
        return $this->scopeConfig->getWebsiteValue("tobai_geo_store_switcher/group_{$group}/store");
    }
}

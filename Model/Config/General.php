<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;

class General
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->scopeConfig->isSetFlag('tobai_geo_store_switcher/general/active');
    }

    /**
     * @return bool
     */
    public function isOverwriteDefault()
    {
        return $this->scopeConfig->isSetFlag('tobai_geo_store_switcher/general/overwrite_default');
    }

    /**
     * @return string|bool
     */
    public function getDefaultStore()
    {
        return $this->isOverwriteDefault()
            ? $this->scopeConfig->getValue('tobai_geo_store_switcher/general/default_store')
            : false;
    }

    /**
     * @return bool
     */
    public function isMappingSore()
    {
        return $this->scopeConfig->isSetFlag('tobai_geo_store_switcher/general/mapping_sore');
    }

    /**
     * @return bool
     */
    public function isCountries()
    {
        return $this->scopeConfig->isSetFlag('tobai_geo_store_switcher/general/by_countries');
    }

    /**
     * @return array
     */
    public function getCountryList()
    {
        $countriesData = $this->scopeConfig->getValue('tobai_geo_store_switcher/general/country_list');
        $countries = $this->isCountries() && !empty($countriesData) ? explode(',', $countriesData) : [];
        return $countries;
    }

    /**
     * @param string $countryCode
     * @return string
     */
    public function getCountryStore($countryCode)
    {
        return $this->scopeConfig->getValue("tobai_geo_store_switcher/{$countryCode}/store");
    }

    /**
     * @return int
     */
    public function getGroupCount()
    {
        return (int)$this->scopeConfig->getValue('tobai_geo_store_switcher/general/by_groups');
    }

    /**
     * @param int $group
     * @return array
     */
    public function getGroupCountryList($group)
    {
        $countriesData = $this->scopeConfig->getValue("tobai_geo_store_switcher/group_{$group}/country_list");
        $countries = !empty($countriesData) ? explode(',', $countriesData) : [];
        return $countries;
    }

    /**
     * @param int $group
     * @return string
     */
    public function getGroupStore($group)
    {
        return $this->scopeConfig->getValue("tobai_geo_store_switcher/group_{$group}/store");
    }
}

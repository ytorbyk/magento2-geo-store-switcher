<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface as StoreScopeInterface;

class Store extends \Magento\Store\Model\System\Store
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner
     */
    private $scopeDefiner;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner $scopeDefiner
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner $scopeDefiner
    ) {
        $this->scopeDefiner = $scopeDefiner;
        parent::__construct($storeManager);
    }

    /**
     * Load/Reload Website collection
     *
     * @return $this
     */
    protected function _loadWebsiteCollection()
    {
        switch ($this->scopeDefiner->getScope()) {
            case ScopeConfigInterface::SCOPE_TYPE_DEFAULT:
                $this->_websiteCollection = $this->_storeManager->getWebsites();
                break;
            case StoreScopeInterface::SCOPE_WEBSITE:
                $websites = $this->_storeManager->getWebsites();
                $websiteId = $this->scopeDefiner->getScopeValue();
                $this->_websiteCollection = isset($websites[$websiteId]) ? [$websites[$websiteId]] : [];
                break;
            case StoreScopeInterface::SCOPE_STORE:
            default:
                $this->_websiteCollection = [];
                break;
        }
        return $this;
    }
}

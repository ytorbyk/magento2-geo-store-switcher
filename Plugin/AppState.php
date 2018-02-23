<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Plugin;

use Magento\Framework\App\Area;
use Magento\Framework\App\ObjectManager;
use Tobai\GeoStoreSwitcher\Model\Config\Backend\ScopeConfig as BackendScopeConfig;

class AppState
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General
     */
    private $configGeneral;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher
     */
    private $geoStoreSwitcher;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeCodeResolver
     */
    private $scopeCodeResolver;

    /**
     * @var array
     */
    private $disabledAreas = [
        Area::AREA_ADMIN,
        Area::AREA_ADMINHTML,
        Area::AREA_CRONTAB
    ];

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Tobai\GeoStoreSwitcher\Model\Config\General $configGeneral
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $geoStoreSwitcher
     * @param \Tobai\GeoStoreSwitcher\Model\Config\ScopeCodeResolver $scopeCodeResolver
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tobai\GeoStoreSwitcher\Model\Config\General $configGeneral,
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $geoStoreSwitcher,
        \Tobai\GeoStoreSwitcher\Model\Config\ScopeCodeResolver $scopeCodeResolver
    ) {
        $this->storeManager = $storeManager;
        $this->configGeneral = $configGeneral;
        $this->geoStoreSwitcher = $geoStoreSwitcher;
        $this->scopeCodeResolver = $scopeCodeResolver;
    }

    /**
     * @param \Magento\Framework\App\State $subject
     * @param void $result
     * @return void
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSetAreaCode(
        \Magento\Framework\App\State $subject,
        $result
    ) {
        if (!in_array($subject->getAreaCode(), $this->disabledAreas) && $this->configGeneral->isAvailable()) {
            $this->switchStore();
        } elseif ($subject->getAreaCode() === Area::AREA_ADMINHTML) {
            $scopeConfig = ObjectManager::getInstance()->get(BackendScopeConfig::class);
            $this->configGeneral->setScopeConfig($scopeConfig);
        }
    }

    /**
     * @return void
     */
    protected function switchStore()
    {
        $this->geoStoreSwitcher->initCurrentStore();
        $storeId = $this->geoStoreSwitcher->getCurrentStoreId();
        if ($storeId) {
            $this->storeManager->setCurrentStore($storeId);
            $this->scopeCodeResolver->reset();
        }
    }
}

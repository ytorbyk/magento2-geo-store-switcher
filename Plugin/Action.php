<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Plugin;

use Magento\Framework\App\Area;
use Magento\Framework\App\ObjectManager;
use Tobai\GeoStoreSwitcher\Model\Config\Backend\ScopeConfig as BackendScopeConfig;

class Action
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher
     */
    private $geoStoreSwitcher;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeCodeResolver
     */
    private $scopeCodeResolver;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    private $resultFactory;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $requestHelper;

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
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $geoStoreSwitcher
     * @param \Tobai\GeoStoreSwitcher\Model\Config\ScopeCodeResolver $scopeCodeResolver
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $geoStoreSwitcher,
        \Tobai\GeoStoreSwitcher\Model\Config\ScopeCodeResolver $scopeCodeResolver,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Framework\App\RequestInterface $requestHelper
    ) {
        $this->storeManager      = $storeManager;
        $this->geoStoreSwitcher  = $geoStoreSwitcher;
        $this->scopeCodeResolver = $scopeCodeResolver;
        $this->resultFactory     = $resultFactory;
        $this->requestHelper     = $requestHelper;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function afterExecute()
    {
        $targetStoreId = $this->getStoreIdBasedOnIP();
        $currentStore  = $this->storeManager->getStore();
        $resultFactory = $this->resultFactory;
        if ($targetStoreId && ($currentStore->getId() != $targetStoreId)) {
            $redirectUrl = rtrim($this->storeManager->getStore($targetStoreId)->getUrl(),'/') . $this->requestHelper->getPathInfo();
            $response    = $resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT)->setUrl($redirectUrl);
        } else {
            $response = $resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        }
        return $response;
    }

    /**
     * @return int|null
     */
    protected function getStoreIdBasedOnIp()
    {
        $this->geoStoreSwitcher->initCurrentStore();
        $storeId = $this->geoStoreSwitcher->getCurrentStoreId();
        return $storeId;
    }
}

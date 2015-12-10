<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model;

use Magento\Store\Model\ScopeInterface;

class GeoStoreResolver extends \Magento\Store\Model\StoreResolver
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General
     */
    protected $generalConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher
     */
    protected $storeSwitcher;

    /**
     * @var bool
     */
    protected $isSwitched = false;

    /**
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Magento\Store\Api\StoreCookieManagerInterface $storeCookieManager
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Cache\FrontendInterface $cache
     * @param \Magento\Store\Model\StoreResolver\ReaderList $readerList
     * @param \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $storeSwitcher
     * @param string $runMode
     * @param null $scopeCode
     */
    public function __construct(
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\Store\Api\StoreCookieManagerInterface $storeCookieManager,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Cache\FrontendInterface $cache,
        \Magento\Store\Model\StoreResolver\ReaderList $readerList,
        \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig,
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $storeSwitcher,
        $runMode = ScopeInterface::SCOPE_STORE,
        $scopeCode = null
    ) {
        $this->generalConfig = $generalConfig;
        $this->storeSwitcher = $storeSwitcher;
        parent::__construct(
            $storeRepository,
            $storeCookieManager,
            $request,
            $cache,
            $readerList,
            $runMode,
            $scopeCode
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStoreId()
    {
        if ($this->generalConfig->isActive() && !$this->isSwitched) {
            $this->switchScope();
            $this->isSwitched = true;
        }
        return parent::getCurrentStoreId();
    }

    /**
     * Change scope
     */
    protected function switchScope()
    {
        $storeId = $this->storeSwitcher->getStoreId();
        if ($storeId) {
            $this->runMode = ScopeInterface::SCOPE_STORE;
            $this->scopeCode = $this->storeRepository->getById($storeId)->getCode();
        }
    }
}

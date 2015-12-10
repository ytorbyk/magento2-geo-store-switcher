<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model;

use Magento\Store\Model\WebsiteRepository;
use Magento\Store\Model\Website;

class GeoWebsite
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher
     */
    protected $storeSwitcher;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var Int|bool
     */
    protected $defaultWebsiteId;

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $storeSwitcher
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $storeSwitcher,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository
    ) {
        $this->storeSwitcher = $storeSwitcher;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @return bool|int
     */
    public function getId()
    {
        if (null === $this->defaultWebsiteId && $this->storeSwitcher->isInitialized()) {
            try {
                $storeId = $this->storeSwitcher->getStoreId();
                if ($storeId) {
                    $store = $this->storeRepository->getById($storeId);
                    $this->defaultWebsiteId = $store->getWebsiteId();
                } else {
                    $this->defaultWebsiteId = false;
                }
            } catch (\Exception $e) {
                $this->defaultWebsiteId = false;
            }
        }
        return $this->defaultWebsiteId ?: false;
    }
}

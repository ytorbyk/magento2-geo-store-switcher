<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRule;

class CountryStoreMapping implements \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General
     */
    private $generalConfig;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    private $storeRepository;

    /**
     * @var \Magento\Store\Api\WebsiteRepositoryInterface
     */
    private $websiteRepository;

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig
     * @param \Magento\Store\Api\StoreRepositoryInterface $storeRepository
     * @param \Magento\Store\Api\WebsiteRepositoryInterface $websiteRepository
     */
    public function __construct(
        \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        \Magento\Store\Api\WebsiteRepositoryInterface $websiteRepository
    ) {
        $this->generalConfig = $generalConfig;
        $this->storeRepository = $storeRepository;
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * @param int|bool $storeId
     * @param string $countryCode
     * @return int
     */
    public function updateStoreId($storeId, $countryCode)
    {
        if (!$this->generalConfig->isMappingSore()) {
            return $storeId;
        }

        $countryStoreCode = strtolower($countryCode);
        if (in_array($countryStoreCode, $this->getWebsite($storeId)->getStoreCodes())) {
            $storeId = $this->storeRepository->get($countryStoreCode)->getId();
        }
        return $storeId;
    }

    /**
     * @param int|bool $storeId
     * @return \Magento\Store\Model\Website
     */
    protected function getWebsite($storeId)
    {
        if ($storeId) {
            $websiteId = $this->storeRepository->getById($storeId)->getWebsiteId();
            $website = $this->websiteRepository->getById($websiteId);
        } else {
            $website = $this->websiteRepository->getDefault();
        }
        return $website;
    }
}

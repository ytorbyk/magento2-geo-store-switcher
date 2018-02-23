<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Plugin;

class StoreResolver
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher
     */
    private $geoStoreSwitcher;

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $geoStoreSwitcher
     */
    public function __construct(
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $geoStoreSwitcher
    ) {
        $this->geoStoreSwitcher = $geoStoreSwitcher;
    }

    /**
     * @param \Magento\Store\Api\StoreResolverInterface $subject
     * @param int|string $result
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCurrentStoreId(
        \Magento\Store\Api\StoreResolverInterface $subject,
        $result
    ) {
        return $this->geoStoreSwitcher->getCurrentStoreId() ?: $result;
    }
}

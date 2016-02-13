<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher;

interface PermanentRuleInterface
{
    /**
     * @param int|bool $storeId
     * @param string $countryCode
     * @return int|bool
     */
    public function updateStoreId($storeId, $countryCode);
}

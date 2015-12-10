<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher;

interface RuleInterface
{
    /**
     * @param string $countryCode
     * @return int|bool
     */
    public function getStoreId($countryCode);
}

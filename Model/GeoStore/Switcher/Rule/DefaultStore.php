<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\Rule;

class DefaultStore implements \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General
     */
    private $generalConfig;

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig
     */
    public function __construct(
        \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig
    ) {
        $this->generalConfig = $generalConfig;
    }

    /**
     * @param string $countryCode
     * @return int|bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getStoreId($countryCode)
    {
        return $this->generalConfig->getDefaultStore();
    }
}

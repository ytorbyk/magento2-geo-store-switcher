<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\Rule;

use Tobai\GeoStoreSwitcher\Model;

class GroupCountry implements Model\GeoStore\Switcher\RuleInterface
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General
     */
    protected $generalConfig;

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig
     */
    public function __construct(
        Model\Config\General $generalConfig
    ) {
        $this->generalConfig = $generalConfig;
    }

    /**
     * @param string $countryCode
     * @return int|bool
     */
    public function getStoreId($countryCode)
    {
        $group = $this->getGroup($countryCode);
        return $group ? $this->generalConfig->getGroupStore($group) : false;
    }

    /**
     * @param string $countryCode
     * @return bool|int
     */
    protected function getGroup($countryCode)
    {
        $groupCount = $this->generalConfig->getGroupCount();
        for ($group = 1; $group <= $groupCount; $group++) {
            if (in_array($countryCode, $this->generalConfig->getGroupCountryList($group))) {
                return $group;
            }
        }
        return false;
    }
}

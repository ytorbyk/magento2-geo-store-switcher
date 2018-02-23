<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher;

class RulePool implements RuleInterface
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleFactory
     */
    private $ruleFactory;

    /**
     * @var array
     */
    private $rules = [];

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleFactory $ruleFactory
     * @param array $rules
     */
    public function __construct(
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleFactory $ruleFactory,
        array $rules
    ) {
        $this->ruleFactory = $ruleFactory;
        $this->rules = $rules;
    }

    /**
     * @param string $countryCode
     * @return int|bool
     */
    public function getStoreId($countryCode)
    {
        foreach ($this->rules as $ruleClass) {
            $rule = $this->ruleFactory->create($ruleClass);
            $storeId = $rule->getStoreId($countryCode);
            if ($storeId) {
                return $storeId;
            }
        }
        return false;
    }
}

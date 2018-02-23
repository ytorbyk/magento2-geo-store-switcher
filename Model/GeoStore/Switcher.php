<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore;

class Switcher
{
    /**
     * @var \Tobai\GeoIp2\Model\CountryInterface
     */
    private $country;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface
     */
    private $rule;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface
     */
    private $permanentRule;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var int|bool
     */
    private $storeId = false;

    /**
     * @param \Tobai\GeoIp2\Model\CountryInterface $country
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface $rule
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface $permanentRule
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Tobai\GeoIp2\Model\CountryInterface $country,
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface $rule,
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface $permanentRule,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->country = $country;
        $this->rule = $rule;
        $this->permanentRule = $permanentRule;
        $this->logger = $logger;
    }

    /**
     * @return int|null
     */
    public function getCurrentStoreId()
    {
        return $this->storeId;
    }

    /**
     * @return void
     */
    public function initCurrentStore()
    {
        $countryCode = (string)$this->country->getCountryCode();
        try {
            $storeId = $this->rule->getStoreId($countryCode);
            $storeId = $this->permanentRule->updateStoreId($storeId, $countryCode);
            $this->storeId = $storeId;
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}

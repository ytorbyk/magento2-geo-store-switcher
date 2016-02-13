<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore;

use Tobai\GeoIp2;
use Tobai\GeoStoreSwitcher\Model;
use Psr\Log\LoggerInterface as Logger;

class Switcher
{
    /**
     * @var \Tobai\GeoIp2\Model\CountryInterface
     */
    protected $country;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface
     */
    protected $rule;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface
     */
    protected $permanentRule;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var int|bool
     */
    protected $storeId = false;

    /**
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * @param \Tobai\GeoIp2\Model\CountryInterface $country
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface $rule
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface $permanentRule
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        GeoIp2\Model\CountryInterface $country,
        Model\GeoStore\Switcher\RuleInterface $rule,
        Model\GeoStore\Switcher\PermanentRuleInterface $permanentRule,
        Logger $logger
    ) {
        $this->country = $country;
        $this->rule = $rule;
        $this->permanentRule = $permanentRule;
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->isInitialized;
    }

    /**
     * @return bool|int
     */
    public function getStoreId()
    {
        if (!$this->isInitialized) {
            $countryCode = (string)$this->country->getCountryCode();

            try {
                $this->storeId = $this->rule->getStoreId($countryCode);
                $this->storeId = $this->permanentRule->updateStoreId($this->storeId, $countryCode);
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
            $this->isInitialized = true;
        }

        return $this->storeId;
    }
}

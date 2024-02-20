<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore;

use Magento\Framework\HTTP\PhpEnvironment\Request;

class Switcher
{
    private const COUNTRY_CODE_HEADER = 'HTTP_CLOUDFRONT_VIEWER_COUNTRY';
    private const COUNTRY_CODE_DEFAULT = 'SE';

    private Request $httpRequest;

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
     * @param Request $httpRequest
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface $rule
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface $permanentRule
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Request $httpRequest,
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface $rule,
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface $permanentRule,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->httpRequest = $httpRequest;
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
        $countryCode = $this->getCountryCode();
        try {
            $storeId = $this->rule->getStoreId($countryCode);
            $storeId = $this->permanentRule->updateStoreId($storeId, $countryCode);
            $this->storeId = $storeId;
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }

    private function getCountryCode(): string
    {
        return $this->httpRequest->getServerValue(self::COUNTRY_CODE_HEADER, self::COUNTRY_CODE_DEFAULT);
    }
}

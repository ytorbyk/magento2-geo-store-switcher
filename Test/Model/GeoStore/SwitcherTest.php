<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\GeoStore;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class SwitcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher
     */
    protected $switcher;

    /**
     * @var \Tobai\GeoIp2\Model\CountryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $country;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $rule;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $permanentRule;

    /**
     * @var \Psr\Log\LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $logger;

    protected function setUp()
    {
        $this->country = $this->getMock('Tobai\GeoIp2\Model\CountryInterface');

        $this->rule = $this->getMock('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface');

        $this->permanentRule = $this->getMock('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRuleInterface');

        $this->logger = $this->getMock('Psr\Log\LoggerInterface');

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->switcher = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher',
            [
                'country' => $this->country,
                'rule' => $this->rule,
                'permanentRule' => $this->permanentRule,
                'logger' => $this->logger
            ]
        );
    }

    public function testGetStoreId()
    {
        $countryCode = 'UA';
        $storeId = 2;
        $updatedStoreId = 5;

        $this->country->expects($this->once())
            ->method('getCountryCode')
            ->willReturn($countryCode);

        $this->rule->expects($this->once())
            ->method('getStoreId')
            ->with($countryCode)
            ->willReturn($storeId);

        $this->permanentRule->expects($this->once())
            ->method('updateStoreId')
            ->with($storeId, $countryCode)
            ->willReturn($updatedStoreId);

        $this->logger->expects($this->never())->method('critical');

        $this->assertFalse($this->switcher->isInitialized());
        $this->assertEquals($updatedStoreId, $this->switcher->getStoreId());
        $this->assertTrue($this->switcher->isInitialized());
    }

    public function testGetStoreIdWithRuleException()
    {
        $countryCode = 'UA';
        $ex = new \Exception('Rule Exception');

        $this->country->expects($this->once())
            ->method('getCountryCode')
            ->willReturn($countryCode);

        $this->rule->expects($this->once())
            ->method('getStoreId')
            ->with($countryCode)
            ->willThrowException($ex);

        $this->permanentRule->expects($this->never())->method('updateStoreId');

        $this->logger->expects($this->once())
            ->method('critical')
            ->with($ex);

        $this->assertFalse($this->switcher->isInitialized());
        $this->assertFalse($this->switcher->getStoreId());
        $this->assertTrue($this->switcher->isInitialized());
    }

    public function testGetStoreIdWithPermanentRuleException()
    {
        $countryCode = 'UA';
        $storeId = 2;
        $ex = new \Exception('Permanent Rule Exception');

        $this->country->expects($this->once())
            ->method('getCountryCode')
            ->willReturn($countryCode);

        $this->rule->expects($this->once())
            ->method('getStoreId')
            ->with($countryCode)
            ->willReturn($storeId);

        $this->permanentRule->expects($this->once())
            ->method('updateStoreId')
            ->with($storeId, $countryCode)
            ->willThrowException($ex);

        $this->logger->expects($this->once())
            ->method('critical')
            ->with($ex);

        $this->assertFalse($this->switcher->isInitialized());
        $this->assertEquals($storeId, $this->switcher->getStoreId());
        $this->assertTrue($this->switcher->isInitialized());
    }
}

<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\GeoStore;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class RulePoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RulePool
     */
    protected $rulePool;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $ruleFactory;

    /**
     * @var array
     */
    protected $rules = [
        'rule_1' => 'Rule Class 1',
        'rule_2' => 'Rule Class 2'
    ];

    protected function setUp()
    {
        $this->ruleFactory = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->rulePool = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RulePool',
            [
                'ruleFactory' => $this->ruleFactory,
                'rules' => $this->rules
            ]
        );
    }

    public function testGetStoreIdFirstRuleSuccess()
    {
        $countryCode = 'UA';
        $storeRule1 = 2;

        $rule1 = $this->getMock('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface');
        $rule1->expects($this->once())
            ->method('getStoreId')
            ->with($countryCode)
            ->willReturn($storeRule1);


        $this->ruleFactory->expects($this->once())
            ->method('create')
            ->with('Rule Class 1')
            ->willReturn($rule1);

        $this->assertEquals($storeRule1, $this->rulePool->getStoreId($countryCode));
    }

    public function testGetStoreIdSecondRuleSuccess()
    {
        $countryCode = 'UA';
        $storeRule1 = false;
        $storeRule2 = 5;

        $rule1 = $this->getMock('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface');
        $rule1->expects($this->once())
            ->method('getStoreId')
            ->with($countryCode)
            ->willReturn($storeRule1);

        $rule2 = $this->getMock('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface');
        $rule2->expects($this->once())
            ->method('getStoreId')
            ->with($countryCode)
            ->willReturn($storeRule2);


        $this->ruleFactory->expects($this->exactly(2))
            ->method('create')
            ->willReturnMap([
                ['Rule Class 1', [], $rule1],
                ['Rule Class 2', [], $rule2]
            ]);

        $this->assertEquals($storeRule2, $this->rulePool->getStoreId($countryCode));
    }

    public function testGetStoreIdNoRuleSuccess()
    {
        $countryCode = 'UA';
        $storeRule1 = false;
        $storeRule2 = false;

        $rule1 = $this->getMock('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface');
        $rule1->expects($this->once())
            ->method('getStoreId')
            ->with($countryCode)
            ->willReturn($storeRule1);

        $rule2 = $this->getMock('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface');
        $rule2->expects($this->once())
            ->method('getStoreId')
            ->with($countryCode)
            ->willReturn($storeRule2);


        $this->ruleFactory->expects($this->exactly(2))
            ->method('create')
            ->willReturnMap([
                ['Rule Class 1', [], $rule1],
                ['Rule Class 2', [], $rule2]
            ]);

        $this->assertFalse($this->rulePool->getStoreId($countryCode));
    }
}

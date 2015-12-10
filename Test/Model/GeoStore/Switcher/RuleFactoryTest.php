<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\GeoStore;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class RuleFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var \Magento\Framework\ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    protected function setUp()
    {
        $this->objectManager = $this->getMock('Magento\Framework\ObjectManagerInterface');

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->ruleFactory = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleFactory',
            [
                'objectManager' => $this->objectManager,
            ]
        );
    }

    public function testCreate()
    {
        $className = 'Rule Class Name';

        $rule = $this->getMock('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface');

        $this->objectManager->expects($this->once())
            ->method('create')
            ->with($className)
            ->willReturn($rule);

        $this->assertSame($rule, $this->ruleFactory->create($className));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage WrongClassName doesn't implement \Tobai\GeoStoreSwitcher\Model\Store\Switcher\RuleInterface
     */
    public function testCreateWrongClass()
    {
        $className = 'WrongClassName';

        $rule = $this->getMockBuilder('WrongClassName')->disableOriginalConstructor()->getMock();

        $this->objectManager->expects($this->once())
            ->method('create')
            ->with($className)
            ->willReturn($rule);

        $this->ruleFactory->create($className);
    }
}

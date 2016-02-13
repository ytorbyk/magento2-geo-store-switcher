<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\GeoStore;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class GroupCountryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\Rule\GroupCountry
     */
    protected $groupCountry;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $generalConfig;

    protected function setUp()
    {
        $this->generalConfig = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\Config\General')
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->groupCountry = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\Rule\GroupCountry',
            [
                'generalConfig' => $this->generalConfig
            ]
        );
    }

    public function testGetStoreIdGroup1()
    {
        $countryCode = 'UA';
        $groupCount = 2;
        $group1 = 1;
        $groupCountryList1 = ['US', 'UA', 'UK'];
        $groupCountryStore1 = 5;

        $this->generalConfig->expects($this->once())
            ->method('getGroupCount')
            ->willReturn($groupCount);

        $this->generalConfig->expects($this->once())
            ->method('getGroupCountryList')
            ->with($group1)
            ->willReturn($groupCountryList1);

        $this->generalConfig->expects($this->once())
            ->method('getGroupStore')
            ->with($group1)
            ->willReturn($groupCountryStore1);

        $this->assertEquals($groupCountryStore1, $this->groupCountry->getStoreId($countryCode));
    }

    public function testGetStoreIdGroup2()
    {
        $countryCode = 'UA';
        $groupCount = 2;

        $group1 = 1;
        $groupCountryList1 = ['US', 'UK'];

        $group2 = 2;
        $groupCountryList2 = ['DE', 'UA', 'FR'];
        $groupCountryStore2 = 4;

        $this->generalConfig->expects($this->once())
            ->method('getGroupCount')
            ->willReturn($groupCount);

        $this->generalConfig->expects($this->exactly(2))
            ->method('getGroupCountryList')
            ->willReturnMap(
                [
                    [$group1, $groupCountryList1],
                    [$group2, $groupCountryList2]
                ]
            );

        $this->generalConfig->expects($this->once())
            ->method('getGroupStore')
            ->with($group2)
            ->willReturn($groupCountryStore2);

        $this->assertEquals($groupCountryStore2, $this->groupCountry->getStoreId($countryCode));
    }

    public function testGetStoreIdNoGroup()
    {
        $countryCode = 'UA';
        $groupCount = 2;

        $group1 = 1;
        $groupCountryList1 = ['US', 'UK'];

        $group2 = 2;
        $groupCountryList2 = ['DE', 'FR'];

        $this->generalConfig->expects($this->once())
            ->method('getGroupCount')
            ->willReturn($groupCount);

        $this->generalConfig->expects($this->exactly(2))
            ->method('getGroupCountryList')
            ->willReturnMap(
                [
                    [$group1, $groupCountryList1],
                    [$group2, $groupCountryList2]
                ]
            );

        $this->generalConfig->expects($this->never())->method('getGroupStore');

        $this->assertFalse($this->groupCountry->getStoreId($countryCode));
    }
}

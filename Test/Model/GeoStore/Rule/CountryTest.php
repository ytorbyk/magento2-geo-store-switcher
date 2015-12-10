<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\GeoStore;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class CountryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\Rule\Country
     */
    protected $country;

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
        $this->country = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\Rule\Country',
            [
                'generalConfig' => $this->generalConfig
            ]
        );
    }

    public function testGetStoreId()
    {
        $countryCode = 'UA';
        $countryList = ['US', 'UA', 'UK'];
        $countryStore = 5;

        $this->generalConfig->expects($this->once())
            ->method('getCountryList')
            ->willReturn($countryList);

        $this->generalConfig->expects($this->once())
            ->method('getCountryStore')
            ->with($countryCode)
            ->willReturn($countryStore);

        $this->assertEquals($countryStore, $this->country->getStoreId($countryCode));
    }

    public function testGetStoreIdNotInList()
    {
        $countryCode = 'UA';
        $countryList = ['US', 'UK'];

        $this->generalConfig->expects($this->once())
            ->method('getCountryList')
            ->willReturn($countryList);

        $this->generalConfig->expects($this->never())->method('getCountryStore');

        $this->assertFalse($this->country->getStoreId($countryCode));
    }
}

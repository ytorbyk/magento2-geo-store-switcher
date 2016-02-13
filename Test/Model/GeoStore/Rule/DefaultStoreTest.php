<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\GeoStore;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class DefaultStoreTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\Rule\DefaultStore
     */
    protected $defaultStore;

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
        $this->defaultStore = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\Rule\DefaultStore',
            [
                'generalConfig' => $this->generalConfig
            ]
        );
    }

    public function testGetStoreId()
    {
        $countryCode = 'UA';
        $configDefaultStore = 2;

        $this->generalConfig->expects($this->once())
            ->method('getDefaultStore')
            ->willReturn($configDefaultStore);

        $this->assertEquals($configDefaultStore, $this->defaultStore->getStoreId($countryCode));
    }
}

<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Plugin\PageCache;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class GeoIdentifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Plugin\PageCache\GeoIdentifier
     */
    protected $geoIdentifier;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $generalConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeSwitcher;

    protected function setUp()
    {
        $this->generalConfig = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\Config\General')
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeSwitcher = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher')
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->geoIdentifier = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Plugin\PageCache\GeoIdentifier',
            [
                'generalConfig' => $this->generalConfig,
                'storeSwitcher' => $this->storeSwitcher,
            ]
        );
    }

    public function testAfterGetValueDisabled()
    {
        $identifierValue = 'some_str';

        $this->generalConfig->expects($this->once())
            ->method('isAvailable')
            ->willReturn(false);

        $this->storeSwitcher->expects($this->never())->method('getStoreId');

        /** @var \Magento\Framework\App\PageCache\Identifier $identifier */
        $identifier = $this->getMockBuilder('Magento\Framework\App\PageCache\Identifier')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals($identifierValue, $this->geoIdentifier->afterGetValue($identifier, $identifierValue));
    }

    /**
     * @param int|bool $storeId
     * @param string $identifierValue
     * @param string $geoIdentifierValue
     * @dataProvider afterGetValueDataProvider
     */
    public function testAfterGetValue($storeId, $identifierValue, $geoIdentifierValue)
    {
        $this->generalConfig->expects($this->once())
            ->method('isAvailable')
            ->willReturn(true);

        $this->storeSwitcher->expects($this->atLeastOnce())
            ->method('getStoreId')
            ->willReturn($storeId);

        /** @var \Magento\Framework\App\PageCache\Identifier $identifier */
        $identifier = $this->getMockBuilder('Magento\Framework\App\PageCache\Identifier')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals($geoIdentifierValue, $this->geoIdentifier->afterGetValue($identifier, $identifierValue));
    }

    /**
     * @return array
     */
    public function afterGetValueDataProvider()
    {
        return [
            'store is not identified' =>
                [
                    'storeId' => false,
                    'identifierValue' => 'some_str',
                    'geoIdentifierValue' => 'some_str'
                ],
            'store identified' =>
                [
                    'storeId' => 12,
                    'identifierValue' => 'some_str',
                    'geoIdentifierValue' => 'some_str12'
                ]
        ];
    }
}

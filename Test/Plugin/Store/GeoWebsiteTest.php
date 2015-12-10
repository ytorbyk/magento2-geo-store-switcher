<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Plugin\Store;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class GeoWebsiteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Plugin\Store\GeoWebsite
     */
    protected $geoWebsite;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $generalConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeSwitcher;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeRepository;

    protected function setUp()
    {
        $this->generalConfig = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\Config\General')
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeSwitcher = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeRepository = $this->getMock('Magento\Store\Api\StoreRepositoryInterface');

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->geoWebsite = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Plugin\Store\GeoWebsite',
            [
                'generalConfig' => $this->generalConfig,
                'storeSwitcher' => $this->storeSwitcher,
                'storeRepository' => $this->storeRepository,
            ]
        );
    }

    public function testAfterGetDefaultStoreNotActive()
    {
        $this->generalConfig->expects($this->once())
            ->method('isActive')
            ->willReturn(false);

        $this->storeSwitcher->expects($this->never())->method('isInitialized');
        $this->storeSwitcher->expects($this->never())->method('getStoreId');

        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->getMock();
        $website->expects($this->never())->method('getStoreIds');

        $store = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $store->expects($this->never())->method('getId');


        $this->storeRepository->expects($this->never())->method('getById');

        $this->assertSame($store, $this->geoWebsite->afterGetDefaultStore($website, $store));
    }

    public function testAfterGetDefaultStoreNotInitialized()
    {
        $this->generalConfig->expects($this->once())
            ->method('isActive')
            ->willReturn(true);

        $this->storeSwitcher->expects($this->once())
            ->method('isInitialized')
            ->willReturn(false);

        $this->storeSwitcher->expects($this->never())->method('getStoreId');

        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->getMock();
        $website->expects($this->never())->method('getStoreIds');

        $store = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $store->expects($this->never())->method('getId');


        $this->storeRepository->expects($this->never())->method('getById');

        $this->assertSame($store, $this->geoWebsite->afterGetDefaultStore($website, $store));
    }

    public function testAfterGetDefaultStore()
    {
        $storeId = 1;
        $websiteStoreIds = [1, 2, 3];
        $geoStoreId = 2;

        $this->generalConfig->expects($this->once())
            ->method('isActive')
            ->willReturn(true);

        $this->storeSwitcher->expects($this->once())
            ->method('isInitialized')
            ->willReturn(true);
        $this->storeSwitcher->expects($this->once())
            ->method('getStoreId')
            ->willReturn($geoStoreId);

        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn($websiteStoreIds);

        $store = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $geoStore = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeRepository->expects($this->once())
            ->method('getById')
            ->willReturn($geoStore);

        $this->assertSame($geoStore, $this->geoWebsite->afterGetDefaultStore($website, $store));
    }

    public function testAfterGetDefaultStoreSameStore()
    {
        $storeId = 1;
        $geoStoreId = 1;

        $this->generalConfig->expects($this->once())
            ->method('isActive')
            ->willReturn(true);

        $this->storeSwitcher->expects($this->once())
            ->method('isInitialized')
            ->willReturn(true);
        $this->storeSwitcher->expects($this->once())
            ->method('getStoreId')
            ->willReturn($geoStoreId);

        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->getMock();
        $website->expects($this->never())->method('getStoreIds');

        $store = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $this->assertSame($store, $this->geoWebsite->afterGetDefaultStore($website, $store));
    }

    public function testAfterGetDefaultStoreNotInWebsite()
    {
        $storeId = 1;
        $websiteStoreIds = [1, 3];
        $geoStoreId = 2;

        $this->generalConfig->expects($this->once())
            ->method('isActive')
            ->willReturn(true);

        $this->storeSwitcher->expects($this->once())
            ->method('isInitialized')
            ->willReturn(true);
        $this->storeSwitcher->expects($this->once())
            ->method('getStoreId')
            ->willReturn($geoStoreId);

        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->getMock();
        $website->expects($this->once())
            ->method('getStoreIds')
            ->willReturn($websiteStoreIds);

        $store = $this->getMockBuilder('Magento\Store\Model\Store')
            ->disableOriginalConstructor()
            ->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $this->assertSame($store, $this->geoWebsite->afterGetDefaultStore($website, $store));
    }
}

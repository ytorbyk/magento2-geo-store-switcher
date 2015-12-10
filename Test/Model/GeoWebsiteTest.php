<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class GeoWebsiteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoWebsite
     */
    protected $geoWebsite;

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
        $this->storeSwitcher = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeRepository = $this->getMock('Magento\Store\Api\StoreRepositoryInterface');

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->geoWebsite = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\GeoWebsite',
            [
                'storeSwitcher' => $this->storeSwitcher,
                'storeRepository' => $this->storeRepository,
            ]
        );
    }

    public function testGetIdStoreNotInitialized()
    {
        $this->storeSwitcher->expects($this->once())
            ->method('isInitialized')
            ->willReturn(false);

        $this->storeSwitcher->expects($this->never())->method('getStoreId');
        $this->storeRepository->expects($this->never())->method('getById');

        $this->assertFalse($this->geoWebsite->getId());
    }

    public function testGetIdStoreFalse()
    {
        $this->storeSwitcher->expects($this->once())
            ->method('isInitialized')
            ->willReturn(true);

        $this->storeSwitcher->expects($this->once())
            ->method('getStoreId')
            ->willReturn(false);
        $this->storeRepository->expects($this->never())->method('getById');

        $this->assertFalse($this->geoWebsite->getId());
        $this->assertFalse($this->geoWebsite->getId());
    }

    public function testGetIdStoreException()
    {
        $this->storeSwitcher->expects($this->once())
            ->method('isInitialized')
            ->willReturn(true);

        $this->storeSwitcher->expects($this->once())
            ->method('getStoreId')
            ->willThrowException(new \Magento\Framework\Exception\NoSuchEntityException());
        $this->storeRepository->expects($this->never())->method('getById');

        $this->assertFalse($this->geoWebsite->getId());
        $this->assertFalse($this->geoWebsite->getId());
    }

    public function testGetId()
    {
        $storeId = 2;
        $websiteId = 55;

        $this->storeSwitcher->expects($this->once())
            ->method('isInitialized')
            ->willReturn(true);

        $store = $this->getMock('Magento\Store\Api\Data\StoreInterface');
        $store->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);

        $this->storeSwitcher->expects($this->once())
            ->method('getStoreId')
            ->willReturn($storeId);
        $this->storeRepository->expects($this->once())
            ->method('getById')
            ->with($storeId)
            ->willReturn($store);

        $this->assertSame($websiteId, $this->geoWebsite->getId());
        $this->assertSame($websiteId, $this->geoWebsite->getId());
    }
}

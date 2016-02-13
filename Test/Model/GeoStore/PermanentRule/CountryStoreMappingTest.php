<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\GeoStore\Switcher\PermanentRule;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class CountryStoreMappingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRule\CountryStoreMapping
     */
    protected $countryStoreMapping;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $generalConfig;

    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeRepository;

    /**
     * @var \Magento\Store\Api\WebsiteRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $websiteRepository;

    protected function setUp()
    {
        $this->generalConfig = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\Config\General')
            ->disableOriginalConstructor()
            ->getMock();

        $this->storeRepository = $this->getMock('Magento\Store\Api\StoreRepositoryInterface');

        $this->websiteRepository = $this->getMock('Magento\Store\Api\WebsiteRepositoryInterface');

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->countryStoreMapping = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\PermanentRule\CountryStoreMapping',
            [
                'generalConfig' => $this->generalConfig,
                'storeRepository' => $this->storeRepository,
                'websiteRepository' => $this->websiteRepository
            ]
        );
    }

    public function testUpdateStoreIdDisabled()
    {
        $countryCode = 'UA';
        $storeId = 2;

        $this->generalConfig->expects($this->once())
            ->method('isMappingSore')
            ->willReturn(false);

        $this->storeRepository->expects($this->never())->method('get');
        $this->storeRepository->expects($this->never())->method('getById');

        $this->websiteRepository->expects($this->never())->method('getById');
        $this->websiteRepository->expects($this->never())->method('getDefault');

        $this->assertEquals($storeId, $this->countryStoreMapping->updateStoreId($storeId, $countryCode));
    }

    public function testUpdateStoreIdWithStoreIdFalse()
    {
        $countryCode = 'UA';
        $storeId = false;
        $websiteStoreCodes = ['us', 'uk', 'ua'];
        $updateStoreI = 5;

        $this->generalConfig->expects($this->once())
            ->method('isMappingSore')
            ->willReturn(true);


        $store = $this->getMockBuilder('Magento\Store\Api\Data\StoreInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($updateStoreI);

        $this->storeRepository->expects($this->never())->method('getById');
        $this->storeRepository->expects($this->once())
            ->method('get')
            ->with(strtolower($countryCode))
            ->willReturn($store);


        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->getMock();
        $website->expects($this->once())
            ->method('getStoreCodes')
            ->willReturn($websiteStoreCodes);

        $this->websiteRepository->expects($this->never())->method('getById');
        $this->websiteRepository->expects($this->once())
            ->method('getDefault')
            ->willReturn($website);

        $this->assertEquals($updateStoreI, $this->countryStoreMapping->updateStoreId($storeId, $countryCode));
    }

    public function testUpdateStoreIdWithStoreIdFalseAndNotFoundCountryStore()
    {
        $countryCode = 'UA';
        $storeId = false;
        $websiteStoreCodes = ['us', 'uk'];

        $this->generalConfig->expects($this->once())
            ->method('isMappingSore')
            ->willReturn(true);


        $this->storeRepository->expects($this->never())->method('get');
        $this->storeRepository->expects($this->never())->method('getById');


        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->getMock();
        $website->expects($this->once())
            ->method('getStoreCodes')
            ->willReturn($websiteStoreCodes);

        $this->websiteRepository->expects($this->never())->method('getById');
        $this->websiteRepository->expects($this->once())
            ->method('getDefault')
            ->willReturn($website);

        $this->assertEquals($storeId, $this->countryStoreMapping->updateStoreId($storeId, $countryCode));
    }

    public function testUpdateStoreId()
    {
        $countryCode = 'UA';
        $storeId = 2;
        $websiteStoreCodes = ['us', 'uk', 'ua'];
        $updateStoreI = 5;

        $websiteId = 11;

        $this->generalConfig->expects($this->once())
            ->method('isMappingSore')
            ->willReturn(true);


        $store = $this->getMockBuilder('Magento\Store\Api\Data\StoreInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $store->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);

        $this->storeRepository->expects($this->once())
            ->method('getById')
            ->willReturn($store);


        $updatedStore = $this->getMockBuilder('Magento\Store\Api\Data\StoreInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $updatedStore->expects($this->once())
            ->method('getId')
            ->willReturn($updateStoreI);

        $this->storeRepository->expects($this->once())
            ->method('get')
            ->with(strtolower($countryCode))
            ->willReturn($updatedStore);


        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->getMock();
        $website->expects($this->once())
            ->method('getStoreCodes')
            ->willReturn($websiteStoreCodes);

        $this->websiteRepository->expects($this->once())
            ->method('getById')
            ->with($websiteId)
            ->willReturn($website);
        $this->websiteRepository->expects($this->never())->method('getDefault');

        $this->assertEquals($updateStoreI, $this->countryStoreMapping->updateStoreId($storeId, $countryCode));
    }

    public function testUpdateStoreIdNotFoundCountryStore()
    {
        $countryCode = 'UA';
        $storeId = 2;
        $websiteStoreCodes = ['us', 'uk'];

        $websiteId = 11;

        $this->generalConfig->expects($this->once())
            ->method('isMappingSore')
            ->willReturn(true);


        $store = $this->getMockBuilder('Magento\Store\Api\Data\StoreInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $store->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($websiteId);

        $this->storeRepository->expects($this->once())
            ->method('getById')
            ->willReturn($store);
        $this->storeRepository->expects($this->never())->method('get');


        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->getMock();
        $website->expects($this->once())
            ->method('getStoreCodes')
            ->willReturn($websiteStoreCodes);

        $this->websiteRepository->expects($this->once())
            ->method('getById')
            ->with($websiteId)
            ->willReturn($website);
        $this->websiteRepository->expects($this->never())->method('getDefault');

        $this->assertEquals($storeId, $this->countryStoreMapping->updateStoreId($storeId, $countryCode));
    }
}

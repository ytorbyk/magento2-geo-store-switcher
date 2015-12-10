<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Plugin\Store;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class GeoWebsiteRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Plugin\Store\GeoWebsiteRepository
     */
    protected $geoWebsiteRepository;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $generalConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoWebsite|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $geoWebsite;

    protected function setUp()
    {
        $this->generalConfig = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\Config\General')
            ->disableOriginalConstructor()
            ->getMock();

        $this->geoWebsite = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\GeoWebsite')
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->geoWebsiteRepository = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Plugin\Store\GeoWebsiteRepository',
            [
                'generalConfig' => $this->generalConfig,
                'geoWebsite' => $this->geoWebsite
            ]
        );
    }

    public function testAfterGetDisabled()
    {
        $this->setGeneralConfigIsActive(false);

        $this->geoWebsite->expects($this->never())->method('getId');

        $website = $this->createWebsiteMock();
        $website->expects($this->never())->method('setIsDefault');

        $this->assertSame($website, $this->geoWebsiteRepository->afterGet($this->createWebsiteRepositoryMock(), $website));
    }

    public function testAfterGetWebsiteFalse()
    {
        $this->setGeneralConfigIsActive(true);

        $this->setGeoWebsiteId(false);

        $website = $this->createWebsiteMock();
        $website->expects($this->never())->method('setIsDefault');

        $this->assertSame($website, $this->geoWebsiteRepository->afterGet($this->createWebsiteRepositoryMock(), $website));
    }

    /**
     * @param int $geoWebsiteId
     * @param int $websiteId
     * @param bool $isDefault
     * @dataProvider afterGetDataProvider
     */
    public function testAfterGet($geoWebsiteId, $websiteId, $isDefault)
    {
        $this->setGeneralConfigIsActive(true);

        $this->setGeoWebsiteId($geoWebsiteId);

        $website = $this->createWebsiteMock($websiteId);
        $website->expects($this->once())
            ->method('setIsDefault')
            ->with($isDefault)
            ->willReturnSelf();

        $this->assertSame($website, $this->geoWebsiteRepository->afterGet($this->createWebsiteRepositoryMock(), $website));
    }

    /**
     * @return array
     */
    public function afterGetDataProvider()
    {
        return [
            ['geoWebsiteId' => 2, 'websiteId' => 5, 'isDefault' => false],
            ['geoWebsiteId' => 2, 'websiteId' => 2, 'isDefault' => true]
        ];
    }


    public function testAfterGetByIdDisabled()
    {
        $this->setGeneralConfigIsActive(false);

        $this->geoWebsite->expects($this->never())->method('getId');

        $website = $this->createWebsiteMock();
        $website->expects($this->never())->method('setIsDefault');

        $this->assertSame($website, $this->geoWebsiteRepository->afterGetById($this->createWebsiteRepositoryMock(), $website));
    }

    public function testAfterGetByIdWebsiteFalse()
    {
        $this->setGeneralConfigIsActive(true);

        $this->setGeoWebsiteId(false);

        $website = $this->createWebsiteMock();
        $website->expects($this->never())->method('setIsDefault');

        $this->assertSame($website, $this->geoWebsiteRepository->afterGetById($this->createWebsiteRepositoryMock(), $website));
    }

    /**
     * @param int $geoWebsiteId
     * @param int $websiteId
     * @param bool $isDefault
     * @dataProvider afterGetByIdDataProvider
     */
    public function testAfterGetById($geoWebsiteId, $websiteId, $isDefault)
    {
        $this->setGeneralConfigIsActive(true);

        $this->setGeoWebsiteId($geoWebsiteId);

        $website = $this->createWebsiteMock($websiteId);
        $website->expects($this->once())
            ->method('setIsDefault')
            ->with($isDefault)
            ->willReturnSelf();

        $this->assertSame($website, $this->geoWebsiteRepository->afterGetById($this->createWebsiteRepositoryMock(), $website));
    }

    /**
     * @return array
     */
    public function afterGetByIdDataProvider()
    {
        return [
            ['geoWebsiteId' => 1, 'websiteId' => 3, 'isDefault' => false],
            ['geoWebsiteId' => 4, 'websiteId' => 4, 'isDefault' => true]
        ];
    }


    public function testAfterGetListDisabled()
    {
        $this->setGeneralConfigIsActive(false);

        $this->geoWebsite->expects($this->never())->method('getId');

        $website1 = $this->createWebsiteMock();
        $website1->expects($this->never())->method('setIsDefault');

        $website2 = $this->createWebsiteMock();
        $website2->expects($this->never())->method('setIsDefault');

        $websiteList = [$website1, $website2];

        $this->assertSame($websiteList, $this->geoWebsiteRepository->afterGetList($this->createWebsiteRepositoryMock(), $websiteList));
    }

    public function testAfterGetListWebsiteFalse()
    {
        $this->setGeneralConfigIsActive(true);

        $this->setGeoWebsiteId(false);

        $website1 = $this->createWebsiteMock();
        $website1->expects($this->never())->method('setIsDefault');

        $website2 = $this->createWebsiteMock();
        $website2->expects($this->never())->method('setIsDefault');

        $websiteList = [$website1, $website2];

        $this->assertSame($websiteList, $this->geoWebsiteRepository->afterGetList($this->createWebsiteRepositoryMock(), $websiteList));
    }

    /**
     * @param int $geoWebsiteId
     * @param int $websiteId1
     * @param bool $isDefault1
     * @param int $websiteId2
     * @param bool $isDefault2
     * @dataProvider afterGetListDataProvider
     */
    public function testAfterGetList($geoWebsiteId, $websiteId1, $isDefault1, $websiteId2, $isDefault2)
    {
        $this->setGeneralConfigIsActive(true);

        $this->setGeoWebsiteId($geoWebsiteId);

        $website1 = $this->createWebsiteMock($websiteId1);
        $website1->expects($this->once())
            ->method('setIsDefault')
            ->with($isDefault1)
            ->willReturnSelf();

        $website2 = $this->createWebsiteMock($websiteId2);
        $website2->expects($this->once())
            ->method('setIsDefault')
            ->with($isDefault2)
            ->willReturnSelf();

        $websiteList = [$website1, $website2];

        $this->assertSame($websiteList, $this->geoWebsiteRepository->afterGetList($this->createWebsiteRepositoryMock(), $websiteList));
    }

    /**
     * @return array
     */
    public function afterGetListDataProvider()
    {
        return [
            ['geoWebsiteId' => 1, 'websiteId1' => 3, 'isDefault1' => false, 'websiteId2' => 1, 'isDefault2' => true],
            ['geoWebsiteId' => 4, 'websiteId1' => 4, 'isDefault1' => true, 'websiteId2' => 2, 'isDefault2' => false]
        ];
    }


    public function testAfterGetDefaultDisabled()
    {
        $this->setGeneralConfigIsActive(false);

        $this->geoWebsite->expects($this->never())->method('getId');

        $website = $this->createWebsiteMock();

        $websiteRepository = $this->createWebsiteRepositoryMock();
        $websiteRepository->expects($this->never())->method('getById');
        $this->assertSame($website, $this->geoWebsiteRepository->afterGetDefault($websiteRepository, $website));
    }

    public function testAfterGetDefaultWebsiteFalse()
    {
        $this->setGeneralConfigIsActive(true);

        $this->setGeoWebsiteId(false);

        $website = $this->createWebsiteMock();

        $websiteRepository = $this->createWebsiteRepositoryMock();
        $websiteRepository->expects($this->never())->method('getById');
        $this->assertSame($website, $this->geoWebsiteRepository->afterGetDefault($websiteRepository, $website));
    }

    public function testAfterGetDefaultEqual()
    {
        $geoWebsiteId = $websiteId = 2;

        $this->setGeneralConfigIsActive(true);

        $this->setGeoWebsiteId($geoWebsiteId);

        $website = $this->createWebsiteMock($websiteId);

        $websiteRepository = $this->createWebsiteRepositoryMock();
        $websiteRepository->expects($this->never())->method('getById');
        $this->assertSame($website, $this->geoWebsiteRepository->afterGetDefault($websiteRepository, $website));
    }

    public function testAfterGetDefaultNotEqual()
    {
        $geoWebsiteId = 2;
        $websiteId = 3;

        $this->setGeneralConfigIsActive(true);

        $this->setGeoWebsiteId($geoWebsiteId);

        $website = $this->createWebsiteMock($websiteId);

        $geoWebsite = $this->createWebsiteMock();

        $websiteRepository = $this->createWebsiteRepositoryMock();
        $websiteRepository->expects($this->once())
            ->method('getById')
            ->with($geoWebsiteId)
            ->willReturn($geoWebsite);

        $this->assertSame($geoWebsite, $this->geoWebsiteRepository->afterGetDefault($websiteRepository, $website));
    }


    protected function setGeneralConfigIsActive($isActive)
    {
        $this->generalConfig->expects($this->once())
            ->method('isActive')
            ->willReturn($isActive);

        return $this->generalConfig;
    }

    protected function setGeoWebsiteId($id)
    {
        $this->geoWebsite->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($id);

        return $this->geoWebsite;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Magento\Store\Model\WebsiteRepository
     */
    protected function createWebsiteRepositoryMock()
    {
        return $this->getMockBuilder('Magento\Store\Model\WebsiteRepository')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @param null $id
     * @return \PHPUnit_Framework_MockObject_MockObject|\Magento\Store\Model\Website
     */
    protected function createWebsiteMock($id = null)
    {
        $website = $this->getMockBuilder('Magento\Store\Model\Website')
            ->disableOriginalConstructor()
            ->setMethods(['getId', 'setIsDefault'])
            ->getMock();

        if ($id !== null) {
            $website->expects($this->once())
                ->method('getId')
                ->willReturn($id);
        } else {
            $website->expects($this->never())->method('getId');
        }

        return $website;
    }
}

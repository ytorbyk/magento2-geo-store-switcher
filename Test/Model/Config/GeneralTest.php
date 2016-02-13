<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\Config;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class GeneralTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General
     */
    protected $configGeneral;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeConfig|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Helper\Config\AppState|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $appStateHelper;

    /**
     * @var \Tobai\GeoStoreSwitcher\Helper\Config\Request|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestHelper;

    protected function setUp()
    {
        $this->scopeConfig = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\Config\ScopeConfig')
            ->disableOriginalConstructor()
            ->getMock();

        $this->appStateHelper = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Helper\Config\AppState')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestHelper = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Helper\Config\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->configGeneral = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\Config\General',
            [
                'scopeConfig' => $this->scopeConfig,
                'appStateHelper' => $this->appStateHelper,
                'requestHelper' => $this->requestHelper
            ]
        );
    }

    public function testSetOriginStore()
    {
        $store = $this->getMock('Magento\Store\Api\Data\StoreInterface');

        $this->scopeConfig->expects($this->once())
            ->method('setOriginStore')
            ->with($store)
            ->willReturnSelf();

        $this->assertSame($this->configGeneral, $this->configGeneral->setOriginStore($store));
    }

    public function testIsAvailableIsFrontendAreaFalse()
    {
        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(false);

        $this->requestHelper->expects($this->never())->method('isCurrentIp');
        $this->requestHelper->expects($this->never())->method('isCurrentUserAgent');
        $this->scopeConfig->expects($this->never())->method('getFrontendStoreOrBackendValue');

        $this->assertFalse($this->configGeneral->isAvailable());
    }

    public function testIsAvailableIsCurrentIpFalse()
    {
        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(true);

        $this->scopeConfig->expects($this->once())
            ->method('getStoreValue')
            ->with('tobai_geo_store_switcher/general/white_ips')
            ->willReturn('127.0.0.1, 10.0.0.1');
        $this->requestHelper->expects($this->once())
            ->method('isCurrentIp')
            ->with(['127.0.0.1', '10.0.0.1'])
            ->willReturn(true);
        $this->requestHelper->expects($this->never())->method('isCurrentUserAgent');
        $this->scopeConfig->expects($this->never())->method('getFrontendStoreOrBackendValue');

        $this->assertFalse($this->configGeneral->isAvailable());
    }

    public function testIsAvailableIsCurrentUserAgentFalse()
    {
        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(true);

        $this->scopeConfig->expects($this->any())
            ->method('getStoreValue')
            ->willReturnMap([
                ['tobai_geo_store_switcher/general/white_ips', '127.0.0.1, 10.0.0.1'],
                ['tobai_geo_store_switcher/general/white_ua', '/^mozilla/i'],
            ]);
        $this->requestHelper->expects($this->once())
            ->method('isCurrentIp')
            ->with(['127.0.0.1', '10.0.0.1'])
            ->willReturn(false);

        $this->requestHelper->expects($this->once())
            ->method('isCurrentUserAgent')
            ->with('/^mozilla/i')
            ->willReturn(true);
        $this->scopeConfig->expects($this->never())->method('getFrontendStoreOrBackendValue');

        $this->assertFalse($this->configGeneral->isAvailable());
    }

    public function testIsAvailableIsActiveFalse()
    {
        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(true);

        $this->scopeConfig->expects($this->any())
            ->method('getStoreValue')
            ->willReturnMap([
                ['tobai_geo_store_switcher/general/white_ips', '127.0.0.1, 10.0.0.1'],
                ['tobai_geo_store_switcher/general/white_ua', '/^mozilla/i'],
            ]);
        $this->requestHelper->expects($this->once())
            ->method('isCurrentIp')
            ->with(['127.0.0.1', '10.0.0.1'])
            ->willReturn(false);

        $this->requestHelper->expects($this->once())
            ->method('isCurrentUserAgent')
            ->with('/^mozilla/i')
            ->willReturn(false);

        $this->scopeConfig->expects($this->once())
            ->method('getFrontendStoreOrBackendValue')
            ->with('tobai_geo_store_switcher/general/active')
            ->willReturn(false);

        $this->assertFalse($this->configGeneral->isAvailable());
    }

    public function testIsAvailable()
    {
        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(true);

        $this->scopeConfig->expects($this->any())
            ->method('getStoreValue')
            ->willReturnMap([
                ['tobai_geo_store_switcher/general/white_ips', '127.0.0.1, 10.0.0.1'],
                ['tobai_geo_store_switcher/general/white_ua', '/^mozilla/i'],
            ]);
        $this->requestHelper->expects($this->once())
            ->method('isCurrentIp')
            ->with(['127.0.0.1', '10.0.0.1'])
            ->willReturn(false);

        $this->requestHelper->expects($this->once())
            ->method('isCurrentUserAgent')
            ->with('/^mozilla/i')
            ->willReturn(false);

        $this->scopeConfig->expects($this->once())
            ->method('getFrontendStoreOrBackendValue')
            ->with('tobai_geo_store_switcher/general/active')
            ->willReturn(true);

        $this->assertTrue($this->configGeneral->isAvailable());
    }

    public function testIsActive()
    {
        $isActive = 'value';

        $this->scopeConfig->expects($this->once())
            ->method('getFrontendStoreOrBackendValue')
            ->with('tobai_geo_store_switcher/general/active')
            ->willReturn($isActive);

        $this->assertEquals($isActive, $this->configGeneral->isActive());
    }

    /**
     * @param string $whiteIps
     * @param array $whiteIpsResult
     * @dataProvider getWhiteIpsDataProvider
     */
    public function testGetWhiteIps($whiteIps, $whiteIpsResult)
    {
        $this->scopeConfig->expects($this->once())
            ->method('getStoreValue')
            ->with('tobai_geo_store_switcher/general/white_ips')
            ->willReturn($whiteIps);

        $this->assertEquals($whiteIpsResult, $this->configGeneral->getWhiteIps());
    }

    /**
     * @return array
     */
    public function getWhiteIpsDataProvider()
    {
        return [
            ['', []],
            ['127.0.0.1', ['127.0.0.1']],
            ['127.0.0.1,10.0.0.1', ['127.0.0.1', '10.0.0.1']],
            ['127.0.0.1, 10.0.0.1', ['127.0.0.1', '10.0.0.1']]
        ];
    }

    public function testGetWhiteUa()
    {
        $whiteUa = 'value';

        $this->scopeConfig->expects($this->once())
            ->method('getStoreValue')
            ->with('tobai_geo_store_switcher/general/white_ua')
            ->willReturn($whiteUa);

        $this->assertEquals($whiteUa, $this->configGeneral->getWhiteUa());
    }

    public function testIsOverwriteDefault()
    {
        $overwriteDefault = 1;

        $this->scopeConfig->expects($this->once())
            ->method('getWebsiteValue')
            ->with('tobai_geo_store_switcher/general/overwrite_default')
            ->willReturn($overwriteDefault);

        $this->assertTrue($this->configGeneral->isOverwriteDefault());
    }

    public function testGetDefaultStoreDisabled()
    {
        $overwriteDefault = 0;

        $this->scopeConfig->expects($this->once())
            ->method('getWebsiteValue')
            ->with('tobai_geo_store_switcher/general/overwrite_default')
            ->willReturn($overwriteDefault);

        $this->assertFalse($this->configGeneral->getDefaultStore());
    }

    public function testGetDefaultStore()
    {
        $overwriteDefault = 1;
        $defaultStore = 12;

        $this->scopeConfig->expects($this->exactly(2))
            ->method('getWebsiteValue')
            ->willReturnMap([
                ['tobai_geo_store_switcher/general/overwrite_default', $overwriteDefault],
                ['tobai_geo_store_switcher/general/default_store', $defaultStore],
            ]);

        $this->assertEquals($defaultStore, $this->configGeneral->getDefaultStore());
    }

    public function testIsMappingSore()
    {
        $isMappingSore = 1;

        $this->scopeConfig->expects($this->once())
            ->method('getWebsiteValue')
            ->with('tobai_geo_store_switcher/general/mapping_sore')
            ->willReturn($isMappingSore);

        $this->assertTrue($this->configGeneral->isMappingSore());
    }

    public function testIsCountriesNotActive()
    {
        $isActive = false;
        $this->scopeConfig->expects($this->once())
            ->method('getFrontendStoreOrBackendValue')
            ->with('tobai_geo_store_switcher/general/active')
            ->willReturn($isActive);

        $this->assertFalse($this->configGeneral->isCountries());
    }

    public function testIsCountries()
    {
        $isActive = true;
        $byCountries = true;

        $this->scopeConfig->expects($this->once())
            ->method('getFrontendStoreOrBackendValue')
            ->with('tobai_geo_store_switcher/general/active')
            ->willReturn($isActive);

        $this->scopeConfig->expects($this->once())
            ->method('getFrontendWebsiteOrBackendValue')
            ->with('tobai_geo_store_switcher/general/by_countries')
            ->willReturn($byCountries);

        $this->assertTrue($this->configGeneral->isCountries());
    }

    /**
     * @param string $countriesData
     * @param array $countriesList
     * @dataProvider getCountryListDataProvider
     */
    public function testGetCountryList($countriesData, $countriesList)
    {
        $this->scopeConfig->expects($this->once())
            ->method('getFrontendStoreOrBackendValue')
            ->with('tobai_geo_store_switcher/general/active')
            ->willReturn(true);

        $this->scopeConfig->expects($this->any())
            ->method('getFrontendWebsiteOrBackendValue')
            ->willReturnMap([
                ['tobai_geo_store_switcher/general/by_countries', true],
                ['tobai_geo_store_switcher/general/country_list', $countriesData]
            ]);

        $this->assertEquals($countriesList, $this->configGeneral->getCountryList());
    }

    /**
     * @return array
     */
    public function getCountryListDataProvider()
    {
        return [
            ['', []],
            ['1', [1]],
            ['1,3,23', [1, 3, 23]],
        ];
    }

    public function testGetCountryStore()
    {
        $countryCode = 3;
        $countryStore = 2;

        $this->scopeConfig->expects($this->once())
            ->method('getWebsiteValue')
            ->with("tobai_geo_store_switcher/{$countryCode}/store")
            ->willReturn($countryStore);

        $this->assertEquals($countryStore, $this->configGeneral->getCountryStore($countryCode));
    }

    public function testGetGroupCountNotActive()
    {
        $isActive = false;
        $this->scopeConfig->expects($this->once())
            ->method('getFrontendStoreOrBackendValue')
            ->with('tobai_geo_store_switcher/general/active')
            ->willReturn($isActive);

        $this->assertEquals(0, $this->configGeneral->getGroupCount());
    }

    public function testGetGroupCount()
    {
        $isActive = true;
        $groupCount = 5;

        $this->scopeConfig->expects($this->once())
            ->method('getFrontendStoreOrBackendValue')
            ->with('tobai_geo_store_switcher/general/active')
            ->willReturn($isActive);

        $this->scopeConfig->expects($this->once())
            ->method('getFrontendWebsiteOrBackendValue')
            ->with('tobai_geo_store_switcher/general/by_groups')
            ->willReturn($groupCount);

        $this->assertEquals($groupCount, $this->configGeneral->getGroupCount());
    }

    /**
     * @param int $group
     * @param string $countriesData
     * @param array $countriesList
     * @dataProvider getGroupCountryListDataProvider
     */
    public function testGetGroupCountryList($group, $countriesData, $countriesList)
    {
        $this->scopeConfig->expects($this->any())
            ->method('getFrontendWebsiteOrBackendValue')
            ->with("tobai_geo_store_switcher/group_{$group}/country_list")
            ->willReturn($countriesData);

        $this->assertEquals($countriesList, $this->configGeneral->getGroupCountryList($group));
    }

    /**
     * @return array
     */
    public function getGroupCountryListDataProvider()
    {
        return [
            [2, '', []],
            [5, '1', [1]],
            [9, '1,3,23', [1, 3, 23]],
        ];
    }

    public function testGetGroupStore()
    {
        $group = 2;
        $countryStore = 12;

        $this->scopeConfig->expects($this->once())
            ->method('getWebsiteValue')
            ->with("tobai_geo_store_switcher/group_{$group}/store")
            ->willReturn($countryStore);

        $this->assertEquals($countryStore, $this->configGeneral->getGroupStore($group));
    }
}

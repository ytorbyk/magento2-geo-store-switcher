<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\Config;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ScopeConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeConfig
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $appScopeConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeDefiner;

    /**
     * @var \Tobai\GeoStoreSwitcher\Helper\Config\AppState|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $appStateHelper;

    /**
     * @var string
     */
    protected $storeId = 'store-id';

    /**
     * @var string
     */
    protected $websiteId = 'website-id';

    protected function setUp()
    {
        $this->appScopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface');

        $this->scopeDefiner = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner')
            ->disableOriginalConstructor()
            ->getMock();

        $this->appStateHelper = $this->getMockBuilder('Tobai\GeoStoreSwitcher\Helper\Config\AppState')
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->scopeConfig = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\Config\ScopeConfig',
            [
                'appScopeConfig' => $this->appScopeConfig,
                'scopeDefiner' => $this->scopeDefiner,
                'appStateHelper' => $this->appStateHelper
            ]
        );

        $store = $this->getMock('Magento\Store\Api\Data\StoreInterface');
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($this->storeId);
        $store->expects($this->once())
            ->method('getWebsiteId')
            ->willReturn($this->websiteId);
        $this->scopeConfig->setOriginStore($store);
    }

    public function testSetOriginStore()
    {
        $store = $this->getMock('Magento\Store\Api\Data\StoreInterface');
        $store->expects($this->never())->method('getId');
        $store->expects($this->never())->method('getWebsiteId');
        $this->scopeConfig->setOriginStore($store);
    }

    public function testGetDefaultValue()
    {
        $path = 'test-path';
        $value = 'value';

        $this->appScopeConfig->expects($this->once())
            ->method('getValue')
            ->with($path, ScopeConfigInterface::SCOPE_TYPE_DEFAULT, null)
            ->willReturn($value);

        $this->assertEquals($value, $this->scopeConfig->getDefaultValue($path));
    }

    public function testGetStoreValue()
    {
        $path = 'test-path';
        $value = 'value';

        $this->appScopeConfig->expects($this->once())
            ->method('getValue')
            ->with($path, ScopeInterface::SCOPE_STORE, $this->storeId)
            ->willReturn($value);

        $this->assertEquals($value, $this->scopeConfig->getStoreValue($path));
    }

    public function testGetFrontendStoreOrBackendValueFrontend()
    {
        $path = 'test-path';
        $value = 'value';

        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(true);

        $this->appScopeConfig->expects($this->once())
            ->method('getValue')
            ->with($path, ScopeInterface::SCOPE_STORE, $this->storeId)
            ->willReturn($value);

        $this->assertEquals($value, $this->scopeConfig->getFrontendStoreOrBackendValue($path));
    }

    public function testGetFrontendStoreOrBackendValueBackend()
    {
        $scope = 'some-scope';
        $scopeValue = 'some-scope-value';
        $path = 'test-path';
        $value = 'value';

        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(false);

        $this->scopeDefiner->expects($this->once())
            ->method('getScope')
            ->willReturn($scope);
        $this->scopeDefiner->expects($this->once())
            ->method('getScopeValue')
            ->willReturn($scopeValue);

        $this->appScopeConfig->expects($this->once())
            ->method('getValue')
            ->with($path, $scope, $scopeValue)
            ->willReturn($value);

        $this->assertEquals($value, $this->scopeConfig->getFrontendStoreOrBackendValue($path));
    }

    public function testGetWebsiteValue()
    {
        $path = 'test-path';
        $value = 'value';

        $this->appScopeConfig->expects($this->once())
            ->method('getValue')
            ->with($path, ScopeInterface::SCOPE_WEBSITE, $this->websiteId)
            ->willReturn($value);

        $this->assertEquals($value, $this->scopeConfig->getWebsiteValue($path));
    }

    public function testGetFrontendWebsiteOrBackendValueFrontend()
    {
        $path = 'test-path';
        $value = 'value';

        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(true);

        $this->appScopeConfig->expects($this->once())
            ->method('getValue')
            ->with($path, ScopeInterface::SCOPE_WEBSITE, $this->websiteId)
            ->willReturn($value);

        $this->assertEquals($value, $this->scopeConfig->getFrontendWebsiteOrBackendValue($path));
    }

    public function testGetFrontendWebsiteOrBackendValueBackend()
    {
        $scope = 'some-scope';
        $scopeValue = 'some-scope-value';
        $path = 'test-path';
        $value = 'value';

        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(false);

        $this->scopeDefiner->expects($this->atLeastOnce())
            ->method('getScope')
            ->willReturn($scope);
        $this->scopeDefiner->expects($this->once())
            ->method('getScopeValue')
            ->willReturn($scopeValue);

        $this->appScopeConfig->expects($this->once())
            ->method('getValue')
            ->with($path, $scope, $scopeValue)
            ->willReturn($value);

        $this->assertEquals($value, $this->scopeConfig->getFrontendWebsiteOrBackendValue($path));
    }

    public function testGetFrontendWebsiteOrBackendValueBackendStoreScope()
    {
        $path = 'test-path';

        $this->appStateHelper->expects($this->once())
            ->method('isFrontendArea')
            ->willReturn(false);

        $this->scopeDefiner->expects($this->atLeastOnce())
            ->method('getScope')
            ->willReturn(ScopeInterface::SCOPE_STORE);
        $this->scopeDefiner->expects($this->never())->method('getScopeValue');

        $this->appScopeConfig->expects($this->never())->method('getValue');

        $this->assertEmpty($this->scopeConfig->getFrontendWebsiteOrBackendValue($path));
    }
}

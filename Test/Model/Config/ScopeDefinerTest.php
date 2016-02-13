<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\Config;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class ScopeDefinerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner
     */
    protected $scopeDefiner;

    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $request;

    protected function setUp()
    {
        $this->request = $this->getMock('Magento\Framework\App\RequestInterface');

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->scopeDefiner = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\Config\ScopeDefiner',
            [
                'request' => $this->request
            ]
        );
    }

    public function testGetScopeValueStore()
    {
        $storeValue = 'store-value';

        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturnMap([
                ['store', null, $storeValue],
                ['store', null, $storeValue]
            ]);

        $this->assertEquals($storeValue, $this->scopeDefiner->getScopeValue());
    }

    public function testGetScopeValueWebsite()
    {
        $websiteValue = 'website-value';

        $this->request->expects($this->any())
            ->method('getParam')
            ->willReturnMap([
                ['store', null, null],
                ['website', null, $websiteValue],
                ['website', null, $websiteValue]
            ]);

        $this->assertEquals($websiteValue, $this->scopeDefiner->getScopeValue());
    }
}

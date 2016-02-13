<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Helper\Config;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;
use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;

class AppStateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Helper\Config\AppState
     */
    protected $appState;

    /**
     * @var \Magento\Framework\App\State|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $state;

    protected function setUp()
    {
        $this->state = $this->getMockBuilder('Magento\Framework\App\State')
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->appState = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Helper\Config\AppState',
            [
                'state' => $this->state
            ]
        );
    }

    /**
     * @param string $area
     * @param bool $isFrontend
     * @dataProvider isFrontendAreaDataProvider
     */
    public function testIsFrontendArea($area, $isFrontend)
    {
        $this->state->expects($this->atLeastOnce())
            ->method('getAreaCode')
            ->willReturn($area);

        $this->assertEquals($isFrontend, $this->appState->isFrontendArea());
    }

    /**
     * @return array
     */
    public function isFrontendAreaDataProvider()
    {
        return [
            [Area::AREA_GLOBAL, true],
            [Area::AREA_FRONTEND, true],
            [Area::AREA_ADMIN, true],
            [Area::AREA_ADMINHTML, false],
            [Area::AREA_DOC, true],
            [Area::AREA_CRONTAB, true],
            [Area::AREA_WEBAPI_REST, true],
            [Area::AREA_WEBAPI_SOAP, true],
        ];
    }

    public function testIsFrontendAreaException()
    {
        $this->state->expects($this->atLeastOnce())
            ->method('getAreaCode')
            ->willThrowException(new LocalizedException(__('error')));

        $this->assertFalse($this->appState->isFrontendArea());
    }
}

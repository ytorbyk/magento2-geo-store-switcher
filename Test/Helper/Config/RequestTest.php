<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Helper\Config;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Helper\Config\Request
     */
    protected $request;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $remoteAddress;

    /**
     * @var \Magento\Framework\HTTP\Header|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpHeader;

    protected function setUp()
    {
        $this->remoteAddress = $this->getMockBuilder('Magento\Framework\HTTP\PhpEnvironment\RemoteAddress')
            ->disableOriginalConstructor()
            ->getMock();

        $this->httpHeader = $this->getMockBuilder('Magento\Framework\HTTP\Header')
            ->disableOriginalConstructor()
            ->getMock();

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->request = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Helper\Config\Request',
            [
                'remoteAddress' => $this->remoteAddress,
                'httpHeader' => $this->httpHeader
            ]
        );
    }

    /**
     * @param [] $whiteIps
     * @param string $remoteIp
     * @param bool $isCurrentIp
     * @dataProvider isCurrentIpDataProvider
     */
    public function testIsCurrentIp($whiteIps, $remoteIp, $isCurrentIp)
    {
        $this->remoteAddress->expects($this->atLeastOnce())
            ->method('getRemoteAddress')
            ->willReturn($remoteIp);

        $this->assertEquals($isCurrentIp, $this->request->isCurrentIp($whiteIps));
    }

    /**
     * @return array
     */
    public function isCurrentIpDataProvider()
    {
        return [
            ['whiteIps' => [], 'remoteIp' => '', 'isCurrentIp' => false],
            ['whiteIps' => ['127.0.0.1'], 'remoteIp' => '', 'isCurrentIp' => false],
            ['whiteIps' => ['10.0.0.1'], 'remoteIp' => '127.0.0.1', 'isCurrentIp' => false],
            ['whiteIps' => ['10.0.0.1', '127.0.0.1'], 'remoteIp' => '127.0.0.1', 'isCurrentIp' => true]
        ];
    }

    /**
     * @param $uaRegex
     * @param $currentUserAgent
     * @param $isCurrentUserAgent
     * @dataProvider isCurrentUserAgentDataProvider
     */
    public function testIsCurrentUserAgent($uaRegex, $currentUserAgent, $isCurrentUserAgent)
    {
        $this->httpHeader->expects($this->any())
            ->method('getHttpUserAgent')
            ->willReturn($currentUserAgent);

        $this->assertEquals($isCurrentUserAgent, $this->request->isCurrentUserAgent($uaRegex));
    }

    /**
     * @return array
     */
    public function isCurrentUserAgentDataProvider()
    {
        return [
            ['uaRegex' => '', 'currentUserAgent' => 'Mozilla browser', 'isCurrentUserAgent' => false],
            ['uaRegex' => '/^mozilla/i', 'currentUserAgent' => '', 'isCurrentUserAgent' => false],
            ['uaRegex' => '/^mozilla/i', 'currentUserAgent' => 'Some browser', 'isCurrentUserAgent' => false],
            ['uaRegex' => '/^mozilla/i', 'currentUserAgent' => 'Mozilla browser', 'isCurrentUserAgent' => true],
            ['uaRegex' => '/^mozillai', 'currentUserAgent' => 'Mozilla browser', 'isCurrentUserAgent' => false]
        ];
    }
}

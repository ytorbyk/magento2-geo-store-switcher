<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Helper\Config;

class Request
{
    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddress;

    /**
     * @var \Magento\Framework\HTTP\Header
     */
    protected $httpHeader;

    /**
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress
     * @param \Magento\Framework\HTTP\Header $httpHeader
     */
    public function __construct(
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Magento\Framework\HTTP\Header $httpHeader
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->httpHeader = $httpHeader;
    }

    /**
     * @param array $whiteIps
     * @return bool
     */
    public function isCurrentIp($whiteIps)
    {
        $remoteIp = $this->remoteAddress->getRemoteAddress();
        return !empty($whiteIps) && !empty($remoteIp) && array_search($remoteIp, $whiteIps) !== false;
    }

    /**
     * @param string $uaRegex
     * @return bool
     */
    public function isCurrentUserAgent($uaRegex)
    {
        return $uaRegex && @preg_match($uaRegex, $this->httpHeader->getHttpUserAgent());
    }
}

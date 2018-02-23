<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Plugin\PageCache;

class GeoIdentifier
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General
     */
    private $generalConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher
     */
    private $storeSwitcher;

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $storeSwitcher
     */
    public function __construct(
        \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig,
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $storeSwitcher
    ) {
        $this->generalConfig = $generalConfig;
        $this->storeSwitcher = $storeSwitcher;
    }

    /**
     * Adds a theme key to identifier for a built-in cache if user-agent theme rule is actual
     *
     * @param \Magento\Framework\App\PageCache\Identifier $identifier
     * @param string $result
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetValue(\Magento\Framework\App\PageCache\Identifier $identifier, $result)
    {
        if ($this->generalConfig->isAvailable() && $this->storeSwitcher->getCurrentStoreId()) {
            $result .= $this->storeSwitcher->getCurrentStoreId();
        }
        return $result;
    }
}

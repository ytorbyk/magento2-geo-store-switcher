<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Helper\Config;

use Magento\Framework\App\Area;
use Magento\Framework\Exception\LocalizedException;

class AppState
{
    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @param \Magento\Framework\App\State $state
     */
    public function __construct(\Magento\Framework\App\State $state) {
        $this->state = $state;
    }

    /**
     * @return bool
     */
    public function isFrontendArea()
    {
        try {
            if ($this->state->getAreaCode() == Area::AREA_ADMINHTML) {
                return false;
            }
        } catch (LocalizedException $e) {
            /* Area is not initialized. Do nothing. */
            return true;
        }
        return true;
    }
}

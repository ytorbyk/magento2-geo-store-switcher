<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config;

class ScopeDefiner extends \Magento\Config\Model\Config\ScopeDefiner
{
    /**
     * @return int|null
     */
    public function getScopeValue()
    {
        return $this->_request->getParam($this->getScope());
    }
}

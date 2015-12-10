<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\Source;

use Magento\Store;

class Website implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $store;

    /**
     * @param \Magento\Store\Model\System\Store $store
     */
    public function __construct(
        Store\Model\System\Store $store
    ) {
        $this->store = $store;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->store->getWebsiteValuesForForm(true, false);
    }
}

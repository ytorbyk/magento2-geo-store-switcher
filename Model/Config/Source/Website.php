<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\Source;

class Website implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    private $store;

    /**
     * @param \Magento\Store\Model\System\Store $store
     */
    public function __construct(
        \Magento\Store\Model\System\Store $store
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

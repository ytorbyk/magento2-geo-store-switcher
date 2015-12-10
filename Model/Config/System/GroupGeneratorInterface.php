<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\System;

interface GroupGeneratorInterface
{
    /**
     * @param int $sortOrder
     * @return array
     */
    public function generate(&$sortOrder = 1);
}

<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
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

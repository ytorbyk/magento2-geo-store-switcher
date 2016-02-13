<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\System;

use Magento\Framework\ObjectManagerInterface;
use Tobai\GeoStoreSwitcher\Model;

class GroupGeneratorFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     *
     * @param string $className
     * @param array $data
     * @return \Tobai\GeoStoreSwitcher\Model\Config\System\GroupGeneratorInterface
     * @throws \InvalidArgumentException
     */
    public function create($className, array $data = [])
    {
        $generator = $this->objectManager->create($className, $data);
        if (!$generator instanceof Model\Config\System\GroupGeneratorInterface) {
            throw new \InvalidArgumentException(
                $className . ' doesn\'t implement \Tobai\GeoStoreSwitcher\Model\Config\System\GroupGeneratorInterface'
            );
        }
        return $generator;
    }
}

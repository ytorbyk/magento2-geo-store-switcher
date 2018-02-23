<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\System;

class GroupGeneratorFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
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
        if (!$generator instanceof GroupGeneratorInterface) {
            throw new \InvalidArgumentException($className . ' doesn\'t implement ' . GroupGeneratorInterface::class);
        }
        return $generator;
    }
}

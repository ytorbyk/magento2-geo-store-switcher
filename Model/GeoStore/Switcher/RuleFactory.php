<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher;

use Magento\Framework\ObjectManagerInterface;
use Tobai\GeoStoreSwitcher\Model;

class RuleFactory
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
     * @return \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface
     * @throws \InvalidArgumentException
     */
    public function create($className, array $data = [])
    {
        $rule = $this->objectManager->create($className, $data);
        if (!$rule instanceof Model\GeoStore\Switcher\RuleInterface) {
            throw new \InvalidArgumentException(
                $className . ' doesn\'t implement \Tobai\GeoStoreSwitcher\Model\Store\Switcher\RuleInterface'
            );
        }
        return $rule;
    }
}

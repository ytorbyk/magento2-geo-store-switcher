<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher;

use Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface as SwitcherRuleInterface;

class RuleFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $className
     * @param array $data
     * @return \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher\RuleInterface
     * @throws \InvalidArgumentException
     */
    public function create($className, array $data = [])
    {
        $rule = $this->objectManager->create($className, $data);
        if (!$rule instanceof SwitcherRuleInterface) {
            throw new \InvalidArgumentException($className . ' doesn\'t implement ' . SwitcherRuleInterface::class);
        }
        return $rule;
    }
}

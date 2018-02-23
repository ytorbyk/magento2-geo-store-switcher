<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\System;

class GroupGeneratorPool implements GroupGeneratorInterface
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\System\GroupGeneratorFactory
     */
    private $generatorFactory;

    /**
     * @var array
     */
    private $generators = [];

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\Config\System\GroupGeneratorFactory $generatorFactory
     * @param array $generators
     */
    public function __construct(GroupGeneratorFactory $generatorFactory, array $generators)
    {
        $this->generatorFactory = $generatorFactory;
        $this->generators = $generators;
    }

    /**
     * @param int $starSortOrder
     * @return array
     */
    public function generate(&$starSortOrder = 1)
    {
        $configurationGroups = [];
        foreach ($this->generators as $generatorClass) {
            $generator = $this->generatorFactory->create($generatorClass);
            $configurationGroups += $generator->generate($starSortOrder);
        }
        return $configurationGroups;
    }
}

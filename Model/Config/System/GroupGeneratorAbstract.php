<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\System;

use Tobai\GeoStoreSwitcher\Model;
use Magento\Config\Model\Config\Structure\Element\FlyweightFactory;

class GroupGeneratorAbstract
{
    /**
     * @var \Magento\Config\Model\Config\Structure\Element\FlyweightFactory
     */
    protected $flyweightFactory;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General
     */
    protected $generalConfig;

    /**
     * @param \Magento\Config\Model\Config\Structure\Element\FlyweightFactory $flyweightFactory
     * @param \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig
     */
    public function __construct(
        FlyweightFactory $flyweightFactory,
        Model\Config\General $generalConfig
    ) {
        $this->flyweightFactory = $flyweightFactory;
        $this->generalConfig = $generalConfig;
    }

    /**
     * @param array $groupData
     * @param string $scope
     * @return \Magento\Config\Model\Config\Structure\Element\Group
     */
    protected function createGroup(array $groupData, $scope = 'default')
    {
        $group = $this->flyweightFactory->create('group');
        $group->setData($groupData, $scope);
        return $group;
    }
}

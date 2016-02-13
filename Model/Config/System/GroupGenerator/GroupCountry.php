<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\System\GroupGenerator;

use Tobai\GeoStoreSwitcher\Model\Config\System;

class GroupCountry extends System\GroupGeneratorAbstract implements System\GroupGeneratorInterface
{
    /**
     * @param int $sortOrder
     * @return array
     */
    public function generate(&$sortOrder = 1)
    {
        $groupCount = $this->generalConfig->getGroupCount();
        $groupGroups = [];
        for ($group = 1; $group <= $groupCount; $group++) {
            $groupId = 'group_' . $group;
            $groupData = [
                '_elementType' => 'group',
                'id' => $groupId,
                'label' => (string)__('Group #%1 Configuration', $group),
                'path' => 'tobai_geo_store_switcher',
                'showInDefault' => '1',
                'showInWebsite' => '1',
                'showInStore' => '0',
                'sortOrder' => $sortOrder++,
                'type' => 'text',
                'children' => [
                    'country_list' => [
                        '_elementType' => 'field',
                        'id' => 'country_list',
                        'label' => (string)__('Choose countries'),
                        'path' => 'tobai_geo_store_switcher/' . $groupId,
                        'showInDefault' => '1',
                        'showInWebsite' => '1',
                        'showInStore' => '0',
                        'sortOrder' => '1',
                        'source_model' => 'Magento\Directory\Model\Config\Source\Country\Full',
                        'type' => 'multiselect',
                        'depends' => [
                            'fields' => [
                                'active' => [
                                    '_elementType' => 'field',
                                    'id' => 'tobai_geo_store_switcher/general/active',
                                    'value' => '1',
                                    'dependPath' => [
                                        'tobai_geo_store_switcher',
                                        'general',
                                        'active'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'store' => [
                        '_elementType' => 'field',
                        'id' => 'store',
                        'label' => (string)__('Set Store View'),
                        'path' => 'tobai_geo_store_switcher/' . $groupId,
                        'showInDefault' => '1',
                        'showInWebsite' => '1',
                        'showInStore' => '0',
                        'sortOrder' => '1',
                        'source_model' => 'Tobai\GeoStoreSwitcher\Model\Config\Source\Store',
                        'type' => 'select',
                        'depends' => [
                            'fields' => [
                                'active' => [
                                    '_elementType' => 'field',
                                    'id' => 'tobai_geo_store_switcher/general/active',
                                    'value' => '1',
                                    'dependPath' => [
                                        'tobai_geo_store_switcher',
                                        'general',
                                        'active'
                                    ]
                                ],
                                'country_list' => [
                                    '_elementType' => 'field',
                                    'id' => "tobai_geo_store_switcher/{$groupId}/country_list",
                                    'negative' => 'negative',
                                    'value' => '',
                                    'dependPath' => [
                                        'tobai_geo_store_switcher',
                                        $groupId,
                                        'country_list'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            $groupGroups[$groupId] = $this->createGroup($groupData);
        }
        return $groupGroups;
    }
}

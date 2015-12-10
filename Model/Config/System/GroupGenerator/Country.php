<?php
/**
 * Copyright © 2015 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Model\Config\System\GroupGenerator;

use Magento\Config\Model\Config\Structure\Element\FlyweightFactory;
use Magento\Directory\Model\CountryFactory;
use Tobai\GeoStoreSwitcher\Model;
use Tobai\GeoStoreSwitcher\Model\Config\System;

class Country extends System\GroupGeneratorAbstract implements System\GroupGeneratorInterface
{
    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $countryFactory;

    /**
     * @param \Magento\Config\Model\Config\Structure\Element\FlyweightFactory $flyweightFactory
     * @param \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     */
    public function __construct(
        FlyweightFactory $flyweightFactory,
        Model\Config\General $generalConfig,
        CountryFactory $countryFactory
    ) {
        $this->countryFactory = $countryFactory;
        parent::__construct($flyweightFactory, $generalConfig);
    }


    /**
     * @param int $sortOrder
     * @return array
     */
    public function generate(&$sortOrder = 1)
    {
        $countries = $this->generalConfig->getCountryList();
        $countriesGroups = [];
        foreach ($countries as $countryCode) {
            $groupData = [
                '_elementType' => 'group',
                'id' => $countryCode,
                'label' => $this->getCountry($countryCode)->getName(),
                'path' => 'tobai_geo_store_switcher',
                'showInDefault' => '1',
                'showInStore' => '0',
                'showInWebsite' => '0',
                'sortOrder' => $sortOrder++,
                'type' => 'text',
                'children' => [
                    'store' => [
                        '_elementType' => 'field',
                        'id' => 'store',
                        'label' => (string)__('Set Store View'),
                        'path' => 'tobai_geo_store_switcher/' . $countryCode,
                        'showInDefault' => '1',
                        'showInStore' => '0',
                        'showInWebsite' => '0',
                        'sortOrder' => '1',
                        'source_model' => 'Magento\Store\Model\System\Store',
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
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            $countriesGroups[$countryCode] = $this->createGroup($groupData);
        }
        return $countriesGroups;
    }

    /**
     * @param string $countryCode
     * @return \Magento\Directory\Model\Country
     */
    protected function getCountry($countryCode)
    {
        return $this->countryFactory->create()->loadByCode($countryCode);
    }
}

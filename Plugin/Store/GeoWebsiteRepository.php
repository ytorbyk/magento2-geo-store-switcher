<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Plugin\Store;

use Magento\Store\Model\WebsiteRepository;
use Magento\Store\Model\Website;

class GeoWebsiteRepository
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\General
     */
    protected $generalConfig;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoWebsite
     */
    protected $geoWebsite;

    /**
     * @var \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher
     */
    protected $storeSwitcher;

    /**
     * @param \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig
     * @param \Tobai\GeoStoreSwitcher\Model\GeoWebsite $geoWebsite
     * @param \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $storeSwitcher
     */
    public function __construct(
        \Tobai\GeoStoreSwitcher\Model\Config\General $generalConfig,
        \Tobai\GeoStoreSwitcher\Model\GeoWebsite $geoWebsite,
        \Tobai\GeoStoreSwitcher\Model\GeoStore\Switcher $storeSwitcher
    ) {
        $this->generalConfig = $generalConfig;
        $this->geoWebsite = $geoWebsite;
        $this->storeSwitcher = $storeSwitcher;
    }

    /**
     * @return bool
     */
    protected function isNeededProcess()
    {
        return $this->storeSwitcher->isInitialized()
            && $this->generalConfig->isAvailable()
            && $this->geoWebsite->getId() !== false;
    }

    /**
     * @param \Magento\Store\Model\WebsiteRepository $subject
     * @param \Magento\Store\Model\Website $website
     * @return \Magento\Store\Model\Website
     */
    public function afterGet(WebsiteRepository $subject, Website $website)
    {
        if ($this->isNeededProcess()) {
            $website->setIsDefault($this->geoWebsite->getId() == $website->getId());
        }
        return $website;
    }

    /**
     * @param \Magento\Store\Model\WebsiteRepository $subject
     * @param \Magento\Store\Model\Website $website
     * @return \Magento\Store\Model\Website
     */
    public function afterGetById(WebsiteRepository $subject, Website $website)
    {
        if ($this->isNeededProcess()) {
            $website->setIsDefault($this->geoWebsite->getId() == $website->getId());
        }
        return $website;
    }

    /**
     * @param WebsiteRepository $subject
     * @param \Magento\Store\Model\Website[] $websiteList
     * @return \Magento\Store\Model\Website[]
     */
    public function afterGetList(WebsiteRepository $subject, array $websiteList)
    {
        if ($this->isNeededProcess()) {
            foreach ($websiteList as $website) {
                $website->setIsDefault($this->geoWebsite->getId() == $website->getId());
            }
        }
        return $websiteList;
    }

    /**
     * @param \Magento\Store\Model\WebsiteRepository $subject
     * @param \Magento\Store\Model\Website $website
     * @return \Magento\Store\Model\Website
     */
    public function afterGetDefault(WebsiteRepository $subject, Website $website)
    {
        if (!$this->isNeededProcess()) {
            return $website;
        }

        if ($this->geoWebsite->getId() != $website->getId()) {
            $website = $subject->getById($this->geoWebsite->getId());
        }
        return $website;
    }
}

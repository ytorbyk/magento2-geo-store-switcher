<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */
namespace Tobai\GeoStoreSwitcher\Block\Adminhtml\System\Config;

class Form extends \Magento\Config\Block\System\Config\Form
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\System\GroupGeneratorInterface
     */
    private $groupGenerator;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Config\Model\Config\Factory $configFactory
     * @param \Magento\Config\Model\Config\Structure $configStructure
     * @param \Magento\Config\Block\System\Config\Form\Fieldset\Factory $fieldsetFactory
     * @param \Magento\Config\Block\System\Config\Form\Field\Factory $fieldFactory
     * @param \Tobai\GeoStoreSwitcher\Model\Config\System\GroupGeneratorInterface $groupGenerator
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Factory $configFactory,
        \Magento\Config\Model\Config\Structure $configStructure,
        \Magento\Config\Block\System\Config\Form\Fieldset\Factory $fieldsetFactory,
        \Magento\Config\Block\System\Config\Form\Field\Factory $fieldFactory,
        \Tobai\GeoStoreSwitcher\Model\Config\System\GroupGeneratorInterface $groupGenerator,
        array $data = []
    ) {
        $this->groupGenerator = $groupGenerator;
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $configFactory,
            $configStructure,
            $fieldsetFactory,
            $fieldFactory,
            $data
        );
    }

    /**
     * Initialize form
     *
     * @return $this
     */
    public function initForm()
    {
        $this->_initObjects();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        /** @var $section \Magento\Config\Model\Config\Structure\Element\Section */
        $section = $this->_configStructure->getElement($this->getSectionCode());
        if ($section && $section->isVisible($this->getWebsiteCode(), $this->getStoreCode())) {
            foreach ($section->getChildren() as $group) {
                $this->_initGroup($group, $section, $form);
            }
            $sortOrder = isset($group) && $group->getAttribute('sortOrder') ? $group->getAttribute('sortOrder') : 1;
            $sortOrder++;
            foreach ($this->groupGenerator->generate($sortOrder) as $group) {
                $this->_initGroup($group, $section, $form);
            }
        }

        $this->setForm($form);
        return $this;
    }
}

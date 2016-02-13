<?php
/**
 * Copyright © 2016 ToBai. All rights reserved.
 */

namespace Tobai\GeoStoreSwitcher\Test\Model\Config\Backend;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class RegexpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Tobai\GeoStoreSwitcher\Model\Config\Backend\Regexp
     */
    protected $regexp;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $messageManager;

    protected function setUp()
    {
        $this->messageManager = $this->getMock('Magento\Framework\Message\ManagerInterface');

        $objectManagerHelper = new ObjectManagerHelper($this);
        $this->regexp = $objectManagerHelper->getObject(
            'Tobai\GeoStoreSwitcher\Model\Config\Backend\Regexp',
            [
                'messageManager' => $this->messageManager
            ]
        );
    }

    public function testBeforeSave()
    {
        $value = '/^mozillai/i';

        $this->regexp->setValue($value);

        $this->messageManager->expects($this->never())->method('addNotice');

        $this->regexp->beforeSave();
        $this->assertEquals($value, $this->regexp->getValue());
    }

    public function testBeforeSaveFail()
    {
        $wrongValue = '/^mozillai';

        $this->regexp->setValue($wrongValue);

        $this->messageManager->expects($this->once())
            ->method('addNotice')
            ->with(__('Invalid regular expression: %value', ['value' => $wrongValue]));

        $this->regexp->beforeSave();
        $this->assertNull($this->regexp->getValue());
    }
}

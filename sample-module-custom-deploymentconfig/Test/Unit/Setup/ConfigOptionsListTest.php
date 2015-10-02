<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomDeploymentConfigExample\Test\Unit\Setup;

use Magento\CustomDeploymentConfigExample\Setup\ConfigOptionsList;

class ConfigOptionsListTest extends \PHPUnit_Framework_TestCase
{
    public function testGetOptions()
    {
        $configOptionsList = new ConfigOptionsList();
        $this->assertInstanceOf('Magento\Framework\Setup\Option\TextConfigOption', $configOptionsList->getOptions()[0]);
    }

    public function testCreateConfig()
    {
        $deploymentConfig = $this->getMock('Magento\Framework\App\DeploymentConfig', [], [], '', false);
        $configOptionsList = new ConfigOptionsList();
        $data = $configOptionsList->createConfig(
            [ConfigOptionsList::INPUT_KEY_CUSTOM_OPTION => 'value'],
            $deploymentConfig
        );
        $this->assertEquals(['example' => ['custom-option' => 'value']], $data[0]->getData());
    }

    public function testCreateConfigNoValue()
    {
        $deploymentConfig = $this->getMock('Magento\Framework\App\DeploymentConfig', [], [], '', false);
        $configOptionsList = new ConfigOptionsList();
        $data = $configOptionsList->createConfig([], $deploymentConfig);
        $this->assertEquals([], $data[0]->getData());
    }

    public function testValidate()
    {
        $deploymentConfig = $this->getMock('Magento\Framework\App\DeploymentConfig', [], [], '', false);
        $configOptionsList = new ConfigOptionsList();
        $this->assertEquals(
            [],
            $configOptionsList->validate([ConfigOptionsList::INPUT_KEY_CUSTOM_OPTION => 'value'], $deploymentConfig)
        );
    }

    public function testValidateInvalid()
    {
        $deploymentConfig = $this->getMock('Magento\Framework\App\DeploymentConfig', [], [], '', false);
        $configOptionsList = new ConfigOptionsList();
        $this->assertEquals(
            ['Invalid custom option value'],
            $configOptionsList->validate([ConfigOptionsList::INPUT_KEY_CUSTOM_OPTION => 'invalid'], $deploymentConfig)
        );
    }
}

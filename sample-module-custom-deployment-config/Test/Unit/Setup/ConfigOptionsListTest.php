<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomDeploymentConfigExample\Test\Unit\Setup;

use Magento\CustomDeploymentConfigExample\Setup\ConfigOptionsList;

class ConfigOptionsListTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigOptionsList
     */
    private $configOptionsList;

    /**
     * @var \Magento\Framework\App\DeploymentConfig|\PHPUnit_Framework_MockObject_MockObject
     */
    private $deploymentConfigMock;

    public function setUp()
    {
        $this->configOptionsList = new ConfigOptionsList();
        $this->deploymentConfigMock = $this->getMock('Magento\Framework\App\DeploymentConfig', [], [], '', false);
    }

    public function testGetOptions()
    {
        $this->assertInstanceOf(
            'Magento\Framework\Setup\Option\TextConfigOption',
            $this->configOptionsList->getOptions()[0]
        );
    }

    public function testCreateConfig()
    {
        $data = $this->configOptionsList->createConfig(
            [ConfigOptionsList::INPUT_KEY_CUSTOM_OPTION => 'value'],
            $this->deploymentConfigMock
        );
        $this->assertCount(1, $data);
        $this->assertEquals(['example' => ['custom-option' => 'value']], $data[0]->getData());
    }

    public function testCreateConfigNoValue()
    {
        $this->deploymentConfigMock->expects($this->once())
            ->method('get')
            ->with(ConfigOptionsList::CONFIG_PATH_CUSTOM_OPTION)
            ->willReturn('some value');
        $data = $this->configOptionsList->createConfig([], $this->deploymentConfigMock);
        $this->assertEquals([], $data[0]->getData());
    }

    public function testCreateConfigNoValueDefault()
    {
        $this->deploymentConfigMock->expects($this->once())
            ->method('get')
            ->with(ConfigOptionsList::CONFIG_PATH_CUSTOM_OPTION)
            ->willReturn(null);
        $data = $this->configOptionsList->createConfig([], $this->deploymentConfigMock);
        $this->assertEquals(['example' => ['custom-option' => 'default custom value']], $data[0]->getData());
    }

    public function testValidate()
    {
        $configOptionsList = new ConfigOptionsList();
        $this->assertEquals(
            [],
            $configOptionsList->validate(
                [ConfigOptionsList::INPUT_KEY_CUSTOM_OPTION => 'value'],
                $this->deploymentConfigMock
            )
        );
    }

    public function testValidateInvalid()
    {
        $configOptionsList = new ConfigOptionsList();
        $this->assertEquals(
            ['Invalid custom option value'],
            $configOptionsList->validate(
                [ConfigOptionsList::INPUT_KEY_CUSTOM_OPTION => 'invalid'],
                $this->deploymentConfigMock
            )
        );
    }
}

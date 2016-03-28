<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomDeploymentConfigExample\Test\Unit\Console\Command;

use Magento\CustomDeploymentConfigExample\Console\Command\ShowCustomDeploymentConfigCommand;
use Magento\CustomDeploymentConfigExample\Setup\ConfigOptionsList;
use Symfony\Component\Console\Tester\CommandTester;

class ShowCustomDeploymentConfigCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\App\DeploymentConfig|\PHPUnit_Framework_MockObject_MockObject
     */
    private $deploymentConfigMock;

    /**
     * @var CommandTester|\PHPUnit_Framework_MockObject_MockObject
     */
    private $commandTester;

    public function setUp()
    {
        $this->deploymentConfigMock = $this->getMock('Magento\Framework\App\DeploymentConfig', [], [], '', false);
        $command = new ShowCustomDeploymentConfigCommand($this->deploymentConfigMock);
        $this->commandTester = new CommandTester($command);
    }

    public function testExecute()
    {

        $this->deploymentConfigMock->expects($this->once())
            ->method('get')
            ->with(ConfigOptionsList::CONFIG_PATH_CUSTOM_OPTION)
            ->willReturn('value');

        $this->commandTester->execute([]);
        $this->assertContains('value', $this->commandTester->getDisplay());
    }

    public function testExecuteNoValue()
    {
        $this->deploymentConfigMock->expects($this->once())
            ->method('get')
            ->with(ConfigOptionsList::CONFIG_PATH_CUSTOM_OPTION)
            ->willReturn(null);

        $this->commandTester->execute([]);
        $this->assertContains('is not set', $this->commandTester->getDisplay());
    }
}

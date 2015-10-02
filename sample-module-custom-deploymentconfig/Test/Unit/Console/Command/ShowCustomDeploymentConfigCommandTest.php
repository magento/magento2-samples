<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomDeploymentConfigExample\Test\Unit\Console\Command;

use Magento\CustomDeploymentConfigExample\Console\Command\ShowCustomDeploymentConfigCommand;
use Magento\CustomDeploymentConfigExample\Setup\ConfigOptionsList;
use Symfony\Component\Console\Tester\CommandTester;

class ShowCustomDeploymentConfigCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $deploymentConfigMock = $this->getMock('Magento\Framework\App\DeploymentConfig', [], [], '', false);
        $deploymentConfigMock->expects($this->once())
            ->method('get')
            ->with(ConfigOptionsList::CONFIG_PATH_CUSTOM_OPTION)
            ->willReturn('value');
        $command = new ShowCustomDeploymentConfigCommand($deploymentConfigMock);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $this->assertContains('value', $commandTester->getDisplay());
    }

    public function testExecuteNoValue()
    {
        $deploymentConfigMock = $this->getMock('Magento\Framework\App\DeploymentConfig', [], [], '', false);
        $deploymentConfigMock->expects($this->once())
            ->method('get')
            ->with(ConfigOptionsList::CONFIG_PATH_CUSTOM_OPTION)
            ->willReturn(null);
        $command = new ShowCustomDeploymentConfigCommand($deploymentConfigMock);
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);
        $this->assertContains('is not set', $commandTester->getDisplay());
    }
}

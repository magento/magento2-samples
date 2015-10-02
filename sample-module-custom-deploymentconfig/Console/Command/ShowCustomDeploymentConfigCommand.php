<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CustomDeploymentConfigExample\Console\Command;

use Magento\CustomDeploymentConfigExample\Setup\ConfigOptionsList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ShowCustomDeploymentConfigCommand
 */
class ShowCustomDeploymentConfigCommand extends Command
{
    /**
     * @var \Magento\Framework\App\DeploymentConfig
     */
    private $deploymentConfig;

    public function __construct(\Magento\Framework\App\DeploymentConfig $deploymentConfig)
    {
        $this->deploymentConfig = $deploymentConfig;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('example:show-custom-deployment-config')
            ->setDescription('Show custom deployment configuration option');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $value = $this->deploymentConfig->get(ConfigOptionsList::CONFIG_PATH_CUSTOM_OPTION);
        if ($value) {
            $output->writeln(
                '<info>The custom deployment configuration value is ' . $value  . '</info>'
            );
        } else {
            $output->writeln('<info>The custom deployment configuration value is not yet set.</info>');
        }
    }
}

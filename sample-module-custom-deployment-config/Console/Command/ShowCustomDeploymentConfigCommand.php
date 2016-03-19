<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
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
 * Command to show the custom option set in the deployment configuration
 */
class ShowCustomDeploymentConfigCommand extends Command
{
    /**
     * @var \Magento\Framework\App\DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\DeploymentConfig $deploymentConfig
     */
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
        $this->setName('example:custom-deployment-config:show')
            ->setDescription('Shows custom deployment configuration option');

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
            $output->writeln('<info>The custom deployment configuration value is not set.</info>');
        }
    }
}

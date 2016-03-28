<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CommandExample\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\Module\ModuleListInterface;

class CheckActiveModulesCommand extends Command
{
    /**
     * Module list
     *
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @param ModuleListInterface $moduleList
     */
    public function __construct(ModuleListInterface $moduleList)
    {
        $this->moduleList = $moduleList;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('example:modules:check-active')
            ->setDescription('Checks application status (installed or not)');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>List of active modules:<info>');
        foreach ($this->moduleList->getNames() as $moduleName) {
            $output->writeln('<info>' . $moduleName . '<info>');
        }
    }
}

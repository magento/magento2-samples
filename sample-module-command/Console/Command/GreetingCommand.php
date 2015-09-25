<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\CommandExample\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GreetingCommand
 */
class GreetingCommand extends Command
{
    const NAME_ARGUMENT = 'name';
    const ALLOW_ANONYMOUS = 'allow-anonymous';

    const ANONYMOUS = 'Anonymous';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('example:greeting')
            ->setDescription('Greeting command')
            ->setDefinition([
                new InputArgument(
                    self::NAME_ARGUMENT,
                    InputArgument::OPTIONAL,
                    'Name'
                ),
                new InputOption(
                    self::ALLOW_ANONYMOUS,
                    '-a',
                    InputOption::VALUE_NONE,
                    'Allow anonymous'
                ),

            ]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument(self::NAME_ARGUMENT);
        $allowAnonymous = $input->getOption(self::ALLOW_ANONYMOUS);
        if (is_null($name) && !$allowAnonymous) {
            throw new \InvalidArgumentException('Argument:' . self::NAME_ARGUMENT . ' missed.');
        }
        if ($allowAnonymous) {
            $name = self::ANONYMOUS;
        }
        $output->writeln('<info>Hello ' . $name . '!</info>');
    }
}

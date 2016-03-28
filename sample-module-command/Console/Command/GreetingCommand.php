<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
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
    /**
     * Name argument
     */
    const NAME_ARGUMENT = 'name';

    /**
     * Allow option
     */
    const ALLOW_ANONYMOUS = 'allow-anonymous';

    /**
     * Anonymous name
     */
    const ANONYMOUS_NAME = 'Anonymous';

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
        if (is_null($name)) {
            if ($allowAnonymous) {
                $name = self::ANONYMOUS_NAME;
            } else {
                throw new \InvalidArgumentException('Argument ' . self::NAME_ARGUMENT . ' is missing.');
            }
        }
        $output->writeln('<info>Hello ' . $name . '!</info>');
    }
}

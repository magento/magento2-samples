## Synopsis

This module contains few commands under example: section.

## Motivation

This is one of a collection of examples to demonstrate the features of Magento 2.
The intent of this sample is to demonstrate how to create Magento Cli commands.

## Technical feature

CLI tool is a script on php which is executable from the CLI. It will be located under %root_dir%/bin/ and called magento.
In order to run a script, user will make it executable and type a path to it in the shell
> cd %magento_root%
> ./bin/magento

CLI tool itself will not have any commands other than default things like "help".
All the commands will be provided by the modules which are register them through the mechanism of registration provided by a tool.

Module commands always rely on Magento application and will need to have access to its context, dependency injections, plugins etc.
Also module command should be implemented in scope of particular module and depends on the module status.
Command can use Object Manager and Magento DI features, for example it can use injection via constructor.

## Adding Command And Registration Mechanism

* Create Command class (recomended <module_dir>/Console/Command/) which represents Command in CLI extends base class (Symfony\Component\Console\Command\Command) and overrides 2 protected methods:

    class CacheClearCommand extends Command
    {
        $cacheModel;

        public function __construct(CacheModel $cacheModel)
        {
            $this->cacheModel = $cacheModel;
            parent::__construct();
        }

        protected function configure()
        {
            $this->setName('cache:clear')
                ->setDescription('Clear cache');
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $this->cacheModel->clearCache();
            $output->writeln('Cache is cleared.');
        }
    }
* Declare command class in Magento\Framework\Console\CommandList using DI (<module_dir>/etc/di.xml).
    <type name="Magento\Framework\Console\CommandList">
        <arguments>
            <argument name="commands" xsi:type="array">
                <item name="cache_cleaner" xsi:type="object">Magento\Cache\Console\Command\CacheClearCommand</item>
            </argument>
        </arguments>
    </type>
* Clear application cache

* run "php <path to magento app>/bin/magento list" to make sure that command is present

## Tests

Unit tests could be found in the [Test/Unit](Test/Unit) directory.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)

#### Contents
*   <a href="#syn">Synopsis</a>
*   <a href="#over">Overview</a>
*   <a href="#install">Install the sample module</a>
*   <a href="#add-register">Add and register the command</a>
*   <a href="#tests">Tests</a>
*   <a href="#contrib">Contributors</a>
*   <a href="#lic">License</a>


<h2 id="syn">Synopsis</h2>

This sample module creates two new command-line commands:

*   `magento example:modules:check-active` (displays the list of enabled modules)
*   `magento example:greeting` (displays a greeting)

You can use this sample module as an example to create your own custom commands.

<h2 id="over">Overview</h2>
As with all other Magento command-line utilities, you run this sample command from the `<your Magento install dir>/bin` directory. For more information, see the <a href="http://devdocs.magento.com/guides/v2.0/install-gde/install/cli/install-cli.html#instgde-install-cli-first" target="_blank">Magento Installation Guide</a>.

Before you begin, make sure you understand the following:

*   All Magento command-line interface (CLI) commands rely on the Magento application and must have access to its context, dependency injections, plugins, and so on.
*   All CLI commands should be implemented in the scope of your module and should depend on the module's status.
*   Your command can use the Object Manager and Magento dependency injection features; for example, it can use <a href="http://devdocs.magento.com/guides/v2.0/extension-dev-guide/depend-inj.html#dep-inj-preview-cons" target="_blank">constructor dependency injection</a>.
*   You must register your command as discussed in <a href="#add-register">Add and register the command</a>.

<h2 id="install">Install the sample module</h2>
You'll find it useful to install this sample module so you can refer to it when you're coding your own custom commands. If you'd prefer not to, continue with <a href="#add-register">Add and register the command</a>.

**Note**: Following is one way to install the sample modules. With the release of Magento 2.0, you'll also be able to install modules using the Magento Marketplaces.

### Clone the magento2-samples repository
Clone the <a href="https://github.com/magento/magento2-samples" target="_blank">magento2-samples</a> repository using either the HTTPS or SSH protocols. 

### Copy the code
Create a directory for the sample module and copy `magento2-samples/sample-module-command` to it:

    mkdir -p <your Magento install dir>/app/code/Magento/CommandExample
    cp -R <magento2-samples clone dir>/sample-module-command/* <your Magento install dir>/app/code/Magento/CommandExample

### Update the Magento database and schema
If you added the module to an existing Magento installation, run the following command:

    php <your Magento install dir>/bin/magento setup:upgrade

### Verify the module is installed
Enter the following command:

    php <your Magento install dir>/bin/magento --list

The following confirms you installed the module correctly:

    example
        example:modules:check-active              Checks application status (installed or not)
        example:greeting                          Greeting command

<h2 id="add-register">Add and register the command</h2>
To add the command and register it:

1.  Create a Command class (the recommended location is `<module_dir>/Console/Command`).

    This class represents each of your module's CLI commands. (See `app/code/Magento/CommandExample/Console/Command` for examples.)

    Your Command class extends base class (`Symfony\Component\Console\Command\Command`) and overrides two protected methods:

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

2.  Declare your Command class in `Magento\Framework\Console\CommandList` using dependency injection (`<module_dir>/etc/di.xml`):

        <type name="Magento\Framework\Console\CommandList">
            <arguments>
                <argument name="commands" xsi:type="array">
                    <item name="check_active_status_command" xsi:type="object">Magento\CommandExample\Console\Command\CheckActiveModulesCommand</item>
                </argument>
            </arguments>
        </type>

2.  Clean the cache and compiled code directories:

        cd <your Magento install dir>/var
        rm -rf cache/* page_cache/* di/* generation/* 

<h2 id="tests">Tests</h2>

Unit tests can be found in the [Test/Unit](Test/Unit) directory.

<h2 id="contrib">Contributors</h2>

Magento Core team

<h2 id="lic">License</h2>

[Open Source License](LICENSE.txt)

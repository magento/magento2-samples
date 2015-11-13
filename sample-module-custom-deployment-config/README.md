#### Contents
*   <a href="#syn">Synopsis</a>
*   <a href="#over">Overview</a>
*   <a href="#install">Install the sample module</a>
*   <a href="#add-options">Add custom options to deployment configuration</a>
*   <a href="#tests">Tests</a>
*   <a href="#contrib">Contributors</a>
*   <a href="#lic">License</a>

<h2 id="syn">Synopsis</h2>
This module contains a `ConfigOptionsList` class which adds a custom option to the deployment configuration.
It also contains one command-line command named `example:custom-deployment-config:show` that enables you to display custom options you added to the Magento deployment configuration.

Creating custom options is discussed in <a href="#add-options">Add custom options to <code>setup:config:set</code></a>.

<h2 id="over">Overview</h2>
Modules can store their custom configuration in the Magento deployment configuration and later retrieve from it.
`setup:config:set` is a command for managing Magento deployment configuration.
Custom options also display in the `setup:install` command, allowing the user to specify custom configuration during installation.

<h2 id="install">Install the sample module</h2>
You'll find it useful to install this sample module so you can refer to it when you're coding your own custom commands. If you'd prefer not to, continue with <a href="#add-options">Add custom options to the deployment configuration</a>.

### Clone the magento2-samples repository
Clone the <a href="https://github.com/magento/magento2-samples" target="_blank">magento2-samples</a> repository using either the HTTPS or SSH protocols. 

### Copy the code
Create a directory for the sample module and copy `magento2-samples/sample-module-custom-deployment-config` to it:

    mkdir -p <your Magento install dir>/app/code/Magento/CustomDeploymentConfigExample
    cp -R <magento2-samples clone dir>/sample-module-custom-deployment-config/* <your Magento install dir>/app/code/Magento/CustomDeploymentConfigExample

### Update the Magento database and schema
If you added the module to an existing Magento installation, run the following command:

    php <your Magento install dir>/bin/magento setup:upgrade

### Verify the module is installed
Enter the following command:

    php <your Magento install dir>/bin/magento --list

The following confirms you installed the module correctly:

    example
         example:custom-deployment-config:show     Show custom deployment configuration option

### Command usage
To use the sample command:

	cd <your Magento install dir>/bin
	php magento example:custom-deployment-config:show
	php magento setup:config:set --help

`magento example:custom-deployment-config:show` displays the value assigned to the custom option defined in `Magento\CustomDeploymentConfigExample\Setup\ConfigOptionsList` while `magento setup:config:set --help` displays all options available for set.

<h2 id="add-options">Add custom options to the deployment configuration</h2>
To add custom options to the deployment configuration:

1.	Create class `ConfigOptionsList` in `<module_dir>/Setup` that implements
`Magento\Framework\Setup\ConfigOptionsListInterface`

2.	Implement required methods:

	* `getOptions()`: Returns list of custom options that should be added to the deployment configuration
	* `createConfigData()`: Creates the required array structure to be stored in the deployment configuration
	* `validate()`: Validates option values

3.	Clean the Magento cache.

4.	Run `php <path to Magento root>/bin/magento setup:config:set --help` to make sure the custom option is present.

## Tests

Unit tests could be found in the [Test/Unit](Test/Unit) directory.

## Contributors

Magento Core team

## License

[Open Source License](LICENSE.txt)

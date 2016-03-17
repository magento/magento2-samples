<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomDeploymentConfigExample\Setup;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\Data\ConfigData;
use Magento\Framework\Config\File\ConfigFilePool;
use Magento\Framework\Setup\ConfigOptionsListInterface;
use Magento\Framework\Setup\Option\TextConfigOption;

/**
 * This class adds the custom option to the deployment configuration.
 * A DeploymentConfig object is available to be used when creating config data and validating option values.
 */
class ConfigOptionsList implements ConfigOptionsListInterface
{
    /**
     * Name of custom option in setup:config:set command
     */
    const INPUT_KEY_CUSTOM_OPTION = 'custom-option';

    /**
     * Path to the custom configuration in deployment configuration.
     * This path will be used to retrieve the option value
     */
    const CONFIG_PATH_CUSTOM_OPTION = 'example/custom-option';

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return [
            new TextConfigOption(
                self::INPUT_KEY_CUSTOM_OPTION,
                TextConfigOption::FRONTEND_WIZARD_TEXT,
                self::CONFIG_PATH_CUSTOM_OPTION,
                'Custom deployment config option'
            )
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createConfig(array $options, DeploymentConfig $deploymentConfig)
    {
        $configData = new ConfigData(ConfigFilePool::APP_CONFIG);
        if (isset($options[self::INPUT_KEY_CUSTOM_OPTION])) {
            $configData->set(self::CONFIG_PATH_CUSTOM_OPTION, $options[self::INPUT_KEY_CUSTOM_OPTION]);
        } elseif ($deploymentConfig->get(self::CONFIG_PATH_CUSTOM_OPTION) === null) {
            // set to default value if it is not already set in deployment configuration
            $configData->set(self::CONFIG_PATH_CUSTOM_OPTION, 'default custom value');
        }
        return [$configData];
    }

    /**
     * Suppress warning added because we are not using DeploymentConfig to validate user input here
     *
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate(array $options, DeploymentConfig $deploymentConfig)
    {
        $errors = [];
        if (isset($options[self::INPUT_KEY_CUSTOM_OPTION])
            && $options[self::INPUT_KEY_CUSTOM_OPTION] === 'invalid'
        ) {
            $errors[] = 'Invalid custom option value';
        }
        return $errors;
    }
}

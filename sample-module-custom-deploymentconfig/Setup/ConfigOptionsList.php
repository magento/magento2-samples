<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CustomDeploymentConfigExample\Setup;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Config\Data\ConfigData;
use Magento\Framework\Config\File\ConfigFilePool;
use Magento\Framework\Setup\ConfigOptionsListInterface;
use Magento\Framework\Setup\Option\TextConfigOption;

class ConfigOptionsList implements ConfigOptionsListInterface
{
    const INPUT_KEY_CUSTOM_OPTION = 'custom-option';

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
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function createConfig(array $options, DeploymentConfig $deploymentConfig)
    {
        $configData = new ConfigData(ConfigFilePool::APP_CONFIG);
        if (isset($options[self::INPUT_KEY_CUSTOM_OPTION])) {
            $configData->set(self::CONFIG_PATH_CUSTOM_OPTION, $options[self::INPUT_KEY_CUSTOM_OPTION]);
        }
        return [$configData];
    }

    /**
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

<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Plugin;

use Magento\SampleInterception\Model\Intercepted\ChildAfter;

class PluginAfter
{
    /**
     * Wraps the input to the base method in (after)(/after) tags
     *
     * The after plugin returns what will be seen as the output of the base method.
     *
     * @param ChildAfter $subject
     * @param string $interceptedOutput
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterBaseMethodUppercase(ChildAfter $subject, $interceptedOutput)
    {
        return "(after) $interceptedOutput (/after)";
    }
}
<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Plugin;

use Magento\SampleInterception\Model\Intercepted\ChildBefore;

class PluginBefore
{
    /**
     * Wraps the input to the base method in (before)(/before) tags.
     *
     * The before plugin returns an array, which will be the array of arguments given to the base method.
     *
     * @param ChildBefore $subject
     * @param string $interceptedInput
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeBaseMethodUppercase(ChildBefore $subject, $interceptedInput)
    {
        return ["(before) $interceptedInput (/before)"];
    }



}
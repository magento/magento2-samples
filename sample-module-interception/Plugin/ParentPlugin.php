<?php
/***
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Plugin;

use Magento\SampleInterception\Model\Intercepted\ChildInherit;

class ParentPlugin
{
    /**
     * Wraps the input to the base method in tags to indicate that the plugin was called
     *
     * @param ChildInherit $subject
     * @param string $interceptedOutput
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */

    public function afterBaseMethodReverse(ChildInherit $subject, $interceptedOutput)
    {
        return "(parent plugin) $interceptedOutput (/parent plugin)";
    }
}

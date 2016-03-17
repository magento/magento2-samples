<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Model;

/**
 * Several classes extend this class, and they have plugins assigned to them. Even though the method is defined in the
 * parent, a plugin for "baseMethodUppercase" can be assigned to a child, and will be called when the method is called through
 * the child.
 *
 * The before, after, and around plugins defined in this module do not affect the method at the same time. Each
 * only affects the base method when that method is called via the child class to which the plugin is assigned.
 *
 * A plugin defined on this class also affects its children. A plugin for this class modifies "baseMethodReverse" so
 * when that method is called through children, it will be modified.
 */
class Intercepted
{
    /**
     * Return capitalized string
     *
     * @param string $inString
     * @return string
     */
    public function baseMethodUppercase($inString)
    {
        return strtoupper($inString);
    }

    /**
     * Return reversed string
     *
     * @param string $inString
     * @return string
     */
    public function baseMethodReverse($inString)
    {
        return strrev($inString);
    }
}

<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SampleInterception\Model;

/**
 * This class has no plugins directly assigned to it. But three classes inherit from it, and they have plugins assigned
 * to them. Even though the method is defined in the parent, a plugin for "baseMethod" can be assigned to a child,
 * and will be called when the method is called through the child.
 *
 * The before, after, and around plugins defined in this module do not affect the method at the same time. Each
 * only affects the base method when that method is called via the child class to which the plugin is assigned.
 */
class Intercepted
{
    /**
     * Return capitalized string
     *
     * @param string $inString
     *
     * @return string
     */
    public function baseMethod($inString)
    {
        return strtoupper($inString);
    }
}
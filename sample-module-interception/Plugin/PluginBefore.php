<?php
namespace M2Demo\PluginDemo\Plugin;
use M2Demo\PluginDemo\Helper\Intercepted\ChildBefore;

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
    public function beforeBaseMethod(ChildBefore $subject, $interceptedInput)
    {
        return ["(before) $interceptedInput (/before)"];
    }



}
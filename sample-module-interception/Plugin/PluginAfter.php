<?php
namespace M2Demo\PluginDemo\Plugin;
use M2Demo\PluginDemo\Helper\Intercepted\ChildAfter;

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
    public function afterBaseMethod(ChildAfter $subject, $interceptedOutput)
    {
        return "(after) $interceptedOutput (/after)";
    }
}
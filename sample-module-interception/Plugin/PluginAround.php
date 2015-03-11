<?php
namespace M2Demo\PluginDemo\Plugin;
use M2Demo\PluginDemo\Helper\Intercepted\ChildAround;

class PluginAround
{
    /**
     * 1) Wraps the input to the base method in (around: before helper)(/around: before helper) tags
     * 2) Wraps the output of the base method in (around: after helper)(/around: after helper) tags
     *
     * The base method capitalizes strings, so the "before base" tags will be affected, and the "after base"
     * tags will not.
     *
     * The after plugin receives all of the base method's arguments, calls the base method via closure,
     * and returns what will be seen as the output of the base method.
     *
     * @param ChildAround $subject
     * @param callable $proceed
     * @param string $interceptedInput
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundBaseMethod(ChildAround $subject, \Closure $proceed, $interceptedInput)
    {
        $argument = "(around: before helper) $interceptedInput (/around: before helper)";
        $result = $proceed($argument);
        return "(around: after helper) $result (/around: after helper)";
    }
}
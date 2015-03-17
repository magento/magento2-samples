<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleInterception\Block;

/**
 * A container for the Block class's dependencies. This Context adds three new dependencies on top of its parent.
 * They are the helper classes used by the block and intercepted by the plugins.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Context extends \Magento\Framework\View\Element\Template\Context
{
    /** @var  \Magento\SampleInterception\Helper\Intercepted\ChildBefore */
    protected $helperBefore;

    /** @var  \Magento\SampleInterception\Helper\Intercepted\ChildAfter */
    protected $helperAfter;

    /** @var  \Magento\SampleInterception\Helper\Intercepted\ChildAround */
    protected $helperAround;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\View\LayoutInterface $layout
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\TranslateInterface $translator
     * @param \Magento\Framework\App\CacheInterface $cache
     * @param \Magento\Framework\View\DesignInterface $design
     * @param \Magento\Framework\Session\SessionManagerInterface $session
     * @param \Magento\Framework\Session\SidResolverInterface $sidResolver
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\View\ConfigInterface $viewConfig
     * @param \Magento\Framework\App\Cache\StateInterface $cacheState
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\Filter\FilterManager $filterManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\View\FileSystem $viewFileSystem
     * @param \Magento\Framework\View\TemplateEnginePool $enginePool
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\View\Page\Config $pageConfig
     * @param \Magento\SampleInterception\Helper\Intercepted\ChildBefore $helperBefore
     * @param \Magento\SampleInterception\Helper\Intercepted\ChildAfter $helperAfter
     * @param \Magento\SampleInterception\Helper\Intercepted\ChildAround $helperAround
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\TranslateInterface $translator,
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Framework\View\DesignInterface $design,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Framework\Session\SidResolverInterface $sidResolver,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\ConfigInterface $viewConfig,
        \Magento\Framework\App\Cache\StateInterface $cacheState,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Filter\FilterManager $filterManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\View\FileSystem $viewFileSystem,
        \Magento\Framework\View\TemplateEnginePool $enginePool,
        \Magento\Framework\App\State $appState,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Page\Config $pageConfig,
        \Magento\SampleInterception\Helper\Intercepted\ChildBefore $helperBefore,
        \Magento\SampleInterception\Helper\Intercepted\ChildAfter $helperAfter,
        \Magento\SampleInterception\Helper\Intercepted\ChildAround $helperAround
    ) {
        parent::__construct(
            $request,
            $layout,
            $eventManager,
            $urlBuilder,
            $translator,
            $cache,
            $design,
            $session,
            $sidResolver,
            $scopeConfig,
            $assetRepo,
            $viewConfig,
            $cacheState,
            $logger,
            $escaper,
            $filterManager,
            $localeDate,
            $inlineTranslation,
            $filesystem,
            $viewFileSystem,
            $enginePool,
            $appState,
            $storeManager,
            $pageConfig
        );
        $this->helperBefore = $helperBefore;
        $this->helperAfter = $helperAfter;
        $this->helperAround = $helperAround;
    }

    /**
     * @return \Magento\SampleInterception\Helper\Intercepted\ChildBefore
     */
    public function getHelperBefore()
    {
        return $this->helperBefore;
    }

    /**
     * @return \Magento\SampleInterception\Helper\Intercepted\ChildAfter
     */
    public function getHelperAfter()
    {
        return $this->helperAfter;
    }

    /**
     * @return \Magento\SampleInterception\Helper\Intercepted\ChildAround
     */
    public function getHelperAround()
    {
        return $this->helperAround;
    }
}

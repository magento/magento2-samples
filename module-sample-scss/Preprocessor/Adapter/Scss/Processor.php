<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleScss\Preprocessor\Adapter\Scss;

use Psr\Log\LoggerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\View\Asset\File;
use Magento\Framework\View\Asset\Source;
use Magento\Framework\View\Asset\ContentProcessorInterface;

/**
 * Class Processor
 */
class Processor implements ContentProcessorInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Source
     */
    private $assetSource;

    /**
     * Constructor
     *
     * @param Source $assetSource
     * @param LoggerInterface $logger
     */
    public function __construct(Source $assetSource, LoggerInterface $logger)
    {
        $this->assetSource = $assetSource;
        $this->logger = $logger;
    }

    /**
     * Process file content
     *
     * @param File $asset
     * @return string
     */
    public function processContent(File $asset)
    {
        $path = $asset->getPath();
        try {
            $compiler = new \scssc();
            $content = $this->assetSource->getContent($asset);

            if (trim($content) === '') {
                return '';
            }

            return $compiler->compile($content);
        } catch (\Exception $e) {
            $errorMessage = PHP_EOL . self::ERROR_MESSAGE_PREFIX . PHP_EOL . $path . PHP_EOL . $e->getMessage();
            $this->logger->critical($errorMessage);

            return $errorMessage;
        }
    }
}

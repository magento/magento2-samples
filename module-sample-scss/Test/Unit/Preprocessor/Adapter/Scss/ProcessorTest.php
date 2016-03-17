<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SampleScss\Test\Unit\Preprocessor\Adapter\Scss;

use Psr\Log\LoggerInterface;
use Magento\Framework\Phrase;
use Magento\Framework\View\Asset\File;
use Magento\Framework\View\Asset\Source;
use Magento\SampleScss\Preprocessor\Adapter\Scss\Processor;

/**
 * Class ProcessorTest
 *
 * @see \Magento\SampleScss\Preprocessor\Adapter\Scss\Processor
 */
class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    const TEST_FILE = '/_files/test.scss';

    const RESULT_FILE = '/_files/result.css';

    const TEST_PATH = 'test/path/to/file';

    const TEST_EXCEPTION_MESSAGES = 'Test exception messages';

    /**
     * Run test for processContent method
     */
    public function testProcessContent()
    {
        $fileMock = $this->getFileMock();
        $loggerMock = $this->getLoggerMock();
        $assetSourceMock = $this->getSourceMock();

        $loggerMock->expects(self::never())
            ->method('critical');

        $assetSourceMock->expects(self::once())
            ->method('getContent')
            ->with($fileMock)
            ->willReturn(file_get_contents(__DIR__ . self::TEST_FILE));

        $processor = new Processor($assetSourceMock, $loggerMock);

        $content = $processor->processContent($fileMock);

        $search = [' ', "\t", "\n", "\r", "\0", "\x0B"];
        $expectedContent = 'h1{color:#009a82;margin:0;padding:0;}#container{width:460px;margin:0pxauto;}';

        self::assertEquals($expectedContent, str_replace($search, '', $content));
    }

    /**
     * Run test for processContent method (Exception)
     */
    public function testProcessContentException()
    {
        $fileMock = $this->getFileMock();
        $loggerMock = $this->getLoggerMock();
        $assetSourceMock = $this->getSourceMock();

        $message = PHP_EOL
            . Processor::ERROR_MESSAGE_PREFIX . PHP_EOL
            . self::TEST_PATH . PHP_EOL
            . self::TEST_EXCEPTION_MESSAGES;

        $loggerMock->expects(self::once())
            ->method('critical')
            ->with($message);

        $assetSourceMock->expects(self::once())
            ->method('getContent')
            ->with($fileMock)
            ->willThrowException(new \Exception(self::TEST_EXCEPTION_MESSAGES));

        $processor = new Processor($assetSourceMock, $loggerMock);

        $content = $processor->processContent($fileMock);

        self::assertEquals($message, $content);
    }

    /**
     * @return LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getLoggerMock()
    {
        $loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->getMockForAbstractClass();

        return $loggerMock;
    }

    /**
     * @return Source|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getSourceMock()
    {
        $assetSourceMock = $this->getMockBuilder(Source::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $assetSourceMock;
    }

    /**
     * @return File|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getFileMock()
    {
        $fileMock = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fileMock->expects(self::once())
            ->method('getPath')
            ->willReturn(self::TEST_PATH);

        return $fileMock;
    }
}

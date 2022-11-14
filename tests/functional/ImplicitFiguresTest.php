<?php
namespace DoW\CommonMark\Tests\Functional;

use PHPUnit\Framework\TestCase;

class ImplicitFiguresTest extends TestCase
{
    /**
     * Test emphasis parsing with em and strong enabled
     * @test
     */
    public function testNotLinkableFigure()
    {
        $environment = \League\CommonMark\Environment\Environment::createCommonMarkEnvironment();
        $environment->addExtension(new \DoW\CommonMark\ImplicitFigures\Extension());
        $converter = new \League\CommonMark\MarkdownConverter($environment);

        $mdContent = trim(file_get_contents($this->getPathToData('input.md')));
        $expectedContents = trim(file_get_contents($this->getPathToData('input.html')));

        $this->assertEquals($expectedContents, trim($converter->convertToHtml($mdContent)->getContent()));
    }

    /**
     * Returns the full path to the test data file
     *
     * @param string $file
     *
     * @return string
     */
    protected function getPathToData($file)
    {
        return realpath(__DIR__ . '/data/' . $file);
    }
}

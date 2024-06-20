<?php

namespace Webdevcave\DirectoryCrawler\Tests;

use PHPUnit\Framework\TestCase;
use Webdevcave\DirectoryCrawler\Crawler;

class CrawlerTest extends TestCase
{
    const TARGET_DIRS = [
        __DIR__.'/TargetNamespace/Controllers',
        __DIR__.'/TargetNamespace/Interfaces',
    ];
    const TARGET_FILES = [
        __DIR__.'/TargetNamespace/Controllers/ContactFormController.php',
        __DIR__.'/TargetNamespace/Controllers/HomeController.php',
        __DIR__.'/TargetNamespace/Interfaces/DatabaseInterface.php',
        __DIR__.'/TargetNamespace/NotAnActualClass.php',
    ];
    const TARGET_CLASSES = [
        'Webdevcave\\DirectoryCrawler\\Tests\\TargetNamespace\\Controllers\\ContactFormController',
        'Webdevcave\\DirectoryCrawler\\Tests\\TargetNamespace\\Controllers\\HomeController',
        'Webdevcave\\DirectoryCrawler\\Tests\\TargetNamespace\\Interfaces\\DatabaseInterface',
    ];

    private ?Crawler $crawler = null;

    public function testListContents(): void
    {
        $this->assertEmpty(
            array_diff($this->crawler->contents(), [...self::TARGET_DIRS, ...self::TARGET_FILES]),
            'Contents list does not match the expected list'
        );
    }

    public function testListDirectories(): void
    {
        $this->assertEmpty(
            array_diff($this->crawler->directories(), self::TARGET_DIRS),
            'Directories list does not match the expected list'
        );
    }

    public function testListFiles(): void
    {
        $this->assertEmpty(
            array_diff($this->crawler->files(), self::TARGET_FILES),
            'Files list does not match the expected list'
        );
    }

    public function testListClasssesNoCheck(): void
    {
        $expected = [...self::TARGET_CLASSES];
        $expected[] = 'Webdevcave\\DirectoryCrawler\\Tests\\TargetNamespace\\NotAnActualClass';

        $actual = $this->crawler->classes('Webdevcave\\DirectoryCrawler\\Tests\\TargetNamespace\\');
        print_r(compact('actual', 'expected'));

        $this->assertEmpty(
            array_diff($this->crawler->classes('Webdevcave\\DirectoryCrawler\\Tests\\TargetNamespace\\'), $expected),
            'Class listing (non-checked) does not match the expected list)'
        );
    }

    public function testListClasssesCheck(): void
    {
        $this->assertEmpty(
            array_diff($this->crawler->classes('Webdevcave\\DirectoryCrawler\\Tests\\TargetNamespace\\', true), self::TARGET_CLASSES),
            'Class listing (checking) does not match the expected list)'
        );
    }

    protected function setUp(): void
    {
        $this->crawler = new Crawler(__DIR__.'/TargetNamespace');
    }

    protected function tearDown(): void
    {
        $this->crawler = null;
    }
}

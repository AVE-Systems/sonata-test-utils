<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Makes easier flash messages checking.
 *
 * @method void assertGreaterThan(int $expected, $actual, string $message = '')
 * @method void assertTrue($condition, string $message = '')
 * @method void assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false)
 *
 * @see Assert::assertGreaterThan
 * @see Assert::assertTrue
 * @see Assert::assertEquals
 */
trait SonataAdminFlashMessagesTrait
{
    /**
     * Check success flash message text.
     *
     * @param string  $message
     * @param Crawler $crawler
     */
    protected function assertFlashSuccessMessageExists(
        string $message,
        Crawler $crawler
    ): void {
        $nodes = $this->findFlashSuccessMessages($crawler)->getIterator();

        $this->assertGreaterThan(
            0,
            $nodes->count(),
            'No success flash messages'
        );

        $matched = false;
        foreach ($nodes as $node) {
            if (preg_match("~{$message}~", $node->nodeValue)) {
                $matched = true;
            }
        }
        $this->assertTrue(
            $matched,
            'No success flash messages with text "'.$message.'".'
        );
    }
    /**
     * Check info flash message text.
     *
     * @param string  $message
     * @param Crawler $crawler
     */
    protected function assertFlashInfoMessageExists(
        string $message,
        Crawler $crawler
    ): void {
        $nodes = $this->findFlashInfoMessages($crawler)->getIterator();

        $this->assertGreaterThan(
            0,
            $nodes->count(),
            'No info flash messages'
        );

        $matched = false;

        foreach ($nodes as $node) {
            if (preg_match("~{$message}~", $node->nodeValue)) {
                $matched = true;
            }
        }

        $this->assertTrue(
            $matched,
            'No info flash messages with text "'.$message.'".'
        );
    }

    /**
     * Check error flash message text.
     *
     * @param string  $error
     * @param Crawler $crawler
     */
    protected function assertFlashErrorMessageExists(
        string $error,
        Crawler $crawler
    ): void {
        $nodes = $this->findFlashErrorMessages($crawler)->getIterator();

        $this->assertGreaterThan(
            0,
            $nodes->count(),
            'No error flash messages'
        );

        $matched = false;
        foreach ($nodes as $node) {
            if (preg_match("~{$error}~", $node->nodeValue)) {
                $matched = true;
            }
        }
        $this->assertTrue(
            $matched,
            'No error flash messages with text "'.$error.'".'
        );
    }

    /**
     * Check error flash messages count.
     *
     * @param int     $count
     * @param Crawler $crawler
     */
    protected function assertFlashErrorMessagesCount(int $count, Crawler $crawler)
    {
        $this->assertEquals(
            $count,
            $this->findFlashErrorMessages($crawler)->count()
        );
    }

    /**
     * Check warning flash message text.
     *
     * @param string  $expectedMessage
     * @param Crawler $crawler
     */
    protected function assertFlashWarningMessageExists(
        string $expectedMessage,
        Crawler $crawler
    ): void {
        $nodes = $this->findFlashWarningMessages($crawler)->getIterator();

        $this->assertGreaterThan(
            0,
            $nodes->count(),
            'No warning flash messages'
        );

        $matched = false;
        foreach ($nodes as $node) {
            if (preg_match("~{$expectedMessage}~", $node->nodeValue)) {
                $matched = true;
            }
        }
        $this->assertTrue(
            $matched,
            'No warning flash messages with text "'.$expectedMessage.'".'
        );
    }

    /**
     * Find error flash messages.
     *
     * @param Crawler $crawler
     *
     * @return Crawler
     */
    private function findFlashErrorMessages(Crawler $crawler)
    {
        return $crawler
            ->filter('div[class="alert alert-error fade in"]');
    }

    /**
     * Find success flash messages.
     *
     * @param Crawler $crawler
     *
     * @return Crawler
     */
    private function findFlashSuccessMessages(Crawler $crawler)
    {
        return $crawler
            ->filter('div[class="alert alert-success fade in"]');
    }

    /**
     * Find info flash messages.
     *
     * @param Crawler $crawler
     *
     * @return Crawler
     */
    private function findFlashInfoMessages(Crawler $crawler)
    {
        return $crawler
            ->filter('div[class="alert alert-info fade in"]');
    }

    /**
     * Find warning flash messages.
     *
     * @param Crawler $crawler
     *
     * @return Crawler
     */
    private function findFlashWarningMessages(Crawler $crawler)
    {
        return $crawler
            ->filter('div[class="alert alert-warning fade in"]');
    }
}

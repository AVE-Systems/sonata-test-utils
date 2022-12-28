<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Makes easier flash messages checking.
 *
 * @method void assertContains($needle, $haystack, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false)
 * @method void assertTrue($condition, string $message = '')
 * @method void assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false)
 *
 * @see Assert::assertContains()
 * @see Assert::assertTrue()
 * @see Assert::assertEquals()
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
        $this->assertContains(
            $message,
            $this->findFlashSuccessMessages($crawler),
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
        $this->assertContains(
            $message,
            $this->findFlashInfoMessages($crawler),
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
        $this->assertContains(
            $error,
            $this->findFlashErrorMessages($crawler),
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
        $this->assertCount(
            $count,
            $this->findFlashErrorMessages($crawler)
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
        $this->assertContains(
            $expectedMessage,
            $this->findFlashWarningMessages($crawler),
            'No warning flash messages with text "'.$expectedMessage.'".'
        );
    }

    /**
     * Find error flash messages.
     *
     * @return string[]
     */
    private function findFlashErrorMessages(Crawler $crawler): array
    {
        return $crawler
            ->filter('div[class="alert alert-error fade in"]')
            ->each(function (Crawler $element) {
                return $element->evaluate('normalize-space(.)')[0];
            });
    }

    /**
     * Find success flash messages.
     *
     * @return string[]
     */
    private function findFlashSuccessMessages(Crawler $crawler): array
    {
        return $crawler
            ->filter('div[class="alert alert-success fade in"]')
            ->each(function (Crawler $element) {
                return $element->evaluate('normalize-space(.)')[0];
            });
    }

    /**
     * Find info flash messages.
     *
     * @return string[]
     */
    private function findFlashInfoMessages(Crawler $crawler): array
    {
        return $crawler
            ->filter('div[class="alert alert-info fade in"]')
            ->each(function (Crawler $element) {
                return $element->evaluate('normalize-space(.)')[0];
            });
    }

    /**
     * Find warning flash messages.
     *
     * @return string[]
     */
    private function findFlashWarningMessages(Crawler $crawler): array
    {
        return $crawler
            ->filter('div[class="alert alert-warning fade in"]')
            ->each(function (Crawler $element) {
                return $element->evaluate('normalize-space(.)')[0];
            });
    }
}

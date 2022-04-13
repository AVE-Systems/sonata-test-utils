<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Трейт для упрощения проверки флэш-сообщений Sonata на странице.
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
     * Позволяет проверить текст "успешного" флэш-сообшения в респонсе.
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
            'Успешные сообщения отсутствуют на странице!'
        );

        $matched = false;
        foreach ($nodes as $node) {
            if (preg_match("~{$message}~", $node->nodeValue)) {
                $matched = true;
            }
        }
        $this->assertTrue(
            $matched,
            'Успешные флэш-сообщения не содержат текст "'.$message.'".'
        );
    }
    /**
     * Позволяет проверить текст "информационного" флэш-сообшения.
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
            'Информационные сообщения отсутствуют на странице!'
        );

        $matched = false;

        foreach ($nodes as $node) {
            if (preg_match("~{$message}~", $node->nodeValue)) {
                $matched = true;
            }
        }

        $this->assertTrue(
            $matched,
            'Информационные флэш-сообщения не содержат текст "'.$message.'".'
        );
    }

    /**
     * Позволяет проверить текст "неуспешного" флэш-сообшения в респонсе.
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
            'Сообщения c ошибками отсутствуют на странице!'
        );

        $matched = false;
        foreach ($nodes as $node) {
            if (preg_match("~{$error}~", $node->nodeValue)) {
                $matched = true;
            }
        }
        $this->assertTrue(
            $matched,
            'Неуспешные флэш-сообщения не содержат текст "'.$error.'".'
        );
    }

    /**
     * Проверяет количество неуспешных флэш-сообщений в респонсе.
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
     * Позволяет проверить текст "предупреждающего" флэш-сообшения в респонсе.
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
            'Сообщения c предупреждениями отсутствуют на странице!'
        );

        $matched = false;
        foreach ($nodes as $node) {
            if (preg_match("~{$expectedMessage}~", $node->nodeValue)) {
                $matched = true;
            }
        }
        $this->assertTrue(
            $matched,
            'Предупреждающие флэш-сообщения не содержат текст "'
            .$expectedMessage.'".'
        );
    }

    /**
     * Поиск флэш сообщений с ошибками.
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
     * Поиск успешных флэш сообщений.
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
     * Поиск информационных флэш сообщений.
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
     * Поиск флэш сообщений с предупреждениями.
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

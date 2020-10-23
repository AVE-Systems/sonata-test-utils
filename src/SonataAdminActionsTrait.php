<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Нужен для упрощения проверки наличия пунктов в меню "Действия",
 * оформленных в стиле "SonataAdminBundle".
 * Должен использоваться в тестах, наследованных от "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 *
 * @see Assert::assertCount
 */
trait SonataAdminActionsTrait
{
    /**
     * Проверяет, что на странице списка присутствует кнопка, соответствующая
     * переданному действию.
     *
     * @param string  $actionTitle
     * @param Crawler $crawler
     */
    protected function assertActionButtonExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $addActionXPath = $this->getActionButtonXPath($actionTitle);

        $this->assertCount(
            1,
            $crawler->filterXPath($addActionXPath),
            sprintf('На странице нет действия "%s"', $actionTitle)
        );
    }

    /**
     * Проверяет, что на странице списка не присутствует кнопка, соответствующая
     * переданному действию.
     *
     * @param string  $actionTitle
     * @param Crawler $crawler
     */
    protected function assertActionButtonNotExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $addActionXPath = $this->getActionButtonXPath($actionTitle);

        $this->assertCount(
            0,
            $crawler->filterXPath($addActionXPath),
            sprintf('На странице есть действие "%s"', $actionTitle)
        );
    }

    /**
     * Возвращает XPath, соответствующий действию на стандартной странице списка
     * Sonata Admin.
     *
     * @param string $actionTitle
     *
     * @return string
     */
    private function getActionButtonXPath(string $actionTitle): string
    {
        return "//a[contains(@class, 'sonata-action-element') and normalize-space()='$actionTitle']";
    }
}

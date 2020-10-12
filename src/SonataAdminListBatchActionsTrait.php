<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Нужен для упрощения проверки наличия пунктов в меню для массовых действий
 * со строками на странице списка, оформленных в стиле "SonataAdminBundle".
 * Должен использоваться в тестах, наследованных от "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 *
 * @see Assert::assertCount
 */
trait SonataAdminListBatchActionsTrait
{
    /**
     * Проверяет, что на странице списка присутствует кнопка, соответствующая
     * переданному групповому действию.
     *
     * @param string       $actionTitle
     * @param Crawler|null $crawler
     */
    protected function assertListBatchActionButtonNotExists(
        string $actionTitle,
        ?Crawler $crawler
    ) {
        $deleteBatchActionXPath = $this->getListBatchActionButtonXPath(
            $actionTitle
        );

        $this->assertCount(
            0,
            $crawler->filterXPath($deleteBatchActionXPath),
            sprintf('На странице есть действие "%s"', $actionTitle)
        );
    }

    /**
     * Возвращает XPath, соответствующий групповому действию на стандартной
     * странице списка Sonata Admin.
     *
     * @param string $actionTitle
     *
     * @return string
     */
    private function getListBatchActionButtonXPath(
        string $actionTitle
    ): string {
        return "//select[@name='action']/option[.='$actionTitle']";
    }
}

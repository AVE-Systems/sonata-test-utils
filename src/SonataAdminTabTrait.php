<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Нужен для упрощения проверки содержимого во вкладках в HTML,
 * оформленных в стиле "SonataAdminBundle".
 * Должен использоваться в тестах, наследованных от "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 *
 * @see Assert::assertEquals
 */
trait SonataAdminTabTrait
{
    /**
     * Проверяет, что вкладка с заданным именем существует в контейнере.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    protected function assertTabExists(string $tabLabel, Crawler $tabsContainer)
    {
        $this->assertTabLabelExists($tabLabel, $tabsContainer);
        $this->assertTabPaneExists($tabLabel, $tabsContainer);
    }

    /**
     * Проверяет, что вкладка с заданным именем не существует в контейнере.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    protected function assertTabNotExists(string $tabLabel, Crawler $tabsContainer)
    {
        $this->assertTabLabelNotExists($tabLabel, $tabsContainer);
    }

    /**
     * Проверяет, что ярлык вкладки с заданным именем существует в контейнере.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    private function assertTabLabelExists(string $tabLabel, Crawler $tabsContainer)
    {
        $this->assertCount(
            1,
            $tabsContainer->filterXPath("//{$this->getTabLabelXPath($tabLabel)}"),
            sprintf('Вкладка с заголовком "%s" не найдена', $tabLabel)
        );
    }

    /**
     * Проверяет, что ярлык вкладки с заданным именем не существует в контейнере.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    private function assertTabLabelNotExists(string $tabLabel, Crawler $tabsContainer)
    {
        $this->assertCount(
            0,
            $tabsContainer->filterXPath("//{$this->getTabLabelXPath($tabLabel)}"),
            sprintf('Вкладка с заголовком "%s" найдена', $tabLabel)
        );
    }

    /**
     * Проверяет, что панель вкладки с заданным содержимым ярлыка существует
     * в контейнере.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    private function assertTabPaneExists(string $tabLabel, Crawler $tabsContainer)
    {
        $tabPaneXPath = $this->getTabPaneXPathByTabLabel($tabLabel, $tabsContainer);

        $this->assertCount(
            1,
            $tabsContainer->filterXPath("//{$tabPaneXPath}"),
            sprintf('Панель для вкладки с заголовком "%s" не найдена', $tabLabel)
        );
    }

    /**
     * Возвращает XPath-путь к контейнеру с ярлыками вкладок.
     *
     * @return string
     */
    private function tabLabelsContainerXPath(): string
    {
        return "ul[contains(@class, 'nav-tabs')]";
    }

    /**
     * Возвращает XPath-путь к ярлыку вкладки по заданному содержимому.
     *
     * @param string $tabLabel
     *
     * @return string
     */
    private function getTabLabelXPath(string $tabLabel): string
    {
        return "{$this->tabLabelsContainerXPath()}/li//a[normalize-space()='$tabLabel']";
    }

    /**
     * Возвращает XPath-путь к контейнеру с панелями вкладок.
     *
     * @return string
     */
    private function tabPanesContainerXPath(): string
    {
        return "{$this->tabLabelsContainerXPath()}/following-sibling::div[contains(@class, 'tab-content')]";
    }

    /**
     * Возвращает XPath-путь к панели вкладки с заданным идентификатором.
     *
     * @param string $tabPaneId идентификатор без предшествующего "#"
     *
     * @return string
     */
    private function getTabPaneXPath(string $tabPaneId): string
    {
        return "{$this->tabPanesContainerXPath()}/div[contains(@class, 'tab-pane') and @id='$tabPaneId']";
    }

    /**
     * Возвращает XPath-путь к панели вкладки по содержимому ярлыка.
     *
     * @param string  $tabLabel
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     *
     * @return string
     */
    private function getTabPaneXPathByTabLabel(
        string $tabLabel,
        Crawler $tabsContainer
    ): string {
        $tabLabelHref = $tabsContainer
            ->filterXPath("//{$this->getTabLabelXPath($tabLabel)}")
            ->attr('href');

        $tabPaneId = ltrim($tabLabelHref, '#');

        return $this->getTabPaneXPath($tabPaneId);
    }
}

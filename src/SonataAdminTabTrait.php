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
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    protected function assertTabLabelExists(string $tabLabel, Crawler $tabsContainer)
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
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    protected function assertTabLabelNotExists(string $tabLabel, Crawler $tabsContainer)
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
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
     */
    protected function assertTabPaneExists(string $tabLabel, Crawler $tabsContainer)
    {
        $tabPaneXPath = $this->getTabPaneXPathByTabLabel($tabLabel, $tabsContainer);

        $this->assertCount(
            1,
            $tabsContainer->filterXPath("//{$tabPaneXPath}"),
            sprintf('Панель для вкладки с заголовком "%s" не найдена', $tabLabel)
        );
    }

    /**
     * Checks that a tab label with the given title and exclamation mark icon
     * indicating an error exists in the container.
     *
     * @param Crawler $tabsContainer as the root node it must be a container
     *                               that contains a container with tab
     *                               labels (ul-list)
     */
    protected function assertTabExclamationIconExists(
        string $tabLabel,
        Crawler $tabsContainer
    ): void {
        $this->assertCount(
            1,
            $tabsContainer->filterXPath("//{$this->getTabExclamationIconXPath($tabLabel)}"),
            sprintf(
                'Tab with title "%s" and exclamation mark icon not found',
                $tabLabel
            )
        );
    }

    /**
     * Checks that a tab label with the given title and exclamation mark icon
     * indicating an error does not exist in the container.
     *
     * @param Crawler $tabsContainer as the root node it must be a container
     *                               that contains a container with tab
     *                               labels (ul-list)
     */
    protected function assertTabExclamationIconNotExists(
        string $tabLabel,
        Crawler $tabsContainer
    ): void {
        $this->assertCount(
            0,
            $tabsContainer->filterXPath(
                "//{$this->getTabExclamationIconXPath($tabLabel)}"
            ),
            sprintf(
                'Tab with title "%s" and exclamation mark icon exists',
                $tabLabel
            )
        );
    }

    /**
     * Возвращает XPath-путь к контейнеру с ярлыками вкладок.
     */
    private function tabLabelsContainerXPath(): string
    {
        return "ul[contains(@class, 'nav-tabs')]";
    }

    /**
     * Возвращает XPath-путь к ярлыку вкладки по заданному содержимому.
     */
    private function getTabLabelXPath(string $tabLabel): string
    {
        return "{$this->tabLabelsContainerXPath()}/li//a[normalize-space()='$tabLabel']";
    }

    /**
     * Возвращает XPath-путь к панели вкладки с заданным идентификатором.
     *
     * @param string $tabPaneId идентификатор без предшествующего "#"
     */
    private function getTabPaneXPath(string $tabPaneId): string
    {
        return "{$this->tabLabelsContainerXPath()}/following-sibling::div"
            ."/descendant-or-self::div[contains(@class, 'tab-pane') and @id='$tabPaneId']";
    }

    /**
     * Возвращает XPath-путь к панели вкладки по содержимому ярлыка.
     *
     * @param Crawler $tabsContainer корневым узлом должен быть контейнер,
     *                               содержащий контейнер с ярлыками
     *                               вкладок (ul-список) и контейнер с панелями
     *                               вкладок
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

    /**
     * Returns the XPath to a tab label with the icon of exclamation mark.
     */
    private function getTabExclamationIconXPath(string $tabLabel): string
    {
        return "{$this->tabLabelsContainerXPath()}/li//"
            ."a[normalize-space()='$tabLabel']//i[contains(@class, "
            ."'fa-exclamation-circle') and contains(@class, 'has-errors') "
            ."and not(contains(@class, 'hide'))]";
    }
}

<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Нужен для упрощения проверки наличия пунктов меню,
 * оформленных в стиле "SonataAdminBundle".
 * Должен использоваться в тестах, наследованных от "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 *
 * @see Assert::assertCount
 */
trait SonataAdminMenuTrait
{
    /**
     * Проверяет, что список меню присутствует на странице.
     *
     * @param Crawler $crawler
     */
    protected function assertMenuExists(Crawler $crawler)
    {
        $menuXPath = $this->getMenuXPath();

        $this->assertCount(
            1,
            $crawler->filterXPath('//'.$menuXPath),
            'Не найдено меню на странице'
        );
    }

    /**
     * Проверяет, что список меню отсутствует на странице.
     *
     * @param Crawler $crawler
     */
    protected function assertMenuNotExists(Crawler $crawler)
    {
        $menuXPath = $this->getMenuXPath();

        $this->assertCount(
            0,
            $crawler->filterXPath('//'.$menuXPath),
            'Меню присутствует на странице'
        );
    }

    /**
     * Проверяет, что пункт с заданным названием в списке меню.
     *
     * @param Crawler $crawler
     * @param string  $menuItem название пункта меню
     */
    protected function assertMenuItemExists(Crawler $crawler, string $menuItem)
    {
        $menuXPath = $this->getMenuXPath();
        $itemXPath = $this->getMenuItemXPath($menuItem);

        $xpath = "//$menuXPath//$itemXPath";

        $this->assertCount(
            1,
            $crawler->filterXPath($xpath),
            sprintf('В меню нет пункта "%s"', $menuItem)
        );
    }

    /**
     * Проверяет, что пункт с заданным названием в списке меню.
     *
     * @param Crawler $crawler
     * @param string  $menuItem название пункта меню
     */
    protected function assertMenuItemNotExists(
        Crawler $crawler,
        string $menuItem
    ) {
        $menuXPath = $this->getMenuXPath();
        $itemXPath = $this->getMenuItemXPath($menuItem);

        $xpath = "//$menuXPath//$itemXPath";

        $this->assertCount(
            0,
            $crawler->filterXPath($xpath),
            sprintf('В меню есть пункт "%s"', $menuItem)
        );
    }

    protected function assertMenuItemInGroupExists(
        Crawler $crawler,
        string $menuItem,
        string $menuGroup
    ) {
        $xpath = $this->getMenuItemInGroupXPath($menuGroup, $menuItem);

        $this->assertCount(
            1,
            $crawler->filterXPath($xpath),
            sprintf('В группе меню "%s" нет пункта "%s"', $menuGroup, $menuItem)
        );
    }

    protected function assertMenuItemInGroupNotExists(
        Crawler $crawler,
        string $menuItem,
        string $menuGroup
    ) {
        $xpath = $this->getMenuItemInGroupXPath($menuGroup, $menuItem);

        $this->assertCount(
            0,
            $crawler->filterXPath($xpath),
            sprintf('В группе меню "%s" есть пункт "%s"', $menuGroup, $menuItem)
        );
    }

    /**
     * @param string $menuGroup
     * @param string $menuItem
     *
     * @return string
     */
    private function getMenuItemInGroupXPath(
        string $menuGroup,
        string $menuItem
    ): string {
        $menuXPath = $this->getMenuXPath();
        $groupXMenuPath = $this->getMenuGroupMenuXPath($menuGroup);
        $itemXPath = $this->getMenuItemXPath($menuItem);

        $xpath = "//$menuXPath//$groupXMenuPath//$itemXPath";

        return $xpath;
    }

    /**
     * Возвращает XPath-путь к меню SonataAdminBundle.
     *
     * @return string
     */
    private function getMenuXPath()
    {
        return 'ul[@class="sidebar-menu"]';
    }

    /**
     * Возвращает XPath-путь к группе меню SonataAdminBundle по названию.
     *
     * @param string $menuGroup
     *
     * @return string
     */
    private function getMenuGroupXPath(string $menuGroup)
    {
        return "li[contains(@class, 'treeview')]//a[normalize-space()='{$menuGroup}']";
    }

    /**
     * Возвращает XPath-путь к меню группы  по названию в меню
     * SonataAdminBundle.
     *
     * @param string $menuGroup
     *
     * @return string
     */
    private function getMenuGroupMenuXPath(string $menuGroup)
    {
        $menuGroupXPath = $this->getMenuGroupXPath($menuGroup);

        return "$menuGroupXPath/following-sibling::ul[contains(@class, 'treeview-menu')]";
    }

    /**
     * Возвращает XPath-путь к пункту меню SonataAdminBundle по названию.
     *
     * @param string $menuItem название пункта меню
     *
     * @return string
     */
    private function getMenuItemXPath(string $menuItem)
    {
        return "li//a[normalize-space()='{$menuItem}']";
    }
}

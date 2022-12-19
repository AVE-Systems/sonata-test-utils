<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Нужен для упрощения проверки наличия пунктов меню,
 * оформленных в стиле "SonataAdminBundle".
 * Должен использоваться в тестах, наследованных от "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 * @method void assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false)
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
     */
    protected function assertMenuItemExists(
        Crawler $crawler,
        string $menuItem,
        string $expectedUrl = null
    ) {
        $menuXPath = $this->getMenuXPath();
        $itemXPath = $this->getMenuItemXPath($menuItem);

        $xpath = "//$menuXPath//$itemXPath";

        $link = $crawler->filterXPath($xpath);

        $this->assertCount(
            1,
            $link,
            sprintf('В меню нет пункта "%s"', $menuItem)
        );

        if ($expectedUrl !== null) {
            $actualUrl = $link->attr('href');

            $this->assertEquals(
                $expectedUrl,
                $actualUrl,
                sprintf(
                    'Пункт меню "%s" ведёт на "%s", а не на "%s"',
                    $menuItem,
                    $actualUrl,
                    $expectedUrl
                )
            );
        }
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

    /**
     * Проверяет, что пункт с заданным названием есть в определённой группе
     * меню.
     */
    protected function assertMenuItemInGroupExists(
        Crawler $crawler,
        string $menuItem,
        string $menuGroup,
        string $expectedUrl = null
    ) {
        $xpath = $this->getMenuItemInGroupXPath($menuGroup, $menuItem);

        $link = $crawler->filterXPath($xpath);

        $this->assertCount(
            1,
            $link,
            sprintf('В группе меню "%s" нет пункта "%s"', $menuGroup, $menuItem)
        );

        if ($expectedUrl !== null) {
            $actualUrl = $link->attr('href');

            $this->assertEquals(
                $expectedUrl,
                $actualUrl,
                sprintf(
                    'Пункт меню "%s" ведёт на "%s", а не на "%s"',
                    $menuItem,
                    $actualUrl,
                    $expectedUrl
                )
            );
        }
    }

    /**
     * Проверяет, что пункта с заданным названием нет в определённой группе
     * меню.
     *
     * @param Crawler $crawler
     * @param string  $menuItem  название пункта меню
     * @param string  $menuGroup название группы меню
     */
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
     * Проверяет, что названия пунктов меню и иерархия в определённой группе
     * соответствуют переданному значению.
     */
    protected function assertMenuItemsInGroupEqual(
        Crawler $crawler,
        array $expectedMenuHierarchyLabels,
        string $menuGroup
    ) {
        $menuXPath = $this->getMenuXPath();
        $groupMenuXPath = "//$menuXPath//{$this->getMenuGroupMenuXPath($menuGroup)}";

        $groupMenu = $crawler->filterXPath($groupMenuXPath);

        $actualMenuHierarchyLabels = $this->retrieveMenuLabels($groupMenu);

        $this->assertAssocArraysEqual(
            $expectedMenuHierarchyLabels,
            $actualMenuHierarchyLabels,
            sprintf('Не совпадает порядок пунктов меню в группе "%s"', $menuGroup)
        );
    }

    /**
     * Проверяет, что названия пунктов меню и иерархия соответствуют
     * переданному значению.
     *
     * @param Crawler $crawler
     * @param array   $expectedMenuHierarchyLabels
     *
     * @example [
     *   'Управленческий совет' => [
     *     'Информация о заседаниях',
     *     'Представители учредителя',
     *   ],
     *   'Справки директоров',
     * ]
     */
    protected function assertMenuItemsEqual(
        Crawler $crawler,
        array $expectedMenuHierarchyLabels
    ) {
        $menuXPath = "//{$this->getMenuXPath()}";
        $menu = $crawler->filterXPath($menuXPath);

        $actualMenuHierarchyLabels = $this->retrieveMenuLabels($menu);

        $this->assertAssocArraysEqual(
            $expectedMenuHierarchyLabels,
            $actualMenuHierarchyLabels,
            'Не совпадает порядок пунктов меню'
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

        return "//$menuXPath//$groupXMenuPath//$itemXPath";
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
     * Возвращает XPath-путь к меню группы по названию в меню
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

    /**
     * Обходит меню и возвращает названия пунктов в соответствии с иерархией.
     *
     * @param Crawler $menu
     *
     * @return string[]
     *
     * @example [
     *   'Управленческий совет' => [
     *     'Информация о заседаниях',
     *     'Представители учредителя',
     *   ],
     *   'Справки директоров',
     * ]
     */
    private function retrieveMenuLabels(Crawler $menu): array
    {
        $menuLabels = [];

        $menu->filterXPath('ul/li')->each(
            function (Crawler $item) use (&$menuLabels) {
                $itemLabel = trim($item->filterXPath('li/a')->text());
                $subMenu = $item->filterXPath('li/ul');

                if ($subMenu->count() === 0) {
                    $menuLabels[] = $itemLabel;
                } else {
                    $menuLabels[$itemLabel] = $this->retrieveMenuLabels(
                        $subMenu
                    );
                }
            }
        );

        return $menuLabels;
    }

    private function assertAssocArraysEqual(
        array $expectedArray,
        array $actualArray,
        string $message
    ) {
        $this->assertEquals($expectedArray, $actualArray);

        // При сравнении ассоциативных массивов, не учитывается порядок ключей,
        // поэтому нужна дополнительная проверка
        $expectedOrderedKeys = [];
        $actualOrderedKeys = [];

        array_walk_recursive(
            $expectedArray,
            function ($value, $key) use (&$expectedOrderedKeys) {
                $expectedOrderedKeys[] = $key;
            }
        );

        array_walk_recursive(
            $actualArray,
            function ($value, $key) use (&$actualOrderedKeys) {
                $actualOrderedKeys[] = $key;
            }
        );

        $this->assertEquals($expectedOrderedKeys, $actualOrderedKeys, $message);
    }
}

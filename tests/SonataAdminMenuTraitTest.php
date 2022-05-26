<?php

namespace Tests;

use AveSystems\SonataTestUtils\SonataAdminMenuTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class SonataAdminMenuTraitTest extends TestCase
{
    use SonataAdminMenuTrait;

    public function dataProvider_MenuNotExists()
    {
        $html = <<<'HTML'
<html>
    <body>
      <div class="wrapper">
      </div>
    </body>
</html>
HTML;

        return [[$html]];
    }

    public function dataProvider_TestAssertMenuItem()
    {
        $html = <<<'HTML'
<html>
    <body>
      <div class="wrapper">
        <aside class="main-sidebar">
          <div class="slimScrollDiv">
            <section class="sidebar">
              <ul class="sidebar-menu">
                <li class="first treeview">
                  <a href="#">
                    <i class="fa fa-sitemap"></i>
                    <span>Управляющий совет</span>
                    <span class="pull-right-container">
                      <i class="fa pull-right fa-angle-left"></i>
                    </span>
                  </a>
                  <ul class="active treeview-menu menu_level_1">
                    <li class="first">
                      <a href="/managerialcouncil/list">
                        <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                        Информация о заседаниях
                      </a>      
                    </li>
                    <li class="last">
                      <a href="#">
                        <i class="fa fa-sitemap"></i>
                        <span>Представители учредителя</span>
                        <span class="pull-right-container">
                          <i class="fa pull-right fa-angle-left"></i>
                        </span>
                      </a>
                      <ul class="active treeview-menu menu_level_1">
                        <li class="first">
                          <a href="/managerialcouncil/create">
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            Создать новый
                          </a>      
                        </li>
                        <li class="last">
                          <a href="/stakeholderagent/list">
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                            Показать список
                          </a>
                        </li>  
                      </ul>
                    </li>  
                  </ul>
                </li>
                <li>
                  <a href="mailto:result@mioo.ru">
                    <i class="fa fa-envelope"></i>
                    Адрес поддержки
                  </a>        
                </li>
              </ul>
            </section>
          </div>
        </aside>
      </div>
    </body>
</html>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_MenuNotExists
     *
     * @param string $html
     */
    public function testAssertMenuExists_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Не найдено меню на странице.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuExists($crawler);
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuExists_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuExists($crawler);
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuNotExists_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Меню присутствует на странице.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuNotExists($crawler);
    }

    /**
     * @dataProvider dataProvider_MenuNotExists
     *
     * @param string $html
     */
    public function testAssertMenuNotExists_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuNotExists($crawler);
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemExists_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.В меню нет пункта "Адрес".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemExists($crawler, 'Адрес');
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemExists_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemExists($crawler, 'Адрес поддержки');
        $this->assertMenuItemExists($crawler, 'Управляющий совет');
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemExists_WithUrl_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Пункт меню "Адрес поддержки" ведёт на "mailto:result@mioo\.ru", а не на "mailto:result".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemExists(
            $crawler,
            'Адрес поддержки',
            'mailto:result'
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemExists_WithUrl_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemExists(
            $crawler,
            'Адрес поддержки',
            'mailto:result@mioo.ru'
        );

        $this->assertMenuItemExists(
            $crawler,
            'Управляющий совет',
            '#'
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemNotExists_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.В меню есть пункт "Адрес поддержки".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemNotExists($crawler, 'Адрес поддержки');
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemNotExists_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemNotExists($crawler, 'Адрес');
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemInGroupExists_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.В группе меню "Управляющий совет" нет пункта "Адрес поддержки".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemInGroupExists(
            $crawler,
            'Адрес поддержки',
            'Управляющий совет'
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemInGroupExists_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemInGroupExists(
            $crawler,
            'Информация о заседаниях',
            'Управляющий совет'
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemInGroupExists_WithUrl_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Пункт меню "Информация о заседаниях" ведёт на "/managerialcouncil/list", а не на "/managerialcouncil".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemInGroupExists(
            $crawler,
            'Информация о заседаниях',
            'Управляющий совет',
            '/managerialcouncil'
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemInGroupExists_WithUrl_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemInGroupExists(
            $crawler,
            'Информация о заседаниях',
            'Управляющий совет',
            '/managerialcouncil/list'
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemInGroupNotExists_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.В группе меню "Управляющий совет" есть пункт "Информация о заседаниях".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemInGroupNotExists(
            $crawler,
            'Информация о заседаниях',
            'Управляющий совет'
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemInGroupNotExists_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemInGroupNotExists(
            $crawler,
            'Адрес поддержки',
            'Управляющий совет'
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemsEqual_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMenuItemsEqual(
            $crawler,
            [
                'Управляющий совет' => [
                    'Информация о заседаниях',
                    'Представители учредителя' => [
                        'Создать новый',
                        'Показать список',
                    ],
                ],
                'Адрес поддержки',
            ]
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemsEqual_ShouldThrowException_InvalidItemPositionInGroup(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Не совпадает порядок пунктов меню.'
        );

        $this->assertMenuItemsEqual(
            $crawler,
            [
                'Управляющий совет' => [
                    'Представители учредителя' => [
                        'Создать новый',
                        'Показать список',
                    ],
                    'Информация о заседаниях', // должен быть выше
                ],
                'Адрес поддержки',
            ]
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemsEqual_ShouldThrowException_InvalidRootItemPosition(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Не совпадает порядок пунктов меню.'
        );

        $this->assertMenuItemsEqual(
            $crawler,
            [
                'Адрес поддержки', // должен быть ниже
                'Управляющий совет' => [
                    'Информация о заседаниях',
                    'Представители учредителя' => [
                        'Создать новый',
                        'Показать список',
                    ],
                ],
            ]
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemsEqual_ShouldThrowException_MissingItemInGroup(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Failed asserting that two arrays are equal.'
        );

        $this->assertMenuItemsEqual(
            $crawler,
            [
                'Управляющий совет' => [
                    'Информация о заседаниях',
                    // отсутствует пункт меню
                ],
                'Адрес поддержки',
            ]
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertMenuItem
     *
     * @param string $html
     */
    public function testAssertMenuItemsEqual_ShouldThrowException_ExtraItemInGroup(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Failed asserting that two arrays are equal.'
        );

        $this->assertMenuItemsEqual(
            $crawler,
            [
                'Адрес поддержки',
                'Управляющий совет' => [
                    'Информация о заседаниях',
                    'Представители учредителя' => [
                        'Создать новый',
                        'Показать список',
                        'Импортировать',
                    ],
                ],
            ]
        );
    }
}

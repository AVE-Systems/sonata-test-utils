<?php

namespace Tests;

use AveSystems\SonataTestUtils\SonataAdminActionsTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class SonataAdminActionsTraitTest extends TestCase
{
    use SonataAdminActionsTrait;

    public function dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithManyActions(): array
    {
        $html = <<<'HTML'
<section class="content-header">
    <div class="sticky-wrapper">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-collapse">
                    <div class="navbar-left">
                        <ul class="nav navbar-nav">
                            <li class="first last dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Отчеты
                                    <b class="caret"></b>
                                </a>
                                <ul class="menu_level_1 dropdown-menu">
                                    <li class="first last">
                                        <a href="/app/organization-organization/list?report=orgs_not_have_directors">
                                            Организации в которых нет контракта с директором
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown sonata-actions">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                Действия
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a class="sonata-action-element" href="/organization/create">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                        Добавить новый
                                    </a>
                                </li>
                                <li>
                                    <a class="sonata-action-element" href="/organization/123/show">
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                        Показать
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown sonata-actions">
                            <a href="#" class="dropdown-toggle sonata-ba-action" data-toggle="dropdown">
                                <i class="fa fa-filter" aria-hidden="true"></i>
                                Фильтры
                                <span class="badge sonata-filter-count">2</span>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-scrollable" role="menu">
                                <li>
                                    <a href="#" class="sonata-toggle-filter sonata-ba-action">
                                        <i class="fa fa-check-square-o"></i>
                                        Название
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="sonata-toggle-filter sonata-ba-action">
                                        <i class="fa fa-check-square-o"></i>
                                        Статус
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</section>
HTML;

        return [[$html]];
    }

    public function dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithoutActions(): array
    {
        $html = <<<'HTML'
<section class="content-header">
    <div class="sticky-wrapper">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-collapse">
                    <div class="navbar-left">
                        <ul class="nav navbar-nav">
                            <li class="first last dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    Отчеты
                                    <b class="caret"></b>
                                </a>
                                <ul class="menu_level_1 dropdown-menu">
                                    <li class="first last">
                                        <a href="/app/organization-organization/list?report=orgs_not_have_directors">
                                            Организации в которых нет контракта с директором
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown sonata-actions">
                            <a href="#" class="dropdown-toggle sonata-ba-action" data-toggle="dropdown">
                                <i class="fa fa-filter" aria-hidden="true"></i>
                                Фильтры
                                <span class="badge sonata-filter-count">2</span>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-scrollable" role="menu">
                                <li>
                                    <a href="#" class="sonata-toggle-filter sonata-ba-action">
                                        <i class="fa fa-check-square-o"></i>
                                        Название
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="sonata-toggle-filter sonata-ba-action">
                                        <i class="fa fa-check-square-o"></i>
                                        Статус
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</section>
HTML;

        return [[$html]];
    }

    public function dataProvider_TestAssertAction_WithoutTabMenu_WithoutFilterMenu_WithOnlyOneAction(): array
    {
        $html = <<<'HTML'
<section class="content-header">
    <div class="sticky-wrapper">
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a class="sonata-action-element" href="/organization/create">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                Добавить новый
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</section>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithManyActions
     *
     * @param string $html
     */
    public function testAssertHeaderActionButtonExists_ShouldNotThrowException(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertHeaderActionButtonExists(
            'Показать',
            $crawler
        );

        $this->assertHeaderActionButtonExists(
            'Добавить новый',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithManyActions
     *
     * @param string $html
     */
    public function testAssertHeaderActionButtonExists_ShouldThrowException(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.На странице нет действия "Добавить".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertHeaderActionButtonExists(
            'Добавить',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithManyActions
     *
     * @param string $html
     */
    public function testAssertHeaderActionButtonNotExists_ShouldNotThrowException(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertHeaderActionButtonNotExists(
            'Добавить',
            $crawler
        );

        $this->assertHeaderActionButtonNotExists(
            'оказать',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithManyActions
     *
     * @param string $html
     */
    public function testAssertHeaderActionButtonNotExists_ShouldThrowException(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.На странице есть действие "Добавить новый".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertHeaderActionButtonNotExists(
            'Добавить новый',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithManyActions
     *
     * @param string $html
     */
    public function testAssertHeaderActionButtonsEqual_ShouldNotThrowException(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertHeaderActionButtonsEqual(
            ['Добавить новый', 'Показать'],
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithManyActions
     *
     * @param string $html
     */
    public function testAssertHeaderActionButtonsEqual_ShouldThrowException(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Действия сверху страницы не соответствуют ожидаемым.'
        );

        $this->assertHeaderActionButtonsEqual(
            ['Показать', 'Добавить новый'],
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithoutTabMenu_WithoutFilterMenu_WithOnlyOneAction
     *
     * @param string $html
     */
    public function testAssertHeaderActionButtonsEqual_ShouldNotThrowException_OnlyOneAction(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertHeaderActionButtonsEqual(
            ['Добавить новый'],
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithoutTabMenu_WithoutFilterMenu_WithOnlyOneAction
     *
     * @param string $html
     */
    public function testAssertHeaderActionButtonsEqual_ShouldThrowException_OnlyOneAction(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Действия сверху страницы не соответствуют ожидаемым.'
        );

        $this->assertHeaderActionButtonsEqual(
            ['Редактировать'],
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithManyActions
     * @dataProvider dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithoutActions
     *
     * @param string $html
     */
    public function testAssertHeaderFilterFieldsEqual_ShouldNotThrowException(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertHeaderFilterFieldsEqual(
            ['Название', 'Статус'],
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithoutTabMenu_WithoutFilterMenu_WithOnlyOneAction
     *
     * @param string $html
     */
    public function testAssertHeaderFilterFieldsEqual_ShouldNotThrowException_WithoutFilters(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertHeaderFilterFieldsEqual(
            [],
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithoutTabMenu_WithoutFilterMenu_WithOnlyOneAction
     *
     * @param string $html
     */
    public function testAssertHeaderFilterFieldsEqual_ShouldThrowException_WithoutFilters(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Фильтры сверху страницы не соответствуют ожидаемым.'
        );

        $this->assertHeaderFilterFieldsEqual(
            ['Название'],
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction_WithTabMenu_WithFilterMenu_WithManyActions
     *
     * @param string $html
     */
    public function testAssertHeaderFilterFieldsEqual_ShouldThrowException(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Фильтры сверху страницы не соответствуют ожидаемым.'
        );

        $this->assertHeaderFilterFieldsEqual(
            ['Статус', 'Название'],
            $crawler
        );
    }

    public function dataProvider_TestAssertListActions(): array
    {
        $html = <<<'HTML'
<div class="box-footer">
    <div class="form-inline clearfix">
    <div class="pull-left">
    <label class="checkbox" for="s1fc55cb054_all_elements">
        <div class="icheckbox_square-blue">
            <input type="checkbox" name="all_elements" id="s1fc55cb054_all_elements">
            <ins class="iCheck-helper"></ins>
        </div>
        Применить для всех (1572)
    </label>
    
    <div class="select2-container" id="s2id_autogen3" style="width: auto; height: auto">
      <a href="javascript:void(0)" class="select2-choice" tabindex="-1">
          <span class="select2-chosen" id="select2-chosen-4">
            Удалить
          </span>
          <abbr class="select2-search-choice-close"></abbr>
          <span class="select2-arrow" role="presentation">
              <b role="presentation"></b>
          </span>
      </a>
      <a href="javascript:void(0)" class="select2-choice" tabindex="-1">
          <span class="select2-chosen" id="select2-chosen-4">
            Показать
          </span>
          <abbr class="select2-search-choice-close"></abbr>
          <span class="select2-arrow" role="presentation">
              <b role="presentation"></b>
          </span>
      </a>
      <label for="s2id_autogen4" class="select2-offscreen"></label>
      <input class="select2-focusser select2-offscreen" type="text" aria-haspopup="true" role="button" aria-labelledby="select2-chosen-4" id="s2id_autogen4">
    </div>
    
    <select name="action" style="width: auto; height: auto; display: none;" class="" tabindex="-1" title="">
      <option value="delete">Удалить</option>
      <option value="show">Показать</option>
    </select>
    
    <input type="submit" class="btn btn-small btn-primary" value="OK">
</div>
</div>
</div>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_TestAssertListActions
     *
     * @param string $html
     */
    public function testAssertListBatchActionNotExists_ShouldNotThrowException(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertListBatchActionButtonNotExists(
            'Редактировать',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertListActions
     *
     * @param string $html
     */
    public function testAssertListBatchActionNotExists_ShouldThrowException(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.На странице списка есть групповое действие "Показать".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertListBatchActionButtonNotExists(
            'Показать',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertListActions
     *
     * @param string $html
     */
    public function testAssertListBatchActionButtonsEqual_ShouldNotThrowException(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertListBatchActionButtonsEqual(
            ['Удалить', 'Показать'],
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertListActions
     *
     * @param string $html
     */
    public function testAssertListBatchActionButtonsEqual_ShouldThrowException(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.На странице списка групповые действия не соответствуют ожидаемым.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertListBatchActionButtonsEqual(
            ['Показать', 'Удалить'],
            $crawler
        );
    }

    public function dataProvider_TestAssertFormActionButtonExistsAndNotExists(): array
    {
        $html = <<<'HTML'
          <form>
            <div class="sonata-ba-form-actions well well-small form-actions">
                <button type="submit" class="btn btn-success" name="btn_update_and_edit">
                    <i class="fa fa-save" aria-hidden="true"></i> Сохранить
                </button>
                <a class="btn btn-danger" href="/link/delete">
                <i class="fa fa-minus-circle" aria-hidden="true"></i>Удалить</a>
            </div>
          </form>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_TestAssertFormActionButtonExistsAndNotExists
     *
     * @param string $html
     */
    public function testsAssertFormActionButtonExists_Successful(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormActionButtonExists(
            'Сохранить',
            $crawler
        );
        $this->assertFormActionButtonExists(
            'Удалить',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormActionButtonExistsAndNotExists
     *
     * @param string $html
     */
    public function testsAssertFormActionButtonExists_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.На форме нет кнопки \'Несуществующий текст кнопки\'.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormActionButtonExists(
            'Несуществующий текст кнопки',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormActionButtonExistsAndNotExists
     *
     * @param string $html
     */
    public function testsAssertFormActionButtonNotExists_Successful(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormActionButtonNotExists(
            'Несуществующий текст кнопки',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormActionButtonExistsAndNotExists
     *
     * @param string $html
     */
    public function testsAssertFormActionButtonNotExists_WhenButtonType_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.На форме есть кнопка \'Сохранить\'.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormActionButtonNotExists(
            'Сохранить',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormActionButtonExistsAndNotExists
     *
     * @param string $html
     */
    public function testsAssertFormActionButtonNotExists_WhenLinkType_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.На форме есть кнопка \'Удалить\'.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormActionButtonNotExists(
            'Удалить',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormActionButtonExistsAndNotExists
     *
     * @param string $html
     */
    public function testsAssertFormActionButtonsEqual_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormActionButtonsEqual(
            ['Сохранить', 'Удалить'],
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormActionButtonExistsAndNotExists
     *
     * @param string $html
     */
    public function testsAssertFormActionButtonsEqual_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Кнопки действий снизу формы не соответствуют ожидаемым.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormActionButtonsEqual(
            ['Удалить', 'Сохранить'],
            $crawler
        );
    }
}

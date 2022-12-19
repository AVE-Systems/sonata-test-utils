<?php

namespace Tests;

use AveSystems\SonataTestUtils\SonataAdminActionsTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class SonataAdminActionsTraitTest extends TestCase
{
    use SonataAdminActionsTrait;

    public function dataProvider_TestAssertAction(): array
    {
        $html = <<<'HTML'
<ul class="nav navbar-nav navbar-right">
    <li class="dropdown sonata-actions open">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
            Действия
            <b class="caret"></b>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a class="sonata-action-element" href="/app/organization-organization/create">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    Добавить новый
                </a>
            </li>
            <li>
                <a class="sonata-action-element" href="/app/organization-organization/1/show">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                    Показать
                </a>
            </li>    
        </ul>
    </li>
</ul>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_TestAssertAction
     *
     * @param string $html
     */
    public function testAssertActionButtonExists_Success(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertActionButtonExists(
            'Показать',
            $crawler
        );

        $this->assertActionButtonExists(
            'Добавить новый',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction
     *
     * @param string $html
     */
    public function testAssertActionButtonExists_Fail(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.На странице нет действия "Добавить".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertActionButtonExists(
            'Добавить',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction
     *
     * @param string $html
     */
    public function testAssertActionButtonNotExists_Success(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertActionButtonNotExists(
            'Добавить',
            $crawler
        );

        $this->assertActionButtonNotExists(
            'оказать',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertAction
     *
     * @param string $html
     */
    public function testAssertActionButtonNotExists_Fail(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.На странице есть действие "Добавить новый".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertActionButtonNotExists(
            'Добавить новый',
            $crawler
        );
    }

    public function dataProvider_TestAssertListActions(): array
    {
        $html = <<<'HTML'
<div class="pull-left">
    <label class="checkbox" for="s1fc55cb054_all_elements">
        <div class="icheckbox_square-blue">
            <input type="checkbox" name="all_elements" id="s1fc55cb054_all_elements" style="position: absolute; opacity: 0;">
            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
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
            '.На странице есть действие "Показать".'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertListBatchActionButtonNotExists(
            'Показать',
            $crawler
        );
    }
}

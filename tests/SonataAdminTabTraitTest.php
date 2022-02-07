<?php

namespace Tests;

use AveSystems\SonataTestUtils\SonataAdminTabTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class SonataAdminTabTraitTest extends TestCase
{
    use SonataAdminTabTrait;

    public function testAssertTabExists_ShouldNotThrowException()
    {
        $html = <<<'HTML'
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li>
            <a href="#portfolio">
                <i class="fa fa-exclamation-circle"></i>
                Портфолио
            </a>
        </li>
        <li>
            <a href="#tests" data-toggle="tab">
                <i class="fa fa-exclamation-circle"></i>
                Тесты
            </a>
        </li>
        <li>
            <a href="#out" data-toggle="tab">
                <i class="fa fa-exclamation-circle"></i>
                Вкладка с содержимым вне контейнера
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade" id="portfolio">
            Данные портфолио
        </div>
        <div class="tab-pane fade" id="tests">
            Данные тестов
        </div>
    </div>
    <div class="tab-pane fade" id="out">
        Содержимое вне контейнера
    </div>
</div>
HTML;

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $tabsContainer = $crawler->filter('div.nav-tabs-custom');

        $this->assertTabExists('Портфолио', $tabsContainer);
        $this->assertTabExists('Тесты', $tabsContainer);
        $this->assertTabExists('Вкладка с содержимым вне контейнера', $tabsContainer);
    }

    public function testAssertTabExists_ShouldThrowExceptionIfTabLabelNotFound()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches('.Вкладка с заголовком "Тест" не найдена.');

        $html = <<<'HTML'
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li>
            <a href="#portfolio">
                <i class="f$this->expectExceptionMessageMatches('.Вкладка с заголовком "Портфолио" найдена.');a fa-exclamation-circle"></i>
                Портфолио
            </a>
        </li>
        <li>
            <a href="#tests" data-toggle="tab">
                <i class="fa fa-exclamation-circle"></i>
                Тесты
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade" id="portfolio">
            Данные портфолио
        </div>
        <div class="tab-pane fade" id="tests">
            Данные тестов
        </div>
    </div>
</div>
HTML;

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertTabExists('Тест', $crawler->filter('div.nav-tabs-custom'));
    }

    public function testAssertTabExists_ShouldThrowExceptionIfTabPaneNotFound()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Панель для вкладки с заголовком "Портфолио" не найдена.'
        );

        /** @noinspection HtmlUnknownAnchorTarget */
        $html = <<<'HTML'
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li>
            <a href="#portfolio">
                <i class="fa fa-exclamation-circle"></i>
                Портфолио
            </a>
        </li>
        <li>
            <a href="#tests" data-toggle="tab">
                <i class="fa fa-exclamation-circle"></i>
                Тесты
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade" id="port">
            Данные портфолио
        </div>
        <div class="tab-pane fade" id="tests">
            Данные тестов
        </div>
    </div>
</div>
HTML;

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertTabExists('Портфолио', $crawler->filter('div.nav-tabs-custom'));
    }

    public function testAssertTabNotExists_ShouldNotThrowException()
    {
        $html = <<<'HTML'
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li>
            <a href="#portfolio">
                <i class="fa fa-exclamation-circle"></i>
                Портфолио
            </a>
        </li>
        <li>
            <a href="#tests" data-toggle="tab">
                <i class="fa fa-exclamation-circle"></i>
                Тесты
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade" id="portfolio">
            Данные портфолио
        </div>
        <div class="tab-pane fade" id="tests">
            Данные тестов
        </div>
    </div>
</div>
HTML;

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertTabNotExists('Аттестации', $crawler->filter('div.nav-tabs-custom'));
    }

    public function testAssertTabNotExists_ShouldThrowException()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches('.Вкладка с заголовком "Портфолио" найдена.');

        $html = <<<'HTML'
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li>
            <a href="#portfolio">
                <i class="fa fa-exclamation-circle"></i>
                Портфолио
            </a>
        </li>
        <li>
            <a href="#tests" data-toggle="tab">
                <i class="fa fa-exclamation-circle"></i>
                Тесты
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade" id="portfolio">
            Данные портфолио
        </div>
        <div class="tab-pane fade" id="tests">
            Данные тестов
        </div>
    </div>
</div>
HTML;

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertTabNotExists('Портфолио', $crawler->filter('div.nav-tabs-custom'));
    }

    public function dataProvider_Tabs(): array
    {
        $html = <<<'HTML'
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li>
            <a href="#tab_1">
                <i class="fa fa-exclamation-circle has-errors" aria-hidden="true"></i>
                Salary
            </a>
        </li>
        <li>
            <a href="#tab_2" data-toggle="tab">
                <i class="fa fa-exclamation-circle has-errors hide" aria-hidden="true"></i>
                    Professional activity
            </a>
        </li>
    </ul>
</div>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_Tabs
     */
    public function testAssertTabExclamationIconExists_ShouldThrowException(
        string $html
    ) {
        $tabLabel = 'Professional activity';

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            "Tab with title \"{$tabLabel}\" and exclamation mark icon not found"
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertTabExclamationIconExists($tabLabel, $crawler);
    }

    /**
     * @dataProvider dataProvider_Tabs
     */
    public function testAssertTabExclamationIconExists_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $tabLabel = 'Salary';

        $this->assertTabExclamationIconExists($tabLabel, $crawler);
    }

    /**
     * @dataProvider dataProvider_Tabs
     */
    public function testAssertTabExclamationIconNotExists_ShouldThrowException(
        string $html
    ) {
        $tabLabel = 'Salary';

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            "Tab with title \"$tabLabel\" and exclamation mark icon exists"
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertTabExclamationIconNotExists($tabLabel, $crawler);
    }

    /**
     * @dataProvider dataProvider_Tabs
     */
    public function testAssertTabExclamationIconNotExists_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $tabLabel = 'Professional activity';

        $this->assertTabExclamationIconNotExists($tabLabel, $crawler);
    }
}

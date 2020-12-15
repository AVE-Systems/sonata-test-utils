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
}

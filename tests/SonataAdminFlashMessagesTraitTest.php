<?php

namespace Tests;

use AveSystems\SonataTestUtils\SonataAdminFlashMessagesTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

final class SonataAdminFlashMessagesTraitTest extends TestCase
{
    use SonataAdminFlashMessagesTrait;

    public function dataProviderResponseWithFlashMessages(): array
    {
        $html = <<<'HTML'
<section class="content">
    <div class="alert alert-success fade in">
        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-hidden="true"
        >&times;</button>
        Представитель учредителя оповещен.
    </div>
    <div class="alert alert-success fade in">
        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-hidden="true"
        >&times;</button>
        Отправлено в архив.
    </div>
    <div class="alert alert-warning fade in">
        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-hidden="true"
        >&times;</button>
        Предупреждение.
    </div>
    <div class="alert alert-error fade in">
        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-hidden="true"
        >&times;</button>
        Произошла ошибка в редактировании названия.
    </div>
    <div class="alert alert-error fade in">
        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-hidden="true"
        >&times;</button>
        Не удалось оповестить админа.
    </div>
    <div class="alert alert-info fade in">
        <button 
            type="button" 
            class="close" 
            data-dismiss="alert" 
            aria-hidden="true"
        >×</button>
        Данная справка является архивной.
    </div>
</section>
HTML;

        return [[$html]];
    }

    public function dataProviderResponseWithoutFlashMessages(): array
    {
        $html = <<<'HTML'
<section class="content">
  <div>
        Оповещение отправлено.
  </div>
  <div>
        Произошла ошибка в редактировании названия.
  </div>
  <div>
        Предупреждение.
  </div>
</section>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashSuccessMessageExists_UsingResponseWithFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->assertFlashSuccessMessageExists(
            'Представитель учредителя оповещен',
            $crawler
        );

        $this->assertFlashSuccessMessageExists(
            'Отправлено в архив',
            $crawler
        );
        $this->expectException(ExpectationFailedException::class);
        $this->assertFlashErrorMessageExists(
            'Отправлено в архив',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithoutFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashSuccessMessage_UsingResponseWithoutFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);
        $this->assertFlashSuccessMessageExists(
            'Оповещение отправлено.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashWarningMessageExists_UsingResponseWithFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->assertFlashWarningMessageExists(
            'Предупреждение',
            $crawler
        );
        $this->expectException(ExpectationFailedException::class);
        $this->assertFlashErrorMessageExists(
            'Предупреждение',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithoutFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashWarningMessage_UsingResponseWithoutFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);
        $this->assertFlashWarningMessageExists(
            'Предупреждение',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashErrorMessageExists_UsingResponseWithFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->assertFlashErrorMessageExists(
            'Произошла ошибка в редактировании названия.',
            $crawler
        );

        $this->assertFlashErrorMessageExists(
            'Не удалось оповестить админа.',
            $crawler
        );

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashSuccessMessageExists(
            'Не удалось оповестить админа.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithoutFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashErrorMessageExists_UsingResponseWithoutFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);
        $this->assertFlashErrorMessageExists(
            'Произошла ошибка в редактировании названия.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashErrorMessagesCount_UsingResponseWithFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->assertFlashErrorMessagesCount(2, $crawler);
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashInfoMessageExists_UsingResponseWithFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->assertFlashInfoMessageExists(
            'Данная справка является архивной',
            $crawler
        );

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashInfoMessageExists(
            'Отправлено в архив',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithoutFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashInfoMessage_UsingResponseWithoutFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);
        $this->assertFlashInfoMessageExists(
            'Оповещение отправлено.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testFindFlashErrorMessages_UsingResponseWithFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $nodes = $this->findFlashErrorMessages($crawler);

        $this->assertCount(2, $nodes);
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testFindFlashSuccessMessages_UsingResponseWithFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $nodes = $this->findFlashSuccessMessages($crawler);

        $this->assertCount(2, $nodes);
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testFindFlashWarningMessages_UsingResponseWithFlashMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $nodes = $this->findFlashWarningMessages($crawler);

        $this->assertCount(1, $nodes);
    }

    public function getCrawler(): Crawler
    {
        return new Crawler();
    }
}

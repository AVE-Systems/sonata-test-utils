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
    <div class="alert alert-warning fade in">
        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-hidden="true"
        >&times;</button>
        Некритичная ошибка.
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
        Информационное сообщение.
    </div>
    <div class="alert alert-info fade in">
        <button 
            type="button" 
            class="close" 
            data-dismiss="alert" 
            aria-hidden="true"
        >×</button>
        Оповещение.
    </div>
</section>
HTML;

        return [[$html]];
    }

    public function dataProvider_NoFlash(): array
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
    public function testAssertFlashSuccessMessageExists_Success(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->assertFlashSuccessMessageExists(
            '× Представитель учредителя оповещен.',
            $crawler
        );

        $this->assertFlashSuccessMessageExists(
            '× Отправлено в архив.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashSuccessMessageExists_OtherType_Fail(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashErrorMessageExists(
            '× Отправлено в архив',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_NoFlash
     *
     * @param string $content
     */
    public function testAssertFlashSuccessMessage_NoFlash_Fail(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashSuccessMessageExists(
            '× Оповещение отправлено.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashWarningMessageExists_Success(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->assertFlashWarningMessageExists(
            '× Предупреждение.',
            $crawler
        );

        $this->assertFlashWarningMessageExists(
            '× Некритичная ошибка.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashWarningMessageExists_OtherType_Fail(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashErrorMessageExists(
            '× Предупреждение',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_NoFlash
     *
     * @param string $content
     */
    public function testAssertFlashWarningMessage_NoFlash_Fail(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashWarningMessageExists(
            '× Предупреждение',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashErrorMessageExists_Success(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->assertFlashErrorMessageExists(
            '× Произошла ошибка в редактировании названия.',
            $crawler
        );

        $this->assertFlashErrorMessageExists(
            '× Не удалось оповестить админа.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashErrorMessageExists_OtherType_Fail(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashSuccessMessageExists(
            '× Не удалось оповестить админа.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_NoFlash
     *
     * @param string $content
     */
    public function testAssertFlashErrorMessageExists_NoFlash_Fail(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashErrorMessageExists(
            '× Произошла ошибка в редактировании названия.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashErrorMessagesCount_Success(
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
    public function testAssertFlashErrorMessagesCount_Fail(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashErrorMessagesCount(1, $crawler);
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashInfoMessageExists_Success(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->assertFlashInfoMessageExists(
            '× Информационное сообщение.',
            $crawler
        );

        $this->assertFlashInfoMessageExists(
            '× Оповещение.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testAssertFlashInfoMessageExists_OtherType_Fail(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashInfoMessageExists(
            '× Отправлено в архив',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_NoFlash
     *
     * @param string $content
     */
    public function testAssertFlashInfoMessage_NoFlash_Fail(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $this->expectException(ExpectationFailedException::class);

        $this->assertFlashInfoMessageExists(
            '× Оповещение отправлено.',
            $crawler
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testFindFlashErrorMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $messages = $this->findFlashErrorMessages($crawler);

        $this->assertEquals(
            [
                '× Произошла ошибка в редактировании названия.',
                '× Не удалось оповестить админа.'
            ],
            $messages
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testFindFlashSuccessMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $messages = $this->findFlashSuccessMessages($crawler);

        $this->assertEquals(
            [
                '× Представитель учредителя оповещен.',
                '× Отправлено в архив.'
            ],
            $messages
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testFindFlashWarningMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $messages = $this->findFlashWarningMessages($crawler);

        $this->assertEquals(
            [
                '× Предупреждение.',
                '× Некритичная ошибка.'
            ],
            $messages
        );
    }

    /**
     * @dataProvider dataProviderResponseWithFlashMessages
     *
     * @param string $content
     */
    public function testFindFlashInfoMessages(
        string $content
    ): void {
        $crawler = $this->getCrawler();
        $crawler->addHtmlContent($content);

        $messages = $this->findFlashInfoMessages($crawler);

        $this->assertEquals(
            [
                '× Информационное сообщение.',
                '× Оповещение.',
            ],
            $messages
        );
    }

    public function getCrawler(): Crawler
    {
        return new Crawler();
    }
}

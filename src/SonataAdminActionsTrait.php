<?php

namespace AveSystems\SonataTestUtils;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Нужен для упрощения проверки наличия пунктов в меню "Действия",
 * оформленных в стиле "SonataAdminBundle".
 * Должен использоваться в тестах, наследованных от "TestCase".
 *
 * @method void assertContains($needle, $haystack, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false)
 * @method void assertNotContains($needle, $haystack, string $message = '', bool $ignoreCase = false, bool $checkForObjectIdentity = true, bool $checkForNonObjectIdentity = false)
 */
trait SonataAdminActionsTrait
{
    /**
     * Проверяет, что сверху на странице присутствует переданное действие.
     */
    protected function assertHeaderActionButtonExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $actualActions = $this->extractTextsFromElementsByXPath(
            $crawler,
            $this->getHeaderAllActionButtonsXPath()
        );

        $this->assertContains(
            $actionTitle,
            $actualActions,
            sprintf('На странице нет действия "%s"', $actionTitle)
        );
    }

    /**
     * Проверяет, что сверху на странице не присутствует переданное действие.
     */
    protected function assertHeaderActionButtonNotExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $actualActions = $this->extractTextsFromElementsByXPath(
            $crawler,
            $this->getHeaderAllActionButtonsXPath()
        );

        $this->assertNotContains(
            $actionTitle,
            $actualActions,
            sprintf('На странице есть действие "%s"', $actionTitle)
        );
    }

    /**
     * Проверяет, что сверху на странице список действий соответствует
     * переданному.
     */
    protected function assertHeaderActionButtonsEqual(
        array $actions,
        Crawler $crawler
    ): void {
        $actualActions = $this->extractTextsFromElementsByXPath(
            $crawler,
            $this->getHeaderAllActionButtonsXPath()
        );

        $this->assertEquals(
            $actions,
            $actualActions,
            'Действия сверху страницы не соответствуют ожидаемым'
        );
    }

    /**
     * Проверяет, что на странице сверху есть фильтр с переданными полями.
     */
    protected function assertHeaderFilterFieldsEqual(
        array $fields,
        Crawler $crawler
    ): void {
        $actualFields = $this->extractTextsFromElementsByXPath(
            $crawler,
            $this->getHeaderFilterListXPath().'//li//a[contains(@class, "sonata-toggle-filter")]'
        );

        $this->assertEquals(
            $fields,
            $actualFields,
            'Фильтры сверху страницы не соответствуют ожидаемым'
        );
    }

    /**
     * Проверяет, что на странице списка не присутствует переданное
     * групповое действие.
     */
    protected function assertListBatchActionButtonNotExists(
        string $actionTitle,
        Crawler $crawler
    ) {
        $actualActions = $this->extractTextsFromElementsByXPath(
            $crawler,
            $this->getListBatchAllActionButtonsXPath()
        );

        $this->assertNotContains(
            $actionTitle,
            $actualActions,
            sprintf('На странице списка есть групповое действие "%s"', $actionTitle)
        );
    }

    /**
     * Проверяет, что на странице списка список групповых действий соответствует
     * переданному.
     */
    protected function assertListBatchActionButtonsEqual(
        array $actions,
        Crawler $crawler
    ) {
        $actualActions = $this->extractTextsFromElementsByXPath(
            $crawler,
            $this->getListBatchAllActionButtonsXPath()
        );

        $this->assertEquals(
            $actions,
            $actualActions,
            'На странице списка групповые действия не соответствуют ожидаемым'
        );
    }

    /**
     * Проверяет, что на странице создания/редактирования
     * отсутствует кнопка, соответствующая переданному действию.
     *
     * @param string  $actionTitle
     * @param Crawler $crawler
     */
    protected function assertFormActionButtonNotExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $actualActions = $this->extractTextsFromElementsByXPath(
            $crawler,
            $this->getFormAllActionButtonsXPath()
        );

        $this->assertNotContains(
            $actionTitle,
            $actualActions,
            "На форме есть кнопка '$actionTitle'"
        );
    }

    /**
     * Проверяет, что на странице создания/редактирования
     * присутствует кнопка, соответствующая переданному действию.
     *
     * @param string  $actionTitle
     * @param Crawler $crawler
     */
    protected function assertFormActionButtonExists(
        string $actionTitle,
        Crawler $crawler
    ): void {
        $actualActions = $this->extractTextsFromElementsByXPath(
            $crawler,
            $this->getFormAllActionButtonsXPath()
        );

        $this->assertContains(
            $actionTitle,
            $actualActions,
            "На форме нет кнопки '$actionTitle'"
        );
    }

    /**
     * Проверяет, что на странице создания/редактирования
     * кнопки действий соответствуют переданным.
     */
    protected function assertFormActionButtonsEqual(array $actions, Crawler $form)
    {
        $actualActions = $this->extractTextsFromElementsByXPath(
            $form,
            $this->getFormAllActionButtonsXPath()
        );

        $this->assertEquals(
            $actions,
            $actualActions,
            'Кнопки действий снизу формы не соответствуют ожидаемым'
        );
    }

    /**
     * Возвращает XPath, соответствующий всем действиям на стандартной странице
     * Sonata Admin.
     *
     * @return string
     */
    private function getHeaderAllActionButtonsXPath(): string
    {
        return $this->getHeaderActionsListXPath().'//li//a[contains(@class, "sonata-action-element")]';
    }

    private function getSonataContentXPath(): string
    {
        return 'section[contains(@class, "content")]';
    }

    private function getSonataContentHeaderXPath(): string
    {
        return 'section[contains(@class, "content-header")]';
    }

    private function getHeaderListsXPath(): string
    {
        return "//{$this->getSonataContentHeaderXPath()}//nav"
            .'//div[contains(@class, "navbar-collapse")]/ul';
    }

    private function getHeaderActionsListXPath(): string
    {
        return "{$this->getHeaderListsXPath()}[1]";
    }

    private function getHeaderFilterListXPath(): string
    {
        return "{$this->getHeaderListsXPath()}[2]";
    }

    private function getListBatchAllActionButtonsXPath(): string
    {
        return "//div[contains(@class, 'box-footer')]//select[@name='action']/option";
    }

    /**
     * Возвращает XPath-путь ко всем кнопкам действий снизу формы.
     */
    private function getFormAllActionButtonsXPath(): string
    {
        $containerXPath = "div[contains(@class, 'sonata-ba-form-actions')]";
        $buttonXpath = "button[@type='submit']";
        $linkTypeButtonXpath = 'a';

        return "(//$containerXPath/$buttonXpath | //$containerXPath/$linkTypeButtonXpath)";
    }

    private function extractTextsFromElementsByXPath(
        Crawler $crawler,
        string $xpath
    ): array {
        return $crawler
            ->filterXPath($xpath)
            ->each(function (Crawler $element) {
                return $element->text();
            });
    }
}

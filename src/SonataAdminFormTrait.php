<?php

namespace AveSystems\SonataTestUtils;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Нужен для упрощения проверки полей формы, оформленных в стиле
 * "SonataAdminBundle". Должен использоваться в тестах, наследованных от
 * "TestCase".
 *
 * @method void assertCount(int $expectedCount, $haystack, string $message = '')
 * @method void assertThat($value, Constraint $constraint, string $message = '')
 * @method void assertStringContainsString(string $string, string $haystack, string $message = '')
 *
 * @see Assert::assertCount
 * @see Assert::assertThat
 * @see Assert::assertStringContainsString
 */
trait SonataAdminFormTrait
{
    /**
     * Проверяет, что содержимое поля ввода формы, найденного по заголовку,
     * содержит переданное значение.
     *
     * @param string  $expectedInputValue
     * @param string  $label
     * @param Crawler $form               корневым узлом должна быть нужная форма
     */
    protected function assertFormTextFieldValueEquals(
        string $expectedInputValue,
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'Значение в поле ввода "%s" не соответствует ожидаемому',
            $label
        );

        $constraint = new IsEqual($expectedInputValue);

        $this->assertFormTextFieldExists($label, $form);

        $inputValue = $this->getNormalizedSpaceFormTextFieldValue(
            $label,
            $form
        );

        $this->assertThat($inputValue, $constraint, $message);
    }

    /**
     * Проверяет, что содержимое числового поля ввода формы, найденного по
     * заголовку, содержит переданное значение.
     *
     * @param string  $expectedInputValue
     * @param string  $label
     * @param Crawler $form               корневым узлом должна быть нужная форма
     */
    protected function assertFormNumberFieldValueEquals(
        string $expectedInputValue,
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'Значение в поле ввода "%s" не соответствует ожидаемому',
            $label
        );

        $constraint = new IsEqual($expectedInputValue);

        $this->assertFormNumberFieldExists($label, $form);

        $inputValue = $this->getNormalizedSpaceFormNumberFieldValue(
            $label,
            $form
        );

        $this->assertThat($inputValue, $constraint, $message);
    }

    /**
     * Проверяет, что содержимое поля ввода формы, найденного по заголовку,
     * содержит переданное значение.
     *
     * @param string  $expectedInputValue
     * @param string  $label
     * @param Crawler $form               корневым узлом должна быть нужная форма
     */
    protected function assertFormTextareaFieldValueEquals(
        string $expectedInputValue,
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'Значение в поле ввода "%s" не соответствует ожидаемому',
            $label
        );

        $constraint = new IsEqual($expectedInputValue);

        $this->assertFormTextareaFieldExists($label, $form);

        $inputValue = $this->getNormalizedSpaceFormTextareaFieldValue(
            $label,
            $form
        );

        $this->assertThat($inputValue, $constraint, $message);
    }

    /**
     * Проверяет, что содержимое поле ввода, найденное по заголовку,
     * присутствует на форме.
     *
     * @param string  $label
     * @param Crawler $form
     */
    private function assertFormTextFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
            'Не найдено поле с заголовком "%s"',
            $label
        );

        $inputXPath = "//{$this->getFormTextFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Проверяет, что содержимое числового поля ввода, найденное по заголовку,
     * присутствует на форме.
     *
     * @param string  $label
     * @param Crawler $form
     */
    private function assertFormNumberFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
            'Не найдено поле с заголовком "%s"',
            $label
        );

        $inputXPath = "//{$this->getFormNumberFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Проверяет, что содержимое поле ввода, найденное по заголовку,
     * присутствует на форме.
     *
     * @param string  $label
     * @param Crawler $form
     */
    private function assertFormTextareaFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
            'Не найдено поле с заголовком "%s"',
            $label
        );

        $inputXPath = "//{$this->getFormTextareaFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Проверяет, что заданное поле в виде checkbox существует на форме.
     *
     * @param string  $label
     * @param Crawler $form
     */
    private function assertFormCheckboxFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
            'Не найдено поле с заголовком "%s"',
            $label
        );

        $inputXPath = "//{$this->getFormCheckboxFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Возвращает XPath-путь к флажковому полю формы с заданным заголовком.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormCheckboxFieldXPath(string $label): string
    {
        $fieldXPath = "form//div[contains(@class, 'sonata-ba-field')]";
        $labelXPath = "label/span[contains(@class, 'control-label__text') and normalize-space()='$label']";
        $checkboxXPath = "input[@type='checkbox']";

        return "$fieldXPath//{$labelXPath}/preceding-sibling::{$checkboxXPath}";
    }

    /**
     * Возвращает путь для получения файлового поля по его названию.
     *
     * @param string $label название (лейбл)
     *
     * @return string
     */
    protected function getFileFieldXPath(string $label)
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $fileFieldContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $fileFieldXPath = "input[@type='file']";

        return "$labelXPath/following-sibling::{$fileFieldContainerXPath}//{$fileFieldXPath}";
    }

    /**
     * Проверяет, что файловое поле с данным заголовком существует в форме.
     *
     * @param string  $label наименование поля
     * @param Crawler $form  ссылка на краулер по форме
     */
    protected function assertFileFormFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
            'Не найдено поле с заголовком "%s"',
            $label
        );

        $inputXPath = "//{$this->getFileFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($inputXPath),
            $message
        );
    }

    /**
     * Проверяет, что списковое поле имеет ожидаемое значение.
     *
     * @param string  $expectedValue ожидаемое значение
     * @param string  $label         наименование поля
     * @param Crawler $form          ссылка на краулер по форме
     */
    protected function assertSelectFormFieldValueEquals(
        string $expectedValue,
        string $label,
        Crawler $form
    ) {
        $message = sprintf(
            'Не найдено поле с заголовком "%s" и значением "%s"',
            $label,
            $expectedValue
        );

        $selectXPath = "//{$this->getSelectFieldXPath($label)}";
        $selectElement = $form->filterXPath($selectXPath)->first();
        $value = $this->getSelectedValueFromSelect($selectElement);

        $this->assertEquals($expectedValue, $value, $message);
    }

    /**
     * Проверяет, что списковое поле с данным заголовком существует в форме.
     *
     * @param string  $label наименование поля
     * @param Crawler $form  ссылка на краулер по форме
     */
    protected function assertSelectFormFieldExists(string $label, Crawler $form)
    {
        $message = sprintf(
            'Не найдено поле с заголовком "%s"',
            $label
        );

        $selectXPath = "//{$this->getSelectFieldXPath($label)}";

        $this->assertCount(
            1,
            $form->filterXPath($selectXPath),
            $message
        );
    }

    /**
     * Возвращает XPath-путь к списковому полю формы с заданным заголовком.
     *
     * @param string $label
     *
     * @return string
     */
    private function getSelectFieldXPath(string $label): string
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $inputContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $inputXPath = "select[contains(@class, 'form-control')]";

        return "$labelXPath/following-sibling::{$inputContainerXPath}//{$inputXPath}";
    }

    /**
     * Возвращает XPath-путь к заданному заголовку поля формы.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormFieldLabelXPath(string $label): string
    {
        $formGroupXPath = 'form//div[contains(@class, "form-group")]';
        $labelXPath = "label[contains(@class, 'control-label') and normalize-space()='$label']";

        return "$formGroupXPath/$labelXPath";
    }

    /**
     * Возвращает XPath-путь к полю формы с заданным заголовком.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormTextFieldXPath(string $label): string
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $inputContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $inputXPath = "input[@type='text' and contains(@class, 'form-control')]";

        return "$labelXPath/following-sibling::{$inputContainerXPath}//{$inputXPath}";
    }

    /**
     * Возвращает XPath-путь к числовому полю формы с заданным заголовком.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormNumberFieldXPath(string $label): string
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $inputContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $inputXPath = "input[@type='number' and contains(@class, 'form-control')]";

        return "$labelXPath/following-sibling::{$inputContainerXPath}//{$inputXPath}";
    }

    /**
     * Возвращает XPath-путь к полю формы с заданным заголовком.
     *
     * @param string $label
     *
     * @return string
     */
    private function getFormTextareaFieldXPath(string $label): string
    {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $textareaContainerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $textareaXPath = "textarea[contains(@class, 'form-control')]";

        return "$labelXPath/following-sibling::$textareaContainerXPath//{$textareaXPath}";
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
        $actionButtonXPath = $this->getFormActionButtonXPath($actionTitle);

        $this->assertCount(
            0,
            $crawler->filterXPath($actionButtonXPath),
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
        $actionButtonXPath = $this->getFormActionButtonXPath($actionTitle);

        $this->assertCount(
            1,
            $crawler->filterXPath($actionButtonXPath),
            "На форме нет кнопки '$actionTitle'"
        );
    }

    /**
     * Возвращает XPath-путь к кнопке действий над формой с заданным текстом.
     *
     * @param string $buttonText
     *
     * @return string
     */
    private function getFormActionButtonXPath(string $buttonText): string
    {
        $containerXPath = "div[contains(@class, 'sonata-ba-form-actions')]";
        $buttonXpath = "button[@type='submit' and normalize-space()='$buttonText']";
        $linkTypeButtonXpath = "a[normalize-space()='$buttonText']";

        return "(//$containerXPath/$buttonXpath | //$containerXPath/$linkTypeButtonXpath)";
    }

    /**
     * Возвращает содержимое поля формы с заданным заголовком без начальных,
     * конечных и повторяющихся пробелов.
     *
     * @param string  $label
     * @param Crawler $form
     *
     * @return string
     */
    private function getNormalizedSpaceFormTextFieldValue(
        string $label,
        Crawler $form
    ) {
        $xPathToNormalize = "//{$this->getFormTextFieldXPath($label)}/@value";

        return $form->evaluate("normalize-space($xPathToNormalize)")[0];
    }

    /**
     * Возвращает содержимое числового поля формы с заданным заголовком без
     * начальных, конечных и повторяющихся пробелов.
     *
     * @param string  $label
     * @param Crawler $form
     *
     * @return string
     */
    private function getNormalizedSpaceFormNumberFieldValue(
        string $label,
        Crawler $form
    ) {
        $xPathToNormalize = "//{$this->getFormNumberFieldXPath($label)}/@value";

        return $form->evaluate("normalize-space($xPathToNormalize)")[0];
    }

    /**
     * Возвращает содержимое поля формы с заданным заголовком без начальных,
     * конечных и повторяющихся пробелов.
     *
     * @param string  $label
     * @param Crawler $form
     *
     * @return string
     */
    private function getNormalizedSpaceFormTextareaFieldValue(
        string $label,
        Crawler $form
    ) {
        $xPathToNormalize = "//{$this->getFormTextareaFieldXPath($label)}";

        return $form->evaluate("normalize-space($xPathToNormalize)")[0];
    }

    /**
     * Получает кролер для таблицы по ее названию
     * (актуально для страницы редактирования-создания в админке).
     *
     * @param Crawler $form  родительская форма
     * @param string  $title заголовок таблицы
     *
     * @return Crawler
     */
    protected function getSubAdminTableByItsTitle(string $title, Crawler $form): Crawler
    {
        $labelXPath = $this->getFormFieldLabelXPath($title);
        $tableXPath = "//$labelXPath/following-sibling::div//table[contains(@class,'table')]";

        return $form->filterXPath($tableXPath);
    }

    /**
     * Получает значение выбранного элемента выпающего списка.
     *
     * @param Crawler $selectElement корневым элементом должен быть select
     *
     * @return string
     */
    private function getSelectedValueFromSelect(Crawler $selectElement): string
    {
        $selectedOption = $selectElement->filter('option[selected]');

        return trim($selectedOption->attr('value'));
    }

    /**
     * Проверяет есть ли заданая ошибка для элемента формы с определенным лэйблом.
     *
     * @param string  $label   лэйбл для поиска элемента формы
     * @param string  $error   ожидаемая строка ошибки
     * @param Crawler $crawler
     */
    protected function assertFormFieldContainsError(
        string $label,
        string $error,
        Crawler $crawler
    ) {
        $labelXPath = $this->getFormFieldLabelXPath($label);
        $containerXPath = "div[contains(@class, 'sonata-ba-field')]";
        $errorListXPath = "div[contains(@class, 'sonata-ba-field-error-messages')]";

        $errorsPath = "//$labelXPath/following-sibling::{$containerXPath}//{$errorListXPath}";
        $errorsContainer = $crawler->filterXPath($errorsPath);

        $this->assertCount(
            1,
            $errorsContainer,
            'Не удалось однозначно найти такое поле с ошибками'
        );

        $this->assertStringContainsString(
            $error,
            $errorsContainer->text(),
            'Ошибка не равна ожидаемой'
        );
    }
}

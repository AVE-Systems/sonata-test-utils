<?php

namespace Tests;

use AveSystems\SonataTestUtils\SonataAdminFormTrait;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

class SonataAdminFormTraitTest extends TestCase
{
    use SonataAdminFormTrait;

    public function dataProvider_TestAssertFormFieldValueEquals(): array
    {
        $html = <<<'HTML'
          <form>
                <div class="row">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="box box-primary">
                          <div class="box-body">
                            <div class="sonata-ba-collapsed-fields">
                              <div class="form-group">
                                <label class="control-label required">
                                  Время проведения
                                </label>
                                <div class="sonata-ba-field sonata-ba-field-standard-natural">
                                  <div class="input-group">
                                    <div class="input-group date">
                                      <input type="text" 
                                        class="sonata-medium-date form-control" 
                                        name="sbb9cecc71b[timeOfAppointment]"
                                        value="27.08.2019 09:24"
                                      >
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label required">
                                  Продолжительность
                                </label>
                                <div class="sonata-ba-field sonata-ba-field-standard-natural">
                                  <div class="input-group">
                                    <div class="input-group date">
                                      <input type="number" 
                                        class="form-control" 
                                        name="sbb9cecc71b[duration]"
                                        value="22"
                                      >
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="control-label required">
                                  Повестка
                                </label>
                                <div class="sonata-ba-field sonata-ba-field-standard-natural">
                                  <textarea 
                                    class="form-control" 
                                    name="sbb9cecc71b[description]"
                                  >
                                  Lorem Ipsum - это текст-"рыба",
                                  часто используемый в печати.
                                  </textarea>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_TestAssertFormFieldValueEquals
     *
     * @param string $html
     */
    public function testAssertFormTextFieldValueEquals_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Значение в поле ввода "Время проведения" не соответствует ожидаемому.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormTextFieldValueEquals(
            'Lorem Ipsum - это текст-"рыба", часто используемый в печати.',
            'Время проведения',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormFieldValueEquals
     *
     * @param string $html
     */
    public function testAssertFormTextFieldValueEquals_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormTextFieldValueEquals(
            '27.08.2019 09:24',
            'Время проведения',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormFieldValueEquals
     *
     * @param string $html
     */
    public function testAssertFormNumberFieldValueEquals_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Значение в поле ввода "Продолжительность" не соответствует ожидаемому.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormNumberFieldValueEquals(
            '22000',
            'Продолжительность',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormFieldValueEquals
     *
     * @param string $html
     */
    public function testAssertFormNumberFieldValueEquals_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormNumberFieldValueEquals(
            '22',
            'Продолжительность',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormFieldValueEquals
     *
     * @param string $html
     */
    public function testAssertNumberFormFieldExists_Exists(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormNumberFieldExists(
            'Продолжительность',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormFieldValueEquals
     *
     * @param string $html
     */
    public function testAssertNumberFormFieldExists_NotExists(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Не найдено поле с заголовком "Другое поле"'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormNumberFieldExists(
            'Другое поле',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormFieldValueEquals
     *
     * @param string $html
     */
    public function testAssertFormTextareaFieldValueEquals_ShouldThrowException(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessageMatches(
            '.Значение в поле ввода "Повестка" не соответствует ожидаемому.'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormTextareaFieldValueEquals(
            '27.08.2019 09:24',
            'Повестка',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_TestAssertFormFieldValueEquals
     *
     * @param string $html
     */
    public function testAssertFormTextareaFieldValueEquals_ShouldNotThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormTextareaFieldValueEquals(
            'Lorem Ipsum - это текст-"рыба", часто используемый в печати.',
            'Повестка',
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

    public function dataProvider_SelectForm(): array
    {
        $html = <<<'HTML'
         <form>
            <div class="form-group" id="sonata-ba-field-container-sbb9cecc71b_list">
                <label class="control-label" for="sbb9cecc71b_list">
                    List
                </label>
                <div class="sonata-ba-field sonata-ba-field-standard-natural">        
                    <select id="sbb9cecc71b_list" 
                            name="sbb9cecc71b[list]" 
                            class="form-control" 
                            title="List"
                    >
                        <option value=""></option>
                        <option value="2">Not selected option</option>
                        <option value="1" selected="selected">Selected option</option>
                    </select>
                </div>
            </div>
        </form>
HTML;

        return [[$html]];
    }

    public function dataProvider_SelectForm_NoSelected(): array
    {
        $html = <<<'HTML'
            <form>
                <div class="form-group">
                    <label class="control-label" for="sbb9cecc71b_list">
                        List
                    </label>
                    <div class="sonata-ba-field sonata-ba-field-standard-natural">
                        <select id="sbb9cecc71b_list" 
                                name="sbb9cecc71b[list]" 
                                class="form-control" 
                                title="List"
                        >
                            <option value=""></option>
                            <option value="2">Not selected option 2</option>
                            <option value="1">Not selected option 1</option>
                        </select>
                    </div>
                </div>
            </form>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_SelectForm
     *
     * @param string $html
     */
    public function testAssertSelectFormFieldExists_Exists(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertSelectFormFieldExists(
            'List',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_SelectForm
     *
     * @param string $html
     */
    public function testAssertSelectFormFieldExists_NotExists(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Не найдено поле с заголовком "Другое поле"'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertSelectFormFieldExists(
            'Другое поле',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_SelectForm
     *
     * @param string $html
     */
    public function testAssertSelectOptionExists_Exists(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertSelectOptionExists(
            'List',
            'Not selected option',
            $crawler
        );

        $this->assertSelectOptionExists(
            'List',
            'Selected option',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_SelectForm
     *
     * @param string $html
     */
    public function testAssertSelectOptionExists_NotExists(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'The value "Not existed option" in the field with '.
            'title "List" not found'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertSelectOptionExists(
            'List',
            'Not existed option',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_SelectForm
     *
     * @param string $html
     */
    public function testAssertSelectFormFieldValueEquals_Equals(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $expectedValueThatSelected = '1';

        $this->assertSelectFormFieldValueEquals(
            $expectedValueThatSelected,
            'List',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_SelectForm_NoSelected
     *
     * @param string $html
     */
    public function testAssertSelectFormFieldValueEquals_EmptyValue(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $expectedValueThatSelected = '';

        $this->assertSelectFormFieldValueEquals(
            $expectedValueThatSelected,
            'List',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_SelectForm
     *
     * @param string $html
     */
    public function testAssertSelectFormFieldValueEquals_NotEquals(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'The field with title "List" and value "2" not found'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $expectedValueThatNotSelected = '2';

        $this->assertSelectFormFieldValueEquals(
            $expectedValueThatNotSelected,
            'List',
            $crawler
        );
    }

    public function dataProvider_MultipleSelectFormWithAutocomplete()
    {
        $html = <<<'HTML'
            <form>
                <div class="form-group">
                    <label class="control-label">
                      Разрешенные пользователи
                    </label>
                    <div class="sonata-ba-field sonata-ba-field-standard-natural">
                        <input type="text" id="s5f9c101b7577a_allowedUsers_autocomplete_input" value="" class="" />
                        <select id="s5f9c101b7577a_allowedUsers_autocomplete_input_v4" data-sonata-select2="false">
                            <option value="1" selected>user1@yandex.ru</option>
                            <option value="2" selected>user2@yandex.ru</option>
                            <option value="3" selected>user3@yandex.ru</option>
                        </select>
                        <div id="s5f9c101b7577a_allowedUsers_hidden_inputs_wrap">
                            <input type="hidden" name="s5f9c101b7577a[allowedUsers][]" value="1">
                            <input type="hidden" name="s5f9c101b7577a[allowedUsers][]" value="2">
                            <input type="hidden" name="s5f9c101b7577a[allowedUsers][]" value="3">
                        </div>
                        <div id="field_actions_s5f9c101b7577a_allowedUsers" class="field-actions"></div>
                    </div>
                </div>
            </form>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_MultipleSelectFormWithAutocomplete
     *
     * @param string $html
     */
    public function testAssertMultipleSelectFormFieldWithAutocompleteExists_Exists(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMultipleSelectFormFieldWithAutocompleteExists(
            'Разрешенные пользователи',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_MultipleSelectFormWithAutocomplete
     *
     * @param string $html
     */
    public function testAssertMultipleSelectFormFieldWithAutocompleteExists_NotExists(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Не найдено поле с заголовком "Другое поле"'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertMultipleSelectFormFieldWithAutocompleteExists(
            'Другое поле',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_MultipleSelectFormWithAutocomplete
     *
     * @param string $html
     */
    public function testAssertMultipleSelectFormFieldWithAutocompleteValueEquals_Equals(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $expectedValueThatSelected = ['2', '1', '3'];

        $this->assertMultipleSelectFormFieldWithAutocompleteValueEquals(
            $expectedValueThatSelected,
            'Разрешенные пользователи',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_MultipleSelectFormWithAutocomplete
     *
     * @param string $html
     */
    public function testAssertMultipleSelectFormFieldWithAutocompleteValueEquals_NotEquals_NotFound(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'В поле с заголовком "Разрешенные пользователи" не найдены значения "0", "4"'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $expectedValues = ['0', '1', '2', '3', '4'];

        $this->assertMultipleSelectFormFieldWithAutocompleteValueEquals(
            $expectedValues,
            'Разрешенные пользователи',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_MultipleSelectFormWithAutocomplete
     *
     * @param string $html
     */
    public function testAssertMultipleSelectFormFieldWithAutocompleteValueEquals_NotEquals_ExtraFound(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'В поле с заголовком "Разрешенные пользователи" найдены лишние значения "2", "3"'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $expectedValues = ['1'];

        $this->assertMultipleSelectFormFieldWithAutocompleteValueEquals(
            $expectedValues,
            'Разрешенные пользователи',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_MultipleSelectFormWithAutocomplete
     *
     * @param string $html
     */
    public function testAssertMultipleSelectFormFieldWithAutocompleteValueEquals_NotEquals_NotFoundAndExtraFound(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'В поле с заголовком "Разрешенные пользователи" не найдены значения "0", "4" и найдены лишние значения "2", "3"'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $expectedValues = ['0', '1', '4'];

        $this->assertMultipleSelectFormFieldWithAutocompleteValueEquals(
            $expectedValues,
            'Разрешенные пользователи',
            $crawler
        );
    }

    public function dataProvider_CheckboxForm()
    {
        $html = <<<'HTML'
            <form>
                <div class="sonata-ba-field sonata">
                    <div class="checkbox">
                    <label>
                        <input type="checkbox">
                        <span class="control-label__text">
                            Тестовый флаг
                        </span>
                    </label>
                    </div>
                </div>
                <div class="sonata-ba-field sonata">
                    <div class="checkbox">
                    <label>
                        <input type="checkbox" checked>
                        <span class="control-label__text">
                            Выбранный флаг
                        </span>
                    </label>
                    </div>
                </div>
            </form>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_CheckboxForm
     *
     * @param string $html
     */
    public function testAssertFormCheckboxFieldExists_Exists(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormCheckboxFieldExists(
            'Тестовый флаг',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_CheckboxForm
     *
     * @param string $html
     *
     * @todo Разобраться почему здесь используется метод assertSelectFormFieldExists
     * @todo https://trello.com/c/xyKQRV67
     */
    public function testAssertFormCheckboxFieldExists_NotExists(string $html)
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Не найдено поле с заголовком "Другое поле"'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertSelectFormFieldExists(
            'Другое поле',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_CheckboxForm
     *
     * @param string $html
     */
    public function testAssertFormCheckboxFieldExistsAndChecked_Checked(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormCheckboxFieldExistsAndChecked(
            'Выбранный флаг',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_CheckboxForm
     *
     * @param string $html
     */
    public function testAssertFormCheckboxFieldExistsAndChecked_ExistsButNotChecked(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Поле с заголовком "Тестовый флаг" не установлено во включенное '.
            'состояние'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormCheckboxFieldExistsAndChecked(
            'Тестовый флаг',
            $crawler
        );
    }

    /**
     * @dataProvider dataProvider_CheckboxForm
     *
     * @param string $html
     */
    public function testAssertFormCheckboxFieldExistsAndChecked_NotExists(
        string $html
    ) {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Не найдено поле с заголовком "Другое поле"'
        );

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $this->assertFormCheckboxFieldExistsAndChecked(
            'Другое поле',
            $crawler
        );
    }

    public function dataProvider_TableForm()
    {
        $html = <<<'HTML'
            <form>
                <div class="form-group">
                    <label class="control-label">
                        Тестовая таблица
                    </label>
                    <div class="sonata-ba-field">
                        <table class="table">
                            <tr>
                                <td>test</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">
                        Тестовая таблица 1
                    </label>
                    <div class="sonata-ba-field">
                        <div>
                            <table class="table">
                                <tr>
                                    <td>other</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_TableForm
     *
     * @param string $html
     */
    public function testGetSubAdminTableByItsTitle(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $tableCrawler = $this->getSubAdminTableByItsTitle('Тестовая таблица', $crawler);
        $this->assertCount(1, $tableCrawler);
        $this->assertEquals('test', $tableCrawler->filter('td')->text());

        $tableCrawler = $this->getSubAdminTableByItsTitle('Несуществующая', $crawler);
        $this->assertCount(0, $tableCrawler);
    }

    public function dataProvider_ErrorForm()
    {
        $html = <<<'HTML'
        <form>
            <div class="form-group has-error" id="sonata-ba-field-container-s26e8548772_code">
                <label class="control-label required" for="s26e8548772_code">
                    Поле с ошибкой
                </label>
                <div class="sonata-ba-field sonata-ba-field-standard-natural sonata-ba-field-error">
                    <input type="text"
                        id="s26e8548772_code" name="s26e8548772[code]"
                        class=" form-control"
                    >
                    <div class="help-block sonata-ba-field-error-messages">
                        <ul class="list-unstyled">
                            <li>
                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                                Текст ошибки
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_ErrorForm
     *
     * @param string $html
     */
    public function testAssertFormFieldContainsError_ThereIsError(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $label = 'Поле с ошибкой';
        $error = 'Текст ошибки';
        $this->assertFormFieldContainsError($label, $error, $crawler);
    }

    /**
     * @dataProvider dataProvider_ErrorForm
     *
     * @param string $html
     */
    public function testAssertFormFieldContainsError_ThereIsNoSuchField(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $label = 'Несуществующее поле';
        $error = 'Текст ошибки';

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Не удалось однозначно найти такое поле с ошибками'
        );
        $this->assertFormFieldContainsError($label, $error, $crawler);
    }

    /**
     * @dataProvider dataProvider_ErrorForm
     *
     * @param string $html
     */
    public function testAssertFormFieldContainsError_ThereIsNoSuchError(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $label = 'Поле с ошибкой';
        $error = 'Другая ошибка';

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Ошибка не равна ожидаемой'
        );
        $this->assertFormFieldContainsError($label, $error, $crawler);
    }

    public function dataProvider_TestAssertFileFormFieldExists()
    {
        $html = <<<'HTML'
        <form>
            <div class="form-group">
                <label class="control-label" for="sbe667f4009_file">
                    Справочное изображение
                </label>
                <div class="sonata-ba-field sonata-ba-field-standard-natural">
                    <input type="file" id="sbe667f4009_file" name="sbe667f4009[file]">
                    <span class="help-block sonata-ba-field-help">
                        <img src="/uploads/image.jpg" class="admin-preview">
                    </span>
                </div>
            </div>
        </form>
HTML;

        return [[$html]];
    }

    /**
     * @dataProvider dataProvider_TestAssertFileFormFieldExists
     *
     * @param string $html
     */
    public function testAssertFileFormFieldExists_Success(string $html)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $label = 'Справочное изображение';

        $this->assertFileFormFieldExists($label, $crawler);
    }

    /**
     * @dataProvider dataProvider_TestAssertFileFormFieldExists
     *
     * @param string $html
     */
    public function testAssertFileFormFieldExists_ShouldThrowException(
        string $html
    ) {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $label = 'Несуществующее поле';

        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage(
            'Не найдено файловое поле с заголовком "Несуществующее поле"'
        );
        $this->assertFileFormFieldExists($label, $crawler);
    }
}

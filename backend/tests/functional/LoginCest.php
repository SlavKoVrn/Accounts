<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\fixtures\AccountFixture;
use common\fixtures\TransactionFixture;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ],
            'account' => [
                'class' => AccountFixture::className(),
                'dataFile' => codecept_data_dir() . 'account_data.php'
            ],
            'transaction' => [
                'class' => TransactionFixture::className(),
                'dataFile' => codecept_data_dir() . 'transaction_data.php'
            ],
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {
        $I->amOnPage('/admin/site/login');
        $I->fillField('Username', 'erau');
        $I->fillField('Password', 'password_0');
        $I->click('login-button');

        $I->see('Выход (erau)', 'form button[type=submit]');
        $I->dontSeeLink('Выход');
        $I->dontSeeLink('Вход');
    }

    /**
     * @param FunctionalTester $I
     */
    public function createAccountSecure(FunctionalTester $I)
    {
        $I->amOnPage('/admin/account/create');
        $I->dontSee('Добавить счет');
        $I->dontSee('Сохранить','button[type=submit]');
        $I->fillField('Username', 'user1');
        $I->fillField('Password', '123');
        $I->click('login-button');
        $I->see('user1');
        $I->see('Добавить счет');
        $I->see('Сохранить','button[type=submit]');
        $I->fillField('Номер счета', 'schet-123');
        $I->fillField('Назначение счета', 'добавление счета - тест');
        $I->click('Сохранить','button[type=submit]');
        $I->see('Счет № schet-123');
        $I->seeLink('Счета');
        $I->click('Счета');
        $I->seeLink('Добавить счет');
        $I->see('schet-123','td');
        $I->click(['xpath'=>'(//a[@title="Перевод"])[3]']);
        $I->see('На счете недостаточно средств','div[class="alert-danger alert fade in"]');
    }

    /**
     * @param FunctionalTester $I
     */
    public function transactAccountNotOwnerSecure(FunctionalTester $I)
    {
        $I->amOnPage('/admin/account/3/transact');
        $I->fillField('Username', 'user1');
        $I->fillField('Password', '123');
        $I->click('login-button');
        $I->see('user1');
        $I->see('Работать можно только со своими счетами');
        $I->see('Forbidden (#403)');
    }

    /**
     * @param FunctionalTester $I
     */
    public function updateAccountSecure(FunctionalTester $I)
    {
        $I->amOnPage('/admin/account/1/update');
        $I->dontSee('Изменить счет № : schet-1');
        $I->fillField('Username', 'user1');
        $I->fillField('Password', '123');
        $I->click('login-button');
        $I->see('user1');
        $I->see('Изменить счет № : schet-1');
        $I->fillField('Номер счета', 'schet-1.1');
        $I->fillField('Назначение счета', 'для чего нужен счет');
        $I->click('Сохранить','button[type=submit]');
        $I->see('Счет № schet-1.1');
        $I->see('для чего нужен счет');
    }

    public function updateAccountNotOwnerSecure(FunctionalTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('Username', 'user1');
        $I->fillField('Password', '123');
        $I->click('login-button');
        $I->see('user1');
        $I->amOnPage('/admin/account/3/update');
        $I->see('Forbidden (#403)');
    }

    public function viewAccountSecure(FunctionalTester $I)
    {
        $I->amOnPage('/admin/account/1/view');
        $I->dontSee('Счет № 11-1');
        $I->fillField('Username', 'user1');
        $I->fillField('Password', '123');
        $I->click('login-button');
        $I->see('user1');
        $I->see('Счет № schet-1');
        $I->see('Владелец','.//tr/th');
        $I->see('user1','.//tr/td');
        $I->see('Номер счета','.//tr/th');
        $I->see('schet-1','.//tr/td');
    }

    public function viewAccountNotOwnerSecure(FunctionalTester $I)
    {
        $I->amOnPage('/admin/account/3/view');
        $I->dontSee('Счет № schet-1');
        $I->fillField('Username', 'user1');
        $I->fillField('Password', '123');
        $I->click('login-button');
        $I->see('user1');
        $I->dontSee('Счет № schet-1');
        $I->see('Работать можно только со своими счетами');
        $I->see('Forbidden (#403)');
    }

    public function deleteAccountSecure(FunctionalTester $I)
    {
        $I->amOnPage('/admin/account/1/delete');
        $I->fillField('Username', 'user1');
        $I->fillField('Password', '123');
        $I->click('login-button');
        $I->see('user1');
        $I->see('Method Not Allowed (#405)');
        $I->see('This URL can only handle the following request methods: POST.');
        $I->amOnPage('/admin/account');
        $I->see('Номер счета');
        $I->see('schet-1');
        $I->sendAjaxPostRequest('/admin/account/1/delete');
        $I->amOnPage('/admin/account');
        $I->see('Номер счета');
        $I->dontSee('schet-1');
    }

    public function deleteAccountNotOwnerSecure(FunctionalTester $I)
    {
        $I->amOnPage('/admin/account/3/delete');
        $I->fillField('Username', 'user1');
        $I->fillField('Password', '123');
        $I->click('login-button');
        $I->see('user1');
        $I->see('Method Not Allowed (#405)');
        $I->see('This URL can only handle the following request methods: POST.');
        $I->sendAjaxPostRequest('/admin/account/3/delete');
        $I->see('Forbidden (#403): Forbidden.');
    }

}

<?php

namespace api\tests\api;

use \api\tests\ApiTester;
use common\fixtures\UserFixture;
use common\fixtures\AccountFixture;
use common\fixtures\TransactionFixture;

class AccountCest
{
    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
            'accounts' => [
                'class' => AccountFixture::class,
                'dataFile' => codecept_data_dir() . 'accounts.php'
            ],
            'transactions' => [
                'class' => TransactionFixture::class,
                'dataFile' => codecept_data_dir() . 'transactions.php'
            ],
        ]);
    }

    public function viewUnauthorized(ApiTester $I)
    {
        $I->sendGET('account');
        $I->seeResponseCodeIs(401);
    }

    public function index(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendGET('account');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            ['account_number' => 'schet-1'],
            ['account_number' => 'schet-2'],
        ]);
        $I->dontSeeResponseContainsJson([
            ['account_number' => 'schet-3'],
            ['account_number' => 'schet-4'],
        ]);
        $I->seeHttpHeader('X-Pagination-Total-Count', 2);
    }

    public function indexWithOwner(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendGET('/account?expand=owner');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'account_number' => 'schet-1',
                'owner' => [
                    'username' => 'erau',
                    'email' => 'sfriesen@jenkins.info',
                ],
            ]
        ]);
    }

    public function indexWithTransactions(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendGET('/account?expand=debetTransactions,creditTransactions&sort=-transaction_date');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'account_number' => 'schet-1',
                'debetTransactions' => [
                    [
                        'transaction_number'=>'transact-2',
                        'transaction_summ'=>22,
                    ],
                    [
                        'transaction_number'=>'transact-3',
                        'transaction_summ'=>33,
                    ],
                ],
                'creditTransactions' => [
                    [
                        'transaction_number'=>'transact-1',
                        'transaction_summ'=>22,
                    ],
                    [
                        'transaction_number'=>'transact-4',
                        'transaction_summ'=>33,
                    ],
                ],
            ]
        ]);
    }

    public function search(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendGET('account?s[account_number]=schet-1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            ['account_number' => 'schet-1'],
        ]);
        $I->dontSeeResponseContainsJson([
            ['account_number' => 'schet-2'],
        ]);
        $I->seeHttpHeader('X-Pagination-Total-Count', 1);
    }

    public function view(ApiTester $I)
    {
        $I->sendGET('account/1');
        $I->seeResponseCodeIs(401);
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendGET('account/1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'account_number' => 'schet-1',
        ]);
    }

    public function viewNotFound(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendGET('account/15');
        $I->seeResponseCodeIs(404);
    }

    public function createUnauthorized(ApiTester $I)
    {
        $I->sendPOST('account', [
            'account_number' => 'schet-5',
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function create(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendPOST('account', [
            'account_user_id'=>1,
            'account_number' => 'schet-5',
            'balance_date'=>date('Y-m-d'),
            'balance_summ'=>222,
            'account_description'=>'пластиковая карта',
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseContainsJson([
            'account_number' => 'schet-5',
            'balance_summ'=>222,
            'account_description'=>'пластиковая карта',
        ]);
    }

    public function update(ApiTester $I)
    {
        $I->sendPATCH('account/1', [
            'account_number' => 'schet-1.1',
        ]);
        $I->seeResponseCodeIs(401);
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendPATCH('account/1', [
            'account_number' => 'schet-1.1',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id' => 1,
            'account_number' => 'schet-1.1',
        ]);
    }

    public function updateForbidden(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendPATCH('account/3', [
            'account_number' => 'schet-3.3',
        ]);
        $I->seeResponseCodeIs(403);
    }

    public function deleteUnauthorized(ApiTester $I)
    {
        $I->sendDELETE('account/1');
        $I->seeResponseCodeIs(401);
    }

    public function delete(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendDELETE('account/1');
        $I->seeResponseCodeIs(204);
    }

    public function deleteForbidden(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendDELETE('account/3');
        $I->seeResponseCodeIs(403);
    }

    public function transactUnauthorized(ApiTester $I)
    {
        $I->sendPOST('account/1/transact/2',[
            'transaction_summ'=>22,
            'transaction_description'=>'перевод',
        ]);
        $I->seeResponseCodeIs(401);
    }

    public function transactSumMoreThanAvailable(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendPOST('account/1/transact/2',[
            'transaction_summ'=>557,
            'transaction_description'=>'перевод',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            "message" => "недостаточно средств"
        ]);
    }

    public function transactFromAccountNotAvailable(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendPOST('account/22/transact/2',[
            'transaction_summ'=>10,
            'transaction_description'=>'перевод',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            "message" => "с какого счета ?"
        ]);
    }

    public function transactToAccountNotAvailable(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendPOST('account/1/transact/22',[
            'transaction_summ'=>10,
            'transaction_description'=>'перевод',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            "message" => "на какой счет ?"
        ]);
    }

    public function transactToSameAccount(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendPOST('account/2/transact/2',[
            'transaction_summ'=>10,
            'transaction_description'=>'перевод',
        ]);
        $I->seeResponseCodeIs(422);
        $I->seeResponseContainsJson([
            "message" => "тот же самый счет"
        ]);
    }

    public function transactSuccess(ApiTester $I)
    {
        $I->amBearerAuthenticated('uf0OYTWi6BdBG7COVH7g4-opwJwQAN2V');
        $I->sendPOST('account/1/transact/2?expand=debetTransactions,creditTransactions',[
            'transaction_summ'=>22,
            'transaction_description'=>'перевод',
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'account_from' => [
                'account_number'=>'schet-1',
                'balance_summ'=> 78,
            ],
            'account_to' => [
                'account_number'=>'schet-2',
                'balance_summ'=> 222,
            ],
        ]);
    }

}

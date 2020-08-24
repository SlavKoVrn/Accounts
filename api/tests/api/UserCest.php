<?php

namespace api\tests\api;

use api\tests\ApiTester;
use common\fixtures\UserFixture;

class UserCest
{
    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ],
        ]);
    }

    public function getUsers(ApiTester $I)
    {
        $I->sendGET('users?s[username]=user');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            [
                'username'=>'user1',
            ],
            [
                'username'=>'user2',
            ],
            [
                'username'=>'user3',
            ],
        ]);
        $I->seeHttpHeader('X-Pagination-Total-Count', 3);
    }
}

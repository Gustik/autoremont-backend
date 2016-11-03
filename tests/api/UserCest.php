<?php

use app\tests\fixtures\UserFixture;

class UserCest {
    private $user;

    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
                'users' => ['class' => UserFixture::className()]
        ]);
    }

    public function getAndVerifyCode(\ApiTester $I)
    {
        $this->user = $I->grabFixture('users', 'user2');
        $I->sendGET('/v3/user/get-code', ['phone'=>$this->user->login]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);

        $I->sendGET('/v3/user/verify-code',['phone'=>$this->user->login, 'code'=>'1111']);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
    }

    public function checkToken(\ApiTester $I)
    {
        $I->amHttpAuthenticated($this->user->access_token, '123456');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendGET('/v3/user/check-token');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
    }
}
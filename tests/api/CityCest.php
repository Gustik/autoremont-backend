<?php

use app\tests\fixtures\CityFixture;
use app\tests\fixtures\UserFixture;

class CityCest
{
    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'cities' => ['class' => CityFixture::className()],
            'users' => ['class' => UserFixture::className()],
        ]);
    }

    public function index(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user2');
        $city = $I->grabFixture('cities', 'yakutsk');

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendGET('/v3/city/index');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data' => [['name' => $city->name]]]);
    }
}

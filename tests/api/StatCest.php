<?php

use app\tests\fixtures\CityFixture;
use app\tests\fixtures\CompanyFixture;
use app\tests\fixtures\ProfileFixture;
use app\tests\fixtures\UserFixture;

class StatCest
{
    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'cities' => ['class' => CityFixture::className()],
            'users' => ['class' => UserFixture::className()],
            'profiles' => ['class' => ProfileFixture::className()],
            'companies' => ['class' => CompanyFixture::className()],
        ]);
    }

    public function call(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user2');
        $company = $I->grabFixture('companies', 'company1');

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendGET("/v3/stat/call?to={$company->phone}&cat={$company->category}");
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
    }
}

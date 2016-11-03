<?php

use app\tests\fixtures\CompanyFixture;
use app\tests\fixtures\UserFixture;

class CompanyCest {
    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'companies' => ['class' => CompanyFixture::className()],
            'users' => ['class' => UserFixture::className()]
        ]);
    }

    public function index(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user2');
        $company = $I->grabFixture('companies', 'company1');

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendGET('/v3/company/index',['category' => $company->category]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data'=>[['phone' => $company->phone]]]);
    }

}
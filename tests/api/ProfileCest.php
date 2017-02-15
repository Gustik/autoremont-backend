<?php

use app\tests\fixtures\OfferFixture;
use app\tests\fixtures\ProfileFixture;
use app\tests\fixtures\ReviewFixture;
use app\tests\fixtures\UserFixture;

class ProfileCest
{
    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'users' => ['class' => UserFixture::className()],
            'profiles' => ['class' => ProfileFixture::className()],
            'reviews' => ['class' => ReviewFixture::className()],
            'offers' => ['class' => OfferFixture::className()],
        ]);
    }

    public function view(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user3');
        $profile = $user->profile;

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendGET('/v3/profile/view');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson([
            'data' => [
                'name' => $profile->name,
                'phone' => $profile->phone,
                'bill_account_days' => $profile->billAccountDays,
            ],
        ]);
    }
}
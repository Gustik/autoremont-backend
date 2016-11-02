<?php

use app\tests\fixtures\OfferFixture;
use app\tests\fixtures\ProfileFixture;
use app\tests\fixtures\UserFixture;

class OfferCest {
    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'offers' => ['class' => OfferFixture::className()],
            'users' => ['class' => UserFixture::className()],
            'profiles' => ['class' => ProfileFixture::className()],
        ]);
    }

    public function view(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user2');
        $offer = $I->grabFixture('offers', 'offer1');

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendGET('/v3/offer/view', ['id' => $offer->order_id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data'=>['text' => $offer->text]]);
    }

    public function produce(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user2');
        $offer = $I->grabFixture('offers', 'offer1');
        $newOffer = [
            'text' => 'ok',
            'order_id' => $offer->order_id,
        ];

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendPOST('/v3/offer/produce', $newOffer);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data'=>['text' => $newOffer['text']]]);
    }
}
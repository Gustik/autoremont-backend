<?php

use app\tests\fixtures\OfferFixture;
use app\tests\fixtures\OrderFixture;
use app\tests\fixtures\ReviewFixture;
use app\tests\fixtures\UserFixture;

class ReviewCest {
    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'orders' => ['class' => OrderFixture::className()],
            //'review' => ['class' => ReviewFixture::className()],
            'offers' => ['class' => OfferFixture::className()],
            'users' => ['class' => UserFixture::className()]
        ]);
    }

    public function create(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user3');
        $order = $user->orders[0];
        $offer = $I->grabFixture('offers', 'offer1');
        $offer->order_id = $order->id;
        $offer->save();

        $review = [
            'order_id' => $order->id,
            'comment' => 'good',
            'rating' => 4,
            'mech_id' => $offer->author_id,
        ];

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendPOST('/v3/review/create', $review);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data'=>['comment' => $review['comment']]]);
    }


    public function update(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user3');
        $order = $user->orders[0];
        $offer = $I->grabFixture('offers', 'offer1');
        $offer->order_id = $order->id;
        $offer->save();
        $review = new \app\models\Review();

        $review->mech_id = $offer->author_id;
        $review->author_id = $user->id;
        $review->comment = 'bad';
        $review->save();


        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendPOST('/v3/review/create', $review);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data'=>['comment' => $review->comment]]);
    }

}
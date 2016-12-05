<?php

use app\tests\fixtures\OfferFixture;
use app\tests\fixtures\OrderFixture;
use app\tests\fixtures\ProfileFixture;
use app\tests\fixtures\ReviewFixture;
use app\tests\fixtures\UserFixture;

class ReviewCest
{
    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'orders' => ['class' => OrderFixture::className()],
            'reviews' => ['class' => ReviewFixture::className()],
            'offers' => ['class' => OfferFixture::className()],
            'users' => ['class' => UserFixture::className()],
            'profiles' => ['class' => ProfileFixture::className()],
        ]);
    }

    public function create(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user3');

        $order = $I->grabFixture('orders', 'order2');
        $order->author_id = $user->id;
        $order->save();

        $offer = $I->grabFixture('offers', 'offer2');
        $offer->order_id = $order->id;
        $offer->save();
        $review = [
            'order_id' => $order->id,
            'comment' => 'good',
            'rating' => 0.5,
            'mech_id' => $offer->author_id,
        ];

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendPOST('/v3/review/create', $review);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(
            [
                'data' => [
                    'comment' => $review['comment'],
                    'rating' => $review['rating'],
                ],
            ]);
    }

    public function createMoreReviewsInOneOrder(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user3');

        $order = $I->grabFixture('orders', 'order2');
        $order->author_id = $user->id;
        $order->save();

        $offer = $I->grabFixture('offers', 'offer2');
        $offer->order_id = $order->id;
        $offer->save();

        $review = [
            'order_id' => $order->id,
            'comment' => 'good',
            'rating' => 0.5,
            'mech_id' => $offer->author_id,
        ];

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendPOST('/v3/review/create', $review);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendPOST('/v3/review/create', $review);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 400]);
    }

    public function update(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user3');
        $order = $user->orders[0];

        // Предложение от cantWorkUser
        $mech = $I->grabFixture('users', 'cantWorkUser');
        $offer = $I->grabFixture('offers', 'offer1');
        $offer->author_id = $mech->id;
        $offer->order_id = $order->id;
        $offer->save();

        $review = $I->grabFixture('reviews', 'review1');
        $review->mech_id = $offer->author_id;
        $review->author_id = $user->id;
        $review->save();

        $updatedReview = [
            'id' => $review->id,
            'comment' => 'not bad!',
            'rating' => 5,
        ];

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendPOST('/v3/review/update', $updatedReview);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data' => ['comment' => $updatedReview['comment']]]);
    }
}

<?php

use app\tests\fixtures\OfferFixture;
use app\tests\fixtures\ProfileFixture;
use app\tests\fixtures\ReviewFixture;
use app\tests\fixtures\UserFixture;

class UserCest
{
    private $user;

    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'users' => ['class' => UserFixture::className()],
            'profiles' => ['class' => ProfileFixture::className()],
            'reviews' => ['class' => ReviewFixture::className()],
            'offers' => ['class' => OfferFixture::className()],
        ]);
    }

    public function getAndVerifyCode(\ApiTester $I)
    {
        $this->user = $I->grabFixture('users', 'user2');
        $I->sendGET('/v3/user/get-code', ['phone' => $this->user->login]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);

        $I->sendGET('/v3/user/verify-code', ['phone' => $this->user->login, 'code' => '1111']);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200, 'data' => ['login' => $this->user->login]]);
    }

    public function checkToken(\ApiTester $I)
    {
        $I->amHttpAuthenticated($this->user->access_token, '123456');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendGET('/v3/user/check-token');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
    }

    public function viewWithReviews(\ApiTester $I)
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

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendGET('/v3/user/view', ['phone' => $mech->login]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson([
            'data' => [
                'login' => $mech->login,
                'reviews' => [
                    [
                        'id' => $review->id,
                        'order_id' => $order->id,
                        'mech_id' => $review->mech_id,
                        'author_id' => $review->author_id,
                        'comment' => $review->comment,
                        'rating' => $review->rating,
                        'authorName' => $user->profile->name,
                        'my' => true,
                    ],
                ],
            ],
        ]);
    }
}

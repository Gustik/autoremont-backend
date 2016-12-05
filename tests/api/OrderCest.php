<?php

use app\tests\fixtures\OfferFixture;
use app\tests\fixtures\OrderFixture;
use app\tests\fixtures\ProfileFixture;
use app\tests\fixtures\ReviewFixture;
use app\tests\fixtures\UserFixture;

class OrderCest
{
    private $user;

    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'users' => ['class' => UserFixture::className()],
            'profiles' => ['class' => ProfileFixture::className()],
            'orders' => ['class' => OrderFixture::className()],
            'offers' => ['class' => OfferFixture::className()],
            'reviews' => ['class' => ReviewFixture::className()],
        ]);
        $this->user = $I->grabFixture('users', 'user2');

        $I->amHttpAuthenticated($this->user->access_token, '123456');
    }

    public function clientCreate(\ApiTester $I)
    {
        $order = [
            'car_brand' => 't',
            'car_model' => 's',
            'car_year' => '2005',
            'description' => 'Need repair',
            'category_id' => 2,
            'price' => '1',
            'tagNames' => 'Ходовка',
        ];
        $I->sendPOST('/v3/order/client-create', $order);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
    }

    public function clientCreateWithoutCategory(\ApiTester $I)
    {
        $order = [
            'car_brand' => 't',
            'car_model' => 's',
            'car_year' => '2005',
            'description' => 'Need repair',
            'price' => '1',
            'tagNames' => 'Ходовка',
        ];
        $I->sendPOST('/v3/order/client-create', $order);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data' => ['category_id' => 1]]);
    }

    public function clientUpdate(\ApiTester $I)
    {
        $order = $I->grabFixture('orders', 'order1');
        $I->sendPOST('/v3/order/client-update', ['id' => $order->id, 'description' => 'oh no!']);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['data' => ['description' => 'oh no!']]);
    }

    public function clientView(\ApiTester $I)
    {
        $order = $I->grabFixture('orders', 'order1');
        $I->sendGET('/v3/order/client-view', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'id' => 'integer',
                'created_at' => 'string',
                'updated_at' => 'string',
                'description' => 'string|null',
                'car_brand' => 'string|null',
                'car_model' => 'string|null',
                'car_year' => 'string|null',
                'car_color' => 'string|null',
                'author_id' => 'integer',
                'category_id' => 'integer',
                'new_calls' => 'string|null',
                'new_offers' => 'string|null',
                'my_offer' => 'array|null',
                'tagNames' => 'string|null',
            ],
        ]);
    }

    public function clientViewWithOffersAndRating(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user3');

        $order = $I->grabFixture('orders', 'order3');
        $offer = $I->grabFixture('offers', 'offer1');
        $offer->order_id = $order->id;
        $offer->save();

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendGET('/v3/order/client-view', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'id' => 'integer',
                'created_at' => 'string',
                'updated_at' => 'string',
                'description' => 'string|null',
                'car_brand' => 'string|null',
                'car_model' => 'string|null',
                'car_year' => 'string|null',
                'car_color' => 'string|null',
                'author_id' => 'integer',
                'category_id' => 'integer',
                'new_calls' => 'string|null',
                'new_offers' => 'string|null',
                'my_offer' => 'string|null',
                'tagNames' => 'string|null',
                'offers' => [
                    [
                        'author' => [
                            'rating' => 'integer|float',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Поле reviewed у offer, на который я создал отзыв, должен быть true.
     *
     * @param ApiTester $I
     */
    public function clientViewWithReviewedOffer(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user3');

        $order = $I->grabFixture('orders', 'order3');
        $order->author_id = $user->id;
        $order->save();

        $offer = $I->grabFixture('offers', 'offer1');
        $offer->order_id = $order->id;
        $offer->save();

        $review = $I->grabFixture('reviews', 'review1');
        $review->mech_id = $offer->author_id;
        $review->author_id = $user->id;
        $review->save();

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendGET('/v3/order/client-view', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson([
            'data' => [
                'offers' => [
                    [
                        'id' => $offer->id,
                        'reviewed' => true,
                    ],
                ],
            ],
        ]);
    }

    public function mechView(\ApiTester $I)
    {
        $order = $I->grabFixture('orders', 'order1');
        $I->sendGET('/v3/order/client-view', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseMatchesJsonType([
            'data' => [
                'id' => 'integer',
                'created_at' => 'string',
                'updated_at' => 'string',
                'description' => 'string|null',
                'car_brand' => 'string|null',
                'car_model' => 'string|null',
                'car_year' => 'string|null',
                'car_color' => 'string|null',
                'author_id' => 'integer',
                'category_id' => 'integer',
                'new_calls' => 'string|null',
                'new_offers' => 'string|null',
                'my_offer' => 'object|null',
                'tagNames' => 'string|null',
                'author' => ['login' => 'string'],
            ],
        ]);
    }

    public function mechIndex(\ApiTester $I)
    {
        $user = $I->grabFixture('users', 'user2');
        $order = $I->grabFixture('orders', 'order3');
        $order->created_at = date('Y-m-d H:i:s');
        $order->save();

        $I->amHttpAuthenticated($user->access_token, '123456');
        $I->sendGET('/v3/order/mech-index', ['id' => $order->category_id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data' => [['description' => $order->description]]]);
    }

    public function mechCallReapir(\ApiTester $I)
    {
        $order = $I->grabFixture('orders', 'repairOrder');
        $I->sendGET('/v3/order/mech-call', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data' => ['login' => $order->author->login]]);
    }

    public function mechCallPart(\ApiTester $I)
    {
        $order = $I->grabFixture('orders', 'partOrder');
        $I->sendGET('/v3/order/mech-call', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data' => ['login' => $order->author->login]]);
    }

    /**
     * Звонок не оплатившего на заказ по ремонту.
     *
     * @param ApiTester $I
     */
    public function cantWorkMechCallToReapir(\ApiTester $I)
    {
        $cantWorkUser = $I->grabFixture('users', 'cantWorkUser');
        $order = $I->grabFixture('orders', 'repairOrder');

        $I->amHttpAuthenticated($cantWorkUser->access_token, '123456');
        $I->sendGET('/v3/order/mech-call', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data' => ['login' => 'need_payment']]);
    }

    /**
     * Звонок не оплатившего на заказ по запчастям
     *
     * @param ApiTester $I
     */
    public function cantWorkMechCallToPart(\ApiTester $I)
    {
        $cantWorkUser = $I->grabFixture('users', 'cantWorkUser');
        $order = $I->grabFixture('orders', 'partOrder');

        $I->amHttpAuthenticated($cantWorkUser->access_token, '123456');
        $I->sendGET('/v3/order/mech-call', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data' => ['login' => $order->author->login]]);
    }

    /**
     * Звонок в горде где не включена тарификация.
     *
     * @param ApiTester $I
     */
    public function freeCityMechCallToPart(\ApiTester $I)
    {
        $cantWorkUser = $I->grabFixture('users', 'freeCityMech');
        $order = $I->grabFixture('orders', 'freeCityRepairOrder');

        $I->amHttpAuthenticated($cantWorkUser->access_token, '123456');
        $I->sendGET('/v3/order/mech-call', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data' => ['login' => $order->author->login]]);
    }
}

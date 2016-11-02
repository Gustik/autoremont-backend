<?php

use app\tests\fixtures\OrderFixture;
use app\tests\fixtures\ProfileFixture;
use app\tests\fixtures\UserFixture;

class OrderCest {

    private $user;

    public function _before(\ApiTester $I)
    {
        $I->haveFixtures([
            'users' => ['class' => UserFixture::className()],
            'profiles' => ['class' => ProfileFixture::className()],
            'orders' => ['class' => OrderFixture::className()],
        ]);
        $this->user = $I->grabFixture('users', 'user2');

        $I->amHttpAuthenticated($this->user->access_token, '123456');

    }

    public function clientCreate(\ApiTester $I) {
        $order = [
            'description' => 'Need repair',
            'category_id' => 1,
        ];
        $I->sendPOST('/v3/order/client-create', $order);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
    }

    public function clientUpdate(\ApiTester $I) {
        $order = $I->grabFixture('orders', 'order1');
        $I->sendPOST('/v3/order/client-update', ["id" => $order->id, "description" => "oh no!"]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['data' => ['description' => 'oh no!']]);
    }

    public function clientView(\ApiTester $I) {
        $order = $I->grabFixture('orders', 'order1');
        $I->sendGET('/v3/order/client-view', ['id'=>$order->id]);
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
            ]
        ]);
    }

    public function mechView(\ApiTester $I) {
        $order = $I->grabFixture('orders', 'order1');
        $I->sendGET('/v3/order/client-view', ['id'=>$order->id]);
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
            ]
        ]);
    }

    public function mechIndex(\ApiTester $I) {
        $order = $I->grabFixture('orders', 'order3');
        $I->sendGET('/v3/order/mech-index', ['id' => $order->category_id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data'=>[['description' => $order->description]]]);
    }

    public function mechCall(\ApiTester $I) {
        $order = $I->grabFixture('orders', 'order3');
        $I->sendGET('/v3/order/mech-call', ['id' => $order->id]);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 200]);
        $I->seeResponseContainsJson(['data'=>['login' => $order->author->login]]);
    }
}
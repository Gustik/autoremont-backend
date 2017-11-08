<?php

use app\commands\BillAccountController;
use app\models\BillAccount;
use app\tests\fixtures\BillAccountFixture;
use app\tests\fixtures\ProfileFixture;
use app\tests\fixtures\UserFixture;
use yii\db\Expression;
use yii\db\Query;

class CommandsCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->haveFixtures([
            'offers' => ['class' => BillAccountFixture::className()],
            'users' => ['class' => UserFixture::className()],
            'profiles' => ['class' => ProfileFixture::className()],
        ]);
    }

    public function decrementDate(\FunctionalTester $I)
    {
        $user = $I->grabFixture('users', 'cantWorkUser');
        $account = new BillAccount();

        // Оплатил Только что
        $account->days = 1;
        $account->user_id = $user->id;
        $account->processed_at = new Expression('NOW()');
        $account->save();

        BillAccountController::decrementDay();

        $user = \app\models\User::findOne($user->id);
        $account = BillAccount::findOne($account->id);

        \PHPUnit_Framework_Assert::assertEquals(1, $user->can_work); // Пользователя включили
        \PHPUnit_Framework_Assert::assertEquals(1, $account->days); // Дни не снимаются
        BillAccountController::decrementDay();
        BillAccountController::decrementDay();

        $user = \app\models\User::findOne($user->id);
        $account = BillAccount::findOne($account->id);
        \PHPUnit_Framework_Assert::assertEquals(1, $user->can_work); // Пользователя не отключили
        \PHPUnit_Framework_Assert::assertEquals(1, $account->days); // Дни не снимаются (сутки еще не прошли)

        // 5 дней, последний перерасчет был сутки назад.
        $account = BillAccount::findOne($account->id);
        $account->days = 5;
        $account->processed_at = new Expression('SUBDATE(NOW(), 1)');
        $account->save();

        BillAccountController::decrementDay();
        BillAccountController::decrementDay();

        $account = BillAccount::findOne($account->id);
        $user = \app\models\User::findOne($user->id);

        \PHPUnit_Framework_Assert::assertEquals(1, $user->can_work); // пользователь может работать
        \PHPUnit_Framework_Assert::assertEquals(4, $account->days); // осталось 4 дня

        // Оплатил сутки назад
        $account = BillAccount::findOne($account->id);
        $account->days = 1;
        $account->processed_at = new Expression('SUBDATE(NOW(), 1)');
        $account->save();

        BillAccountController::decrementDay(); // Можно хоть сколько раз вызывать
        BillAccountController::decrementDay();

        $account = BillAccount::findOne($account->id);
        $user = \app\models\User::findOne($user->id);

        \PHPUnit_Framework_Assert::assertEquals(0, $user->can_work); // Отключаем пользователя
        \PHPUnit_Framework_Assert::assertEquals(0, $account->days); // Дни кончились
    }

    /**
     * processed_at не должен меняться при многократном вызове decrementDay
     * при diffDay = 0
     *
     * @param FunctionalTester $I
     * @throws \yii\console\Exception
     */
    public function manyDecrementDate(\FunctionalTester $I)
    {
        $user = $I->grabFixture('users', 'cantWorkUser');
        $account = new BillAccount();
        $now = (new Query())->select('NOW() as d')->one()['d'];
        // Оплатил Только что
        $account->days = 1;
        $account->user_id = $user->id;
        $account->processed_at = $now;
        $saveProcessed_at = $now;
        $account->save();

        BillAccountController::decrementDay();
        sleep(1);
        BillAccountController::decrementDay();
        BillAccountController::decrementDay();

        $user = \app\models\User::findOne($user->id);
        $account = BillAccount::findOne($account->id);

        \PHPUnit_Framework_Assert::assertEquals(1, $user->can_work); // Пользователя включили
        \PHPUnit_Framework_Assert::assertEquals(1, $account->days); // Дни не снимаются

        \PHPUnit_Framework_Assert::assertEquals($saveProcessed_at, $account->processed_at); // processed_at не должен меняться
    }

    public function diffDate(\FunctionalTester $I)
    {
        $processed_at = '2017-11-07 15:40:00';
        $now = '2017-11-08 00:40:00';
        $diff = BillAccountController::diffDays($processed_at, $now);
        \PHPUnit_Framework_Assert::assertEquals(0, $diff);

        $diff = BillAccountController::diffDays('2017-11-07 00:40:00', '2017-11-08 00:39:00');
        \PHPUnit_Framework_Assert::assertEquals(0, $diff);

        $diff = BillAccountController::diffDays('2017-11-07 00:40:00', '2017-11-08 00:40:00');
        \PHPUnit_Framework_Assert::assertEquals(1, $diff);
    }
}

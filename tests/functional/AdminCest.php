<?php
class AdminCest
{
    public function _before(\FunctionalTester $I)
    {

    }

    public function openAdminLoginPage(\FunctionalTester $I)
    {
        $I->amOnPage(['/admin/main/login']);
        $I->see('Вход в админ-панель');
    }
}
<?php
class SiteCest
{
    public function _before(\FunctionalTester $I)
    {

    }

    public function openSiteIndex(\FunctionalTester $I)
    {
        $I->amOnPage(['/']);
        $I->see('Ремонт и запчасти для Вашего авто в Якутске', 'h1');
    }

    public function openSiteLicense(\FunctionalTester $I)
    {
        $I->amOnPage(['/site/license']);
        $I->see('Лицензионное соглашение');
    }

}

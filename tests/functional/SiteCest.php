<?php
use app\tests\fixtures\PageFixture;

class SiteCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->haveFixtures([
            'pages' => ['class' => PageFixture::className()],
        ]);
    }

    public function openSiteIndex(\FunctionalTester $I)
    {
        $I->amOnPage(['/']);
        $I->see('Ремонт и запчасти для Вашего авто в Якутске', 'h1');
    }

    public function openSiteLicense(\FunctionalTester $I)
    {
        $I->amOnPage(['/license']);
        $I->see('Лицензионное соглашение');
    }

}

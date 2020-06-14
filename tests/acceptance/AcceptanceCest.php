<?php

class AcceptanceCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function isOnStartPage(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Continuous-Delivery Blog');
    }

    public function databaseCreatedTest(AcceptanceTester $I){
        $I->seeInDatabase("article", ["id" => "1", "category" => "1", "title" => "Title First Article"]);
    }

    public function clicksOnPolitics(AcceptanceTester $I){
        // simple link
        $I->amOnPage('/');
        $I->click('Politics');
        $I->see('Second Test Article');
    }


}

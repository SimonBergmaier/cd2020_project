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

    public function isDatabaseCreated(AcceptanceTester $I){
        $I->seeInDatabase("categories", ["id" => "1", "name" => "Politics"]);
        $I->seeInDatabase("article", ["id" => "1", "category" => "1", "title" => "Title First Article"]);
        $I->seeInDatabase("users", ["id" => "1", "username" => "FirstUser"]);
        $I->seeInDatabase("comment", ["id" => "1", "articleId" => "2", "authorId" => "1", "text" => "First Comment Second Article"]);
    }

    public function isLoginWorkin(AcceptanceTester $I){
        $I->amOnPage('/');
        $I->see('Not logged in!');
        $I->click('Not logged in!');
        $I->see('Login now');
        $I->click('Login now');
        // we are using label to match user_name field
        $I->fillField('userName', 'FirstUser');
        $I->fillField('password','testuser');
        $I->click('loginbtn');
        $I->see('Logged in as FirstUser');
    }

    public function clicksOnFirstCategory(AcceptanceTester $I){
        // simple link
        $I->amOnPage('/');
        $category = $I->grabFromDatabase('categories', 'name', ['id' => '1']);
        $I->click($category);
        $I->amOnPage('index.php/?view=list&categoryId=' . 1);
        $I->see($I->grabFromDatabase('article', 'title', array(['category' => '1'])[0]));
    }

    public function clicksOnViewFirstArticle(AcceptanceTester $I){
        // simple link
        $I->amOnPage('index.php/?view=list&categoryId=' . 1);
        $I->click("View Article");
        $I->amOnPage('index.php?view=article&id=' . 2);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => '2']));
    }
}

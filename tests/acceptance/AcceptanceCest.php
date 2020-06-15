<?php

class AcceptanceCest
{

    private $username = "FirstUser";
    private $cookie = null;

    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function IsOnStartpage(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Continuous-Delivery Blog');
    }

    public function IsDatabaseCreated(AcceptanceTester $I){
        $I->seeInDatabase("categories", ["id" => "1", "name" => "Politics"]);
        $I->seeInDatabase("article", ["id" => "1", "category" => "1", "title" => "Title First Article"]);
        $I->seeInDatabase("users", ["id" => "1", "username" => "FirstUser"]);
        $I->seeInDatabase("comment", ["id" => "1", "articleId" => "2", "authorId" => "1", "text" => "First Comment Second Article"]);
    }

    public function isLoginWorking(AcceptanceTester $I){
        $I->amOnPage('/');
        $I->see('Not logged in!');
        $I->click('Not logged in!');
        $I->see('Login now');
        $I->click('Login now');
        $I->fillField('userName', $this->username);
        $I->fillField('password','Testuser');
        $I->click('loginbtn');
        $I->see('Logged in as ' . $this->username);
        $this->cookie   = $I->grabCookie('PHPSESSID');
    }

    public function clicksOnFirstCategory(AcceptanceTester $I){
        $category_id = 1;
        $I->amOnPage('/');
        $category = $I->grabFromDatabase('categories', 'name', ['id' => $category_id]);
        $I->click($category);
        $I->amOnPage('index.php/?view=list&categoryId=' . $category_id);
        $I->see($I->grabFromDatabase('article', 'title', array(['category' => $category_id])[0]));
    }

    public function clicksOnEmptyCategory(AcceptanceTester $I){
        $category_id = 2;
        $I->amOnPage('/');
        $category = $I->grabFromDatabase('categories', 'name', ['id' =>  $category_id]);
        $I->click($category);
        $I->amOnPage('index.php/?view=list&categoryId=' . $category_id);
        $I->see('No articles in this category');
    }

    public function clicksOnViewFirstArticle(AcceptanceTester $I){
        $category_id = 1;
        $article_id = 2;
        $I->amOnPage('index.php/?view=list&categoryId=' . $category_id = 1);
        $I->click("View Article");
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
    }

    public function CommentWithoutLogin(AcceptanceTester $I){
        $article_id = 3;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->dontSee('Create Comment:');
        //$I->fillField('text', 'Hello this is a new Comment on ' . $I->grabFromDatabase('article', 'title', ['id' => $article_id]));
        //$I->see('Not logged in.');
    }

    /**
     * @depends isLoginWorking
     * @param AcceptanceTester $I
     */
    public function CommentWithLogin(AcceptanceTester $I){
        $I->setCookie('PHPSESSID', $this->cookie);
        $article_id = 3;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see('Create Comment:');
        $comment = 'Hello this is a new Comment on ' . $I->grabFromDatabase('article', 'title', ['id' => $article_id]);
        $I->fillField('#inputText', $comment);
        $I->click('Create Comment...');
        $I->seeInDatabase("comment", ["text" => $comment]);

        $I->see($comment);
    }

    public function NewArticleWithoutLogin(AcceptanceTester $I){
        $I->amOnPage('index.php');
        $I->dontSee('Create new Article');
    }



}

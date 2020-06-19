<?php

class AcceptanceCest
{

    private $username = "FirstUser";
    private $userId = "1";
    private $cookie = null;

    public function _before(AcceptanceTester $I)
    {
    }

    /**
     * Tests if the users sees the landing page
     * @param AcceptanceTester $I
     */
    public function IsOnStartpage(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Continuous-Delivery Blog');
        $I->see('Please select a category');
    }

    /**
     * Tests whether the database was created correct or not
     * @param AcceptanceTester $I
     */
    public function IsDatabaseCreated(AcceptanceTester $I)
    {
        $I->seeInDatabase("categories", ["id" => "1", "name" => "Politics"]);
        $I->seeInDatabase("article", ["id" => "1", "category" => "1", "title" => "Title First Article"]);
        $I->seeInDatabase("users", ["id" => "1", "username" => "FirstUser"]);
        $I->seeInDatabase("comment", ["id" => "1", "articleId" => "2", "authorId" => "1", "text" => "First Comment Second Article"]);
    }

    /**
     * Tests if the login workflow is working
     * and sets the PHPSESSID cookie, so that it can be used to test other functionalities,
     * which requires logged in users
     * @param AcceptanceTester $I ,
     */
    public function isLoginWorking(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Not logged in!');
        $I->click('Not logged in!');
        $I->see('Login now');
        $I->click('Login now');
        $I->fillField('userName', $this->username);
        $I->fillField('password', 'Testuser');
        $I->click('loginbtn');
        $I->see('Logged in as ' . $this->username);
        $this->cookie = $I->grabCookie('PHPSESSID');
    }

    /**
     * Tests if the users sees the articles of the first category
     * (Articles in second category 2)
     * @param AcceptanceTester $I
     */
    public function clicksOnFirstCategory(AcceptanceTester $I)
    {
        $XPATH_ARTICLE_IN_LIST = 'tbody/tr';
        $category_id = 1;
        $I->amOnPage('/');
        $category = $I->grabFromDatabase('categories', 'name', ['id' => $category_id]);
        $I->click($category);
        $I->amOnPage('index.php/?view=list&categoryId=' . $category_id);
        $I->see($I->grabFromDatabase('article', 'title', array(['category' => $category_id])[0]));
        $arrayProducts = $I->grabMultiple($XPATH_ARTICLE_IN_LIST);
        $sumProducts = count($arrayProducts);
        $I->seeNumberOfElements($XPATH_ARTICLE_IN_LIST, $sumProducts);
    }

    /**
     * Tests if the users sees the notification that no articles are in the second category
     * @param AcceptanceTester $I
     */
    public function clicksOnSecondCategoryWhichIsEmpty(AcceptanceTester $I)
    {
        $category_id = 2;
        $I->amOnPage('/');
        $category = $I->grabFromDatabase('categories', 'name', ['id' => $category_id]);
        $I->click($category);
        $I->amOnPage('index.php/?view=list&categoryId=' . $category_id);
        $I->see('No articles in this category');
    }

    /**
     * Tests if user see content of second article from first category
     * @param AcceptanceTester $I
     */
    public function clicksOnViewSecondArticle(AcceptanceTester $I)
    {
        $article_id = 2;
        $I->amOnPage('index.php/?view=list&categoryId=' . $category_id = 1);
        $I->click("View Article");
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
    }

    /**
     * Tests if the user sees the comment on the second article
     * @depends clicksOnViewSecondArticle
     * @param AcceptanceTester $I
     */
    public function seeCommentsOnSecondArticle(AcceptanceTester $I)
    {
        $CSS_COMMENTS_ON_ARTICLE = 'panel';
        $article_id = 2;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
        $I->see($I->grabFromDatabase('comment', 'text', ['articleId' => $article_id]));
        $arrayProducts = $I->grabMultiple($CSS_COMMENTS_ON_ARTICLE);
        $sumProducts = count($arrayProducts);
        $I->comment($sumProducts);
        $I->seeNumberOfElements($CSS_COMMENTS_ON_ARTICLE, $sumProducts);
    }

    /**
     * Tests if the user can edit his/her article
     * (UserId = 1, articleId = 2)
     * @depends isLoginWorking
     * @param AcceptanceTester $I
     */
    public function editSecondArticleWhenLoggedInWithCorrectUser(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->cookie);
        $article_id = 2;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
        $I->see("Edit Article");
        $I->see("Delete Article");
    }

    /**
     * Tests if the user can edit his/her comments on the second article
     * (UserId = 1, articleId = 2)
     * @depends isLoginWorking
     * @param AcceptanceTester $I
     */
    public function editCommentsOfSecondArticleWhenLoggedInWithCorrectUser(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->cookie);
        $article_id = 2;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
        $I->see($I->grabFromDatabase('comment', 'text', ['articleId' => $article_id, 'authorId' => 1]));
        $I->see("Edit Comment");
    }

    public function CommentWithoutLogin(AcceptanceTester $I)
    {
        $article_id = 3;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->dontSee('Create Comment:');
    }

    /**
     * @depends isLoginWorking
     * @param AcceptanceTester $I
     */
    public function CommentWithLogin(AcceptanceTester $I)
    {
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

    public function NewArticleWithoutLogin(AcceptanceTester $I)
    {
        $I->amOnPage('index.php');
        $I->dontSee('Create new Article');
    }

    /**
     * @depends isLoginWorking
     * @param AcceptanceTester $I
     */
    public function NewArticleWithLogin(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->cookie);
        $I->amOnPage('index.php');
        $I->see('Create new Article');
    }


}

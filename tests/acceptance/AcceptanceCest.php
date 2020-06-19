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
     * Tests whether the database was created correct or not
     * @param AcceptanceTester $I
     */
    public function IsDatabaseCreated(AcceptanceTester $I)
    {
        $I->seeInDatabase("categories", ["id" => "1", "name" => "Politics"]);
        $I->seeInDatabase("article", ["id" => "1", "category" => "1", "title" => "Title First Article"]);
        $I->seeInDatabase("users", ["id" => $this->userId, "username" => $this->username]);
        $I->seeInDatabase("comment", ["id" => "1", "articleId" => "2", "authorId" => $this->userId, "text" => "First Comment Second Article"]);
    }

    /**
     * Tests if the users sees the landing page
     * @param AcceptanceTester $I
     */
    public function IsOnStartpage(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Continuous Delivery Blog');
        $I->see('Please select a category');
    }


    /**
     * Tests if the users gets to the landing page
     * @param AcceptanceTester $I
     */
    public function ClickOnBlogName(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Continuous Delivery Blog');
        $I->click('Continuous Delivery Blog');
        $I->see('Please select a category');
    }

    /**
     * Tests if the users gets to the landing page
     * @param AcceptanceTester $I
     */
    public function ClickOnHome(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Home');
        $I->click('Home');
        $I->see('Please select a category');
    }

    /**
     * Tests if the login workflow is working
     * and sets the PHPSESSID cookie, so that it can be used to test other functionalities,
     * which requires logged in users
     * @param AcceptanceTester $I ,
     */
    public function IsLoginWorking(AcceptanceTester $I)
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
     * Tests if the login workflow is working when user enters wrong credentials
     * @param AcceptanceTester $I ,
     */
    public function TryLoginWithWrongCredentials(AcceptanceTester $I)
    {
        $I->amOnPage('/index.php?view=login');
        $I->fillField('userName', $this->username);
        $I->fillField('password', 'Wrong Password');
        $I->click('loginbtn');
        $I->see('Not logged in.');
    }

    /**
     * Tests if the registering workflow is working when user enters wrong credentials
     * @depends IsDatabaseCreated
     * @param AcceptanceTester $I ,
     */
    public function TryRegisteringWithTwoDifferentPasswords(AcceptanceTester $I)
    {
        $testUsername = 'TestUser';
        $testPassword = 'TestPassword';
        $I->amOnPage('/index.php?view=register');
        $I->fillField('userName', $testUsername);
        $I->fillField('password', $testPassword);
        $I->fillField('passwordRep', "Not matching password");
        $I->click('Register');
        $I->dontSeeInDatabase("users", ["username" => $testUsername]);
        $I->see('Passwords didn\'t match');
    }

    /**
     * Tests if the registering workflow is working when user enters correct credentials
     * @depends IsDatabaseCreated
     * @param AcceptanceTester $I ,
     */
    public function IsRegisteringWorking(AcceptanceTester $I)
    {
        $testUsername = 'TestUser';
        $testPassword = 'TestPassword';
        $I->amOnPage('/index.php?view=register');
        $I->fillField('userName', $testUsername);
        $I->fillField('password', $testPassword);
        $I->fillField('passwordRep', $testPassword);
        $I->click('Register');
        $I->seeInDatabase("users", ["username" => $testUsername]);
        $I->see('Logged in as ' . $testUsername);
    }

    /**
     * Tests if the users sees the articles of the first category
     * (Articles in second category 2)
     * @depends IsDatabaseCreated
     * @param AcceptanceTester $I
     */
    public function ClicksOnFirstCategory(AcceptanceTester $I)
    {
        $XPATH_ARTICLE_IN_LIST = '//tbody/tr';
        $category_id = 1;
        $I->amOnPage('/');
        $category = $I->grabFromDatabase('categories', 'name', ['id' => $category_id]);
        $I->click($category);
        $I->amOnPage('index.php/?view=list&categoryId=' . $category_id);
        $I->see($I->grabFromDatabase('article', 'title', array(['category' => $category_id])[0]));
        $I->seeNumberOfElements($XPATH_ARTICLE_IN_LIST, $I->grabNumRecords('article', ['category' => $category_id]));
    }

    /**
     * Tests if the users sees the notification that no articles are in the second category
     * @depends IsDatabaseCreated
     * @param AcceptanceTester $I
     */
    public function ClicksOnSecondCategoryWhichIsEmpty(AcceptanceTester $I)
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
     * @depends IsDatabaseCreated
     * @param AcceptanceTester $I
     */
    public function ClicksOnViewSecondArticle(AcceptanceTester $I)
    {
        $article_id = 2;
        $I->amOnPage('index.php/?view=list&categoryId=' . $category_id = 1);
        $I->click("View Article");
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
        $I->see($I->grabFromDatabase('article', 'text', ['id' => $article_id]));
    }

    /**
     * Tests if the user sees the comment on the second article
     * @depends ClicksOnViewSecondArticle
     * @param AcceptanceTester $I
     */
    public function SeeCommentsOnSecondArticle(AcceptanceTester $I)
    {
        $CSS_COMMENTS_ON_ARTICLE = 'panel';
        $article_id = 2;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
        $I->see($I->grabFromDatabase('comment', 'text', ['articleId' => $article_id]));
        $I->seeNumberOfElements(['class' => $CSS_COMMENTS_ON_ARTICLE], $I->grabNumRecords('comment', ['articleId' => $article_id, 'active' => "1"])); // DISABLED, can wait only for one element
    }

    /**
     * Tests if the user sees the comment on the second article
     * @depends ClicksOnViewSecondArticle
     * @param AcceptanceTester $I
     */
    public function SeeCommentsOnFirstArticle(AcceptanceTester $I)
    {
        $article_id = 1;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
        $I->see("No Comments for this Article");
    }

    /**
     * Tests if the user can see operations related to his/her article
     * (UserId = 1, articleId = 2)
     * @depends IsLoginWorking
     * @param AcceptanceTester $I
     */
    public function SeeOperationsOfArticleWhenLoggedInWithCorrectUser(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->cookie);
        $article_id = 2;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
        $I->see("Edit Article");
        $I->see("Delete Article");
    }

    /**
     * Tests if the user can edit his/her article
     * (UserId = 1, articleId = 2)
     * @depends SeeOperationsOfArticleWhenLoggedInWithCorrectUser
     * @param AcceptanceTester $I
     */
    public function EditSecondArticleWhenLoggedInWithCorrectUser(AcceptanceTester $I)
    {
        $article_id = 2;
        $current_text = $I->grabFromDatabase('article', 'text', ['id' => $article_id]);
        $new_text = "Edited " . $current_text;
        $I->setCookie('PHPSESSID', $this->cookie);
        $I->amOnPage('index.php?view=editArticle&id=' . $article_id);
        $I->see($current_text);
        $I->fillField('text', $new_text);
        $I->click("Edit Article...");
        $I->see($new_text);
    }

    /**
     * Tests if the user can see the edit button of his/her comments on article
     * (UserId = 1, articleId = 2)
     * @depends IsLoginWorking
     * @param AcceptanceTester $I
     */
    public function SeeEditCommentsOfSecondArticleWhenLoggedInWithCorrectUser(AcceptanceTester $I)
    {
        $XPATH_EDIT_LINKS = "//a[contains(@href, 'index.php?view=editComment&id=')]";
        $I->setCookie('PHPSESSID', $this->cookie);
        $article_id = 2;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see($I->grabFromDatabase('article', 'title', ['id' => $article_id]));
        $I->see($I->grabFromDatabase('comment', 'text', ['articleId' => $article_id, 'authorId' => $this->userId]));
        $I->seeNumberOfElements($XPATH_EDIT_LINKS, $I->grabNumRecords('comment', ['articleId' => $article_id, 'authorId' => $this->userId]));
    }

    /**
     * Tests if the user can see the edit button of his/her comments on article
     * (UserId = 1, articleId = 2)
     * @depends SeeEditCommentsOfSecondArticleWhenLoggedInWithCorrectUser
     * @param AcceptanceTester $I
     */
    public function EditSecondCommentOfSecondArticleWhenLoggedInWithCorrectUser(AcceptanceTester $I)
    {
        $article_id = 2;
        $comment_id = 2;
        $currentComment = $I->grabFromDatabase("comment", "text", ['id' => $comment_id]);
        $newComment = "Edited" . $currentComment;
        $XPATH_EDIT_LINKS = "//a[contains(@href, 'index.php?view=editComment&id=" . $comment_id . "')]";
        $I->setCookie('PHPSESSID', $this->cookie);
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->see("Edit Comment");
        $I->click($XPATH_EDIT_LINKS);
        $I->amOnPage('/index.php?view=editComment&id=' . $comment_id);
        $I->fillField('text', $newComment);
        $I->click("Edit Comment...");
        $I->see($newComment);
    }

    /**
     * Tests if the user can delete comments on his/her article
     * (UserId = 1, articleId = 2, commentId=2)
     * @depends SeeEditCommentsOfSecondArticleWhenLoggedInWithCorrectUser
     * @param AcceptanceTester $I
     */
    public function DeleteSecondCommentOfSecondArticleWhenLoggedInWithCorrectUser(AcceptanceTester $I)
    {
        $article_id = 2;
        $comment_id = 2;
        $currentComment = $I->grabFromDatabase("comment", "text", ['id' => $comment_id]);
        $XPATH_DELETE_COMMENT = "//button[parent::*[preceding-sibling::input[@type='hidden'][@name='commentId'][@value='" . $comment_id . "']]]";
        $I->setCookie('PHPSESSID', $this->cookie);
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->click($XPATH_DELETE_COMMENT);
        $I->dontSee($currentComment);
    }

    /**
     * Tests that a users which is not logged in can not comment something
     * @param AcceptanceTester $I
     * @depends IsDatabaseCreated
     */
    public function TryToCommentWithoutLogin(AcceptanceTester $I)
    {
        $article_id = 3;
        $I->amOnPage('index.php?view=article&id=' . $article_id);
        $I->dontSee('Create Comment:');
    }

    /**
     * @depends IsLoginWorking
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
     * @depends IsLoginWorking
     * @param AcceptanceTester $I
     */
    public function NewArticleWithLogin(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->cookie);
        $I->amOnPage('index.php');
        $I->see('Create new Article');
    }

    /**
     * Tests if the logout workflow is working
     * @depends IsLoginWorking
     * @param AcceptanceTester $I ,
     */
    public function IsLogoutWorking(AcceptanceTester $I)
    {
        $I->setCookie('PHPSESSID', $this->cookie);
        $I->amOnPage('/');
        $I->see('Logged in as ' . $this->username);
        $I->click('Logged in as ' . $this->username);
        $I->click('Logout');
        $I->see('Not logged in!');
    }
}

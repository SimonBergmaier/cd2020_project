<?php 

require_once('inc/bootstrap.php');

use Blog\Article;
use Blog\Category;
use Blog\Comment;
use Blog\User;

class UnitCest
{
    public function _before(UnitTester $I)
    {
    }

    // tests
    public function UserTest(UnitTester $I)
    {
        $user = new User(1,"bill", "12345");

        $I->assertEquals("bill", $user->getUserName());
        $I->assertEquals(1, $user->getId());
        $I->assertEquals("12345", $user->getPasswordHash());
    }

    public function CommentTest(UnitTester $I) 
    {
        $comment = new Comment(1,1,1,"Comment","1-2-3");

        $I->assertEquals(1, $comment->getId());
        $I->assertEquals(1, $comment->getArticleId());
        $I->assertEquals(1, $comment->getAuthor());
        $I->assertEquals("Comment", $comment->getText());
        $I->assertEquals("1-2-3", $comment->getCreationDate());
    }

    public function CategoryTest(UnitTester $I) 
    {
        $category = new Category(1, "Cat");
        
        $I->assertEquals(1, $category->getId());
        $I->assertEquals("Cat", $category->getName());
    }

    public function ArticleTest(UnitTester $I) 
    {
        $article = new Article(1, 1, "Title", "Subtitle", "Text", "1-2-3", "1");

        $I->assertEquals(1, $article->getId());
        $I->assertEquals(1, $article->getCategoryId());
        $I->assertEquals("Title", $article->getTitle());
        $I->assertEquals("Subtitle", $article->getSubtitle());
        $I->assertEquals("Text", $article->getText());
        $I->assertEquals("1-2-3", $article->getCreationDate());
        $I->assertEquals(1, $article->getAuthor());
    }
}

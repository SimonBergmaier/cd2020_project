<?php

namespace Data;

use Blog\Category;
use Blog\Comment;
use Blog\User;
use Blog\Article;
use Blog\Util;

include 'IDataManager.php';

/**
 * DataManager
 * Mock Version
 *
 *
 * @package
 * @subpackage
 * @author     John Doe <jd@fbi.gov>
 */
class DataManager implements \IDataManager {

    private static $__connection;

    private static function getConnection() {
        if (!isset(self::$__connection)) {
            self::$__connection = new \PDO('mysql:host=localhost;dbname=fh_2020_cd_1910455011;charset=utf8', 'cd-2020', '$ecurePassword');
        }
        return self::$__connection;
    }

    public static function exposeConnection() {
        return self::getConnection();
    }

    private static function query($connection, $query, $parameters = array()) {
        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        try {
            $statement = $connection->prepare($query);

            $i = 1;
            foreach ($parameters AS $param) {
                if (is_int($param)) {
                    $statement->bindValue($i, $param, \PDO::PARAM_INT);
                }
                if (is_string($param)) {
                    $statement->bindValue($i, $param, \PDO::PARAM_STR);
                }
                $i++;
            }

            $result = $statement->execute();
        }
        catch (\Exception $e) {
            die('Database Error' . implode(' | ', $statement->errorInfo()));
        }

        return $statement;
    }

    private static function lastInsertId($connection) {
        return $connection->lastInsertId();
    }

    private static function fetchObject($cursor) {
        return $cursor->fetchObject();
    }

    private static function close($cursor) {
        return $cursor->closeCursor();
    }

    private static function closeConnection($connection) {
        self::$__connection = null;
    }

    /**
     * get the categories
     *
     * note: global …; -> suboptimal
     *
     * @return array of Category-items
     */
    public static function getCategories(): array {
        $con = self::getConnection();
        $categories = [];


        $res = self::query($con, "SELECT id, name FROM categories");

        while ($cat = self::fetchObject($res)) {
            $categories[] = new Category($cat->id, $cat->name);
        }

        self::close($res);
        self::closeConnection($con);

        return $categories;
    }

    /**
     * get the articles per category
     *
     * @param integer $categoryId numeric id of the category
     * @return array of Article-items
     */
    public static function getArticlesByCategory(int $categoryId): array {
        $con = self::getConnection();
        $articles = [];

        $res = self::query($con, "SELECT id, category, title, subtitle, text, author, creationDate FROM article WHERE category = ? AND active = 1 ORDER BY creationDate DESC;", [$categoryId]);

        while ($article = self::fetchObject($res)) {
            $articles[] = new Article($article->id, $article->category, $article->title, $article->subtitle, $article->text, $article->creationDate, $article->author);
        }

        self::close($res);
        self::closeConnection($con);

        return $articles;
    }

    public static function getArticleById(int $articleId) {
        $con = self::getConnection();
        $article = false;

        $res = self::query($con,"SELECT id, category, title, subtitle, text, author, creationDate FROM article WHERE id = ? AND active = 1", [$articleId]);

        if($a = self::fetchObject($res)) {
            $article = new Article($a->id, $a->category, $a->title, $a->subtitle, $a->text, $a->creationDate, $a->author);
        }

        self::close($res);
        self::closeConnection($con);

        return $article;
    }

    /**
     * @param int $articleId
     * @return array
     */
    public static function getCommentsByArticle(int $articleId): array {
        $con = self::getConnection();
        $comments = [];

        $res = self::query($con, "SELECT id, authorId, text, creationDate FROM comment WHERE active = 1 AND articleId = ? ORDER BY creationDate DESC",[$articleId]);

        while($comment = self::fetchObject($res)) {
            $comments[] = new Comment($comment->id, $articleId, $comment->authorId, $comment->text, $comment->creationDate);
        }

        self::close($res);
        self::closeConnection($con);
        return $comments;
    }

    public static function getCommentById(int $commentId) {
        $con = self::getConnection();
        $comment = false;

        $res = self::query($con, "SELECT id, authorId, articleId, text, creationDate FROM comment WHERE id = ? AND active = 1",[$commentId]);

        if($c = self::fetchObject($res)) {
            $comment = new Comment($c->id, $c->articleId, $c->authorId, $c->text, $c->creationDate);
        }

        self::close($res);
        self::closeConnection($con);
        return $comment;
    }

    /**
     * get the User item by id
     *
     * @param integer $userId uid of that user
     * @return User | false
     */
    public static function getUserById(int $userId) { // no return type, cos "null" is not a valid User

        $con = self::getConnection();
        $user = false;

        $res = self::query($con, "SELECT id, userName, passHash FROM users WHERE id = ?", [$userId]);

        if ($u = self::fetchObject($res)) {
            $user = new User($u->id, $u->userName, $u->passHash);
        }

        self::close($res);
        self::closeConnection($con);

        return $user;
    }

    /**
     * get the User item by name
     *
     * note: show for case sensitive and insensitive options
     *
     * @param string $userName name of that user - must be exact match
     * @return User | false
     */
    public static function getUserByUserName(string $userName) { // no return type, cos "null" is not a valid User
        $con = self::getConnection();
        $user = false;

        $res = self::query($con, "SELECT id, userName, passHash FROM users WHERE userName = ?", [$userName]);

        if ($u = self::fetchObject($res)) {
            $user = new User($u->id, $u->userName, $u->passHash);
        }

        self::close($res);
        self::closeConnection($con);

        return $user;
    }

    public static function getCommentCount(int $articleId) : int {
        $con = self::getConnection();
        $count = 0;

        $res = self::query($con, "SELECT COUNT(id) AS count FROM comment WHERE articleId = ? AND active = 1", [$articleId]);

        if($c = self::fetchObject($res)) {
            $count = $c->count;
        }

        self::close($res);
        self::closeConnection($con);
        return $count;
    }

    public static function addArticle(int $user, int $categoryId, string $title, string $subtitle, string $text) {
        $con = self::getConnection();
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $con->beginTransaction();

        $articleId = null;

        try {
            self::query($con, "INSERT INTO article (category, title, subtitle, text, author) VALUES (?,?,?,?,?)", [$categoryId, $title, $subtitle, $text, $user]);

            $articleId = self::lastInsertId($con);
            $con->commit();
        } catch (\Exception $e) {
            $con->rollBack();
            $articleId = null;
        }

        self::closeConnection($con);
        return $articleId;
    }

    public static function removeArticle(int $articleId) : bool {
        $con = self::getConnection();
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $con->beginTransaction();

        try {
            self::query($con, "UPDATE article SET active = 0 WHERE id = ?",[$articleId]);
            $con->commit();
            $ret = true;
        } catch(\Exception $e) {
            $con->rollBack();
            $ret = false;
        }

        self::closeConnection($con);
        return $ret;
    }

    public static function registerUser( string $username, string $password) : bool {
        $con = self::getConnection();
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $con->beginTransaction();

        $username = Util::escape($username);

        $passHash = hash('sha1', $username . "|" . $password);

        try{
            self::query($con, "INSERT INTO users (username, passhash) VALUES (?,?)",[$username, $passHash]);
            $con->commit();
            $ret = true;
        } catch(\Exception $e) {
            $con->rollBack();
            $ret = false;
        }

        self::closeConnection($con);
        return $ret;
    }

    public static function editArticle(int $articleId, string $title, string $subtitle, string $text, int$categoryId) : bool {
        $con = self::getConnection();
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $con->beginTransaction();

        try {
            self::query($con, "UPDATE article SET title = ?, subtitle = ?, text = ?, category = ? WHERE id = ?", [$title, $subtitle, $text, $categoryId, $articleId]);
            $con->commit();
            $ret = true;
        } catch (\Exception $e) {
            $con->rollBack();
            $ret = false;
        }

        self::closeConnection($con);

        return $ret;
    }

    public static function addComment(int $user, int $articleId, string $text) : bool {
        $con = self::getConnection();
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $con->beginTransaction();

        try {
            self::query($con, "INSERT INTO comment (articleId, authorId, text) VALUES (?,?,?)", [$articleId, $user, $text]);

            $ret = true;
            $con->commit();
        } catch (\Exception $e) {
            $con->rollBack();
            $ret = false;
        }

        self::closeConnection($con);
        return $ret;
    }

    public static function removeComment(int $commentId) : bool {
        $con = self::getConnection();
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $con->beginTransaction();

        try {
            self::query($con, "UPDATE comment SET active = 0 WHERE id = ?",[$commentId]);
            $con->commit();
            $ret = true;
        } catch(\Exception $e) {
            $con->rollBack();
            $ret = false;
        }

        self::closeConnection($con);
        return $ret;
    }

    public static function editComment(int $commentId, string $text) : bool {
        $con = self::getConnection();
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $con->beginTransaction();

        try {
            self::query($con, "UPDATE comment SET text = ? WHERE id = ?", [$text, $commentId]);
            $con->commit();
            $ret = true;
        } catch (\Exception $e) {
            $con->rollBack();
            $ret = false;
        }

        self::closeConnection($con);

        return $ret;
    }
}
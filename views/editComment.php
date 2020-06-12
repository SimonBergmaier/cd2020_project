<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 19.05.2018
 * Time: 18:45
 */

use Blog\Util;
use Blog\AuthenticationManager;
use Data\DataManager;
use Blog\Controller;

if (!AuthenticationManager::isAuthenticated()) {
    Util::redirect("index.php");
}

$comment = DataManager::getCommentById($_REQUEST['id']);

if (!AuthenticationManager::isAuthenticated() && AuthenticationManager::getAuthenticatedUser()->getId() != $comment->getAuthor()) {
    Util::redirect("index.php");
}

require_once("partials/header.php");
?>

    <div class="page-header">
        <h2>Edit Comment</h2>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Please fill out the form below:
        </div>
        <div class="panel-body">

            <form class="form-horizontal" method="post" action="<?php echo Util::action(Controller::ACTION_ECOMMENT, array('view' => $view)); ?>">
                <input type="hidden" name="<?php echo Controller::COMMENT_ID ?>" value="<?php echo $comment->getId() ?>">
                <input type="hidden" name="<?php echo Controller::ARTICLE_ID ?>" value="<?php echo $comment->getArticleId()?>">
                <div class="form-group">
                    <label for="inputText" class="col-sm-2 control-label">Text:</label>
                    <div class="col-sm-6">
                        <textarea rows="10" class="form-control" id="inputText" name="<?php print Controller::TEXT; ?>" placeholder="Add the Text of the Comment..."><?php echo $comment->getText()?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
                        <button type="submit" class="btn btn-default">Edit Comment...</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

<?php
require_once('views/partials/footer.php');

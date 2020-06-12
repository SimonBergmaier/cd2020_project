<?php
require_once('inc/bootstrap.php');
require_once('partials/header.php');

use Data\DataManager;
use Blog\Util;
use Blog\Controller;
use Blog\AuthenticationManager;

$article = DataManager::getArticleById((int)$_REQUEST['id']);

if (!$article) {
    Util::redirect();
}

$author = DataManager::getUserById($article->getAuthor());
$comments = DataManager::getCommentsByArticle($article->getId());
$user = AuthenticationManager::getAuthenticatedUser();

?>
    <div class="page-header container">
        <div class="row">
            <h2 class="col-sm-12"><?php echo $article->getTitle() ?></h2>
        </div>
        <div class="row">
            <h4 class="col-sm-8"><?php echo $article->getSubtitle() ?></h4>

            <?php if ($user != null && $user->getId() === $article->getAuthor()): ?>
                <form class="form-inline" method="post"
                      action="<?php echo Util::action(Controller::ACTION_REMOVE, array('view' => $view)); ?>">
                    <input type="hidden" name="<?php echo Controller::ARTICLE_ID ?>"
                           value="<?php echo $article->getId() ?>">
                    <div class="btn-group">
                        <a class="btn btn-default" href="index.php?view=editArticle&id=<?php echo $article->getId() ?>"><span
                                    class="glyphicon glyphicon-edit"></span> Edit
                            Article...</a>
                        <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>
                            Delete Article...
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

    </div>
    <div class="container">
        <div class="text-left">
            <?php echo nl2br($article->getText()) ?>
        </div>
        <div class="text-right text-muted">
            Author: <?php echo $author->getUserName() ?>
            CreationDate: <?php echo $article->getCreationDate() ?>
        </div>
    </div>
<?php if (AuthenticationManager::isAuthenticated()): ?>

    <hr/>
    <div class="container">
        <div class="row">
            <h3 class="col-sm-12">Create Comment: </h3>
            <form class="form-horizontal" method="post"
                  action="<?php echo Util::action(Controller::ACTION_ACOMMENT, array('view' => $view)); ?>">
                <div class="row">
                    <input type="hidden" name="<?php echo Controller::ARTICLE_ID ?>"
                           value="<?php echo $article->getId() ?>">
                    <div class="col-sm-6">
                    <textarea class="form-control" rows="3" id="inputText" name="<?php echo Controller::TEXT ?>"
                              placeholder="Please add your Comment here..." required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-pencil"></span>
                            Create Comment...
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
    <hr/>
    <div class="container">
        <div class="row">
            <h3 class="col-sm-12">Comments:</h3>
        </div>
        <div class="panel-group">
            <?php if (sizeof($comments) > 0) : ?>
                <?php foreach ($comments as $comment) : ?>
                    <?php $cauthor = DataManager::getUserById($comment->getAuthor()); ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p class="col-sm-8"><?php echo nl2br($comment->getText()); ?></p>
                            <?php if ($user != null && ($comment->getAuthor() === $user->getId() || $article->getAuthor() === $user->getId())) : ?>
                                <form class="form-inline" method="post"
                                      action="<?php echo Util::action(Controller::ACTION_RCOMMENT, array('view' => $view)); ?>">
                                    <input type="hidden" name="<?php echo Controller::COMMENT_ID ?>"
                                           value="<?php echo $comment->getId(); ?>">
                                    <input type="hidden" name="<?php echo Controller::ARTICLE_ID ?>"
                                           value="<?php echo $article->getId(); ?>">
                                    <div class="btn-group pull-right">
                                        <?php if ($user->getId() == $comment->getAuthor()) : ?>
                                            <a href="index.php?view=editComment&id=<?php echo $comment->getId(); ?>"
                                               class="btn btn-default"><span class="glyphicon glyphicon-edit"></span>
                                                Edit Comment...</a>
                                        <?php endif; ?>
                                        <button type="submit" class="btn btn-danger"><span
                                                    class="glyphicon glyphicon-trash"></span> Delete Comment...
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>

                        <div class="panel-footer text-muted">
                            Author: <?php echo $cauthor->getUserName() ?>
                            CreationDate: <?php echo $comment->getCreationDate() ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-info">No Comments for this Article</div>
            <?php endif; ?>
        </div>
    </div>

    <script type="text/javascript">

    </script>


<?php
require_once('partials/footer.php');
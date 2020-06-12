<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 17.05.2018
 * Time: 13:51
 */

use Blog\Util;
use Blog\AuthenticationManager;
use Data\DataManager;

if (!AuthenticationManager::isAuthenticated()) {
    Util::redirect("index.php");
}

$article = DataManager::getArticleById($_REQUEST['id']);

$categories = DataManager::getCategories();

require_once("partials/header.php");
?>

    <div class="page-header">
        <h2>Edit Article</h2>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Please fill out the form below:
        </div>
        <div class="panel-body">

            <form class="form-horizontal" method="post" action="<?php echo Util::action(Blog\Controller::ACTION_EDIT, array('view' => $view)); ?>">
                <input type="hidden" name="<?php echo Blog\Controller::ARTICLE_ID ?>" value="<?php echo $article->getId() ?>">
                <div class="form-group">
                    <label for="inputTitle" class="col-sm-2 control-label">Title:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="inputTitle" name="<?php print Blog\Controller::TITLE; ?>" placeholder="Add the Title of the Article..." value="<?php echo $article->getTitle()?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputSubtitle" class="col-sm-2 control-label">Subtitle:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="inputSubtitle" name="<?php print Blog\Controller::SUBTITLE; ?>" placeholder="Add the Subtitle of the Article..." value="<?php echo $article->getSubtitle()?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputText" class="col-sm-2 control-label">Text:</label>
                    <div class="col-sm-6">
                        <textarea rows="10" class="form-control" id="inputText" name="<?php print Blog\Controller::TEXT; ?>" placeholder="Add the Text of the Article..."><?php echo $article->getText()?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCategory" class="col-sm-2 control-label">Category:</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="inputCategory" name="<?php print Blog\Controller::CATEGORY; ?>" required>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category->getId(); ?>" <?php if($article->getCategoryId() === $category->getId()) echo 'selected'?>><?php echo $category->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
                        <button type="submit" class="btn btn-default">Edit Article...</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

<?php
require_once('views/partials/footer.php');

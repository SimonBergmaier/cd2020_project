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

$categories = DataManager::getCategories();

require_once("partials/header.php");
?>

    <div class="page-header">
        <h2>Create new Article</h2>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Please fill out the form below:
        </div>
        <div class="panel-body">

            <form class="form-horizontal" method="post" action="<?php echo Util::action(Blog\Controller::ACTION_ADD, array('view' => $view)); ?>">
                <div class="form-group">
                    <label for="inputTitle" class="col-sm-2 control-label">Title:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="inputTitle" name="<?php print Blog\Controller::TITLE; ?>" placeholder="Add the Title of the Article..." required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputSubtitle" class="col-sm-2 control-label">Subtitle:</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="inputSubtitle" name="<?php print Blog\Controller::SUBTITLE; ?>" placeholder="Add the Subtitle of the Article..." required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputText" class="col-sm-2 control-label">Text:</label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="inputText" name="<?php print Blog\Controller::TEXT; ?>" placeholder="Add the Text of the Article..."></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputCategory" class="col-sm-2 control-label">Category:</label>
                    <div class="col-sm-6">
                        <select class="form-control" id="inputCategory" name="<?php print Blog\Controller::CATEGORY; ?>" required>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category->getId(); ?>"><?php echo $category->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
                        <button type="submit" class="btn btn-default">Create Article...</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

<?php
require_once('views/partials/footer.php');

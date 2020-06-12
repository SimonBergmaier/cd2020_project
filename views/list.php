<?php
//require_once('lib/Bookshop/BaseObject.php');
//require_once('lib/Bookshop/Entity.php');
//require_once('lib/Bookshop/Category.php');

require_once('inc/bootstrap.php');
require_once('partials/header.php');

use Data\DataManager;
use Blog\Util;

$categories = DataManager::getCategories();
$categoryId = isset($_REQUEST['categoryId']) ? (int)$_REQUEST['categoryId'] : null;

$articles = (isset($categoryId) && $categoryId > 0) ? DataManager::getArticlesByCategory($categoryId) : null;

?>

<div class="page-header">
  <h2>List of Articles by category</h2>
</div>

<ul class="nav nav-tabs">
  <?php foreach ($categories  as $cat) : ?>
      <li role="presentation"
          <?php if ($cat->getId() === $categoryId) : ?>class="active" <?php endif; ?>>
          <a href="<?php echo $_SERVER['PHP_SELF'] ?>?view=list&categoryId=<?php echo urlencode($cat->getId()); ?>"><?php echo Util::escape($cat->getName()); ?></a></span>
      </li>
  <?php endforeach; ?>
</ul>

<?php
    if(isset($articles)) :
        if(sizeof($articles) > 0) :
            require_once('partials/articlelist.php');
        else :?>
        <div class="alert alert-warning">No articles in this category</div>
        <?php
        endif;
    else :
        ?>
        <div class="alert alert-info">Please select a category</div>
    <?php
    endif;
?>

<?php
require_once('partials/footer.php');
?>
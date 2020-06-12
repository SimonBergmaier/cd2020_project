<?php

use Blog\Util;
use Data\DataManager;
?>

<table class="table">
    <thead>
    <tr>
        <th>
            Title
        </th>
        <th>
            Subtitle
        </th>
        <th>
            Author
        </th>
        <th>
            Comments
        </th>
        <th>
            Creation Date
        </th>
        <th>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($articles as $article): ?>
        <tr>
            <td>
                <strong>
                    <?php echo Util::escape($article->getTitle()); ?>
                </strong>
            </td>
            <td>
                <?php echo Util::escape($article->getSubtitle()); ?>
            </td>
            <td>
                <?php
                $user = DataManager::getUserById(intval($article->getAuthor()));
                echo $user->getUserName();
                ?>
            </td>
            <td>
                <?php echo DataManager::getCommentCount($article->getId());?>
            </td>
            <td>
                <?php echo Util::escape($article->getCreationDate()); ?>
            </td>
            <td>
                <a class="btn btn-sm btn-default" href="index.php?view=article&id=<?php echo Util::escape($article->getId());?>"><span class="glyphicon glyphicon-eye-open"></span> View Article</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
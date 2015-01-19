
<?php if (!empty($posts)):?>
<?php foreach ($posts as $key => $post): ?>
<div class="media">
    <span class="pull-left">
        <?php
            $image = $post['thumb'];

            if ($image) {

                $image_thumb = str_replace('.', '_thumb.', $image);

                echo '<img src="'. BASE_APP_FOLDER . '/public/upload/'  . $image_thumb .'" class="media-object">';
            }
        ?>
    </span>
    <div class="media-body">
        <h4 class="media-heading">
            <a href="<?php echo BASE_APP_FOLDER . '/post/details/' . $post['id']; ?>"><?php echo $post['title']; ?></a>
            <?php
                if (UserModel::isLogged()):

                $hrefToEdit = BASE_APP_FOLDER . '/post/edit/' . $post['id'];
                $hrefToTrash = BASE_APP_FOLDER . '/post/delete/' . $post['id'];

            ?>

            <a href="<?php echo $hrefToTrash; ?>" class="pull-right trash-link">
                <i class="glyphicon glyphicon-trash"></i>
            </a>

            <a href="<?php echo $hrefToEdit; ?>" class="pull-right edit-link">
                <i class="glyphicon glyphicon-pencil"></i>
            </a>

            <?php
                endif;
            ?>
        </h4>

        <?php
            echo substr(htmlspecialchars_decode($post['description'], ENT_QUOTES), 0, 500) . ' ... ';
        ?>

    </div>
</div>

<div class="clearfix"></div>
<?php endforeach; ?>

    <ul class="pagination">
        <?php

        $appendToHREF = $inputSearchQuery ? '?inputSearchQuery=' . urlencode($inputSearchQuery) : '';

        for ($i = 1, $max = $pagination->maxPage; $i <= $max; $i++) {

            if ($pagination->currentPage === $i) {
                echo "<li class='active'><span>" . $i . "</span></li>";
            } else {
                echo "<li><a href='" . BASE_APP_FOLDER . '/post/index/' . $i . $appendToHREF . "'>" . $i . "</a></li>";
            }
        }

        ?>
    </ul>

<?php else: ?>

    <p class="lead">
        Brak wyników.
    </p>

<?php endif; ?>


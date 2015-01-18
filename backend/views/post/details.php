<div class="media">
    <?php
        $image = $post['thumb'];

        if ($image) {
            $src = BASE_APP_FOLDER . '/public/upload/' . $image;
            echo "<div class='media-object pull-left'>";
            echo "<img src='" . $src . "'>";
            echo "</div>";
        }

    ?>
    <div class="media-body">
        <?php echo htmlspecialchars_decode($post['description'], ENT_QUOTES); ?>
    </div>
</div>
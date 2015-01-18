<nav class="navbar navbar-default">
    <div class="container">

        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo BASE_APP_FOLDER; ?>/">Simple Blog</a>
        </div>

        <ul class="nav navbar-nav">
            <li>
                <a href="<?php echo BASE_APP_FOLDER; ?>/">Lista postów</a>
            </li>

            <?php if (!UserModel::isLogged()): ?>

                <li>
                    <a href="<?php echo BASE_APP_FOLDER; ?>/user/login">Zaloguj</a>
                </li>

            <?php else: ?>

                <li>
                    <a href="<?php echo BASE_APP_FOLDER; ?>/post/add">Dodaj post</a>
                </li>

                <li>
                    <a href="<?php echo BASE_APP_FOLDER; ?>/user/logout">Wyloguj</a>
                </li>

            <?php endif; ?>

        </ul>

    </div>
</nav>
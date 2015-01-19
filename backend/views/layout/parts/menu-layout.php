<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="<?php echo BASE_APP_FOLDER; ?>/"><i class="glyphicon glyphicon-home"></i> Blog</a>
        </div>

        <ul class="nav navbar-nav">
            <li>
                <a href="<?php echo BASE_APP_FOLDER; ?>/"><i class="glyphicon glyphicon glyphicon-th-list"></i> Lista postów</a>
            </li>
            <?php if (!UserModel::isLogged()): ?>
            <li>
                <a href="<?php echo BASE_APP_FOLDER; ?>/user/login"><i class="glyphicon glyphicon-user"></i> Zaloguj</a>
            </li>
            <?php else: ?>
            <li>
                <a href="<?php echo BASE_APP_FOLDER; ?>/post/add"><i class="glyphicon glyphicon glyphicon-plus"></i> Dodaj post</a>
            </li>

            <li>
                <a href="<?php echo BASE_APP_FOLDER; ?>/user/logout"><i class="glyphicon glyphicon glyphicon-off"></i> Wyloguj</a>
            </li>
            <?php endif; ?>
        </ul>

        <form class="navbar-form navbar-right" role="search" method="GET">
            <div class="form-group">
                <input id="inputSearchQuery" name="inputSearchQuery" value="<?php echo $inputSearchQuery; ?>" type="text" class="form-control" placeholder="Wpisz szukaną frazę">
            </div>
            <button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i> Szukaj</button>
        </form>
    </div>
</nav>
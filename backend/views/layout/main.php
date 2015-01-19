<?php
extract($this->_viewParams);
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="author" content="Piotr Pażuchowski">

    <link rel="stylesheet" href="<?php echo BASE_APP_FOLDER; ?>/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_APP_FOLDER; ?>/public/css/main.css">

    <?php $this->renderHeadScripts(); ?>

</head>

<body>

<?php
require_once "parts/menu-layout.php";
?>

<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="page-header">
                <h1><?php echo $headerText; ?></h1>
            </div>

            <?php $this->renderContent(); ?>

        </div>
    </div>
</div>

<?php $this->renderBottomScripts(); ?>

</body>

</html>
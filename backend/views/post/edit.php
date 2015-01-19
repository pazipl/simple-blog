<?php

$modelTitle = $model->getTitle();
$modelDescription = $model->getDescription();

$inputTitle = $modelTitle ? $modelTitle : '';
$inputDescription = $modelDescription ? htmlspecialchars_decode($modelDescription, ENT_QUOTES) : '';
$inputImage = $post['thumb'] ? BASE_APP_FOLDER . '/public/upload/' . str_replace('.', '_thumb.', $post['thumb']) : null;

$errorMessages = $model->getErrorMessages();

if (!empty($errorMessages)):
    ?>
    <div class="alert alert-danger">
        <ul>
            <?php
            foreach ($errorMessages as $key => $message) {
                echo '<li>' . $message . '</li>';
            }
            ?>
        </ul>
    </div>
<?php
endif;

?>

<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="inputTitle" class="col-sm-2 control-label">Tytuł <span class="text-danger">*</span></label>

        <div class="col-sm-10">
            <input placeholder="Wprowadź tytuł" id="inputTitle" class="form-control" name="inputTitle"
                   value="<?php echo $inputTitle ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="inputDescription" class="col-sm-2 control-label">Treść <span class="text-danger">*</span></label>

        <div class="col-sm-10">
            <textarea placeholder="Wprowadź treść" id="inputDescription" class="ckeditor"
                      name="inputDescription"><?php echo $inputDescription; ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="inputImage" class="col-sm-2 control-label">Obrazek</label>

        <div class="col-sm-10">
            <?php if ($inputImage): ?>
                <div class="list-inline">
                    <img src="<?php echo $inputImage ?>">
                    <input id="inputRemoveImage" type="checkbox" name="inputRemoveImage">
                    <label for="inputRemoveImage">Usuń</label>

                </div>

            <?php else: ?>
                <input id="inputImage" name="inputImage" type="file">
                <p class="help-block">Maksymalny rozmiar pliku to 2MB.</p>
            <?php endif; ?>
        </div>

    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success">Zapisz</button>
        </div>
    </div>


</form>
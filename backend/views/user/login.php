<?php
    $modelLogin = $model->getLogin();
    $modelPassword = $model->getPassword();

    $inputLogin = $modelLogin ? $modelLogin : '';
    $inputPassword = $modelPassword ? $modelPassword : '';
?>


<?php if (!empty($model->errorMessage)): ?>
<p class="alert alert-danger">
    <?php echo $model->errorMessage; ?>
</p>
<?php endif; ?>

<p class="alert text-center">
    Aby się zalogować użyj danych: User / SuperTajneHaslo2015
</p>

<form class="form-horizontal" role="form" method="POST">

    <div class="form-group">
        <label for="inputLogin" class="col-sm-2 control-label">Login <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            <input placeholder="Wprowadź login ..." name="inputLogin" value="<?php echo $inputLogin ?>" class="form-control" id="inputLogin" type="text" />
        </div>
    </div>

    <div class="form-group">
        <label for="inputPassword" class="col-sm-2 control-label">Hasło <span class="text-danger">*</span></label>
        <div class="col-sm-10">
            <input placeholder="Wprowadź hasło ..." name="inputPassword" value="<?php echo $inputPassword ?>" class="form-control" id="inputPassword" type="password" />
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">Zaloguj się</button>
        </div>
    </div>

</form>
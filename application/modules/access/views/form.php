
    <div class="row">
      <div class="col-md-12">
        <h1><?php echo $form_title ?></h1>

        <ol class="breadcrumb">
          <li><a href="/admin">Inicio</a></li>
          <li>Administradores</li>
          <li class="active"><?php echo $breadcrumb ?></li>
        </ol>
      </div>
    </div>

    <?php if (validation_errors() != ''): ?>
      <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
        <h4>Error</h4>
        La información está incompleta o errónea.
      </div>
    <?php endif ?>

    <section class="panel">
      <div class="panel-body">
        <form action="" method="post" class="form-horizontal">
          <?php $error = form_error('data[name]'); ?>
          <div class="form-group<?php echo ($error != '') ? ' has-error' : ''; ?>">
            <label for="name" class="col-lg-2 control-label">Nombre</label>
            <div class="col-lg-3">
              <input type="text" name="data[name]" id="name" class="form-control" value="<?php echo set_value('name', $name); ?>">
            </div>
            <?php echo $error; ?>
          </div>

          <?php $error = form_error('data[mail]'); ?>
          <div class="form-group<?php echo ($error != '') ? ' has-error' : ''; ?>">
            <label for="mail" class="col-lg-2 control-label">Correo</label>
            <div class="col-lg-3">
              <input type="text" name="data[mail]" id="mail" class="form-control" value="<?php echo set_value('mail', $mail); ?>">
            </div>
            <?php echo $error; ?>
          </div>

          <?php $error = form_error('data[user]'); ?>
          <div class="form-group<?php echo ($error != '') ? ' has-error' : ''; ?>">
            <label for="user" class="col-lg-2 control-label">Usuario</label>
            <div class="col-lg-3">
              <input type="text" name="data[user]" id="user" class="form-control" value="<?php echo set_value('user', $user); ?>">
            </div>
            <?php echo $error; ?>
          </div>

          <?php $error = form_error('data[pass]'); ?>
          <div class="form-group<?php echo ($error != '') ? ' has-error' : ''; ?>">
            <label for="pass" class="col-lg-2 control-label">Contraseña</label>
            <div class="col-lg-3">
              <input type="password" name="data[pass]" id="pass" class="form-control">
            </div>
            <?php echo $error; ?>
          </div>

          <?php $error = form_error('repeat'); ?>
          <div class="form-group<?php echo ($error != '') ? ' has-error' : ''; ?>">
            <label for="repeat" class="col-lg-2 control-label">Repetir</label>
            <div class="col-lg-3">
              <input type="password" name="repeat" id="repeat" class="form-control">
            </div>
            <?php echo $error; ?>
          </div>

          <?php $error = form_error('data[active]'); ?>
          <div class="form-group<?php echo ($error != '') ? ' has-error' : ''; ?>">
            <label for="active" class="col-lg-2 control-label">Activo</label>
            <div class="col-lg-3">
              <input type="hidden" name="data[active]" value="0">
              <input type="checkbox" name="data[active]" value="1" class="js-switch" <?php echo validate_checkbox($active, 1); ?>>
            </div>
            <?php echo $error; ?>
          </div>

          <div class="form-group">
            <div class="col-lg-3 col-sm-offset-2 btn-crud">
              <button type="submit" class="btn btn-success">Guardar</button>
              <a href="/admin/access" class="btn btn-default">Regresar</a>
            </div>
          </div>

        </form>
      </div>
    </section>

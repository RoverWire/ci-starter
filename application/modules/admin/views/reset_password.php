
    <div class="center-content">
      <div class="panel-login">

        <?php if ($this->session->flashdata('error') === TRUE): ?>
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
            El correo proporcionado no se encuentra registrado.
          </div>
        <?php endif ?>

        <?php if ($this->session->flashdata('sucess') === TRUE): ?>
          <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
            Los nuevos accesos le han sido enviado a su correo. No olvide verificar su carpeta de SPAM.
          </div>
        <?php endif ?>

        <section class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title text-center">Reestablecer Contraseña</h3>
          </div>
          <div class="panel-body">
            <form accept-charset="UTF-8" action="" role="form" method="post">
              <fieldset>
                <div class="form-group">
                  <input type="password" class="form-control" placeholder="Nueva Contraseña" name="mail">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" placeholder="Confirmar" name="mail">
                </div>
                <button class="btn btn-lg btn-info btn-block" type="submit">Actualizar</button>
              </fieldset>
            </form>
          </div>
        </section>
      </div>
    </div>

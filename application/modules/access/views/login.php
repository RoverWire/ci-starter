    <div class="center-content">
      <div class="panel-login">
        <?php if ($this->session->flashdata('error') === TRUE): ?>
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
            Credenciales incorrectas. Intente de nuevo.
          </div>
        <?php endif ?>

        <?php if ($this->session->flashdata('success') === TRUE): ?>
          <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
            Accesos actualizados. Puede iniciar sesión con su nueva contraseña.
          </div>
        <?php endif ?>

        <section class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title text-center">Administración</h3>
          </div>
          <div class="panel-body">
            <form accept-charset="UTF-8" action="" role="form" method="post">
              <fieldset>
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Usuario" name="user">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" placeholder="Password" name="pass" value="">
                </div>
                <button class="btn btn-lg btn-success btn-block" type="submit" value="Login">Login</button>
              </fieldset>
            </form>
          </div>
        </section>
      </div>
      <div class="col-md-12 text-center">
        <a href="/access/lost-password">¿Olvidó su contraseña?</a>
      </div>
    </div>

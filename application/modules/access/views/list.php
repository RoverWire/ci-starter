
<div class="row">
  <div class="col-md-12">
    <h1>Administradores</h1>

    <ol class="breadcrumb">
      <li><a href="/admin">Inicio</a></li>
      <li class="active">Administradores</li>
    </ol>
  </div>
</div>

<?php if (!empty($msg_success)): ?>
  <div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
    <?php echo $msg_success; ?>
  </div>
<?php endif ?>

<form action="<?php echo (isset($form_action)) ? $form_action:''?>" id="consulta" method="post">
<section class="panel">
  <div class="panel-body">

    <div class="row search-row">
      <div class="col-sm-6">
        <div class="input-group">
          <input type="text" name="search" placeholder="buscar" value="<?php echo $search ?>" class="form-control">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
          <?php if (!empty($search)): ?>
            <a href="/admin/access" class="btn btn-default"><i class="fa fa-undo"></i></a>
          <?php endif ?>

          </span>
        </div>
      </div>

      <div class="col-sm-6 col-btn-add">
        <a href="/admin/access/add" class="btn btn-success">
          Agregar Nuevo
        </a>
      </div>
    </div>

    <div class="col-12 table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th width="1%">#</th>
            <th width="30%">Nombre</th>
            <th>Usuario</th>
            <th>Correo</th>
            <th width="1%">Activo</th>
            <th class="text-center">Opciones</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <td colspan="7">
              &nbsp;
            </td>
          </tr>
        </tfoot>
        <tbody>
        <?php if ($query->num_rows()): ?>

          <?php foreach ($query->result() as $row): ?>

          <tr>
            <td><input type="checkbox" name="del[]" value="<?php echo $row->id; ?>"<?php echo ($row->id == 1 || $row->user == 'admin') ? ' disabled':'' ?>></td>
            <td><?php echo $row->name ?></td>
            <td><?php echo $row->user ?></td>
            <td><?php echo $row->mail ?></td>
            <td class="text-center"><i class="fa <?php echo ($row->active == 0) ? 'fa-close red':'fa-check green' ?>"></i></td>
            <td class="text-center">
              <div class="btn-group table-options">
                <a href="/admin/access/edit/<?php echo $row->id ?>" class="btn btn-default" title="editar"><i class="fa fa-pencil"></i></a>
                <a href="/admin/access/delete/<?php echo $row->id ?>" class="btn btn-danger<?php echo ($row->id == 1 || $row->user == 'admin') ? ' disabled':'' ?>" title="eliminar"><i class="fa fa-trash-o"></i></a>
              </div>
            </td>
          </tr>

          <?php endforeach ?>

        <?php else: ?>

          <tr>
            <td class="text-center" colspan="6">No se encontraron resultados.</td>
          </tr>

        <?php endif ?>
        </tbody>
      </table>
    </div>

    <div class="row">
      <div class="col-md-12">
        <button type="button" class="btn btn-danger btn-sm" id="btn-delete"><i class="fa fa-trash"></i> Eliminar Seleccion</button>
      </div>

      <div class="col-12 hidden-sm text-center">
        <?php echo $pages ?>
      </div>

      <div class="col-12 visible-sm text-center">
        <?php echo $pages_mobile ?>
      </div>
    </div>

  </div>
</section>
</form>

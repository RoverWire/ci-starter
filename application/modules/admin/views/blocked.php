<div class="center-content">
	<div class="panel panel-login panel-danger">
		<div class="panel-heading text-center">
			Conexión Bloqueada
		</div>
		<div class="panel-body">
			<p>Por seguridad su conexión ha sido bloqueada
			tras alcanzar el número máximo de intentos
			permitidos para iniciar sesión.</p>

			<p>Podrá intentar nuevamente en:</p>

			<div id="clock" class="text-center" data-remaining="<?php echo $remaining ?>"><?php echo $remaining ?></div>
		</div>
	</div>
</div>

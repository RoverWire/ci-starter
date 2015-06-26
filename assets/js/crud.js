$(function(){
	$('#btn-delete').click(function(){
		if($('input[type=checkbox]:checked').length){
			if (confirm('Se eliminarán los elementos seleccionados')) {
				$('#consulta').submit();
			}
		} else {
			alert('No se ha seleccionado ningún elemento.');
		}
	});

	$('a[title=eliminar]').click(function(e){
		if(! confirm('Eliminar el elemento')){
			e.preventDefault();
		}
	});

	$('#detalle-usuario').on('hidden.bs.modal', function() {
		$(this).removeData();
	});

	$('#delete-image').click(function() {
		var id  = $(this).data('id');
		var url = $(this).data('url');
		$.ajax({
			url: url,
			type: 'POST',
			data: {'id': id}
		})
		.done(function() {
			$('.single-image').slideUp();
			setTimeout(function(){
				$('.single-image').remove();
			},1000);
		});
	});
});

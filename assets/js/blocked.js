$(function() {
  $('#clock').countdown($('#clock').data('end'), function(event) {
    $(this).text(
      event.strftime('%H:%M:%S')
    );
  }).on('finish.countdown', function() {
    location.reload();
  });
});

function end_blocked_time() {
  var time = $('#clock').data('remaining').split(':');
  var end  = new Date(new Date().valueOf() + (time[0] * 3600000) + (time[1] * 60000) + (time[2] * 1000));

  $('#clock').countdown(end, function(event) {
    $(this).text( event.strftime('%H:%M:%S') );
  }).on('finish.countdown', function() {
    location.reload();
  });
}

$(function() {
  end_blocked_time();
});

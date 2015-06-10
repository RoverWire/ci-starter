<?php
  foreach ($report as $key) {
    $visits[] = $key[1];
    $news[]   = $key[2];
    $label[]  = "'".date('d', strtotime($key[0]))."'";
  }
?>

<div class="row mg-b">
  <div class="col-sm-12">
    <h3 class="no-margin">Dashboard</h3>
  </div>
</div>

<div class="panel panel-graph">
  <div class="panel-heading">
    <h4 class="panel-title">Analytics</h4>
  </div>
  <div class="panel-body">
    <div class="canvas-holder">
      <canvas id="my_visits" class="main-graph"></canvas>
    </div>
  </div>
</div>

<script src="/assets/vendor/chart.js"></script>
<script>
  var options = {
    responsive: true,
    maintainAspectRatio: false
  }

  var data = {
    labels: [<?php echo implode(', ', $label) ?>],
    datasets: [
      {
        label: "Visitas",
        fillColor: "rgba(220,220,220,0.2)",
        strokeColor: "rgba(220,220,220,1)",
        pointColor: "rgba(220,220,220,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(220,220,220,1)",
        data: [<?php echo implode(', ', $visits) ?>]
      },
      {
        label: "Nuevas",
        fillColor: "rgba(151,187,205,0.2)",
        strokeColor: "rgba(151,187,205,1)",
        pointColor: "rgba(151,187,205,1)",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "rgba(151,187,205,1)",
        data: [<?php echo implode(', ', $news) ?>]
      },
    ]
  }

  var ctx = document.getElementById("my_visits").getContext("2d");
  var myLineChart = new Chart(ctx).Line(data, options);
</script>

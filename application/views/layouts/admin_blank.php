<!DOCTYPE html>
<html class="no-js" lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin - <?php echo $title ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/vendor/offline/theme.css">
    <?php echo $_styles ?>

    <script src="/assets/vendor/modernizr.js"></script>
  </head>
  <body class="<?php echo $body_class ?>">
    <?php echo $content ?>

    <script src="/assets/vendor/jquery.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <?php echo $_scripts ?>

  </body>
</html>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-touch-fullscreen" content="yes">
  <title>FlinkISO | QMS</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php
  echo $this->Html->meta('icon');
  echo $this->Html->css(array(
      // 'cake.generic',
      'bootstrap/css/bootstrap.min',
      'dist/css/AdminLTE.min',
      'dist/css/skins/_all-skins.min',
      'plugins/iCheck/flat/blue',
      'plugins/morris/morris',
      'plugins/jvectormap/jquery-jvectormap-1.2.2',
      'plugins/datepicker/datepicker3',
      'plugins/daterangepicker/daterangepicker-bs3',
      'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min',
      'jquery.countdown',
      'jquery-ui-1.9.2.custom.min',
      'bootstrap-chosen.min',
      'jquery.datepicker',
      'custom','font-awesome.min','icons'
  ));

echo $this->fetch('css');
?>
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"> -->
  <!-- <link rel="stylesheet" href="dist/css/AdminLTE.min.css"> -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
<?php
echo $this->Html->script(array(
    // 'js/bootstrap.min','js/npm',
    'plugins/jQuery/jQuery-2.2.0.min',
    'plugins/jQueryUI/jquery-ui.min',
    // 'jquery-form.min',
    // 'jquery.validate.min',
    'js/bootstrap.min',
    'plugins/fastclick/fastclick',
    // 'plugins/morris/morris.min',
    'plugins/sparkline/jquery.sparkline.min',
    'plugins/jvectormap/jquery-jvectormap-1.2.2.min',
    'plugins/jvectormap/jquery-jvectormap-world-mill-en',
    'plugins/slimScroll/jquery.slimscroll.min',
    // 'plugins/chartjs/Chart.min',
    // 'dist/js/pages/dashboard2.js',
    'dist/js/demo',
    'plugins/knob/jquery.knob',
    'jquery.datepicker',
    'chosen.min',
    'tooltip.min',
    'plugins/daterangepicker/daterangepicker',
    'plugins/datepicker/bootstrap-datepicker',
    // 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min',
    
    
    'dist/js/app.min',
    // 'dist/js/pages/dashboard',
    // 'dist/js/demo',
    'timeout',
    'jquery.countdown',
    'validation'
));
echo $this->fetch('script');
?>
</head>
<body class="hold-transition skin-blue sidebar-collapse sidebar-mini">
<div class="wrapper">
  <section class="content">
  <?php echo $this->Session->flash(array('class'=>'alert-danger')); ?>
  <?php echo $this->fetch('content');?>  
  <!-- Info boxes -->
  <!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.006
    </div>
    <strong>Copyright &copy; 2013 <a href="http://www.techmentis.biz">Techmentis Global Services Pvt Ltd</a>.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.0 -->
<!-- <script src="plugins/jQuery/jQuery-2.2.0.min.js"></script> -->
<!-- Bootstrap 3.3.6 -->
<!-- <script src="bootstrap/js/bootstrap.min.js"></script> -->
<!-- FastClick -->
<!-- <script src="plugins/fastclick/fastclick.js"></script> -->
<!-- AdminLTE App -->
<!-- <script src="dist/js/app.min.js"></script> -->
<!-- Sparkline -->
<!-- <script src="plugins/sparkline/jquery.sparkline.min.js"></script> -->
<!-- jvectormap -->
<!-- <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script> -->
<!-- <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->
<!-- SlimScroll 1.3.0 -->
<!-- <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script> -->
<!-- ChartJS 1.0.1 -->
<!-- <script src="plugins/chartjs/Chart.min.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="dist/js/pages/dashboard2.js"></script> -->
<!-- AdminLTE for demo purposes -->
<!-- <script src="dist/js/demo.js"></script> -->

</body>
</html>

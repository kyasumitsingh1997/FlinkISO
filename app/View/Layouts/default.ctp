<?php header('Access-Control-Allow-Origin: *'); ?>
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

<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/dist/css/AdminLTE.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/dist/css/skins/_all-skins.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/plugins/iCheck/flat/blue.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/plugins/jvectormap/jquery-jvectormap-1.2.2.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/plugins/datepicker/datepicker3.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/plugins/daterangepicker/daterangepicker-bs3.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/jquery.countdown.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/jquery-ui-1.9.2.custom.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/jquery.datepicker.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/icons.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Configure::read('cdn');?>/css/bootstrap-chosen.min.css" />


<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/jQueryUI/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/validation.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/tooltip.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/jquery.datepicker.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/datepicker/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/chosen.min.js"></script>
  <?php
  echo $this->Html->meta('icon');
//   echo $this->Html->css(array(
//       // 'cake.generic',
//       'bootstrap/css/bootstrap.min',
//       'dist/css/AdminLTE.min',
//       'dist/css/skins/_all-skins.min',
//       'plugins/iCheck/flat/blue',
//       // 'plugins/morris/morris.min',
//       'plugins/jvectormap/jquery-jvectormap-1.2.2',
//       'plugins/datepicker/datepicker3',
//       'plugins/daterangepicker/daterangepicker-bs3',
//       'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min',
//       'jquery.countdown',
//       'jquery-ui-1.9.2.custom.min',
//       'bootstrap-chosen.min',
//       'jquery.datepicker',
//       'custom','font-awesome.min','icons'
//   ));

// echo $this->fetch('css');
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
// echo $this->Html->script(array(
//     // 'js/bootstrap.min','js/npm',
//     'plugins/jQuery/jQuery-2.2.0.min',
//     'plugins/jQueryUI/jquery-ui.min',
//     // 'jquery-form.min',
//     // 'jquery.validate.min',
//     'js/bootstrap.min',
//     'validation',
//     'chosen.min',
//     'tooltip.min',
//     'plugins/daterangepicker/moment.min',
//     'jquery.datepicker',    
//     'plugins/daterangepicker/daterangepicker',
//     'plugins/datepicker/bootstrap-datepicker',
// ));
// echo $this->fetch('script');
?>
<style type="text/css">
  /*.skin-blue .main-header .navbar, .skin-blue .main-header .logo{background-color: #ef0000;}*/
</style>
</head>
<body class="hold-transition skin-blue sidebar-collapse sidebar-mini">
  
<div class="wrapper">  
  <?php echo $this->Element('header');?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php if ($this->Session->read('User'))echo $this->Element('asidebar');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  <!-- Content Header (Page header) -->
	<?php if ($this->Session->read('User')) echo $this->Element('breadcrump');?>	    
  <!-- Main content -->
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
	<?php
if ($this->Session->read('User'))
    echo $this->Element('control-sidebar');
?>  

</div>
<!-- ./wrapper -->

<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/fastclick/fastclick.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/sparkline/jquery.sparkline.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/plugins/knob/jquery.knob.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/dist/js/app.min.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/timeout.js"></script>
<script type="text/javascript" src="<?php echo Configure::read('cdn');?>/js/jquery.countdown.js"></script>
<?php
// echo $this->Html->script(array(
    // 'plugins/fastclick/fastclick.min',
    // 'plugins/sparkline/jquery.sparkline.min',
    // 'plugins/jvectormap/jquery-jvectormap-1.2.2.min',
    // 'plugins/jvectormap/jquery-jvectormap-world-mill-en',
    // 'plugins/slimScroll/jquery.slimscroll.min',
    // // 'dist/js/demo',
    // 'plugins/knob/jquery.knob',    
    // 'dist/js/app.min',
    // 'timeout',
    // 'jquery.countdown',    
    // 'plugins/store/dist/store.everything.min',
    // 'plugins/idleTimeout/jquery-idleTimeout.min'
// ));
// echo $this->fetch('script');
?>

<?php echo $this->element('sql_dump'); ?>

</body>
</html>

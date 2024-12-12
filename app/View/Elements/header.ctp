<header class="main-header">

    <!-- Logo -->
    <a href="<?php echo Router::url('/', true); ?>/users/dashboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>F</b>link</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Flink</b>ISO</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <?php if($this->Session->read('User')) echo $this->Element('top-menu');?>
    </nav>
  </header>

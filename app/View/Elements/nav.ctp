<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php
            if(file_exists(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->Session->read('User.employee_id') . DS . 'avatar.png')){
                  echo $this->Html->image($this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->Session->read('User.employee_id') . DS . 'avatar.png',array('class'=>'img-circle user-image'));
              }else{
                  echo $this->Html->image('dist/img/user2-160x160.jpg',array('class'=>'img-circle user-image'));                  
              }
          ?>          
        </div>
        <div class="pull-left info">
          <p><?php echo $this->Session->read('User.name'); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <!-- <li class="header">MAIN NAVIGATION</li> -->
        <!-- <li class="active treeview">
          <a href="#"> -->
            <li><?php echo $this->html->link('<i class="fa fa-dashboard"></i>  Dashboard',array('controller'=>'question_banks','action'=>'index'),array('escape'=>false)); ?></li>
          <!-- </a> -->
          <!-- <ul class="treeview-menu">
            <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
            <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
          </ul> -->
        <!-- </li> -->
        <li><?php echo $this->html->link('<i class="fa fa-dashboard"></i>  Training Videos',array('controller'=>'trainings','action'=>'index'),array('escape'=>false)); ?></li>
        <li><?php echo $this->html->link('<i class="fa fa-dashboard"></i>  Tests',array('controller'=>'tests','action'=>'index'),array('escape'=>false)); ?></li>
        <li><?php echo $this->html->link('<i class="fa fa-dashboard"></i>  Results',array('controller'=>'tests','action'=>'index'),array('escape'=>false)); ?></li>
        <li><?php echo $this->html->link('<i class="fa fa-dashboard"></i>  Certificates',array('controller'=>'tests','action'=>'index'),array('escape'=>false)); ?></li>
    </section>
    <!-- /.sidebar -->
  </aside>

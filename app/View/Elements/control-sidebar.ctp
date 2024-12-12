<!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-dashboard"></i></a></li>
      <li><a href="#control-sidebar-settings" data-toggle="tab"><i class="fa fa-gear"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-paper-plane-o"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <ul class="control-sidebar-menu">
          <li>
            <a href="<?php echo Router::url('/', true); ?>dashboards/mr">
              <i class="menu-icon fa fa-trophy bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Quality Management');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>dashboards/quality_control">
              <i class="menu-icon fa fa-dot-circle-o bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Quality Control');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>dashboards/hr">
              <i class="menu-icon fa fa-users bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Human Resource');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>dashboards/purchase">
              <i class="menu-icon fa fa-calculator bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Purchase');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>dashboards/bd">
              <i class="menu-icon fa fa-line-chart bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Sales & Marketing');?></h4>                
              </div>
            </a>
          </li>

          <li>
            <a href="<?php echo Router::url('/', true); ?>dashboards/personal_admin">
              <i class="menu-icon fa fa-cog bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Admin');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>dashboards/production">
              <i class="menu-icon fa fa-industry bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Productions');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>projects">
              <i class="menu-icon fa fa-gears bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Project Management');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>dashboards/env">
              <i class="menu-icon fa fa-leaf bg-green"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Environmental Safety');?></h4>
              </div>
            </a>
          </li>
        </ul>
      </div>
      <!-- /.tab-pane -->

      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <?php if($this->Session->read('User'))echo $this->element('customise'); ?>
      </div>
      <div class="tab-pane" id="control-sidebar-settings">
        <ul class="control-sidebar-menu">
          <li>
            <a href="<?php echo Router::url('/', true); ?>approvals">
              <i class="menu-icon fa fa-exclamation-triangle bg-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Approvals');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>auto_approvals">
              <i class="menu-icon fa fa-random bg-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Auto Approvals');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>system_tables">
              <i class="menu-icon fa fa-table bg-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('System Tables');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>users/smtp_details">
              <i class="menu-icon fa fa-gears bg-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Email Setup');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>email_triggers">
              <i class="menu-icon fa fa-send-o bg-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Email Triggers');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>users/password_setting">
              <i class="menu-icon fa fa-lock bg-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Password Policy');?></h4>                
              </div>
            </a>
          </li>

          <li>
            <a href="<?php echo Router::url('/', true); ?>users/two_way_authentication">
              <i class="menu-icon fa fa-wrench bg-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Two Factor Authetication');?></h4>                
              </div>
            </a>
          </li> 
          <li>
            <a href="<?php echo Router::url('/', true); ?>companies/view/<?php echo $this->Session->read('User.company_id');?>">
              <i class="menu-icon fa fa-wrench bg-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Company Settings');?></h4>                
              </div>
            </a>
          </li>
          <li>
            <a href="<?php echo Router::url('/', true); ?>companies/pdf_header">
              <i class="menu-icon fa fa-wrench bg-blue"></i>
              <div class="menu-info">
                <h4 class="control-sidebar-subheading"><?php echo __('Define Header');?></h4>                
              </div>
            </a>
          </li>          
        </ul>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

<?php 
  // $messages = $this->requestAction(array('controller'=>'Messages','action'=>'inbox_dashboard'));
  // $notifications = $this->requestAction(array('controller'=>'NotificationUsers','action'=>'display_notifications'));
    
?>
<div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"><?php echo count((array)$messages);?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo count((array)$messages);?> messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php foreach ($messages as $message) { ?>
                      <li><!-- start message -->
                      <a href="<?php echo Router::url('/', true); ?>messages/reply/<?php echo $message['MessageUserInbox']['message_id'];?>">
                        <h4>
                          <?php echo $message['Message']['subject']; ?>
                          <small><i class="fa fa-clock-o"></i> <?php echo $this->Time->timeAgoInWords($message['MessageUserInbox']['created']);?></small>
                        </h4>
                        <p><?php echo $message['CreatedBy']['name'] . '-(' . $message['from'][0]['Branch']['name'] . ' Branch)'; ?></p>
                      </a>
                    </li>
                    <!-- end message -->
                  <?php } ?>                  
                </ul>
              </li>
              <li class="footer"><?php echo $this->Html->link(__('See All Messages'),array('controller'=>'messages','action'=>'index'));?><a href="#"></a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-danger"><?php echo count((array)$notifications);?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo count((array)$notifications);?> notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php foreach ($notifications as $notification) { ?>
                    <li>
                    <a href="#">
                      <h4><?php echo $notification['Notification']['title'];?><br />
                      <small><?php echo $notification['Notification']['message'];?></small></h4>
                      <small><i class="fa fa-clock-o"></i> <?php echo $this->Time->timeAgoInWords($notification['Notification']['created']);?></small>
                    </a>
                  </li>
                  <?php } ?>                  
                </ul>
              </li>
              <li class="footer"><?php echo $this->Html->link(__('See All Notifications'),array('controller'=>'notifications'));?><a href="#"></a></li>
            </ul>
          </li>
          
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php $languages = $this->requestAction('App/language_details'); ?>
              <i class="fa fa-globe"></i><span class="label label-warning"><?php echo count((array)$languages);?></span>              
            </a>
            <ul class="dropdown-menu">
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php 
                  foreach ($languages as $language) { ?>
                  <li><?php echo $this->Html->link(__($language['Language']['name']), array('controller' => 'languages', 'action' => 'change_language', $language['Language']['short_code'])); ?></li>
                  <?php }?>               
                </ul>
              </li>              
            </ul>
          </li>
          
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <?php echo $this->Html->link('<i class="fa fa-book"></i>',array('controller'=>'helps','action'=>'help'),array('escape'=>false));?>
                            
            
            
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php
                if(file_exists(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->Session->read('User.employee_id') . DS . 'avatar.png')){
                      echo $this->Html->image($this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->Session->read('User.employee_id') . DS . 'avatar.png',array('class'=>'img-circle user-image'));
                  }else{
                      echo $this->Html->image('img/avatar.png',array('class'=>'img-circle user-image'));
                  }
              ?>
              
              <span class="hidden-xs"><?php echo $this->Session->read('User.name'); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <?php
                if(file_exists(WWW_ROOT . 'img' . DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->Session->read('User.employee_id') . DS . 'avatar.png')){
                      echo $this->Html->image($this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->Session->read('User.employee_id') . DS . 'avatar.png',array('class'=>'img-circle'));
                  }else{ ?>
                       <?php echo $this->Html->image('img/avatar.png',array('class'=>'img-circle'));?> 
                  <?php }
              ?>
                
                <p>
                  <?php echo $this->Session->read('User.name');?>
                  <small><?php echo $this->Session->read('User.branch');?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body hide">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <?php echo $this->Html->link('Change Password',array('controller'=>'users','action'=>'change_password'),array('class'=>'btn btn-default btn-flat')); ?>
                </div>
                <div class="pull-right">
                  <?php echo $this->Html->link('Sign Out',array('controller'=>'users','action'=>'logout'),array('class'=>'btn btn-default btn-flat')); ?>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>

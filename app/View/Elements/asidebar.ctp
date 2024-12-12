
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
                      echo $this->Html->image('img/avatar.png',array('class'=>'img-circle user-image'));
                  }
              ?>
        </div>
        <div class="pull-left info">
          <p><?php echo $this->Session->read('User.name');?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->      
      <ul class="sidebar-menu">

        <li class="header"><?php echo __('MAIN NAVIGATION');?></li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span><?php echo __('Dashboard');?></span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Dashboard'), array('controller' => 'users', 'action' => 'dashboard')); ?></li>
            <li><?php echo $this->Html->link(__('PM Dashboard'), array('controller' => 'users', 'action' => 'pm_dashboard')); ?></li>
            <li><?php echo $this->Html->link(__('Pending Tasks'), array('controller' => 'task_statuses', 'action' => 'index','taskstuatus'=>0)); ?></li>
            <li><?php echo $this->Html->link(__('Tasks Report'), array('controller' => 'tasks', 'action' => 'task_report')); ?></li>
            <li><?php echo $this->Html->link(__('All Tasks'), array('controller' => 'tasks', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Tasks Calendar'), array('controller' => 'tasks', 'action' => 'task_calendar')); ?></li>
            <li><?php echo $this->Html->link(__('Pending Actions'), array('controller' => 'users', 'action' => 'pending_tasks')); ?></li>
            <li><?php echo $this->Html->link(__('Audit Calendar'), array('controller' => 'dashboards', 'action' => 'audit_cal')); ?></li>
            
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-database"></i>
            <span><?php echo __('Project Masters'); ?> </span>            
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Milestone Types'), array('controller' => 'milestone_types', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Deliverabl Units'), array('controller' => 'deliverable_units', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Cost Categories'), array('controller' => 'cost_categories', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Query Types'), array('controller' => 'query_types', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Hold Types'), array('controller' => 'hold_types', 'action' => 'index')); ?></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-database"></i>
            <span><?php echo __('Masters'); ?> </span>            
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Divisions'), array('controller' => 'divisions', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Branches'), array('controller' => 'branches', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Departments'), array('controller' => 'departments', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Designations'), array('controller' => 'designations', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Org Chart'), array('controller' => 'employees', 'action' => 'org_chart')); ?></li>
            <li><?php echo $this->Html->link(__('Employees'), array('controller' => 'employees', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Update Employee Cost'), array('controller' => 'employees', 'action' => 'cost')); ?></li>
            <li><?php echo $this->Html->link(__('User Access'), array('controller' => 'user_access_controls', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Users'), array('controller' => 'users', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Management team'), array('controller' => 'users', 'action' => 'management_team')); ?></li>
            <li><?php echo $this->Html->link(__('Materials'), array('controller' => 'materials', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Services/Products'), array('controller' => 'products', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Asset Register'), array('controller' => 'devices', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Clients/Customers'), array('controller' => 'customers', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Qualifications'), array('controller' => 'educations', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Quality Documents Category'), array('controller' => 'master_list_of_format_categories', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Deliverabl Units'), array('controller' => 'deliverable_units', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Cost Categories'), array('controller' => 'cost_categories', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Query Types'), array('controller' => 'query_types', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Hold Types'), array('controller' => 'hold_types', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Holidays'), array('controller' => 'holidays', 'action' => 'index')); ?></li>
          </ul>
        </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-gears"></i>
            <span><?php echo __('Project Management'); ?> </span> <i class="fa fa-angle-left pull-right"></i>         
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Projects'), array('controller' => 'projects', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Milestones'), array('controller' => 'milestones', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Activities'), array('controller' => 'project_activities', 'action' => 'index')); ?></li>
            <!-- <li><?php echo $this->Html->link(__('Time Sheets'), array('controller' => 'project_timesheets', 'action' => 'project_timesheet_ajax')); ?></li> -->
            <li><?php echo $this->Html->link(__('All Members Board'), array('controller' => 'projects', 'action' => 'all_member_lock_board')); ?></li>
            <li><?php echo $this->Html->link(__('Project team board'), array('controller' => 'projects', 'action' => 'project_team_board')); ?></li>
            <li><?php echo $this->Html->link(__('Resigned Members'), array('controller' => 'employees', 'action' => 'resigned')); ?></li>
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-gears"></i>
            <span><?php echo __('MIS'); ?> </span> <i class="fa fa-angle-left pull-right"></i>         
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('MIS'), array('controller' => 'projects', 'action' => 'mis')); ?></li>
            <li><?php echo $this->Html->link(__('File Tracker'), array('controller' => 'projects', 'action' => 'tracker')); ?></li>
            <li><?php echo $this->Html->link(__('Daily Tracksheet'), array('controller' => 'file_processes', 'action' => 'daily_traclksheet')); ?></li>
            

            <!-- <li><?php echo $this->Html->link(__('User Logins'), array('controller' => 'user_sessions', 'action' => 'user_login_report')); ?></li> -->
            
            <!-- <li><?php echo $this->Html->link(__('Production / Quality log'), array('controller' => 'file_processes', 'action' => 'index')); ?></li> -->
            <!-- <li><?php echo $this->Html->link(__('Member\'s Engagement Board'), array('controller' => 'projects', 'action' => 'meb')); ?></li> -->
                <!-- <li><?php echo $this->Html->link(__('Employee Report 1'), array('controller' => 'projects', 'action' => 'employee_reports')); ?></li>
                <li><?php echo $this->Html->link(__('Milestones'), array('controller' => 'milestones', 'action' => 'index')); ?></li>
                <li><?php echo $this->Html->link(__('Activities'), array('controller' => 'project_activities', 'action' => 'index')); ?></li> -->
            
            <!-- <li><?php echo $this->Html->link(__('All Members Board'), array('controller' => 'projects', 'action' => 'all_member_lock_board')); ?></li> -->
            <!-- <li><?php echo $this->Html->link(__('Project team board'), array('controller' => 'projects', 'action' => 'project_team_board')); ?></li> -->
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span><?php echo __('Document Management'); ?> </span> <i class="fa fa-angle-left pull-right"></i>         
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Add New Standard'), array('controller' => 'standards', 'action' => 'lists')); ?></li>
            <li><?php echo $this->Html->link(__('Add New Clause'), array('controller' => 'clauses', 'action' => 'lists')); ?></li>
            <li><?php echo $this->Html->link(__('New Document Category'), array('controller' => 'master_list_of_format_categories', 'action' => 'lists')); ?></li>
            <li><?php echo $this->Html->link(__('Master List Of Formats'), array('controller' => 'master_list_of_formats','action'=>'index')); ?></li>
            <li><?php echo $this->Html->link(__('Available Quality Documents'), array('controller' => 'file_uploads', 'action' => 'quality_documents')); ?></li>
            <li><?php echo $this->Html->link(__('Add New Document'), array('controller' => 'master_list_of_formats', 'action' => 'lists')); ?></li>                                              
          <?php if($this->Session->read('User.is_mr') == 1 or $this->Session->read('User.is_mr') == true) {?>
              <li><?php echo $this->Html->link(__('All Documents'), array('controller' => 'file_uploads','action'=>'index')); ?></li>
              <!-- <li><?php echo $this->Html->link(__('Master List Of Formats'), array('controller' => 'master_list_of_formats','action'=>'index')); ?></li> -->
              <li><?php echo $this->Html->link(__('Add Change Request'), array('controller' => 'change_addition_deletion_requests', 'action' => 'index')); ?></li>
              <li><?php echo $this->Html->link(__('Easy Document Search'), array('controller' => 'file_uploads', 'action' => 'index')); ?></li>
              <li><?php echo $this->Html->link(__('Advance Search'), array('controller' => 'file_uploads', 'action' => 'file_advanced_search')); ?></li>
              <li><?php echo $this->Html->link(__('Quality Documents'), array('controller' => 'file_uploads', 'action' => 'quality_documents')); ?></li>
              <li><?php echo $this->Html->link(__('Standardwise Documents <span class="label label-primary pull-right">beta</span>'), array('controller' => 'clauses', 'action' => 'standards'),array('escape'=>false)); ?></li>
          <?php }?>
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-anchor"></i>
            <span><?php echo __('<span class="label label-primary pull-right">beta</span>Context of organization'); ?> </span> <i class="fa fa-angle-left pull-right"></i>         
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('KRAs'), array('controller' => 'employee_kras', 'action' => 'kras')); ?></li>
            <li><?php echo $this->Html->link(__('kpis'), array('controller' => 'list_of_kpis', 'action' => 'lists')); ?></li>
            <li><?php echo $this->Html->link(__('List of activities'), array('controller' => 'list_of_issues', 'action' => 'lists')); ?></li>
            <li><?php echo $this->Html->link(__('Issues'), array('controller' => 'list_of_issues', 'action' => 'lists')); ?></li>
            <li><?php echo $this->Html->link(__('Risks'), array('controller' => 'risks', 'action' => 'lists')); ?></li>
            <li><?php echo $this->Html->link(__('Objectives'), array('controller' => 'objectives', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Objective Monitoring'), array('controller' => 'objective_monitorings', 'action' => 'index')); ?></li>
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-trophy"></i>
            <span><?php echo __('Quality Management'); ?> </span> <i class="fa fa-angle-left pull-right"></i>         
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Audits'), array('controller' => 'internal_audit_plans', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('MRM'), array('controller' => 'meetings', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('External Meetings'), array('controller' => 'meetings', 'action' => 'external_index')); ?></li>
            <li><?php echo $this->Html->link(__('CAPA'), array('controller' => 'corrective_preventive_actions', 'action' => 'index')); ?></li>
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-dot-circle-o"></i>
            <span><?php echo __('Quality Control'); ?> </span> <i class="fa fa-angle-left pull-right"></i>          
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('NCs'), array('controller' => 'non_conforming_products_materials', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Customer Complaints'), array('controller' => 'customer_complaints', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Customer Feedbacks'), array('controller' => 'customer_feedbacks', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Calibrations'), array('controller' => 'calibrations', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Material QC'), array('controller' => 'material_quality_checks', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Preventive Mainteinence'), array('controller' => 'device_maintenances', 'action' => 'index')); ?></li>            
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-users"></i>
            <span><?php echo __('Human Resources'); ?> </span> <i class="fa fa-angle-left pull-right"></i>         
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Employee Master'), array('controller' => 'employees', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Courses'), array('controller' => 'courses', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('TNIs'), array('controller' => 'training_need_identifications', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Trainings'), array('controller' => 'trainings', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Training Evaluations'), array('controller' => 'training_evaluations', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Competency Mapping'), array('controller' => 'competency_mappings', 'action' => 'index')); ?></li>            
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-calculator"></i>
            <span><?php echo __('Purchase'); ?> </span> <i class="fa fa-angle-left pull-right"></i>          
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Suppliers'), array('controller' => 'supplier_registrations', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Purchase Orders'), array('controller' => 'purchase_orders', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Delivery'), array('controller' => 'delivery_challans', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Supplier Evaluation Template'), array('controller' => 'supplier_evaluation_templates')); ?></li>
            <li><?php echo $this->Html->link(__('Supplier Evaluation'), array('controller' => 'supplier_evaluation_reevaluations')); ?></li>
            <li><?php echo $this->Html->link(__('Acceptable Suppliers'), array('controller' => 'list_of_acceptable_suppliers', 'action' => 'index')); ?></li>            
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-line-chart"></i>
            <span><?php echo __('Sales & Marketing'); ?> </span> <i class="fa fa-angle-left pull-right"></i>         
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Summary'), array('controller' => 'dashboards', 'action' => 'bd')); ?></li>
            <li><?php echo $this->Html->link(__('Customers'), array('controller' => 'customers', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Proposals'), array('controller' => 'proposals', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Followups'), array('controller' => 'proposal_followups', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Meetings'), array('controller' => 'customer_meetings', 'action' => 'index')); ?></li>            
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-cog"></i>
            <span><?php echo __('Admin'); ?> </span> <i class="fa fa-angle-left pull-right"></i>        
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Checklist'), array('controller' => 'housekeeping_checklists', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Housekeeping Responsibilities'), array('controller' => 'housekeeping_responsibilities', 'action' => 'index')); ?></li>            
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-industry"></i>
            <span><?php echo __('Production'); ?> </span> <i class="fa fa-angle-left pull-right"></i>          
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Materials'), array('controller' => 'materials', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Products'), array('controller' => 'products', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Design'), array('controller' => 'designs', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Production Plan'), array('controller' => 'production_weekly_plans', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Production Batch'), array('controller' => 'productions', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Inspection Template'), array('controller' => 'production_inspection_templates', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Add Stock'), array('controller' => 'stocks', 'action' => 'index',0)); ?></li> 
            <li><?php echo $this->Html->link(__('Stock Status'), array('controller' => 'stocks', 'action' => 'stock_status')); ?></li> 
            <li><?php echo $this->Html->link(__('Production Report'), array('controller' => 'productions', 'action' => 'data_backup')); ?></li>
          </ul>
      </li>
      
      <li class="treeview">
          <a href="#">
            <i class="fa fa-bullseye"></i>
            <span><?php echo __('FMEA'); ?> </span> <i class="fa fa-angle-left pull-right"></i>          
          </a>
          <ul class="treeview-menu">            
              <li><?php echo $this->Html->link(__('FMEA'), array('controller' => 'fmeas', 'action' => 'index')); ?></li>
              <!-- <li><?php echo $this->Html->link(__('Design'), array('controller' => 'designs', 'action' => 'index')); ?></li> -->
              <li><?php echo $this->Html->link(__('Severity Types'), array('controller' => 'fmea_severity_types', 'action' => 'index')); ?></li>
              <li><?php echo $this->Html->link(__('Occurences'), array('controller' => 'fmea_occurences', 'action' => 'index')); ?></li>
              <li><?php echo $this->Html->link(__('Detections'), array('controller' => 'fmea_detections', 'action' => 'index')); ?></li>          
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-fire"></i>
            <span><?php echo __('Risk Management'); ?> </span> <i class="fa fa-angle-left pull-right"></i>          
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Assessments'), array('controller' => 'risk_assessments', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Incident Management'), array('controller' => 'incidents', 'action' => 'index')); ?></li>            
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-leaf"></i>
            <span><?php echo __('Environmental Safety'); ?> </span> <i class="fa fa-angle-left pull-right"></i>           
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Checklist'), array('controller' => 'environment_checklists', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Evaluation Criteria'), array('controller' => 'evaluation_criterias', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Actvities'), array('controller' => 'env_activities', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Identification'), array('controller' => 'env_identifications', 'action' => 'index')); ?></li>
            <li><?php echo $this->Html->link(__('Evaluation'), array('controller' => 'env_evaluations', 'action' => 'index')); ?></li>
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-bullseye"></i>
            <span><?php echo __('Objective Monitoring'); ?> </span> <i class="fa fa-angle-left pull-right"></i>          
          </a>
          <ul class="treeview-menu">            
              <li><?php echo $this->Html->link(__('Objectives'), array('controller' => 'objectives', 'action' => 'index')); ?></li>
              <li><?php echo $this->Html->link(__('Processes'), array('controller' => 'processes', 'action' => 'index')); ?></li>
              <li><?php echo $this->Html->link(__('Objective Monitoring'), array('controller' => 'objective_monitorings', 'action' => 'index')); ?></li>
              <li><?php echo $this->Html->link(__('Objective Monitoring Chart'), array('controller' => 'objective_monitorings', 'action' => 'objective_monitoring_chart')); ?></li>
          </ul>
      </li>
      <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span><?php echo __('Reports'); ?> </span> <i class="fa fa-angle-left pull-right"></i>           
          </a>
          <ul class="treeview-menu">
            <li><?php echo $this->Html->link(__('Summary'), array('controller' => 'reports', 'action' => 'report_summery')); ?></li>
            <li><?php echo $this->Html->link(__('Employee Compliance'), array('controller' => 'reports', 'action' => 'compliance')); ?></li>
            <li><?php echo $this->Html->link(__('Opportunities For Improvement'), array('controller' => 'internal_audits', 'action' => 'opportunities_for_improvement')); ?></li>
            <li><?php echo $this->Html->link(__('Auto Reports'), array('controller' => 'reports', 'action' => 'report_center')); ?></li>
            <li><?php echo $this->Html->link(__('Saved Reports'), array('controller' => 'reports', 'action' => 'saved_reports')); ?></li>            
          </ul>
      </li>
        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

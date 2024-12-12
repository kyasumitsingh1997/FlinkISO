<div  id="main">
    <?php echo $this->Session->flash();?>
    <h2><?php echo __('Task Status');?></h2>
    <div class="tasks row ">        
        <div class="col-md-8">
            <?php                
                        if($this->data['Task']['task_status'] == 0)$status = 0;
                        elseif($this->data['Task']['task_status'] == 1)$status = 1;
                        else $status = 0;

                        if($this->request->data['Task']['user_id'] != 0 && $this->request->data['Task']['user_id'] != -1)$user = $this->request->data['Task']['user_id'];
                        else $user = '0';

                        if($this->request->data['Task']['process_id'])$processid = $this->request->data['Task']['process_id'];
                        else $processid = '0';
                            
                        if($this->request->data['Task']['project_activity_id'])$projectactivityid = $this->request->data['Task']['project_activity_id'];
                        else $projectactivityid = '0';
                        
                        // else $status = 1;
                        if($this->request->data['Task']['from_date'])$from_date = $this->request->data['Task']['from_date'];
                        else $from_date = '0';
                        
                        if($this->request->data['Task']['to_date'])$to_date = $this->request->data['Task']['to_date'];
                        else $to_date = '0';
                        // echo ">>>" . $user;
                            
                    ?>
            <div id="pending-task-tabs" class="nav-tabs-info">
                <ul class="nav nav-tabs">
                    
                            <li><?php 
                            if($all_tasks > 0)$all_tasks_class = 'default';  
                            else $all_tasks_class = 'default';

                            echo $this->Html->link(__('Tasks <span class="label label-'.$all_tasks_class.'">'.$all_tasks.'</span>'), array(
                                    'action' => 'index',0,
                                    'taskstuatus'=>$status,
                                    'users'=>$user,
                                    'fromdate'=>$from_date,
                                    'todate'=>$to_date,
                                    'processid'=>0,
                                    'projectactivityid' => 0
                                    ),array('escape'=>false)
                                ); ?>
                        </li>                        
                        <li>
                            <?php 
                            if($process_tasks > 0)$all_process_tasks = 'default';  
                            else $all_process_tasks = 'default';
                            echo $this->Html->link(__('Process Tasks <span class="label label-'.$all_process_tasks.'">'.$process_tasks.'</span>'), array(
                                    'action' => 'index',1,
                                    'taskstuatus'=>$status,
                                    'users'=>$user,
                                    'fromdate'=>$from_date,
                                    'todate'=>$to_date,
                                    'processid'=>$processid,
                                    'projectactivityid' => 0
                                    ),array('escape'=>false)
                                ); ?>
                        </li>                        
                        <li>
                            <?php 
                            if($project_tasks > 0)$all_project_tasks = 'default';  
                            else $all_project_tasks = 'default';
                            echo $this->Html->link(__('Project Tasks <span class="label label-'.$all_project_tasks.'">'.$project_tasks.'</span>'), array(
                                    'action' => 'index',2,
                                    'taskstuatus'=>$status,
                                    'users'=>$user,
                                    'fromdate'=>$from_date,
                                    'todate'=>$to_date,
                                    'processid'=>0,
                                    'projectactivityid' => $projectactivityid
                                    ),array('escape'=>false)
                                ); ?>
                            </li>                        
                     
                    <li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator', 'class' => 'pull-right')); ?></li>
                </ul>
            </div>            
        </div>
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><strong>Search</strong></h3><i class="fa fa-search pull-right"></i>
                  </div>
                <div class="box-body">
                    <div class="row">
                    <?php                 
                        echo $this->Form->create('Task');
                        echo "<div class='col-md-12'>".$this->Form->input('task_status',array('type'=>'radio', 
                                'options'=>array('On going','Completed'), 
                                'default'=>0,
                                'label'=>__('Task Type : Project related or general')))."</div>";
                        echo "<div class='col-md-6'>".$this->Form->input('from_date', array('lebel'=>'Task start date'))."</div>";
                        echo "<div class='col-md-6'>".$this->Form->input('to_date', array('lebel'=>'Task end date'))."</div>";
                        echo "<div class='col-md-12'>".$this->Form->input('user_id', array('lebel'=>'Assigned To'))."</div>";
                        // echo "<div class='col-md-12'>".$this->Form->input('process_id', array('lebel'=>'Assigned To'))."</div>";
                        // echo "<div class='col-md-12'>".$this->Form->input('project_activity_id', array('lebel'=>'Assigned To'))."</div>";
                    ?>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <?php 
                        echo $this->Form->submit('Filter', array('class' => 'btn btn-primary btn-success', 'update' => '#tasks_ajax', 'async' => 'false','id'=>'submit_id','escape'=>false));
                        // echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator'));
                        echo $this->Form->end();
                        echo $this->Js->writeBuffer(); 
                    ?>                        
                </div>
            </div>
        </div>
        <div id="tasks_tab_ajax"></div>
    </div>

<script>
    $(function() {
        $("#pending-task-tabs").tabs({
            beforeLoad: function(event, ui) {
                ui.jqXHR.error(function() {
                    ui.panel.html(
                            "Error Loading ... " +
                            "Please contact administrator.");
                });
            }
        });
    });
</script>
</div>

<script>
    $.ajaxSetup({
        beforeSend: function () {
            $("#busy-indicator").show();
        },
        complete: function () {
            $("#busy-indicator").hide();
        }
    });
    $().ready(function(){
        $("#TaskUserId").chosen();
        $("#TaskProcessId").chosen();
        $("#TaskProjectActivityId").chosen();
        $("#TaskFromDate").datepicker({
              changeMonth: true,
              changeYear: true,
              format: 'yyyy-mm-dd',
              autoclose:true,
              todayHighlightTaskFromDate:true,          
            });
          $("#TaskToDate").datepicker({
              changeMonth: true,
              changeYear: true,
              format: 'yyyy-mm-dd',
              autoclose:true,    
              todayHighlight:true,      
            });

        $("#TaskToDate").change(function(){
            if(new Date($("#TaskFromDate").val()) > new Date($("#TaskToDate").val())){
                alert('End date should be greater than start date');
                $("#TaskToDate").val('');
            }
        });
    });
</script>

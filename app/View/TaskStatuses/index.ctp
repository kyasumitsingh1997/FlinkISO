<div  id="main">
    <?php echo $this->Session->flash(); ?>
    <div class="taskStatuses" id="task-report">
        <div class="row">
            <div class="col-md-12">
                <h3><?php echo __('Tasks Status');?></h3>
                <br />
            </div>
            <div class="col-md-12">
                <?php 
                if($results){
                foreach ($results as $result) { ?>
                <div class="row tasks-progress">
                    <div class="col-md-6">
                        <div class="progress-group">
                            <span class="progress-text">
                                <small class="label label-default"><?php echo $schedules[$result['Task']['schedule_id']];?></small> <strong><?php echo $result['Task']['name'];?></strong>
                            </span>
                            <?php 
                                    $completion = $this->requestAction('tasks/view/'.$result['Task']['id']);
                                    $total_tasks = $performed = 0;
                                    foreach ($completion[1] as $date => $task_status): 
                                        $total_tasks = $total_tasks + 1;
                                        if (isset($task_status['TaskStatus']['task_performed']) && $task_status['TaskStatus']['task_performed'] == 1){
                                            $performed = $performed + 1;
                                        }
                                    endforeach;
                                    $completion = round($performed/$total_tasks*100);                                    
                                    ?>
                                <?php
                                    if($result['Task']['task_status'] == 1 ){
                                        $cclass = ' progress-bar-danger';
                                        $result['Task']['task_completion'] = 100;
                                        $completion = 100;
                                    }
                                    if($result['Task']['task_completion'] <= 100)$class = ' progress-bar-success';
                                    if($result['Task']['task_completion'] <= 80 )$class = ' progress-bar-aqua';
                                    if($result['Task']['task_completion'] <= 60 )$class = ' progress-bar-yellow';
                                    if($result['Task']['task_completion'] <= 40)$class = ' progress-bar-red';
                                ?>
                                 &nbsp;<span class='label label-info pull-right'><?php echo $completion;?>%</span>&nbsp;
                                <?php // echo $task['Task']['task_completion'];?>
                                    <div class="progress-group">
                                        <div class="progress xs">                                            
                                            <div style="width: <?php echo $completion;?>%" class="progress-bar <?php echo $class;?>"></div>
                                            <div style="width: <?php echo 100 - $completion;?>%" class="progress-bar progress-bar-warning"></div>
                                        </div>
                                    </div>                            
                        </div>
                    </div>
                    <div class="col-md-6">
                        Created By : <small><?php echo $userNames[$result['Task']['created_by']];?> </small><br />
                        Assigned To : <small><?php echo $userNames[$result['Task']['user_id']];?> </small><br />
                        <small>From : <?php echo date('d M y',strtotime($result['Task']['start_date']));?> To: <?php echo date('d M y',strtotime($result['Task']['end_date']));?> </small>
                        <?php 
                            echo $this->Html->link('view',array('controller'=>'tasks','action'=>'view',$result['Task']['id']),array('class'=>'btn btn-xs btn-default','target'=>'_blank'));
                        ?>
                    </div>  
                </div>                  
                <?php }
                }else{ ?>
                    No Tasks to display.
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>

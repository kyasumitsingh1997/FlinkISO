<?php
    if (count($tasks)) {
        $i = 0;
        echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min','chosen.min'));
        echo $this->fetch('script');        
?>
<script type="text/javascript">
$().ready(function(){
setTimeout(fade_out, 10000);
    function fade_out() {
    $("#flashMessageCustom").fadeOut();
    }

    $("#process_task_submit").bind('click',function (event){
        
        <?php foreach ($tasks as $key => $task) { 
        if($task['Task']['user_id'] == $this->Session->read('User.id')){ ?>
            var st = 0;
            $('.<?php echo $task["Task"]["id"];?>_task_performed').each(function() {
                if (!$("input[name='"+ this.name + "']:checked").val()) {
                    
                    st = 1;
                }                
            });            
            if(st == 1){
                st = 0;
                alert('Add task performed for <?php echo $task["Task"]["name"];?> task');
                event.preventDefault();
                // return false;
            }
        <?php 
        } 
    } ?>    
                
});

//    $.validator.setDefaults({submitHandler: function(form) {
//            $(form).ajaxSubmit({
//                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/task",
//                type: 'POST',
//                target: '#task_main',
//                error: function(request, status, error) {
//                    alert(request.responseText);
//                }
//            });
//        }
//    });

   $(".cs").chosen(); 
});
</script>
<style type="text/css">
    /*.cs >chosen-container{width: 100% !important;}*/
</style>
<div id = "process_task_ajax_1">
    <div id="process_task_ajax">
        <div id="flashMessageCustom">
           <?php echo $this->Session->flash(); ?>
        </div>
        <?php echo $this->Form->create('TaskStatus', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            <!-- <h3 class="panel-title">
                <?php echo $this->Html->link(__('Project Tasks Assigned To You'), array('controller' => 'tasks', 'action' => 'index')); ?>
                <span class='badge btn-danger'><?php if(isset($count)) echo $count; ?></span>
            </h3> -->
            <table class="table table-condensed checklists">
                <tr>
                    <th></th>
                    <th width="25%"><?php echo __('Tasks'); ?></th>
                    <th><?php echo __('Assigned To'); ?></th>
                    <th><?php echo __('Performed?'); ?></th>
                    <th><?php echo __('Status'); ?></th>
                    <th><?php echo __('Comments'); ?></th>
                    <th><?php echo __('Edit'); ?></th>
                    <th width="75"><?php echo $this->Html->link('View All', array('controller' => 'tasks','action'=>'index'), array('class' => 'pull-right btn btn-xs btn-info')); ?>
                    </th>
                </tr>
                <?php foreach ($tasks as $key => $task) {
                    if ($task['TaskStatus'] && $task['TaskStatus']['task_performed'] == 1) {
                ?>
                <tr class="text-success">
                    <?php } else { ?>
                <tr class="text-danger">
                <?php } ?>
                    <td>&nbsp;
                        <?php 
                            // if($task['Task']['rag_status'] == 0)$rag_class = 'danger';
                            // elseif($task['Task']['rag_status'] == 1)$rag_class = 'warning';
                            // elseif($task['Task']['rag_status'] == 2)$rag_class = 'success';
                            // else $rag_class = 'default';
                        if(isset($task['Task']['revised_due_date'])){
                            if(($task['Task']['task_status'] == 0 && $task['Task']['revised_due_date'] < date('Y-m-d')) || $task['Task']['rag_status'] == 0)$rag_class = 'danger';
                            elseif(($task['Task']['task_status'] == 0 && $task['Task']['revised_due_date'] > date('Y-m-d')) || $task['Task']['rag_status'] == 1) $rag_class = 'warning';
                            // elseif($task['Task']['task_status'] == 1 && $task['Task']['end_date'] < date('Y-m-d')) $rag_class = 'success';
                            elseif($task['Task']['task_status'] == 2)$rag_class = 'default';
                            else $rag_class = 'success';   
                        }else{
                            if(($task['Task']['task_status'] == 0 && $task['Task']['end_date'] < date('Y-m-d')) || $task['Task']['rag_status'] == 0) $rag_class = 'danger';
                            elseif(($task['Task']['task_status'] == 0 && $task['Task']['end_date'] > date('Y-m-d')) || $task['Task']['rag_status'] == 1)$rag_class = 'warning';
                            // elseif($task['Task']['task_status'] == 1 && $task['Task']['end_date'] < date('Y-m-d')) $rag_class = 'success';
                            else $rag_class = 'success';   
                        }
                            
                        ?>
                        <span class="label label-<?php echo $rag_class;?>"><?php echo $task['Schedule']['name']; ?></span> </td>
                    <td>
                        <?php echo $this->Html->link($task['Task']['name'], array('controller' => 'tasks', 'action' => 'view', $task['Task']['id'])); ?> <br /><small><?php echo $task['ProjectActivity']['title']; ?></small>
                    </td>
                    <td><?php echo $task['User']['name']; ?></td>
                    <!-- <td>
                        <?php
                            $task['TaskStatus']['rag_status'] = isset($task['TaskStatus']['rag_status'])? $task['TaskStatus']['rag_status'] : '';
                            $editId = isset($editId)? $editId : '';
                            if ($task['TaskStatus']['rag_status'] > 0 && ($task['TaskStatus']['id'] != $editId)) {
                                echo $task['TaskStatus']['rag_status'] == 0 ? '<td><i class="fa fa-check-square-o" aria-hidden="true"></i>' : '<td><i class="fa fa-minus-square-o" aria-hidden="true"></i></td>';
                                echo $task['TaskStatus']['rag_status'] == 1 ? '<td><i class="fa fa-check-square-o" aria-hidden="true"></i>' : '<td><i class="fa fa-minus-square-o" aria-hidden="true"></i></td>';
                                echo $task['TaskStatus']['rag_status'] == 2 ? '<td><i class="fa fa-check-square-o" aria-hidden="true"></i>' : '<td><i class="fa fa-minus-square-o" aria-hidden="true"></i></td>';
                                echo "<td></td>";
                            } else {
                                $i = 1;
                                echo "<td>".$this->Form->input('Task.' . $key . '.rag_status', array('label' => '', 'legend' => false, 'div' => false, 'options' => array('0' => 'Red', '1' => 'Amber','2'=>'Green'), 'type' => 'radio', 'style' => 'float:none', 'value' => $task['Task']['rag_status']))."</td>";

                                // echo "<td>".$this->Form->input('TaskStatus.' . $key . '.Task.rag_status', array('label' => '', 'legend' => false, 'div' => false, 'options' => array('0' => 'On going', '1' => 'Completed'), 'type' => 'radio', 'style' => 'float:none', 'value' => $task['Task']['task_status']));
                                // echo "<br /><small>";
                            }
                        ?>
                    </td> -->
                    
                    <!-- <td> -->
                            <?php
                                $task['TaskStatus']['task_performed'] = isset($task['TaskStatus']['task_performed'])? $task['TaskStatus']['task_performed'] : '';
                                $editId = isset($editId)? $editId : '';
                                if ($task['TaskStatus']['task_performed'] > 0 && ($task['TaskStatus']['id'] != $editId)) {
                                    echo $task['TaskStatus']['task_performed'] == 1 ? '<td><i class="fa fa-check-square-o" aria-hidden="true"></i>' : '<td><i class="fa fa-minus-square-o" aria-hidden="true"></i></td>';
                                    if($task['Task']['revised_due_date'])echo "<td>On going<br /><small>Revised Due` Date : " . $task['Task']['revised_due_date'] . "</small></td>";
                                    else echo "<td>On going<br /><small>End Date : " . $task['Task']['end_date'] . "</small></td>";
                                
                                } else {
                                    $i = 1;
                                    echo "<td>".$this->Form->input('TaskStatus.' . $key . '.task_performed', array('label' => '', 'legend' => false, 'div' => false, 'options' => array('1' => 'Yes', '2' => 'No'), 'type' => 'radio', 'class'=>$task['Task']['id'].'_task_performed', 'style' => 'float:none', 'value' => $task['TaskStatus']['task_performed']))."</td>";

                                    echo "<td>".$this->Form->input('TaskStatus.' . $key . '.Task.task_status', array(
                                        'label' => false,
                                        // 'div'=>array('class'=>'cs'),
                                        'class'=>$task['Task']['id'].'_task_status cs', 
                                        'style'=>array('width'=>'100%'),
                                        'options' => array('0' => 'On going', '1' => 'Completed','2'=>'Not Started','3'=>'Cancelled'), 
                                        'value' => $task['Task']['task_status']));
                                    if($task['Task']['revised_due_date'] && $task['Task']['revised_due_date'] != '1970-01-01')echo "<td>On going<br /><small>Revised Due Date : " . $task['Task']['revised_due_date'] . "</small></td>";
                                    else echo "<td>On going<br /><small>End Date : " . $task['Task']['end_date'] . "</small></td>";
                                }
                            ?>
                    <!-- </td> -->
                        <!-- <td><?php 
                            // echo $this->Form->input('TaskStatus.' . $key . '.Task.task_status', array('label' => '', 'legend' => false, 'div' => false, 'options' => array('0' => 'On going', '1' => 'Completed'), 'type' => 'radio', 'style' => 'float:none', 'value' => $task['Task']['task_status']));
                            // echo "<br /><small>End Date : " . $task['Task']['end_date'] . "</small>";
                                
                        ?></td> -->
                    <td><?php
                                $task['TaskStatus']['id'] = isset($task['TaskStatus']['id'])? $task['TaskStatus']['id'] : '';
                                $task['TaskStatus']['comments'] = isset($task['TaskStatus']['comments'])? $task['TaskStatus']['comments'] : '';

                                echo $this->Form->input('TaskStatus.' . $key . '.id', array('type' => 'hidden', 'value' => $task['TaskStatus']['id']));
                                if ($task['TaskStatus']['comments'] && ($task['TaskStatus']['id'] != $editId)) {
                                    echo $task['TaskStatus']['comments'];
                                } else {
                                    echo $this->Form->input('TaskStatus.' . $key . '.comments', array('label' => false, 'style' => 'height: 30px', 'value' => $task['TaskStatus']['comments']));
                                }
                                echo $this->Form->input('TaskStatus.' . $key . '.task_id', array('style' => 'width:100%', 'type' => 'hidden', 'value' => $task['Task']['id']));
                                echo $this->Form->input('TaskStatus.' . $key . '.branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                                echo $this->Form->input('TaskStatus.' . $key . '.departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                                echo $this->Form->input('TaskStatus.' . $key . '.created_by', array('type' => 'hidden', 'value' => $this->Session->read('User.id')));
                                echo $this->Form->input('TaskStatus.' . $key . '.modified_by', array('type' => 'hidden', 'value' => $this->Session->read('User.id')));
                            ?>
                    </td>
                    <td><?php
                                if ($task['TaskStatus']['id']){
                                    echo $this->Js->link('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', 
                                        array('controller' => 'tasks', 'action' => 'get_process_task', $task['TaskStatus']['id']), 
                                        array('escape' => false, 'update' => '#process_task_ajax', 'async' => 'false'))
                            ?>
                    </td>
                    <td>
                            <div class="btn-group  pull-right">
                                <div id="process_taskcountdiv<?php echo $task['TaskStatus']['id'];?>" class="btn-xs btn btn-primary"></div>                                            
                                <?php echo $this->Html->link('Files','#',array('id'=>'process_taskfilesbtn'.$task['TaskStatus']['id'],'class'=>'btn btn-xs btn-info')); ?>    
                            </div>
                    
                    
                            <div id="process_taskfilesdiv<?php echo $task['TaskStatus']['id'];?>"></div>                           
                            <script>
                                
                                    $('#process_taskfilesbtn<?php echo $task["TaskStatus"]["id"];?>').click(function(){
                                        $('#process_taskfilesdiv<?php echo $task["TaskStatus"]["id"];?>').load("<?php echo Router::url('/', true); ?>tasks/process_task_ajax/<?php echo $task['TaskStatus']['id'] ?>/<?php echo $task['TaskStatus']['id']; ?>", function(response, status, xhr){});
                                });
                            </script>
                            <script>
                                    $().ready(function(){$('#process_taskcountdiv<?php echo $task['TaskStatus']['id'];?>').load('<?php echo Router::url('/', true); ?>tasks/process_task_ajax_file_count/<?php echo $task['TaskStatus']['id'] ?>', function(response, status, xhr){});});
                                    </script>
                            <?php echo $this->Js->writeBuffer(); ?>
                            <?php } ?>
                    </td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan='7'>
                            <?php
                                if ($i == 1)
                                    echo $this->Js->submit(__('Submit'), array('url' => array('controller' => 'tasks', 'action' => 'get_process_task'), 'div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#process_task_ajax_1', 'async' => 'false','id'=>'process_task_submit'));
                            ?>
                    </td>
                </tr>
            </table>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>
           
    </div>
<?php } else{ ?>
    <div id="process_tasks_ajax" style="padding:10px">
          
          <h3 class="panel-title"><?php echo  __("Tasks Assigned To You"); ?>
         <span class='badge btn-danger'><?php if(isset($count)) echo $count; ?></span>
          </h3>
                    
   No data Found

    </div>
   <?php
} ?>
</div>
<?php
    echo $this->Html->script(array(
        'plugins/jQuery/jQuery-2.2.0.min',
        'plugins/jQueryUI/jquery-ui.min',
        'js/bootstrap.min',
        'dist/js/demo',
        'dist/js/app.min',
        'chosen.min'
        ));    
    echo $this->fetch('script');
    // echo $this->Html->css(array('flinkiso'));
    // echo $this->fetch('css');
?>
<script>
$().ready(function() {
    setTimeout(fade_out, 10000);
    function fade_out() {
    $("#flashMessageCustom").fadeOut();
    }
});
</script>

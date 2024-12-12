<?php
if($tasks){
    if (count($tasks)) {
        $i = 0;
        echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min'));
        echo $this->fetch('script');
?>
<script>
$().ready(function() {
    setTimeout(fade_out, 10000);
    function fade_out() {
	$("#flashMessageCustom").fadeOut();
    }

    $("#task_submit").bind('click',function (event){
        
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
</script>
<div id="tasks_ajax_main">
    <div id="flashMessageCustom">
	   <?php echo $this->Session->flash(); ?>
    </div>
    <?php echo $this->Form->create('TaskStatus', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
    <table class="table table-condensed checklists">
        <tr>
            <th></th>
            <th><?php echo __('Tasks'); ?></th>
            <th><?php echo __('Assigned To'); ?></th>
            <th><?php echo __('Task Performed?'); ?></th>
            <th><?php echo __('Status'); ?></th>
            <th><?php echo __('Comments'); ?></th>
            <th><?php echo __('Action'); ?></th>
        </tr>
        <?php foreach ($tasks as $key => $task) {
            if ($task['TaskStatus'] && $task['TaskStatus']['task_performed'] == 1) {
        ?>
            <tr class="text-success">
        <?php } else { ?>
            <tr class="text-danger">
        <?php } ?>
            <td><span class="label label-default"><?php echo $task['Schedule']['name']; ?></span> &nbsp;</td>
            <td><?php echo $this->Html->link($task['Task']['name'], array('controller' => 'tasks', 'action' => 'view', $task['Task']['id'])); ?></td>
            <td><?php echo $task['User']['name']; ?></td>
            <!-- <td> -->
            <?php
                $task['TaskStatus']['task_performed'] = isset($task['TaskStatus']['task_performed'])? $task['TaskStatus']['task_performed'] : '';
                $editId = isset($editId)? $editId : '';
                if ($task['TaskStatus']['task_performed'] > 0 && ($task['TaskStatus']['id'] != $editId)) {
                    echo $task['TaskStatus']['task_performed'] == 1 ? '<td><i class="fa fa-check-square-o" aria-hidden="true"></i>' : '<td><i class="fa fa-minus-square-o" aria-hidden="true"></i></td>';
                    echo "<td>On going<br /><small>End Date : " . $task['Task']['end_date'] . "</small></td>";
                } else {
                    $i = 1;
                    echo "<td>".$this->Form->input('TaskStatus.' . $key . '.task_performed', array('label' => '', 'legend' => false, 'div' => false, 'options' => array('1' => 'Yes', '2' => 'No'), 'type' => 'radio', 'class'=>$task['Task']['id'].'_task_performed',  'style' => 'float:none', 'value' => $task['TaskStatus']['task_performed']))."</td>";

                    echo "<td>".$this->Form->input('TaskStatus.' . $key . '.task_status', array('label' => '', 'legend' => false, 'div' => false, 'options' => array('0' => 'On going', '1' => 'Completed'), 'type' => 'radio', 'class'=>$task['Task']['id'].'_task_status', 'style' => 'float:none', 'value' => $task['Task']['task_status']));
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
                                    array('controller' => 'tasks', 'action' => 'get_task', $task['TaskStatus']['id']), 
                                    array('escape' => false, 'update' => '#tasks_ajax_main', 'async' => 'false'))?>
                    <div class="btn-group  pull-right">
                        <div id="taskcountdiv_main<?php echo $task['TaskStatus']['id'];?>" class="btn-xs btn btn-primary"></div>                                            
                            <?php echo $this->Html->link('Files','#',array('id'=>'taskfilesbtn'.$task["TaskStatus"]["id"],'class'=>'btn btn-xs btn-info')); ?>    
                        </div>
                    <div id="taskfilesdiv<?php echo $task['TaskStatus']['id'];?>"></div>                           
                        <script>
                                
                                    $('#taskfilesbtn<?php echo $task["TaskStatus"]["id"];?>').click(function(){
                                        $('#taskfilesdiv<?php echo $task["TaskStatus"]["id"];?>').load("<?php echo Router::url('/', true); ?>tasks/task_ajax/<?php echo $task['TaskStatus']['id'] ?>/<?php echo $task['TaskStatus']['id']; ?>", function(response, status, xhr){});
                                });
                            </script>
                            <script>
                                    $().ready(function(){$('#taskcountdiv_main<?php echo $task["TaskStatus"]["id"];?>').load('<?php echo Router::url('/', true); ?>tasks/task_ajax_file_count/<?php echo $task["TaskStatus"]["id"] ?>', function(response, status, xhr){});});
                                    </script>
                            <?php echo $this->Js->writeBuffer(); ?>
                            <?php } ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan='5'>
                <?php
                    if ($i == 1)
                        echo $this->Js->submit(__('Submit'), array('url' => array('controller' => 'tasks', 'action' => 'get_task'), 'div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#tasks_ajax_main','id'=>'task_submit', 'async' => 'false'));
                ?>
            </td>
            </tr>
        </table>
        <?php echo $this->Form->end(); ?>
        <?php echo $this->Js->writeBuffer(); ?>
           
    </div>
<?php } else{ ?>
    <div id="tasks_ajax_main" style="padding:10px">
          
          <h3 class="panel-title"><?php echo  __("Tasks Assigned To You"); ?>
         <span class='badge btn-danger'><?php if(isset($count)) echo $count; ?></span>
          </h3>
                    <hr/>
   No data Found

    </div>
   <?php
} 
}
?>

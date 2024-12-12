<?php
    // Configure::write('debug',1);
    // debug($this->data['Task']);
?>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<script>
$.validator.setDefaults({
    ignore: null,
    errorPlacement: function (error, element) {
        if ($(element).attr('name') == 'data[Task][user_id]' ||
            $(element).attr('name') == 'data[Task][schedule_id]') {
            $(element).next().after(error);
    } else {
        $(element).after(error);
    }
},
});

$().ready(function () {
    $("#TaskRevisedDueDate").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,      
    });
    $("#TaskTaskCompletionDate").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
      startDate : '<?php echo date("Y-m-d",strtotime($this->data["Task"]["start_date"]));?>'
    });

    <?php if($processDates){ 
        $min_date = array_keys($processDates);
        $min_date = date('yyyy-MM-dd',strtotime($min_date[0]));
        $max_date = array_values($processDates);
        $max_date = date('yyyy-MM-dd',strtotime($max_date[0]));

        $start_date = $this->request->data['Task']['start_date'];
        $start_date = date('yyyy-MM-dd',strtotime($start_date));
        $end_date = $this->request->data['Task']['end_date'];
        $end_date = date('yyyy-MM-dd',strtotime($end_date));
        
        ?>        
        $("#TaskStartDate").daterangepicker({
        format: 'MM/DD/YYYY',
            minDate : '<?php echo $min_date;?>',
            maxDate : '<?php echo $max_date;?>',
            startDate : '<?php echo $start_date;?>',
            endDate : '<?php echo $end_date;?>',
            locale: {
                format: 'MM/DD/YYYY'
            },
            autoclose:true,
        });     
    
    <?php } ?>

    <?php if($projectActivity){ 
        $min_date = $projectActivity['ProjectActivity']['start_date'];
        $min_date = date('yyyy-MM-dd',strtotime($min_date));
        $max_date = $projectActivity['ProjectActivity']['end_date'];
        $max_date = date('yyyy-MM-dd',strtotime($max_date));
        
        $start_date = $this->request->data['Task']['start_date'];
        $start_date = date('yyyy-MM-dd',strtotime($start_date));
        $end_date = $this->request->data['Task']['end_date'];
        $end_date = date('yyyy-MM-dd',strtotime($end_date));

        ?>
        
        $("#TaskStartDate").daterangepicker({
        format: 'MM/DD/YYYY',
            minDate : '<?php echo $min_date;?>',
            maxDate : '<?php echo $max_date;?>',
            startDate : '<?php echo $start_date;?>',
            endDate : '<?php echo $end_date;?>',
            locale: {
                format: 'MM/DD/YYYY'
            },
            autoclose:true,
        });     
    
    <?php } ?>

    <?php if(!$projectActivity && !$projectActivity){ ?>
        
        $("#TaskStartDate").daterangepicker({
        format: 'MM/DD/YYYY',
            locale: {
                format: 'MM/DD/YYYY'
            },
            autoclose:true,
        });     
    
    <?php } ?>
    jQuery.validator.addMethod("greaterThanZero", function (value, element) {
        return this.optional(element) || (parseFloat(value) > 0);
    }, "Please select the value");

    $('#TaskEditForm').validate({
        rules: {
            "data[Task][user_id]": {
                greaterThanZero: true,
            },
            "data[Task][schedule_id]": {
                greaterThanZero: true,
            },
            "data[Task][start_date]": {
                required: true,
            },
            "data[Task][end_date]": {
                required: true,
            }
        }
    });
    
    $('#TaskName').blur(function() {
        $("#getTaskName").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_task_name/' + encodeURIComponent(this.value) + '/<?php echo $this->data['Task']['id']; ?>', function(response, status, xhr) {
            if (response != "") {
                $('#TaskName').val('');
                $('#TaskName').addClass('error');
            } else {
                $('#TaskName').removeClass('error');
            }
        });
    });
    
    $("#submit-indicator").hide();
    $("#submit_id").click(function(){
       if($('#TaskEditForm').valid()){
           $("#submit_id").prop("disabled",true);
           $("#submit-indicator").show();
           $("#TaskEditForm").submit();
       }
   });
    $('#TaskUserId').change(function () {
        if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
            $(this).next().next('label').remove();
        }
    });
    $('#TaskScheduleId').change(function () {
        if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
            $(this).next().next('label').remove();
        }
    });
});
</script>
<div id="main-edit">
    <div id="tasks_ajax">
        <?php echo $this->Session->flash(); ?>
        <div class="nav panel panel-default">
            <div class="tasks form col-md-6">
                <h4><?php echo $this->element('breadcrumbs') . __('Edit Task'); ?>
                    <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
                    <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>

                </h4>
                <?php echo $this->Form->create('Task', array('role' => 'form', 'class' => 'form')); ?>
                <?php echo $this->Form->input('id'); ?>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $this->Form->input('name'); ?>
                        <label id="getTaskName" class="error" style="clear:both" ></label>
                    </div>                
                    <div class="col-md-6"><?php echo $this->Form->input('user_id', array('style' => 'width:100%')); ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php 
                    // if($this->request->data['Task']['task_type'] == 0)
                        echo $this->Form->input('task_type',array('options'=>array(0=>'General',1=>'Process Related',2=>'Project Related') , 'disabled', 'type'=>'radio', 'label'=>__('Task Type : Project related or general'))); ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo $this->Form->input('task_status',array('options' => array('0' => 'On going', '1' => 'Completed','2'=>'Not Started','3'=>'Cancelled'), 'type'=>'radio', 'label'=>__('Task Type : Project related or general'))); ?>
                    </div>
                    <?php             
                        // if($this->request->params["named"]["process_id"]){
                    echo '<div class="col-md-6">';
                    echo $this->Form->input('process_id',array('disabled','label'=>__('Linked With Process'))); 
                            // echo $this->Form->input('task_type',array('value'=>1, 'type'=>'hidden'));
                    echo "</div>";
                        // }
                        // if($this->request->params["named"]["project_activity_id"]){
                    echo '<div class="col-md-6">';
                    echo $this->Form->input('project_id',array('disabled', 'label'=>__('Project'))); 
                            // echo $this->Form->input('task_type',array('value'=>1, 'type'=>'hidden'));
                    echo "</div>";

                    echo '<div class="col-md-6">';
                    echo $this->Form->input('milestone_id',array('label'=>__('Milestone'))); 
                            // echo $this->Form->input('task_type',array('value'=>1, 'type'=>'hidden'));
                    echo "</div>";

                    echo '<div class="col-md-6">';
                    echo $this->Form->input('project_activity_id',array('label'=>__('Project Activity'))); 
                            // echo $this->Form->input('task_type',array('value'=>1, 'type'=>'hidden'));
                    echo "</div>";
                        // }
                    ?>                
                </div>    
                <div class="row">
                    <!-- <div class="col-md-6"><?php echo $this->Form->input('process_id',array('label'=>__('Linked With Process'))); ?></div> -->
                    <div class="col-md-6"><?php echo $this->Form->input('master_list_of_format_id', array('style' => 'width:100%')); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('schedule_id', array('lebel'=>'Taks schedule', 'style' => 'width:100%')); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('start_date', array('lable'=>'Taks start date')); ?></div>
                    <div class="col-md-4"><?php 
                    if($this->Session->read('User.is_mr')){
                        // echo $this->Form->input('end_date', array('label'=>'Original End Date'));
                        echo $this->Form->input('revised_due_date', array('lebel'=>'Revised Due Date')); 
                    }else{
                        // echo '<label>Task End Date</label><br />'.$this->request->data['Task']['end_date'];
                        echo $this->Form->input('revised_due_date', array('lebel'=>'Revised Due Date')); 
                    }
                        
                    ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('task_completion_date', array('lable'=>'Taks Completion date')); ?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('description', array('type' => 'textarea')); ?></div>
                </div>
                <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
                ?>
                <?php 
                echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
                echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));
                ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>
            </div>
            <div class="col-md-6">
                <?php if(isset($projectActivity))echo $this->element('projecttimeline',array('project_details'=>$project_details[1]));?>
                <?php if(isset($project))echo $this->element('projecttimeline',array('project_details'=>$project_details[1]));?>
                <p><?php echo $this->element('helps'); ?></p>            
            </div>
        </div>
        <?php $this->Js->get('#list'); ?>
        <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#tasks_ajax'))); ?>
        <?php echo $this->Js->writeBuffer(); ?>
    </div>
</div>

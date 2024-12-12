<?php
    if($this->request->params['named']['project_activity_id']){
        foreach ($project_details[1] as $details) {
            foreach ($details['Milestone']['ProjectActivity'] as $activity) {
                if($activity['ProjectActivity']['id'] == $this->request->params['named']['project_activity_id']){
                    $sequence = count($activity['Tasks']) + 1;
                }
            }
        }        
    }
?>
<?php if($this->request->params['named']['project_id']){
    $redirect = "loadhear";
}else{
    $redirect = "main";
}
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
submitHandler: function (form) {
    $(form).ajaxSubmit({
        url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
        type: 'POST',
        target: '#<?php echo $redirect;?>',
        beforeSend: function(){
         $("#submit_id").prop("disabled",true);
         $("#submit-indicator").show();
     },
     complete: function() {
         $("#submit_id").removeAttr("disabled");
         $("#submit-indicator").hide();
     },
     error: function (request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
}
});

$().ready(function () {
    <?php if($processDates){ 
        $start_date = array_keys($processDates);
        $start_date = date('yyyy-MM-dd',strtotime($start_date[0]));
        $end_date = array_values($processDates);
        $end_date = date('yyyy-MM-dd',strtotime($end_date[0]));
        
        ?>
        $("#TaskStartDate").daterangepicker({
        format: 'MM/DD/YYYY',
            minDate : '<?php echo $start_date;?>',
            maxDate : '<?php echo $end_date;?>',
            locale: {
                format: 'MM/DD/YYYY'
            },
            autoclose:true,
        });     
    
    <?php } ?>

    <?php if($projectActivity){ 
        $start_date = $projectActivity['ProjectActivity']['start_date'];
        $start_date = date('yyyy-MM-dd',strtotime($start_date));
        
        $end_date = $projectActivity['ProjectActivity']['end_date'];
        $end_date = date('yyyy-MM-dd',strtotime($end_date));
        
        ?>
        
        $("#TaskStartDate").daterangepicker({
        format: 'MM/DD/YYYY',
            minDate : '<?php echo $start_date;?>',
            maxDate : '<?php echo $end_date;?>',
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
    
    

    <?php if($this->request->params['named']['process_id']){ ?> 
        $("#get_process").load('<?php echo Router::url('/', true); ?>/processes/view/<?php echo $this->request->params["named"]["process_id"]; ?>/1');
        <?php } ?>


        $("#TaskProcessId").on('change',function(){
            $("#get_process").load('<?php echo Router::url('/', true); ?>/processes/view/' + $("#TaskProcessId").val() + '/1');
        });

        $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#TaskAddAjaxForm').validate({
            rules: {
                "data[Task][user_id]": {
                    greaterThanZero: true,
                },
                "data[Task][schedule_id]": {
                    greaterThanZero: true,
                },
                "data[Task][start_date]": {
                    required: true,
                }                
            }
        });
        
        $('#TaskName').blur(function() {
            $("#getTaskName").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_task_name/' + encodeURIComponent(this.value), function(response, status, xhr) {
                if (response != "") {
                    $('#TaskName').val('');
                    $('#TaskName').addClass('error');
                } else {
                    $('#TaskName').removeClass('error');
                }
            });
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

<div id="tasks_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav">
        <div class="tasks form col-md-6">            
            <?php echo $this->Form->create('Task', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>

            
            <div class="row">
                <div class="col-md-12"><div id="get_process"></div></div>
                <div class="col-md-12"><h4><?php echo __('Add Task'); ?></h4></div>
                <?php if(!isset($this->request->params['named']['process_id']) && !isset($this->request->params['named']['project_id'])){ ?>
                    <div class="col-md-12">
                        <?php echo $this->Form->input('name',array('label'=>__('Task Name'))); ?>
                        <label id="getTaskName" class="error" style="clear:both" ></label>
                    </div>                    
                <?php }else{ ?>
                    <div class="col-md-6">
                        <?php echo $this->Form->input('name',array('label'=>__('Task Name'))); ?>
                        <label id="getTaskName" class="error" style="clear:both" ></label>
                        </div>
                    <div class="col-md-6"><?php echo $this->Form->input('process_team_id', array()); ?></div>
                <?php }?>
                
                <?php
                $default = 0;  
                if(isset($this->request->params['named']['project_activity_id'])){
                    $default = 2;
                }elseif(isset($this->request->params['named']['process_id'])){
                    $default = 1;
                }elseif(isset($this->request->params['named']['customer_complaint_id'])){
                    $default = 3;
                }else {
                    $default = 0; 
                }
                
                $disabled = 'disabled';
                ?>
                <div class="col-md-12">
                    <?php echo $this->Form->input('task_type',array('type'=>'radio', 
                        'options'=>$taslTypes, 
                        'default'=> $default, $disabled, 'class'=>'readonly', 'label'=>__('Task Type : Project related or general'))); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger">
                            <?php echo ('<strong>' . __('Note : ') . '</strong><p>' . __('If you are assigning a new task to a user, make sure that the user has a permission to access the tasks.') . '</p><p>' . __('You can change the user permissions from Users -> View -> Manage Access Control, then click on MR and check the tasks section to provide required permission') . '</p>'); ?>
                        </div>
                    </div>
                    <?php             
                        if($this->request->params["named"]["process_id"]){ ?>
                            <div class="col-md-6"><?php 
                            echo $this->Form->input('process_id',array('label'=>__('Linked With Process'), 'default'=>$this->request->params["named"]["process_id"])); 
                            echo $this->Form->input('task_type',array('value'=>1, 'type'=>'hidden'));
                        ?> </div> <?php } ?>
                        
                        <?php
                        if($this->request->params["named"]["project_id"]){ ?>
                            <div class="col-md-6"><?php 
                            echo $this->Form->input('project_id',array('label'=>__('Project'), 'default'=>$this->request->params["named"]["project_id"])); 
                            echo $this->Form->input('task_type',array('value'=>2, 'type'=>'hidden'));
                        ?> </div> <?php } ?>

                        <?php if($this->request->params["named"]["milestone_id"]){ ?>
                        <div class="col-md-6"><?php echo $this->Form->input('milestone_id',array('default'=>$this->request->params['named']['milestone_id'], 'label'=>__('Milestone'))); ?>
                        </div> <?php } ?>

                        <?php
                        if($this->request->params["named"]["project_activity_id"]){ ?>
                            <div class="col-md-6"><?php 
                            echo $this->Form->input('project_activity_id',array('label'=>__('Project Activity'), 'default'=>$this->request->params["named"]["project_activity_id"])); 
                            echo $this->Form->input('task_type',array('value'=>2, 'type'=>'hidden'));
                        ?> </div> <?php } ?>

                    
                        
                    
                        <?php
                        if($this->request->params["named"]["customer_complaint_id"]){ ?>
                            <div class="col-md-6"><?php 
                            echo $this->Form->input('customer_complaint_id',array('label'=>__('Customer Complaint'), 'default'=>$this->request->params["named"]["customer_complaint_id"])); 
                            echo $this->Form->input('task_type',array('value'=>3, 'type'=>'hidden'));
                        ?> </div> <?php } ?>
                    
                    
                    <div class="col-md-6"><?php echo $this->Form->input('master_list_of_format_id',array('label'=>__('Linked with Master List Of Format'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('schedule_id', array('label'=>'Taks schedule', 'style' => 'width:100%')); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('start_date', array('label'=>'Task date range')); ?></div>
                    <!-- <div class="col-md-4"><?php echo $this->Form->input('end_date', array('label'=>'Taks end date')); ?></div> -->
                    <div class="col-md-12"><?php echo $this->Form->input('description', array('type' => 'textarea')); ?></div>
                    <div class="col-md-12"><?php echo $this->Form->input('deliverable', array('type' => 'textarea')); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('sequence', array('value'=>$sequence)); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('priority', array('options' => array(0=>'High',1=>'Medium',2=>'Low'))); ?></div>
                    <div class="col-md-4"><?php echo $this->Form->input('user_id',array('label'=>__('User Responsible'))); ?></div>
                    <?php
                    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                    ?>
                </div>
                <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
                ?>
                <?php echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#tasks_ajax', 'async' => 'false','id'=>'submit_id')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
                <?php echo $this->Form->end(); ?>
                <?php echo $this->Js->writeBuffer(); ?>
            </div>
            <div class="col-md-6">
                <p><?php echo $this->element('objectiv_details'); ?></p>
                <?php if($this->request->params['named']['project_activity_id'])echo $this->element('projecttimeline',array('project_details'=>$project_details[1]));?>
                <?php if($this->request->params['named']['project_id'])echo $this->element('projecttimeline',array('project_details'=>$project_details[1]));?>
                <p><?php echo $this->element('helps'); ?></p>            
            </div>
        </div>
    </div>
    <script> 
        $("#TaskProjectActivityId").change(function(){
            $("#main").load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/lists/project_activity_id:" + $("#TaskProjectActivityId").val());
        });

        $("#TaskEndDate").change(function(){
            if(new Date($("#TaskStartDate").val()) > new Date($("#TaskEndDate").val())){
                alert('End date should be greater than start date');
                $("#TaskEndDate").val('');
            }
        });    
        
        $.ajaxSetup({
            beforeSend: function () {
                $("#busy-indicator").show();
            },
            complete: function () {
                $("#busy-indicator").hide();
            }
        });
        </script>

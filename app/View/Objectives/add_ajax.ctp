<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="objectives_ajax">
    <?php echo $this->Session->flash();?><div class="nav">
        <div class="objectives form col-md-8">
            <h4>Add Objective</h4>
            <?php echo $this->Form->create('Objective',array('role'=>'form','class'=>'form','default'=>false)); ?>
            <div class="row">
                <fieldset>
                    <?php
                    echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('list_of_kpi_id',array()) . '</div>';
                    echo "<div class='col-md-12'>".$this->Form->input('list_of_kpi_ids',array('name'=>'data[Objective][list_of_kpi_ids][]', 'label'=>'Related KIPs', 'options'=>$listOfKpis,'multiple')) . '</div>';
                    echo "<div class='col-md-6'>".$this->Form->input('clauses',array()) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('master_list_of_format_id',array('label'=>'Select Format (optional)')) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('objective',array()) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('desired_output',array()) . '</div>'; 
                    echo "<div class='col-md-12 hide'>".$this->Form->input('team',array()) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('requirments',array()) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('resource_requirments',array()) . '</div>'; 
                    //echo "<div class='col-md-6'>".$this->Form->input('owner_id',array()) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('schedule_id',array('label'=>'Monitoring Schedule')) . '</div>';
                    echo "<div class='col-md-6'>".$this->Form->input('branch_id',array('label'=>'Assigned to branch')) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('department_id',array('label'=>'Assigned to department')) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('label'=>'Assigned to employee')) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('target_date',array()) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('current_status',array('options'=>array(0=>'Open',1=>'Closed'),'default'=>0)) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('evaluation_method',array()) . '</div>'; 
                   ?>
                </fieldset>
                <?php
                    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
                ?>
            </div>
        <div class="">
            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#objectives_ajax','async' => 'false')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer();?>
        </div>
    </div>
    <script> $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
        autoclose:true,
    }); 
    </script>
    <div class="col-md-4">
        <p><?php echo $this->element('helps'); ?></p>
    </div>
</div>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[Objective][branch_id]')
                $(element).next().after(error);
            else if ($(element).attr('name') == 'data[Objective][employee_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[Objective][department_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[Objective][schedule_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
        });
        }
    });
    $().ready(function() {
        $("#submit-indicator").hide();
        
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        jQuery.validator.addMethod("customPhoneNumber", function(value, element) {
            return this.optional(element) || /^[0-9-/()+\s]{6,16}$/i.test(value);
        }, "Please enter valid number");

        $('#ObjectiveAddAjaxForm').validate({
            rules: {
                "data[Objective][branch_id]": {
                    greaterThanZero: true,
                },
                "data[Objective][department_id]": {
                    greaterThanZero: true,
                },
                "data[Objective][employee_id]": {
                    greaterThanZero: true,
                },
                "data[Objective][schedule_id]": {
                    greaterThanZero: true,
                },
            }

        });

        $('#ObjectiveBranchId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ObjectiveDepartmentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ObjectiveEmployeeId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });  
        $('#ObjectiveScheduleId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });        
    });

</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

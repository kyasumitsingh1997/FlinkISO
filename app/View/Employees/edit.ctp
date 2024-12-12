<div id="employees_ajax">
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
    <?php echo $this->fetch('script'); ?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {

            if ($(element).attr('name') == 'data[Employee][branch_id]')
                $(element).next().after(error);
            else if ($(element).attr('name') == 'data[Employee][designation_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[Employee][department_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        }
    });
    $().ready(function() {
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        jQuery.validator.addMethod("customPhoneNumber", function(value, element) {
            return this.optional(element) || /^[0-9-/()+\s]{6,16}$/i.test(value);
        }, "Please enter valid number");

        $('#EmployeeEditForm').validate({
            rules: {
                "data[Employee][office_email]": {
                    required: true,
                    email: true
                },
                "data[Employee][branch_id]": {
                    greaterThanZero: true,
                },
                "data[Employee][designation_id]": {
                    greaterThanZero: true,
                },
                "data[Employee][personal_telephone]": {
                    customPhoneNumber: true,
                },
                "data[Employee][mobile]": {
                    customPhoneNumber: true,
                },
                "data[Employee][office_telephone]": {
                    customPhoneNumber: true,
                },
                "data[Employee][personal_email]": {
                    email: true
                },
                "data[Employee][department_id]": {
                    required: true,
                },
            }

        });
        $("#submit-indicator").hide();
    $("#submit_id").click(function(){
             if($('#EmployeeEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#EmployeeEditForm").submit();
             }
        });
        $('#EmployeeDesignationId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#EmployeeBranchId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#EmployeeDepartmentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#EmployeeOfficeEmail').blur(function() {

            $("#getEmployeeEmail").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_employee_email/' + encodeURIComponent(this.value) + '/<?php echo $this->data['Employee']['id']; ?>', function(response, status, xhr) {
                if (response != "") {
                    $('#EmployeeOfficeEmail').val('');
                    $('#EmployeeOfficeEmail').addClass('error');
                } else {
                    $('#EmployeeOfficeEmail').removeClass('error');
                }
            });
        });

        $('#EmployeeEmployeeNumber').blur(function() {

            $("#getEmployeeNumber").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_employee_number/' + encodeURIComponent(this.value)+ '/<?php echo $this->data['Employee']['id']; ?>', function(response, status, xhr) {
                if (response != "") {
                    $('#EmployeeEmployeeNumber').val('');
                    $('#EmployeeEmployeeNumber').addClass('error');
                } else {
                    $('#EmployeeEmployeeNumber').removeClass('error');
                }
            });
        });
    });
</script>
    <div class="nav panel panel-default">
        <div class="employees form col-md-8 panel">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Employee'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
            </h4>
            <?php echo $this->Form->create('Employee', array('role' => 'form', 'class' => 'form' , 'type' => 'file')); ?>
            <?php echo $this->Form->input('id'); ?>

            <div class="row">
                <fieldset><legend><h5><?php echo __('Mandatory Details'); ?></h5></legend>
                    <div class="col-md-6"><?php echo $this->Form->input('name', array('label' => __('Name'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('employee_number', array('label' => __('Employee Number'))); ?><label id="getEmployeeNumber" class="error" style="clear:both" ></label></div>
                    <div class="col-md-6"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('designation_id', array('style' => 'width:100%', 'label' => __('Designation'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('joining_date', array('label' => __('Joining Date'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('date_of_birth', array('label' => __('Date of Birth'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('office_email', array('label' => __('Office Email'))); ?>
                        <label id="getEmployeeEmail" class="error" style="clear:both" ></label>
                    </div>
                    <div class="col-md-6"><?php echo $this->Form->input('parent_id', array('label' => __('Reports To'),'options'=>$PublishedEmployeeList)); ?></div>
                    <div class="col-md-6 panel panel-body panel-info">
                         <label><?php echo __('Avatar'); ?></label>
                    <p class="text-info">(Acceptable image formats is 'png')</p>
                     <?php echo $this->Form->file('avatar', array('style' => 'box-shadow: none !important; border: none !important;')); ?>
                     <?php
                            if(file_exists(WWW_ROOT . 'img' .DS . $this->Session->read('User.company_id') . DS . 'avatar' . DS . $this->request->params['pass'][0] . DS . 'avatar.png')){
                                echo "<span class='text-success glyphicon glyphicon-ok pull-right'></span>";
                            }else{
                                echo "<span class='text-danger glyphicon glyphicon-remove pull-right'></span>";
                            }
                         ?>
                     </div>
                </fieldset>
            </div>

            <div class="row">
                <fieldset><legend><h5><?php echo __('Optional Details'); ?></h5></legend>
                <div class="col-md-6"><?php echo $this->Form->input('indentification_number', array('label' => __('Identification Number'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('pancard_number', array('label' => __('Pancard Number'))); ?></div>
                <div class="col-md-6">
                        <?php
                            $qualifications = explode(",", $this->data['Employee']['qualification']);
                            echo $this->Form->input('qualification', array('name' => 'qualification[]', 'type' => 'select', 'options' => $educations, 'value' => $qualifications, 'multiple', 'label' => __('Qualification')));
                        ?>   
                        </div>                 
                    <div class="col-md-6"><?php echo $this->Form->input('maritial_status', array('type' => 'select', 'options' => $maritalStatus, 'style' => 'width:100%', 'label' => __('Marital Status'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('personal_telephone', array('label' => __('Personal Telephone'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('office_telephone', array('label' => __('Office Telephone'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('mobile', array('label' => __('Mobile'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('personal_email', array('label' => __('Personal Email'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('residence_address', array('label' => __('Residence Address'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('permenant_address', array('label' => __('Permanent Address'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('driving_license', array('label' => __('Driving License'))); ?></div>
                    <div class="col-md-6"><?php echo $this->Form->input('employment_status', array('options' => array('1'=>'Active', '0'=>'Resigned','2'=>'Death',3=>'Dismissed',4=>'Retrentched'))); ?></div>
                    </fieldset>                    
                </div>
                <div class="row">
                        <div class="col-md-12"><?php echo $this->Form->input('roles_responsibilities', array('label' => __('Roles Responsibilities'))); ?></div>
                    </div>
        <?php if(($this->Session->read('User.employee_id')) == ($this->data['Employee']['id'])){ ?>
            <div class="">
                 <div class="col-md-6 panel panel-body panel-info">
                          <label><?php echo __('Select Certificate'); ?></label>
                          <p class="text-info">(Acceptable certificate formats is 'crt')</p>
                          <?php echo $this->Form->file('certificate', array('style' => 'box-shadow: none !important; border: none !important;')); ?>
                         <?php
                            if(file_exists(APP. DS . 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->params['pass'][0] . DS . 'pdf.crt')){
                                echo "<span class='text-success glyphicon glyphicon-ok pull-right'></span>";
                            }else{
                                echo "<span class='text-danger glyphicon glyphicon-remove pull-right'></span>";
                            }
                         ?>
                         </div>
                     <div class="col-md-6 panel panel-body panel-info">
                         <label><?php echo __('Select Signature'); ?></label>
                    <p class="text-info">(Acceptable image formats is 'png')</p>
                     <?php echo $this->Form->file('signature', array('style' => 'box-shadow: none !important; border: none !important;')); ?>
                     <?php
                            if(file_exists(APP. DS . 'files' . DS . $this->Session->read('User.company_id') . DS . 'PdfCertificate' . DS . $this->request->params['pass'][0] . DS . 'signature.png')){
                                echo "<span class='text-success glyphicon glyphicon-ok pull-right'></span>";
                            }else{
                                echo "<span class='text-danger glyphicon glyphicon-remove pull-right'></span>";
                            }
                         ?>
                     </div>
            </div>
        <?php }; ?>
            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>
<script>
    var myDate = new Date();
    var newDate = new Date(myDate.getFullYear() - 18, myDate.getMonth(), myDate.getDate());
    $("[name*='joining_date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');
    $("[name*='joining_date']").datepicker('option', 'maxDate', 0);


    $("[name*='date_of_birth']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');
    $("[name*='date_of_birth']").datepicker('option', 'maxDate', newDate);
</script>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#employees_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>

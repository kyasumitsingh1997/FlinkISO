<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?><?php echo $this->fetch('script'); ?>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[Customer][branch_id]') {
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
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });

        }

    });

    $().ready(function() {
    $("#submit-indicator").hide();
        var curValue = $("input[name='data[Customer][customer_type]']").val();
        if (curValue != 0) {
            $("#CustomerMaritialStatus_chosen").width('100%');
            $("div.indCust").show();
        } else {
            $("div.indCust").hide();
        }

        $("input[name='data[Customer][customer_type]']").click(function() {
            var curValue = $("input[name='data[Customer][customer_type]']:checked").val();
            if (curValue == 0) {
                $("div.indCust").hide();
            } else {
                $("#CustomerMaritialStatus_chosen").width('100%');
                $("div.indCust").show();

            }
        });

        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        jQuery.validator.addMethod("customPhoneNumber", function(value, element) {
            return this.optional(element) || /^[0-9-/()+]{6,16}$/i.test(value);
        }, "Please enter correct number");
        $('#CustomerAddAjaxForm').validate({
            rules: {
                "data[Customer][branch_id]": {
                    greaterThanZero: true,
                },
                "data[Customer][phone]": {
                    required: true,
                },
                "data[Customer][mobile]": {
                    required: true,
                },
            }
        });
        $('#CustomerBranchId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#CustomerCustomerCode').blur(function() {
            uniqueCheck(this.value, 'custCode');
        });
        $('#CustomerEmail').blur(function() {
            uniqueCheck(this.value, 'emailId');
        });
    });

    function uniqueCheck(chechVal, type) {
        if (type == 'custCode') {
            $("#getCustCode").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_unique_values/' + encodeURIComponent(chechVal) + '/' + type, function(response, status, xhr) {
                if (response != "") {
                    $('#CustomerCustomerCode').val('');
                    $('#CustomerCustomerCode').addClass('error');
                } else {
                    $('#CustomerCustomerCode').removeClass('error');
                }
            });
        } else {
            $("#getEmail").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_unique_values/' + encodeURIComponent(chechVal) + '/' + type, function(response, status, xhr) {
                if (response != "") {
                    $('#CustomerEmail').val('');
                    $('#CustomerEmail').addClass('error');
                } else {
                    $('#CustomerEmail').removeClass('error');
                }
            });
        }
    }
</script>

<div id="customers_ajax"> <?php echo $this->Session->flash(); ?>
    <div class="nav">
        <div class="customers form col-md-8">
            <h4><?php echo __('Add Customer'); ?></h4>
            <?php echo $this->Form->create('Customer', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="btn-group"><div class="btn btn-default btn-sm" id="type_company">Company</div><div class="btn btn-default btn-sm" id="type_individual">Individual</div></div>                   
                    <?php echo $this->Form->hidden('customer_type', array('legend' => false, 'default' => 0));?>
                </div>
                <div class="col-md-6">
                    <div class="btn-group"><div class="btn btn-default btn-sm" id="new">New Prospect (Lead)</div><div class="btn btn-default btn-sm" id="existing">Existing Customer</div></div>
                    <?php echo $this->Form->hidden('lead_type', array('legend' => false, 'default' =>1));?>
                </div>
                <div class="col-md-3"><?php echo $this->Form->input('customer_code'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('name'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('email'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('residence_address', array('label' => __('Address'))); ?></div>
                <div class="col-md-6">
                    <label id="getEmail" class="error" ></label>
                </div>
                <div class="col-md-6"><?php echo $this->Form->input('phone'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('mobile'); ?></div>
                <div class="indCust">
                    <div class="col-md-3"><?php echo $this->Form->input('maritial_status', array('type' => 'select', 'options' => $maritalStatus, 'style' => 'width:100%', 'label' => __('Marital Status'))); ?></div>
                    <div class="col-md-3"><?php echo $this->Form->input('date_of_birth'); ?></div>
                </div>
                <?php echo $this->Form->hidden('branch_id', array('label'=> __('Branch'), 'value' => $this->Session->read('User.branch_id'))); ?>
                <?php
                        echo $this->Form->hidden('customer_since_date',array('value'=>date('Y-m-d')));                      
                        echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
                        echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
                        echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
                ?>
                <div class="col-md-6"><?php echo $this->Form->input('employee_id'); ?></div>
            </div>
            
            <div class="row">
                <hr />
                <div class="col-md-6"><h4><?php echo __('Add Customer Contact Details'); ?></h4></div><div class="col-md-6"><?php echo $this->Form->input('CustomerContact.same_as_above',array('type'=>'checkbox','onClick'=>'same(this.id)')); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('CustomerContact.name'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('CustomerContact.email'); ?><label id="getEmail" class="error" ></label></div>
                <div class="col-md-6"><?php echo $this->Form->input('CustomerContact.phone'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('CustomerContact.mobile'); ?></div>
            </div>
            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
            ?>
            <?php echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#customers_ajax', 'async' => 'false','id'=>'submit_id')); ?> <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?> <?php echo $this->Form->end(); ?> <?php echo $this->Js->writeBuffer(); ?> </div>
<script>

    var myDate = new Date();
    var newDate = new Date(myDate.getFullYear() - 18, myDate.getMonth(), myDate.getDate());
    $("[name*='customer_since_date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');
    $("[name*='customer_since_date']").datepicker('option', 'maxDate', myDate);


    $("[name*='date_of_birth']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');
    $("[name*='date_of_birth']").datepicker('option', 'maxDate', myDate);
</script>
<script>
    $().ready(function(){
        $('#type_company').click(function(){$('#type_company').removeClass(' btn-default').addClass(' btn-success');$('#type_individual').removeClass(' btn-success').addClass(' btn-default');$('#CustomerCustomerType').val(0)});
        $('#type_individual').click(function(){$('#type_individual').removeClass(' btn-default').addClass(' btn-success');$('#type_company').removeClass(' btn-success').addClass(' btn-default');$('#CustomerCustomerType').val(1)});
        
        $('#new').click(function(){$('#new').removeClass(' btn-default').addClass(' btn-success');$('#existing').removeClass(' btn-success').addClass(' btn-default');$('#CustomerLeadType').val(0)});
        $('#existing').click(function(){$('#existing').removeClass(' btn-default').addClass(' btn-success');$('#new').removeClass(' btn-success').addClass(' btn-default');$('#CustomerLeadType').val(1)});
    });

    function same(a){
        // var a = $('#CustomerContactSameAsAbove').isChecked();
        if ($('#CustomerContactSameAsAbove').is(':checked')) {
            $('#CustomerContactName').val($('#CustomerName').val());
            $('#CustomerContactEmail').val($('#CustomerEmail').val());
            $('#CustomerContactPhone').val($('#CustomerPhone').val());
            $('#CustomerContactMobile').val($('#CustomerMobile').val());
        }else{
            $('#CustomerContactName').val('');
            $('#CustomerContactEmail').val('');
            $('#CustomerContactPhone').val('');
            $('#CustomerContactMobile').val('');
        }
        
    }
</script>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>

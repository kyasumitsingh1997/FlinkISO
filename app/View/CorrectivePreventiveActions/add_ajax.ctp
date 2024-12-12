<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style type="text/css">
#get_details .chosen-container-single .chosen-single{float: left; width: 100%}
</style>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[CorrectivePreventiveAction][capa_source_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][capa_category_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][suggestion_form_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][customer_complaint_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][supplier_registration_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][device_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][product_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][material_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][internal_audit_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][process_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CorrectivePreventiveAction][risk_assessment_id]') {
                $(element).next().after(error);
            } 

            // else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][master_list_of_format]') {
            //     $(element).next().after(error);
            // } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][env_activity_id]') {
            //     $(element).next().after(error);
            // } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][env_identification_id]') {
            //     $(element).next().after(error);
            // } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][project_id]') {
            //     $(element).next().after(error);
            // } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][project_activity_id]') {
            //     $(element).next().after(error);
            // }

            else {
                $(element).after(error);
            }
        },
        submitHandler: function (form) {
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
                error: function (request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
        }
    });

    $().ready(function () {
    $("#submit-indicator").hide();

    $("#CapaInvestigationTargetDate").datepicker({
        format: 'yyyy-mm-dd',
        startDate: '<?php echo date("Y-m-d");?>',
        autoclose:true,
      });
    $("#CapaRootCauseAnalysiTargetDate").datepicker({
        format: 'yyyy-mm-dd',
        startDate: '<?php echo date("Y-m-d");?>',
        autoclose:true,
    });

    $("#CapaInvestigationTargetDate").val("<?php echo date('Y-m-d',strtotime('+7 days'));?>");
    $("#CapaRootCauseAnalysiTargetDate").val("<?php echo date('Y-m-d',strtotime('+7 days'));?>");

    $("#CorrectivePreventiveActionProjectId").change(function(){
        $("#project_activity_id_change").load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/project_activity_id_change/" + $("#CorrectivePreventiveActionProjectId").val());
    });
  
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#CorrectivePreventiveActionAddAjaxForm').validate({
            rules: {
                "data[CorrectivePreventiveAction][capa_source_id]": {
                    greaterThanZero: true,
                },
                "data[CorrectivePreventiveAction][capa_category_id]": {
                    greaterThanZero: true,
                },
                "data[CorrectivePreventiveAction][suggestion_form_id]": {
                    greaterThanZero: true,
                },
                "data[CorrectivePreventiveAction][customer_complaint_id]": {
                    greaterThanZero: true,
                },
                "data[CorrectivePreventiveAction][supplier_registration_id]": {
                    greaterThanZero: true,
                },
                "data[CorrectivePreventiveAction][device_id]": {
                    greaterThanZero: true,
                },
                "data[CorrectivePreventiveAction][material_id]": {
                    greaterThanZero: true,
                },
                "data[CorrectivePreventiveAction][internal_audit_id]": {
                    greaterThanZero: true,
                },
                "data[CorrectivePreventiveAction][process_id]": {
                    greaterThanZero: true,
                },
                "data[CorrectivePreventiveAction][risk_assessment_id]": {
                    greaterThanZero: true,
                }
                // ,
                // "data[CorrectivePreventiveAction][env_activity_id]": {
                //     greaterThanZero: true,
                // },
                // "data[CorrectivePreventiveAction][env_identification_id]": {
                //     greaterThanZero: true,
                // },
                // "data[CorrectivePreventiveAction][project_id]": {
                //     greaterThanZero: true,
                // },
                // "data[CorrectivePreventiveAction][project_activity_id]": {
                //     greaterThanZero: true,
                // }
            }
        });

        $('#CorrectivePreventiveActionCapaSourceId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionCapaCategoryId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionProductId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionInternalAuditId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionSuggestionFormId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionCustomerComplaintId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionSupplierRegistrationId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionDeviceId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionMaterialId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionProcessId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionRiskAssessmentId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
   		 
        //  $('#CorrectivePreventiveActionMasterListOfFormat').change(function () {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#CorrectivePreventiveActionEnvActivityId').change(function () {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#CorrectivePreventiveActionEnvIdentificationId').change(function () {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#CorrectivePreventiveActionProjectId').change(function () {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#CorrectivePreventiveActionProjectActivityId').change(function () {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
$("[name='data[CorrectivePreventiveAction][document_changes_required]']").click(function(){
   
	    docChangeRequired();
	});
      docChangeRequired();
    
    $("#suggestion").hide();
    $("#audit").hide();
    $("#complaint").hide();
    $("#supplier").hide();
    $("#product").hide();
    $("#device").hide();
    $("#material").hide();
    $("#procedure").hide();
    $("#task").hide();
    $("#activities").hide();
    $("#identification").hide();
    $("#project").hide();
    $("#process").hide();
    $("#risk").hide();
    $('#CorrectivePreventiveActionCapaCategoryId').change(function () {
        $("#suggestion").hide();
        $("#complaint").hide();
        $("#supplier").hide();
        $("#product").hide();
        $("#device").hide();
        $("#material").hide();
        $("#procedure").hide();
        $("#task").hide();
        $("#audit").hide();
        $("#activities").hide();
        $("#identification").hide();
        $("#project").hide();
        $("#process").hide();
        $("#risk").hide();
        $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionProcedureId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionTaskId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionProjectId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionProjectActivityId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionProcessId').val(0).trigger('chosen:updated').rules('remove');
        $('#CorrectivePreventiveActionRiskAssessmentId').val(0).trigger('chosen:updated').rules('remove');

        $("#get_details :input").val('').trigger('chosen:updated');
        //alert($('#CorrectivePreventiveActionCapaCategoryId').val());
        if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5245a8fc-8f4c-4ab5-ab27-41f2c6c3268c") {
            $("#audit").show();
	        $("#CorrectivePreventiveActionInternalAuditId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionInternalAuditId_chosen").width('100%');
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5245a90d-1f4c-4693-9853-41ebc6c3268c") {
            $("#suggestion").show();
            $("#CorrectivePreventiveActionSuggestionFormId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionSuggestionFormId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5245a935-7f58-482c-83c5-41f1c6c3268c") {
            $("#complaint").show();
            $("#CorrectivePreventiveActionCustomerComplaintId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionCustomerComplaintId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5245a95b-1340-4531-8d4a-4151c6c3268c") {
            $("#supplier").show();
            $("#CorrectivePreventiveActionSupplierRegistrationId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionSupplierRegistrationId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "528fcdd7-63ec-497e-b4f3-01e5c6c3268c") {
            $("#product").show();
            $("#CorrectivePreventiveActionProductId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionProductId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "533e94b8-7b70-4fad-bcdd-1a3a51f38a45") {
            $("#device").show();
            $("#CorrectivePreventiveActionDeviceId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionDeviceId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "53200cde-bb2c-4236-be8c-f90d51f38a45") {
            $("#material").show();
            $("#CorrectivePreventiveActionMaterialId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionMaterialId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5950b983-f668-49e5-a959-d26fdb1e6cf9") {
            $("#process").show();
            $("#CorrectivePreventiveActionProcessId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionProcessId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5950b98f-88e8-4123-b0d5-e796db1e6cf9") {
            $("#risk").show();
            $("#CorrectivePreventiveActionRiskAssessmentId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionRiskAssessmentId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "56235f2c-29bc-41ca-8766-05776c5ee721") {
            $("#task").show();
            $("#CorrectivePreventiveActionTaskId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionTaskId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "568be18e-88d8-45ea-8148-01dcdb1e6cf9") {
            $("#project").show();
            $("#CorrectivePreventiveActionProjectId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionProjectId_chosen").width('100%');            
            $("#CorrectivePreventiveActionProjectActivityId").rules("add", {greaterThanZero: true});
            $("#CorrectivePreventiveActionProjectActivityId_chosen").width('100%');            
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "567afe0b-019c-461e-a5eb-024adb1e6cf9") {
            $("#activities").show();
            $("#identification").show();
            $("#CorrectivePreventiveActionEnvIdentificationId_chosen").width('100%');
            $("#CorrectivePreventiveActionEnvActivityId_chosen").width('100%');
            $("#CorrectivePreventiveActionEnvAvtivityId").rules("add", {greaterThanZero: true});
            
        // } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "56235f1f-d0b4-49ae-9fe4-053a6c5ee721") {
        //     $("#process").show();
        //     $("#CorrectivePreventiveActionProcessId").rules("add", {greaterThanZero: true});
        //     $("#CorrectivePreventiveActionProcessId_chosen").width('100%');            
        } else {            
            $("#material").hide();
            $("#suggestion").hide();
            $("#audit").hide();
            $("#complaint").hide();
            $("#supplier").hide();
            $("#product").hide();
            $("#device").hide();
            $("#audit").hide();
            $("#activities").hide();
            $("#identification").hide();
            $("#project").hide();
            $("#process").hide();
            $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionProjectId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionProjectActivityId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionProcessId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionRiskAssessmentId').val(0).trigger('chosen:updated').rules('remove');
        }
    });
  function docChangeRequired(){
     
	var changeRequired = $("[name='data[CorrectivePreventiveAction][document_changes_required]']").is(':checked');
	if(changeRequired == true){
	    $("#docChangeReq").show();
	    $("#CorrectivePreventiveActionMasterListOfFormat_chosen").width('100%');
	    $("#CorrectivePreventiveActionMasterListOfFormat").rules('add', {greaterThanZero: true});

//	    $("#CorrectivePreventiveActionCurrentDocumentDetails").rules('add', {required: true});
//	    $("#CorrectivePreventiveActionRequestDetails").rules('add', {required: true});
	//    $("#CorrectivePreventiveActionReasonForChange").rules('add', {required: true});
	  //  $("#docChangeReq").find("select").prop("disabled", false).trigger('chosen:updated');
	    // $("[name='data[CorrectivePreventiveAction][document_changes_required]']").val(1);
	} else {
	    $("#docChangeReq").hide();

//	    $("#docChangeReq").find("input, textarea, select, button, select, div").val("");
//	    $("#docChangeReq").find("select").prop("disabled", true).trigger('chosen:updated');
	//    $("[name='data[CorrectivePreventiveAction][document_changes_required]']").val(0);

	    $("#CorrectivePreventiveActionMasterListOfFormat").rules('remove');

//	    $("#CorrectivePreventiveActionCurrentDocumentDetails").rules("remove");
//	    $("#CorrectivePreventiveActionRequestDetails").rules('remove');
	//    $("#CorrectivePreventiveActionReasonForChange").rules('remove');

	    $("#CorrectivePreventiveActionMasterListOfFormat").next().next('label').remove();

	//    $("#CorrectivePreventiveActionReasonForChange").next('label').remove();
//	    $("#CorrectivePreventiveActionCurrentDocumentDetails").next('label').remove();
//	    $("#CorrectivePreventiveActionRequestDetails").next('label').remove();
	}
    }
    });

 function currentStatus(n){
    if(n == 1){
        $("#closefield").removeClass('hide').addClass('show');
    }else{
        $("#closefield").removeClass('show').addClass('hide');
    }
 }

</script>

<div id="correctivePreventiveActions_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav">
        <div class="correctivePreventiveActions form col-md-8">
            <h4><?php echo __('Add Corrective Preventive Action'); ?></h4>
            <?php echo $this->Form->create('CorrectivePreventiveAction', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>

            <div class="row">
                <legend><h1>Problem Description</h1></legend>

                <div class="col-md-12"><?php
                    echo "<label>" . __('Select Action') . "</label>";
                    echo $this->Form->input('capa_type', array('label' => false, 'legend' => false, 'value' => false, 'div' => false, 'options' => array('0' => 'Corrective Action', '1' => 'Preventive Action', '2' => 'Both'), 'type' => 'radio', 'style' => 'float:none'));
                    ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('name', array('value'=>'CAPA Dated : ' . date('Y-m-d'), 'label' => __('Name'))); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('number', array('label' => __('Number'),'value'=>$cap_number)); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('created_date', array('value'=> date('Y-m-d'), 'label' => __('Created Date'))); ?></div>
                <div class="col-md-3"><?php echo $this->Form->input('target_date', array('value'=> date('Y-m-d',strtotime('+7 days')), 'label' => __('Target Date'))); ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?php echo $this->Form->input('capa_rating_id', array('style' => 'width:100%', 'label' => __('CAPA Ratings'))); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('capa_source_id', array('style' => 'width:100%', 'label' => __('CAPA Source'))); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('capa_category_id', array('style' => 'width:100%', 'label' => __('CAPA Category'))); ?></div>
            </div>
            <div class="row">
                <div id="get_details">
                    <div id="audit">
                        <div class="col-md-12"><?php echo $this->Form->input('internal_audit_id', array('style' => 'width:100%', 'label' => __('Select Internal Audit'))); ?></div>
                    </div>
                    <div id="suggestion">
                        <div class="col-md-12"><?php echo $this->Form->input('suggestion_form_id', array('style' => 'width:100%', 'label' => __('Select Suggestion Form'))); ?></div>
                    </div>
                    <div id="complaint">
                        <div class="col-md-12"><?php echo $this->Form->input('customer_complaint_id', array('style' => 'width:100%', 'label' => __('Select Customer Complaint'))); ?></div>
                    </div>
                    <div id="supplier">
                        <div class="col-md-12"><?php echo $this->Form->input('supplier_registration_id', array('style' => 'width:100%', 'label' => __('Select Supplier'))); ?></div>
                    </div>
                    <div id="product">
                        <div class="col-md-12"><?php echo $this->Form->input('product_id', array('style' => 'width:100%', 'label' => __('Select Product'))); ?></div>
                    </div>
                    <div id="device">
                        <div class="col-md-12"><?php echo $this->Form->input('device_id', array('style' => 'width:100%', 'label' => __('Select Device'))); ?></div>
                    </div>
                    <div id="material">
                        <div class="col-md-12"><?php echo $this->Form->input('material_id', array('style' => 'width:100%', 'label' => __('Select Material'))); ?></div>
                    </div>
                    <div id="process">
                        <div class="col-md-12"><?php echo $this->Form->input('process_id', array('style' => 'width:100%', 'label' => __('Select Process'))); ?></div>
                    </div>
                    <div id="risk">
                        <div class="col-md-12"><?php echo $this->Form->input('risk_assessment_id', array('style' => 'width:100%', 'label' => __('Select Risk'))); ?></div>
                    </div>
                    <div id="procedure">
                        <div class="col-md-12"><?php echo $this->Form->input('procedure_id', array('style' => 'width:100%', 'label' => __('Select Procedure'))); ?></div>
                    </div>
                    <div id="task">
                        <div class="col-md-12"><?php echo $this->Form->input('task_id', array('style' => 'width:100%', 'label' => __('Select Task'))); ?></div>
                    </div>
                    <div id="identification">
                        <div class="col-md-12"><?php echo $this->Form->input('env_identification_id', array('style' => 'width:100%', 'label' => __('Select Identification details'))); ?></div>
                    </div>
                    <div id="activities">
                        <div class="col-md-12"><?php echo $this->Form->input('env_activity_id', array('style' => 'width:100%', 'label' => __('Select Activity'))); ?></div>
                    </div>
                    <div id="project">
                        <div class="col-md-12">
                            <?php echo $this->Form->input('project_id', array('style' => 'width:100%', 'label' => __('Select Project'))); ?>
                        </div>
                        <div class="col-md-6">
                            <div id="project_activity_id_change"><?php echo $this->Form->input('project_activity_id', array('style' => 'width:100%', 'label' => __('Select Project Activity'))); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12"><?php echo $this->Form->input('problem_description'); ?></div>
            </div>
            <div class="row">
<!--                <div class="col-md-4"><?php //echo $this->Form->hidden('raised_by', array('label' => __('Raised By'))); ?></div>-->
              
                <div class="col-md-12"><?php echo $this->Form->input('initial_remarks', array('label' => __('Initial Remarks/Interim Containment Actions'))); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('proposed_immidiate_action', array('label' => __('Proposed Immediate Action'))); ?></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h3><?php echo __('Assign Investigation');?></h3>
                    <?php echo $this->Form->input('CapaInvestigation.employee_id',array('name'=>'data[CapaInvestigation][employee_id][]', 'lable'=>'Investigation Assigned To','options'=>$PublishedEmployeeList,'multiple'));?>
                    <?php echo $this->Form->input('CapaInvestigation.target_date');?>
                </div>
                <div class="col-md-6">
                    <h3><?php echo __('Assign Root Cause Analysis');?></h3>
                    <?php echo $this->Form->input('CapaRootCauseAnalysi.employee_id',array('name'=>'data[CapaRootCauseAnalysi][employee_id][]', 'label'=> 'Root Cause Analysis Assigned To','options'=>$PublishedEmployeeList,'multiple'));?>
                    <?php echo $this->Form->input('CapaRootCauseAnalysi.target_date');?>
                </div>
            </div>

            <div id="statusCloseDetails" class="row hide">
                <div class="col-md-6 text-danger"><?php echo $this->Form->input('root_cause_analysis_required', array('label' => __('Root Cause Analysis Required'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('document_changes_required', array('type' => 'checkbox', 'value' => '0', 'label' => __('Document Changes Required'))); ?></div>
                <div id="docChangeReq">
                    <div class="col-md-6"><?php echo $this->Form->input('master_list_of_format', array('options' => $masterListOfFormats)); ?></div>      
                    <div class="row"><div class="col-md-12" id="current_details"></div>
                </div>
                <div class="col-md-12"><?php echo $this->Form->input('reason_for_change', array('type' => 'textarea')); ?></div>
                <div class="clearfix">&nbsp;</div>
            </div>
		</div>
        <div class="row">
            <div class="col-md-4">
                <?php echo $this->Form->input('capa_password', array('type' => 'password')); ?>
            </div>
            <div class="col-md-8">
                <br /><br /><p>You can protect CAPA beging closed by locking this record with password.</p>
            </div>
        </div>
            <hr />
            <div class="row hide">
                <div class="col-md-12"><?php
                    echo "<label>" . __('Current Status') . "</label>";
                    echo $this->Form->input('current_status', array('value' => '0', 'label' => false, 'legend' => false,  'div' => false, 'options' => array('0' => 'Open', '1' => 'Close'), 'type' => 'radio', 'style' => 'float:none','onclick' => 'currentStatus(this.value)'));
                    ?></div>
            </div>
            <div class="row">
                <div class="col-md-12 hide" id="closefield"><?php echo $this->Form->input('closure_remarks', array('label' => 'Closure Remarks')) ?></div>
            </div>
            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
            <?php echo $this->Form->hidden('branchid', array('value' => $this->Session->read('User.branch_id'), 'disabled' => 'disabled')); ?>
            <?php echo $this->Form->hidden('departmentid', array('value' => $this->Session->read('User.department_id'), 'disabled' => 'disabled')); ?>
            <?php echo $this->Form->hidden('master_list_of_format_id', array('value' => $documentDetails['MasterListOfFormat']['id'])); ?>

            <?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#correctivePreventiveActions_ajax', 'async' => 'false','id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
       
        </div>



        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
</div>
<script>
    $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
    });
</script>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }
    });
</script>

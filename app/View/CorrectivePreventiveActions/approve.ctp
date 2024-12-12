<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<?php 

$close_status = 'show';
foreach ($capaInvestigations as $capaInvestigation) {
    if($capaInvestigation['CapaInvestigation']['current_status'] == 0){
        $close_status = 'hide';        
    }
}
foreach ($capaRootCauseAnalysis as $capaRootCauseAnalysi) {
    if($capaRootCauseAnalysi['CapaRootCauseAnalysi']['current_status'] == 0){
        $close_status = 'hide';        
    }
}
// Configure::write('debug',1);
// debug($close_status);
// debug($capaInvestigations);
    // exit;
?>
<style type="text/css">
input[type=radio]{margin: 0 5px !important;}
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
            } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][master_list_of_format]') {
                $(element).next().after(error);
            } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][env_activity_id]') {
                $(element).next().after(error);
            } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][env_identification_id]') {
                $(element).next().after(error);
            } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][project_id]') {
                $(element).next().after(error);
            } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][project_activity_id]') {
                $(element).next().after(error);
            } else  if ($(element).attr('name') == 'data[CorrectivePreventiveAction][process_id]') {
                $(element).next().after(error);
            }

            else {
                $(element).after(error);
            }
    }});

    $().ready(function(){

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

        $("#CorrectivePreventiveActionCurrentStatus0").on('change',function(){
                $("#CorrectivePreventiveActionClosureRemarks").val('');
                $("#closeremarks").addClass('hide').removeClass('show');
        });
        $("#CorrectivePreventiveActionCurrentStatus1").on('change',function(){                             
                $("#closeremarks").addClass('show').removeClass('hide');
        });

        $("#CorrectivePreventiveActionProjectId").change(function(){
            $("#project_activity_id_change").load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/project_activity_id_change/" + $("#CorrectivePreventiveActionProjectId").val());
        });   



        $('.chosen-select').chosen();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#CorrectivePreventiveActionApproveForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
         $("#CorrectivePreventiveActionApproveForm").submit();
             }
        });

        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        $('#CorrectivePreventiveActionApproveForm').validate({
            rules: {
                "data[CorrectivePreventiveAction][capa_source_id]" : {
                    greaterThanZero:true,
                },
                "data[CorrectivePreventiveAction][capa_category_id]" : {
                    greaterThanZero: true,
                },
                <?php if ($this->request->data['CorrectivePreventiveAction']['capa_category_id'] == "5245a8fc-8f4c-4ab5-ab27-41f2c6c3268c") { ?>
                    "data[CorrectivePreventiveAction][internal_audit_id]" : {
                    greaterThanZero:true,
                },
                <?php } elseif ($this->request->data['CorrectivePreventiveAction']['capa_category_id'] == "5245a90d-1f4c-4693-9853-41ebc6c3268c") { ?>
                    "data[CorrectivePreventiveAction][suggestion_form_id]" : {
                        greaterThanZero:true,
                    },
                <?php } elseif ($this->request->data['CorrectivePreventiveAction']['capa_category_id'] == "5245a935-7f58-482c-83c5-41f1c6c3268c") { ?>
                    "data[CorrectivePreventiveAction][customer_complaint_id]" : {
                        greaterThanZero:true,
                    },
                <?php } elseif ($this->request->data['CorrectivePreventiveAction']['capa_category_id'] == "5245a95b-1340-4531-8d4a-4151c6c3268c") { ?>
                    "data[CorrectivePreventiveAction][supplier_registration_id]" : {
                        greaterThanZero:true,
                    },
                <?php } elseif ($this->request->data['CorrectivePreventiveAction']['capa_category_id'] == "528fcdd7-63ec-497e-b4f3-01e5c6c3268c") { ?>
                    "data[CorrectivePreventiveAction][product_id]" : {
                        greaterThanZero:true,
                    },
                <?php } elseif ($this->request->data['CorrectivePreventiveAction']['capa_category_id'] == "528fcdd7-63ec-497e-b4f3-01e5c6c3268c") { ?>
                    "data[CorrectivePreventiveAction][device_id]" : {
                        greaterThanZero:true,
                    },
                <?php } elseif ($this->request->data['CorrectivePreventiveAction']['capa_category_id'] == "53200cde-bb2c-4236-be8c-f90d51f38a45") { ?>
                    "data[CorrectivePreventiveAction][material_id]" : {
                        greaterThanZero:true,
                    }
                <?php } elseif ($this->request->data['CorrectivePreventiveAction']['capa_category_id'] == "567afe0b-019c-461e-a5eb-024adb1e6cf9") { ?>
                    "data[CorrectivePreventiveAction][env_activity_id]" : {
                        greaterThanZero:true,
                    }
                <?php } elseif ($this->request->data['CorrectivePreventiveAction']['capa_category_id'] == "5950b983-f668-49e5-a959-d26fdb1e6cf9") { ?>
                    "data[CorrectivePreventiveAction][process_id]" : {
                        greaterThanZero:true,
                    }
                <?php } ?>
            }
        });

        $("#suggestion").hide();
        $("#audit").hide();
        $("#complaint").hide();
        $("#supplier").hide();
        $("#product").hide();
        $("#device").hide();
        $("#material").hide();
        $("#process").hide();
        $("#procedure").hide();
        $("#task").hide();
        $("#activities").hide();
        $("#identification").hide();
        $("#project").hide();

        $('#CorrectivePreventiveActionCapaCategoryId').change(function () {
            if ($(this).val() != - 1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }

            $("#suggestion").hide();
            $("#complaint").hide();
            $("#supplier").hide();
            $("#product").hide();
            $("#device").hide();
            $("#material").hide();
            $("#process").hide();
            $("#procedure").hide();
            $("#task").hide();
            $("#audit").hide();
            $("#activities").hide();
            $("#identification").hide();
            $("#project").hide();

            $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionProcessId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionProcedureId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionTaskId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionProjectId').val(0).trigger('chosen:updated').rules('remove');
            $('#CorrectivePreventiveActionProjectActivityId').val(0).trigger('chosen:updated').rules('remove');
            $("#get_details :input").val(0).trigger('chosen:updated');

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
            } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "56235f1f-d0b4-49ae-9fe4-053a6c5ee721") {
                $("#process").show();
                $("#CorrectivePreventiveActionProcessId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionProcessId_chosen").width('100%');            
            } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "56235f2c-29bc-41ca-8766-05776c5ee721") {
                $("#task").show();
                $("#CorrectivePreventiveActionTaskId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionTaskId_chosen").width('100%');            
            } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "56235f1f-d0b4-49ae-9fe4-053a6c5ee721") {
                $("#process").show();
                $("#CorrectivePreventiveActionProcessId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionProcessId_chosen").width('100%');            
            } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "568be18e-88d8-45ea-8148-01dcdb1e6cf9") {
                $("#project").show();
                $("#CorrectivePreventiveActionProjectId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionProjectId_chosen").width('100%');            
                $("#CorrectivePreventiveActionProjectActivityId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionProjectActivityId_chosen").width('100%');            
            } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5950b983-f668-49e5-a959-d26fdb1e6cf9") {
                $("#project").show();
                $("#CorrectivePreventiveActionProcessId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionProcessId_chosen").width('100%');            
                // $("#CorrectivePreventiveActionProjectActivityId").rules("add", {greaterThanZero: true});
                // $("#CorrectivePreventiveActionProjectActivityId_chosen").width('100%');            
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
            }

        });

        if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5245a8fc-8f4c-4ab5-ab27-41f2c6c3268c") 
        {
                $("#audit").show();
                $("#CorrectivePreventiveActionInternalAuditId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionInternalAuditId_chosen").width('100%');
                $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove');
        
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5245a90d-1f4c-4693-9853-41ebc6c3268c") {
                $("#suggestion").show();
                $("#CorrectivePreventiveActionSuggestionFormId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionSuggestionFormId_chosen").width('100%');
                $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove');
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5245a935-7f58-482c-83c5-41f1c6c3268c") {
                $("#complaint").show();
                $("#CorrectivePreventiveActionCustomerComplaintId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionCustomerComplaintId_chosen").width('100%');
                $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove');
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5245a95b-1340-4531-8d4a-4151c6c3268c") {
                $("#supplier").show();
                $("#CorrectivePreventiveActionSupplierRegistrationId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionSupplierRegistrationId_chosen").width('100%');
                $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove');
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "528fcdd7-63ec-497e-b4f3-01e5c6c3268c") {
                $("#product").show();
                $("#CorrectivePreventiveActionProductId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionProductId_chosen").width('100%');
                $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove'); ;
                $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove');
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "533e94b8-7b70-4fad-bcdd-1a3a51f38a45") {
                $("#device").show();
                $("#CorrectivePreventiveActionDeviceId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionDeviceId_chosen").width('100%');
                $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove');
        } else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "53200cde-bb2c-4236-be8c-f90d51f38a45") {
                $("#material").show();
                $("#CorrectivePreventiveActionMaterialId").rules("add", {greaterThanZero: true});
                $("#CorrectivePreventiveActionMaterialId_chosen").width('100%');
                $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove'); 
        }else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "567afe0b-019c-461e-a5eb-024adb1e6cf9") {

                $("#activities").show();
                $("#identification").show();
                $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove');
        }else if ($('#CorrectivePreventiveActionCapaCategoryId').val() == "5950b983-f668-49e5-a959-d26fdb1e6cf9") {

                $("#process").show();
                $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvActivityIdd').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionEnvIdentificationId').val(0).trigger('chosen:updated').rules('remove');
        } else {

                $("#material").hide();
                $("#suggestion").hide();
                $("#audit").hide();
                $("#complaint").hide();
                $("#supplier").hide();
                $("#product").hide();
                $("#device").hide();
                $("#activities").hide();
                $("#identification").hide();
                $("#process").hide();
                $('#CorrectivePreventiveActionMaterialId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionInternalAuditId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSuggestionFormId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionCustomerComplaintId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionSupplierRegistrationId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProductId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionDeviceId').val(0).trigger('chosen:updated').rules('remove');
                $('#CorrectivePreventiveActionProcessId').val(0).trigger('chosen:updated').rules('remove');
        }

        $('#CorrectivePreventiveActionCapaSourceId').change(function () {
            if ($(this).val() != - 1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }
        });

        $('#CorrectivePreventiveActionProductId').change(function () {
            if ($(this).val() != - 1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionInternalAuditId').change(function () {
            if ($(this).val() != - 1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionSuggestionFormId').change(function () {
            if ($(this).val() != - 1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionCustomerComplaintId').change(function () {
            if ($(this).val() != - 1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionSupplierRegistrationId').change(function () {
            if ($(this).val() != - 1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionDeviceId').change(function () {
            if ($(this).val() != - 1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionMaterialId').change(function () {
            if ($(this).val() != - 1 && $(this).next().next('label').hasClass("error")){
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionMasterListOfFormat').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CorrectivePreventiveActionProcessId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    

    // $('#CorrectivePreventiveActionCapaCategoryId').change(function(){
    //     alert($('#CorrectivePreventiveActionCapaCategoryId').val());
    // });     
        
    




     

    $("[name='data[CorrectivePreventiveAction][document_changes_required]']").click(function(){
        docChangeRequired();
    });

        handleUserTermsChange();
        currentStatus();
        docChangeRequired();


    });


    function docChangeRequired(){
    var changeRequired = $("[name='data[CorrectivePreventiveAction][document_changes_required]']").is(':checked');
    if(changeRequired == true){
        $("#docChangeReq").show();
        $("#CorrectivePreventiveActionMasterListOfFormat_chosen").width('100%');
        $("#CorrectivePreventiveActionMasterListOfFormat").rules('add', {greaterThanZero: true});
//      $("#CorrectivePreventiveActionCurrentDocumentDetails").rules('add', {required: true});
//      $("#CorrectivePreventiveActionRequestDetails").rules('add', {required: true});
        <?php //if(!isset($this->data['CorrectivePreventiveAction']['change_addition_deletion_request_id'])){ ?>
      //  $("#CorrectivePreventiveActionReasonForChange").rules('add', {required: true});
        <?php  // } ?>
        $("#docChangeReq").find("select").prop("disabled", false).trigger('chosen:updated');
        $("[name='data[CorrectivePreventiveAction][document_changes_required]']").val(1);
    } else {
        $("#docChangeReq").hide();
//      $("#docChangeReq").find("input, textarea, select, button, select, div").val("");
//      $("#docChangeReq").find("select").prop("disabled", true).trigger('chosen:updated');
        $("[name='data[CorrectivePreventiveAction][document_changes_required]']").val(0);

        $("#CorrectivePreventiveActionMasterListOfFormat").rules('remove');
//      $("#CorrectivePreventiveActionCurrentDocumentDetails").rules("remove");
//      $("#CorrectivePreventiveActionRequestDetails").rules('remove');
//      $("#CorrectivePreventiveActionReasonForChange").rules('remove');

        $("#CorrectivePreventiveActionMasterListOfFormat").next().next('label').remove();
//      $("#CorrectivePreventiveActionReasonForChange").next('label').remove();
//      $("#CorrectivePreventiveActionCurrentDocumentDetails").next('label').remove();
//      $("#CorrectivePreventiveActionRequestDetails").next('label').remove();
    }
    }
 
 
</script>


<div id="correctivePreventiveActions_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="correctivePreventiveActions form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Edit Corrective Preventive Action'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'lists'), array('id' => 'list', 'class' => 'label btn-info')); ?>
            </h4>

            <?php echo $this->Form->create('CorrectivePreventiveAction', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>
        <?php echo $this->Form->hidden('change_addition_deletion_request_id'); ?>

            <div class="row">                
                <legend><h1>Problem Description</h1></legend>

                
                <div class="col-md-12">
                    <?php
                    echo "<label>" . __('Select Action') . "</label>";
                    echo $this->Form->input('capa_type', array('label' => false, 'legend' => false, 'value' => false, 'div' => false, 'options' => array('0' => 'Corrective Action', '1' => 'Preventive Action', '2' => 'Both'), 'type' => 'radio', 'style' => 'float:none'));
                    ?>
                </div>
                <div class="col-md-3"><?php echo $this->Form->input('name', array('label' => __('Name'))); ?></div>
                <div class="col-md-3"><?php 
                if($this->request->data['CorrectivePreventiveAction']['number'] == ''){
                     echo $this->Form->input('number', array('label' => __('Number'),'value'=>$cap_number)); 
                }else{
                    echo $this->Form->input('number', array('label' => __('Number'))); 
                }?>
                </div>
                <div class="col-md-3"><?php echo $this->Form->input('created_date', array('value'=> date('Y-m-d',strtotime($this->data['CorrectivePreventiveAction']['created'])), 'label' => __('Created Date'))); ?></div>
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
                        <div class="col-md-12"><?php echo $this->Form->input('internal_audit_id', array('style' => 'width:100%','label' => __('Select Internal Audit'))); ?></div>
                    </div>
                    <?php if($this->request->data['Process']['id']){ ?> 
                    <div class="col-md-12">
                        <strong>Process</strong> : <?php echo $this->request->data['Process']['title']; ?>                        
                        <?php echo $this->Form->hidden('process_id',array('default'=>$this->request->data['Process']['id']));?>
                    </div>
                    <?php } ?>

                    <?php if($this->request->data['RiskAssessment']['id']){ ?> 
                    <div class="col-md-12">
                        <strong>Risk</strong> : <?php echo $this->request->data['RiskAssessment']['title']; ?>
                        <?php echo $this->Form->hidden('risk_assessment_id',array('default'=>$this->request->data['RiskAssessment']['id']));?>
                    </div>
                    <?php } ?>
                    
                    <div id="suggestion">
                        <div class="col-md-6"><?php echo $this->Form->input('suggestion_form_id', array('style' => 'width:100%', 'label' => __('Select Suggestion Form'))); ?></div>
                    </div>
                    <div id="complaint">
                        <div class="col-md-6"><?php echo $this->Form->input('customer_complaint_id', array('style' => 'width:100%', 'label' => __('Select Customer Complaint'))); ?></div>
                    </div>
                    <div id="supplier">
                        <div class="col-md-6"><?php echo $this->Form->input('supplier_registration_id', array('style' => 'width:100%', 'label' => __('Select Supplier'))); ?></div>
                    </div>
                    <div id="product">
                        <div class="col-md-6"><?php echo $this->Form->input('product_id', array('style' => 'width:100%', 'label' => __('Select Product'))); ?></div>
                    </div>
                    <div id="device">
                        <div class="col-md-6"><?php echo $this->Form->input('device_id', array('style' => 'width:100%', 'label' => __('Select Device'))); ?></div>
                    </div>
                    <div id="material">
                        <div class="col-md-6"><?php echo $this->Form->input('material_id', array('style' => 'width:100%', 'label' => __('Select Material'))); ?></div>
                    </div>
                    <?php if($this->request->data['Process']['id']){ ?>
                        <div id="process">
                            <div class="col-md-6"><?php echo $this->Form->input('process_id', array('default'=>$this->request->data['Process']['id'], 'style' => 'width:100%', 'label' => __('Select Process'))); ?></div>
                        </div>
                    <?php }else{ ?> 
                        <div id="process">
                        <div class="col-md-6"><?php echo $this->Form->input('process_id', array('style' => 'width:100%', 'label' => __('Select Process'))); ?></div>
                    </div>
                    <?php } ?>
                    
                    <div id="procedure">
                        <div class="col-md-6"><?php echo $this->Form->input('procedure_id', array('style' => 'width:100%', 'label' => __('Select Procedure'))); ?></div>
                    </div>
                    <div id="task">
                        <div class="col-md-6"><?php echo $this->Form->input('task_id', array('style' => 'width:100%', 'label' => __('Select Task'))); ?></div>
                    </div>
                    <div id="identification">
                        <div class="col-md-6"><?php echo $this->Form->input('env_identification_id', array('style' => 'width:100%', 'label' => __('Select Identification details'))); ?></div>
                    </div>
                    <div id="activities">
                        <div class="col-md-6"><?php echo $this->Form->input('env_activity_id', array('style' => 'width:100%', 'label' => __('Select Activity'))); ?></div>
                    </div>
                    <div id="project">
                        <div class="col-md-6">
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
            <div class="col-md-12"><legend><h1>Initial Action</h1></legend></div>
<!--                <div class="col-md-4"><?php //echo $this->Form->hidden('raised_by', array('label' => __('Raised By'))); ?></div>-->              
                <div class="col-md-12"><?php echo $this->Form->input('initial_remarks', array('label' => __('Initial Remarks/Interim Containment Actions'))); ?></div>
            </div>
            <div class="row">
               <div class="col-md-12"> <legend><h1>Corrective Action</h1></legend></div>
                <div class="col-md-12"><?php echo $this->Form->input('proposed_immidiate_action', array('label' => __('Proposed Immediate Action'))); ?></div>
            </div>

            <div id="statusCloseDetails" class="row hide">
                
                <div class="col-md-6 text-danger"><?php echo $this->Form->input('root_cause_analysis_required', array('label' => __('Root Cause Analysis Required'))); ?></div>
          
                <div class="col-md-6 hide"><?php echo $this->Form->input('document_changes_required', array('type' => 'checkbox', 'value' => '0', 'label' => __('Document Changes Required'))); ?></div>
		<div id="docChangeReq" class="hide">
               <div class="col-md-6"><?php echo $this->Form->input('master_list_of_format', array('options' => $masterListOfFormats, 'onchange' => 'getDocDetailsRevisions(this.value)')); ?></div>      
                        <div class="row"><div class="col-md-12" id="current_details"></div></div>

		  
		    <div class="clearfix">&nbsp;</div>
		</div>
		</div>
	  
            <hr />
            <div class="row">
                <div class="col-md-12"><?php
                    if($close_status != 'hide'){
                        echo "<label>" . __('Current Status') . "</label>";
                        echo $this->Form->input('current_status', array('label' => false, 'legend' => false,  'div' => false, 'options' => array(0 => 'Open', 1 => 'Close'), 'value'=>0,  'type' => 'radio', 'style' => 'float:none','onclick' => 'currentStatus()'));    
                    }else{
                        echo "<label>" . __('Current Status') . "<small>You can not close the CAPA at this stage as someone the action items are open.</small></label>";
                        echo $this->Form->input('current_status', array('label' => false, 'legend' => false,  'div' => false, 'options' => array(0 => 'Open', 1 => 'Close'), 'value'=>0,  'type' => 'radio', 'disabled', 'style' => 'float:none','onclick' => 'currentStatus()'));
                    }                        
                    ?></div>
            
           <div class="col-md-12 hide" id="closeremarks"><?php echo $this->Form->input('closure_remarks', array('label' => 'Closure Remarks')) ?></div>
           <div class="">
            <div class="hide">
            <?php
            $i = 0;
            if($capaInvestigations){
                foreach ($capaInvestigations as $investigations) {
                    // $employees[] = $investigations['CapaInvestigation']['employee_id'];
                    // $target_date = $investigations['CapaInvestigation']['target_date'];
                    echo $this->Form->input('CapaInvestigation.'.$i.'.id',array('type'=>'text', 'value'=>$investigations['CapaInvestigation']['id']));
                    $i++;
                }
            }
            $i = 0;
            if($capaRootCauseAnalysis){
                foreach ($capaRootCauseAnalysis as $capaRootCauseAnalysi) {
                    echo $this->Form->input('CapaRootCauseAnalysi.'.$i.'.id',array('type'=>'text', 'value'=>$capaRootCauseAnalysi['CapaRootCauseAnalysi']['id']));
                    $i++;
                    // $employees_root[] = $capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_assigned_to'];
                    // $target_date_root = $capaRootCauseAnalysi['CapaRootCauseAnalysi']['target_date'];
                }
            }

            ?>    
        </div>
            <?php if($capaInvestigations){?>            
                <div class="col-md-12">
                    <div class="col-md-12"><legend><h1>Investigation History</h1></legend></div>
                          <!-- Nav tabs -->
                          <ul class="nav nav-tabs" role="tablist" >
                            <?php foreach ($capaInvestigations as $capaInvestigation) { ?>
                            <li role="presentation" class="<?php echo $capaInvestigation['CapaInvestigation']['current_status'] ? 'success' : 'danger'; ?>"><a href="#inv_<?php echo $capaInvestigation['CapaInvestigation']['id'];?>" aria-controls="inv_<?php echo $capaInvestigation['CapaInvestigation']['id'];?>" role="tab" data-toggle="tab"><?php echo $capaInvestigation['CapaInvestigation']['current_status'] ? __('Close') : __('Open'); ?></a></li>
                            <?php } ?>                
                          </ul>

                          <!-- Tab panes -->
                          <div class="tab-content">
                            <?php foreach ($capaInvestigations as $capaInvestigation) { ?>
                            <div role="tabpanel" class="tab-pane" id="inv_<?php echo $capaInvestigation['CapaInvestigation']['id'];?>">
                                <table class="table table-responsive">
                                    <tr><td width="40%"><?php echo __('Details'); ?></td>
                                        <td>
                                            <?php echo h($capaInvestigation['CapaInvestigation']['details']); ?>
                                                &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Employee Id'); ?></td>
                                        <td><?php echo $this->Html->link($capaInvestigation['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaInvestigation['Employee']['id'])); ?>
                                                &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Target Date'); ?></td>
                                        <td>
                                            <?php echo h($capaInvestigation['CapaInvestigation']['target_date']); ?>
                                                &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Proposed Action'); ?></td>
                                        <td>
                                                <?php echo h($capaInvestigation['CapaInvestigation']['proposed_action']); ?>
                                                &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Completed On Date'); ?></td>
                                        <td>
                                                <?php if($capaInvestigation['CapaInvestigation']['completed_on_date'] != '1970-01-01')echo h($capaInvestigation['CapaInvestigation']['completed_on_date']); ?>
                                                &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo nl2br('Investigation Report'); ?></td>
                                        <td>
                                                <?php echo h($capaInvestigation['CapaInvestigation']['investigation_report']); ?>
                                                &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Current Status'); ?></td>
                                        <td>
                                            <?php echo $capaInvestigation['CapaInvestigation']['current_status'] ? __('Close') : __('Open'); ?>
                                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Prepared By'); ?></td>
                                        <td><?php echo h($capaInvestigation['ApprovedBy']['name']); ?>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Approved By'); ?></td>
                                        <td><?php echo h($capaInvestigation['ApprovedBy']['name']); ?>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><?php echo __('Publish'); ?></td>
                                        <td><?php if($capaInvestigation['CapaInvestigation']['publish'] == 1) { ?>
                                                <span class="fa fa-check"></span>
                                                <?php } else { ?>
                                                <span class="fa fa-ban"></span>
                                                <?php } ?>&nbsp;
                                        </td>
                                    </tr>
                                    <!-- <tr>
                                        <td><?php echo __('Soft Delete'); ?></td>
                                        <td><?php if($capaInvestigation['CapaInvestigation']['soft_delete'] == 1) { ?>
                                                <span class="fa fa-check"></span>
                                                <?php } else { ?>
                                                <span class="fa fa-ban"></span>
                                                <?php } ?>&nbsp;
                                        </td>
                                    </tr> -->
                                </table>
                                <?php echo $this->Html->link('Edit',array('controller'=>'capa_investigations','action'=>'edit',$capaInvestigation['CapaInvestigation']['id']),array('target'=>'_blank' ,'class'=>'btn btn-sm btn-primary'));?>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
        
            <?php }else{ ?> 
                <div class="">
                    <div class="col-md-12">
                        <h3><?php echo __('Assign Investigation');?></h3>
                        <?php echo $this->Form->input('CapaInvestigation.employee_id',array('name'=>'data[CapaInvestigation][employee_id][]', 'lable'=>'Investigation Assigned To','options'=>$PublishedEmployeeList,'multiple'));?>
                        <?php echo $this->Form->input('CapaInvestigation.target_date');?>
                    </div>
                </div>
            <?php } ?>
            <?php if($capaRootCauseAnalysis){ ?> 
            <div class="col-md-12">
                <h3><?php echo __('Rootcause Analysis History');?></h3>
              <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <?php foreach($capaRootCauseAnalysis as $capaRootCauseAnalysi){ ?> 
                <li role="presentation" class=""><a href="#rootc_<?php echo $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'] ?>" aria-controls="rootc_<?php echo $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'] ?>" role="tab" data-toggle="tab"><?php echo $capaRootCauseAnalysi['CapaRootCauseAnalysi']['current_status'] ? __('Close') : __('Open'); ?></a></li>  
                <?php } ?>
              </ul>

              <!-- Tab panes -->
              <div class="tab-content">
                <?php foreach($capaRootCauseAnalysis as $capaRootCauseAnalysi){ ?> 
                    <div role="tabpanel" class="tab-pane" id="rootc_<?php echo $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id'] ?>">
                            <table class="table table-responsive">
                                <tr><td><?php echo __('Corrective Preventive Action'); ?></td>
                                <td>
                                    <?php echo $this->Html->link($capaRootCauseAnalysi['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaRootCauseAnalysi['CorrectivePreventiveAction']['id'])); ?>
                                    &nbsp;
                                </td></tr>
                                <!-- <tr><td><?php echo __('Employee'); ?></td>
                                <td>
                                    <?php echo $this->Html->link($capaRootCauseAnalysi['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaRootCauseAnalysi['Employee']['id'])); ?>
                                    &nbsp;
                                </td></tr> -->
                                <tr><td><?php echo __('Root Cause Details'); ?></td>
                                <td>
                                    <?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['root_cause_details']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Determined By'); ?></td>
                                <td>
                                    <?php echo h($capaRootCauseAnalysi['DeterminedBy']['name']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Determined On Date'); ?></td>
                                <td>
                                    <?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['determined_on_date']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Root Cause Remarks'); ?></td>
                                <td>
                                    <?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['root_cause_remarks']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Proposed Action'); ?></td>
                                <td>
                                    <?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['proposed_action']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Action Assigned To'); ?></td>
                                <td>
                                    <?php echo h($capaRootCauseAnalysi['ActionAssignedTo']['name']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Action Completed On Date'); ?></td>
                                <td>
                                    <?php if($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_completed_on_date'] != '1970-01-01')echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_completed_on_date']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Action Completion Remarks'); ?></td>
                                <td>
                                    <?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['action_completion_remarks']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Effectiveness'); ?></td>
                                <td>
                                    <?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['effectiveness']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Closure Remarks'); ?></td>
                                <td>
                                    <?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['closure_remarks']); ?>
                                    &nbsp;
                                </td></tr>
                                <tr><td><?php echo __('Current Status'); ?></td>
                                <td> <?php echo $capaRootCauseAnalysi['CapaRootCauseAnalysi']['current_status'] ? __('Close') : __('Open'); ?>
                                                &nbsp;
                                    
                                </td></tr>
                                <tr><td><?php echo __('Prepared By'); ?></td>

                            <td><?php echo h($capaRootCauseAnalysi['ApprovedBy']['name']); ?>&nbsp;</td></tr>
                                <tr><td><?php echo __('Approved By'); ?></td>

                            <td><?php echo h($capaRootCauseAnalysi['ApprovedBy']['name']); ?>&nbsp;</td></tr>
                                <tr><td><?php echo __('Publish'); ?></td>

                                <td>
                                    <?php if($capaRootCauseAnalysi['CapaRootCauseAnalysi']['publish'] == 1) { ?>
                                    <span class="fa fa-check"></span>
                                    <?php } else { ?>
                                    <span class="fa fa-ban"></span>
                                    <?php } ?>&nbsp;</td>
                        &nbsp;</td></tr>                                
                        </table>
                        <?php echo $this->Html->link('Edit',array('controller'=>'capa_investigations','action'=>'edit',$capaRootCauseAnalysi['CapaRootCauseAnalysi']['id']),array('target'=>'_blank', 'class'=>'btn btn-sm btn-primary'));?>
                    </div>
                <?php } ?>
              </div>
            </div>            
            <?php }else{ ?> 
                <div class="">
                    <div class="col-md-12">
                        <h3><?php echo __('Assign Root Cause Analysis');?></h3>
                        <?php echo $this->Form->input('CapaRootCauseAnalysi.employee_id',array('name'=>'data[CapaRootCauseAnalysi][employee_id][]', 'label'=> 'Root Cause Analysis Assigned To','options'=>$PublishedEmployeeList,'multiple'));?>
                        <?php echo $this->Form->input('CapaRootCauseAnalysi.target_date');?>
                    </div>
                </div>
            <?php } ?>        

           
           <div class="">                
                <div class="col-md-12">
                    <legend><h1>Corrective Action Validations</h1></legend>
                    <?php echo $this->Form->input('corrective_action_validations'); ?>
                                    &nbsp;
                </div>
                <div class="col-md-12">
                    <legend><h1>Preventive Actions</h1></legend>
                    <?php echo $this->Form->input('preventive_actions'); ?>
                                    &nbsp;
                </div>
            </div>
           </div>

           </div>
           <div class="row">
            <div class="col-md-4">
                <?php 
                // Configure::write('debug',1);
                // debug($this->Session->read('User.employee_id'));
                // debug($this->Session->read('User.id'));
                // debug($this->data['CorrectivePreventiveAction']['created_by']);
                if($this->Session->read('User.id') != $this->data['CorrectivePreventiveAction']['created_by']){
                    unset($this->request->data['CorrectivePreventiveAction']['capa_password']);
                }?>
                <?php echo $this->Form->input('capa_password', array('type' => 'password')); ?>
            </div>
            <div class="col-md-8">
                <br /><br /><p>You can protect CAPA beging closed by locking this record with password.</p>
            </div>
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
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#correctivePreventiveActions_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>
<script>
    $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
    });
</script>

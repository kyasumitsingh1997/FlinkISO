<div class="alert alert-warning">This change request is for quality documents only. To add change requests for evidence/other documents, navigate to that document under record, and use "Actions","Add Change Request" dropdown.</div>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script');

?>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[ChangeAdditionDeletionRequest][prepared_by]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][approved_by]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][master_list_of_format]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][branch_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][department_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][employee_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][suggestion_form_id]' ||
                    $(element).attr('name') == 'data[ChangeAdditionDeletionRequest][customer_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function(form) {
			$('#ChangeAdditionDeletionRequestProposedDocumentChanges').val(CKEDITOR.instances.ChangeAdditionDeletionRequestProposedDocumentChanges.getData());
			$('#ChangeAdditionDeletionRequestProposedWorkInstructionChanges').val(CKEDITOR.instances.ChangeAdditionDeletionRequestProposedWorkInstructionChanges.getData());
                        if($('#ChangeAdditionDeletionRequestProposedDocumentChanges').val() == '' ){
                             alert("Please enter proposed document changes");
                             return false;
                        }

                        // if($('#ChangeAdditionDeletionRequestProposedWorkInstructionChanges').val() == ''){
                        //     alert("Please enter proposed work instruction changes");
                        //      return false;
                        // }
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
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#ChangeAdditionDeletionRequestAddAjaxForm').validate({
            rules: {
                "data[ChangeAdditionDeletionRequest][master_list_of_format]": {
                    greaterThanZero: true,
                },
                "data[ChangeAdditionDeletionRequest][branch_id]": {
                    greaterThanZero: true,
                },
                "data[ChangeAdditionDeletionRequest][prepared_by]": {
                    greaterThanZero: true,
                },
            }
        });
        $('#ChangeAdditionDeletionRequestPreparedBy').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

        $('#ChangeAdditionDeletionRequestMasterListOfFormat').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestBranchId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestDepartmentId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestEmployeeId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestCustomerId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestSuggestionFormId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestFileUploadIdFormId').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ChangeAdditionDeletionRequestOthers').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });

    	functionalityChangeReq ();
	$('#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeRequired').change(function() {
	    functionalityChangeReq ();
	});
    });

    function functionalityChangeReq () {
	if ($('#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeRequired').prop('checked') == false) {
	    $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").prop("disabled", true);
	    $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").val('');
	} else {
	    $("#ChangeAdditionDeletionRequestFlinkisoFunctionalityChangeDetails").prop("disabled", false);
	}
    }

    function getDocDetailsRevisions(masterListID){
        $('#current_details').load('<?php echo Router::url('/', true); ?>master_list_of_formats/ajax_view/' + masterListID + '/1');
        $('#loadCRRevisons').load('<?php echo Router::url('/', true); ?>change_addition_deletion_requests/getRevisions/' + masterListID);
    };
</script>
<?php if(!empty($this->request->params['pass'][0])){ $masterListOfFormatID = $this->request->params['pass'][0]; ?>
<script>
    getDocDetailsRevisions('<?php echo $masterListOfFormatID; ?>');
</script>
<?php } else { $masterListOfFormatID = -1; }?>
<div id="changeAdditionDeletionRequests_ajax"> <?php echo $this->Session->flash(); ?>
	<div class="nav">
		<div class="changeAdditionDeletionRequests form col-md-8">
			<h4><?php echo __('Document Change Request'); ?></h4>
			<?php echo $this->Form->create('ChangeAdditionDeletionRequest', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
			<div class="row">
                <div class="col-md-12"><?php echo $this->Form->input('title', array('value'=>'')); ?></div>
				<div class="col-md-12"><?php echo $this->Form->input('request_from', array('default' => 'Branch', 'options' => array('Branch' => __('Branch'), 'Department' => __('Department'), 'Employee' => __('Employee'), 'Customer' => __('Customer'), 'SuggestionForm' => __('Suggestion'),'Other' => __('Other')), 'type' => 'radio')); ?></div>
				<div class="col-md-6 hidediv" id="Branch"><?php echo $this->Form->input('branch_id', array('style' => 'width:100%', 'label' => __('Branch'))); ?></div>
				<div class="col-md-6 hidediv" id="Department"><?php echo $this->Form->input('department_id', array('style' => 'width:100%', 'label' => __('Department'))); ?></div>
				<div class="col-md-6 hidediv" id="Employee"><?php echo $this->Form->input('employee_id', array('style' => 'width:100%')); ?></div>
				<div class="col-md-6 hidediv" id="Customer"><?php echo $this->Form->input('customer_id', array('style' => 'width:100%')); ?></div>
				<div class="col-md-6 hidediv" id="SuggestionForm"><?php echo $this->Form->input('suggestion_form_id', array('style' => 'width:100%', 'label' => __('Suggestion Form'))); ?></div>
                <div class="col-md-6 hidediv" id="OtherFiles"><?php echo $this->Form->input('file_upload_id', array('style' => 'width:100%', 'label' => __('Other Files'))); ?></div>

				<div class="col-md-6 hidediv" id="Other"><?php echo $this->Form->input('others', array('label' => __('Other'))); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('master_list_of_format', array('value' => $masterListOfFormatID, 'onchange' => 'getDocDetailsRevisions(this.value)')); ?></div>
			</div>
			<div class="row">
				<div class="col-md-12" id="current_details"></div>
			</div>
			<div class="row">
				<div class="col-md-12 hide" id="reason_for_change"><?php echo $this->Form->input('reason_for_change'); ?><span class="help"><?php echo __('Short description of changes required & reason for document change'); ?></span></div>
				<?php
                                    echo $this->Form->hidden('meeting_id');
                                    echo $this->Form->hidden('branchid', array('value' => $this->Session->read('User.branch_id')));
                                    echo $this->Form->hidden('departmentid', array('value' => $this->Session->read('User.department_id')));
                                    echo $this->Form->hidden('master_list_of_format_id', array('value' => $documentDetails['MasterListOfFormat']['id']));
                                ?>
			</div>
			<?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish', array('label' => __('Publish')));
                }
            ?>
			<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#changeAdditionDeletionRequests_ajax', 'async' => 'false','id'=>'submit_id')); ?> <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?> <?php echo $this->Form->end(); ?> <?php echo $this->Js->writeBuffer(); ?> </div>
		<script>
    $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly');

    $(document).ready(function() {
        $('.hidediv').hide();
        $('#Branch').show();

        $("[name='data[ChangeAdditionDeletionRequest][request_from]']").click(function() {            
            $val = this.value;            
            $('.hidediv').hide();
            $('#ChangeAdditionDeletionRequestBranchId').val(0).trigger('chosen:updated');
            $('#ChangeAdditionDeletionRequestDepartmentId').val(0).trigger('chosen:updated');
            $('#ChangeAdditionDeletionRequestEmployeeId').val(0).trigger('chosen:updated');
            $('#ChangeAdditionDeletionRequestCustomerId').val(0).trigger('chosen:updated');
            $('#ChangeAdditionDeletionRequestSuggestionFormId').val(0).trigger('chosen:updated');
            $('#ChangeAdditionDeletionRequestFileUploadIdFormId').val(0).trigger('chosen:updated');            
            $('#ChangeAdditionDeletionRequestOthers').val('');

            $('.hidediv').find('select').prop('value', -1);
            $('#' + $val).toggle();
            $('#ChangeAdditionDeletionRequest' + $val + 'Id_chosen').width('100%');

            $('#ChangeAdditionDeletionRequestBranchId').rules('remove');
            $('#ChangeAdditionDeletionRequestDepartmentId').rules('remove');
            $('#ChangeAdditionDeletionRequestEmployeeId').rules('remove');
            $('#ChangeAdditionDeletionRequestCustomerId').rules('remove');
            $('#ChangeAdditionDeletionRequestSuggestionFormId').rules('remove');
            $('#ChangeAdditionDeletionRequestFileUploadIdFormId').rules('remove');
            $('#ChangeAdditionDeletionRequestOthers').rules('remove');

            $('#ChangeAdditionDeletionRequestBranchId').next().next('label').remove();
            $('#ChangeAdditionDeletionRequestBranchId').val(0).trigger('chosen:updated');

            if ($val != 'Other') {
                $('#ChangeAdditionDeletionRequest' + $val + 'Id').rules('add', {
                    greaterThanZero: true
                });
            } else {
                $('#ChangeAdditionDeletionRequestOthers').rules('add', {
                    required: true
                });
            }
        });
    });
</script>
		<div class="col-md-4">
			<div id="loadCRRevisons"></div>
			<p><?php echo $this->element('helps'); ?></p>
		</div>
	</div>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>
</div>

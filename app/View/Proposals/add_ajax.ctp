<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="">
<?php if(isset($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'model'){ ?>
<style>
#proposal .modal-dialog {
	width: 96% !important
}
.chosen-container, .chosen-container-single, .chosen-select {
	width: 100% !important;
	min-width:100px !important;
}
</style>
<div class="modal fade" id="proposal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">Step-1:Create Proposal <small>Step:2-Approve | Step:3-Send To Customer | Step-4 : Add followups</small></h4>
			</div>
			<div class="modal-body">
				<?php } ?>
				<style>
#ui-datepicker-div{z-index:1999 !important}
</style>
<?php if($customerContacts) { ?>
				<div id="proposals_ajax"> <?php echo $this->Session->flash(); ?>
					<div class="nav">
						<div class="proposals form col-md-8"> <?php echo $this->Form->create('Proposal', array('role' => 'form', 'class' => 'form')); ?>
							<div class="row">
								<?php if(!$this->request->params['pass'][0]){ ?>
								<h4><?php echo __('Add Proposal'); ?></h4>
								<div class="col-md-6"><?php echo $this->Form->input('title',array('label'=>'Title for internal use')); ?></div>
								<div class="col-md-6"><?php echo $this->Form->input('customer_id',array('default'=>$this->request->params['named']['customer_id'],'read-only','label'=>'To')); ?></div>
							</div>
							<div class="row">
								<div class="col-md-6"><?php echo $this->Form->input('customer_contact_id'); ?></div>
								<div class="col-md-6"><?php echo $this->Form->input('employee_id', array('type' => 'select', 'options'=>$PublishedEmployeeList, 'default'=>$this->Session->read('User.employee_id'), 'label' => __('From'))); ?></div>
								<!--<div class="col-md-9"><?php echo $this->Form->input('active_lock', array('type' => 'checkbox', 'label' => __('Restrict access? <small>Once this checkbox is checked only a creator will have access to the proposal sent.</small>'))); ?></div>-->
								<div class="col-md-8"><?php echo $this->Form->input('proposal_heading',array('label'=>'Subject')); ?></div>
								<div class="col-md-4"><?php echo $this->Form->input('proposal_date',array('value'=>date('Y-m-d'))); ?></div>
								<div class="col-md-4"><?php echo $this->Form->input('proposal_cc',array('CC Proposal while seding')); ?></div>
								<div class="col-md-4"><?php echo $this->Form->input('proposal_bcc',array('BCC Proposal while seding')); ?></div>
								<div class="col-md-4"><?php echo $this->Form->input('proposal_followup_rule_id',array('CC Proposal while seding')); ?></div>
								<div class="col-md-4"><?php echo $this->Form->input('proposal_assigned_to',array('Followup Assiend To Whome?')); ?></div>
								<!--	<div class="col-md-3">
						<div class="btn-group">
							<a href="#" class="btn btn-xs btn-success">Load Template</a>
							<a href="#" class="btn btn-xs btn-warning">New Template</a>
						</div>
					  </div> --> 
                    
								<script>
 $("#ProposalProposalDate").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly'); 
</script>
								<?php }else{ ?>
								<div class="col-md-3"><?php echo $this->Form->input('title',array('label'=>'Title for internal use')); ?></div>
								<div class="col-md-3"><?php echo $this->Form->input('customer_id',array('default'=>$this->request->params['named']['customer_id'],'read-only','label'=>'To')); ?></div>
								<div class="col-md-3"><?php echo $this->Form->input('customer_contact_id'); ?></div>
								<div class="col-md-3"><?php echo $this->Form->input('employee_id', array('type' => 'select', 'options'=>$PublishedEmployeeList, 'default'=>$this->Session->read('User.employee_id'), 'label' => __('From'))); ?></div>
								<!--<div class="col-md-9"><?php echo $this->Form->input('active_lock', array('type' => 'checkbox', 'label' => __('Restrict access? <small>Once this checkbox is checked only a creator will have access to the proposal sent.</small>'))); ?></div>--> 
							</div>
							<div class="row">
								<div class="col-md-4"><?php echo $this->Form->input('proposal_heading',array('label'=>'Subject')); ?></div>
								<div class="col-md-2"><?php echo $this->Form->input('proposal_date',array('value'=>date('Y-m-d'))); ?></div>
								<div class="col-md-3"><?php echo $this->Form->input('proposal_cc',array('CC Proposal while seding')); ?></div>
								<div class="col-md-3"><?php echo $this->Form->input('proposal_bcc',array('BCC Proposal while seding')); ?></div>
								<div class="col-md-6"><?php echo $this->Form->input('proposal_followup_rule_id',array('CC Proposal while seding')); ?></div>
								<div class="col-md-6"><?php echo $this->Form->input('proposal_assigned_to',array('Followup Assiend To Whome?')); ?></div>
								<!--	<div class="col-md-3">
						<div class="btn-group">
							<a href="#" class="btn btn-xs btn-success">Load Template</a>
							<a href="#" class="btn btn-xs btn-warning">New Template</a>
						</div>
					  </div> --> 
								<script>
   
      $("#ProposalProposalDate").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly'); 
</script>
								<?php } ?>
								<div class="col-md-12">
									<h5><?php echo __('Proposal Details'); ?> <small><?php echo __('Add your proposal high-level details here. (Internal Use Only & will not be sent to customer )'); ?></small></h5>
									<?php echo $this->Form->input('proposal_details',array('class'=>false,'label'=>false)); ?> </div>
								<div class="col-md-12">
									<h5><?php echo __('Email Body'); ?> <small><?php echo __('Add what you would like to send to a customer. This will be sent as an email along with actual proposal files attached'); ?></small></h5>
									<?php echo $this->Form->input('email_body',array('class'=>false,'label'=>false)); ?> </div>
								<div class="col-md-12"><?php echo $this->Form->input('notes',array('label'=>'Notes (Internal Use)')); ?></div>
								<div class="col-md-6"><?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?></div>
								<div class="col-md-6"><?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?></div>
							</div>
							<?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
            ?>
							<?php echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#proposals_ajax', 'async' => 'false','id'=>'submit_id')); ?> <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?> <?php echo $this->Form->end(); ?> <?php echo $this->Js->writeBuffer(); ?> </div>
<?php }else { ?> 
<div id="proposals_ajax"> <?php echo $this->Session->flash(); ?>
                    <div class="nav">
                        <div class="proposals form col-md-8">
        <h3>No Customer Contacts Found</h3>
        <p>Please add Customer Contact to whom you would like to sent the proposal before adding any proposal.</p>
        <p>If you have already added any contact to this customer, make sure they are approved & published</p>
    </div>

<?php } ?>  					
                    	<div class="col-md-4">
							<p><div class="" id="getRules"></div></p>
							<p><?php echo $this->element('helps'); ?></p>
						</div>
					</div>
				</div>
				<script>
    $.ajaxSetup({
        beforeSend: function () {
            $("#busy-indicator").show();
        },
        complete: function () {
            $("#busy-indicator").hide();
        }
    });
</script>
				<?php if(isset($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'model'){ ?>
			</div>
		</div>
		<!-- /.modal-content --> 
	</div>
	<!-- /.modal-dialog --> 
</div>
<!-- /.modal --> 
</div>
<script>$('#proposal').modal();</script>
<?php } ?>
<?php echo $this->Html->script(array('ckeditor/ckeditor')); ?> 
<script type="text/javascript">
    CKEDITOR.replace('ProposalProposalDetails', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]
    });
	CKEDITOR.replace('ProposalEmailBody', {toolbar: [
            ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
            {name: 'insert', items: ['Table', 'HorizontalRule', 'SpecialChar', 'PageBreak']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']},
            {name: 'document', items: ['Preview', '-', 'Templates']},
            '/',
            {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
            {name: 'basicstyles', items: ['Bold', 'Italic']},
            {name: 'styles', items: ['Format', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']},
        ]
    });
</script> 
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?> <?php echo $this->fetch('script'); ?> 
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[Proposal][customer_id]' ||
                $(element).attr('name') == 'data[Proposal][client_id]' ||
                $(element).attr('name') == 'data[Proposal][employee_id]')
                $(element).next().after(error);
            else {
                $(element).after(error);
            }
        },
        submitHandler: function (form) {
			$('#ProposalProposalDetails').val(CKEDITOR.instances.ProposalProposalDetails.getData());
			$('#ProposalEmailBody').val(CKEDITOR.instances.ProposalEmailBody.getData());
            $(form).ajaxSubmit({
                url: '<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax',
                type: 'POST',
                target: '#proposal',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
            });
        }
    });

    $().ready(function () {
        // $('#ProposalCustomerId').change(function(){

        // });
        $("#ProposalCustomerId").change(function(){
            $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_contacts/" + $("#ProposalCustomerId").val(), function(data) {
                    $("#ProposalCustomerContactId").html(data).trigger("chosen:updated"); 
            });
            
        });

		$('#ProposalProposalFollowupRuleId').change(function(){
				$('#getRules').load('<?php echo Router::url('/', true); ?>proposal_followup_rules/view/'+ $('#ProposalProposalFollowupRuleId').val());
			});
		
        $("#submit-indicator").hide();
        jQuery.validator.addMethod("notEqual", function (value, element, param) {
            return this.optional(element) || value != param;
        }, "Please select the value");

        $('#ProposalAddAjaxForm').validate({
            rules: {
                "data[Proposal][customer_id]": {
                    notEqual: -1,
                },
                "data[Proposal][employee_id]": {
                    notEqual: -1,
                },
            }
        })
        $('#ProposalCustomerId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProposalEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script>

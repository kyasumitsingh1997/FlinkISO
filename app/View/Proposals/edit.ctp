<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?><?php echo $this->fetch('script'); ?>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[Proposal][customer_id]' ||
                $(element).attr('name') == 'data[Proposal][employee_id]')
                $(element).next().after(error);
            else {
                $(element).after(error);
            }
        }
    });

    $().ready(function () {
		$('#getRules').load('<?php echo Router::url('/', true); ?>proposal_followup_rules/view/'+ $('#ProposalProposalFollowupRuleId').val());
		$('#ProposalProposalFollowupRuleId').change(function(){
				$('#getRules').load('<?php echo Router::url('/', true); ?>proposal_followup_rules/view/'+ $('#ProposalProposalFollowupRuleId').val());
		});
			
        jQuery.validator.addMethod("notEqual", function (value, element, param) {
            return this.optional(element) || value != param;
        }, "Please select the value");

        $('#ProposalEditForm').validate({
            rules: {
                "data[Proposal][customer_id]": {
                    notEqual: -1,
                },
                "data[Proposal][employee_id]": {
                    notEqual: -1,
                },
            }
        });
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#ProposalEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#ProposalEditForm").submit();
             }
        });
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

<div id="proposals_ajax"> <?php echo $this->Session->flash(); ?>
	<div class="nav panel panel-default">
		<div class="proposals form col-md-8">
			<h4><?php echo $this->element('breadcrumbs') . __('Edit Proposal'); ?> <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?> <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?> </h4>
			<?php echo $this->Form->create('Proposal', array('role' => 'form', 'class' => 'form')); ?> <?php echo $this->Form->input('id'); ?>
			<div class="row">
				<div class="col-md-6"><?php echo $this->Form->input('title',array('label'=>'Title for internal use')); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('customer_id',array('disabled','label'=>'To')); ?></div>
			</div>
			<div class="row">
				<div class="col-md-6"><?php echo $this->Form->input('customer_contact_id'); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('employee_id', array('type' => 'select', 'options'=>$PublishedEmployeeList, 'default'=>$this->Session->read('User.employee_id'), 'label' => __('From'))); ?></div>
				<!--<div class="col-md-9"><?php echo $this->Form->input('active_lock', array('type' => 'checkbox', 'label' => __('Restrict access? <small>Once this checkbox is checked only a creator will have access to the proposal sent.</small>'))); ?></div>-->
				<div class="col-md-8"><?php echo $this->Form->input('proposal_heading',array('label'=>'Subject')); ?></div>
				<div class="col-md-4"><?php echo $this->Form->input('proposal_date',array('value'=>date('Y-m-d'))); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('proposal_cc',array('CC Proposal while seding')); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('proposal_bcc',array('BCC Proposal while seding')); ?></div>
				<div class="col-md-12"><?php echo $this->Form->input('proposal_followup_rule_id',array('CC Proposal while seding')); ?></div>
				<!--<?php
                    if ($this->request->data['Proposal']['active_lock'] == 1) {
                        $checked = true;
                    } else {
                        $checked = false;
                    }
                ?>
				<div class="col-md-4"><?php echo $this->Form->input('active_lock', array('type' => 'checkbox', 'label' => __('Restrict access?'), 'checked' => $checked)); ?></div> -->
				<div class="col-md-12">
					<h5><?php echo __('Proposal Details'); ?> <small><?php echo __('Add your proposal high-level details here. (Internal Use Only & will not be sent to customer )'); ?></small></h5>
					<?php echo $this->Form->input('proposal_details',array('class'=>false,'label'=>false)); ?> </div>
				<div class="col-md-12">
					<h5><?php echo __('Email Body'); ?> <small><?php echo __('Add what you would like to send to a customer. This will be sent as an email along with actual proposal files attached'); ?></small></h5>
					<?php echo $this->Form->input('email_body',array('class'=>false,'label'=>false)); ?> </div>
				<div class="col-md-12"><?php echo $this->Form->input('notes',array('label'=>'Notes (Internal Use)')); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?></div>
				<div class="col-md-12">
					<?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
            ?>

				</div>
			</div>		
			<?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
					echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));
					echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator'));
					echo $this->Form->end(); ?> <?php echo $this->Js->writeBuffer(); ?> </div>
		<script>
    $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
    }).attr('readonly','readonly');
</script>
		<div class="col-md-4">
			<p><div class="" id="getRules"></div></p>
			<p><?php echo $this->element('helps'); ?></p>
		</div>
	</div>
	<?php $this->Js->get('#list'); ?>
	<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#proposals_ajax'))); ?> <?php echo $this->Js->writeBuffer(); ?> </div>
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Import from file (excel & csv formats only)</h4>
			</div>
			<div class="modal-body"><?php echo $this->element('import'); ?></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
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
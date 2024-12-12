<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style>
.chosen-container, .chosen-container-single, .chosen-select { min-width:100px !important}
</style>
<div id="proposalFollowups_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav">
        <div class="proposalFollowups form col-md-8">
            <h4><?php echo __('Add Proposal Followup'); ?></h4>
            <?php echo $this->Form->create('ProposalFollowup', array('role' => 'form', 'class' => 'form', 'default' => false)); ?>
            <div class="row">
                <div class="col-md-4"><?php echo $this->Form->input('proposal_id', array('default' => $proposal_id)); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('employee_id', array('default' => $employee_id,'label'=>'Assigned To')); ?></div>
                <div class="col-md-4"><?php echo $this->Form->input('customer_contact_id', array()); ?></div>
					<div id="getProposal" class="col-md-12"></div>
					<div class="" id="getRules" class="col-md-12"></div>
				<div class="col-md-3"><?php echo $this->Form->input('followup_date',array('value'=>date('Y-m-d'))); ?></div>
				<div class="col-md-3">
				<?php 
					for($i=1; $i<=365; $i++){
						$options[$i] = $i;	
					}
				?>
				<?php echo $this->Form->input('followup_day',array('options'=>$options,'default'=>$this->request->params['named']['day'])); ?>
				</div>
				<div class="col-md-3"><?php 
				/*if($this->request->params['named']['followup_type'] == 'Email')$default = 0;
				if($this->request->params['named']['followup_type'] == 'Call')$default = 1;
				if($this->request->params['named']['followup_type'] == 'Visit')$default = 2;
				if($this->request->params['named']['followup_type'] == 'Other')$default = 3;
				if($this->request->params['named']['followup_type'] == 'Any')$default = 4;*/
				
					echo $this->Form->input('followup_type',array('options'=>array('Email'=>'Email','Call'=>'Call','Visit'=>'Visit','Other'=>'Other','Any'=>'Any'),'default'=>$default)); 
				
				?></div>
				<div class="col-md-3"><?php echo $this->Form->input('require', array('type' => 'checkbox', 'label' => __('Meeting Required?'))); ?></div>
				<div class="col-md-12"><?php echo $this->Form->input('followup_heading'); ?></div>
				
                <div class="col-md-12">
                    <?php echo $this->Form->input('followup_details'); ?>
                    <span class="help-text"><?php echo __('You can copy and paste your followup details here. You can also upload it after saving this record'); ?></span>
                </div>
                <div class="col-md-6"><?php echo $this->Form->input('next_follow_up_date'); ?></div>
				  <div class="col-md-6"><?php echo $this->Form->input('followup_assigned_to'); ?></div>
                <div class="col-md-6 pull-right"><?php echo $this->Form->input('status', array('options' => array('Open' => 'Open', 'Closed' => 'Closed', 'Pipeline' => 'Pipeline', 'Other' => 'Other'))); ?></div>
				  <div class="col-md-6"><?php echo $this->Form->input('Proposal.proposal_status', array('options' => array('1'=> 'Sent' , '2' => 'Returned' , '3' => 'Rejected', '4' => 'Approved',  '5' => 'On Hold'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('active_lock', array('type' => 'hidden', 'value' => $active_lock)); ?></div>
                <?php echo $this->Form->input('redirect', array('type' => 'hidden', 'value' => $proposal_id)); ?>
            </div>

            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
            ?>
            <?php echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success', 'update' => '#proposalFollowups_ajax', 'async' => 'false','id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>



        <div class="col-md-4">
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
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[ProposalFollowup][proposal_id]' ||
                $(element).attr('name') == 'data[ProposalFollowup][employee_id]' ||
                $(element).attr('name') == 'data[ProposalFollowup][status]')
                $(element).next().after(error);
            else {
                $(element).after(error);
            }
        },
        submitHandler: function (form) {
            $(form).ajaxSubmit({
                url: '<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax',
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
            });
        }
    });

    $().ready(function () {
		<?php if($loadData == true) { ?>
			$('#getProposal').load('<?php echo Router::url('/', true); ?>proposals/view/'+ $('#ProposalFollowupProposalId').val() + '/hideHelp:true');
			$('#getRules').load('<?php echo Router::url('/', true); ?>proposal_followup_rules/findrule/'+ $('#ProposalFollowupProposalId').val());
		<?php } ?>
		$('#ProposalFollowupProposalId').change(function(){
				$('#getProposal').load('<?php echo Router::url('/', true); ?>proposals/view/'+ $('#ProposalFollowupProposalId').val() + '/hideHelp:true');
				$('#getRules').load('<?php echo Router::url('/', true); ?>proposal_followup_rules/findrule/'+ $('#ProposalFollowupProposalId').val());
			});
        $("#submit-indicator").hide();
        jQuery.validator.addMethod("notEqual", function (value, element, param) {
            return this.optional(element) || value != param;
        }, "Please select the value");

        $('#ProposalFollowupAddAjaxForm').validate({
            rules: {
                "data[ProposalFollowup][proposal_id]": {
                    notEqual: -1
                },
                "data[ProposalFollowup][employee_id]": {
                    notEqual: -1
                },
                "data[ProposalFollowup][status]": {
                    notEqual: -1
                }
            }
        });
        $('#ProposalFollowupProposalId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProposalFollowupEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#ProposalFollowupStatus').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script>
<script> 
    $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); 
</script>

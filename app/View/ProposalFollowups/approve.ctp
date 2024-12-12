<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>

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
        }
    });

    $().ready(function () {

        jQuery.validator.addMethod("notEqual", function (value, element, param) {
            return this.optional(element) || value != param;
        }, "Please select the value");

        $('#ProposalFollowupApproveForm').validate({
            rules: {
                "data[ProposalFollowup][proposal_id]": {
                    notEqual: -1,
                },
                "data[ProposalFollowup][employee_id]": {
                    notEqual: -1,
                },
                "data[ProposalFollowup][status]": {
                    notEqual: -1,
                },
            }
        });
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#ProposalFollowupApproveForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
		 $("#ProposalFollowupApproveForm").submit();
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

<div id="proposalFollowups_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="proposalFollowups form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('Approve Proposal Followup'); ?>&nbsp;
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>

            </h4>
            <?php echo $this->Form->create('ProposalFollowup', array('role' => 'form', 'class' => 'form')); ?>
            <?php echo $this->Form->input('id'); ?>
            <div class="row">
                <div class="col-md-6"><?php echo $this->Form->input('proposal_id'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('employee_id'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('followup_date'); ?></div>
                <?php
                    if (isset($customermeeting['CustomerMeeting']['id'])) {
                        $checked = true;
                    } else {
                        $checked = false;
                    }
                ?>
                <div class="col-md-6"><?php echo $this->Form->input('require', array('type' => 'checkbox', 'label' => __('Meeting Required?'), 'checked' => $checked)); ?></div>
                <div class="col-md-12"><?php echo $this->Form->input('followup_heading'); ?></div>
                <div class="col-md-12">
                    <?php echo $this->Form->input('followup_details'); ?>
                    <span class="help-text"><?php echo __('You can copy and paste your followup details here. You can also upload it after saving this record'); ?></span>
                </div>
                <div class="col-md-6"><?php echo $this->Form->input('next_follow_up_date'); ?></div>
                <div class="col-md-6"><?php echo $this->Form->input('status', array('options' => array('Open' => 'Open', 'Closed' => 'Closed', 'Pipeline' => 'Pipeline', 'Other' => 'Other'))); ?></div>
            </div>

            <?php
                if ($showApprovals && $showApprovals['show_panel'] == true) {
                    echo $this->element('approval_form');
                } else {
                    echo $this->Form->input('publish');
                }
            ?>
            <?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit('Submit', array('div' => false, 'class' => 'btn btn-primary btn-success' ,'id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
            <?php echo $this->Form->end(); ?>
            <?php echo $this->Js->writeBuffer(); ?>
        </div>

<script>
    var startDateTextBox = $('#ProposalFollowupFollowupDate');
    var endDateTextBox = $('#ProposalFollowupNextFollowUpDate');

    startDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        timeFormat: 'HH:mm:ss',
        changeMonth: true,
        changeYear: true,
        'showTimepicker': false,
        onClose: function (dateText, inst) {
            if (endDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate) {
                    endDateTextBox.val(startDateTextBox.val());
                }
            } else {
                endDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            endDateTextBox.datepicker('option', 'minDate', startDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
    endDateTextBox.datepicker({
        format: 'yyyy-mm-dd',
      autoclose:true,
        timeFormat: 'HH:mm:ss',
        changeMonth: true,
        changeYear: true,
        'showTimepicker': false,
        onClose: function (dateText, inst) {
            if (startDateTextBox.val() != '') {
                var testStartDate = startDateTextBox.datepicker('getDate');
                var testEndDate = endDateTextBox.datepicker('getDate');
                if (testStartDate > testEndDate)
                    startDateTextBox.val(endDateTextBox.val());


            } else {

                startDateTextBox.val(dateText);
            }
        },
        onSelect: function (selectedDateTime) {
            startDateTextBox.datepicker('option', 'maxDate', endDateTextBox.datepicker('getDate'));
        }
    }).attr('readonly', 'readonly');
</script>

        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#proposalFollowups_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>
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

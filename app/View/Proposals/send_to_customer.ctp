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
		<!--	<h4><?php echo $this->element('breadcrumbs') . __('Send Proposal To Client'); ?> 
					<?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?> 
					<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?> 
		</h4> -->
		<h4>Review & Send Proposal</h4>
			<?php echo $this->Form->create('Proposal', array('role' => 'form', 'class' => 'form')); ?> <?php echo $this->Form->input('id'); ?>
			<div class="row">
				<div class="col-md-12"><h3><?php echo $this->request->data['Customer']['name']; ?>  : <small><?php echo $this->request->data['Proposal']['title']; ?></small></h3></div>			
				<div class="col-md-5"><?php echo $this->Form->input('employee_id',array('label'=>'From')); ?></div>				
				<div class="col-md-5"><?php echo $this->Form->input('customer_contact_id',array('label'=>'To')); ?></div>
				<div class="col-md-2"><?php echo $this->Form->input('proposal_date'); ?></div>
				<div class="col-md-5"><?php echo $this->Form->input('proposal_cc'); ?></div>
				<div class="col-md-5"><?php echo $this->Form->input('proposal_bcc'); ?></div>
				<div class="col-md-2"><?php echo $this->Form->input('proposal_sent_date',array('label'=>'Sent Date')); ?></div>
				<div class="col-md-12"><strong>Subject : </strong> <?php echo $this->request->data['Proposal']['proposal_heading']; ?></div>
				<div class="col-md-12"><strong>Message Body : </strong><p><?php echo $this->request->data['Proposal']['email_body']; ?></p></div>
				<div class="col-md-6"><?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?></div>
				<?php echo $this->Form->hidden('proposal_heading',array('value'=>$this->request->data['Proposal']['proposal_heading'])); ?>
				<?php echo $this->Form->hidden('proposal_details',array('value'=>$this->request->data['Proposal']['proposal_details'])); ?>
				<div class="col-md-12">
					<table class="table table-striped table-hover table-bordered table-responsive ">
						<tr>
							<th>Attach</th>
							<th>File Name</th>
							<th>Version</th>
							<th>Comment</th>
							<th>Created</th>
						</tr>
						<?php 
						$i = 0;
						foreach($files as $file):
							if($file['FileUpload']['file_status'] == 0)echo "<tr class='danger text-danger'>";
							else echo "<tr>";
							$webroot = "/ajax_multi_upload";
							$fullPath = Configure::read('MediaPath') . DS. 'files' . DS . $this->Session->read('User.company_id'). DS . $file['FileUpload']['file_dir'];
							$displayPath = '../files/'. $this->Session->read('User.company_id').'/'. str_replace(DS , '/', $file['FileUpload']['file_dir']);
							$baseEncFile = base64_encode($fullPath);
							$delUrl = "$webroot/file_uploads/delete/".$file['FileUpload']['id'];
						?>						
							<td><?php echo $this->Form->input('add_file.'. $i ,array('type'=>'checkbox','value'=>$file['FileUpload']['id'],'label'=>'Add File', array('div'=>false))); ?></td>
							<td><?php echo $this->Html->image('../ajax_multi_upload/img/fileicons/'.$file['FileUpload']['file_type'].'.png'); ?>
								<?php 
									if($file['FileUpload']['file_status'] == 1)echo $this->Html->link($file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type'],$displayPath,array('target'=>'_blank','escape'=>TRUE)); 
									else echo "<s>".$file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type']."</s>";		
								?>
							</td>
							<td><?php echo $file['FileUpload']['version']; ?></td>
							<td><?php echo $file['FileUpload']['comment']; ?></td>
							<td><?php
						            if($file['FileUpload']['file_status'] == 0)echo "Deleted ". $this->Time->niceShort($file['FileUpload']['created']);
						            else echo $this->Time->niceShort($file['FileUpload']['modified']);
						        ?>
						    </td>
						</tr>
						<?php $i++ ; endforeach; ?>
					</table>
				</div>
				<div class="col-md-12">
					<?php 
						$options = array(0 => 'Save only & Mark Record as sent to Customer',1 => 'Save and Email to Customer'); 
						echo $this->Form->input('proposal_sent_type',array('type'=>'radio','options'=>$options,'default'=>0));
					?>
					
				</div>
			</div>
			<?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
					echo $this->Form->submit(__('Send To Customer'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id'));?> 
					<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?> 
					<?php echo $this->Form->end(); ?> 
					<?php echo $this->Js->writeBuffer(); ?> 
	</div>
		<script>
    $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
    }).attr('readonly','readonly');
</script>
		<div class="col-md-4">
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

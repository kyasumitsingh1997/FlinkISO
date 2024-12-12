<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?><?php echo $this->fetch('script'); ?>
<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[Invoice][customer_id]' ||
                $(element).attr('name') == 'data[Invoice][employee_id]')
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

        $('#InvoiceSendToCustomerForm').validate({
            rules: {
                "data[Invoice][customer_id]": {
                    notEqual: -1,
                },
                "data[Invoice][employee_id]": {
                    notEqual: -1,
                },
                "data[Invoice][email_subject]": {
                    required:true,
                },
                "data[Invoice][email_body]": {
                    required:true,
                },
                "data[Invoice][invoice_sent_date]": {
                    required:true,
                },
            }
        });
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
             if($('#InvoiceSendToCustomerForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                 $("#InvoiceSendToCustomerForm").submit();
             }
        });
        $('#InvoiceCustomerId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#InvoiceEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    });
</script>

<div id="invoices_ajax"> <?php echo $this->Session->flash(); ?>
	<div class="nav panel panel-default">
		<div class="invoices form col-md-8">
			<h4><?php echo $this->element('breadcrumbs') . __('Send Invoice To Client'); ?> 
					<?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?> 
					<?php echo $this->Html->link('Edit',array('action'=>'edit',$this->request->params['pass'][0]),array('class'=>'label btn-info')); ?>
					<?php echo $this->Html->link(__('Create PDF'), array('action' => 'generate_pdf',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
					<?php echo $this->Html->link('View Invoice',array('action'=>'edit',$this->request->params['pass'][0]),array('class'=>'label btn-info')); ?>
			</h4>
			<?php echo $this->Form->create('Invoice', array('role' => 'form', 'class' => 'form')); ?> <?php echo $this->Form->input('id'); ?>
			<div class="row">
				<div class="col-md-12"><h3><?php echo $this->request->data['Customer']['name']; ?>  : <small><?php echo $this->request->data['Invoice']['invoice_number']; ?> / <?php echo $this->request->data['Invoice']['invoice_date']; ?></small></h3></div>			
				<div class="col-md-5"><?php echo $this->Form->input('employee_id',array('label'=>'From',array('default'=>$this->Session->read('employee_id')))); ?></div>				
				<div class="col-md-5"><?php echo $this->Form->input('customer_contact_id',array('label'=>'To')); ?></div>
				<div class="col-md-2"><?php echo $this->Form->input('invoice_date'); ?></div>
				<div class="col-md-5"><?php echo $this->Form->input('invoice_cc'); ?></div>
				<div class="col-md-5"><?php echo $this->Form->input('invoice_bcc'); ?></div>
				<div class="col-md-2"><?php echo $this->Form->input('invoice_sent_date',array('label'=>'Sent Date')); ?></div>
				<div class="col-md-12"><strong>Subject : </strong> <?php echo $this->Form->input('email_subject',array('label'=>false)); ?></div>
				<div class="col-md-12"><strong>Message Body : </strong><p><?php echo $this->Form->input('email_body',array('label'=>false,'type'=>'textarea')); ?></p></div>
				<div class="col-md-6"><?php echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id'))); ?></div>
				<div class="col-md-6"><?php echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id'))); ?></div>
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
	<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#invoices_ajax'))); ?> <?php echo $this->Js->writeBuffer(); ?> </div>
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

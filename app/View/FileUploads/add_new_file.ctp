<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<style type="text/css">
.tab-content{padding: 10px; display: block; border: 5px solid #ccc}
</style>
<?php echo $this->Session->flash();?>
<div class="row" id="main">
	<div class="col-md-8">
		<h2><?php echo __('Current File');?></h2>
		<div class="col-md-12">
				<table class="table table-responsive">
                 <?php
                if($fileUpload['FileUpload']['file_status'] == 0)echo "<tr class='danger text-danger'>";
                   else echo "<tr>";
                   $webroot = "/ajax_multi_upload";
                   $fullPath = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id'). DS . $fileUpload['FileUpload']['file_dir'];
                   $displayPath = base64_encode(str_replace(DS , '/', $fileUpload['FileUpload']['id']));
                   $baseEncFile = base64_encode($fullPath);
                   $delUrl = "$webroot/file_uploads/delete/".$fileUpload['FileUpload']['id'];
                   $permanentDelUrl = "$webroot/file_uploads/purge/".$fileUpload['FileUpload']['id'];
                ?>
                <tr>
                    <td>
                    <?php 
                        if($fileUpload['FileUpload']['file_status'] == 1 or $fileUpload['FileUpload']['file_status'] == 2) echo $this->Html->link('Download File',array(
                                'controller' => 'file_uploads',
                                'action' => 'view_media_file',
                                'full_base' => $displayPath
                            ), array('target'=>'_blank','escape'=>TRUE,'class'=>'btn btn-xl btn-success'));
                        else echo "<s>".$fileUpload['FileUpload']['file_details'].'.'.$fileUpload['FileUpload']['file_type']."</s>";
                    ?></td>
                    <td>
                        <?php echo $this->Html->link('Access Permissions','#dashboard_files_div',array('class'=>'btn btn-xl btn-primary', 'escape'=>FALSE,'id'=>'share_'.$fileUpload['FileUpload']['id']));
                        ?>
                    </td>
                    <div id="share_div_<?php echo $fileUpload['FileUpload']['id'];?>"></div>
                    <script>
                
                    $("#share_<?php echo $fileUpload['FileUpload']['id'];?>").on('click',function(){
                        cache: false,
                        $("#share_div_<?php echo $fileUpload['FileUpload']['id'];?>").load("<?php echo Router::url('/', true); ?>file_uploads/share/<?php echo $fileUpload['FileUpload']['id'];?>/1");
                    });

                    </script>
                </tr>
                <tr><td colspan="2"><?php echo __('Document Details'); ?></td></tr>
                <tr><td colspan="2">
                        <?php echo html_entity_decode($fileUpload['FileUpload']['file_content']); ?>
                        &nbsp;
                    </td></tr>
                <tr>
                <tr><td><?php echo __('Record'); ?></td>
                    <td>
                        <?php echo h($fileUpload['FileUpload']['record']); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('File Details'); ?></td>
                    <td>
                        <?php echo h($fileUpload['FileUpload']['file_details']); ?>.<?php echo h($fileUpload['FileUpload']['file_type']); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('File Status'); ?></td>
                    <td>
                        <?php 
                            if($fileUpload['FileUpload']['file_status'] == 0) echo "Deleted"; 
                            if($fileUpload['FileUpload']['file_status'] == 1) echo "Available"; 
                            if($fileUpload['FileUpload']['file_status'] == 2) echo "Under Revision"; 
                            if($fileUpload['FileUpload']['file_status'] == 3) echo "Upload Latest File"; 
                        ?>
                        &nbsp;
                    </td></tr>
               
                <tr><td><?php echo __('By'); ?></td>
                    <td>
                        <?php echo $this->Html->link($fileUpload['User']['name'], array('controller' => 'users', 'action' => 'view', $fileUpload['User']['id'])); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($fileUpload['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Result'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['FileUpload']['result']); ?>
                        &nbsp;
                    </td>
                </tr>
                
                <tr><td><?php echo __('Publish'); ?></td>

                    <td>
                        <?php if ($fileUpload['FileUpload']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;</td></tr>
                <tr><td><?php echo __('Branch'); ?></td>
                    <td>
                        <?php echo $this->Html->link($fileUpload['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $fileUpload['BranchIds']['id'])); ?>
                        &nbsp;
                    </td></tr>
                <tr><td><?php echo __('Department'); ?></td>
                    <td>
                        <?php echo $this->Html->link($fileUpload['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $fileUpload['DepartmentIds']['id'])); ?>
                        &nbsp;
                    </td></tr>
            </table>
		</div>
		<h2><?php echo __('Change Request Details');?></h2>
		<div class="changeAdditionDeletionRequests col-md-12">
            <table class="table table-responsive">                
                <tr><td><?php echo __('Request From'); ?></td>
                    <td>
                        <?php if (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['branch_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['branch_id'] != -1) { echo "<strong>Branch : </strong>" . h($changeAdditionDeletionRequest['Branch']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['department_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['department_id'] != -1) { echo "<strong>Department : </strong>" . h($changeAdditionDeletionRequest['Department']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['employee_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['employee_id'] != -1) { echo "<strong>Employee : </strong>" . h($changeAdditionDeletionRequest['Employee']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['customer_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['customer_id'] != -1) { echo "<strong>Customer : </strong>" . h($changeAdditionDeletionRequest['Customer']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['suggestion_form_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['suggestion_form_id'] != -1) { echo "<strong>Suggestion : </strong>" . h($changeAdditionDeletionRequest['SuggestionForm']['title']); ?>
                        <?php
			    } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others'] != ""){
				$needle = "CAPA Number: ";
				$capaCheck = strpos($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others'], $needle);
				if($capaCheck !== false){
				    $capaNumber = str_replace($needle, '', $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']);
				    echo "<strong>CAPA Number: </strong>" . $capaNumber;
				} else {
				    echo "<strong>Other : </strong>" . h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']);
				}
			    }
			?>
                    </td>
                </tr>
                <tr><td><?php echo __('Request Details'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['request_details']); ?>
                        &nbsp;
                    </td>
                </tr>
                
                    <?php if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['file_upload_id'] != -1) { ?> 
                <tr>
                    <td><?php echo __('Document Details'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['FileUpload']['file_details']); ?>.<?php echo h($changeAdditionDeletionRequest['FileUpload']['file_type']); ?>
                        &nbsp;
                    </td>
                </tr>
                    <?php }else { ?> 
                <tr>
                    <td><?php echo __('Master List Of Format'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['MasterListOfFormat']['title']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr>                    
                    <td><?php echo __('Document Number'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['MasterListOfFormat']['document_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td><?php echo __('Issue Number'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['MasterListOfFormat']['issue_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr>                    
                    <td><?php echo __('Revision Number'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['MasterListOfFormat']['revision_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td><?php echo __('Revision Date'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['MasterListOfFormat']['revision_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr>    
                    <?php } ?>
                <?php if(!isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['file_upload_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['file_upload_id'] == NULL or $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['file_upload_id'] == -1) { ?>
              	<tr>
                    <td colspan="2"><h4 class="text-primary"><?php echo __('Previouse Work Instructions'); ?></h4>
                        <?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['current_work_instructions']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><h4 class="text-primary"><?php echo __('New Work Instrcution'); ?></h4>
                        <?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes']; ?>
                        &nbsp;
                    </td>
                </tr>
                <?php } ?>
				<tr><td><?php echo __('Reason For Change'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['reason_for_change']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($changeAdditionDeletionRequest['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($changeAdditionDeletionRequest['changeAdditionDeletionRequest']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;
                </tr>
            </table>
    </div>
		<div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#newdoc" aria-controls="newdoc" role="tab" data-toggle="tab"><?php echo __('New Document Details'); ?></a></li>
                                    <li role="presentation"><a href="#currdoc" aria-controls="currdoc" role="tab" data-toggle="tab"><?php echo __('Previous Document Details'); ?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="newdoc">
                                        <?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['proposed_document_changes']; ?>
                            &nbsp;
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="currdoc">
                                        <?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['current_document_details']; ?>
                            &nbsp;
                                    </div>                                
                                </div>
                            </div>
                        </div>
		<h2><?php echo __('Add Revised File');?></h2>
		<?php echo $this->Form->create('FileUpload',array('role'=>'form','class'=>'form', 'type'=>'file', 'default'=>true));?>
		<div class="col-md-12">
                <h4><?php echo __('Upload File'); ?></h4>
                <p>You change request is approved. Please upload a new file to replace the old file.</p>
                <?php echo $this->Form->file('document', array('class'=>'btn btn-lg btn-default')); ?>
		</div>
		<div class="col-md-12"><?php echo $this->Form->input('comment',array('value'=>'Old file is replaced')); ?></div>
		<?php echo $this->Form->hidden('id', array('value'=>$fileUpload['FileUpload']['id'])); ?>
		<?php echo $this->Form->hidden('system_table_id', array('value'=>$fileUpload['FileUpload']['system_table_id'])); ?>
		<?php echo $this->Form->hidden('record', array('value'=>$fileUpload['FileUpload']['record'])); ?>
		<?php echo $this->Form->hidden('file_dir', array('value'=>$fileUpload['FileUpload']['file_dir'])); ?>
		<?php echo $this->Form->hidden('file_details', array('value'=>$fileUpload['FileUpload']['file_details'])); ?>
		<?php echo $this->Form->hidden('user_id', array('value'=>$this->Session->read('User.id'))); ?>
		<?php echo $this->Form->hidden('file_status', array('value'=>1)); ?>
		<?php echo $this->Form->hidden('archived', array('value'=>0)); ?>
		<?php echo $this->Form->hidden('version', array('value'=>$fileUpload['FileUpload']['version'])); ?>
        <?php echo $this->Form->hidden('prepared_by', array('value'=>$changeAdditionDeletionRequest['PreparedBy']['id'])); ?>
        <?php echo $this->Form->hidden('approved_by', array('value'=>$changeAdditionDeletionRequest['ApprovedBy']['id'])); ?>


		<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success')); ?>
		<?php // echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
		<?php echo $this->Form->end(); ?>
		<?php echo $this->Js->writeBuffer();?>
	</div>
	<div class="col-md-4">
			<p><?php echo $this->element('helps'); ?></p>
	</div>
</div>
<script>
    $.validator.setDefaults({
           ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[Evidence][record]' ||
                    $(element).attr('name') == 'data[Evidence][record_type]' ||
                    $(element).attr('name') == 'data[Evidence][model_name]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
     //    submitHandler: function(form) {
     //        $(form).ajaxSubmit({
     //            url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_new_file",
     //            type: 'POST',
     //            target: '#main',
     //            beforeSend: function(){
     //               $("#submit_id").prop("disabled",true);
     //                $("#submit-indicator").show();
     //            },
     //            complete: function() {
     //               $("#submit_id").removeAttr("disabled");
     //               $("#submit-indicator").hide();
     //            },
     //            error: function(request, status, error) {                    
     //                alert('Action failed!');
     //            }
	    // });
        }
    });
		$().ready(function() {
            // $("#FileUploadAddNewFileForm").chosen();
            // $("#submit-indicator").hide();
            // jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            //     return this.optional(element) || (parseFloat(value) >0);
            // }, "Please select the value");
            // jQuery.validator.addMethod("greaterThanZeroString", function(value, element) {
            //     return this.optional(element) || (value !=-1);
            // }, "Please select the value");

        $('#FileUploadAddNewFileForm').validate({
            rules: {
                // "data[Evidence][record]": {
                //     greaterThanZeroString: true,
                // },
                // "data[Evidence][model_name]": {
                //     greaterThanZeroString: true,
                // },
              
            }
        });
        //   $('#EvidenceModelName').change(function() {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });

        // $('#EvidenceRecord').change(function() {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
        // $('#EvidenceRecordType').change(function() {
        //     if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
        //         $(this).next().next('label').remove();
        //     }
        // });
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

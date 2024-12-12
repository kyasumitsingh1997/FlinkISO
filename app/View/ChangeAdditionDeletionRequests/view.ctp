<?php if(!isset($this->request->params['named']['ajaxCAPAModal'])) { ?>
<div id="changeAdditionDeletionRequests_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="changeAdditionDeletionRequests form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('View Change Request'); ?>
                <?php echo $this->Html->link(__('Back'), "javascript:history.back()",array('class'=>'btn btn-xs btn-warning')); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['document_change_accepted'] == 0 )echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->link(__('Add'), '#add', array('id' => 'add', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
			<?php if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['document_change_accepted'] == 0 ) { ?>
				<div class="alert alert-danger">Proposed changes were rejected.</div>
			<?php }?>
			<?php if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['document_change_accepted'] == 2 ) { ?>
				<div class="alert alert-danger">Proposed changes are under consideration.</div>
			<?php }?>
<?php } ?>

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
                
                <tr>
                    <td colspan="2">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#currentdocumentdetails" aria-controls="currentdocumentdetails" role="tab" data-toggle="tab"><?php echo __('Previous Document Details'); ?></a></li>
                            <li role="presentation"><a href="#proposedchanges" aria-controls="proposedchanges" role="tab" data-toggle="tab"><?php echo __('New Document Details'); ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="currentdocumentdetails">
                                <?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['current_document_details']; ?>
                                &nbsp;                 
                            </div>
                            <div role="tabpanel" class="tab-pane" id="proposedchanges">
                                <?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['proposed_document_changes']; ?>
                                &nbsp;
                            </div>        
                        </div>
                    </td>
                </tr>
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

<?php if(!isset($this->request->params['named']['ajaxCAPAModal'])) { ?>

<?php if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1 ) { ?>
<?php echo $this->element('upload-edit-cr',array('usersId'=>$changeAdditionDeletionRequest['MasterListOfFormat']['created_by'],'recordId'=>$changeAdditionDeletionRequest['MasterListOfFormat']['id'])); ?>
<?php } ?>


<!-- <p><strong>Note:</strong> Add only evedence files here. To add new version of <strong><?php echo h($changeAdditionDeletionRequest['FileUpload']['file_details']); ?>.<?php echo h($changeAdditionDeletionRequest['FileUpload']['file_type']); ?></strong>, goto "Documents", "Add New Documents", then select the correct record and add a new file. Or 
    <strong><?php echo $this->Html->link('Click Here',array('controller'=>'evidences','action'=>'lists')); ?>.</strong></p>
            <?php echo $this->element('upload-edit', array('usersId' => $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['created_by'], 'recordId' => $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'])); ?>  -->           
        </div>
        <div class="col-md-4">
			<p><?php echo $this->element('document_revisions'); ?></p>
			<p><?php echo $this->element('helps'); ?></p>
        </div>
    </div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#changeAdditionDeletionRequests_ajax'))); ?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'], 'ajax'), array('async' => true, 'update' => '#changeAdditionDeletionRequests_ajax'))); ?>
    <?php $this->Js->get('#add'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'lists', null, 'ajax'), array('async' => true, 'update' => '#changeAdditionDeletionRequests_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>

<?php } ?>

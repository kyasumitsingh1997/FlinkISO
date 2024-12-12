<style type="text/css">
.nav-tabs{max-width: 650px;}
.nav-tabs img{max-width: 650px !important;}
</style>
<div id="masterListOfFormats_ajax">
<?php echo $this->Session->flash();?>
<div class="nav panel panel-default">
<div class="alert alert-danger">Information on this page is confidential. Any attempt to right click, copy, print screen, save etc will be recorded and reported.</div>
<div class="masterListOfFormats form col-md-8">
<h4><?php echo __('View Master List Of Format'); ?>
		<?php
			if($this->request->params['pass'][1] == 1){
				echo $this->Html->link(__('List'), array('controller'=>'dashboard','action' => 'readiness'),array('id'=>'list','class'=>'label btn-info'));
			}else{
				echo $this->Html->link(__('List'), array('controller'=>'file_uploads','action' => 'quality_documents'),array('id'=>'list','class'=>'label btn-info'));
			}

		?>		
		<?php if($masterListOfFormat['MasterListOfFormat']['archived'] == 0) echo $this->Html->link(__('Edit'), array('action'=>'edit',$this->request->params['pass'][0]), array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
		<?php echo $this->Html->link(__('Create CR'), array('controller'=>'change_addition_deletion_requests','action' => 'lists',$this->request->params['pass'][0]),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4><?php if(isset($masterListOfFormat['ChangeAdditionDeletionRequest'][0]) && $masterListOfFormat['ChangeAdditionDeletionRequest'][0]['document_change_accepted'] == 2){ ?><small class="text-danger">This document is under revision</small><?php } ?>

<table class="table table-responsive">
    <caption><h4><?php echo __('Title'); ?>: <strong><?php echo h($masterListOfFormat['MasterListOfFormat']['title']); ?></strong></h4></caption>
<!--		<tr>
			<td colspan="2"><?php echo __('Title'); ?></td>
			<td colspan="6">
				<?php echo h($masterListOfFormat['MasterListOfFormat']['title']); ?>
			&nbsp;
		</td>
		</tr>
-->
		<tr>
		<td><strong><?php echo __('Standard'); ?></strong></td>
		<td>
			<?php echo h($masterListOfFormat['Standard']['name']); ?>
			&nbsp;
                </td><td><strong><?php echo __('Category'); ?></strong></td>
		<td>
			<?php echo h($masterListOfFormat['MasterListOfFormatCategory']['name']); ?>
			&nbsp;
		</td>
		</tr>
		<tr>
		<td><strong><?php echo __('Document Number'); ?></strong></td>
		<td>
			<?php echo h($masterListOfFormat['MasterListOfFormat']['document_number']); ?>
			&nbsp;
                </td><td><strong><?php echo __('Revision Number'); ?></strong></td>
		<td>
			<?php echo h($masterListOfFormat['MasterListOfFormat']['revision_number']); ?>
			&nbsp;
		</td>
		</tr>
		<tr>
                    <td><strong><?php echo __('Issue Number'); ?></strong></td>
		<td>
			<?php echo h($masterListOfFormat['MasterListOfFormat']['issue_number']); ?>
			&nbsp;
                </td><td><strong><?php echo __('Revision Date'); ?></strong></td>
		<td>
			<?php if($masterListOfFormat['MasterListOfFormat']['revision_date'] != '1970-01-01')echo h($masterListOfFormat['MasterListOfFormat']['revision_date']); ?>
			&nbsp;
		</td></tr>

		<tr>
                    <td><strong><?php echo __('Prepared By'); ?></strong></td>
		<td>
			<?php echo h($masterListOfFormat['PreparedBy']['name']); ?>
			&nbsp;
                </td><td><strong><?php echo __('Approved By'); ?></strong></td>
		<td>
			<?php echo h($masterListOfFormat['ApprovedBy']['name']); ?>
			&nbsp;
		</td>
		</tr>
		<tr>
                <td><strong><?php echo __('Archived'); ?></strong></td>
		<td>
			<?php echo h($masterListOfFormat['MasterListOfFormat']['archived']) ? __('Yes') : __('No'); ?>
			&nbsp;
		</td><td><strong><?php echo __('Publish'); ?></strong></td>

		<td>
			<?php if($masterListOfFormat['MasterListOfFormat']['publish'] == 1) { ?>
			<span class="fa fa-check text-success"></span>
			<?php } else { ?>
			<span class="fa fa-ban text-danger"></span>
			<?php } ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="1">
				<?php 
                    // echo $masterListOfFormat['MasterListOfFormat']['document_status'];
                            switch ($masterListOfFormat['MasterListOfFormat']['document_status']) {
                                case 0:
                                    echo '<div class="btn btn-xs btn-warning">'.$documentStatuses[$masterListOfFormat['MasterListOfFormat']['document_status']].'</div>';
                                    break;
                                case 1:
                                    echo '<div class="btn btn-xs btn-success">'.$documentStatuses[$masterListOfFormat['MasterListOfFormat']['document_status']].'</div>';
                                    break;
                                case 2:
                                    echo '<div class="btn btn-xs btn-danger">'.$documentStatuses[$masterListOfFormat['MasterListOfFormat']['document_status']].'</div>';
                                    break;
                                case 3:
                                    echo '<div class="btn btn-xs btn-danger">'.$documentStatuses[$masterListOfFormat['MasterListOfFormat']['document_status']].'</div>';
                                    break;

                                default:
                                    # code...
                                    break;
                            }
                        ?>
			</td>
			<td colspan="3">
				<?php if($last_version){
					echo "<span class='text-danger'>This document is either under revision or is in draft mode. To download the earlier version of the document, click ".$this->html->link('here',array('action'=>'view',$last_version['MasterListOfFormat']['id']))."</div>";
				}?>
			</td>
		</tr>
		<tr>
			<td colspan="4"><h3><?php echo __('Distribution');?></h3></td>
		</tr>
		<tr>
			<td><strong><?php echo __('Branches'); ?></strong></td>
			<td>
			<?php // echo $this->Html->link($masterListOfFormat['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $masterListOfFormat['BranchIds']['id'])); 
				foreach ($masterListOfFormat['MasterListOfFormatBranch'] as $branches) {
					echo $PublishedBranchList[$branches['branch_id']] . ', ';
				}
			?>

			&nbsp;
			</td><td><strong><?php echo __('Departments'); ?></strong></td>
			<td>
			<?php //echo $this->Html->link($masterListOfFormat['Department']['name'], array('controller' => 'departments', 'action' => 'view', $masterListOfFormat['Department']['id'])); 
				foreach ($masterListOfFormat['MasterListOfFormatDepartment'] as $departments) {
					echo $PublishedDepartmentList[$departments['department_id']] . ', ';
				}
			?>
			&nbsp;
			</td>
		</tr>
		<tr>
			<td colspan="1"><strong><?php echo __('Users'); ?></strong></td>
			<td colspan="3">
			<?php //echo $this->Html->link($masterListOfFormat['Department']['name'], array('controller' => 'departments', 'action' => 'view', $masterListOfFormat['Department']['id'])); 
				$users = json_decode($masterListOfFormat['MasterListOfFormat']['user_id'],true);
				foreach ($users as $user) {
					echo $PublishedUserList[$user] . ', ';
				}
			?>
			&nbsp;
			</td>
		</tr>
        <?php if(isset($masterListOfFormat['ChangeAdditionDeletionRequest'][0]) && $masterListOfFormat['ChangeAdditionDeletionRequest'][0]['document_change_accepted'] == 2){?>
		<?php } ?>
		<?php if(isset($parent_document) || isset($linked_documents)){ ?> 
			<tr>
				<td colspan="4">
					<table class="table table-responsive table-bordered">					
							<?php if(isset($parent_document)){ ?> 
								<tr><td colspan="9"><h3><?php echo __('Parent Document');?></h3></tr>
								<tr>
									<th><?php echo __('#')?></th>
									<th><?php echo __('Title')?></th>
									<th><?php echo __('Standard')?></th>
									<th><?php echo __('Category')?></th>
									<th><?php echo __('Issue#')?></th>
									<th><?php echo __('Issue Date')?></th>
									<th><?php echo __('Revision#')?></th>
									<th><?php echo __('Revision Date')?></th>
									<th></th>
								</tr>
								<tr>
									<td><?php echo $parent_document['MasterListOfFormat']['document_number']?></td>
									<td><?php echo $parent_document['MasterListOfFormat']['title']?></td>
									<td><?php echo $parent_document['Standard']['name']?></td>
									<td><?php echo $parent_document['MasterListOfFormatCategory']['name']?></td>
									<td><?php echo $parent_document['MasterListOfFormat']['issue_number']?></td>
									<td><?php echo $parent_document['MasterListOfFormat']['date_created']?></td>
									<td><?php echo $parent_document['MasterListOfFormat']['revision_number']?></td>
									<td><?php echo $parent_document['MasterListOfFormat']['revision_date']?></td>
									<td><?php echo $this->Html->link('View',array('action'=>'view',$parent_document['MasterListOfFormat']['id']),array('class'=>'btn btn-warning btn-xs','target'=>'_blank'));?></td>
							</tr>
							<?php } ?>
							<?php if(isset($linked_documents)){ ?> 
								<tr><td colspan="9"><h3><h3><?php echo __('Linked Documents');?></h3></tr>
									<tr>
										<th><?php echo __('#')?></th>
										<th><?php echo __('Title')?></th>
										<th><?php echo __('Standard')?></th>
										<th><?php echo __('Category')?></th>
										<th><?php echo __('Issue#')?></th>
										<th><?php echo __('Issue Date')?></th>
										<th><?php echo __('Revision#')?></th>
										<th><?php echo __('Revision Date')?></th>
										<th></th>
									</tr>
									<?php foreach($linked_documents as $linked_document) { ?>
										<tr>
											<td><?php echo $linked_document['MasterListOfFormat']['document_number']?></td>
											<td><?php echo $linked_document['MasterListOfFormat']['title']?></td>
											<td><?php echo $linked_document['Standard']['name']?></td>
											<td><?php echo $linked_document['MasterListOfFormatCategory']['name']?></td>
											<td><?php echo $linked_document['MasterListOfFormat']['issue_number']?></td>
											<td><?php echo $linked_document['MasterListOfFormat']['date_created']?></td>
											<td><?php echo $linked_document['MasterListOfFormat']['revision_number']?></td>
											<td><?php echo $linked_document['MasterListOfFormat']['revision_date']?></td>
											<td><?php echo $this->Html->link('View',array('action'=>'view',$linked_document['MasterListOfFormat']['id']),array('class'=>'btn btn-warning btn-xs','target'=>'_blank'));?></td>
										</tr>
									<?php } ?>
								<?php } ?>
				</table>

			</td>
		</tr>
		
		<?php } ?>
		<tr>
			<td colspan="4"><h3><?php echo __('Document Details');?></h3></td>
		</tr>
		<tr><td colspan="4" id="disablediv">
			<div role="tabpanel">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#document_details" aria-controls="document_details" role="tab" data-toggle="tab"><?php echo __('Document Details'); ?></a></li>
					<li role="presentation"><a href="#work_instructions" aria-controls="work_instructions" role="tab" data-toggle="tab"><?php echo __('Work Instructions'); ?></a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active panel panel-default panel-body" id="document_details">
					<?php
						if(empty($masterListOfFormat['MasterListOfFormat']['document_details'])) echo "<p><div class='alert alert-danger'>You have not updated your document details yet. Click on edit to update document details</div></p>";
						else echo $masterListOfFormat['MasterListOfFormat']['document_details'];
					?>&nbsp;</div>
					<div role="tabpanel" class="tab-pane panel panel-default panel-body" id="work_instructions"><?php
						if(empty($masterListOfFormat['MasterListOfFormat']['work_instructions'])) echo "<p><div class='alert alert-danger'>You have not updated your work instructions yet. Click on edit to update document details</div></p>";
						else echo $masterListOfFormat['MasterListOfFormat']['work_instructions'];
					?>&nbsp;</div>
				</div>
			</div>
		</td>
		</tr>

</table>
<div class="alert alert-info">Upload your document details / work instructions here. They must be identical to your current document details & work instrcutions.<br />
Note : To create change request or add updated document with new revision/issue, click on Edit button at the top and make the changes or create CR from This page. Once you change request is approved, upload a new document below.
</div>
<?php echo $this->element('upload-edit',array('usersId'=>$masterListOfFormat['MasterListOfFormat']['created_by'],'recordId'=>$masterListOfFormat['MasterListOfFormat']['id'])); ?>
<h3><?php echo __('Document History');?></h3>
<p><?php echo $this->element('document_issues'); ?></p>
<?php if(count($masterListOfFormat['ChangeAdditionDeletionRequest']) > 0){ ?>
<div class="row">
	<div class="col-md-12">
		<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
                <tr>
                    <th><?php echo __('title'); ?></th>
                    <th><?php echo __('request_from', __('Request From')); ?></th>
                    <th><?php echo __('prepared_by'); ?></th>
                    <th><?php echo __('approved_by'); ?></th>
                    <th><?php echo __('Last Updated'); ?></th>
                    <th><?php echo __('publish', __('Publish')); ?></th>
                </tr>
                <?php
                $changeAdditionDeletionRequests = $masterListOfFormat['ChangeAdditionDeletionRequest'];
                    if ($changeAdditionDeletionRequests) {
                        $x = 0;
                        foreach ($changeAdditionDeletionRequests as $changeAdditionDeletionRequest):

					if($changeAdditionDeletionRequest['document_change_accepted'] == 2){ echo "<tr class='text-warning on_page_src'>";
				?>
                    

				<?php }else{
					if($changeAdditionDeletionRequest['document_change_accepted'] == 1){ ?>
					<tr class="text-success on_page_src">
					<?php } else { ?>
					<tr class="text-danger on_page_src">
					<?php } ?>
                    
				<?php } ?>
                    <td><?php echo h($changeAdditionDeletionRequest['title']); ?>&nbsp;</td>
                    <td>
                        <?php if (isset($changeAdditionDeletionRequest['branch_id']) && $changeAdditionDeletionRequest['branch_id'] != -1) { echo "<strong>Branch:</strong><br />" . h($changeAdditionDeletionRequest['Branch']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['department_id']) && $changeAdditionDeletionRequest['department_id'] != -1) { echo "<strong>Department:</strong><br />" . h($changeAdditionDeletionRequest['Department']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['employee_id']) && $changeAdditionDeletionRequest['employee_id'] != -1) { echo "<strong>Employee:</strong><br />" . h($changeAdditionDeletionRequest['Employee']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['customer_id']) && $changeAdditionDeletionRequest['customer_id'] != -1) { echo "<strong>Customer:</strong><br />" . h($changeAdditionDeletionRequest['Customer']['name']); ?>
                        <?php } elseif (isset($changeAdditionDeletionRequest['suggestion_form_id']) && $changeAdditionDeletionRequest['suggestion_form_id'] != -1) { echo "<strong>Suggestion:</strong><br />" . h($changeAdditionDeletionRequest['SuggestionForm']['title']); ?>
                        <?php
			    } elseif (isset($changeAdditionDeletionRequest['others']) && $changeAdditionDeletionRequest['others'] != ""){
				$needle = "CAPA Number: ";
				$capaCheck = strpos($changeAdditionDeletionRequest['others'], $needle);
				if($capaCheck == 0){
				    $capaNumber = str_replace($needle, '', $changeAdditionDeletionRequest['others']);
				    echo "<strong>CAPA Number: </strong>" . $capaNumber;
				} else {
				    echo "<strong>Other : </strong>" . h($changeAdditionDeletionRequest['others']);
				}
			    }
			?>
                    </td>
                    <td><?php echo h($changeAdditionDeletionRequest['PreparedBy']['name']); ?>&nbsp;</td>
                    <td><?php echo h($changeAdditionDeletionRequest['ApprovedBy']['name']); ?>&nbsp;</td>
					<td><?php echo h($changeAdditionDeletionRequest['modified']); ?>&nbsp;</td>
                    <td width="60">
                        <?php if ($changeAdditionDeletionRequest['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;
                    </td>
                </tr>
                <!-- <tr>
                    <td colspan="2"><?php echo __('Current Document Details'); ?>&nbsp;</td>
                    <td colspan="5"><?php echo __('Proposed Document changes'); ?>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2"><?php echo $changeAdditionDeletionRequest['current_document_details']; ?>&nbsp;</td>
                    <td colspan="5"><?php echo $changeAdditionDeletionRequest['proposed_document_changes']; ?>&nbsp;</td>
                </tr> -->
                <?php
                    $x++;
                    endforeach;
                    } else {
                ?>
                    <tr><td colspan=19><?php echo __('No results found'); ?></td></tr>
                <?php } ?>
            </table>
	</div>
	</div>
<?php } ?>

</div>
<div class="col-md-4">	
	<h3><?php echo __('Document Revisions');?></h3>
	<p><?php echo $this->element('document_revisions'); ?></p>
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>

<?php echo $this->Js->get('#list');?>
<?php if($this->request->params['pass'][1] == 1){
	echo $this->Js->event('click',$this->Js->request(array('controller'=>'dashboards','action' => 'readiness'),array('async' => true, 'update' => '#masterListOfFormats_ajax')));
}else{
	echo $this->Js->event('click',$this->Js->request(array('controller'=>'file_uploads','action' => 'quality_documents','standard_id'=>$masterListOfFormat['Standard']['id']),array('async' => true, 'update' => '#masterListOfFormats_ajax')));
}
?>
<?php echo $this->Js->writeBuffer();?>

</div>
<script type="text/javascript">
	
$(document).ready(function() {
	// $("#disablediv :input").prop("disabled", true);
	// $(document).on( "keydown", function(e) {		
	// 	$(window).bind('contextmenu', false);		
	// 	  // if(e.which == '16' || e.which == '52' || e.which == '44'  || e.which == '17' ||  e.which == '65' ||  e.which == '83'){
	// 	if(e.which < 48 || e.which > 90 ){  	
	// 	  	$(".content").html('Action is not allowed.\n\nThis incident will be reported. Click <?php echo $this->Html->link("here",array("action"=>"view",$this->request->params["pass"][0]),array("class"=>"btn btn-xs inline btn-primary"));?> to refresh the page.');
	// 	  	e.preventDefault();
	// 	  	return false;
	// 	  }
	// 	});

	// $(document).bind('keydown keypress', 'ctrl+s', function () {
 //        $('#save').click();
 //        return false;
 //    });

 //    $(document).bind('copy', function(e) {
 //        $(".content").html('Action is not allowed.\n\nThis incident will be reported. Click <?php echo $this->Html->link("here",array("action"=>"view",$this->request->params["pass"][0]),array("class"=>"btn btn-xs inline btn-primary"));?> to refresh the page.');
 //        e.preventDefault();
 //    }); 
 //    $(document).bind('paste', function() {
 //        $(".content").html('Action is not allowed.\n\nThis incident will be reported. Click <?php echo $this->Html->link("here",array("action"=>"view",$this->request->params["pass"][0]),array("class"=>"btn btn-xs inline btn-primary"));?> to refresh the page.');
 //        e.preventDefault();
 //    }); 
 //    $(document).bind('cut', function() {
 //        $(".content").html('Action is not allowed.\n\nThis incident will be reported. Click <?php echo $this->Html->link("here",array("action"=>"view",$this->request->params["pass"][0]),array("class"=>"btn btn-xs inline btn-primary"));?> to refresh the page.');
 //        e.preventDefault();
 //    });
 //    $(document).bind('contextmenu', function(e) {
 //        $(".content").html('Action is not allowed.\n\nThis incident will be reported. Click <?php echo $this->Html->link("here",array("action"=>"view",$this->request->params["pass"][0]),array("class"=>"btn btn-xs inline btn-primary"));?> to refresh the page.');
 //        e.preventDefault();
 //    });
 //    $(document).bind('clipboard', function(e) {
 //        $(".content").html('Action is not allowed.\n\nThis incident will be reported. Click <?php echo $this->Html->link("here",array("action"=>"view",$this->request->params["pass"][0]),array("class"=>"btn btn-xs inline btn-primary"));?> to refresh the page.');
 //        e.preventDefault();
 //    });

}); 
	


</script>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

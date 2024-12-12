<div id="fileUploads_ajax">
	<?php echo $this->Session->flash(); ?>
	<div class="nav panel panel-default">
		<div class="fileUploads form col-md-8">
			<h4><?php echo __('View File Upload'); ?>		
				<?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
				<?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
				<?php //echo $this->Html->link(__('Add'), '#add', array('id' => 'add', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
				<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
			</h4>
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
						<?php echo $this->Html->link('Access Permissions','#dashboard_files_div',array('class'=>'btn btn-xl btn-primary', 'escape'=>FALSE,'id'=>'share_'.$fileUpload['FileUpload']['id']));?>
					</td>
					<div id="share_div_<?php echo $fileUpload['FileUpload']['id'];?>"></div>
					<script>
	
					$("#share_<?php echo $fileUpload['FileUpload']['id'];?>").on('click',function(){
						cache: false,
						$("#share_div_<?php echo $fileUpload['FileUpload']['id'];?>").load("<?php echo Router::url('/', true); ?>file_uploads/share/<?php echo $fileUpload['FileUpload']['id'];?>/1");
					});

				</script>
			</tr>
			<?php if($fileUpload['FileUpload']['file_content']){ ?>
				<tr><td colspan="2"><?php echo __('Document Details'); ?></td></tr>
				<tr><td colspan="2"><?php echo html_entity_decode($fileUpload['FileUpload']['file_content']); ?>&nbsp;</td></tr>
			<?php } ?>
			
			<tr><td><?php echo __('Record'); ?></td><td><?php //echo $recordDetails['model'];?>-<?php echo $recordDetails['display'];?>&nbsp;</td></tr>
			<tr><td><?php echo __('File Name'); ?></td><td><?php echo h($fileUpload['FileUpload']['file_details']); ?>.<?php echo h($fileUpload['FileUpload']['file_type']); ?>&nbsp;</td></tr>
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
				</td>
			</tr>
			<tr><td><?php echo __('Prepared By'); ?></td>
				<td><?php echo h($fileUpload['PreparedBy']['name']); ?>&nbsp;</td>
			</tr>
			<tr><td><?php echo __('Approved By'); ?></td>
				<td><?php echo h($fileUpload['ApprovedBy']['name']); ?>&nbsp;</td>
			</tr>
			<tr><td><?php echo __('Result'); ?></td>
				<td><?php echo h($fileUpload['FileUpload']['result']); ?>&nbsp;</td>
			</tr>
			<tr><td><?php echo __('Publish'); ?></td>
				<td>
					<?php if ($fileUpload['FileUpload']['publish'] == 1) { ?>
									<span class="fa fa-check"></span>
					<?php } else { ?>
									<span class="fa fa-ban"></span>
					<?php } ?>&nbsp;</td>
				&nbsp;
				</td>
			</tr>
			<tr><td><?php echo __('Branch'); ?></td>
				<td>
				<?php echo $this->Html->link($fileUpload['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $fileUpload['BranchIds']['id'])); ?>
				&nbsp;
				</td>
			</tr>
			<tr><td><?php echo __('Department'); ?></td>
				<td>
				<?php echo $this->Html->link($fileUpload['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $fileUpload['DepartmentIds']['id'])); ?>
				&nbsp;
				</td>
			</tr>
			</table>
			
			<h4><?php echo __('File shared with');?></h4>
			<div>
				<table class="table table-responsive table-bordered"> 
				<?php 
					foreach ($fileUpload['FileShare'] as $share) { ?>
						<tr><td width="20%"><?php echo $PublishedBranchList[$share['branch_id']] ;?></td><td>
						<?php 
						if($share['everyone'] == 1 ){
							echo "Everyone";
						}elseif($share['everyone'] == 0 && ($share['users'] == '""')){
							echo "None";
						}else{
							foreach (json_decode($share['users'],true) as $users) {
								echo $PublishedUserList[$users] .', ';
							}
						}                                    
						?>
							</td>
						</tr>
						<?php } ?>
				</table>
			</div>
			<h4><?php echo __('File view history');?></h4>
			<div>
				<table class="table table-responsive table-bordered"> 
				<?php 
					foreach ($fileUpload['FileView'] as $view) { ?>
						<tr>
							<td width="20%"><?php echo $PublishedUserList[$view['user_id']] ;?></td>
							<td><?php echo $view['created'] ;?></td>
							<td><?php echo $view['type'] ;?></td>
						</tr>
					<?php } ?>
				</table>
			</div>
			
			<h4><?php echo __('Revisions');?></h4>
			<div class="table-responsive">
					<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
						<tr>                                    
							<th><?php echo __('File Name'); ?></th>
							<th><?php echo __('Ext'); ?></th>
							<th><?php echo __('Version'); ?></th>
							<th><?php echo __('System Table'); ?></th>
							<th><?php echo __('User'); ?></th>                   
							<th><?php echo __('File Status'); ?></th>
							<th><?php echo __('Archived'); ?></th>
							<th><?php echo __('publish', __('Publish')); ?></th>
							<th></th>
						</tr>
			<?php 
			if($archived){ ?>
				<?php $x=0;
					foreach ($archived as $fileUpload): ?>
						<tr class="on_page_src">
							<td>
								<?php //$displayPath = Router::url('/').'files/'.$this->Session->read('User.company_id').'/'.$fileUpload['FileUpload']['file_dir'];
									$displayPath = base64_encode(str_replace(DS , '/', $fileUpload['FileUpload']['id']));
								?>
								<!-- <a href="<?php echo $displayPath;?>"> <?php echo $fileUpload['FileUpload']['file_details']; ?> </a>-->
								<?php echo  $this->Html->link($fileUpload['FileUpload']['file_details'], array(
										'controller' => 'file_uploads',
										'action' => 'view_media_file',
										'full_base' => $displayPath
										),array('target'=>'_blank','escape'=>TRUE)); ?>
							&nbsp; </td>
							<td><?php echo h($fileUpload['FileUpload']['file_type']); ?>&nbsp;</td>
							<td><?php echo h($fileUpload['FileUpload']['version']); ?>&nbsp;</td>
							<td><?php 
									if($fileUpload['FileUpload']['system_table_id'] != 'dashboards') echo h($fileUpload['SystemTable']['name']); 
									else echo $fileUpload['FileUpload']['system_table_id'];
									?>&nbsp;
							</td>
							<td>
								<?php echo $this->Html->link($fileUpload['User']['name'], array('controller' => 'users', 'action' => 'view', $fileUpload['User']['id'])); ?>
							</td>       
							<td><?php echo ($fileUpload['FileUpload']['file_status'])?'Available':'Deleted'; ?>&nbsp;</td>
							<td><?php echo ($fileUpload['FileUpload']['archived'])?'Yes':'No'; ?>&nbsp;</td>
							<!-- <td width="60">
								<?php echo $this->Html->link('View',array('action'=>'view',$fileUpload['FileUpload']['id']),array('class'=>'btn btn-xs btn-warning','target'=>'_blank'));?>
							</td> -->
							<td width="60">
								<?php if($fileUpload['FileUpload']['publish'] == 1) { ?>
									<span class="fa fa-check"></span>
								<?php } else { ?>
									<span class="fa fa-ban"></span>
									<?php } ?>&nbsp;</td>
							</tr>
						<?php $x++;
					endforeach; ?>
				<?php }else{ ?>
					<tr><td colspan=19><?php echo __('No results found');?></td></tr>
				<?php } ?>
			</table>
		</div>
	</table>
	<h4>Change Additin Deletion</h4>
	<div class="table-responsive">
		<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
			<tr>
				<th></th>
				<th><?php echo __('title'); ?></th>
				<th><?php echo __('Request From'); ?></th>
				<th><?php echo __('Master List of Format'); ?></th>
				<th><?php echo __('Prepared By'); ?></th>
				<th><?php echo __('Approved By'); ?></th>
				<th><?php echo __('Last Updated'); ?></th>
				<th><?php echo __('publish', __('Publish')); ?></th>
			</tr>
		<?php
		if ($changeAdditionDeletionRequests) {
			$x = 0;
				foreach ($changeAdditionDeletionRequests as $changeAdditionDeletionRequest):
					if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['document_change_accepted'] == 2){ echo "<tr class='text-warning on_page_src'>";
				?>
						<td class=" actions">
							<?php echo $this->Html->link('Edit',array('controller'=>'change_addition_deletion_requests','action'=>'edit',$changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id']));?>
						</td>
					<?php }else{
						if($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['document_change_accepted'] == 1){ ?>
							<tr class="text-success on_page_src">
						<?php } else { ?>
							<tr class="text-danger on_page_src">
						<?php } ?>
					<td class=" actions">
						<div class="btn-group" >
							<?php echo $this->Html->link('View',array('controller'=>'change_addition_deletion_requests', 'action'=>'view',$changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id']),array('class'=>'btn  btn-sm btn-default ')); ?>
						<?php
							$path = Configure::read('MediaPath') . 'files/' . $this->Session->read('User.company_id') . '/upload/' . $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['created_by'] . '/' . $this->params->controller . '/' . $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'] . '/';
							$dir = new Folder($path);
							$files = $dir->read(true);

							if (count($files[1]) > 0) { ?>
								<button type="button" class="btn btn-sm btn-success" style="border-bottom-right-radius:3px; border-top-right-radius:3px; border-left:0px " id='<?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'] ?>-count' data-toggle='tooltip' data-original-title='<?php echo count($files[1]) ?> Evidence Uploaded'>&nbsp;<?php echo count($files[1]) ?></button>
							<?php } else { ?>
								<button type="button" class="btn btn-sm btn-default" style="border-bottom-right-radius:3px; border-top-right-radius:3px; border-left:0px " id='<?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'] ?>-count' data-toggle='tooltip' data-original-title='0 Evidence Uploaded'>&nbsp;0</button>
							<?php } ?>
						</div>
						<script>$('#<?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id'] ?>-count').tooltip();</script>
					</td>
				<?php } ?>
					<td><?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['title']); ?>&nbsp;</td>
					<td>
						<?php if (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['branch_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['branch_id'] != -1) { echo "<strong>Branch:</strong><br />" . h($changeAdditionDeletionRequest['Branch']['name']); ?>
						<?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['department_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['department_id'] != -1) { echo "<strong>Department:</strong><br />" . h($changeAdditionDeletionRequest['Department']['name']); ?>
						<?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['employee_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['employee_id'] != -1) { echo "<strong>Employee:</strong><br />" . h($changeAdditionDeletionRequest['Employee']['name']); ?>
						<?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['customer_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['customer_id'] != -1) { echo "<strong>Customer:</strong><br />" . h($changeAdditionDeletionRequest['Customer']['name']); ?>
						<?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['suggestion_form_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['suggestion_form_id'] != -1) { echo "<strong>Suggestion:</strong><br />" . h($changeAdditionDeletionRequest['SuggestionForm']['title']); ?>
						<?php
							} elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others'] != ""){
							$needle = "CAPA Number: ";
							$capaCheck = strpos($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others'], $needle);
							if($capaCheck == 0){
								$capaNumber = str_replace($needle, '', $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']);
								echo "<strong>CAPA Number: </strong>" . $capaNumber;
							} else {
								echo "<strong>Other : </strong>" . h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']);
							}
						} ?>
					</td>
					<td><?php echo h($changeAdditionDeletionRequest['MasterListOfFormat']['title']); ?>&nbsp;</td>                    
					<td><?php echo h($changeAdditionDeletionRequest['PreparedBy']['name']); ?>&nbsp;</td>
					<td><?php echo h($changeAdditionDeletionRequest['ApprovedBy']['name']); ?>&nbsp;</td>
					<td><?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['modified']); ?>&nbsp;</td>
					<td width="60">
						<?php if ($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['publish'] == 1) { ?>
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
				<td colspan="2"><?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['current_document_details']; ?>&nbsp;</td>
				<td colspan="5"><?php echo $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['proposed_document_changes']; ?>&nbsp;</td>
				</tr> -->
			<?php
				$x++;
			endforeach;
		} else { ?>
			<tr><td colspan=19><?php echo __('No results found'); ?></td></tr>
		<?php } ?>
	</table>
</div>
<?php  // echo $this->element('upload-edit', array('usersId' => $fileUpload['FileUpload']['created_by'], 'recordId' => $fileUpload['FileUpload']['id'])); ?>
</div>
	<div class="col-md-4"><p><?php echo $this->element('helps'); ?></p></div>
	</div>
	<?php echo $this->Js->get('#list'); ?>
	<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#fileUploads_ajax'))); ?>

	<?php echo $this->Js->get('#edit'); ?>
	<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $fileUpload['FileUpload']['id'], 'ajax'), array('async' => true, 'update' => '#fileUploads_ajax'))); ?>

	<?php echo $this->Js->get('#add'); ?>
	<?php echo $this->Js->event('click', $this->Js->request(array('action' => 'lists', null, 'ajax'), array('async' => true, 'update' => '#fileUploads_ajax'))); ?>

	<?php echo $this->Js->writeBuffer(); ?>
</div>
<script>$.ajaxSetup({beforeSend: function() {$("#busy-indicator").show();}, complete: function() {$("#busy-indicator").hide();}});</script>

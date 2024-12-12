<?php if($this->request->params['pass'][1] != 1 ) { ?> 
	<div id="processes_ajax">
		<?php echo $this->Session->flash();?>	
		<div class="nav panel panel-default">
			<div class="processes form col-md-8">
				<h4><?php echo __('View Process'); ?>						
				<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
					<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
					<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
					<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>
		<?php }  ?>		
<table class="table table-responsive">
		<tr><td width="20%"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($process['Process']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Objective'); ?></td>
		<td>
			<?php echo $this->Html->link($process['Objective']['title'], array('controller' => 'objectives', 'action' => 'view', $process['Objective']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Clauses'); ?></td>
		<td>
			<?php echo h($process['Process']['clauses']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process Requirement'); ?></td>
		<td>
			<?php echo h($process['Process']['process_requirments']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Input Process'); ?></td>
		<td>
			<?php echo $this->Html->link($process['InputProcess']['title'], array('controller' => 'input_processes', 'action' => 'view', $process['InputProcess']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Output Process'); ?></td>
		<td>
			<?php echo $this->Html->link($process['OutputProcess']['title'], array('controller' => 'output_processes', 'action' => 'view', $process['OutputProcess']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Schedule'); ?></td>
		<td>
			<?php echo $this->Html->link($process['Schedule']['name'], array('controller' => 'schedules', 'action' => 'view', $process['Schedule']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($process['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($process['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($process['Process']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($process['Process']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<h2><?php echo __('Process Team'); ?></h2>
<table class="table table-responsive">
	<?php foreach($process['ProcessTeam'] as $team) : ?>  
	<tr><td width="20%"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($team['name']); ?>
			&nbsp;
		</td>
	</tr>
	<tr><td><?php echo __('Owner'); ?></td>
		<td>
			<?php echo h($PublishedUserList[$team['owner_id']]); ?>
			&nbsp;
		</td>
	</tr>
	<tr><td><?php echo __('Team'); ?></td>
		<td>
			<?php 
				$process_teams = json_decode($team['team']); 
				foreach ($process_teams as $process_team) {
					echo h($PublishedUserList[$process_team]) . ', ';
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr><td><?php echo __('Branch'); ?></td>
		<td>
			<?php 
				$branches = json_decode($team['branch_id']); 
				foreach ($branches as $branch) {
					echo h($PublishedBranchList[$branch]) . ', ';
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr><td><?php echo __('Department'); ?></td>
		<td>
			<?php 
				$departments = json_decode($team['department_id']); 
				foreach ($departments as $department) {
					echo h($PublishedDepartmentList[$department]) . ', ';
				}
			?>
			&nbsp;
		</td>
	</tr>
	<tr><td><?php echo __('Target'); ?></td>
		<td>
			<?php echo h($team['target']); ?>
			&nbsp;
		</td>
	</tr>
	<tr><td><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo date('Y-m-d',strtotime($team['start_date'])); ?>
			&nbsp;
		</td>
	</tr>
	<tr><td><?php echo __('End Date'); ?></td>
		<td>
			<?php echo date('Y-m-d',strtotime($team['end_date'])); ?>
			&nbsp;
		</td>
	</tr>
	
</table>
<?php endforeach ?>
<h2><?php echo __('Process Related Tasks'); ?></h2>
<?php foreach ($process['Task'] as $task) : ?>
	<table class="table table-responsive">
		<tr>
			<td width="20%"><?php echo __('Tasks'); ?></td>
			<td>
				<?php echo h($task['name']); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Assigned To'); ?></td>
			<td>
				<?php echo h($schedules[$task['user_id']]); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Task Description'); ?></td>
			<td>
				<?php echo h($task['description']); ?>
				&nbsp;
			</td>
		</tr>
		<tr>
			<td><?php echo __('Task Schedule'); ?></td>
			<td>
				<?php echo h($schedules[$task['schedule_id']]); ?>
				&nbsp;
			</td>
		</tr>
		<tr><td><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo date('Y-m-d',strtotime($task['start_date'])); ?>
			&nbsp;
		</td>
	</tr>
	<tr><td><?php echo __('End Date'); ?></td>
		<td>
			<?php echo date('Y-m-d',strtotime($task['end_date'])); ?>
			&nbsp;
		</td>
	</tr>
	</table>	
<?php endforeach; ?>



<?php if($this->request->params['pass'][1] != 1 ) { ?> 
	<?php echo $this->element('upload-edit', array('usersId' => $process['Process']['created_by'], 'recordId' => $process['Process']['id'])); ?>
	<?php 
		
	?>
<?php
	echo "<div class='row'>";	
			foreach ($files as $file) {
				if(
					$file['FileUpload']['file_type'] == 'jpg' ||
					$file['FileUpload']['file_type'] == 'JPG' || 
					$file['FileUpload']['file_type'] == 'jpeg' || 
					$file['FileUpload']['file_type'] == 'png' || 
					$file['FileUpload']['file_type'] == 'gif'
					){
					$fullPath = Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id'). DS . $file['FileUpload']['file_dir'];
					mkdir(WWW_ROOT . DS  . 'img' . DS . 'tmp', 0777 );
					$newPath = WWW_ROOT . 'img' . DS . 'tmp' . DS . $file['FileUpload']['file_details'] .'.'.$file['FileUpload']['file_type'];
				// 	echo $newPath;
				 	copy($fullPath, $newPath);
					echo $this->Thumbnail->render($newPath, 
										array(
											'path' => '',
											'width' => '150',
											'height' => '150',
											'resize' => 'exact',
											'quality' => '100'), 
										array(
											'id' => '', 
											'class'=>'lazy',
											//'data-original'=>WWW_ROOT .'files/Listing/'.$listing['Listing']['id'].'/'.$files[0],
											'alt' => 'nil', 
											'title' => 'HIL'));
					echo "<div class='col-md-6'>" .  $this->Html->image('tmp' . DS . $file['FileUpload']['file_details'] .'.'.$file['FileUpload']['file_type'],array('width'=>'100%')) ."</div>";	
				 }
				
				
			}
	echo "</div>";
?>
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#processes_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$process['Process']['id'] ,'ajax'),array('async' => true, 'update' => '#processes_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
<?php }  ?>	

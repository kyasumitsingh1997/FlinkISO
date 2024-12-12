<div id="projectQueries_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projectQueries form col-md-8">
<h4><?php echo __('View Project Query'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($projectQuery['ProjectQuery']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Query Type'); ?></td>
		<td>
			<?php echo $this->Html->link($projectQuery['QueryType']['name'], array('controller' => 'query_types', 'action' => 'view', $projectQuery['QueryType']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($projectQuery['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectQuery['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Milestone'); ?></td>
		<td>
			<?php echo $this->Html->link($projectQuery['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectQuery['Milestone']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project File'); ?></td>
		<td>
			<?php echo $this->Html->link($projectQuery['ProjectFile']['name'], array('controller' => 'project_files', 'action' => 'view', $projectQuery['ProjectFile']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project Process Plan'); ?></td>
		<td>
			<?php echo $this->Html->link($projectQuery['ProjectProcessPlan']['process'], array('controller' => 'project_process_plans', 'action' => 'view', $projectQuery['ProjectProcessPlan']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($projectQuery['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $projectQuery['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Sent To'); ?></td>
		<td>
			<?php echo h($PublishedEmployeeList[$projectQuery['ProjectQuery']['sent_to']]); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Query'); ?></td>
		<td>
			<?php echo h($projectQuery['ProjectQuery']['query']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Status'); ?></td>
		<td>
			<?php echo h($currentStatuses[$projectQuery['ProjectQuery']['current_status']]); ?>
			&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<h3>Files</h3>
			<?php 
				$folder_path = WWW_ROOT.'img'. DS . 'files' . DS . $this->Session->read('User.company_id'). DS . 'qurery_file' . DS . $projectQuery['ProjectQuery']['id'];
                $dir = new Folder($folder_path);
                $all_files = $dir->find();
                
                if($all_files){
                echo "<div class='row'>";
                foreach($all_files as $file){                       
                	$ffile = New File($file);
                	// debug($ffile);
                	$ff =$ffile->info();
                	$file_path = $file_folder_path . DS . $file;
                	if($ff['extension'] == 'jpg' || $ff['extension'] == 'jpeg' || $ff['extension'] == 'png' || $ff['extension'] == 'gif'){		                    		
                		echo "<div class='col-md-4'>" . $this->Html->image('files' . DS . $this->Session->read('User.company_id'). DS . 'qurery_file' . DS . $projectQuery['ProjectQuery']['id'] . DS .$file,array('fullBase' => true,'class'=>'img-responsive img-rounded')) . "</div>";
                	}else{

                	}

                	
				}
				echo "</div>";
				echo "<h4>Download Files</h4>";
				echo "<ul class='list-group'>";
                foreach($all_files as $file){                       
                	$ffile = New File($file);
                	$ff =$ffile->info();
                	$file_path = $file_folder_path . DS . $file;
                	if($ff['extension'] == 'jpg' || $ff['extension'] == 'jpeg' || $ff['extension'] == 'png' || $ff['extension'] == 'gif'){

                	}else{
                		echo "<li class='list-group-item'><h5>" . 
	                        $this->Html->link($file, array(
	                            'controller' => 'file_uploads',
	                            'action' => 'view_document_file',
	                            'file_name' => $file,
	                            'full_base' => base64_encode(str_replace(Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS ,'',$file_path)),
	                        ),array('target'=>'_blank','escape'=>TRUE)).
	                        "</h5></li>";
                	}
				}
                echo "</ul>";
            }
			?>
		</td>
	</tr>
		<!-- <tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($projectQuery['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($projectQuery['ApprovedBy']['name']); ?>&nbsp;</td></tr> -->
	<!-- <tr><td><?php echo __('Publish'); ?></td>

	<td>
	<?php if($projectQuery['ProjectQuery']['publish'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
	<tr><td><?php echo __('Soft Delete'); ?></td>

	<td>
	<?php if($projectQuery['ProjectQuery']['soft_delete'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr> -->
</table>
	<h3>Responses</h3>
		
			<?php if($projectQueryResponses){ ?>
				<?php foreach ($projectQueryResponses as $projectQueryResponse): ?>
					<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
						<thead><h4><?php echo h($projectQueryResponse['ProjectQueryResponse']['name']); ?></h4></thead>
						<!-- <tr><th><?php echo __('Name'); ?></th><td><?php echo h($projectQueryResponse['ProjectQueryResponse']['name']); ?>&nbsp;</td></tr> -->
						<tr><th><?php echo __('Level'); ?></th><td><?php echo h($projectQueryResponse['ProjectQueryResponse']['level']); ?>&nbsp;</td></tr>
						<tr><th><?php echo __('By'); ?></th><td><?php echo h($PublishedEmployeeList[$projectQueryResponse['ProjectQueryResponse']['raised_by']]); ?>&nbsp;</td></tr>
						<tr><th><?php echo __('To'); ?></th><td><?php echo $projectQueryResponse['Employee']['name']; ?></td></tr>
						<tr><th><?php echo __('Response'); ?></th><td><?php echo h($projectQueryResponse['ProjectQueryResponse']['response']); ?>&nbsp;</td></tr>
						<tr><th><?php echo __('Sent To Client'); ?></th><td><?php echo h($projectQueryResponse['ProjectQueryResponse']['sent_to_client']); ?>&nbsp;</td></tr>
						<tr><th><?php echo __('Client Response'); ?></th><td><?php echo h($projectQueryResponse['ProjectQueryResponse']['client_response']); ?>&nbsp;</td></tr>
						<tr>
							<td colspan="2">
								<?php 
									$folder_path = WWW_ROOT.'img'. DS . 'files' . DS . $this->Session->read('User.company_id'). DS . 'qurery_file_responses' . DS . $projectQueryResponse['ProjectQueryResponse']['id'];
					                $dir = new Folder($folder_path);
					                $all_files = $dir->find();
					                
					                if($all_files){
					                echo "<div class='row'>";
					                foreach($all_files as $file){                       
					                	$ffile = New File($file);
					                	// debug($ffile);
					                	$ff =$ffile->info();
					                	$file_path = $file_folder_path . DS . $file;
					                	if($ff['extension'] == 'jpg' || $ff['extension'] == 'jpeg' || $ff['extension'] == 'png' || $ff['extension'] == 'gif'){		                    		
					                		echo "<div class='col-md-4'>" . $this->Html->image('files' . DS . $this->Session->read('User.company_id'). DS . 'qurery_file_responses' . DS . $projectQueryResponse['ProjectQueryResponse']['id'] . DS .$file,array('fullBase' => true,'class'=>'img-responsive img-rounded')) . "</div>";
					                	}else{

					                	}

					                	
									}
									echo "</div>";
									echo "<h4>Download Files</h4>";
									echo "<ul class='list-group'>";
					                foreach($all_files as $file){                       
					                	$ffile = New File($file);
					                	$ff =$ffile->info();
					                	$file_path = $file_folder_path . DS . $file;
					                	if($ff['extension'] == 'jpg' || $ff['extension'] == 'jpeg' || $ff['extension'] == 'png' || $ff['extension'] == 'gif'){

					                	}else{
					                		echo "<li class='list-group-item'><h5>" . 
						                        $this->Html->link($file, array(
						                            'controller' => 'file_uploads',
						                            'action' => 'view_document_file',
						                            'file_name' => $file,
						                            'full_base' => base64_encode(str_replace(Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS ,'',$file_path)),
						                        ),array('target'=>'_blank','escape'=>TRUE)).
						                        "</h5></li>";
					                	}
									}
					                echo "</ul>";
					            }
								?>
							</td>
						</tr>
					</table>
				<?php endforeach; ?>
				<?php }else{ ?>
					<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover"><tr><td colspan="7">No results found</td></tr></table>
				<?php } ?>
		


<?php echo $this->element('upload-edit', array('usersId' => ${$singularVar}['{$modelClass}']['created_by'], 'recordId' => ${$singularVar}['{$modelClass}']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectQueries_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$projectQuery['ProjectQuery']['id'] ,'ajax'),array('async' => true, 'update' => '#projectQueries_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

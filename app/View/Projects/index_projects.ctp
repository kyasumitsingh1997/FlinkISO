<style type="text/css">
.progress.xs, .progress-xs{height: 2px;}
</style>
<?php echo $this->element('checkbox-script'); ?><div  id="main">
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
				<th><input type="checkbox" id="selectAll"></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th>Resources</th>
				<th>Files</th>
				<th>Closed Files</th>
				<th><?php echo $this->Paginator->sort('current_status','Stage'); ?></th>
				<th width="130"><?php echo __('Actions') ?></th>
				<!-- <th width="130"><?php echo __('Activities') ?></th> -->
				<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($projects){ $i = $j = 0?>
<?php foreach ($projects as $project): 
if($project['Project']['publish'] == 0)$class = ' danger';
else $class = '';
	?>
	<tr class="<?php echo $class ?>">
		<td class=" actions">	
		<?php echo $this->element('actions', array('created' => $project['Project']['created_by'], 'postVal' => $project['Project']['id'], 'softDelete' => $project['Project']['soft_delete'])); ?>	</td>		
		
		<td>
			<?php 
			// $completion = $this->requestAction('task_statuses/task_completion/'.$project['Project']['id']);

			if($project['Project']['total_files']){
				$completion = round($project['Project']['closed_files'] * 100 / $project['Project']['total_files']);
			}
			?>
			<strong><?php echo $this->Html->link(h($project['Project']['title']),array('action'=>'view',$project['Project']['id'])); ?></strong> &nbsp;<span class='label label-info pull-right'><?php echo round($completion);?>%</span>&nbsp;
			
			<div class="progress-group">
                <div class="progress xs">
                    <?php
                        if($completion <= 100 )$class = ' progress-bar-success';
                        if($completion <= 80 )$class = ' progress-bar-aqua';
                        if($completion <= 60 )$class = ' progress-bar-yellow';
                        if($completion <= 40)$class = ' progress-bar-red';
                    ?>
                  <div style="width: <?php echo $completion;?>%" class="progress-bar <?php echo $class;?>"></div>
                </div>
            </div>
            <p><small>From: <?php echo h($project['Project']['start_date']); ?>&nbsp; To: <?php echo h($project['Project']['end_date']); ?></small></p>
		</td>
		
		<td><?php echo $project['Project']['total_resources']; ?></td>
		<td><?php echo $project['Project']['total_files']; ?></td>
		<td><?php echo $project['Project']['closed_files']; ?></td>			
		<td><?php echo ($currentStatuses[$project['Project']['current_status']]); ?>&nbsp;</td>
		<td>
			<!-- Project Activities -->
			<!-- Split button -->
			<?php if(count($project['Milestones']) == 0)$class = 'danger';
			else $class = 'info'; ?>
			<div class="btn-group">
				<button type="button" class="btn btn-<?php echo $class; ?> btn-xs milestone_button" id="<?php echo $project['Project']['id']; ?>_<?php echo $i;?>">Actions</button>
					<button type="button" class="btn btn-<?php echo $class; ?> btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<!-- <span class="">4</span> -->
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu">
					<li><?php echo $this->Html->link('Add Invoice',array('controller'=>'invoices','action'=>'lists','project_id'=>$project['Project']['id']))?></li>
					<li><a href="#">Add Milestone</a></li>
					<li><a href="#">Add Project Costsheet</a></li>
				    <li role="separator" class="divider"></li>
				    <li><a href="#">Change Status</a></li>
				    <li><?php echo $this->Html->link('View Details',array('action'=>'view',$project['Project']['id']))?></li>
				    <li><?php echo $this->Html->link('View Project MIS',array('action'=>'mis',$project['Project']['id']))?></li>
				    <li><?php echo $this->Html->link('View Project Reports',array('action'=>'daily_time_log_daily',$project['Project']['id']))?></li>
			  	</ul>
			</div>
&nbsp;</td>
		<td class="hide">
			<!-- Project Activities -->
			<!-- Split button -->
			<?php if(count($project['ProjectActivities']) == 0)$class = 'danger';
			else $class = 'info'; ?>
			<div class="btn-group">
				<button type="button" class="btn btn-<?php echo $class; ?> btn-xs activities_button" id="<?php echo $project['Project']['id']; ?>_<?php echo $j;?>">Activities</button>
					<button type="button" class="btn btn-<?php echo $class; ?> btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <span class=""><?php echo count($project['ProjectActivities']);?></span>
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu">
					<?php
					if($project['ProjectActivities']){ 
						foreach ($project['ProjectActivities'] as $activity_key => $activity_value) {
							echo "<li>".$this->Html->link($activity_value,array('controller'=>'project_activities',
							 'action'=>'edit',$activity_key))."</li>";
						} ?>
						 <li role="separator" class="divider"></li>
						 <?php 
					}?>
				   <li><a href="#"><?php 
				   if($project['Project']['current_status'] == 0)
				   echo $this->Html->link('Add New Activity',
				    	array('controller'=>'project_activities',
				    		'action'=>'lists',
				    		'project_id'=>$project['Project']['id']
				    		)); ?></a></li>
			  	</ul>
			</div>
&nbsp;</td>
		<td width="60">
			<?php if($project['Project']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php 
$i++;
$j++;
endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=99>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#'.$currentStatuses[$this->request->params['named']['current_status']],
			'evalScripts' => true,
			'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
			'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
			));
			
			echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
			?>			</p>
			<ul class="pagination">
			<?php
		echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
		echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
		echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
	?>
			</ul>
		</div>
	</div>
	</div>	
<?php echo $this->Js->writeBuffer();?>	
<script type="text/javascript">
	$(".milestone_button").on('click',function(){
		$("#main").load('<?php echo Router::url('/', true); ?>milestones/index/project_id:'+ this.id);
	});
	$(".activities_button").on('click',function(){
		$("#main").load('<?php echo Router::url('/', true); ?>project_activities/index/project_id:'+ this.id);
	});
</script>

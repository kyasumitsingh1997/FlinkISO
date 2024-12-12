<?php if($objective) { 
	echo "<ul class='list-group'>";
	?>
	<li class="list-group-item"><h4><?php echo $objective['Objective']['title']; ?> <small>(<?php echo $objective['Objective']['clauses']; ?>)</small></h4> </li>	
	<li class="list-group-item"><strong><?php echo __('Details');?></strong> : <?php echo $objective['Objective']['objective']; ?></li>
</ul>
<?php } ?>

<?php if($objective['Process']) {
	echo '<h5>' . __("Related Processes") . '</h5>';
foreach($objective['Process'] as $process):

	echo "<ul class='list-group'>";
	?>
	<li class="list-group-item"><strong><?php echo __('Process');?></strong> : <?php echo $process['title']; ?></li>
	<li class="list-group-item"><strong><?php echo __('Owner');?></strong> : <?php echo $process['Owner']['name']; ?></li>
	<li class="list-group-item"><strong><?php echo __('Monitoring Schedule');?></strong> : <?php echo $process['Schedule']['name']; ?></li>
	<li class="list-group-item">
		<div class="btn-group">
		<?php echo $this->Html->link('View',array('controller'=>'processes','action'=>'view',$process['id']),array('class'=>'btn btn-xs btn-info')); ?>
		<?php echo $this->Html->link('Edit',array('controller'=>'processes','action'=>'edit',$process['id']),array('class'=>'btn btn-xs btn-warning')); ?>
	</div>
	</li>
</ul>
<?php endforeach; } ?>

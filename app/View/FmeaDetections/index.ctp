<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fmeaDetections ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Fmea Detections','modelClass'=>'FmeaDetection','options'=>array("sr_no"=>"Sr No","detection"=>"Detection","criteria"=>"Criteria","ranking"=>"Ranking"),'pluralVar'=>'fmeaDetections'))); ?>

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>	
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('detection'); ?></th>
				<th><?php echo $this->Paginator->sort('criteria'); ?></th>
				<th><?php echo $this->Paginator->sort('ranking'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($fmeaDetections){ ?>
<?php foreach ($fmeaDetections as $fmeaDetection): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $fmeaDetection['FmeaDetection']['created_by'], 'postVal' => $fmeaDetection['FmeaDetection']['id'], 'softDelete' => $fmeaDetection['FmeaDetection']['soft_delete'])); ?>	</td>		<td><?php echo h($fmeaDetection['FmeaDetection']['detection']); ?>&nbsp;</td>
		<td><?php echo h($fmeaDetection['FmeaDetection']['criteria']); ?>&nbsp;</td>
		<td><?php echo h($fmeaDetection['FmeaDetection']['ranking']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fmeaDetection['FmeaDetection']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fmeaDetection['FmeaDetection']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($fmeaDetection['FmeaDetection']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=60>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#main',
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

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","detection"=>"Detection","criteria"=>"Criteria","ranking"=>"Ranking"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","detection"=>"Detection","criteria"=>"Criteria","ranking"=>"Ranking"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

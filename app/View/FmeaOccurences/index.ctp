<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fmeaOccurences ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Fmea Occurences','modelClass'=>'FmeaOccurence','options'=>array("sr_no"=>"Sr No","probability_of_failure"=>"Probability Of Failure","possible_failure_rates"=>"Possible Failure Rates","cpk"=>"Cpk","ranking"=>"Ranking"),'pluralVar'=>'fmeaOccurences'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('probability_of_failure'); ?></th>
				<th><?php echo $this->Paginator->sort('possible_failure_rates'); ?></th>
				<th><?php echo $this->Paginator->sort('cpk'); ?></th>
				<th><?php echo $this->Paginator->sort('ranking'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($fmeaOccurences){ ?>
<?php foreach ($fmeaOccurences as $fmeaOccurence): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $fmeaOccurence['FmeaOccurence']['created_by'], 'postVal' => $fmeaOccurence['FmeaOccurence']['id'], 'softDelete' => $fmeaOccurence['FmeaOccurence']['soft_delete'])); ?>	</td>		<td><?php echo h($fmeaOccurence['FmeaOccurence']['probability_of_failure']); ?>&nbsp;</td>
		<td><?php echo h($fmeaOccurence['FmeaOccurence']['possible_failure_rates']); ?>&nbsp;</td>
		<td><?php echo h($fmeaOccurence['FmeaOccurence']['cpk']); ?>&nbsp;</td>
		<td><?php echo h($fmeaOccurence['FmeaOccurence']['ranking']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fmeaOccurence['FmeaOccurence']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$fmeaOccurence['FmeaOccurence']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($fmeaOccurence['FmeaOccurence']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=63>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","probability_of_failure"=>"Probability Of Failure","possible_failure_rates"=>"Possible Failure Rates","cpk"=>"Cpk","ranking"=>"Ranking"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","probability_of_failure"=>"Probability Of Failure","possible_failure_rates"=>"Possible Failure Rates","cpk"=>"Cpk","ranking"=>"Ranking"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

<table class="table table-condensed">
	<tr>
		<th><?php echo __('FMEA Assigned');?></th>
		<th><?php echo __('To');?></th>
		<th><?php echo __('Target Date');?></th>
		<th><?php echo __('Act');?></th>
	</tr>
	<?php foreach ($fmeaActions as $fmeaAction) { ?>
		<tr>
			<td><?php echo $fmeaAction['Fmea']['name'];?></td>
			<td><?php echo $fmeaAction['Employee']['name'];?></td>
			<td><?php echo $fmeaAction['FmeaAction']['target_date'];?></td>
			<td><?php echo $this->Html->link('Act',array('controller'=>'fmea_actions','action'=>'edit',$fmeaAction['FmeaAction']['id']),array('class'=>'btn btn-xs btn-danger'));?></td>
		</tr>	
	<?php } ?>
	
</table>
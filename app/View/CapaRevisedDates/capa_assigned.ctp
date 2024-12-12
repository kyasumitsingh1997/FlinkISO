<div class="capaRevisedDates ">	

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
			<table cellpadding="0" cellspacing="0" class="table table-bordered">
				<tr>
					
					
				<th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('target_date'); ?></th>
				<th><?php echo $this->Paginator->sort('new_revised_date_requested'); ?></th>
				<th><?php echo $this->Paginator->sort('reason'); ?></th>
				
						
<th><?php echo __("Act"); ?></th>
				
				</tr>
				<?php if($capaRevisedDates){ ?>
<?php foreach ($capaRevisedDates as $capaRevisedDate): ?>
	
                <tr class="on_page_src">
		<td>
			<?php echo $this->Html->link($capaRevisedDate['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaRevisedDate['CorrectivePreventiveAction']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($capaRevisedDate['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaRevisedDate['Employee']['id'])); ?>
		</td>
		<td><?php echo h($capaRevisedDate['CapaRevisedDate']['target_date']); ?>&nbsp;</td>
		<td><?php echo h($capaRevisedDate['CapaRevisedDate']['new_revised_date_requested']); ?>&nbsp;</td>
		<td><?php echo h($capaRevisedDate['CapaRevisedDate']['reason']); ?>&nbsp;</td>
	 <td>
                        <?php
                        
                                echo $this->Html->link(__('Act'), array('controller' => 'capa_revised_dates', 'action' => 'edit', $capaRevisedDate['CapaRevisedDate']['id']), array('class' => 'badge btn-warning'));
                          
                        ?>
                    </td>
		
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=69>No results found</td></tr>
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



<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

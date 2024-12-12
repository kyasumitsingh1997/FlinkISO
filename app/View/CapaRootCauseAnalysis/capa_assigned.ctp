<div class="capaRootCauseAnalysis" id="capaRootCauseAnalysis">
	<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-bordered">
				<tr>					
					<th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
					<th><?php echo $this->Paginator->sort('determined_by'); ?></th>
					<th><?php echo $this->Paginator->sort('determined_on_date'); ?></th>
					<th><?php echo $this->Paginator->sort('proposed_action'); ?></th>
					<th><?php echo __("Act"); ?></th>
				</tr>
				<?php if($capaRootCauseAnalysis){?>
					<?php foreach ($capaRootCauseAnalysis as $capaRootCauseAnalysi): ?>
						<tr class="">
							<td>
							<?php echo $this->Html->link($capaRootCauseAnalysi['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaRootCauseAnalysi['CorrectivePreventiveAction']['id'])); ?>
							</td>

							<td><?php echo $this->Html->link($capaRootCauseAnalysi['DeterminedBy']['name'], array('controller' => 'employees', 'action' => 'view', $capaRootCauseAnalysi['DeterminedBy']['id'])); ?></td>

							<td><?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['determined_on_date']); ?>&nbsp;</td>
							<td><?php echo h($capaRootCauseAnalysi['CapaRootCauseAnalysi']['proposed_action']); ?>&nbsp;</td>

							<td>
							<?php

							        echo $this->Html->link(__('Act'), array('controller' => 'capa_root_cause_analysis', 'action' => 'edit', $capaRootCauseAnalysi['CapaRootCauseAnalysi']['id']), array('class' => 'badge btn-warning'));
							  
							?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php }else{ ?>
					<tr><td colspan="5">No results found</td></tr>
				<?php } ?>
			</table>		
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#capaRootCauseAnalysis',
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
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

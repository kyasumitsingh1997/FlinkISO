<?php echo $this->Session->flash();?>	
	<div class="capaInvestigations" id="capaInvestigations">
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered">
				<tr>
					<th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
					<th><?php echo $this->Paginator->sort('details'); ?></th>
					<th><?php echo $this->Paginator->sort('target_date'); ?></th>
					<th><?php echo __("Act"); ?></th>
				</tr>
				<?php if($capaInvestigations){ ?>
					<?php foreach ($capaInvestigations as $capaInvestigation): ?>
					<tr class="on_page_src">
						<td><?php echo $this->Html->link($capaInvestigation['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaInvestigation['CorrectivePreventiveAction']['id'])); ?>
						</td>
						<td><?php echo h($capaInvestigation['CapaInvestigation']['details']); ?>&nbsp;</td>
						<td><?php echo h($capaInvestigation['CapaInvestigation']['target_date']); ?>&nbsp;</td>
                 		<td>
                        <?php
                            if ($capaInvestigation['CapaInvestigation']['target_date'] > date('Y-m-d'))
                                echo $this->Html->link(__('Act'), array('controller' => 'capa_investigations', 'action' => 'edit', $capaInvestigation['CapaInvestigation']['id']), array('class' => 'badge btn-warning'));
                            else
                                echo $this->Html->link(__('Act'), array('controller' => 'capa_investigations', 'action' => 'edit', $capaInvestigation['CapaInvestigation']['id']), array('class' => 'badge btn-danger'));
                        ?>
                    	</td>
                    </tr>
				<?php endforeach; ?>
			<?php }else{ ?>
			<tr><td colspan="4">No results found</td></tr>
		<?php } ?>
		</table>
		<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#capaInvestigations',
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

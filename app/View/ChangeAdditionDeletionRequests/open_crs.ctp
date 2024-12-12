

<div  id="main" class="nav">
		<?php echo $this->Session->flash(); ?>
		<div class="changeAdditionDeletionRequests ">
				<div class="col-md-12">      
						<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
								<tr>
										
										<th><?php echo $this->Paginator->sort('request_from', __('Request From')); ?></th>
										<th><?php echo $this->Paginator->sort('master_list_of_format', __('Master List of Format')); ?></th>
										<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
										<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
										<th><?php echo $this->Paginator->sort('Last Updated'); ?></th>					
										<th>&nbsp;</th>
								</tr>
								<?php
										if ($changeAdditionDeletionRequests) {
												$x = 0;
												foreach ($changeAdditionDeletionRequests as $changeAdditionDeletionRequest):

				?>

										<td>
												<?php if (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['branch_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['branch_id'] != -1) { echo "<strong>Branch:</strong><br />" . h($changeAdditionDeletionRequest['Branch']['name']); ?>
												<?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['department_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['department_id'] != -1) { echo "<strong>Department:</strong><br />" . h($changeAdditionDeletionRequest['Department']['name']); ?>
												<?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['employee_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['employee_id'] != -1) { echo "<strong>Employee:</strong><br />" . h($changeAdditionDeletionRequest['Employee']['name']); ?>
												<?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['customer_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['customer_id'] != -1) { echo "<strong>Customer:</strong><br />" . h($changeAdditionDeletionRequest['Customer']['name']); ?>
												<?php } elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['suggestion_form_id']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['suggestion_form_id'] != -1) { echo "<strong>Suggestion:</strong><br />" . h($changeAdditionDeletionRequest['SuggestionForm']['title']); ?>
												<?php
					} elseif (isset($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']) && $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others'] != ""){
				$needle = "CAPA Number: ";
				$capaCheck = strpos($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others'], $needle);
				if($capaCheck == 0){
						$capaNumber = str_replace($needle, '', $changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']);
						echo "<strong>CAPA Number: </strong>" . $capaNumber;
				} else {
						echo "<strong>Other : </strong>" . h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['others']);
				}
					}
			?>
										</td>
										<td><?php echo h($changeAdditionDeletionRequest['MasterListOfFormat']['title']); ?>&nbsp;</td>
										<td><?php echo h($changeAdditionDeletionRequest['PreparedBy']['name']); ?>&nbsp;</td>
										<td><?php echo h($changeAdditionDeletionRequest['ApprovedBy']['name']); ?>&nbsp;</td>
					<td><?php echo h($changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['modified']); ?>&nbsp;</td>
										<td width="60">
												<?php echo $this->Html->link('Act',array('controller'=>'change_addition_deletion_requests','action'=>'edit',$changeAdditionDeletionRequest['ChangeAdditionDeletionRequest']['id']),array('class'=>'btn btn-sm btn-warning','escape'=>false)); ?>
										</td>
								</tr>
								<?php
										$x++;
										endforeach;
										} else {
								?>
										<tr><td colspan=19><?php echo __('No results found'); ?></td></tr>
								<?php } ?>
						</table>		
						
				
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
						?>
				</p>
				<ul class="pagination">
						<?php
								echo "<li class='previous'>" . $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled')) . "</li>";
								echo "<li>" . $this->Paginator->numbers(array('separator' => '')) . "</li>";
								echo "<li class='next'>" . $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled')) . "</li>";
						?>
				</ul>
		</div>
</div>
</div>
</div>
<div id="documents_ajax">
	<?php echo $this->Session->flash();?>	
		<div class="nav panel panel-default">
			<div class="">
				<div class="clauses form col-md-8">
					<h4><?php echo __('View Clause'); ?>		
						<?php echo $this->Html->link(__('List'), array('action' => 'home',$clause['Clause']['standard']),array('id'=>'list','class'=>'label btn-info')); ?>
						<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
						<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
						<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
					</h4>

					<table class="table table-responsive">
							<tr><td><?php echo __('Title'); ?></td>
							<td>
								<?php echo h($clause['Clause']['title']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Standard'); ?></td>
							<td>
								<?php echo h($clause['Clause']['standard']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Clause'); ?></td>
							<td>
								<?php echo h($clause['Clause']['clause']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Sub-clause'); ?></td>
							<td>
								<?php echo h($clause['Clause']['sub-clause']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Details'); ?></td>
							<td>
								<?php echo $clause['Clause']['details']; ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Notes'); ?></td>
							<td>
								<?php echo nl2br($clause['Clause']['additional_details']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Prepared By'); ?></td>

						<td><?php echo h($clause['ApprovedBy']['name']); ?>&nbsp;</td></tr>
							<tr><td><?php echo __('Approved By'); ?></td>

						<td><?php echo h($clause['ApprovedBy']['name']); ?>&nbsp;</td></tr>
							<tr><td><?php echo __('Publish'); ?></td>

							<td>
								<?php if($clause['Clause']['publish'] == 1) { ?>
								<span class="fa fa-check"></span>
								<?php } else { ?>
								<span class="fa fa-ban"></span>
								<?php } ?>&nbsp;</td>
					&nbsp;</td></tr>
							<tr><td><?php echo __('Soft Delete'); ?></td>

							<td>
								<?php if($clause['Clause']['soft_delete'] == 1) { ?>
								<span class="fa fa-check"></span>
								<?php } else { ?>
								<span class="fa fa-ban"></span>
								<?php } ?>&nbsp;</td>
					&nbsp;</td></tr>
					</table>
				</div>
				<div class="col-md-4">
					<p><?php echo $this->element('helps'); ?></p>
				</div>
			</div>
		</div>
<style type="text/css">
	ul{ padding: 0px !important}	
</style>		
		<div class="">
			<div class='col-md-12'>
				<h4>Add Related Documents Below</h4>
				<div id="tabs">	
					<ul>
					<?php 
						$tabs = explode(',', $clause['Clause']['tabs']); ?>
						<div id="clause_tabs_<?php echo $this->request->data['Clause']['id'];?>">	
							<ul>
						<?php foreach ($tabs as $tab) { ?>
								<li><?php echo $this->Html->link(__($tab), array('action' => 'clausefiles',$clause['Clause']['id'],$tab)); ?></li>
						<?php } ?>
								<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
							</ul>
						</div>
						<div id="documents_tabs_<?php echo $this->request->data['Clause']['id'];?>"></div>
					<script>
					  $(function() {
					    $( "#clause_tabs_<?php echo $this->request->data['Clause']['id'];?>" ).tabs({
					      beforeLoad: function( event, ui ) {
						ui.jqXHR.error(function() {
						  ui.panel.html(
						    "Error Loading ... " +
						    "Please contact administrator." );
						});
					      }
					    });
					  }); 
					</script>
				</div>
			</div>
			<?php echo $this->Js->get('#edit');?>
			<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$clause['Clause']['id'] ,'ajax'),array('async' => true, 'update' => '#documents_ajax')));?>
			<?php echo $this->Js->writeBuffer();?>
		</div>
	</div>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>

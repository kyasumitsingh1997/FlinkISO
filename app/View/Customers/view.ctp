<div id="customers_ajax">
    <?php echo $this->Session->flash(); ?>
    <div class="nav panel panel-default">
        <div class="customers form col-md-8">
            <h4><?php echo $this->element('breadcrumbs') . __('View Customer'); ?>
                <?php echo $this->Html->link(__('List'), array('action' => 'index'), array('id' => 'list', 'class' => 'label btn-info')); ?>
          <?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'), array('id' => 'pdf', 'class' => 'label btn-info')); ?>
                <?php echo $this->Html->link(__('Edit'), '#edit', array('id' => 'edit', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->link(__('Add'), '#add', array('id' => 'add', 'class' => 'label btn-info', 'data-toggle' => 'modal')); ?>
                <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
            </h4>
            <table class="table table-responsive">
                <tr><td><?php echo __('Customer Type'); ?></td>
                    <td>
                        <?php echo $customer['Customer']['customer_type'] ? 'Individual' : 'Company'; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Name'); ?></td>
                    <td>
                        <?php echo h($customer['Customer']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Customer Since Date'); ?></td>
                    <td>
                        <?php echo h($customer['Customer']['customer_since_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Customer Code'); ?></td>
                    <td>
                        <?php echo h($customer['Customer']['customer_code']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Email'); ?></td>
                    <td>
                        <?php echo h($customer['Customer']['email']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Phone'); ?></td>
                    <td>
                        <?php echo h($customer['Customer']['phone']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Mobile'); ?></td>
                    <td>
                        <?php echo h($customer['Customer']['mobile']); ?>
                        &nbsp;
                    </td>
                </tr>
                <?php if ($customer['Customer']['customer_type'] != 0) { ?>
                    <tr><td><?php echo __('Marital Status'); ?></td>
                        <td>
                            <?php if ($customer['Customer']['maritial_status'] != -1) echo h($customer['Customer']['maritial_status']); ?>
                            &nbsp;
                        </td>
                    </tr>
                    <tr><td><?php echo __('Date Of Birth'); ?></td>
                        <td>
                            <?php echo h($customer['Customer']['date_of_birth']); ?>
                            &nbsp;
                        </td>
                    </tr>
                <?php } ?>
                <tr><td><?php echo __('Residence Address'); ?></td>
                    <td>
                        <?php echo h($customer['Customer']['residence_address']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Branch'); ?></td>
                    <td>
                        <?php echo $this->Html->link($customer['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $customer['Branch']['id'])); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($customer['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($customer['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr><td><?php echo __('Publish'); ?></td>

                    <td>
                        <?php if ($customer['Customer']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;
                </tr>
            </table>
            <?php echo $this->element('upload-edit', array('usersId' => $customer['Customer']['created_by'], 'recordId' => $customer['Customer']['id'])); ?>
        </div>
        <div class="col-md-4">
            <p><?php echo $this->element('helps'); ?></p>
        </div>	
    </div>
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading"><div class="panel-title"><?php echo __('Contacts'); ?></div></div>
			<div class="panel-body">
				<table class="table">
					<tr>
						<th><?php echo __('Name'); ?></th>
						<th><?php echo __('Phone'); ?></th>
						<th><?php echo __('Email'); ?></th>
						<th width="95"><?php echo __('Actions'); ?></th>
					</tr>
					<?php foreach($customer['CustomerContact'] as $contact): ?>
					
						<tr>
							<td><?php echo h($contact['name']) ; ?></td>
							<td><?php echo h($contact['phone']) ; ?> <?php echo h($contact['mobile']) ; ?></td>
							<td><?php echo h($contact['email']) ; ?></td>
							<td><div class="btn-group">
							<?php echo $this->Html->link('Edit',array('controller'=>'customer_contacts','action'=>'edit',$contact['id']),array('class'=>'btn btn-xs btn-warning')); ?>
							<?php echo $this->Html->link('View',array('controller'=>'customer_contacts','action'=>'view',$contact['id']),array('class'=>'btn btn-xs btn-info')); ?></div></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading"><div class="panel-title"><?php echo __('Proposals'); ?></div></div>
			<div class="panel-body">
					<table class="table">
					<tr>
						<th><?php echo __('Title'); ?></th>
						<th><?php echo __('Date'); ?></th>
						<th><?php echo __('Sent Date'); ?></th>
						<th><?php echo __('Status'); ?></th>
						<th><?php echo __('Follow Ups'); ?></th>
						<th width="95"><?php echo __('Actions'); ?></th>
					</tr>
					<?php foreach($customer['Proposal'] as $proposals):?>
						<tr>
							<td><?php echo h($proposals['title']) ; ?></td>
							<td><?php echo h($proposals['proposal_date']) ; ?></td>
							<td><?php echo h($proposals['proposal_sent_date']) ; ?></td>
							<td><?php 
									if($proposals['proposal_status'] == 0)echo 'Not Sent'; 
									if($proposals['proposal_status'] == 1)echo 'Sent'; 
									if($proposals['proposal_status'] == 2)echo 'Returned'; 
									if($proposals['proposal_status'] == 3)echo 'Rejected'; 
									if($proposals['proposal_status'] == 4)echo 'Approved'; 
									if($proposals['proposal_status'] == 5)echo 'On Hold'; 
								?>
							</td>
							<td><?php echo h($proposals['proposal_sent_date']) ; ?></td>
							<td><div class="btn-group">
							<?php echo $this->Html->link('Edit',array('controller'=>'proposals','action'=>'edit', $proposals['id']),array('class'=>'btn btn-xs btn-warning')); ?>
							<?php echo $this->Html->link('View',array('controller'=>'proposals','action'=>'view', $proposals['id']),array('class'=>'btn btn-xs btn-info')); ?></div></td>
						</tr>
					<?php endforeach; ?>
				</table>
				
			</div>
		</div>
	</div>
    <?php $this->Js->get('#list'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'index', 'ajax'), array('async' => true, 'update' => '#customers_ajax'))); ?>
    <?php $this->Js->get('#edit'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'edit', $customer['Customer']['id'], 'ajax'), array('async' => true, 'update' => '#customers_ajax'))); ?>
    <?php $this->Js->get('#add'); ?>
    <?php echo $this->Js->event('click', $this->Js->request(array('action' => 'lists', null, 'ajax'), array('async' => true, 'update' => '#customers_ajax'))); ?>
    <?php echo $this->Js->writeBuffer(); ?>
</div>
<script>
    $.ajaxSetup({beforeSend: function() {
            $("#busy-indicator").show();
        }, complete: function() {
            $("#busy-indicator").hide();
        }});
</script>

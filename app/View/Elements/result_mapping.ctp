<table class="table table-recursive">
    <tr>
        <th><?php echo __('Customer'); ?></th>
        <th><?php echo "# " . __('Proposals'); ?></th>
        <th><?php echo "# " . __('Proposal Followups'); ?></th>
        <th><?php echo "# " . __('Meetings'); ?></th>		
        <th><?php echo "# " . __('Invoices / Purchase Orders raised'); ?></th>
    </tr>
    <?php foreach ($resultMappings as $mapping): ?>
    <tr>
        <td><?php echo $mapping['CustomerDetails']['name']; ?></td>
        <td><?php if($mapping['Number_of_proposals'] == 0)$class='danger';
					else $class='success'; ?>
		<span class="badge label-<?php echo $class; ?>"><?php echo $mapping['Number_of_proposals']; ?></span></td>
        <td><?php if($mapping['Number_of_proposal_followups'] == 0)$class='danger';
					else $class='success'; ?>
		<span class="badge label-<?php echo $class; ?>"><?php echo $mapping['Number_of_proposal_followups']; ?></span></td>
		<td><?php if($mapping['Number_of_meetings'] == 0)$class='danger';
					else $class='success'; ?>
			<span class="badge label-<?php echo $class; ?>"><?php echo $mapping['Number_of_meetings']; ?></span>
		</td>
        <td>
		<?php if($mapping['Number_of_purchase_orders'] == 0)$class='danger';
					else $class='success'; ?>
					<span class="badge label-<?php echo $class; ?>"><?php echo $mapping['Number_of_purchase_orders']; ?></span></td>
    </tr>
    <?php endforeach; ?>
</table>

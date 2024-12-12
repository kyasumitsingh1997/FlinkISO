<style>
input[type="checkbox"] {
	margin-bottom: 3px;
	margin-top : 2px !important
}
.label, .badge{ font-size: 70%}
.h4-title {font-size: 22px !important;}
</style>
<div style="width:80px">
	<div class="btn-group" >
		<?php if (($this->request->params['controller'] == 'customer_meetings') || ($this->request->params['controller'] == 'proposals')|| ($this->request->params['controller'] == 'proposal_followups')) {
            if ($created == $this->Session->read('User.id') || ($this->Session->read('User.is_mr') == true)) {
                ?>
		<span class="btn  btn-xs btn-default "> <?php echo $this->Form->checkbox('rec_ids', array('label' => false, 'div' => false, 'value' => $postVal, 'multiple' => 'checkbox', 'class' => 'rec_ids', 'onClick' => 'getVals()')); ?> </span>
		<?php } else { ?>
		<button type="button" class="btn  btn-xs btn-default ">&nbsp;<span class=" glyphicon glyphicon-lock"></span></button>
		<?php }
            } else { ?>
		<span class="btn  btn-xs btn-default "> <?php echo $this->Form->checkbox('rec_ids_', array('label' => false, 'div' => false, 'value' => $postVal, 'multiple' => 'checkbox', 'class' => 'rec_ids', 'onClick' => 'getVals()')); ?> </span>
		<?php } ?>
		<button type="button" data-toggle="dropdown" class="btn  btn-xs btn-default ">&nbsp;<i class="fa fa-wrench" aria-hidden="true"></i></button>
		<?php if (($this->request->params['controller'] == 'customer_meetings') || ($this->request->params['controller'] == 'proposals')|| ($this->request->params['controller'] == 'proposals')|| ($this->request->params['controller'] == 'proposal_followups')) {

            if ($created == $this->Session->read('User.id') || ($this->Session->read('User.is_mr') == true)) {
                ?>
		<ul class="dropdown-menu" role="menu">
			<li> <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $postVal), array('style' => 'display:none'), __('Are you sure you want to delete this record ?', $postVal)); ?> </li>
			<?php if (isset($this->params['named'])) {
            if (isset($softDelete) && $softDelete == 1) {
                ?>
			<li><?php echo $this->Form->postLink(__('Restore'), array('action' => 'restore', $postVal)); ?></li>
			<li><?php echo $this->Form->postLink(__('Purge'), array('action' => 'purge', $postVal)); ?></li>
			<?php } else { ?>
			<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $postVal)); ?></li>
			<li><?php echo $this->Html->link(__('Download PDF'), array('action' => 'view', $postVal.'.pdf')); ?></li>
			<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $postVal)); ?></li><li><?php echo $this->Html->link(__('Edit in new window'), array('action' => 'edit', $postVal), array('target' => '_blank')); ?></li>
			<?php if ($this->Session->read('User.is_mr') == true) ; ?>
			<li><?php echo $this->Html->link(__('Publish Record'), array('action' => 'publish_record', $postVal), null, __('Are you sure you want to publish this record ?', $postVal)); ?></li>
			<li> <?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $postVal), array('class' => ''), __('Are you sure ?', $postVal)); ?> </li>
			<?php }
        }
        ?>
		</ul>
		<?php } else { ?>
		<ul class="dropdown-menu" role="menu">
			<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $postVal)); ?></li>
			<li><?php echo $this->Html->link(__('Download PDF'), array('action' => 'view', $postVal.'.pdf')); ?></li>
		</ul>
		<?php }?>
		<?php  }else { ?>
		<?php if ($this->request->params['controller'] == 'reports' || $this->request->params['controller'] == 'list_of_measuring_devices_for_calibrations' || ($this->request->params['controller'] =='stocks' && $this->request->params['pass'][0] == 1)) { ?>
		<ul class="dropdown-menu" role="menu">
			<li> <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $postVal), array('style' => 'display:none'), __('Are you sure you want to delete this record ?', $postVal)); ?> </li>
			<?php if (isset($this->params['named'])) {
                        if (isset($softDelete) && $softDelete == 1) {
                            ?>
			<li><?php echo $this->Form->postLink(__('Restore'), array('action' => 'restore', $postVal)); ?></li>
			<li><?php echo $this->Form->postLink(__('Purge'), array('action' => 'purge', $postVal)); ?></li>
			<?php } else { ?>
			<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $postVal)); ?></li>
			<li><?php echo $this->Html->link(__('Download PDF'), array('action' => 'view', $postVal.'.pdf')); ?></li>
			<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $postVal), array('class' => ''), __('Are you sure ?', $postVal)); ?></li>
			<?php }
                    } ?>
		</ul>
		<?php } else { ?>
		<ul class="dropdown-menu" role="menu">
			<li> <?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $postVal), array('style' => 'display:none'), __('Are you sure you want to delete this record ?', $postVal)); ?> </li>
			<?php if (isset($this->params['named'])) {
                        if (isset($softDelete) && $softDelete == 1) {
                            ?>
			<li><?php echo $this->Form->postLink(__('Restore'), array('action' => 'restore', $postVal)); ?></li>
			<li><?php echo $this->Form->postLink(__('Purge'), array('action' => 'purge', $postVal)); ?></li>
			<?php } else {
                ?>
			<li><?php echo $this->Html->link(__('View / Upload Evidence'), array('action' => 'view', $postVal)); ?></li>
			<li><?php echo $this->Html->link(__('Download PDF'), array('action' => 'view', $postVal .'.pdf')); ?></li>
			
			<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $postVal)); ?></li><li><?php echo $this->Html->link(__('Edit in new window'), array('action' => 'edit', $postVal), array('target' => '_blank')); ?></li>
			
			<?php if ($this->Session->read('User.is_mr') == true) ; ?>
			<li><?php echo $this->Html->link(__('Publish Record'), array('action' => 'publish_record', $postVal), null, __('Are you sure you want to publish this record ?', $postVal)); ?></li>
			<?php if($this->request->params['controller'] != 'designations'){ ?>
			<li><?php echo $this->Form->postLink(__('Delete Record'), array('action' => 'delete', $postVal), array('class' => ''), __('Are you sure ?', $postVal)); ?></li>
			<?php } ?>
			<?php }
                }
                ?>
          	<?php if($this->request->params['controller'] == 'employees') { ?>
          	<li role="separator" class="divider"></li>
          	<li><?php echo $this->Html->link(__('Add Competency Mapping'), array('controller'=>'competency_mappings', 'action' => 'lists', $postVal)); ?>
          	<li><?php echo $this->Html->link(__('Add Appraisal'), array('controller'=>'appraisals', 'action' => 'lists', $postVal)); ?></li> 
          	<li><?php echo $this->Html->link(__('Add TNI'), array('controller'=>'training_need_identifications', 'action' => 'lists', $postVal)); ?></li> 
          	<?php if($employee_users == 0){ ?>
          	<li><?php echo $this->Html->link(__('Create User'), array('controller'=>'users', 'action' => 'lists', $postVal)); ?></li> 
          	<?php } ?>

          	<?php } ?>
          	<?php if($this->request->params['controller'] == 'users' && $is_mr == false) { ?>
          	<li role="separator" class="divider"></li>
          	<li><?php echo $this->Html->link(__('Add Access Control'), array('controller'=>'users', 'action' => 'user_access', $postVal)); ?></li> 
          	<?php } ?>
          	<?php if($this->request->params['controller'] == 'materials' && $material_qc_status != 1 && $qc_required == 1) { ?>
          	<li role="separator" class="divider"></li>
          	<li><?php echo $this->Html->link(__('Add Quality Checks'), array('controller'=>'material_quality_checks', 'action' => 'lists', $postVal)); ?></li>
          	<li><?php echo $this->Html->link(__('Add Non Conformity'), array('controller'=>'non_conforming_products_materials', 'action' => 'lists', $postVal,'Material')); ?></li>  
          	<?php } ?>
          	
          	<?php if($this->request->params['controller'] == 'objectives') { ?>
          	<li role="separator" class="divider"></li>
          	<li><?php echo $this->Html->link(__('Add Processes'), array('controller'=>'processes', 'action' => 'lists', $postVal)); ?></li> 
          	<?php } ?>

          	<?php if($this->request->params['controller'] == 'products' && $nc_found == NULL) { ?>
          	<li role="separator" class="divider"></li>
          	<li><?php echo $this->Html->link(__('Add Non Conformity'), array('controller'=>'non_conforming_products_materials', 'action' => 'lists', $postVal,'Product')); ?></li> 
          	<?php } ?>

          	<?php if($this->request->params['controller'] == 'purchase_orders' && $type == 0) { ?>
          	<li role="separator" class="divider"></li>
          	<li><?php echo $this->Html->link(__('Generate Invoice'),array('controller'=>'invoices', 'action'=>'lists',$postVal)); ?></li> 
          	<?php } ?>
          	<?php if($this->request->params['controller'] == 'purchase_orders' && $type == 1) { ?>
          	<li role="separator" class="divider"></li>
          	<li><?php echo $this->Html->link(__('Generate Delivery Challan'),array('controller'=>'delivery_challans', 'action'=>'lists',$postVal)); ?></li> 
          	<?php } ?>
          	<?php if($this->request->params['controller'] == 'invoices' && $send_to_customer == 0 && $publish == 1) { ?>
          	<li role="separator" class="divider"></li>
          	<li><?php echo $this->Html->link(__('Send Invoice To Customer'),array('controller'=>'invoices', 'action'=>'send_to_customer',$postVal)); ?></li> 
          	<?php } ?>
		</ul>
		<?php }
} ?>
		<?php


   $filesCount = $this->requestAction('App/getFileCount/'.$postVal. '/' . $this->params->controller);

if ($filesCount > 0) {
    ?>
		<button type="button" class="btn btn-xs btn-success" style="border-bottom-right-radius:3px; border-top-right-radius:3px; border-left:0px " id='<?php echo $postVal ?>-count' data-toggle='tooltip' data-original-title='<?php echo $filesCount; ?> Evidence Uploaded'>&nbsp;<?php echo $filesCount; ?></button>
		<?php } else { ?>
		<button type="button" class="btn btn-xs btn-default" style="border-bottom-right-radius:3px; border-top-right-radius:3px; border-left:0px " id='<?php echo $postVal ?>-count' data-toggle='tooltip' data-original-title='0 Evidence Uploaded'>&nbsp;0</button>
		<?php } ?>
		<script>$('#<?php echo $postVal ?>-count').tooltip();</script> 
	</div>
</div>
<?php echo $this->Js->writeBuffer(); ?> 

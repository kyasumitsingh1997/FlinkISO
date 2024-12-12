<div id="purchaseOrderDetails_ajax_<?php echo $x; ?>"> 
	<?php echo "<div class='col-md-12'>".$this->Form->input('ProjectResource.'.$x.'.activities',array('label'=>'Activities for user')) . '</div>'; ?>      
    <?php echo "<div class='col-md-3'>".$this->Form->input('ProjectResource.'.$x.'.user_id',array('label'=>false, 'options'=>$PublishedUserList)) . '</div>'; ?>
    <?php echo "<div class='col-md-3'>".$this->Form->input('ProjectResource.'.$x.'.mandays',array('label'=>false,'default'=>0, 'onchange'=>'cale(this.value,'.$x.')')) . '</div>'; ?>
    <?php echo "<div class='col-md-3'>".$this->Form->input('ProjectResource.'.$x.'.resource_cost',array('label'=>false,'default'=>0,'onchange'=>'cale(this.value,'.$x.')')) . '</div>'; ?>    
    <?php echo "<div class='col-md-2 subt'>".$this->Form->input('ProjectResource.'.$x.'.resource_sub_total',array('label'=>false,'default'=>0)) . '</div>'; ?>       
    <?php echo "<div class='col-md-1'><span class='btn btn-danger type='button' onclick='removeAgendaDiv(".$x.")'>-</span></div>"; ?>    
</div>
<?php $x++;?>
<script type="text/javascript">
	$(".chosen-select").chosen();
</script>
<div id="purchaseOrderDetails_ajax<?php echo $key; ?>_<?php echo $x; ?>"> 
	<?php echo "<div class='col-md-12'><hr /></div>"; ?>
	<?php echo "<div class='col-md-4'>".$this->Form->input('Milestone.'.$key.'.ProjectResource.'.$x.'.lot',array()) . '</div>'; ?>
    <?php echo "<div class='col-md-4'>".$this->Form->input('Milestone.'.$key.'.ProjectResource.'.$x.'.estimated_units',array()) . '</div>'; ?>
    <?php echo "<div class='col-md-4'>".$this->Form->input('Milestone.'.$key.'.ProjectResource.'.$x.'.overall_matrix',array('default'=>0)) . '</div>'; ?>
    <?php echo "<div class='col-md-4'>".$this->Form->input('Milestone.'.$key.'.ProjectResource.'.$x.'.duration_date',array()) . '</div>'; ?>
    <?php echo "<div class='col-md-4'>".$this->Form->input('Milestone.'.$key.'.ProjectResource.'.$x.'.est_resource',array()) . '</div>'; ?>
    <?php echo "<div class='col-md-3'>".$this->Form->input('Milestone.'.$key.'.ProjectResource.'.$x.'.est_man_hours',array()) . '</div>'; ?>
    <?php echo "<div class='col-md-1 text-right'><span style='margin-top:25px' class='btn btn-danger type='button' onclick='removeAgendaDiv(".$key.", ".$x.")'>-</span></div>"; ?>    
</div>
<?php $x++;?>
<script type="text/javascript">
	$(".chosen-select").chosen();
</script>
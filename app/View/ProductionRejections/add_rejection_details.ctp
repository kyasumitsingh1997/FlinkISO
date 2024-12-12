<script>
    $(".chosen-select").chosen();
</script>
<div id="rejectiondetails_ajax">
	<div id="rejectiondetails_ajax<?php echo $i; ?>">
	<?php
		echo "<div class='col-md-8'>".$this->Form->input('RejectionDetail.'.$i.'.defect_type_id',array()) . '</div>'; 
		// echo "<div class='col-md-3'>".$this->Form->input('RejectionDetail.'.$i.'.value_driver_id',array()) . '</div>'; 
		// echo "<div class='col-md-3'>".$this->Form->input('RejectionDetail.'.$i.'.performance_indicator_id',array()) . '</div>'; 
		echo "<div class='col-md-3'>".$this->Form->input('RejectionDetail.'.$i.'.number_of_rejections',array()) . '</div>'; 
		echo "<div class='col-md-1'><br /><span class='text-danger glyphicon glyphicon-remove danger pull-right' style='font-size:20px;background:none' type='button' onclick='removeAgendaDiv(".$i.")'></span></div>"; 
	?>
	</div>
</div>
<?php $i++; $j++; ?>
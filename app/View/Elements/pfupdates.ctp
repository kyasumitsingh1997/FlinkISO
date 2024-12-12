<?php
if($updates){
echo $this->Html->script(array(
	// 'plugins/chartjs/Chart.min',
	// 'plugins/knob/jquery.knob',
	// 'plugins/jvectormap/jquery-jvectormap-1.2.2.min',
	// 'plugins/jvectormap/jquery-jvectormap-world-mill-en',
	// 'js-xlsx-master/dist/xlsx.core.min', 
 //    'Blob.js-master/Blob.min', 
 //    'FileSaver.js-master/FileSaver.min', 
 //    'TableExport-master/src/stable/js/tableexport.min',
 //    'tablesorter-master/js/jquery.tablesorter',
 //    'tablesorter-master/js/jquery.tablesorter.widgets',
    'timeknots-master/src/d3.v2.min',
    'timeknots-master/src/timeknots-min',
));
echo $this->fetch('script');
?>

<div id="timeline<?php echo $id?>" style="width:100%;height:45px; clear: both; position: relative;top:-10px;float: left;"></div>
<?php 
// Configure::write('debug',1);
// debug($updates);
foreach($updates as $pro){
		// debug($pro); 
		// debug($PublishedEmployeeList); 
		if($pro['FileProcess']['current_status'] == 0){$color = "#00a65a"; $name = $pro['Employee']['name'] . "-" . $pro['ProjectProcessPlan']['process'] . " - Assigned";}
		if($pro['FileProcess']['current_status'] == 1){$color = "#dd4b39"; $name = $pro['Employee']['name'] . "-" . $pro['ProjectProcessPlan']['process'] . " - Completed";}
		if($pro['FileProcess']['current_status'] == 2){$color = "#f39c12"; $name = $pro['Employee']['name'] . "-" . $pro['ProjectProcessPlan']['process'] . " - Delayed";}
		if($pro['FileProcess']['current_status'] == 3){$color = "#dd4b39"; $name = $pro['Employee']['name'] . "-" . $pro['ProjectProcessPlan']['process'] . " - Canceled";}
		if($pro['FileProcess']['current_status'] == 4){$color = "#dd4b39"; $name = $pro['Employee']['name'] . "-" . $pro['ProjectProcessPlan']['process'] . " - Not Assigned";}

		$agenda[] = array('name'=>$name,'date'=>$pro['FileProcess']['modified'],'color'=>$color);	
	} ?>

<script type="text/javascript">
	// var agenda = new Array();
	
	
	var agenda = '';
	agenda = <?php echo json_encode($agenda)?>;	
	TimeKnots.draw("#timeline<?php echo $id?>", agenda, {showLabels: true, dateFormat: "%Y/%m/%d", labelFormat:"%Y/%m/%d", radius: 5, lineWidth : 2});
</script>
<?php } ?>
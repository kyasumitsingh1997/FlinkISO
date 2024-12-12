<div class="row panel-body">
<?php 
 foreach ($report_required as $report) {
	switch ($this->request->params['pass'][0]) {
		case 'daily':
				$from = $this->request->params['pass'][1];
				$from = date("Y-m-01",strtotime($from.'-01'));
				$month = date('m',strtotime($this->request->params['pass'][1]));
				$current_month = date('m');
				if($month == $current_month){
					$to = date("Y-m-d", strtotime("-1 day"));
				}else{
					$to = date("Y-m-t", strtotime(date("Y-m-d",strtotime($this->request->params['pass'][1].'-01'))));		
				} 
			break;
		case 'weekly':
				$from = $this->request->params['pass'][1];
				$from = date('Y-m-d',strtotime('monday this week'.$from));
				$month = date('m',strtotime($this->request->params['pass'][1]));
				$current_month = date('m');
				if($month == $current_month){
					$to = date("Y-m-d", strtotime("-1 day"));
				}else{
					$to = date("Y-m-t", strtotime(date("Y-m-d",strtotime($this->request->params['pass'][1].'-01'))));		
				} 
			break;
		case 'monthly':
				$from = $this->request->params['pass'][1];
				$to = date("Y-m-t",strtotime($from.'-01'));				
			break;		
	}


	
	?>
	<div class="col-md-6"><p>
	<?php
	echo "<h4>" . $report['SystemTable']['name'] . "</h4>";
	while ($from <= $to) { 
		switch ($this->request->params['pass'][0]) {
 			case 'daily':
 				$fileName = $report['SystemTable']['system_name'] . '_'. date('d_m_Y', strtotime($from)) .".xls";
		 		echo $this->Html->link($fileName,array('action'=>'generate_report',$this->request->params['pass'][0],$from,$report['SystemTable']['system_name']));
		 		echo "<br />";
 				$from = date("Y-m-d", strtotime("+1 day", strtotime($from)));	
 				break;
 			case 'weekly':
 				$fileName = $report['SystemTable']['system_name'] . '_'. date('d_m', strtotime($from)) ."_To_".date("d_m_Y", strtotime("+7 day", strtotime($from))) ."_weekly.xls";
		 		echo $this->Html->link($fileName,array('action'=>'generate_report',$this->request->params['pass'][0],$from,$report['SystemTable']['system_name']));
		 		echo "<br />";
 				$from = date("Y-m-d", strtotime("+7 day", strtotime($from)));	
 				break;
 			case 'monthly':
 				$last_date = date('d',strtotime('last day of this month'.$from));
 				$fileName = $report['SystemTable']['system_name'] . '_'. date('m_Y', strtotime($from)) ."_monthly.xls";
		 		echo $this->Html->link($fileName,array('action'=>'generate_report',$this->request->params['pass'][0],$from,$report['SystemTable']['system_name']));
		 		echo "<br />";
		 		$from = date("Y-m-d", strtotime("+ ". $last_date." day", strtotime($from)));
 				break;
 		}
 		
 	} ?></p>
 </div>
 	<?php 

}
	
?>
</div>
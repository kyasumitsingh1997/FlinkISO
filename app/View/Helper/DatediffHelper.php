<?php
class DatediffHelper extends AppHelper{
	public $helpers = array('Form','Html');
	
	public function getdiff($start_date = null,$end_date = null){
		$date1 = $start_date;
		$date2 = $end_date;
		
		// echo ">>" . $date2;
		
		$date1 = new DateTime($start_date);
		$date2 = new DateTime(date('Y-m-d H:i:s',strtotime($end_date)));

		$diff = $date2->diff($date1);

		$days   = $diff->format('%D'); 
		$hours   = $diff->format('%H'); 
		$minutes = $diff->format('%i');
		$sec = $diff->format('%s');

		// $start_date = $date1;
		// $end_date = $date2;
		// Configure::write('debug',1);
		
		// debug($date1);
		// debug($date2);
		
		$holiday = 0;
		while (strtotime($date1) <= strtotime($date2)) {
        
        	$dayName = date('D',strtotime($date1));
        	if($dayName == 'Sat' || $dayName == 'Sun'){
        		$holiday++;
        	}
        
        	$date1 = date("Y-m-d", strtotime("+1 day", strtotime($date1)));
        
    	}

		if($days > 1){
			$hours = (($days - $holiday) * 8) + $hours;
		}else{			
			$hours = $hours;
		}
		echo $hours .':'. $minutes;
	
		// return $hours .':'. $minutes;
	}
}
?>

<?php $message = '<h2>Employee Data Entry Status Between '. date("d M Y",strtotime($date1)) .' To '.  date("d M Y",strtotime($date2)) .'</h2>'; ?>
<?php
	$message .= '<table border="1" width="100%">';
		foreach ($sessions as $key => $value) {
			$message .= '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
		}
	$message .= '</table>';
	$message .= '<p>This is a system generated automated report. This report will be emailed on every Monday.</p>';
	echo $message;
?>
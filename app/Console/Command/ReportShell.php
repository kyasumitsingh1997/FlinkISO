<?php

App::import('Core', 'Controller');

App::import('Controller','reports');
App::import('Controller','users');

App::import('Vendor', 'PHPExcel', array(
    'file' => 'Excel/PHPExcel.php'
));

App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class ReportShell extends AppShell {
    
    public $uses = array('Company'); 
    
    public function main() {

    $this->reports = new ReportsController();
	
	// Running Daily Reports
	
            $_ENV['company_id'] =  $company['Company']['id'];
            $var1 = $this->reports->_daily_data_backups();
            $var2 = $this->reports->_generate_daily_data_entry_report();            
     
        if($var1 || $var2){
            //$this->reports->send_email('Daily');
        }    
        //	$this->users = new UsersController();
        //	$this->users->expiry_reminder(3);
        //	$this->users->login_reminder();		 
    }
}

?>

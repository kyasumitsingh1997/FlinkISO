<?php
error_reporting(0);
App::import('Core', 'Controller');
App::import('Controller','tasks');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeEmail', 'Network/Email'); 
class TaskShell extends AppShell {
   
 public $uses = array('Company'); 
    
    
    public function main() {
        
        
        // $companies = $this->Company->find('all', array('fields'=>'id','recursive'=>-1));		
        // foreach($companies as $company){           
        //     $_ENV['company_id'] =  $company['Company']['id'];
            $this->tasks = new TasksController();        
            //You can change these dates and run the command by ./cake to generate graphs manually
            $this->tasks->_taskcorn();        
       // }
    }
}
?>



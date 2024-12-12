<?php
error_reporting(0);
App::import('Core', 'Controller');
App::import('Controller','histories');
App::uses('CtrlComponent', 'Controller/Component');
App::uses('CakeSession', 'Model/Datasource');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

 
class GraphShell extends AppShell {
   
 public $uses = array('Company'); 
    
    
    public function main() {
        
        
        $companies = $this->Company->find('all', array('fields'=>'id','recursive'=>-1));		
        foreach($companies as $company){           
            $_ENV['company_id'] =  $company['Company']['id'];
            $this->histories = new HistoriesController();        
            //You can change these dates and run the command by ./cake to generate graphs manually
            $this->histories->prepare_graph_data_departmentwise(date('Y-m-1'),date('Y-m-d'));
            $this->histories->prepare_dashbord_graphs();
       }
    }
}
?>



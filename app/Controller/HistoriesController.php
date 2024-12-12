<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Histories Controller
 *
 * @property History $History
 */
class HistoriesController extends AppController
{
    public $components = array('Ctrl');
    
    public function  _get_system_table_id()
    {
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $sys_id                       = $this->SystemTable->find('first', array(
            'conditions' => array(
                'SystemTable.system_name' => $this->request->params['controller']
            )
        ));
        return $sys_id['SystemTable']['id'];
    }
    
    /**
     * request handling by - TGS
     *
     */
    /**
     * _check_request method
     *
     * @return void
     */
    
    public $common_condition = array('OR' => array('History.action' => array('add', 'add_ajax'), array('History.model_name' => array('CakeError', 'NotificationUser', 'History', 'UserSession', 'Page', 'Dashboard', 'Error', 'NotificationType', 'Approval', 'Benchmark', 'FileUpload', 'DataEntry', 'Help', 'MeetingBranch', 'MeetingDepartment', 'MeetingEmployee', 'MeetingTopic', 'Message', 'NotificationUser', 'PurchaseOrderDetail', 'NotificationUser', 'PurchaseOrderDetail', 'MasterListOfFormatBranch', 'MasterListOfFormatDepartment', 'MasterListOfFormatDistributor'), 'History.action <>' => 'delete', 'History.action <>' => 'soft_delete', 'History.action <>' => 'purge', 'History.post_values <>' => '[[],[]]')));
    
    
    public function report()
    {
        
        $result                   = explode('+', $this->request->data['histories']['rec_selected']);
        $this->History->recursive = 1;
        $histories                = $this->History->find('all', array(
            'History.publish' => 1,
            'History.soft_delete' => 1,
            'conditions' => array(
                'or' => array(
                    'History.id' => $result
                )
            )
        ));
        $this->set('histories', $histories);
        
        $userSessions        = $this->History->UserSession->find('list', array(
            'conditions' => array(
                'UserSession.publish' => 1,
                'UserSession.soft_delete' => 0
            )
        ));
        $systemTables        = $this->History->SystemTable->find('list', array(
            'conditions' => array(
                'SystemTable.publish' => 1,
                'SystemTable.soft_delete' => 0
            )
        ));
        $masterListOfFormats = $this->History->MasterListOfFormat->find('list', array(
            'conditions' => array(
                'MasterListOfFormat.publish' => 1,
                'MasterListOfFormat.soft_delete' => 0
            )
        ));
        $this->set(compact('userSessions', 'systemTables', 'masterListOfFormats', 'userSessions', 'systemTables', 'masterListOfFormats'));
    }
    
    //output unknown
    public function prepare_graph_datas($startDate = null, $endDate = null)
    {
        
        //get benchmark agevare
        $this->loadModel('Benchmark');
        $agg_benchmarks = $this->Benchmark->find('all', array(
            'conditions' => array(
                'Benchmark.publish' => 1,
                'Benchmark.soft_delete' => 0
            )
        ));
        $i              = 0;
        $b              = 0;
        foreach ($agg_benchmarks as $benchmark):
            $b = $b + $benchmark['Benchmark']['benchmark'];
            $i++;
        endforeach;
        $benchmark = round($b / $i);
        
        
        $this->loadModel('History');
        App::import('HtmlHelper', 'View/Helper');
        
        $this->layout = "ajax";
        
        if (!$startDate)
            $startDate = $this->History->find('first', array(
                'fields' => array(
                    'History.created'
                ),
                'order' => array(
                    'History.created' => 'asc'
                )
            ));
        if (!$endDate)
            $endDate = $this->History->find('first', array(
                'fields' => array(
                    'History.created'
                ),
                'order' => array(
                    'History.created' => 'desc'
                )
            ));
        $date     = date("Y-m-d", strtotime($startDate['History']['created']));
        $end_date = date("Y-m-d", strtotime($endDate['History']['created']));
        
        while (strtotime($date) <= strtotime($end_date)) {
            $count = 0;
            
            $count1 = $this->History->find('count', array(
                'conditions' => array(
                    $this->common_condition,
                    'History.created BETWEEN ? AND ?' => array(
                        date('Y-m-d 00:00:00', strtotime($date)),
                        date("Y-m-d", strtotime("+1 day", strtotime($date)))
                    )
                )
            ));
            
            
            $count2 = $this->History->find('count', array(
                'conditions' => array(
                    'History.action' => 'delete',
                    'History.action' => 'purge',
                    'History.model_name <>' => 'CakeError',
                    'History.model_name <>' => 'NotificationUsers',
                    'History.created BETWEEN ? AND ?' => array(
                        date('Y-m-d 00:00:00', strtotime($date)),
                        date("Y-m-d", strtotime("+1 day", strtotime($date)))
                    )
                )
            ));
            
            $count = $count1 - $count2;
            if ($count > 0)
                $output[] = array(
                    'count' => $count,
                    'date' => $date
                );
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            
        }
        
        $data = "[['Date','Records','Data Entry Benchmark'],";
        foreach ($output as $graph_data):
            $data .= "['" . date('d-m-Y', strtotime($graph_data['date'])) . "'," . $graph_data['count'] . "," . $benchmark . "],";
        endforeach;
        $data .= "]]";
        $data = str_replace("],]]", "]]", $data);
        
        
        $file = fopen(Configure::read('MediaPath') . "files" . DS  . $this->Session->read('User.company_id') . DS . "graphs/graph_data.txt", "w") or die('can not open file');
        fwrite($file, $data);
        fclose($file);
        //}
    }       
 // -------------------------------------------------------------------------------- 
 // First graph which generates date-wise total data entry  
    public function graph_data($newDate = null, $type = null, $id = null){
		error_reporting(0);
		if (isset($this->request->params['pass'][0]))$date = $this->request->params['pass'][0];
		else $date = date('Y-m-d');
		
		$end_date = date("Y-m-d", strtotime("-15 days", strtotime($date)));				
		while ($end_date <= $date) 
		{
			$file = new File(Configure::read('MediaPath') . DS . "files" . DS  . $this->Session->read('User.company_id') . DS . "dataentry" . DS  . $end_date .  DS . "dataentry.txt");
			if ($file) {
				$data[$end_date] = json_decode($file->read(),true);
			}
			$end_date = date("Y-m-d", strtotime("+1 day", strtotime($end_date)));
		}				
		$branches = $this->_get_branch_list();
		$departments = $this->_get_department_list();	
	
		//$labels = "['Day', 'Dataentry', 'Benchmark'],";
		
		
			foreach($data as $date => $dataentry){
				if($dataentry != null){					
				if($this->request->params['pass'][1] == 'null'){
					$total_daily_entry[$date]['count'] = 0;
					$total_daily_entry[$date]['benchmark'] = 0;
				}
				
				foreach($branches as $branch){
					if($this->request->params['pass'][1] == 'Branch'){
						$total_daily_entry[$date]['count'] = 0;
						$total_daily_entry[$date]['benchmark'] = 0;
					}
						if($this->request->params['pass'][1] == 'Department'){
					}				
					foreach($departments as $department){
						switch($this->request->params['pass'][1]){						
							case NULL:							
								$total_daily_entry[$date]['count']	 =  $total_daily_entry[$date]['count'] + $dataentry[$branch][$department]['daily_total'];
								if($dataentry[$branch][$department]['benchmark'] == null)$dataentry[$branch][$department]['benchmark'] = 0;
								$total_daily_entry[$date]['benchmark']	 =  $total_daily_entry[$date]['benchmark'] + $dataentry[$branch][$department]['benchmark'];	
								break;
							case "Branch":
								if($this->request->params['pass'][2] == $branch){
									if($dataentry[$this->request->params['pass'][2]][$department]['benchmark'] == null)$dataentry[$this->request->params['pass'][2]][$department]['benchmark'] = 0;
									if($dataentry[$this->request->params['pass'][2]][$department]['daily_total'] == null)$dataentry[$this->request->params['pass'][2]][$department]['daily_total'] = 0;
									$total_daily_entry[$date][$this->request->params['pass'][2]]['count']	 =  $total_daily_entry[$date][$this->request->params['pass'][2]]['count'] + $dataentry[$this->request->params['pass'][2]][$department]['daily_total'];
									$total_daily_entry[$date][$this->request->params['pass'][2]]['benchmark']	 =  $total_daily_entry[$date][$this->request->params['pass'][2]]['benchmark'] + $dataentry[$this->request->params['pass'][2]][$department]['benchmark'];
								}								
								break;
							case "Department";	
								if($this->request->params['pass'][2] == $department){
									$total_daily_entry[$date][$this->request->params['pass'][2]]['count']	 = $total_daily_entry[$date][$this->request->params['pass'][2]]['count']	 + $dataentry[$branch][$this->request->params['pass'][2]]['daily_total'];
									$total_daily_entry[$date][$this->request->params['pass'][2]]['benchmark']	 =  $total_daily_entry[$date][$this->request->params['pass'][2]]['benchmark'] + $dataentry[$branch][$this->request->params['pass'][2]]['benchmark'];	
								}
								break;
						}
					}
				}
				
			}
			}
			
			switch($this->request->params['pass'][1]){						
							case NULL:
								foreach($total_daily_entry as $date=>$values){
									$labels .= "' ". $date." ',";
									$series1 .= $values['count'] . ",";
									$series2 .= $values['benchmark'] .",";
								}
							break;
							
							case "Branch":
									foreach($total_daily_entry as $date=>$values){
										$labels .= "' ". $date." ',";
										$series1 .= $values[$this->request->params['pass'][2]]['count'].",";
										$series2 .= $values[$this->request->params['pass'][2]]['benchmark'] .",";
									}
							break;
							
							case "Department":
									foreach($total_daily_entry as $date=>$values){
										$labels .= "' ". $date." ',";
										$series1 .= $values[$this->request->params['pass'][2]]['count'].",";
										$series2 .= $values[$this->request->params['pass'][2]]['benchmark'] .",";
									}
							break;
							
			}
			
			
			//$labels = "[" . $labels  . "]";
			$this->set('labels',$labels);			
			$this->set('series1', $series1);
			$this->set('series2', $series2);
			
	}
// Second Tab with displayes Branch Graph - daily
public function graph_data_branches(){
		error_reporting(0);
		if (isset($this->request->params['pass'][0]))$date = $this->request->params['pass'][0];
		else $date = date('Y-m-d');
		
		$end_date = date("Y-m-d", strtotime("-7 days", strtotime($date)));				
		while ($end_date <= $date) 
		{
			$file = new File(Configure::read('MediaPath') . "files" . DS  . $this->Session->read('User.company_id') . DS . "dataentry" . DS  . $end_date .  DS . "dataentry.txt");
			if ($file) {
				$data[$end_date] = json_decode($file->read(),true);
			}
			$end_date = date("Y-m-d", strtotime("+1 day", strtotime($end_date)));
		}
		$branches = $this->_get_branch_list();
		$departments = $this->_get_department_list();	
	
		
		$draw = "[['Branch', 'Dataentry', 'Benchmark'],";
			foreach($data as $date => $dataentry){
				foreach($branches as $branch){
					$total_daily_entry[$branch]['count'] = 0;
					$total_daily_entry[$branch]['benchmark'] = 0;
 					foreach($departments as $department){
								$total_daily_entry[$branch]['count']	 =  $total_daily_entry[$branch]['count'] + $dataentry[$branch][$department]['daily_total'];
								$total_daily_entry[$branch]['benchmark']	 =  $total_daily_entry[$branch]['benchmark'] + $dataentry[$branch][$department]['benchmark'];
					}				
				}

			}

			foreach($total_daily_entry as $branch => $dataentry){
				$draw .= "['". $branch." ', ". $dataentry['count'] . ",".$dataentry['benchmark'] ."],";
				$labels = $labels . ",'" . $branch ."'";	
				$series_count = $series_count . ',' . $dataentry['count']; 
				$series_benchmark = $series_benchmark . ',' . $dataentry['benchmark']; 					
			}
			$labels = '[' . $labels . ']';
			$labels = str_replace('[,', '[', $labels);
			
			$series_count = '[' . $series_count . ']';
			$series_count = str_replace('[,', '[', $series_count);
			//echo $series_count;

			$series_benchmark = '[' . $series_benchmark . ']';
			$series_benchmark = str_replace('[,', '[', $series_benchmark);
			//echo $series_benchmark;

			$series = '[' . $series_count .',' . $series_benchmark .']';
			
			$draw .= "]]";
			$draw = str_replace(',]]',']',$draw);
			$this->set('branch_data', $draw);
			$this->set(array('labels'=>$labels,'series'=>$series));
	}	

// Second Tab with displayes Department Graph	- daily
public function graph_data_departments(){
		error_reporting(0);
		if (isset($this->request->params['pass'][0]))$date = $this->request->params['pass'][0];
		else $date = date('Y-m-d');
		
			$file = new File(Configure::read('MediaPath') . "files" . DS  . $this->Session->read('User.company_id') . DS . "dataentry" . DS  . $date .  DS . "dataentry.txt");
			if ($file) {
				$data = json_decode($file->read(),true);
			}
			
		$branches = $this->_get_branch_list();
		$departments = $this->_get_department_list();	
		
			foreach($data as $branch => $dataentry){	
 					foreach($departments as $department){
								$total_daily_entry[$department]['count']	 =  $total_daily_entry[$department]['count'] + $dataentry[$department]['daily_total'];
								$total_daily_entry[$department]['benchmark']	 =  $total_daily_entry[$department]['benchmark'] + $dataentry[$department]['benchmark'];
					}				
			}

			foreach($total_daily_entry as $department => $dataentry){
				$labels .=  " '".$department . "',";
				$series1 .= $dataentry['count'] .",";
				$series2 .= $dataentry['benchmark'].",";				
			}
			
			$draw = str_replace(',]]',']',$draw);
			$this->set('labels',$labels);			
			$this->set('series1', $series1);
			$this->set('series2', $series2);
	}		

// --------------------------------------------------------------------------------   

    public function prepare_graph_data_departmentwise($startDate = null, $endDate = null, $total = null, $total_for_gauge = null, $benchmark = null)
	{		
		// error_reporting(0);
		// ini_set('max_execution_time', 300);
		// ini_set('memory_limit', '64M');
		$departments = $this->_get_department_list();
		$branches = $this->_get_branch_list();
		$aControllers = $this->get();	
		$output = $this->date_range($startDate,$endDate,'department',$departments,$aControllers,$branches);			
		$this->create_files($output);	
	}
	
	public function date_range($startDate =null, $endDate=null, $type=null,$lists=null,$aControllers=null,$branches = null){
	
			
			//days loop
			if ($startDate == null && $endDate == null) 
			{
				$startDate = $this->History->find('first', array('fields' => array('History.created'),'order' => array('History.created' => 'asc')));
				$endDate   = $this->History->find('first', array('fields' => array('History.created'),'order' => array('History.created' => 'desc')));
				$date      = date("Y-m-d", strtotime($startDate['History']['created']));
				$end_date  = date("Y-m-d", strtotime($endDate['History']['created']));
				$fileDate  = date("Y-m-d");
			} else {
				$date     = date("Y-m-d", strtotime($startDate));
				$end_date = date("Y-m-d", strtotime($endDate));
				$fileDate = date("Y-m-d", strtotime($startDate));
			}
			while ($date <= $end_date) 
		   {
			   	foreach($branches as $branch_key => $branch_name):
					foreach($lists as $list_key=>$list):
						$output[$date][$branch_name][$list] = $this->fetch_data($date,$type,$list_key,$list,$aControllers,$output[$date][$branch_name]['daily_total'],$branch_key,$branch_name);
					endforeach;
				endforeach;
				$date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
			}		
		return $output;
	}
	
	public function fetch_data($date = null, $type = null, $list_key = null, $list = null, $aControllers = null,$total=null,$branch_key = null,$branch_name = null){
		if($type == 'branch'){$typeid = 'branchid';$benchmark_type = 'branch_id';}
		elseif($type == 'department'){$typeid = 'departmentid';$benchmark_type = 'department_id';}
		//open each controller and count records from the department
		//get benchmark
		$this->loadModel('Benchmark');
		$this->Benchmark->virtualFields['benchmark_total'] = 'SUM(Benchmark.benchmark)';
		$benchmark = 0;
		$benchmark = $this->Benchmark->find('first',array('fields'=>array('Benchmark.benchmark_total'),'conditions'=>array('Benchmark.'.$benchmark_type => $list_key,'Benchmark.branch_id'=>$branch_key)));
		$benchmark = $benchmark['Benchmark']['benchmark_total'];
				foreach ($aControllers as $key => $value):
					$getModelName = Inflector::Classify(str_replace('Controller', '', $key));
					$this->loadModel($getModelName);
					if($this->$getModelName->hasField($typeid)){
						if ($getModelName != 'History' && $getModelName != 'UserSession' && $getModelName != 'App' && $getModelName != 'Benchmarks' && $getModelName != 'Page' && $getModelName != 'Dashboard' && 
							$getModelName != 'Error' && $getModelName != 'NotificationType' && $getModelName != 'Approval' && $getModelName != 'Benchmark' && $getModelName != 'FileUpload' && 
							$getModelName != 'Help' && $getModelName != 'MeetingBranch' && $getModelName != 'MeetingDepartment' && $getModelName != 'MeetingEmployee' && 
							$getModelName != 'MeetingTopic' && $getModelName != 'Message' && $getModelName != 'NotificationUser' && $getModelName != 'PurchaseOrderDetail' && $getModelName != 'NotificationUser' && 
							$getModelName != 'PurchaseOrderDetail' && $getModelName != 'MasterListOfFormatBranch' && $getModelName != 'MasterListOfFormatDepartment' && $getModelName != 'MasterListOfFormatDistributor' 
							&& $getModelName != 'MessageUserSent' && $getModelName != 'MessageUserThrash' && $getModelName != 'MessageUserInbox'
							) 
							{
													
								$count = $this->$getModelName->find('count', array(
									'conditions' => array(
									$getModelName .'.branchid' => $branch_key,
									$getModelName .'.'.$typeid .' LIKE' => '%'.$list_key.'%',
									$getModelName . '.created BETWEEN ? AND ? ' => 
										array(date("Y-m-d 00:00.000000",strtotime($date)),date("Y-m-d 23:59.000000",strtotime($date)))))
										);
								// $total = rand(2,10);
								$total = $count + $total;
							}
							
					}
				endforeach;
				return array('daily_total'=>$total,'benchmark'=>$benchmark);
	}
	
	public function create_files($output = null){	
				foreach($output as $date => $entry):
					$folder = new Folder();
					if (isset($_ENV['company_id']) && $_ENV['company_id'] != null) 
					{
						$folder->create(Configure::read('MediaPath') . "files" . DS . $_ENV['company_id'] . DS . "dataentry" . DS . $date, 0777);
						$file = fopen(Configure::read('MediaPath') . "files" . DS . $_ENV['company_id'] . DS . "dataentry" . DS . $date . DS . "dataentry.txt", "w") or die('can not open file');
					} else {
						$folder->create(Configure::read('MediaPath') . "files" . DS . $this->Session->read('User.company_id') . DS . "dataentry" . DS . $date);
						$file = fopen(Configure::read('MediaPath') . "files" . DS . $this->Session->read('User.company_id') . DS . "dataentry" . DS . $date . DS ."dataentry.txt", "w") or die('can not open file');                        
					}
					fwrite($file, json_encode($entry));
					fclose($file);
				endforeach;											
	}
	
    
    public function get()
    {
        
        $aCtrlClasses = App::objects('controller');
        foreach ($aCtrlClasses as $controller) {
            if ($controller != 'AppController') {
                App::import('Controller', str_replace('Controller', '', $controller));
                $aMethods = get_class_methods($controller);
                foreach ($aMethods as $idx => $method) {
                    if ($method{0} == '_') {
                        unset($aMethods[$idx]);
                    }
                }
                App::import('Controller', 'AppController');
                $parentActions            = get_class_methods('AppController');
                $controllers[$controller] = array_diff($aMethods, $parentActions);
            }
        }
        return $controllers;
    }
    
	public function department_guage($newDate = null) {		
		error_reporting(0);
		if (isset($this->request->params['pass'][0]))$date = $this->request->params['pass'][0];
		else $date = date('Y-m-d',strtotime('-1 day'));		
			$file = new File(Configure::read('MediaPath') . "files" . DS  . $this->Session->read('User.company_id') . DS . "dataentry" . DS  . $date .  DS . "dataentry.txt");
			if ($file) {
				$data = json_decode($file->read(),true);
			}			
		$branches = $this->_get_branch_list();
		$departments = $this->_get_department_list();	
		$this->set(array('departments'=>$departments,'data'=>$data,'branches'=>$branch));
		$this->render('/Elements/department_gauge');
    } 
	
	public function branch_guage($newDate = null) {
		error_reporting(0);
		if (isset($this->request->params['pass'][0]))$date = $this->request->params['pass'][0];
		else $date = date('Y-m-d',strtotime('-1 day'));		
			$file = new File(Configure::read('MediaPath') . DS ."files" . DS  . $this->Session->read('User.company_id') . DS . "dataentry" . DS  . $date .  DS . "dataentry.txt");
			if ($file) {
				$data = json_decode($file->read(),true);
			}			
		$branches = $this->_get_branch_list();
		$departments = $this->_get_department_list();
		$this->set(array('data'=>$data));
		$this->render('/Elements/branch_gauge');
    } 	
    
    
    // add to data_entries tables
    
    public function data_entries($startDate = null, $endDate = null)
    {
        
        $this->History->recursive = -1;
        if (!$startDate)
            $startDate = $this->History->find('first', array(
                'fields' => array(
                    'History.created'
                ),
                'order' => array(
                    'History.created' => 'asc'
                )
            ));
        if (!$endDate)
            $endDate = $this->History->find('first', array(
                'fields' => array(
                    'History.created'
                ),
                'order' => array(
                    'History.created' => 'desc'
                )
            ));
        
        $date     = date("Y-m-d", strtotime($startDate['History']['created']));
        $end_date = date("Y-m-d", strtotime($endDate['History']['created']));
        
        
        //$date = '2013-9-1 00:00:00';
        //$end_date = '2013-11-21 00:00:00';
        $this->loadModel('DataEntry');
        $this->DataEntry->deleteAll(array(
            '1 = 1'
        ));
        
        $i = 0;
        $this->loadModel('User');
        $this->User->recursive = -1;
        $users                 = $this->User->find('all');
        foreach ($users as $user):
            $count = 0;
            while (strtotime($date) <= strtotime($end_date)) {
                $count = $this->History->find('count', array(
                    'fields' => array(
                        'History.sr_no',
                        'History.created_by',
                        'History.branchid',
                        'History.departmentid'
                    ),
                    'conditions' => array(
                        $this->common_condition,
                        'History.created_by' => $user['User']['id'],
                        'History.created BETWEEN ? AND ?' => array(
                            date('Y-m-d 00:00:00', strtotime($date)),
                            date("Y-m-d", strtotime("+1 day", strtotime($date)))
                        )
                    )
                ));
                
                $i++;
                $this->DataEntry->create();
                $data['branch_id']     = $user['User']['branch_id'];
                $data['department_id'] = $user['User']['department_id'];
                $data['user_id']       = $user['User']['id'];
                $data['record_date']   = date('Y-m-d', strtotime($date));
                $data['count']         = $count;
                $this->DataEntry->save($data);
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
        endforeach;
        
    }
    
    
    public function db_backup($tables = '*')
    {
        
        $return       = '';
        $modelName    = $this->modelClass;
        $dataSource   = $this->{$modelName}->getDataSource();
        $databaseName = $dataSource->getSchemaName();
        
        
        // Do a short header
        $return .= '-- Database: `' . $databaseName . '`' . "\n";
        $return .= '-- Generation time: ' . date('D jS M Y H:i:s') . "\n\n\n";
        
        
        if ($tables == '*') {
            $tables = array();
            $result = $this->{$modelName}->query('SHOW TABLES');
            foreach ($result as $resultKey => $resultValue) {
                $tables[] = current($resultValue['TABLE_NAMES']);
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }
        
        // Run through all the tables
        foreach ($tables as $table) {
            $tableData = $this->{$modelName}->query('SELECT * FROM ' . $table);
            $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
            $createTableResult = $this->{$modelName}->query('SHOW CREATE TABLE ' . $table);
            $createTableEntry  = current(current($createTableResult));
            $return .= "\n\n" . $createTableEntry['Create Table'] . ";\n\n";
            
            // Output the table data
            foreach ($tableData as $tableDataIndex => $tableDataDetails) {
                $return .= 'INSERT INTO ' . $table . ' VALUES(';
                
                foreach ($tableDataDetails[$table] as $dataKey => $dataValue) {
                    
                    if (is_null($dataValue)) {
                        $escapedDataValue = 'NULL';
                    } else {
                        // Convert the encoding
                        $escapedDataValue = mb_convert_encoding($dataValue, "UTF-8", "ISO-8859-1");
                        
                        // Escape any apostrophes using the datasource of the model.
                        $escapedDataValue = $this->{$modelName}->getDataSource()->value($escapedDataValue);
                    }
                    
                    $tableDataDetails[$table][$dataKey] = $escapedDataValue;
                }
                $return .= implode(',', $tableDataDetails[$table]);
                $return .= ");\n";
            }
            $return .= "\n\n\n";
        }
        
        // Set the default file name
        $date     = date('d-m-Y');
        $old_date = date('d-m-Y', strtotime('-7 day', strtotime($date)));
        $fileName = Configure::read('MediaPath') . 'files'. DS .'dbbackup' . DS . $databaseName . '_' . $date . '.sql';
        
        //Remove old file
        // if (file_exists(Configure::read('MediaPath') . 'files'. DS .'dbbackup' . DS . $databaseName . '_' . $old_date . '.sql')) {
        //     unlink(Configure::read('MediaPath') . 'files'. DS .'dbbackup' . DS . $databaseName . '_' . $old_date . '.sql');
        // }
        file_put_contents($fileName, $return);
        die;
        
    }
    
    public function view($id = null) {
		$i=0;
       $options = array('conditions' => array(
				'History.record_id' => $id,
				'AND'=>array(
					'History.action'=>'edit',
					'History.action'=>'approve'
					),
				'AND'=>array(
						'History.post_values NOT LIKE ' => '[[],[]]',
						'History.post_values NOT LIKE '=>'[[]]'
						)
			),'recursive'=>0,'order'=>array('History.created'=>'desc'));
		$history_pages = $this->History->find('all', $options);
		if($history_pages){
		foreach($history_pages as $history_page){
			$model = $history_page['History']['model_name'];
			$datasend[0] = 	json_decode($history_page['History']['pre_post_values'],true);			
			$newHistory[$i]['pre_post_values'] = $this->_clean_data($datasend,$model);
			$newHistory[$i]['post_values'] = $this->_clean_data(json_decode($history_page['History']['post_values'],true),$model);
			$newHistory[$i]['post_values']['CreatedBy'] = $history_page['CreatedBy'];
			$newHistory[$i]['post_values']['ModifiedBy'] = $history_page['ModifiedBy'];			
			$fullHistory[$i] = $newHistory[$i];
			$i++;
	}

		$this->loadModel($model);		
		$current_record = $this->$model->find('all',array('conditions'=>array($model.'.id'=>$id)));
		$current_record = $this->_clean_data($current_record,$model);
		$this->set(array('current_record' => $current_record,'old_records'=>$fullHistory));	
		}else{
			$this->set('no_history',true);
		}
    }
	
	public function _clean_data($data = null,$model = null){
		$this->loadModel($model);		
		$newArray = array();
		foreach($data[0] as $model_name=>$detail):
			foreach($detail as $field_name=>$field_value):
			if((array_key_exists(Inflector::Classify($field_name),$this->$model->belongsTo)))
			{
				$belongs = $this->$model->belongsTo[Inflector::Classify($field_name)];
				$this->loadModel($belongs['className']);									
					if($this->$belongs['className'])
					{
						$displayField = $this->$belongs['className']->displayField;
						$val = $this->$belongs['className']->find('first',array(
							'conditions'=>array($belongs['className'].'.id'=>$field_value),
							'fields'=>array($belongs['className'].'.'.$displayField),
							'recursive'=>1
							));
							if($val){
								$newArray[Inflector::Classify($field_name)] = $val[$belongs['className']];
							}	
						}					
			}else{
	
				$newArray[$model_name][$field_name] = $field_value;
			}
			endforeach;	
		endforeach;
		return $newArray;	
	}

	public function performance_chart(){
		
		if($this->request->data){
			$data = $this->prepare_dashbord_graphs(null,null,null,$this->request->data['PerformanceChart']['months']);
			if ($data) {
		    	if($data){
		        foreach ($data as $month => $datas) {		          
		            $labels .= '"'.$month .'" ,';
		            if(in_array('CAPA', $this->request->data['PerformanceChart']['Sections'])){
                            $open_capas .= '"'.$datas['CAPA']['Open'] .'" ,';
                            $close_capas .= '"'.$datas['CAPA']['Closed'] .'" ,';
                    }

					if(in_array('NC', $this->request->data['PerformanceChart']['Sections'])){
			            $open_ncs .= '"'.$datas['NC']['Open'] .'" ,';
			            $close_ncs .= '"'.$datas['NC']['Closed'] .'" ,';
			        }

			        if(in_array('Change Requests', $this->request->data['PerformanceChart']['Sections'])){
			            $open_change_reqs .= '"'.$datas['ChangeReq']['Open'] .'" ,';
			            $close_change_reqs .= '"'.$datas['ChangeReq']['Closed'] .'" ,';
			        }

			        if(in_array('Users', $this->request->data['PerformanceChart']['Sections'])){	
			            $users .= '"'.$datas['Users']['Users'] .'" ,';
			            $employees .= '"'.$datas['Users']['Employees'] .'" ,';
			        }

			        if(in_array('Trainings', $this->request->data['PerformanceChart']['Sections'])){
			            $trainings .= '"'.$datas['Trainings']['Trainings'] .'" ,';
			            $evaluations .= '"'.$datas['Trainings']['Evaluations'] .'" ,';            
			        }

			        if(in_array('Suppliers', $this->request->data['PerformanceChart']['Sections'])){
			            $suppliers .= '"'.$datas['Suppliers']['Suppliers'] .'" ,';
			            $supplier_evaluations .= '"'.$datas['Suppliers']['Evaluations'] .'" ,';            
			        }

			        if(in_array('Incidents', $this->request->data['PerformanceChart']['Sections'])){
			            $incidents .= '"'.$datas['Incidents']['Incidents'] .'" ,';
			            $incident_investigations .= '"'.$datas['Incidents']['Investigation'] .'" ,';  
			        }

			        if(in_array('Objectives', $this->request->data['PerformanceChart']['Sections'])){
			            $objectives .= '"'.$datas['Objectives']['Objectives'] .'" ,';			            
			        }

			        if(in_array('Complaints', $this->request->data['PerformanceChart']['Sections'])){
			            $complaints .= '"'.$datas['Complaints']['Complaints'] .'" ,';			            
			        }
		        }
		      }
		    
			    if($open_capas){
			    	$open_capas = $open_capas;
			    	$this->set('open_capas',$open_capas);
			    	$close_capas = $close_capas;
			    	$this->set('close_capas',$close_capas);
			    }

			    if($open_ncs){
			    	$open_ncs = $open_ncs;
			    	$this->set('open_ncs',$open_ncs);
			    	$close_ncs = $close_ncs;
			    	$this->set('close_ncs',$close_ncs);
			    }

			    if($open_change_reqs){
			    	$open_change_reqs = $open_change_reqs;
			    	$this->set('open_change_reqs',$open_change_reqs);
			    	$close_change_reqs = $close_change_reqs;
			    	$this->set('close_change_reqs',$close_change_reqs);
			    }

			    if($users){
			    	$users = $users;
			    	$this->set('users',$users);
			    	$employees = $employees;
			    	$this->set('employees',$employees);
			    }

			    if($suppliers){
			    	$suppliers = $suppliers;
			    	$this->set('suppliers',$suppliers);
			    	$supplier_evaluations = $supplier_evaluations;
			    	$this->set('supplier_evaluations',$supplier_evaluations);
			    }

			    if($trainings){
			    	$trainings = $trainings;
			    	$this->set('trainings',$trainings);
			    	$evaluations = $evaluations;
			    	$this->set('evaluations',$evaluations);
			    }

			    if($incidents){
			    	$incidents = $incidents;
			    	$this->set('incidents',$incidents);
			    	$incident_investigations = $incident_investigations;
			    	$this->set('incident_investigations',$incident_investigations);
			    }

			    if($objectives){
			    	$objectives = $objectives;
			    	$this->set('objectives',$objectives);			    	
			    }

			    if($complaints){
			    	$complaints = $complaints;
			    	$this->set('complaints',$complaints);			    	
			    }
			    
			    

			    $label = rtrim($labels,',');
			    $this->set('label',$label);
			}else{
				$this->set('no_reports',true);
			}    			
		}else{
			$data = $this->prepare_dashbord_graphs(null,null,null,3);
		    if ($data) {
		      if($data){
		        foreach ($data as $month => $datas) {
		            $labels .= '"'.$month .'" ,';
		           
			            $open_capas .= '"'.$datas['CAPA']['Open'] .'" ,';
			            $close_capas .= '"'.$datas['CAPA']['Closed'] .'" ,';
		                $open_ncs .= '"'.$datas['NC']['Open'] .'" ,';
			            $close_ncs .= '"'.$datas['NC']['Closed'] .'" ,';
			            $open_change_reqs .= '"'.$datas['ChangeReq']['Open'] .'" ,';
			            $close_change_reqs .= '"'.$datas['ChangeReq']['Closed'] .'" ,';
			            $users .= '"'.$datas['Users']['Users'] .'" ,';
			            $employees .= '"'.$datas['Users']['Employees'] .'" ,';
			            $trainings .= '"'.$datas['Trainings']['Trainings'] .'" ,';
			            $evaluations .= '"'.$datas['Trainings']['Evaluations'] .'" ,';            
			            $suppliers .= '"'.$datas['Suppliers']['Suppliers'] .'" ,';
			            $supplier_evaluations .= '"'.$datas['Suppliers']['Evaluations'] .'" ,';
			            $incidents .= '"'.$datas['Incidents']['Incidents'] .'" ,';
			            $incident_investigations .= '"'.$datas['Incidents']['Investigation'] .'" ,';
			            $objectives .= '"'.$datas['Objectives']['Objectives'] .'" ,';
			            $complaints .= '"'.$datas['Complaints']['Complaints'] .'" ,';
			       
		        }
		      }
		    
		    
			    if($open_capas){
			    	$open_capas = $open_capas;
			    	$this->set('open_capas',$open_capas);
			    	$close_capas = $close_capas;
			    	$this->set('close_capas',$close_capas);
			    }

			    if($open_ncs){
			    	$open_ncs = $open_ncs;
			    	$this->set('open_ncs',$open_ncs);
			    	$close_ncs = $close_ncs;
			    	$this->set('close_ncs',$close_ncs);
			    }

			    if($open_change_reqs){
			    	$open_change_reqs = $open_change_reqs;
			    	$this->set('open_change_reqs',$open_change_reqs);
			    	$close_change_reqs = $close_change_reqs;
			    	$this->set('close_change_reqs',$close_change_reqs);
			    }

			    if($users){
			    	$users = $users;
			    	$this->set('users',$users);
			    	$employees = $employees;
			    	$this->set('employees',$employees);
			    }

			    if($suppliers){
			    	$suppliers = $suppliers;
			    	$this->set('suppliers',$suppliers);
			    	$supplier_evaluations = $supplier_evaluations;
			    	$this->set('supplier_evaluations',$supplier_evaluations);
			    }

			    if($trainings){
			    	$trainings = $trainings;
			    	$this->set('trainings',$trainings);
			    	$evaluations = $evaluations;
			    	$this->set('evaluations',$evaluations);
			    }
			    
			    if($incidents){
			    	$incidents = $incidents;
			    	$this->set('incidents',$incidents);
			    	$incident_investigations = $incident_investigations;
			    	$this->set('incident_investigations',$incident_investigations);
			    }

			    if($objectives){
			    	$objectives = $objectives;
			    	$this->set('objectives',$objectives);			    	
			    }

			    if($complaints){
			    	$complaints = $complaints;
			    	$this->set('complaints',$complaints);			    	
			    }

			    $label = rtrim($labels,',');
			    $this->set('label',$label);
			}else{
				$this->set('no_reports',true);
			}  
		}
		$this->layout = 'ajax';
	}
    
    public function prepare_dashbord_graphs( $from_date = null, $to_date = null, $result = array(), $months = null){
    	
    	if(!$from_date && !$to_date){
    		if($months){
    			$from_date = date('Y-m-1',strtotime('-'.$months.' months'));
    			$to_date = date('Y-m-t');
    		}    		
    	}

    	while (strtotime($from_date) <= strtotime($to_date)) {

    		//get capa
    		$this->loadModel('CorrectivePreventiveAction');

    		$conditions = array(
    			'CorrectivePreventiveAction.publish'=>1,
    			'CorrectivePreventiveAction.soft_delete'=>0,
    								'CorrectivePreventiveAction.current_status'=>0,'CorrectivePreventiveAction.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$open_capa = $this->CorrectivePreventiveAction->find('count',array(
    			'conditions'=> $conditions));
    		
    		$conditions = array('CorrectivePreventiveAction.soft_delete'=>0,
    								'CorrectivePreventiveAction.publish'=>1,
    								'CorrectivePreventiveAction.current_status'=>1,'CorrectivePreventiveAction.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$closed_capa = $this->CorrectivePreventiveAction->find('count',array(
    			'conditions'=>$conditions));

    		$this->loadModel('NonConformingProductsMaterials');
			$conditions = array(
				'NonConformingProductsMaterials.publish'=>1,
				'NonConformingProductsMaterials.soft_delete'=>0,
    								'NonConformingProductsMaterials.status != '=>1,'NonConformingProductsMaterials.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$open_ncs = $this->NonConformingProductsMaterials->find('count',array(
    			'conditions'=> $conditions));
    		
    		$conditions = array(
    			'NonConformingProductsMaterials.publish'=>1,
    			'NonConformingProductsMaterials.soft_delete'=>0,
    								'NonConformingProductsMaterials.status'=>1,'NonConformingProductsMaterials.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$close_ncs = $this->NonConformingProductsMaterials->find('count',array(
    			'conditions'=>$conditions));    		

    		//get customer complaints
    		$this->loadModel('CustomerComplaint');

    		$conditions = array(
    			'CustomerComplaint.publish'=>1,
    			'CustomerComplaint.soft_delete'=>0,
    								'CustomerComplaint.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$open_customer_complaints = $this->CustomerComplaint->find('count',array(
    			'conditions'=> $conditions));
    		
    		$conditions = array(
    			'CustomerComplaint.publish'=>1,
    			'CustomerComplaint.soft_delete'=>0,
    								'CustomerComplaint.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$closed_customer_complaints = $this->CustomerComplaint->find('count',array(
    			'conditions'=>$conditions));
    		 
			
			//get document changes
    		$this->loadModel('ChangeAdditionDeletionRequest');

    		$conditions = array(
    			'ChangeAdditionDeletionRequest.publish'=>1,
    			'ChangeAdditionDeletionRequest.soft_delete'=>0,
    								'ChangeAdditionDeletionRequest.document_change_accepted'=>0,'ChangeAdditionDeletionRequest.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$open_document_changes = $this->ChangeAdditionDeletionRequest->find('count',array(
    			'conditions'=> $conditions));
    		
    		$conditions = array(
    			'ChangeAdditionDeletionRequest.publish'=>1,
    			'ChangeAdditionDeletionRequest.soft_delete'=>0,
    								'ChangeAdditionDeletionRequest.document_change_accepted'=>1,'ChangeAdditionDeletionRequest.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$closed_document_changes = $this->ChangeAdditionDeletionRequest->find('count',array(
    			'conditions'=>$conditions));


    		//get documents uploaded
    		$this->loadModel('FileUpload');

    		$conditions = array('FileUpload.soft_delete'=>0,
    								'FileUpload.publish'=>1,'FileUpload.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$documents_uploaded = $this->FileUpload->find('count',array(
    			'conditions'=> $conditions));
    		
    		$conditions = array('FileUpload.soft_delete'=>0,
    								'FileUpload.publish'=>1,'FileUpload.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$documents_deleted = $this->FileUpload->find('count',array(
    			'conditions'=>$conditions));


			//Users involved
    		$this->loadModel('User');

    		$conditions = array('User.soft_delete'=>0,
    								'User.publish'=>1,'User.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$users = $this->User->find('count',array(
    			'conditions'=> $conditions));
			
			$this->loadModel('Employee');    		
    		$conditions = array('Employee.publish'=>1,
    								'Employee.soft_delete'=>0,'Employee.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$employees = $this->Employee->find('count',array(
    			'conditions'=>$conditions));

    		
    		//Trainings & Evaluations
    		
    		$this->loadModel('Training');

    		$conditions = array('Training.soft_delete'=>0,
    								'Training.publish'=>1,'Training.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$trainings = $this->Training->find('count',array(
    			'conditions'=> $conditions));
			
			$this->loadModel('TrainingEvaluation');    		
    		$conditions = array('TrainingEvaluation.publish'=>1,
    								'TrainingEvaluation.soft_delete'=>0,'TrainingEvaluation.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$training_evaluations = $this->TrainingEvaluation->find('count',array(
    			'conditions'=>$conditions));


    		//Suppliers & Evaluations
    		
    		$this->loadModel('SupplierRegistration');

    		$conditions = array('SupplierRegistration.soft_delete'=>0,
    								'SupplierRegistration.publish'=>1,'SupplierRegistration.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$suppliers = $this->SupplierRegistration->find('count',array(
    			'conditions'=> $conditions));
			
			$this->loadModel('SupplierEvaluationReevaluation');    		
    		$conditions = array('SupplierEvaluationReevaluation.publish'=>1,
    								'SupplierEvaluationReevaluation.soft_delete'=>0,'SupplierEvaluationReevaluation.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$supplier_evaluations = $this->SupplierEvaluationReevaluation->find('count',array(
    			'conditions'=>$conditions));


    		//incidents
    		$this->loadModel('Incident');

    		$conditions = array('Incident.soft_delete'=>0,
    								'Incident.publish'=>1,'Incident.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$incidents = $this->Incident->find('count',array(
    			'conditions'=> $conditions));
    		
    		$this->loadModel('IncidentInvestigation');
    		$conditions = array('IncidentInvestigation.soft_delete'=>0,
    								'IncidentInvestigation.publish'=>1,'IncidentInvestigation.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$incident_investigations = $this->IncidentInvestigation->find('count',array(
    			'conditions'=>$conditions));

    		$this->loadModel('ObjectiveMonitoring');
    		$conditions = array('ObjectiveMonitoring.soft_delete'=>0,
    								'ObjectiveMonitoring.publish'=>1,'ObjectiveMonitoring.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$objective_monitoring = $this->ObjectiveMonitoring->find('count',array(
    			'conditions'=>$conditions));

    		$this->loadModel('CustomerComplaint');
    		$conditions = array('CustomerComplaint.soft_delete'=>0,
    								'CustomerComplaint.publish'=>1,'CustomerComplaint.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
    				);
    		$complaints = $this->CustomerComplaint->find('count',array(
    			'conditions'=>$conditions));
    		// final array
	   		$result[date('m-Y',strtotime($from_date))] = array(
    			'CAPA'=>array('Open'=>$open_capa,'Closed'=>$closed_capa),
    			'NC'=>array('Open'=>$open_ncs,'Closed'=>$close_ncs),
    			'ChangeReq'=>array('Open'=>$open_document_changes,'Closed'=>$closed_document_changes),
    			'Files'=>array('Open'=>$documents_uploaded,'Closed'=>$documents_deleted),
    			'Users'=>array('Users'=>$users,'Employees'=>$employees),
    			'Trainings'=>array('Trainings'=>$trainings,'Evaluations'=>$training_evaluations),
    			'Suppliers'=>array('Suppliers'=>$suppliers,'Evaluations'=>$supplier_evaluations),
    			'Incidents'=>array('Incidents'=>$incidents,'Investigation'=>$incident_investigations),
    			'Objectives'=>array('Objectives'=>$objective_monitoring),
    			'Complaints'=>array('Complaints'=>$complaints)
    			); 
			$from_date = date("Y-m-d", strtotime("+1 month", strtotime($from_date)));
    	}
    	
    	$result[date('m-Y',strtotime($from_date))] = array(
    			'CAPA'=>array('Open'=>0,'Closed'=>0),
    			'NC'=>array('Open'=>0,'Closed'=>0),
    			'ChangeReq'=>array('Open'=>0,'Closed'=>0),
    			'Files'=>array('Open'=>0,'Closed'=>0),
    			'Users'=>array('Users'=>$users,'Employees'=>$employees),
    			'Trainings'=>array('Trainings'=>0,'Evaluations'=>0),
    			'Suppliers'=>array('Suppliers'=>0,'Evaluations'=>0),
    			'Incidents'=>array('Incidents'=>0,'Investigation'=>0),
    			'Objectives'=>array('Objectives'=>0),
    			'Complaints'=>array('Complaints'=>0)
    			); 
   
				return $result;
    	
    }



	public function empc(){
		
		$this->loadModel('SystemTable');
		$tables = $this->SystemTable->find('all',array('conditions'=>array('SystemTable.reports'=>1),'recursive'=>-1,'fields'=>array('id','system_name')));
		$this->loadModel('User');
		$users = $this->User->find('all',array('fields'=>array('User.id','Employee.name','Employee.id','Branch.name'),'recursive'=>1));
		$this->loadModel('FileUpload');
		foreach($tables as $table){
			foreach ($users as $user) 
				{
					$name = Inflector::Classify($table['SystemTable']['system_name']);
					$this->loadModel($name);
					$records[$user['Branch']['name']][$user['Employee']['name']][$name] = $this->$name->find('count',array('conditions'=>array($name.'.created BETWEEN ? and ?'=>array(date('Y-m-1'),date('Y-m-d H:i:s')),$name.'.created_by'=>$user['User']['id'])));
					// $device_files = $this->FileUpload->find('count',array('conditions'=>array('FileUpload.system_table_id'=>'5297b2e7-0d60-4558-83e6-2d8f0a000005','FileUpload.created_by'=>$user['User']['id'])));
					// $records[$user['Branch']['name']][$user['Employee']['name']]['DeviceFiles'] = $device_files;	
					
				}			
		}
				
		$this->set('records',$records);
		// $email = 'mayuresh@techmentis.biz';
		
  //           try{
  //           		App::uses('CakeEmail', 'Network/Email');
	                
	 //                // if($this->Session->read('User.is_smtp') == 1)
	 //                $EmailConfig = new CakeEmail("smtp");
	 //                // if($this->Session->read('User.is_smtp') == 0)
	 //                //     $EmailConfig = new CakeEmail("default");
	                
	 //                $EmailConfig->to($email);
	 //                $EmailConfig->subject('FlinkISO: Weekly Employee Compliance Report');
	 //                $EmailConfig->template('empc');
	 //                $EmailConfig->viewVars(array('records' => $records));
	 //                $EmailConfig->emailFormat('html');
	                 
	 //              	$EmailConfig->send();                
  //           	}catch(Exception $e) {
  //                	return false;
  //           	}
	}      

	public function reminders(){
			
			$modelLists = array(
					'CapaInvestigation'=>array(
						'date_field'=>'target_date',
						'assigned_to_field' => 'employee_id',
						'status_field'=>'current_status',
						'status_flag'=>false,						
					),'CapaRevisedDate'=>array(
						'date_field'=>'target_date',
						'assigned_to_field' => 'employee_id',
						'status_field'=>null,
						'status_flag'=>false,
					),'CustomerComplaint'=>array(
						'date_field'=>'target_date',
						'assigned_to_field' => 'employee_id',
						'status_field'=>'current_status',
						'status_flag'=>false,
					),'InternalAudit'=>array(
						'date_field'=>'target_date',
						'assigned_to_field' => 'employeeId',
						'status_field'=>'current_status',
						'status_flag'=>false,
					),'MeetingTopic'=>array(
						'date_field'=>'target_date',
						'assigned_to_field' => 'employee_id',
						'status_field'=>'current_status',
						'status_flag'=>false,
					),'RiskAssessment'=>array(
						'date_field'=>'target_date',
						'assigned_to_field' => 'person_responsible',
						'status_field'=>null,
						'status_flag'=>false,
					)
				);
			$this->loadModel('Employee');
			$employeeList = $this->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
			foreach ($employeeList as $employee_id => $employee_name) {
					foreach($modelLists as $model => $fields){
						$status_conditions = array();
						$m = $this->loadModel($model);
						$conditions = array(
								$model.'.publish'=>1,$model.'.soft_delete'=>0,
								'DATE('.$model .'.'.$fields['date_field'].') <' => date('Y-m-d'),
								$model.'.'.$fields['assigned_to_field'] => $employee_id,								
							);
						if($fields['status_field']){
							$status_conditions = array($model.'.'.$fields['status_field'] => $fields['status_flag']);
						}
						$recs = $this->$model->find('all',array('recursive'=>-1, 'conditions'=>array($conditions,$status_conditions)));
						if($recs){
							foreach ($recs as $rec) {
								$result_set[$model][] = array(
									'model'=>$model,
									'current_status'=>$rec[$model][$fields['status_field']],
									'target_date'=>$rec[$model][$fields['date_field']],
									'details'=> $rec[$model][$this->$model->displayField]);
							}
							
							$results[$employee_id]  = $result_set;
						}
					}
				}					
				
				$html = '';
				foreach ($results as $employee_id => $data) {
					$html = '';
					$html .= "<strong>Dear ". $employeeList[$employee_id] . "<strong><br />";
					$html .= "<p>You have following pending action items assigned to you.</p><br />";
					
					foreach ($data as $model => $details) {
						$html .= "<h2>".Inflector::Humanize($model)."</h2>";
						$html .= "<table border='1' cellpadding='4' cellspacing='0' width='100%'>";
						$html .= "<tr><th width='70%'>Details</th><th width='15%'>Target Date</th><th width='15%'>Status</th></tr>";
							foreach ($details as $key => $value) {
								$html .= "<tr><td>".$value['details']."</td><td>".$value['target_date']."</td><td>".($value['current_status'] ? 'Close':'Open')."</td></tr>";
							}

						$html .= '</table>';
					}
					$this->_send_daily_reminder_emails($employee_id,$html);
					$html = '';
				
				}
				exit;
	  }

	  public function _send_daily_reminder_emails($employee_id = null,$html = null){
	  	$this->loadModel('Employee');
	  	  $employee = $this->Employee->find('first',array('conditions'=>array('Employee.id'=>$employee_id), 
		    'fields'=>array('id', 'office_email','personal_email'), 'recursive'=>-1));
		  $officeEmailId = $employee['Employee']['office_email'];
		  $personalEmailId = $employee['Employee']['personal_email'];
		  if ($officeEmailId != '') {
		    $email = $officeEmailId;
		  } else if ($personalEmailId != '') {
		    $email = $personalEmailId;
		  }

		  try{
		    App::uses('CakeEmail', 'Network/Email');
		    if($this->Session->read('User.is_smtp') == 1)
		      $EmailConfig = new CakeEmail("smtp");
		    if($this->Session->read('User.is_smtp') == 0)
		      $EmailConfig = new CakeEmail("default");
		    $EmailConfig->to($email);
		   
		    
		    // if(Configure::read('evnt') == 'Dev')$env = 'DEV';
		    // elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
		    // else 
		    $env = "";

		    $EmailConfig->template('daily_reminder_emails');
		    $EmailConfig->viewVars(array('html' => $html,'title'=>'Pending action items','env' => $env, 'app_url' => FULL_BASE_URL));
		    $EmailConfig->emailFormat('html');
		    $EmailConfig->subject('FlinkISO : Pending action items');
		    $EmailConfig->send();
		  } catch(Exception $e) {
		    $this->Session->setFlash(__('The user has been saved but fail to send email. Please check smtp details.', true), 'smtp');
		    // $this->redirect(array('action' => 'index'));
		  }
		}
	  // }
}

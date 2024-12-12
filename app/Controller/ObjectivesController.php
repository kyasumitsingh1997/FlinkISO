<?php
App::uses('AppController', 'Controller');
/**
 * Objectives Controller
 *
 * @property Objective $Objective
 */
class ObjectivesController extends AppController {

public function _get_system_table_id() {
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $systemTableId['SystemTable']['id'];
    }




/**
 * index method
 *
 * @return void
 */
	public function index() {
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('Objective.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->Objective->recursive = 0;
		$objectives = $this->paginate();
		foreach ($objectives as $objective) {			
			$processCount = 0;
			$objective['ProcessCount'] = 0;
			$processCount = $this->Objective->Process->find('list',array( 'conditions'=>array( 'Process.publish'=>1,'Process.soft_delete'=>0,'Process.objective_id'=>$objective['Objective']['id'])));			
			$objective['ProcessCount'] = count($processCount);
			$objective['Process'] = $processCount;
			

			// find last monitoring
			$this->loadModel('ObjectiveMonitoring');
			$objectiveMonitorings = $this->ObjectiveMonitoring->find('all',array(
				'recursive'=>-1,
				'fields'=>array('ObjectiveMonitoring.id','ObjectiveMonitoring.employee_id','ObjectiveMonitoring.target_date','ObjectiveMonitoring.current_status'),
				'conditions'=>array('ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'])));
			
			$objective['ObjectiveMonitoring'] = $objectiveMonitorings;
			$newObjectives[] = $objective;
		}
		
		$this->set('objectives', $newObjectives);
		
		$this->_get_count();
	}


 
/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('Objective.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->Objective->recursive = 0;
		$this->set('objectives', $this->paginate());
		
		$this->_get_count();
	}

/**
 * search method
 * Dynamic by - TGS
 * @return void
 */
	public function search() {
		if ($this->request->is('post')) {
	
	$search_array = array();
		$search_keys = explode(" ",$this->request->data['Objective']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['Objective']['search_field'] as $search):
				$search_array[] = array('Objective.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('Objective.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->Objective->recursive = 0;
		$this->paginate = array('order'=>array('Objective.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Objective.soft_delete'=>0 , $cons));
		$this->set('objectives', $this->paginate());
		}
                $this->render('index');
	}

/**
 * adcanced_search method
 * Advanced search by - TGS
 * @return void
 */
	public function advanced_search() {
		if ($this->request->is('post')) {
		$conditions = array();
			if($this->request->data['Search']['keywords']){
				$search_array = array();
				$search_keys = explode(" ",$this->request->data['Search']['keywords']);
	
				foreach($search_keys as $search_key):
					foreach($this->request->data['Search']['search_fields'] as $search):
					if($this->request->data['Search']['strict_search'] == 0)$search_array[] = array('Objective.'.$search => $search_key);
					else $search_array[] = array('Objective.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->data['Search']['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->data['Search']['branch_list']){
			foreach($this->request->data['Search']['branch_list'] as $branches):
				$branch_conditions[]=array('Objective.branch_id'=>$branches);
			endforeach;
			$conditiions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->data['Search']['to-date'])$this->request->data['Search']['to-date'] = date('Y-m-d');
		if($this->request->data['Search']['from-date']){
			$conditions[] = array('Objective.created >'=>date('Y-m-d h:i:s',strtotime($this->request->data['Search']['from-date'])),'Objective.created <'=>date('Y-m-d h:i:s',strtotime($this->request->data['Search']['to-date'])));
		}
		unset($this->request->data['Search']);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Objective.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Objective.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->Objective->recursive = 0;
		$this->paginate = array('order'=>array('Objective.sr_no'=>'DESC'),'conditions'=>$conditions , 'Objective.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('objectives', $this->paginate());
		}
                $this->render('index');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Objective->exists($id)) {
			throw new NotFoundException(__('Invalid objective'));
		}
		$options = array('recursive'=>2, 'conditions' => array('Objective.' . $this->Objective->primaryKey => $id));
		$objective = $this->Objective->find('first', $options);
		
		$i = 0;
		if($objective['Process']){
			foreach($objective['Process'] as $process):
				$new_process['Process'][$i] = $process;
				//get teams 
				$this->loadModel('ProcessTeam');
				$teams = $this->ProcessTeam->find('all',array('conditions'=>array('ProcessTeam.process_id'=>$process['id'])));
				if($teams){
					foreach($teams as $team):
					//get branch, department, user names 
					$this->loadModel('Branch');
					$this->loadModel('Department');
					$this->loadModel('User');

					$branches = $this->Branch->find('list',array('conditions'=>array('Branch.id'=>json_decode($team['ProcessTeam']['branch_id']))));
					$departments = $this->Department->find('list',array('conditions'=>array('Department.id'=>json_decode($team['ProcessTeam']['department_id']))));
					$users = $this->User->find('list',array('conditions'=>array('User.id'=>json_decode($team['ProcessTeam']['team']))));
					debug($users);
					$team['ProcessTeam']['Branches'] = $branches;
					$team['ProcessTeam']['Departments'] = $departments;
					$team['ProcessTeam']['Users'] = $users;
					$new_process['Process'][$i]['ProcessTeam'] = $team['ProcessTeam'];
					endforeach;
				;	
				}
			$i++;	
			endforeach;
		}
		$objective['Process'] = $new_process['Process'];
		$this->set('objective', $objective);

		$listOfKips = $this->Objective->ListOfKpi->find('list',array('conditions'=>array('ListOfKpi.publish'=>1,'ListOfKpi.soft_delete'=>0)));
		$this->set('listOfKpis',$listOfKips);

		if($this->Session->read('User.is_mr')==1){
            $nonmrcon = array();
        }else{
            $nonmrcon = array('Objective.employee_id'=>$this->Session->read('User.employee_id'));
        }
        $start_date = $objective['Objective']['created'];
        // echo $start_date;
        $start_date = date('y-m-d',strtotime($start_date));
        $end_date = date('Y-m-d');
		// echo $start_date;
		// echo $end_date;
		$schedules = $this->Objective->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		// $this->set('schedules',$schedules);
		switch ($schedules[$objective['Objective']['schedule_id']]) {
		    case 'Daily':		    
		        $monitoring = $this->Objective->ObjectiveMonitoring->find('count',array(
		            'conditions'=>array($nonmrcon,
		                'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
		                'DATE(ObjectiveMonitoring.created) BETWEEN ? and ?' => array(
		                    $start_date,
		                    $end_date
		                ))
		            ));
		        // echo $monitoring;
		        $i=0;
		        while (strtotime($start_date) <= strtotime($end_date)) {
		        	$i++;
		        	$start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
		        }
		        $completion = $monitoring/$i*100;
		        $this->set('completion',$completion);
		        break;
		    case 'Weekly':
		        $monitoring = $this->Objective->ObjectiveMonitoring->find('count',array(
		            'conditions'=>array($nonmrcon,
		                'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
		                'DATE(ObjectiveMonitoring.created) BETWEEN ? and ?' => array(
		                    $start_date,
		                    $end_date
		                ))
		            ));
		        $i=0;
		        while (strtotime($start_date) <= strtotime($end_date)) {
		        	$i++;
		        	$start_date = date("Y-m-d", strtotime("+1 week", strtotime($start_date)));
		        }
		        $completion = $monitoring/$i*100;
		        $this->set('completion',$completion);
		        break;
		    case 'Monthly':
		        $monitoring = $this->Objective->ObjectiveMonitoring->find('count',array(
		            'conditions'=>array($nonmrcon,
		                'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
		                'DATE(ObjectiveMonitoring.created) BETWEEN ? and ?' => array(
		                    $start_date,
		                    $end_date
		                ))
		            ));
		        $i=0;
		        while (strtotime($start_date) <= strtotime($end_date)) {
		        	$i++;
		        	$start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
		        }
		        $completion = $monitoring/$i*100;
		        $this->set('completion',$completion);
		        break;
		    case 'Quarterly':
		        $monitoring = $this->Objective->ObjectiveMonitoring->find('count',array(
		            'conditions'=>array($nonmrcon,
		                'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
		                'DATE(ObjectiveMonitoring.created) BETWEEN ? and ?' => array(
		                    $start_date,
		                    $end_date
		                ))
		            ));
		        $i=0;
		        while (strtotime($start_date) <= strtotime($end_date)) {
		        	$i++;
		        	$start_date = date("Y-m-d", strtotime("+15 days", strtotime($start_date)));
		        }
		        $completion = $monitoring/$i*100;
		        $this->set('completion',$completion);
		        break;
		    case 'Yearly':
		       $monitoring = $this->Objective->ObjectiveMonitoring->find('count',array(
		            'conditions'=>array($nonmrcon,
		                'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
		                'DATE(ObjectiveMonitoring.created) BETWEEN ? and ?' => array(
		                    $start_date,
		                    $end_date
		                ))
		            ));
		        $i=0;
		        while (strtotime($start_date) <= strtotime($end_date)) {
		        	$i++;
		        	$start_date = date("Y-m-d", strtotime("+1 year", strtotime($start_date)));
		        }
		        $completion = $monitoring/$i*100;
		        $this->set('completion',$completion);
		        break;
		}
 		// exit;
		
	}



/**
 * list method
 *
 * @return void
 */
	public function lists() {
	
        $this->_get_count();		

	}


/**
 * add_ajax method
 *
 * @return void
 */
	public function add_ajax() {
	
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post')) {
		
			$this->request->data['Objective']['system_table_id'] = $this->_get_system_table_id();
			$this->request->data['Objective']['list_of_kpi_ids'] = json_encode($this->request->data['Objective']['list_of_kpi_ids']);
			$this->Objective->create();
			if ($this->Objective->save($this->request->data)) {

				if($this->request->data['Objective']['publish'] == 1)$this->_objective_email($this->request->data['Objective']['employee_id'],$this->request->data['Objective']['target_date']);

				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The objective has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Objective->id));
				else $this->redirect(str_replace('/lists','/add_ajax',$this->referer()));
			} else {
				$this->Session->setFlash(__('The objective could not be saved. Please, try again.'));
			}
		}		
		
		$listOfKpis = $this->Objective->ListOfKpi->find('list',array('conditions'=>array('ListOfKpi.publish'=>1,'ListOfKpi.soft_delete'=>0)));
		$branches = $this->Objective->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$departments = $this->Objective->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$employees = $this->Objective->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$masterListOfFormats = $this->Objective->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Objective->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Objective->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Objective->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Objective->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Objective->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$schedules = $this->Objective->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$this->set(compact('branches','departments','employees', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','schedules','listOfKpis'));
	
	$count = $this->Objective->find('count');
	$published = $this->Objective->find('count',array('conditions'=>array('Objective.publish'=>1)));
	$unpublished = $this->Objective->find('count',array('conditions'=>array('Objective.publish'=>0)));
		
	$this->set(compact('count','published','unpublished'));

	}





/**
 * add method
 *
 * @return void
 */
	public function add() {
	
		if($this->_show_approvals()){
			$this->loadModel('User');
			$this->User->recursive = 0;
			$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.is_approvar'=>1)));
			$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
		}
		
		if ($this->request->is('post')) {
                        $this->request->data['Objective']['system_table_id'] = $this->_get_system_table_id();
			$this->Objective->create();
			if ($this->Objective->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Objective';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Objective->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The objective has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Objective->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The objective could not be saved. Please, try again.'));
			}
		}
		$masterListOfFormats = $this->Objective->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Objective->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Objective->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Objective->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Objective->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Objective->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$schedules = $this->Objective->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$this->set(compact('masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','schedules'));

	$count = $this->Objective->find('count');
	$published = $this->Objective->find('count',array('conditions'=>array('Objective.publish'=>1)));
	$unpublished = $this->Objective->find('count',array('conditions'=>array('Objective.publish'=>0)));
		
	$this->set(compact('count','published','unpublished'));

	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Objective->exists($id)) {
			throw new NotFoundException(__('Invalid objective'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        		$this->request->data[$this->modelClass]['publish'] = 0;
      		}
						
			$this->request->data['Objective']['system_table_id'] = $this->_get_system_table_id();
			$this->request->data['Objective']['list_of_kpi_ids'] = json_encode($this->request->data['Objective']['list_of_kpi_ids']);

			if ($this->Objective->save($this->request->data)) {
				if($this->request->data['Objective']['publish'] == 1)$this->_objective_email($this->request->data['Objective']['employee_id'],$this->request->data['Objective']['target_date']);
				
				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The objective is saved.'));
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The objective could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Objective.' . $this->Objective->primaryKey => $id));
			$this->request->data = $this->Objective->find('first', $options);
		}
		
		$listOfKpis = $this->Objective->ListOfKpi->find('list',array('conditions'=>array('ListOfKpi.publish'=>1,'ListOfKpi.soft_delete'=>0)));
		$branches = $this->Objective->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$departments = $this->Objective->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$employees = $this->Objective->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$masterListOfFormats = $this->Objective->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Objective->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Objective->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Objective->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Objective->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Objective->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$schedules = $this->Objective->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$this->set(compact('branches','departments','employees', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','schedules','listOfKpis'));

		$count = $this->Objective->find('count');
		$published = $this->Objective->find('count',array('conditions'=>array('Objective.publish'=>1)));
		$unpublished = $this->Objective->find('count',array('conditions'=>array('Objective.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->Objective->exists($id)) {
			throw new NotFoundException(__('Invalid objective'));
		}
		
		$this->loadModel('Approval');
        if (!$this->Approval->exists($approvalId)) {
            throw new NotFoundException(__('Invalid approval id'));
        }

        $approval = $this->Approval->read(null, $approvalId);
        $this->set('same', $approval['Approval']['user_id']);

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
				
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Objective->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->Objective->save($this->request->data)) {
                $this->Session->setFlash(__('The objective has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The objective could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The objective could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Objective.' . $this->Objective->primaryKey => $id));
			$this->request->data = $this->Objective->find('first', $options);
		}
		
		$masterListOfFormats = $this->Objective->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Objective->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Objective->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Objective->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Objective->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Objective->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$schedules = $this->Objective->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$this->set(compact('masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','schedules'));

		$count = $this->Objective->find('count');
		$published = $this->Objective->find('count',array('conditions'=>array('Objective.publish'=>1)));
		$unpublished = $this->Objective->find('count',array('conditions'=>array('Objective.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));
	}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function purge($id = null) {
		$this->Objective->id = $id;
		if (!$this->Objective->exists()) {
			throw new NotFoundException(__('Invalid objective'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Objective->delete()) {
			$this->Session->setFlash(__('Objective deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Objective was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
	
            $model_name = $this->modelClass;
            if(!empty($id)){
    
            $data['id'] = $id;
            $data['soft_delete'] = 1;
            $model_name=$this->modelClass;
            $this->$model_name->save($data);
    }
    $this->redirect(array('action' => 'index'));
     
    
}
 
	
	
	
	public function report(){
		
		$result = explode('+',$this->request->data['objectives']['rec_selected']);
		$this->Objective->recursive = 1;
		$objectives = $this->Objective->find('all',array('Objective.publish'=>1,'Objective.soft_delete'=>1,'conditions'=>array('or'=>array('Objective.id'=>$result))));
		$this->set('objectives', $objectives);
		
		$masterListOfFormats = $this->Objective->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Objective->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Objective->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Objective->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Objective->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Objective->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$schedules = $this->Objective->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$this->set(compact('masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','schedules'));
	}

	public function _objective_email($employee_id = null,$date = null){
		$this->loadModel('Employee');
		  $employee = $this->Employee->find('first',array('conditions'=>array('Employee.id'=>$employee_id), 
		    'fields'=>array('id', 'office_email','personal_email','name'), 'recursive'=>-1));
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
		   
		    $model = Inflector::classify($this->request->controller);
		    $this->loadModel($model);
		    $title = $this->request->data[$model][$this->$model->displayField];
		    
		    // if(Configure::read('evnt') == 'Dev')$env = 'DEV';
		    // elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
		    // else 
		    $env = "";

		    $title = "New objective is assigned to you";
		    $text = '<strong>Dear, '.$employee['Employee']['name'].'</strong>';
		    $text .= "<p>New Objective is assigned to you with target date ". $date .". </p><p>Login to QMS application for more detals.</p>";

		    $EmailConfig->template('objective_assigned');
		    $EmailConfig->viewVars(array('text' => $text,'title'=>$title,'env' => $env, 'app_url' => FULL_BASE_URL));
		    $EmailConfig->emailFormat('html');
		    $EmailConfig->subject('FlinkISO : New Objective assigned');  
		    $EmailConfig->send();
		  } catch(Exception $e) {
		    $this->Session->setFlash(__('The user has been saved but fail to send email. Please check smtp details.', true), 'smtp');
		    $this->redirect(array('action' => 'index'));
		  }		

	}
}

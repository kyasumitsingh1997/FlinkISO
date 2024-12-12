<?php
App::uses('AppController', 'Controller');
/**
 * Processes Controller
 *
 * @property Process $Process
 */
class ProcessesController extends AppController {

public function _get_system_table_id() {
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $systemTableId['SystemTable']['id'];
    }


public function _index() {
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('Objective.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->Objective->recursive = 0;
		$objectives = $this->paginate();
		foreach ($objectives as $objective) {			
			$processCount = 0;
			$objective['ProcessCount'] = 0;
			$processCount = $this->Objective->Process->find('count',array( 'conditions'=>array( 'Process.publish'=>1,'Process.soft_delete'=>0,'Process.objective_id'=>$objective['Objective']['id'])));			
			$objective['ProcessCount'] = $processCount;
			$newObjectives[] = $objective;
		}
		$this->set('objectives', $newObjectives);
		
		$this->_get_count();
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('Process.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->Process->recursive = 0;
		$processes = $this->paginate();
		foreach ($processes as $process) {
			$taskCount = 0; 
			$process['TaskCount'] = 0;
			$tasks = $this->Process->Task->find('list',array('conditions'=>array('Task.publish'=>1,'Task.soft_delete'=>0,'Task.process_id'=>$process['Process']['id'])));
			$process['TaskCount'] = count($tasks);
			$process['Tasks'] = $tasks;
			$newProcesses[] = $process;

		}

		$this->set('processes', $newProcesses);
		
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
		$this->paginate = array('order'=>array('Process.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->Process->recursive = 0;
		$this->set('processes', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['Process']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['Process']['search_field'] as $search):
				$search_array[] = array('Process.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('Process.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->Process->recursive = 0;
		$this->paginate = array('order'=>array('Process.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Process.soft_delete'=>0 , $cons));
		$this->set('processes', $this->paginate());
		}
                $this->render('index');
	}

/**
 * adcanced_search method
 * Advanced search by - TGS
 * @return void
 */
	public function advanced_search() {
		if ($this->request->is('get')) {
		$conditions = array();
			if($this->request->query['keywords']){
				$search_array = array();
				$search_keys = explode(" ",$this->request->query['keywords']);
	
				foreach($search_keys as $search_key):
					foreach($this->request->query['search_fields'] as $search):
					if($this->request->query['strict_search'] == 0)$search_array[] = array('Process.'.$search => $search_key);
					else $search_array[] = array('Process.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('Process.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('Process.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'Process.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Process.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Process.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->Process->recursive = 0;
		$this->paginate = array('order'=>array('Process.sr_no'=>'DESC'),'conditions'=>$conditions , 'Process.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('processes', $this->paginate());
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
	public function view($id = null, $ajax= NULL) {
		if (!$this->Process->exists($id)) {
			throw new NotFoundException(__('Invalid process'));
		}
		$options = array('recursive'=>1, 'conditions' => array('Process.' . $this->Process->primaryKey => $id));
		$process = $this->Process->find('first', $options);
		$this->set('process', $process);
		if($ajax == 1)$this->layout = 'ajax';		
		$schedules = $this->Process->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$this->set('schedules',$schedules);
	}



/**
 * list method
 *
 * @return void
 */
	public function lists() {
		if(!$this->request->params['pass'][0]){
			$this->Session->setFlash(__('Please click on the correct Objectives.'));
			$this->redirect(array('controller'=>'objectives','action' => 'index'));
		}else{
			$objective = $this->Process->Objective->find('first',array('recursive'=>2, 'conditions'=>array('Objective.id'=>$this->request->params['pass'][0])));
			if($objective){
				$this->set('objective',$objective);
			}else{
				$this->Session->setFlash(__('Please click on the correct Objectives.'));
				$this->redirect(array('controller'=>'objectives','action' => 'index'));		
			}
		}
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
			$this->request->data['Process']['system_table_id'] = $this->_get_system_table_id();
			$this->Process->create();		
			if ($this->Process->save($this->request->data)) {
				//add team
				
				$this->Process->ProcessTeam->create();
				$processTeam['ProcessTeam'] = $this->request->data['ProcessTeam'];
				$processTeam['ProcessTeam']['name'] = $this->request->data['ProcessTeam']['name'];
				$processTeam['ProcessTeam']['owner_id'] = $this->request->data['Process']['owner_id'];
				$processTeam['ProcessTeam']['branch_id'] = json_encode($this->request->data['ProcessTeam']['branch_id']);
				$processTeam['ProcessTeam']['department_id'] = json_encode($this->request->data['ProcessTeam']['department_id']);
				$processTeam['ProcessTeam']['team'] = json_encode($this->request->data['ProcessTeam']['team']);				
				$processTeam['ProcessTeam']['process_id'] = $this->Process->id;
				$processTeam['ProcessTeam']['objective_id'] = $this->request->data['Process']['objective_id'];
				$processTeam['ProcessTeam']['prepared_by'] = $this->request->data['Process']['prepared_by'];
				$processTeam['ProcessTeam']['approved_by'] = $this->request->data['Process']['approved_by'];
				$processTeam['ProcessTeam']['publish'] = $this->request->data['Process']['publish'];
				$this->Process->ProcessTeam->save($processTeam,false);
				
				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The process has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Process->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The process could not be saved. Please, try again.'));
			}
		}else{
			if(!$this->request->params['pass'][0]){
			$this->Session->setFlash(__('Please click on the correct Objectives.'));
			$this->redirect(array('controller'=>'objectives','action' => 'index'));
		}else{
			$objective = $this->Process->Objective->find('first',array('recursive'=>2, 'conditions'=>array('Objective.id'=>$this->request->params['pass'][0])));
			if($objective){
				$this->set('objective',$objective);
			}else{
				$this->Session->setFlash(__('Please click on the correct Objectives.'));
				$this->redirect(array('controller'=>'objectives','action' => 'index'));		
			}
		}
		}
		$objectives = $this->Process->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
		$owners = $this->Process->Owner->find('list',array('conditions'=>array('Owner.publish'=>1,'Owner.soft_delete'=>0)));
		$inputProcesses = $this->Process->InputProcess->find('list',array('conditions'=>array('InputProcess.publish'=>1,'InputProcess.soft_delete'=>0)));
		$outputProcesses = $this->Process->OutputProcess->find('list',array('conditions'=>array('OutputProcess.publish'=>1,'OutputProcess.soft_delete'=>0)));
		$schedules = $this->Process->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$systemTables = $this->Process->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Process->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Process->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Process->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Process->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Process->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Process->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('objectives', 'owners', 'inputProcesses', 'outputProcesses', 'schedules', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	
	$count = $this->Process->find('count');
	$published = $this->Process->find('count',array('conditions'=>array('Process.publish'=>1)));
	$unpublished = $this->Process->find('count',array('conditions'=>array('Process.publish'=>0)));
		
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
                        $this->request->data['Process']['system_table_id'] = $this->_get_system_table_id();
			$this->Process->create();
			if ($this->Process->save($this->request->data)) {
if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Process';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Process->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The process has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Process->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The process could not be saved. Please, try again.'));
			}
		}
		$objectives = $this->Process->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
		$owners = $this->Process->Owner->find('list',array('conditions'=>array('Owner.publish'=>1,'Owner.soft_delete'=>0)));
		$inputProcesses = $this->Process->InputProcess->find('list',array('conditions'=>array('InputProcess.publish'=>1,'InputProcess.soft_delete'=>0)));
		$outputProcesses = $this->Process->OutputProcess->find('list',array('conditions'=>array('OutputProcess.publish'=>1,'OutputProcess.soft_delete'=>0)));
		$schedules = $this->Process->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$systemTables = $this->Process->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Process->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Process->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Process->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Process->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Process->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Process->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('objectives', 'owners', 'inputProcesses', 'outputProcesses', 'schedules', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	
	$count = $this->Process->find('count');
	$published = $this->Process->find('count',array('conditions'=>array('Process.publish'=>1)));
	$unpublished = $this->Process->find('count',array('conditions'=>array('Process.publish'=>0)));
		
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
		if (!$this->Process->exists($id)) {
			throw new NotFoundException(__('Invalid process'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			
			if ($this->Process->save($this->request->data)) {

				
				$processTeam['ProcessTeam'] = $this->request->data['ProcessTeam'];
				$processTeam['ProcessTeam']['name'] = $this->request->data['ProcessTeam']['name'];
				$processTeam['ProcessTeam']['owner_id'] = $this->request->data['Process']['owner_id'];
				$processTeam['ProcessTeam']['branch_id'] = json_encode($this->request->data['ProcessTeam_branch_id']);
				$processTeam['ProcessTeam']['department_id'] = json_encode($this->request->data['ProcessTeam_department_id']);
				$processTeam['ProcessTeam']['team'] = json_encode($this->request->data['ProcessTeam_team']);				
				$processTeam['ProcessTeam']['process_id'] = $this->Process->id;
				$processTeam['ProcessTeam']['objective_id'] = $this->request->data['Process']['objective_id'];
				$processTeam['ProcessTeam']['prepared_by'] = $this->request->data['Process']['prepared_by'];
				$processTeam['ProcessTeam']['approved_by'] = $this->request->data['Process']['approved_by'];
				$processTeam['ProcessTeam']['publish'] = $this->request->data['Process']['publish'];
				$processTeam['ProcessTeam']['start_date'] = $this->request->data['Process']['start_date'];
				$processTeam['ProcessTeam']['end_date'] = $this->request->data['Process']['end_date'];				
				$this->Process->ProcessTeam->create();
				$this->Process->ProcessTeam->id = $processTeam['ProcessTeam']['id'];
				$this->Process->ProcessTeam->save($processTeam,false);

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The process could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Process.' . $this->Process->primaryKey => $id));
			$this->request->data = $this->Process->find('first', $options);
		}

		//get processTeams 
		$processTeams = $this->Process->ProcessTeam->find('all',array('conditions'=>array(
				'ProcessTeam.process_id'=>$this->data['Process']['id'],
				'ProcessTeam.objective_id'=>$this->data['Process']['objective_id']
			)));
		
		$objectives = $this->Process->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
		$owners = $this->Process->Owner->find('list',array('conditions'=>array('Owner.publish'=>1,'Owner.soft_delete'=>0)));
		$inputProcesses = $this->Process->InputProcess->find('list',array('conditions'=>array('InputProcess.publish'=>1,'InputProcess.soft_delete'=>0)));
		$outputProcesses = $this->Process->OutputProcess->find('list',array('conditions'=>array('OutputProcess.publish'=>1,'OutputProcess.soft_delete'=>0)));
		$schedules = $this->Process->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$systemTables = $this->Process->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Process->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Process->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Process->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Process->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Process->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Process->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processTeams', 'objectives', 'owners', 'inputProcesses', 'outputProcesses', 'schedules', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));

		$count = $this->Process->find('count');
		$published = $this->Process->find('count',array('conditions'=>array('Process.publish'=>1)));
		$unpublished = $this->Process->find('count',array('conditions'=>array('Process.publish'=>0)));
		
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
		if (!$this->Process->exists($id)) {
			throw new NotFoundException(__('Invalid process'));
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
			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;

			if ($this->Process->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->Process->save($this->request->data)) {

				foreach ($this->request->data['ProcessTeam'] as $team) {
					$processTeam['ProcessTeam'] = $team;
					$processTeam['ProcessTeam']['name'] = $team['name'];
					$processTeam['ProcessTeam']['owner_id'] = $team['owner_id'];
					$processTeam['ProcessTeam']['branch_id'] = json_encode($team['branch_id']);
					$processTeam['ProcessTeam']['department_id'] = json_encode($team['department_id']);
					$processTeam['ProcessTeam']['team'] = json_encode($team['team']);				
					$processTeam['ProcessTeam']['process_id'] = $this->Process->id;
					$processTeam['ProcessTeam']['objective_id'] = $this->request->data['Process']['objective_id'];
					$processTeam['ProcessTeam']['prepared_by'] = $this->request->data['Process']['prepared_by'];
					$processTeam['ProcessTeam']['approved_by'] = $this->request->data['Process']['approved_by'];
					$processTeam['ProcessTeam']['publish'] = $this->request->data['Process']['publish'];
					$processTeam['ProcessTeam']['start_date'] = $team['start_date'];
					$processTeam['ProcessTeam']['end_date'] = $team['end_date'];				
					$this->Process->ProcessTeam->create();
					$this->Process->ProcessTeam->id = $team['id'];
					$this->Process->ProcessTeam->save($processTeam,false);
				}
				

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The process could not be saved. Please, try again.'));
			}
				
			} else {
				$this->Session->setFlash(__('The process could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Process.' . $this->Process->primaryKey => $id));
			$this->request->data = $this->Process->find('first', $options);
		}
		$objectives = $this->Process->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
		$owners = $this->Process->Owner->find('list',array('conditions'=>array('Owner.publish'=>1,'Owner.soft_delete'=>0)));
		$inputProcesses = $this->Process->InputProcess->find('list',array('conditions'=>array('InputProcess.publish'=>1,'InputProcess.soft_delete'=>0)));
		$outputProcesses = $this->Process->OutputProcess->find('list',array('conditions'=>array('OutputProcess.publish'=>1,'OutputProcess.soft_delete'=>0)));
		$schedules = $this->Process->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$systemTables = $this->Process->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Process->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Process->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Process->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Process->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Process->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Process->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		
		//get processTeams 
		$processTeams = $this->Process->ProcessTeam->find('all',array('conditions'=>array(
				'ProcessTeam.process_id'=>$this->data['Process']['id'],
				'ProcessTeam.objective_id'=>$this->data['Process']['objective_id']
			)));

		$this->set(compact('processTeams', 'objectives', 'owners', 'inputProcesses', 'outputProcesses', 'schedules', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));

		$count = $this->Process->find('count');
		$published = $this->Process->find('count',array('conditions'=>array('Process.publish'=>1)));
		$unpublished = $this->Process->find('count',array('conditions'=>array('Process.publish'=>0)));
		
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
		$this->Process->id = $id;
		if (!$this->Process->exists()) {
			throw new NotFoundException(__('Invalid process'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Process->delete()) {
			$this->Session->setFlash(__('Process deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Process was not deleted'));
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
		
		$result = explode('+',$this->request->data['processes']['rec_selected']);
		$this->Process->recursive = 1;
		$processes = $this->Process->find('all',array('Process.publish'=>1,'Process.soft_delete'=>1,'conditions'=>array('or'=>array('Process.id'=>$result))));
		$this->set('processes', $processes);
		
		$objectives = $this->Process->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
		$owners = $this->Process->Owner->find('list',array('conditions'=>array('Owner.publish'=>1,'Owner.soft_delete'=>0)));
		$inputProcesses = $this->Process->InputProcess->find('list',array('conditions'=>array('InputProcess.publish'=>1,'InputProcess.soft_delete'=>0)));
		$outputProcesses = $this->Process->OutputProcess->find('list',array('conditions'=>array('OutputProcess.publish'=>1,'OutputProcess.soft_delete'=>0)));
		$schedules = $this->Process->Schedule->find('list',array('conditions'=>array('Schedule.publish'=>1,'Schedule.soft_delete'=>0)));
		$systemTables = $this->Process->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Process->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Process->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Process->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Process->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Process->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Process->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('objectives', 'owners', 'inputProcesses', 'outputProcesses', 'schedules', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

<?php
App::uses('AppController', 'Controller');
/**
 * ObjectiveMonitorings Controller
 *
 * @property ObjectiveMonitoring $ObjectiveMonitoring
 */
class ObjectiveMonitoringsController extends AppController {

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
	$this->paginate = array('order'=>array('ObjectiveMonitoring.created'=>'DESC'),'conditions'=>array($conditions));
	
	$this->ObjectiveMonitoring->recursive = 0;
	$this->set('objectiveMonitorings', $this->paginate());

	$this->_get_count();

	// get kpis
	$this->loadModel('ListOfKpi');
	$listOfKpis = $this->ListOfKpi->find('list');
	$this->set('listOfKpis',$listOfKpis);
}



/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
public function box() {
	
	$conditions = $this->_check_request();
	$this->paginate = array('order'=>array('ObjectiveMonitoring.sr_no'=>'DESC'),'conditions'=>array($conditions));

	$this->ObjectiveMonitoring->recursive = 0;
	$this->set('objectiveMonitorings', $this->paginate());

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
		$search_keys = explode(" ",$this->request->data['ObjectiveMonitoring']['search']);

		foreach($search_keys as $search_key):
			foreach($this->request->data['ObjectiveMonitoring']['search_field'] as $search):
				$search_array[] = array('ObjectiveMonitoring.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
			endforeach;

			if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ObjectiveMonitoring.branch_id'=>$this->Session->read('User.branch_id'));
			}

			$this->ObjectiveMonitoring->recursive = 0;
			$this->paginate = array('order'=>array('ObjectiveMonitoring.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ObjectiveMonitoring.soft_delete'=>0 , $cons));
			$this->set('objectiveMonitorings', $this->paginate());
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
		if($this->request->query['keywords']){
			$search_array = array();
			$search_keys = explode(" ",$this->request->query['keywords']);

			foreach($search_keys as $search_key):
				foreach($this->request->query['search_fields'] as $search):
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ObjectiveMonitoring.'.$search => $search_key);
				else $search_array[] = array('ObjectiveMonitoring.'.$search.' like ' => '%'.$search_key.'%');

				endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
			if($this->request->query['branch_list']){
				foreach($this->request->query['branch_list'] as $branches):
					$branch_conditions[]=array('ObjectiveMonitoring.branch_id'=>$branches);
				endforeach;
				$conditions[]=array('or'=>$branch_conditions);
			}

			if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
			if($this->request->query['from-date']){
				$conditions[] = array('ObjectiveMonitoring.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ObjectiveMonitoring.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
			}
			unset($this->request->query);


			if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ObjectiveMonitoring.branch_id'=>$this->Session->read('User.branch_id'));
			if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ObjectiveMonitoring.created_by'=>$this->Session->read('User.id'));
			$conditions[] = array($onlyBranch,$onlyOwn);

			$this->ObjectiveMonitoring->recursive = 0;
			$this->paginate = array('order'=>array('ObjectiveMonitoring.sr_no'=>'DESC'),'conditions'=>$conditions , 'ObjectiveMonitoring.soft_delete'=>0 );
			if(isset($_GET['limit']) && $_GET['limit'] != 0){
				$this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
			}
			$this->set('objectiveMonitorings', $this->paginate());
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
	if (!$this->ObjectiveMonitoring->exists($id)) {
		throw new NotFoundException(__('Invalid objective monitoring'));
	}
	$options = array('conditions' => array('ObjectiveMonitoring.' . $this->ObjectiveMonitoring->primaryKey => $id));
	$this->set('objectiveMonitoring', $this->ObjectiveMonitoring->find('first', $options));
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

	
	//get objective  proces details
	if(isset($this->request->params['named']['process_id'])){
		$objective = $this->ObjectiveMonitoring->Objective->find('first',array('recursive'=>2, 'conditions'=>array('Objective.id'=>$this->request->params['named']['objective_id'])));
	}else{
		$objective = $this->ObjectiveMonitoring->Objective->find('first',array('recursive'=>0, 'conditions'=>array('Objective.id'=>$this->request->params['named']['objective_id'])));
		$monitorings = $this->ObjectiveMonitoring->find('all',array('recursive'=>0, 'conditions'=>array('ObjectiveMonitoring.id'=>$this->request->params['named']['objective_id'])));
	}
	
	$this->set('objective',$objective);
	$this->set('monitorings',$monitorings);


	if(isset($this->request->params['named']['process_id'])){
		$process = $this->ObjectiveMonitoring->Process->find('first',array('conditions' => array(			
			'Process.id'=>$this->request->params['named']['process_id']),
		'recursive'=>2));
		$this->set('process',$process);
		$last = $this->ObjectiveMonitoring->find('first',array('conditions'=>array(
			'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
			'ObjectiveMonitoring.process_id'=>$process['Process']['id']),
		'recursive'=>0,
		'fields'=>array('ObjectiveMonitoring.id','ObjectiveMonitoring.created','ObjectiveMonitoring.process_id','ObjectiveMonitoring.objective_id')
		));

		if($last){ 
			$this->set('last_monitoring',$last);
		}

		foreach($process['Task'] as $tasks){
			$this->task_completions($tasks['process_id'],$tasks['id'],$last['ObjectiveMonitoring']['created']);
		}
	}


	if ($this->request->is('post')) {

		$this->request->data['ObjectiveMonitoring']['system_table_id'] = $this->_get_system_table_id();
		$this->ObjectiveMonitoring->create();
		if ($this->ObjectiveMonitoring->save($this->request->data)) {


			if ($this->_show_approvals()) $this->_save_approvals();
			$this->Session->setFlash(__('The objective monitoring has been saved'));
			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ObjectiveMonitoring->id));
			else if($this->request->params['named']['process_id'] && $this->request->params['named']['objective_id'])$this->redirect(array('controller'=>'users', 'action' => 'dashboard'));
			else $this->redirect(array('action' => 'view',$this->ObjectiveMonitoring->id));
			
		} else {
			$this->Session->setFlash(__('The objective monitoring could not be saved. Please, try again.'));
		}
	}
	if($this->request->params['named']['process_id'])$processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.process_id' => $this->request->params['named']['process_id'], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
	else $processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.objective_id' => $this->request->params['named']['objective_id'], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));

	$objectives = $this->ObjectiveMonitoring->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
	$employees = $this->ObjectiveMonitoring->Objective->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
	$processes = $this->ObjectiveMonitoring->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0,'Process.objective_id' => $this->request->params['named']['objective_id'])));
	$systemTables = $this->ObjectiveMonitoring->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	$masterListOfFormats = $this->ObjectiveMonitoring->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->ObjectiveMonitoring->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$preparedBies = $this->ObjectiveMonitoring->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	$approvedBies = $this->ObjectiveMonitoring->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	$createdBies = $this->ObjectiveMonitoring->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	$modifiedBies = $this->ObjectiveMonitoring->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	$this->set(compact('processTeams', 'objectives', 'processes', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ObjectiveMonitoring->find('count');
	$published = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>1)));
	$unpublished = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>0)));
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
		$this->request->data['ObjectiveMonitoring']['system_table_id'] = $this->_get_system_table_id();
		$this->ObjectiveMonitoring->create();
		if ($this->ObjectiveMonitoring->save($this->request->data)) {

			if($this->_show_approvals()){
				$this->loadModel('Approval');
				$this->Approval->create();
				$this->request->data['Approval']['model_name']='ObjectiveMonitoring';
				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
				$this->request->data['Approval']['from']=$this->Session->read('User.id');
				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
				$this->request->data['Approval']['record']=$this->ObjectiveMonitoring->id;
				$this->Approval->save($this->request->data['Approval']);
			}
			$this->Session->setFlash(__('The objective monitoring has been saved'));
			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ObjectiveMonitoring->id));
			else $this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash(__('The objective monitoring could not be saved. Please, try again.'));
		}
	}
	if($this->request->params['pass'][0])$processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.objective_id' => $this->request->params['pass'][0], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
	else $processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.objective_id' => $this->request->params['pass'][0], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
	$objectives = $this->ObjectiveMonitoring->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
	$processes = $this->ObjectiveMonitoring->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0,'Process.objective_id' => $this->request->params['named']['objective_id'])));
	$systemTables = $this->ObjectiveMonitoring->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	$masterListOfFormats = $this->ObjectiveMonitoring->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->ObjectiveMonitoring->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$preparedBies = $this->ObjectiveMonitoring->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	$approvedBies = $this->ObjectiveMonitoring->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	$createdBies = $this->ObjectiveMonitoring->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	$modifiedBies = $this->ObjectiveMonitoring->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	$this->set(compact('processTeams', 'objectives', 'processes', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ObjectiveMonitoring->find('count');
	$published = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>1)));
	$unpublished = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>0)));
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
	if (!$this->ObjectiveMonitoring->exists($id)) {
		throw new NotFoundException(__('Invalid objective monitoring'));
	}

	if ($this->_show_approvals()) {
		$this->set(array('showApprovals' => $this->_show_approvals()));
	}

	if ($this->request->is('post') || $this->request->is('put')) {

		if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
			$this->request->data[$this->modelClass]['publish'] = 0;
		}

		$this->request->data['ObjectiveMonitoring']['system_table_id'] = $this->_get_system_table_id();
		if ($this->ObjectiveMonitoring->save($this->request->data)) {

			if ($this->_show_approvals()) $this->_save_approvals();

			if ($this->_show_evidence() == true)
				$this->redirect(array('action' => 'view', $id));
			else
				$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash(__('The objective monitoring could not be saved. Please, try again.'));
		}
	} else {
		$options = array('conditions' => array('ObjectiveMonitoring.' . $this->ObjectiveMonitoring->primaryKey => $id));
		$this->request->data = $this->ObjectiveMonitoring->find('first', $options);
	}

	if($this->request->data['ObjectiveMonitoring']['objective_id'])$processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.objective_id' => $this->request->data['ObjectiveMonitoring']['objective_id'], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
	else $processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.objective_id' => $this->request->data['ObjectiveMonitoring']['objective_id'], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
	$objectives = $this->ObjectiveMonitoring->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
	$processes = $this->ObjectiveMonitoring->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0,'Process.objective_id' => $this->request->data['ObjectiveMonitoring']['objective_id'])));
	$systemTables = $this->ObjectiveMonitoring->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	$masterListOfFormats = $this->ObjectiveMonitoring->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->ObjectiveMonitoring->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$preparedBies = $this->ObjectiveMonitoring->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	$approvedBies = $this->ObjectiveMonitoring->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	$createdBies = $this->ObjectiveMonitoring->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	$modifiedBies = $this->ObjectiveMonitoring->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	$this->set(compact('processTeams', 'objectives', 'processes', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ObjectiveMonitoring->find('count');
	$published = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>1)));
	$unpublished = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>0)));
	$this->set(compact('count','published','unpublished'));

	$objective = $this->ObjectiveMonitoring->Objective->find('first',array('recursive'=>2, 'conditions'=>array('Objective.id'=>$this->request->data['ObjectiveMonitoring']['objective_id'])));
	$this->set('objective',$objective);
	if(isset($this->request->data['ObjectiveMonitoring']['process_id'])){
		$process = $this->ObjectiveMonitoring->Process->find('first',array('conditions' => array(			
			'Process.id'=>$this->request->data['ObjectiveMonitoring']['process_id']),
		'recursive'=>2));
		$this->set('process',$process);
		$last = $this->ObjectiveMonitoring->find('first',array('conditions'=>array(
			'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
			'ObjectiveMonitoring.process_id'=>$process['Process']['id']),
		'recursive'=>0,
		'fields'=>array('ObjectiveMonitoring.id','ObjectiveMonitoring.created','ObjectiveMonitoring.process_id','ObjectiveMonitoring.objective_id')
		));

		if($last){ 
			$this->set('last_monitoring',$last);
		}

		foreach($process['Task'] as $tasks){
			$this->task_completions($tasks['process_id'],$tasks['id'],$last['ObjectiveMonitoring']['created']);
		}
	}
}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
public function approve($id = null, $approvalId = null) {
	if (!$this->ObjectiveMonitoring->exists($id)) {
		throw new NotFoundException(__('Invalid objective monitoring'));
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
		if ($this->ObjectiveMonitoring->save($this->request->data)) {
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
				$this->request->data[$this->modelClass]['publish'] = 0;
			}
			if ($this->ObjectiveMonitoring->save($this->request->data)) {
				$this->Session->setFlash(__('The objective monitoring has been saved.'));

				if ($this->_show_approvals()) $this->_save_approvals();

			} else {
				$this->Session->setFlash(__('The objective monitoring could not be saved. Please, try again.'));
			}

		} else {
			$this->Session->setFlash(__('The objective monitoring could not be saved. Please, try again.'));
		}
	} else {
		$options = array('conditions' => array('ObjectiveMonitoring.' . $this->ObjectiveMonitoring->primaryKey => $id));
		$this->request->data = $this->ObjectiveMonitoring->find('first', $options);
	}
	if($this->request->data['ObjectiveMonitoring']['objective_id'])$processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.objective_id' => $this->request->data['ObjectiveMonitoring']['objective_id'], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
	else $processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.objective_id' => $this->request->data['ObjectiveMonitoring']['objective_id'], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
	$objectives = $this->ObjectiveMonitoring->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
	$processes = $this->ObjectiveMonitoring->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0,'Process.objective_id' => $this->request->data['ObjectiveMonitoring']['objective_id'])));
	$systemTables = $this->ObjectiveMonitoring->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	$masterListOfFormats = $this->ObjectiveMonitoring->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->ObjectiveMonitoring->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$preparedBies = $this->ObjectiveMonitoring->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	$approvedBies = $this->ObjectiveMonitoring->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	$createdBies = $this->ObjectiveMonitoring->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	$modifiedBies = $this->ObjectiveMonitoring->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	$this->set(compact('processTeams', 'objectives', 'processes', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ObjectiveMonitoring->find('count');
	$published = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>1)));
	$unpublished = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>0)));
	$this->set(compact('count','published','unpublished'));

	$objective = $this->ObjectiveMonitoring->Objective->find('first',array('recursive'=>2, 'conditions'=>array('Objective.id'=>$this->request->data['ObjectiveMonitoring']['objective_id'])));
	$this->set('objective',$objective);
	if(isset($this->request->data['ObjectiveMonitoring']['process_id'])){
		$process = $this->ObjectiveMonitoring->Process->find('first',array('conditions' => array(			
			'Process.id'=>$this->request->data['ObjectiveMonitoring']['process_id']),
		'recursive'=>2));
		$this->set('process',$process);
		$last = $this->ObjectiveMonitoring->find('first',array('conditions'=>array(
			'ObjectiveMonitoring.objective_id'=>$objective['Objective']['id'],
			'ObjectiveMonitoring.process_id'=>$process['Process']['id']),
		'recursive'=>0,
		'fields'=>array('ObjectiveMonitoring.id','ObjectiveMonitoring.created','ObjectiveMonitoring.process_id','ObjectiveMonitoring.objective_id')
		));

		if($last){ 
			$this->set('last_monitoring',$last);
		}

		foreach($process['Task'] as $tasks){
			$this->task_completions($tasks['process_id'],$tasks['id'],$last['ObjectiveMonitoring']['created']);
		}
	}
}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
public function purge($id = null) {
	$this->ObjectiveMonitoring->id = $id;
	if (!$this->ObjectiveMonitoring->exists()) {
		throw new NotFoundException(__('Invalid objective monitoring'));
	}
	$this->request->onlyAllow('post', 'delete');
	if ($this->ObjectiveMonitoring->delete()) {
		$this->Session->setFlash(__('Objective monitoring deleted'));
		$this->redirect(array('action' => 'index'));
	}
	$this->Session->setFlash(__('Objective monitoring was not deleted'));
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

       	$result = explode('+',$this->request->data['objectiveMonitorings']['rec_selected']);
       	$this->ObjectiveMonitoring->recursive = 1;
       	$objectiveMonitorings = $this->ObjectiveMonitoring->find('all',array('ObjectiveMonitoring.publish'=>1,'ObjectiveMonitoring.soft_delete'=>1,'conditions'=>array('or'=>array('ObjectiveMonitoring.id'=>$result))));
       	$this->set('objectiveMonitorings', $objectiveMonitorings);

       	if($this->request->params['pass'][0])$processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.objective_id' => $this->request->params['pass'][0], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
       	else $processTeams = $this->ObjectiveMonitoring->Process->ProcessTeam->find('list',array('conditions'=>array('ProcessTeam.objective_id' => $this->request->params['pass'][0], 'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
       	$objectives = $this->ObjectiveMonitoring->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
       	$processes = $this->ObjectiveMonitoring->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0,'Process.objective_id' => $this->request->params['named']['objective_id'])));
       	$systemTables = $this->ObjectiveMonitoring->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
       	$masterListOfFormats = $this->ObjectiveMonitoring->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
       	$companies = $this->ObjectiveMonitoring->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
       	$preparedBies = $this->ObjectiveMonitoring->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
       	$approvedBies = $this->ObjectiveMonitoring->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
       	$createdBies = $this->ObjectiveMonitoring->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
       	$modifiedBies = $this->ObjectiveMonitoring->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
       	$this->set(compact('processTeams', 'objectives', 'processes', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
       	$count = $this->ObjectiveMonitoring->find('count');
       	$published = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>1)));
       	$unpublished = $this->ObjectiveMonitoring->find('count',array('conditions'=>array('ObjectiveMonitoring.publish'=>0)));
       	$this->set(compact('count','published','unpublished'));
       }

       public function task_completions($process_id = null , $task_id = null, $from_date = null ){

       	if($from_date == null)$from = date('Y-m-1');
       	else $from = $from_date;
       	$to = date('Y-m-d');

       	$userCondition = NULL;
       	$taskAssigned = 0;
       	$taskPerformed = 0;

       	$this->loadModel('Schedule');
       	$schedulesList = $this->Schedule->find('list', array('conditions' => array('Schedule.publish' => 1, 'Schedule.soft_delete' => 0)));

       	$this->loadModel('Task');
       	$this->loadModel('TaskStatus');
       	$this->Task->recursive = 0;
       	$schedules = array();
       	if ($from) {
       		foreach ($schedulesList as $key => $value):
       			if ($value == 'dailly' || $value == 'daily' || $value == 'Dailly' || $value == 'Daily') {
       				while ($from <= $to) {
       					$schedules[$value][$from] = $this->Task->find('all', array(
       						'conditions' => array('Task.id' => $task_id, 'Task.schedule_id' => $key,
       							'Task.created < ' => date('Y-m-d 59:59:59', strtotime($from)),
       							$userCondition),
       						'order' => array('MasterListOfFormat.title' => 'Desc'),
       						'recursive' => 0,
       						'fields' => array('Task.id','Task.name', 'MasterListOfFormat.title', 'User.name')));

       					$i = 0;
       					foreach ($schedules[$value][$from] as $task):
       						$taskStatus = $this->TaskStatus->find('first', array(
       							'conditions' => array(
       								'TaskStatus.task_date' => $from,
       								'TaskStatus.task_id' => $task['Task']['id'])));
       					$schedules[$value][$from][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
       					if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
       						$taskPerformed++;
       					$i++;
       					$taskAssigned++;
       					endforeach;
       					$from = date("Y-m-d", strtotime("+1 day", strtotime($from)));
       				}



       			} else if ($value == 'weekly' || $value == 'Weekly') {
       				$startDateUnix = strtotime($from);
       				$endDateUnix = strtotime($to);
       				$currentDateUnix = $startDateUnix;
       				$weekNumbers = array();
       				while ($currentDateUnix < $endDateUnix) {
       					$year = date('Y', $currentDateUnix);
       					$weekNumbers[$year][] = date('W', $currentDateUnix);
       					$currentDateUnix = strtotime('+1 week', $currentDateUnix);
       				}
       				foreach ($weekNumbers as $yy => $yweek) {
       					foreach ($yweek as $ww) {
       						$schedules[$value][$yy . '-Week-' . $ww] = $this->Task->find('all', array(
       							'conditions' => array('Task.id' => $task_id, 'Task.schedule_id' => $key,
       								'Task.schedule_id' => $key,
       								array("OR" => array("AND" => array(
       									"WEEK(Task.created) <=" => $ww,
       									"YEAR(Task.created) =" => $yy),
       								array("YEAR(Task.created) <" => $yy))),
       								$userCondition),
       							'fields' => array(
       								'Task.id',
       								'Task.name',
       								'MasterListOfFormat.title',
       								'User.name',
       								)));
       						$i = 0;
       						foreach ($schedules[$value][$yy . '-Week-' . $ww] as $task):
       							$schedules[$value][$yy . '-Week-' . $ww][$i]['TaskStatus'] = null;
       						$taskStatus = $this->TaskStatus->find('first', array(
       							'conditions' => array(
       								'WEEK(TaskStatus.task_date)' => $ww,
       								'YEAR(TaskStatus.task_date)' => $yy,
       								'TaskStatus.task_id' => $task['Task']['id'])));
       						$schedules[$value][$yy . '-Week-' . $ww][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
       						if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
       							$taskPerformed++;
       						$i++;
       						$taskAssigned++;
       						endforeach;
       					}
       				}
       			}else if ($value == 'monthly' || $value == 'Monthly') {
       				$startDateUnix = strtotime($from);
       				$endDateUnix = strtotime($to);
       				$currentDateUnix = $startDateUnix;
       				$monthNumbers = array();
       				while ($currentDateUnix < $endDateUnix) {
       					$year = date('Y', $currentDateUnix);
       					$monthNumbers[$year][] = IntVal(date('m', $currentDateUnix));
       					$currentDateUnix = strtotime('+1 month', $currentDateUnix);
       				}

       				foreach ($monthNumbers as $yy => $ymonth) {
       					foreach ($ymonth as $ww) {
       						$schedules[$value][$yy . '-Month-' . $ww] = $this->Task->find('all', array(
       							'conditions' => array('Task.id' => $task_id, 'Task.schedule_id' => $key,'Task.schedule_id' => $key,
       								array("OR" => array("AND" => array("MONTH(Task.created) <=" => $ww,
       									"YEAR(Task.created) =" => $yy), array("YEAR(Task.created) <" => $yy))), $userCondition,
       								),
       							'fields' => array(
       								'Task.id',
       								'Task.name',
       								'MasterListOfFormat.title',
       								'User.name',
       								)
       							));
       						$i = 0;
       						foreach ($schedules[$value][$yy . '-Month-' . $ww] as $task):
       							$schedules[$value][$yy . '-Month-' . $ww][$i]['TaskStatus'] = null;
       						$taskStatus = $this->TaskStatus->find('first', array(
       							'conditions' => array(
       								'MONTH(TaskStatus.task_date) <= ' => $ww,
       								'YEAR(TaskStatus.task_date) <= ' => $yy,
       								'TaskStatus.task_id' => $task['Task']['id'])));
       						$schedules[$value][$yy . '-Month-' . $ww][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
       						if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
       							$taskPerformed++;
       						$i++;
       						$taskAssigned++;
       						endforeach;
       					}
       				}
       			}else if ($value == 'quarterly' || $value == 'Quarterly') {
       				$taskDetails = $this->Task->find('all', array(
       					'conditions' => array('Task.id' => $task_id, 'Task.schedule_id' => $key, 'Task.schedule_id' => $key,
       						"DATE_FORMAT(Task.created, '%Y-%m-%d') <=" => $to, $userCondition,
       						),
       					'fields' => array(
       						'Task.id',
       						'Task.name',
       						'Task.created',
       						'MasterListOfFormat.title',
       						'User.name',
       						)
       					));

       				foreach ($taskDetails as $taskDetail) {
       					$created = date('Y-m-d', strtotime($taskDetail['Task']['created']));
       					$currentDate = date('Y-m-d', strtotime($taskDetail['Task']['created']));
       					$lastQuarter = $to;
       					$nextQuarter = $to;

       					$dateArray = array();
       					$k = 0;
       					while ($currentDate <= $lastQuarter) {
       						$nextQuarter = date('Y-m-d', strtotime('+3 month', strtotime($currentDate)));
       						if ($currentDate >= $from) {
       							$dateArray[$k]['currentDate'] = $currentDate;
       							$dateArray[$k]['nextQuarter'] = $nextQuarter;
       							$k++;
       						}
       						$currentDate = $nextQuarter;
       					}
       					$i = 0;
       					foreach ($dateArray as $ww => $quarter) {
       						$cDate = $quarter['currentDate'];
       						$nextQuarter = $quarter['nextQuarter'];
       						$qNumber = $ww + 1;
       						$schedules[$value]['Quarter-' . $qNumber][$i] = $taskDetail;
       						$schedules[$value]['Quarter-' . $qNumber][$i]['TaskStatus'] = null;
       						$taskStatus = $this->TaskStatus->find('first', array(
       							'conditions' => array(
       								"DATE_FORMAT(TaskStatus.task_date, '%Y-%m-%d') >=" => $cDate, "DATE_FORMAT(TaskStatus.task_date, '%Y-%m-%d') <=" => $nextQuarter,
       								'TaskStatus.task_id' => $taskDetail['Task']['id'])));

       						$schedules[$value]['Quarter-' . $qNumber][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
       						if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
       							$taskPerformed++;
       						$i++;
       						$taskAssigned++;
       					}
       				}
       			}else if ($value == 'yearly' || $value == 'Yearly') {
       				$startDateUnix = strtotime($from);
       				$endDateUnix = strtotime($to);
       				$currentDateUnix = $startDateUnix;
       				$yearNumbers = array();
       				while ($currentDateUnix < $endDateUnix) {
       					$year = date('Y', $currentDateUnix);
       					$yearNumbers[$year][] = IntVal(date('Y', $currentDateUnix));
       					$currentDateUnix = strtotime('+1 year', $currentDateUnix);
       				}

       				foreach ($yearNumbers as $yy => $yyear) {
       					foreach ($yyear as $ww) {
       						$schedules[$value][$yy . '-Year-' . $ww] = $this->Task->find('all', array(
       							'conditions' => array('Task.id' => $task_id, 'Task.schedule_id' => $key, 'Task.schedule_id' => $key,
       								"YEAR(Task.created) <=" => $yy, $userCondition,
       								),
       							'fields' => array(
       								'Task.id',
       								'Task.name',
       								'Task.created',
       								'MasterListOfFormat.title',
       								'User.name',
       								)
       							));
       						$i = 0;
       						foreach ($schedules[$value][$yy . '-Year-' . $ww] as $task):
       							$schedules[$value][$yy . '-Year-' . $ww][$i]['TaskStatus'] = null;
       						$taskStatus = $this->TaskStatus->find('first', array(
       							'conditions' => array(
       								'YEAR(TaskStatus.task_date) <= ' => $yy,
       								'TaskStatus.task_id' => $task['Task']['id'])));
       						$schedules[$value][$yy . '-Year-' . $ww][$i]['TaskStatus'] = isset($taskStatus['TaskStatus']) ? $taskStatus['TaskStatus']: '';
       						if (isset($taskStatus['TaskStatus']) && $taskStatus['TaskStatus']['task_performed'])
       							$taskPerformed++;
       						$i++;
       						$taskAssigned++;
       						endforeach;
       					}
       				}
       			}
       			endforeach;
       		}
       		if ($report == true) {
       			return $schedules;
       		}
       		$users = $this->requestAction('App/get_usernames');
       		if ($taskPerformed > 0 && $taskAssigned > 0)
       			$result = round($taskPerformed * 100 / $taskAssigned);
       		else
       			$result = 0;

       		$from = $this->request['data']['TaskStatus']['from_date'];



       		$this->set(compact('schedules', 'from', 'to', 'users', 'result'));
       	}


       	public function objective_monitoring_chart(){	

       		if(!$from_date && !$to_date){			
       			$from_date = date('Y-m-1',strtotime('-12 months'));
       			$to_date = date('Y-m-t');    		
       		}


       		$conditions  = array();
       		if ($this->request->is('post') || $this->request->is('put')) {
       			if($this->request->data['ObjectiveMonitoring']['objective_id'] && $this->request->data['ObjectiveMonitoring']['objective_id'] != '-1')$conditions = array($conditions, 'ObjectiveMonitoring.objective_id' => $this->request->data['ObjectiveMonitoring']['objective_id']);
       			if($this->request->data['ObjectiveMonitoring']['process_id'] && $this->request->data['ObjectiveMonitoring']['process_id'] != '-1')$conditions = array($conditions, 'ObjectiveMonitoring.process_id' => $this->request->data['ObjectiveMonitoring']['process_id']);
       			if($this->request->data['ObjectiveMonitoring']['from_date'])$from_date = $this->request->data['ObjectiveMonitoring']['from_date'];
       			if($this->request->data['ObjectiveMonitoring']['to_date'])$to_date = $this->request->data['ObjectiveMonitoring']['to_date'];
       		}    	
       		$final = array();
       		while (strtotime($from_date) <= strtotime($to_date)) {

       			$score = 0;
       			$schedules = $this->ObjectiveMonitoring->Schedule->find('list',array('Schedule.publish'=>1,'Schedule.soft_delete'=>0));

       			foreach($schedules as $key => $name){
       				$score = 0;
       				$objectiveMonitorings = $this->ObjectiveMonitoring->find('all',array(
       					'fields'=>array('ObjectiveMonitoring.id','ObjectiveMonitoring.schedule_id','ObjectiveMonitoring.completion'),
       					'recursive' => -1,
       					'conditions' => array($conditions, 'ObjectiveMonitoring.schedule_id'=>$key , 
       						'ObjectiveMonitoring.created BETWEEN ? AND ?' => array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
       						)));

       				if($objectiveMonitorings){
       					foreach ($objectiveMonitorings as $monitoring) {						
       						$score = $score + $monitoring['ObjectiveMonitoring']['completion'];
       						$final[$name][date('Y-m',strtotime($from_date))] = $score / count($objectiveMonitorings);						
       					}
       				}else{
       					$final[$name][date('Y-m',strtotime($from_date))] = $score;					
       				}				
       			}




       			$from_date = date("Y-m-d", strtotime("+1 month", strtotime($from_date)));	
       		}
       		$this->set('final',$final);


       		$objectives = $this->ObjectiveMonitoring->Objective->find('list',array('conditions'=>array('Objective.publish'=>1,'Objective.soft_delete'=>0)));
       		$processes = $this->ObjectiveMonitoring->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
       		$this->set(compact('objectives', 'processes'));

       		if($this->request->data){
       			$this->set('selected_objective',$this->request->data['ObjectiveMonitoring']['objective_id']);
       			$this->set('selected_processes',$this->request->data['ObjectiveMonitoring']['objective_id']);
       		}

       	}
       	public function objective_monitoring(){			
       		if(!$from_date && !$to_date){			
       			$from_date = date('Y-m-1',strtotime('-2 months'));
       			$to_date = date('Y-m-t');    		
       		}


       		$conditions  = array();
       		if ($this->request->is('post') || $this->request->is('put')) {
       			if($this->request->data['ObjectiveMonitoring']['objective_id'] && $this->request->data['ObjectiveMonitoring']['objective_id'] != '-1')$conditions = array($conditions, 'ObjectiveMonitoring.objective_id' => $this->request->data['ObjectiveMonitoring']['objective_id']);
       			if($this->request->data['ObjectiveMonitoring']['process_id'] && $this->request->data['ObjectiveMonitoring']['process_id'] != '-1')$conditions = array($conditions, 'ObjectiveMonitoring.process_id' => $this->request->data['ObjectiveMonitoring']['process_id']);
       			if($this->request->data['ObjectiveMonitoring']['from_date'])$from_date = $this->request->data['ObjectiveMonitoring']['from_date'];
       			if($this->request->data['ObjectiveMonitoring']['to_date'])$to_date = $this->request->data['ObjectiveMonitoring']['to_date'];
       		}    	
       		$final = array();
       		$objectives = $this->ObjectiveMonitoring->Objective->find('all',array('recursive'=>'-1'));
       		$i=0;
       		while (strtotime($from_date) <= strtotime($to_date)) {
       			foreach ($objectives as $objective) {
       				$final[$from_date]['Objectives'][$i] = $objective;
       				$processes = $this->ObjectiveMonitoring->Objective->Process->find('all',array('recursive'=>'-1','conditions'=>array('Process.objective_id'=>$objective['Objective']['id'])));
       				foreach ($processes as $process) {
       					$final[$from_date]['Objectives'][$i]['Process'][] = $process;
       				}					
       				$i++;
       			}			
       			$from_date = date("Y-m-d", strtotime("+1 month", strtotime($from_date)));			
       		}
		
       	}

       	public function get_process_list($objective_id = null){
       		$processes = $this->ObjectiveMonitoring->Objective->Process->find('list',array('conditions'=>array('Process.objective_id'=>$objective_id,'Process.publish'=>1,'Process.soft_delete'=>0)));
       		$this->set('processes',$processes);
       	}

       	public function get_process_team($process_id = null){
       		$users = $this->_get_user_list();
       		$team = $this->ObjectiveMonitoring->Objective->Process->ProcessTeam->find('list',array('fields'=>array('ProcessTeam.process_id','ProcessTeam.team'), 'conditions'=>array('ProcessTeam.process_id'=>$process_id,'ProcessTeam.publish'=>1,'ProcessTeam.soft_delete'=>0)));
       		$team = array_values($team);
       		$team = json_decode($team[0],true);

       		foreach ($team as $user) {
       			$user_list[$user] = $users[$user];
       		}
       		$this->set('team',$user_list);

       	}

       }

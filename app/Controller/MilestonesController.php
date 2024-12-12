<?php
App::uses('AppController', 'Controller');
/**
 * Milestones Controller
 *
 * @property Milestone $Milestone
 */
class MilestonesController extends AppController {

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

	if(isset($this->request->params['named']['project_id'])){
		$project_conditions = array('Milestone.project_id'=>$this->request->params['named']['project_id']);
		if($this->request->params['named']['project_id']){
			$project_details = $this->requestAction(array('controller'=>'projects','action'=>'view',$this->request->params['named']['project_id']));
			$this->set('project_details',$project_details[1]);
			$project = $project_details[0]; 
			$this->set('project',$project);            
		}
	}

	// $conditions = $this->_check_request();
	// $this->paginate = array('order'=>array('Milestone.sr_no'=>'DESC'),'conditions'=>array($conditions,$project_conditions));
	
	// $this->Milestone->recursive = 0;
	// $this->set('milestones', $this->paginate());

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
	$this->paginate = array('order'=>array('Milestone.sr_no'=>'DESC'),'conditions'=>array($conditions));

	$this->Milestone->recursive = 0;
	$this->set('milestones', $this->paginate());

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
		$search_keys = explode(" ",$this->request->data['Milestone']['search']);

		foreach($search_keys as $search_key):
			foreach($this->request->data['Milestone']['search_field'] as $search):
				$search_array[] = array('Milestone.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
			endforeach;

			if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('Milestone.branch_id'=>$this->Session->read('User.branch_id'));
			}

			$this->Milestone->recursive = 0;
			$this->paginate = array('order'=>array('Milestone.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Milestone.soft_delete'=>0 , $cons));
			$this->set('milestones', $this->paginate());
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
		// if($this->request->query['keywords']){
			$search_array = array();
			$search_keys = explode(" ",$this->request->query['keywords']);

			foreach($search_keys as $search_key):
				foreach($this->request->query['search_fields'] as $search):
					if($this->request->query['strict_search'] == 0)$search_array[] = array('Milestone.'.$search => $search_key);
				else $search_array[] = array('Milestone.'.$search.' like ' => '%'.$search_key.'%');

				endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
			if($this->request->query['branch_list']){
				foreach($this->request->query['branch_list'] as $branches):
					$branch_conditions[]=array('Milestone.branch_id'=>$branches);
				endforeach;
				$conditions[]=array('or'=>$branch_conditions);
			}

			if($this->request->query['project_id']){
				$project_conditions[]=array('Milestone.project_id'=>$this->request->query['project_id']);				
			}

			if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $project_conditions));
            else
                $conditions[] = array('or' => $project_conditions);

			if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
			if($this->request->query['from-date']){
				$conditions[] = array('Milestone.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'Milestone.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
			}
			unset($this->request->query);
			
			if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Milestone.branch_id'=>$this->Session->read('User.branch_id'));
			if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Milestone.created_by'=>$this->Session->read('User.id'));
			$conditions[] = array($onlyBranch,$onlyOwn);

			$this->Milestone->recursive = 0;
			
			$this->paginate = array('order'=>array('Milestone.sr_no'=>'DESC'),'conditions'=>$conditions , 'Milestone.soft_delete'=>0 );
			
			if(isset($_GET['limit']) && $_GET['limit'] != 0){
				$this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
			}
			$this->set('milestones', $this->paginate());
		// }
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
	if (!$this->Milestone->exists($id)) {
		throw new NotFoundException(__('Invalid milestone'));
	}
	$options = array('conditions' => array('Milestone.' . $this->Milestone->primaryKey => $id));
	$milestone = $this->Milestone->find('first', $options);
	$this->set('milestone', $milestone);
	$project_details = $this->requestAction('projects/view/'.$milestone['Milestone']['project_id']);
	$this->set('project_details',$project_details);
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
		$this->request->data['Milestone']['system_table_id'] = $this->_get_system_table_id();
		// $this->Milestone->create();
		if($this->request->data['Milestone']['start_date'] != '' && $this->request->data['Milestone']['end_date'] != ''){
			// Configure::write('debug',1);						
			// debug($this->request->data);
			// exit;
			$milestone['Milestone'] = $this->request->data['Milestone'];
			$milestone['Milestone']['branch_id'] = $this->Session->read('User.branch_id');
			// $milestone['Milestone']['project_id'] = $this->Project->id;						
			// $milestone['Milestone']['publish'] = $this->request->data['Project']['publish'];
			// debug($milestone);
			$this->loadModel('ProjectResource');
			$this->loadModel('ProjectEstimate');
			$this->Milestone->create();
			if($this->Milestone->save($milestone,false)){

				foreach ($this->request->data['ProjectResource'] as $pr) {
					$est = 0;
					$projectResource['ProjectResource']['project_id'] = $this->request->data['Milestone']['project_id'];
					$projectResource['ProjectResource']['milestone_id'] = $this->Milestone->id;
					$projectResource['ProjectResource']['user_id'] = $pr['user_id'];
					$projectResource['ProjectResource']['mandays'] = $pr['mandays'];
					$projectResource['ProjectResource']['resource_cost'] = $pr['resource_cost'];
					$projectResource['ProjectResource']['resource_sub_total'] = $pr['resource_sub_total'];
					$projectResource['ProjectResource']['prepared_by'] = $this->request->data['Project']['prepared_by'];
					$projectResource['ProjectResource']['publish'] = $this->request->data['Project']['publish'];
					$this->ProjectResource->create();
					$this->ProjectResource->save($projectResource,false);


					$activities = explode(PHP_EOL, $pr['activities']);
					if($activities){
						$est = $pr['resource_sub_total'] / count($activities);
						foreach ($activities as $act) {
							$proAct['project_resource_id'] = $this->ProjectResource->id;
							$proAct['title'] = $proAct['details'] = $act;
							$proAct['project_id'] = $this->request->data['Milestone']['project_id'];
							$proAct['milestone_id'] = $this->Milestone->id;
							$proAct['estimated_cost'] = $est;
							$proAct['start_date'] = $this->request->data['Milestone']['start_date'];
							$proAct['end_date'] = $this->request->data['Milestone']['end_date'];
							$proAct['sequence'] = 0;
							$proAct['user_id'] = $pr['user_id'];
							$proAct['branchid'] = $this->Session->read('User.branchid');
							$proAct['deapartmentid'] = $this->Session->read('User.deapartmentid');
							$proAct['publish'] = $this->request->data['Project']['publish'];
							$proAct['soft_delete'] = 0;
							$this->ProjectResource->ProjectActivity->create();
							$this->ProjectResource->ProjectActivity->save($proAct,false);

						}
						
					}
				}

				foreach ($this->request->data['ProjectEstimate'] as $pe) {
					if($pe['cost'] > 0){
						if($pe['details'] == '')$pe['details'] = 'NA';
						$costEstimate['ProjectEstimate']['project_id'] = $this->request->data['Milestone']['project_id'];
						$costEstimate['ProjectEstimate']['milestone_id'] = $this->Milestone->id;
						$costEstimate['ProjectEstimate']['cost'] = $pe['cost'];
						$costEstimate['ProjectEstimate']['details'] = $pe['details'];
						$costEstimate['ProjectEstimate']['cost_category_id'] = $pe['cost_category_id'];
						$costEstimate['ProjectEstimate']['publish'] = $this->request->data['Project']['publish'];
						$this->ProjectEstimate->create();
						$this->ProjectEstimate->save($costEstimate,false);	
					}
					
				}
			}
		}

			$this->_updateprojectestimate($this->request->data['Milestone']['project_id']);
			$this->Session->setFlash(__('The milestone has been saved'));
			$this->redirect(array('controller'=>'projects', 'action' => 'updatealert', $this->request->data['Milestone']['project_id']));
			// unset($this->request->data['Milestone']['end_date']);
			// $dateRange = split('-', $this->request->data['Milestone']['start_date']);
			// $start_date = rtrim(ltrim($dateRange[0]));
			// $end_date = rtrim(ltrim($dateRange[1]));
			
			// $this->request->data['Milestone']['branch_id'] = $this->Session->read('User.branch_id');

			// $this->request->data['Milestone']['start_date'] = date('Y-m-d',strtotime($start_date));
			// $this->request->data['Milestone']['end_date'] = date('Y-m-d',strtotime($end_date));
		
		// if ($this->Milestone->save($this->request->data)) {
		// 	if ($this->_show_approvals()) $this->_save_approvals();
		// 	$this->Session->setFlash(__('The milestone has been saved'));
		// 		if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Milestone->id));
		// 		// else $this->redirect(array('action' => 'index'));
		// 		// else $this->redirect(array('controller'=>'projects', 'action' => 'view', $this->request->params['named']['project_id']));
		// 		else $this->redirect(array('controller'=>'projects', 'action' => 'view', $this->request->params['named']['project_id']));
		// } else {
		// 	$this->Session->setFlash(__('The milestone could not be saved. Please, try again.'));
		// }
	}
	$projects = $this->Milestone->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
	$branches = $this->Milestone->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
	$masterListOfFormats = $this->Milestone->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->Milestone->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$preparedBies = $this->Milestone->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	$approvedBies = $this->Milestone->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	$createdBies = $modifiedBies = $this->Milestone->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	$currentStatuses = $this->Milestone->customArray['currentStatuses'];
	$currencies = $this->Milestone->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
	$this->set(compact('projects', 'branches',  'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','currentStatuses','currencies'));

	$count = $this->Milestone->find('count');
	$published = $this->Milestone->find('count',array('conditions'=>array('Milestone.publish'=>1)));
	$unpublished = $this->Milestone->find('count',array('conditions'=>array('Milestone.publish'=>0)));

	$this->set(compact('count','published','unpublished'));

	$this->loadModel('CostCategory');
	$costCategories = $this->CostCategory->find('list',array('order'=>array('CostCategory.name'=>'ASC'), 'conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
	$this->set('costCategories',$costCategories);

	if(isset($this->request->params['named']['project_id'])){
		$project = $this->Milestone->Project->find('first',array('conditions'=>array('Project.id'=>$this->request->params['named']['project_id'])));
		$milestones = $this->Milestone->find('all',array('conditions'=>array('Milestone.project_id'=>$this->request->params['named']['project_id'])));
		$this->set('project',$project);
		$this->set('milestones',$milestones);
		if(isset($this->request->params['named']['project_id'])){
		$i = 0 ;
		$milestones = $this->Milestone->find('all',array(
			'recursive'=>0,
			'conditions' => array('Milestone.project_id'=>$this->request->params['named']['project_id'])
			));

		foreach ($milestones as $milestone) {
			$project_details[$i]['Milestone'] = $milestone['Milestone'];
			$activities = $this->Milestone->ProjectActivity->find('all',array(
				'recursive'=>0,
				'conditions'=>array('ProjectActivity.milestone_id'=>$milestone['Milestone']['id'])
				));	
				$y = 0;			
			foreach ($activities as $activity) {
				$z=0;
				$project_details[$i]['Milestone']['ProjectActivity'][$y] = $activity;
				$activity_requirements = $this->Milestone->ProjectActivity->ProjectActivityRequirement->find('all',array(
					'recursive'=>0,
					'conditions'=>array('ProjectActivityRequirement.project_activity_id'=>$activity['ProjectActivity']['id'])
					));
				$tasks = $this->Milestone->ProjectActivity->Task->find('list',array(
					'recursive'=>0,
					'conditions'=>array('Task.project_activity_id'=>$activity['ProjectActivity']['id'])
					));
				$project_details[$i]['Milestone']['ProjectActivity'][$y]['ProjectActivityRequirement'] = $activity_requirements;
				$project_details[$i]['Milestone']['ProjectActivity'][$y]['Tasks'] = $tasks;
				$z++;
				$y++;
			}
		$i++;	
		}
		$this->set('project_details',$project_details);
		// $this->set('project',$project_details);
	}
	}
}

	public function add_resource(){
		$x = $this->request->params['pass']['0'];
		$this->set('x',$x);
		// $key = $this->request->params['pass']['0'];
		// $this->set('key',$key);
	}



/**
 * add method
 *
 * @return void
 */
// public function add() {
	
// 	if($this->_show_approvals()){
// 		$this->loadModel('User');
// 		$this->User->recursive = 0;
// 		$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.is_approvar'=>1)));
// 		$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
// 	}

// 	if ($this->request->is('post')) {
// 		$this->request->data['Milestone']['system_table_id'] = $this->_get_system_table_id();
// 		$this->Milestone->create();

// 		unset($this->request->data['Milestone']['end_date']);
// 		$dateRange = split('-', $this->request->data['Milestone']['start_date']);
// 		$start_date = rtrim(ltrim($dateRange[0]));
// 		$end_date = rtrim(ltrim($dateRange[1]));
		
// 		$this->request->data['Milestone']['start_date'] = date('Y-m-d',strtotime($start_date));
// 		$this->request->data['Milestone']['end_date'] = date('Y-m-d',strtotime($end_date));

// 		if ($this->Milestone->save($this->request->data)) {

// 			if($this->_show_approvals()){
// 				$this->loadModel('Approval');
// 				$this->Approval->create();
// 				$this->request->data['Approval']['model_name']='Milestone';
// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
// 				$this->request->data['Approval']['record']=$this->Milestone->id;
// 				$this->Approval->save($this->request->data['Approval']);
// 			}
// 			$this->Session->setFlash(__('The milestone has been saved'));
// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Milestone->id));
// 			else $this->redirect(array('action' => 'index'));
// 		} else {
// 			$this->Session->setFlash(__('The milestone could not be saved. Please, try again.'));
// 		}
// 	}
// 	$projects = $this->Milestone->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
// 	$branches = $this->Milestone->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
// 	$userSessions = $this->Milestone->UserSession->find('list',array('conditions'=>array('UserSession.publish'=>1,'UserSession.soft_delete'=>0)));
// 	$masterListOfFormats = $this->Milestone->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
// 	$companies = $this->Milestone->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
// 	$preparedBies = $this->Milestone->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
// 	$approvedBies = $this->Milestone->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
// 	$createdBies = $this->Milestone->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
// 	$modifiedBies = $this->Milestone->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
// 	$this->set(compact('projects', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
// 	$count = $this->Milestone->find('count');
// 	$published = $this->Milestone->find('count',array('conditions'=>array('Milestone.publish'=>1)));
// 	$unpublished = $this->Milestone->find('count',array('conditions'=>array('Milestone.publish'=>0)));

// 	$this->set(compact('count','published','unpublished'));

// }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
public function edit($id = null) {
	if (!$this->Milestone->exists($id)) {
		throw new NotFoundException(__('Invalid milestone'));
	}

	if ($this->_show_approvals()) {
		$this->set(array('showApprovals' => $this->_show_approvals()));
	}

	if ($this->request->is('post') || $this->request->is('put')) {

		if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
			$this->request->data[$this->modelClass]['publish'] = 0;
		}

		$this->request->data['Milestone']['system_table_id'] = $this->_get_system_table_id();

		unset($this->request->data['Milestone']['end_date']);
		$dateRange = split('-', $this->request->data['Milestone']['start_date']);
		$start_date = rtrim(ltrim($dateRange[0]));
		$end_date = rtrim(ltrim($dateRange[1]));
		
		$this->request->data['Milestone']['start_date'] = date('Y-m-d',strtotime($start_date));
		$this->request->data['Milestone']['end_date'] = date('Y-m-d',strtotime($end_date));

		// Configure::write('debug',1);
		// debug($this->request->data);
		// exit;

		if ($this->Milestone->save($this->request->data)) {

			// $this->_updateprojectestimate($this->request->data['Milestone']['project_id']);
			// $this->redirect(array('controller'=>'projects', 'updatealert' => 'view', $this->request->data['Milestone']['project_id']));
			if ($this->_show_approvals()) $this->_save_approvals();
			$this->Session->setFlash(__('The milestone Updated.'));
			$this->redirect(array('controller'=>'projects', 'action' => 'view', $this->request->data['Milestone']['project_id']));			
		} else {
			$this->Session->setFlash(__('The milestone could not be saved. Please, try again.'));
		}
	} else {
		$options = array('conditions' => array('Milestone.' . $this->Milestone->primaryKey => $id));
		$this->request->data = $this->Milestone->find('first', $options);
	}
	$projects = $this->Milestone->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
	$branches = $this->Milestone->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
	$currencies = $this->Milestone->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
	$masterListOfFormats = $this->Milestone->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->Milestone->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$preparedBies = $approvedBies = $this->Milestone->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));	
	$createdBies = $modifiedBies = $this->Milestone->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	
	$currentStatuses = $this->Milestone->customArray['currentStatuses'];
	$this->set(compact('projects', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','currentStatuses','currencies'));


	$deliverableUnits = $this->Milestone->Project->DeliverableUnit->find('list',array('conditions'=>array('DeliverableUnit.publish'=>1,'DeliverableUnit.soft_delete'=>0)));
	$this->set('deliverableUnits',$deliverableUnits);


	$milestoneTypes = $this->Milestone->MilestoneType->find('list',array('conditions'=>array('MilestoneType.publish'=>1,'MilestoneType.soft_delete'=>0)));
	$this->set('milestoneTypes',$milestoneTypes);
	// $count = $this->Milestone->find('count');
	// $published = $this->Milestone->find('count',array('conditions'=>array('Milestone.publish'=>1)));
	// $unpublished = $this->Milestone->find('count',array('conditions'=>array('Milestone.publish'=>0)));
	// $this->set(compact('count','published','unpublished'));

	// if($this->request->params['named']['project_id']){
	// 	$project_details = $this->requestAction('projects/view/'.$this->request->data['Milestone']['project_id']);
	// 	$this->set('project_details',$project_details);		
	// }else{
	// 	$project_details = $this->requestAction('projects/view/'.$this->request->data['Milestone']['project_id']);
	// 	$this->set('project_details',$project_details);					
	// }
}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
public function approve($id = null, $approvalId = null) {
	if (!$this->Milestone->exists($id)) {
		throw new NotFoundException(__('Invalid milestone'));
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

		unset($this->request->data['Milestone']['end_date']);
		$dateRange = split('-', $this->request->data['Milestone']['start_date']);
		$start_date = rtrim(ltrim($dateRange[0]));
		$end_date = rtrim(ltrim($dateRange[1]));
		
		$this->request->data['Milestone']['start_date'] = date('Y-m-d',strtotime($start_date));
		$this->request->data['Milestone']['end_date'] = date('Y-m-d',strtotime($end_date));

		if ($this->Milestone->save($this->request->data)) {
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
				$this->request->data[$this->modelClass]['publish'] = 0;
			}
			if ($this->Milestone->save($this->request->data)) {
				$this->Session->setFlash(__('The milestone has been saved.'));

				if ($this->_show_approvals()) $this->_save_approvals();

			} else {
				$this->Session->setFlash(__('The milestone could not be saved. Please, try again.'));
			}

		} else {
			$this->Session->setFlash(__('The milestone could not be saved. Please, try again.'));
		}
	} else {
		$options = array('conditions' => array('Milestone.' . $this->Milestone->primaryKey => $id));
		$this->request->data = $this->Milestone->find('first', $options);
	}
	$projects = $this->Milestone->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
	$branches = $this->Milestone->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
	$masterListOfFormats = $this->Milestone->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->Milestone->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$preparedBies = $this->Milestone->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	$approvedBies = $this->Milestone->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	$createdBies = $modifiedBies = $this->Milestone->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	
	$currentStatuses = $this->Milestone->customArray['currentStatuses'];
	$this->set(compact('projects', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','currentStatuses'));
	$count = $this->Milestone->find('count');
	$published = $this->Milestone->find('count',array('conditions'=>array('Milestone.publish'=>1)));
	$unpublished = $this->Milestone->find('count',array('conditions'=>array('Milestone.publish'=>0)));

	$this->set(compact('count','published','unpublished'));

		// if(isset($this->request->data['Milestone']['project_id'])){
		// 	$project = $this->Milestone->Project->find('first',array('conditions'=>array('Project.id'=>$this->request->data['Milestone']['project_id'])));
		// 	$milestones = $this->Milestone->find('all',array('conditions'=>array('Milestone.id <>'=>$this->request->data['Milestone']['id'], 'Milestone.project_id'=>$this->request->data['Milestone']['project_id'])));
		// 	$this->set('project',$project);
		// 	$this->set('milestones',$milestones);
		// }
	if($this->request->params['named']['project_id']){
		$project_details = $this->requestAction(array('controller'=>'projects','action'=>'view',$this->request->params['named']['project_id']));
		$this->set('project_details',$project_details[1]);
		$project = $project_details[0]; 
		$this->set('project',$project);            
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
	$this->Milestone->id = $id;
	if (!$this->Milestone->exists()) {
		throw new NotFoundException(__('Invalid milestone'));
	}
	$this->request->onlyAllow('post', 'delete');
	if ($this->Milestone->delete()) {
		$this->Session->setFlash(__('Milestone deleted'));
		$this->redirect(array('action' => 'index'));
	}
	$this->Session->setFlash(__('Milestone was not deleted'));
	$this->redirect(array('action' => 'index'));
}

       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
       public function delete($id = null, $parent_id = null) {

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

       	$result = explode('+',$this->request->data['milestones']['rec_selected']);
       	$this->Milestone->recursive = 1;
       	$milestones = $this->Milestone->find('all',array('Milestone.publish'=>1,'Milestone.soft_delete'=>1,'conditions'=>array('or'=>array('Milestone.id'=>$result))));
       	$this->set('milestones', $milestones);

       	$projects = $this->Milestone->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
       	$branches = $this->Milestone->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
       	$userSessions = $this->Milestone->UserSession->find('list',array('conditions'=>array('UserSession.publish'=>1,'UserSession.soft_delete'=>0)));
       	$masterListOfFormats = $this->Milestone->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
       	$companies = $this->Milestone->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
       	$preparedBies = $this->Milestone->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
       	$approvedBies = $this->Milestone->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
       	$createdBies = $this->Milestone->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
       	$modifiedBies = $this->Milestone->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
       	$this->set(compact('projects', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
       }

       public function milestonewise($project_id = null){
		Configure::write('debug',1);
		$milestones = $this->Project->Milestone->find('all',array(
			'conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0,'Milestone.project_id'=>$project_id),
			'contain'=>array(
				'ProjectActivity'=>array(
					'fields'=>array('ProjectActivity.project_id','ProjectActivity.milestone_id', 'ProjectActivity.start_date','ProjectActivity.end_date','ProjectActivity.estimated_cost')
				),					
				'ProjectTimesheet'=>array(						
					'fields'=>array('ProjectTimesheet.project_id','ProjectTimesheet.project_activity_id', 'ProjectTimesheet.start_time','ProjectTimesheet.end_time','ProjectTimesheet.total_cost')
				),
				'PurchaseOrder'=>array(
					'fields'=>array('PurchaseOrder.id', 'PurchaseOrder.project_id','PurchaseOrder.project_activity_id', 'PurchaseOrder.milestone_id','PurchaseOrder.order_date',
						'PurchaseOrder.type','PurchaseOrder.in','PurchaseOrder.out'
					)
				),
				'ProjectResource'=>array(
					'fields'=>array('ProjectResource.id', 'ProjectResource.project_id','ProjectResource.resource_sub_total'
					)
				),
				'ProjectEstimate'=>array(
					'fields'=>array('ProjectEstimate.id', 'ProjectEstimate.project_id','ProjectEstimate.cost'
					)
				)
			)
		));
		debug($milestones);
		exit;
	}


	public function updatemilestone(){
		if ($this->request->is('post')) {
			// Configure::write('debug',1);
			// debug($this->request->data);
			unset($this->request->data['Milestone']['popcount']);
			$this->loadModel('Project');
			foreach ($this->request->data['Milestone'] as $m) {
				debug($m);
				$project_id = $m['Milestone']['project_id'];
				if($m['Milestone']['start_date'] != '' && $m['Milestone']['end_date'] != ''){
					debug($m);

					$milestone['Milestone'] = $m['Milestone'];
					$milestone['Milestone']['branch_id'] = $this->Session->read('User.branch_id');
					$milestone['Milestone']['project_id'] = $m['Milestone']['project_id'];
					$milestone['Milestone']['publish'] = 1;
					debug($milestone);
					$this->Milestone->create();
					
					if($this->Milestone->save($milestone,false)){
						// exit;
						foreach ($m['ProjectOverallPlan'] as $pop) {
							$pop['project_id'] = $m['Milestone']['project_id'];
							$pop['milestone_id'] = $this->Milestone->id;
							$pop['cal_type'] = $pop['cal_type'];
							$pop['plan_type'] = $pop['plan_type'];
							$pop['type'] = $pop['type'];
							$pop['lot_process'] = $pop['lot_process'];
							$pop['estimated_units'] = $pop['estimated_units'];
							$pop['overall_metrics'] = $pop['overall_metrics'];
							$pop['start_date'] = date('Y-m-d',strtotime($pop['start_date']));
							$pop['end_date'] = date('Y-m-d',strtotime($pop['end_date']));
							$pop['estimated_resource'] = $pop['estimated_resource'];
							$pop['estimated_manhours'] = $pop['estimated_manhours'];
							$pop['branchid'] = $this->Session->read('User.branchid');
							$pop['deapartmentid'] = $this->Session->read('User.deapartmentid');
							$pop['publish'] = $m['Milestone']['publish'];
							$pop['soft_delete'] = 0;
							$pop['prepared_by'] = $m['Milestone']['prepared_by'];
							$this->Project->ProjectOverallPlan->create();
							$this->Project->ProjectOverallPlan->save($pop,false);

						}

						foreach ($m['ProjectResource'] as $pr) {
							$projectResource['project_id'] = $m['Milestone']['project_id'];
							$projectResource['milestone_id'] = $this->Milestone->id;
							$projectResource['user_id'] = $pr['user_id'];
							$projectResource['mandays'] = $pr['mandays'];
							$projectResource['resource_cost'] = $pr['resource_cost'];
							$projectResource['resource_sub_total'] = $pr['resource_sub_total'];
							$projectResource['prepared_by'] = $m['Milestone']['prepared_by'];
							$projectResource['publish'] = $m['Milestone']['publish'];
							$this->Project->ProjectResource->create();
							$this->Project->ProjectResource->save($projectResource,false);


							$activities = explode(PHP_EOL, $pr['activities']);
							if($activities){
								foreach ($activities as $act) {
									$proAct['project_resource_id'] = $this->Project->ProjectResource->id;
									$proAct['title'] = $proAct['details'] = $act;
									$proAct['project_id'] = $m['Milestone']['project_id'];
									$proAct['milestone_id'] = $this->Milestone->id;
									$proAct['estimated_cost'] = 0;
									$proAct['start_date'] = $m['Milestone']['start_date'];
									$proAct['end_date'] = $m['Milestone']['end_date'];
									$proAct['sequence'] = 0;
									$proAct['user_id'] = $pr['user_id'];
									$proAct['branchid'] = $this->Session->read('User.branchid');
									$proAct['deapartmentid'] = $this->Session->read('User.deapartmentid');
									$proAct['publish'] = $m['Milestone']['publish'];
									$proAct['soft_delete'] = 0;
									$this->Project->ProjectResource->ProjectActivity->create();
									$this->Project->ProjectResource->ProjectActivity->save($proAct,false);

								}
								
							}

						}

						foreach ($m['ProjectEstimate'] as $pe) {
							if($pe['cost'] > 0){
								$costEstimate['project_id'] = $m['Milestone']['project_id'];
								$costEstimate['milestone_id'] = $this->Milestone->id;
								$costEstimate['cost'] = $pe['cost'];
								
								if($pe['details'] == '')$costEstimate['details'] = 'NIL';
								else $costEstimate['details'] = $pe['details'];
								
								$costEstimate['cost_category_id'] = $pe['cost_category_id'];
								$costEstimate['publish'] = $m['Milestone']['publish'];
								$this->Project->ProjectEstimate->create();
								$this->Project->ProjectEstimate->save($costEstimate,false);	
							}
							
						}
					}
				}
			echo " >>>>> 1";
			}
			echo " >>>> 2";

			$this->Session->setFlash(__('The milestone has been saved'));
			$this->redirect(array('controller'=>'projects', 'action' => 'view', $project_id));
			// exit;
// 	}
		}
	}

  }

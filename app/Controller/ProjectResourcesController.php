<?php
App::uses('AppController', 'Controller');
/**
 * ProjectResources Controller
 *
 * @property ProjectResource $ProjectResource
 * @property PaginatorComponent $Paginator
 */
class ProjectResourcesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

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
		
		// $conditions = $this->_check_request();
		// $this->paginate = array('order'=>array('ProjectResource.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		// $this->ProjectResource->recursive = 0;
		// $this->set('projectResources', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ProjectResource.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectResource->recursive = 0;
		$this->set('projectResources', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectResource']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectResource']['search_field'] as $search):
				$search_array[] = array('ProjectResource.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectResource.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectResource->recursive = 0;
		$this->paginate = array('order'=>array('ProjectResource.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectResource.soft_delete'=>0 , $cons));
		$this->set('projectResources', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectResource.'.$search => $search_key);
					else $search_array[] = array('ProjectResource.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectResource.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectResource.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectResource.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectResource.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectResource.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectResource->recursive = 0;
		$this->paginate = array('order'=>array('ProjectResource.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectResource.soft_delete'=>0 );
		$this->set('projectResources', $this->paginate());
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
		if (!$this->ProjectResource->exists($id)) {
			throw new NotFoundException(__('Invalid project resource'));
		}
		$options = array('conditions' => array('ProjectResource.' . $this->ProjectResource->primaryKey => $id));
		$this->set('projectResource', $this->ProjectResource->find('first', $options));
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
			$this->request->data['ProjectResource']['system_table_id'] = $this->_get_system_table_id();
			Configure::write('debug',1);
			debug($this->request->data);
			exit;
			
			$data = $this->request->data['ProjectResource'];
			
			unset($this->request->data['ProjectResource']['project_id']);
			unset($this->request->data['ProjectResource']['milestone_id']);
			unset($this->request->data['ProjectResource']['branchid']);
			unset($this->request->data['ProjectResource']['departmentid']);
			unset($this->request->data['ProjectResource']['prepared_by']);
			unset($this->request->data['ProjectResource']['approved_by']);
			unset($this->request->data['ProjectResource']['publish']);
			unset($this->request->data['ProjectResource']['system_table_id']);
			unset($this->request->data['ProjectResource']['agendaNumber']);
			unset($this->request->data['ProjectResource']['master_list_of_format_id']);

			debug($this->request->data['ProjectResource']);

			debug($data);
			// $this->ProjectResource->create();
			// if ($this->ProjectResource->save($this->request->data)) {
				foreach ($this->request->data['ProjectResource'] as $projectResource) {
				
					if(($projectResource['user_id'] && $projectResource['user_id'] != -1) && $projectResource['mandays'] > 0){
						$projectResource['project_id'] = $data['project_id'];
						$projectResource['milestone_id'] = $data['milestone_id'];
						$projectResource['branchid'] = $data['branchid'];
						$projectResource['departmentid'] = $data['departmentid'];
						$projectResource['prepared_by'] = $data['prepared_by'];
						$projectResource['approved_by'] = $data['approved_by'];
						$projectResource['publish'] = $data['publish'];
						$projectResource['system_table_id'] = $data['system_table_id'];
						$projectResource['soft_delete'] = 0;
						debug($projectResource);
						$this->ProjectResource->create();
						$this->ProjectResource->save($projectResource,false);
					}
				}
				// exit;
				
				$this->_updateprojectestimate($data['project_id']);
				if ($this->_show_approvals()) $this->_save_approvals();
				// $this->Session->setFlash(__('The project resource has been saved'));
				// if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectResource->id));
				// else $this->redirect(array('action' => 'index'));
				$this->redirect(array('controller'=>'projects', 'action' => 'updatealert',$data['project_id']));
			// } else {
			// 	$this->Session->setFlash(__('The project resource could not be saved. Please, try again.'));
			// }
		}
		$users = $this->ProjectResource->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$projects = $this->ProjectResource->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectResource->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectActivities = $this->ProjectResource->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$systemTables = $this->ProjectResource->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectResource->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectResource->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectResource->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectResource->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectResource->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		
		$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','milestones'));
		$count = $this->ProjectResource->find('count');
		$published = $this->ProjectResource->find('count',array('conditions'=>array('ProjectResource.publish'=>1)));
		$unpublished = $this->ProjectResource->find('count',array('conditions'=>array('ProjectResource.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

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
 //                        $this->request->data['ProjectResource']['system_table_id'] = $this->_get_system_table_id();
	// 		$this->ProjectResource->create();
	// 		if ($this->ProjectResource->save($this->request->data)) {

	// 			if($this->_show_approvals()){
	// 				$this->loadModel('Approval');
	// 				$this->Approval->create();
	// 				$this->request->data['Approval']['model_name']='ProjectResource';
	// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
	// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
	// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['record']=$this->ProjectResource->id;
	// 				$this->Approval->save($this->request->data['Approval']);
	// 			}
	// 			$this->Session->setFlash(__('The project resource has been saved'));
	// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectResource->id));
	// 			else $this->redirect(array('action' => 'index'));
	// 		} else {
	// 			$this->Session->setFlash(__('The project resource could not be saved. Please, try again.'));
	// 		}
	// 	}
	// 	$users = $this->ProjectResource->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
	// 	$projects = $this->ProjectResource->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
	// 	$projectActivities = $this->ProjectResource->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
	// 	$systemTables = $this->ProjectResource->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	// 	$masterListOfFormats = $this->ProjectResource->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	// 	$preparedBies = $this->ProjectResource->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	// 	$approvedBies = $this->ProjectResource->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	// 	$createdBies = $this->ProjectResource->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	// 	$modifiedBies = $this->ProjectResource->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	// 			$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	// $count = $this->ProjectResource->find('count');
	// $published = $this->ProjectResource->find('count',array('conditions'=>array('ProjectResource.publish'=>1)));
	// $unpublished = $this->ProjectResource->find('count',array('conditions'=>array('ProjectResource.publish'=>0)));
		
	// $this->set(compact('count','published','unpublished'));

	// }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ProjectResource->exists($id)) {
			throw new NotFoundException(__('Invalid project resource'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProjectResource']['system_table_id'] = $this->_get_system_table_id();
			if ($this->ProjectResource->save($this->request->data)) {
				$this->_updateprojectestimate($this->request->data['ProjectResource']['project_id']);
				if ($this->_show_approvals()) $this->_save_approvals();
				
				// if ($this->_show_evidence() == true)
				//  $this->redirect(array('action' => 'view', $id));
				// else
		 	// 		$this->redirect(array('action' => 'index'));
				
				$this->redirect(array('controller'=>'projects', 'action' => 'view', $this->request->data['ProjectResource']['project_id']));
			} else {
				$this->Session->setFlash(__('The project resource could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectResource.' . $this->ProjectResource->primaryKey => $id));
			$this->request->data = $this->ProjectResource->find('first', $options);
		}
		$users = $this->ProjectResource->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$projects = $this->ProjectResource->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectResource->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		if($this->request->params['named']['milestone_id']){
			$projectActivities = $this->ProjectResource->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0,'ProjectActivity.milestone_id'=>$this->request->params['named']['milestone_id'])));	
		}else{
			$projectActivities = $this->ProjectResource->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));	
		}
		
		$systemTables = $this->ProjectResource->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectResource->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectResource->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectResource->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectResource->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectResource->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','milestones'));
		$count = $this->ProjectResource->find('count');
		$published = $this->ProjectResource->find('count',array('conditions'=>array('ProjectResource.publish'=>1)));
		$unpublished = $this->ProjectResource->find('count',array('conditions'=>array('ProjectResource.publish'=>0)));
		
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
		if (!$this->ProjectResource->exists($id)) {
			throw new NotFoundException(__('Invalid project resource'));
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
			if ($this->ProjectResource->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectResource->save($this->request->data)) {
                $this->Session->setFlash(__('The project resource has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project resource could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project resource could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectResource.' . $this->ProjectResource->primaryKey => $id));
			$this->request->data = $this->ProjectResource->find('first', $options);
		}
		$users = $this->ProjectResource->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$projects = $this->ProjectResource->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$projectActivities = $this->ProjectResource->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$systemTables = $this->ProjectResource->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectResource->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectResource->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectResource->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectResource->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectResource->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectResource->find('count');
		$published = $this->ProjectResource->find('count',array('conditions'=>array('ProjectResource.publish'=>1)));
		$unpublished = $this->ProjectResource->find('count',array('conditions'=>array('ProjectResource.publish'=>0)));
		
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
		$this->ProjectResource->id = $id;
		if (!$this->ProjectResource->exists()) {
			throw new NotFoundException(__('Invalid project resource'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectResource->delete()) {
			$this->Session->setFlash(__('Project resource deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project resource was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null, $parent_id = NULL) {
	
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
		
		$result = explode('+',$this->request->data['projectResources']['rec_selected']);
		$this->ProjectResource->recursive = 1;
		$projectResources = $this->ProjectResource->find('all',array('ProjectResource.publish'=>1,'ProjectResource.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectResource.id'=>$result))));
		$this->set('projectResources', $projectResources);
		
				$users = $this->ProjectResource->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$projects = $this->ProjectResource->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$projectActivities = $this->ProjectResource->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$systemTables = $this->ProjectResource->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectResource->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectResource->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectResource->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectResource->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectResource->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function resource_check($project_id = null, $milestone_id = null){


		$this->ProjectResource->ProjectProcessPlan->virtualFields = array(
			'recourecs' => 'select count(*) from project_resources where process_id LIKE ProjectProcessPlan.id',
			'check_deleted'=>'select count(*) from  project_overall_plans where project_overall_plans.id LIKE ProjectProcessPlan.project_overall_plan_id'
		);
		$processes = $this->ProjectResource->ProjectProcessPlan->find('all',array(
			'recursive'=>-1,
			'fields'=>array(
				'ProjectProcessPlan.id',
				'ProjectProcessPlan.process',
				'ProjectProcessPlan.recourecs',
			),
			'conditions'=>array(
				'ProjectProcessPlan.check_deleted >'=> 0,
				'ProjectProcessPlan.project_id'=>$project_id,
				'ProjectProcessPlan.milestone_id'=>$milestone_id,
			)			
		));

		// Configure::Write('debug',1);
		// debug($processes);

		$noresources = false;
		
		if($processes){
			foreach ($processes as $processe) {
				if($processe['ProjectProcessPlan']['recourecs'] == 0){
					$noresources = true;					
				}
			}	
		}else{
			$noresources = true;			
		}

		return $noresources;
		// exit;
	}
}

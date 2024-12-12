<?php
App::uses('AppController', 'Controller');
/**
 * ProjectActivities Controller
 *
 * @property ProjectActivity $ProjectActivity
 */
class ProjectActivitiesController extends AppController {

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
			$project_conditions = array('ProjectActivity.project_id'=>$this->request->params['named']['project_id']);
			$project_order = array('ProjectActivity.sequence'=>'DESC');
		}

		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('ProjectActivity.sr_no'=>'DESC',$project_order),'conditions'=>array($conditions,$project_conditions));
	
		$this->ProjectActivity->recursive = 0;
		
		$i = 0;
		$project_activities = $this->paginate();
		foreach ($project_activities as $activity) {
			$activity_requirments = $this->ProjectActivity->ProjectActivityRequirement->find('list',array('conditions'=>array('ProjectActivityRequirement.project_activity_id'=>$activity['ProjectActivity']['id'])));
			$activity['ProjectActivityRequirement'] = $activity_requirments;
			$new_project_activities[$i] = $activity;

			$activity_tasks = $this->ProjectActivity->Task->find('list',array('conditions'=>array('Task.project_activity_id'=>$activity['ProjectActivity']['id'])));
			$activity['Task'] = $activity_tasks;
			$new_project_activities[$i] = $activity;
			$i++;
		}	
		$this->set('projectActivities', $new_project_activities);
		
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
		$this->paginate = array('order'=>array('ProjectActivity.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectActivity->recursive = 0;
		$this->set('projectActivities', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectActivity']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectActivity']['search_field'] as $search):
				$search_array[] = array('ProjectActivity.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectActivity.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectActivity->recursive = 0;
		$this->paginate = array('order'=>array('ProjectActivity.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectActivity.soft_delete'=>0 , $cons));
		$this->set('projectActivities', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectActivity.'.$search => $search_key);
					else $search_array[] = array('ProjectActivity.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectActivity.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectActivity.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectActivity.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectActivity.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectActivity.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectActivity->recursive = 0;
		$this->paginate = array('order'=>array('ProjectActivity.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectActivity.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('projectActivities', $this->paginate());
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
		if (!$this->ProjectActivity->exists($id)) {
			throw new NotFoundException(__('Invalid project activity'));
		}
		$options = array('conditions' => array('ProjectActivity.' . $this->ProjectActivity->primaryKey => $id));
		$this->set('projectActivity', $this->ProjectActivity->find('first', $options));
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
			$this->request->data['ProjectActivity']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectActivity->create();

			unset($this->request->data['ProjectActivity']['end_date']);
			$dateRange = split('-', $this->request->data['ProjectActivity']['start_date']);
			$start_date = rtrim(ltrim($dateRange[0]));
			$end_date = rtrim(ltrim($dateRange[1]));
			
			$this->request->data['ProjectActivity']['start_date'] = date('Y-m-d',strtotime($start_date));
			$this->request->data['ProjectActivity']['end_date'] = date('Y-m-d',strtotime($end_date));
			$this->request->data['ProjectActivity']['users'] = json_encode($this->request->data['ProjectActivity']['users']);

			if ($this->ProjectActivity->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The project activity has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectActivity->id));
				// else $this->redirect(array('action' => 'index'));
				else $this->redirect(array('controller'=>'projects', 'action' => 'view', $this->request->data['ProjectActivity']['project_id']));
			} else {
				$this->Session->setFlash(__('The project activity could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ProjectActivity->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		if($this->request->params['named']['project_id'])$milestones = $this->ProjectActivity->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$this->request->params['named']['project_id'])));
		else $milestones = $this->ProjectActivity->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$branches = $this->ProjectActivity->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectActivity->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProjectActivity->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProjectActivity->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectActivity->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectActivity->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectActivity->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'branches',  'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectActivity->find('count');
		$published = $this->ProjectActivity->find('count',array('conditions'=>array('ProjectActivity.publish'=>1)));
		$unpublished = $this->ProjectActivity->find('count',array('conditions'=>array('ProjectActivity.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		if($this->request->params['named']['project_id']){
            $project_details = $this->requestAction(array('controller'=>'projects','action'=>'view',$this->request->params['named']['project_id']));
            $this->set('project_details',$project_details[1]);
            $project = $project_details[0]; 
            $this->set('project',$project);            
        }
			
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
 //                        $this->request->data['ProjectActivity']['system_table_id'] = $this->_get_system_table_id();
	// 		$this->ProjectActivity->create();
	// 		if ($this->ProjectActivity->save($this->request->data)) {

	// 			if($this->_show_approvals()){
	// 				$this->loadModel('Approval');
	// 				$this->Approval->create();
	// 				$this->request->data['Approval']['model_name']='ProjectActivity';
	// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
	// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
	// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['record']=$this->ProjectActivity->id;
	// 				$this->Approval->save($this->request->data['Approval']);
	// 			}
	// 			$this->Session->setFlash(__('The project activity has been saved'));
	// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectActivity->id));
	// 			else $this->redirect(array('action' => 'index'));
	// 		} else {
	// 			$this->Session->setFlash(__('The project activity could not be saved. Please, try again.'));
	// 		}
	// 	}
	// 	$projects = $this->ProjectActivity->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
	// 	if($this->request->params['named']['project_id'])$milestones = $this->ProjectActivity->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$this->request->params['named']['project_id'])));
	// 	else $milestones = $this->ProjectActivity->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
	// 	$branches = $this->ProjectActivity->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
	// 	$userSessions = $this->ProjectActivity->UserSession->find('list',array('conditions'=>array('UserSession.publish'=>1,'UserSession.soft_delete'=>0)));
	// 	$masterListOfFormats = $this->ProjectActivity->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	// 	$companies = $this->ProjectActivity->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	// 	$preparedBies = $this->ProjectActivity->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	// 	$approvedBies = $this->ProjectActivity->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	// 	$createdBies = $this->ProjectActivity->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	// 	$modifiedBies = $this->ProjectActivity->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	// 			$this->set(compact('projects', 'milestones', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	// $count = $this->ProjectActivity->find('count');
	// $published = $this->ProjectActivity->find('count',array('conditions'=>array('ProjectActivity.publish'=>1)));
	// $unpublished = $this->ProjectActivity->find('count',array('conditions'=>array('ProjectActivity.publish'=>0)));
		
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
		if (!$this->ProjectActivity->exists($id)) {
			throw new NotFoundException(__('Invalid project activity'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        		$this->request->data[$this->modelClass]['publish'] = 0;
      		}
						
			$this->request->data['ProjectActivity']['system_table_id'] = $this->_get_system_table_id();

			unset($this->request->data['ProjectActivity']['end_date']);
			$dateRange = split('-', $this->request->data['ProjectActivity']['start_date']);
			$start_date = rtrim(ltrim($dateRange[0]));
			$end_date = rtrim(ltrim($dateRange[1]));
			
			
			$this->request->data['ProjectActivity']['users'] = json_encode($this->request->data['ProjectActivity']['users']);
			$this->request->data['ProjectActivity']['start_date'] = date('Y-m-d',strtotime($start_date));
			$this->request->data['ProjectActivity']['end_date'] = date('Y-m-d',strtotime($end_date));

			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;
			
			if ($this->ProjectActivity->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				$this->_updateprojectestimate($this->request->data['ProjectActivity']['project_id']);
				$this->Session->setFlash(__('The activity has been saved'));
				$this->redirect(array('controller'=>'projects', 'action' => 'updatealert', $this->request->data['ProjectActivity']['project_id']));
				// if ($this->_show_evidence() == true)
				//  $this->redirect(array('action' => 'view', $id));
				// else
		 	// 		// $this->redirect(array('action' => 'index'));
		 	// 		$this->redirect(array('controller'=>'projects', 'action' => 'view', $this->request->data['ProjectActivity']['project_id']));
			} else {
				$this->Session->setFlash(__('The project activity could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectActivity.' . $this->ProjectActivity->primaryKey => $id));
			$this->request->data = $this->ProjectActivity->find('first', $options);
		}
		$projects = $this->ProjectActivity->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		if($this->request->data['ProjectActivity']['project_id'])$milestones = $this->ProjectActivity->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$this->request->data['ProjectActivity']['project_id'])));
		else $milestones = $this->ProjectActivity->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$branches = $this->ProjectActivity->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectActivity->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProjectActivity->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProjectActivity->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectActivity->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectActivity->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectActivity->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'branches', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectActivity->find('count');
		$published = $this->ProjectActivity->find('count',array('conditions'=>array('ProjectActivity.publish'=>1)));
		$unpublished = $this->ProjectActivity->find('count',array('conditions'=>array('ProjectActivity.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		if($this->request->data['ProjectActivity']['project_id']){
			$project = $this->ProjectActivity->Project->find('first',array('conditions'=>array('Project.id'=>$this->request->data['ProjectActivity']['project_id'])));
			$project_milestones = $this->ProjectActivity->Milestone->find('all',array('conditions'=>array('Milestone.project_id'=>$this->request->data['ProjectActivity']['project_id'])));
			$project_activities = $this->ProjectActivity->find('all',array('conditions'=>array('ProjectActivity.id <> '=> $this->request->data['ProjectActivity']['id'],'ProjectActivity.project_id'=>$this->request->data['ProjectActivity']['project_id'])));
			$this->set('project',$project);
			$this->set('project_milestones',$project_milestones);
			$this->set('project_activities',$project_activities);
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
		if (!$this->ProjectActivity->exists($id)) {
			throw new NotFoundException(__('Invalid project activity'));
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

			unset($this->request->data['ProjectActivity']['end_date']);
			$dateRange = split('-', $this->request->data['ProjectActivity']['start_date']);
			$start_date = rtrim(ltrim($dateRange[0]));
			$end_date = rtrim(ltrim($dateRange[1]));
			
			$this->request->data['ProjectActivity']['start_date'] = date('Y-m-d',strtotime($start_date));
			$this->request->data['ProjectActivity']['end_date'] = date('Y-m-d',strtotime($end_date));

			$this->request->data['ProjectActivity']['users'] = json_encode($this->request->data['ProjectActivity']['users']);
			
			if ($this->ProjectActivity->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectActivity->save($this->request->data)) {
                $this->Session->setFlash(__('The project activity has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project activity could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project activity could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectActivity.' . $this->ProjectActivity->primaryKey => $id));
			$this->request->data = $this->ProjectActivity->find('first', $options);
		}
		$projects = $this->ProjectActivity->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectActivity->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$branches = $this->ProjectActivity->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$userSessions = $this->ProjectActivity->UserSession->find('list',array('conditions'=>array('UserSession.publish'=>1,'UserSession.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectActivity->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProjectActivity->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProjectActivity->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectActivity->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectActivity->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectActivity->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectActivity->find('count');
		$published = $this->ProjectActivity->find('count',array('conditions'=>array('ProjectActivity.publish'=>1)));
		$unpublished = $this->ProjectActivity->find('count',array('conditions'=>array('ProjectActivity.publish'=>0)));
		
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
		$this->ProjectActivity->id = $id;
		if (!$this->ProjectActivity->exists()) {
			throw new NotFoundException(__('Invalid project activity'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectActivity->delete()) {
			$this->Session->setFlash(__('Project activity deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project activity was not deleted'));
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
		
		$result = explode('+',$this->request->data['projectActivities']['rec_selected']);
		$this->ProjectActivity->recursive = 1;
		$projectActivities = $this->ProjectActivity->find('all',array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectActivity.id'=>$result))));
		$this->set('projectActivities', $projectActivities);
		
		$projects = $this->ProjectActivity->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectActivity->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$branches = $this->ProjectActivity->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$userSessions = $this->ProjectActivity->UserSession->find('list',array('conditions'=>array('UserSession.publish'=>1,'UserSession.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectActivity->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProjectActivity->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProjectActivity->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectActivity->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectActivity->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectActivity->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'milestones', 'branches', 'userSessions', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function get_cost(){
		$this->autoRender = false;
		$balance = 0;
		$sequence = 0;
		$cost = $this->ProjectActivity->Milestone->find('first',array('fields'=>array('Milestone.id','Milestone.estimated_cost','Milestone.start_date','Milestone.end_date'),'conditions'=>array('Milestone.id'=>$this->request->params['pass'][0]),'recursive'=>-1));
		
		$act_cost = $this->ProjectActivity->find('all',array('fields'=>array('ProjectActivity.id','ProjectActivity.estimated_cost','ProjectActivity.milestone_id','ProjectActivity.sequence'),'conditions'=>array('ProjectActivity.milestone_id'=>$cost['Milestone']['id']),'recursive'=>-1));
		$balance = $cost['Milestone']['estimated_cost'];
		foreach ($act_cost as $activity_cost) {	
		if($activity_cost){
				$balance = $balance - $activity_cost['ProjectActivity']['estimated_cost'];
			}else{
				$balance = $balance - $activity_cost['ProjectActivity']['estimated_cost'];
			}
		}
		$data = array('sequence'=>$sequence+1, 'cost'=>$balance,'startDate'=>date('m-d-Y',strtotime($cost['Milestone']['start_date'])),'endDate'=>date('m-d-Y',strtotime($cost['Milestone']['end_date'])));
		return json_encode($data);
	}
}

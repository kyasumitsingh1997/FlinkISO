<?php
App::uses('AppController', 'Controller');
/**
 * ProjectEmployees Controller
 *
 * @property ProjectEmployee $ProjectEmployee
 * @property PaginatorComponent $Paginator
 */
class ProjectEmployeesController extends AppController {

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
		// $this->paginate = array('order'=>array('ProjectEmployee.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		// $this->ProjectEmployee->recursive = 0;
		// $this->set('projectEmployees', $this->paginate());
		
		// $this->_get_count();
	}


 
/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('ProjectEmployee.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectEmployee->recursive = 0;
		$this->set('projectEmployees', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectEmployee']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectEmployee']['search_field'] as $search):
				$search_array[] = array('ProjectEmployee.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectEmployee.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectEmployee->recursive = 0;
		$this->paginate = array('order'=>array('ProjectEmployee.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectEmployee.soft_delete'=>0 , $cons));
		$this->set('projectEmployees', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectEmployee.'.$search => $search_key);
					else $search_array[] = array('ProjectEmployee.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectEmployee.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectEmployee.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectEmployee.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectEmployee.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectEmployee.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectEmployee->recursive = 0;
		$this->paginate = array('order'=>array('ProjectEmployee.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectEmployee.soft_delete'=>0 );
		$this->set('projectEmployees', $this->paginate());
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
		if (!$this->ProjectEmployee->exists($id)) {
			throw new NotFoundException(__('Invalid project employee'));
		}
		$options = array('conditions' => array('ProjectEmployee.' . $this->ProjectEmployee->primaryKey => $id));
		$this->set('projectEmployee', $this->ProjectEmployee->find('first', $options));
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
			$this->request->data['ProjectEmployee']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectEmployee->create();
			if ($this->ProjectEmployee->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The project employee has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectEmployee->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project employee could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ProjectEmployee->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectEmployee->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$employees = $this->ProjectEmployee->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectEmployee->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectEmployee->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectEmployee->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectEmployee->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectEmployee->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectEmployee->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('projects', 'milestones', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ProjectEmployee->find('count');
	$published = $this->ProjectEmployee->find('count',array('conditions'=>array('ProjectEmployee.publish'=>1)));
	$unpublished = $this->ProjectEmployee->find('count',array('conditions'=>array('ProjectEmployee.publish'=>0)));
		
	$this->set(compact('count','published','unpublished'));

	}





/**
 * add method
 *
 * @return void
 */
	public function add_members() {
	if ($this->request->is('post')) {
		foreach ($this->request->data['ProjectEmployee']['emp_id'] as $emp_id) {
			if($emp_id != 0){
				$projectEmployee = $this->ProjectEmployee->find('first',array(
					'conditions'=>array(
						'ProjectEmployee.project_id'=>$this->request->data['ProjectEmployee']['current_project_id'],
						'ProjectEmployee.employee_id'=>$emp_id,
					),
					'recursive'=>-1,
					'fields'=>array('ProjectEmployee.id', 'ProjectEmployee.project_id','ProjectEmployee.employee_id')
				));

				$project = $this->ProjectEmployee->Project->find('first',array(
					'recursive'=> -1,
					'fields'=>array('Project.id','Project.start_date','Project.end_date'),
					'conditions'=>array('Project.id'=>$this->request->data['ProjectEmployee']['current_project_id'])
				));

				// debug($projectEmployee['ProjectEmployee']['id']);
				if(!$projectEmployee){
					// if($project['Project']['id'] != $this->request->params['named']['project_id']){
						$data['ProjectEmployee']['project_id'] = $this->request->data['ProjectEmployee']['current_project_id'];
						$data['ProjectEmployee']['employee_id'] = $emp_id;
						$data['ProjectEmployee']['milestone_id'] = $this->request->params['named']['milestone_id'];
						$data['ProjectEmployee']['start_date'] = $project['Project']['start_date'];
						$data['ProjectEmployee']['end_date'] = $project['Project']['end_date'];
						$data['ProjectEmployee']['current_status'] = $project['Project']['current_status'];
						debug($data);
						$this->ProjectEmployee->create();
						$this->ProjectEmployee->save($data,false);
					// }
				}else{
					$this->Session->setFlash(__('Member already added. Send release request'));
					$this->redirect(array('controller'=>'projects', 'action' => 'pro_meb_details','current_project_id'=>$this->request->data['ProjectEmployee']['current_project_id'],'project_id'=>$this->request->data['ProjectEmployee']['project_id']));
				}
			}
		}
	}

	// exit;
		$this->redirect(array('controller'=>'projects', 'action' => 'pro_meb_details','current_project_id'=>$this->request->data['ProjectEmployee']['current_project_id'],'project_id'=>$this->request->data['ProjectEmployee']['project_id']));

	
	// 	if($this->_show_approvals()){
	// 		$this->loadModel('User');
	// 		$this->User->recursive = 0;
	// 		$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.is_approvar'=>1)));
	// 		$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
	// 	}
		
	// 	if ($this->request->is('post')) {
 //                        $this->request->data['ProjectEmployee']['system_table_id'] = $this->_get_system_table_id();
	// 		$this->ProjectEmployee->create();
	// 		if ($this->ProjectEmployee->save($this->request->data)) {

	// 			if($this->_show_approvals()){
	// 				$this->loadModel('Approval');
	// 				$this->Approval->create();
	// 				$this->request->data['Approval']['model_name']='ProjectEmployee';
	// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
	// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
	// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['record']=$this->ProjectEmployee->id;
	// 				$this->Approval->save($this->request->data['Approval']);
	// 			}
	// 			$this->Session->setFlash(__('The project employee has been saved'));
	// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectEmployee->id));
	// 			else $this->redirect(array('action' => 'index'));
	// 		} else {
	// 			$this->Session->setFlash(__('The project employee could not be saved. Please, try again.'));
	// 		}
	// 	}
	// 	$projects = $this->ProjectEmployee->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
	// 	$milestones = $this->ProjectEmployee->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
	// 	$employees = $this->ProjectEmployee->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
	// 	$systemTables = $this->ProjectEmployee->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	// 	$masterListOfFormats = $this->ProjectEmployee->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	// 	$preparedBies = $this->ProjectEmployee->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	// 	$approvedBies = $this->ProjectEmployee->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	// 	$createdBies = $this->ProjectEmployee->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	// 	$modifiedBies = $this->ProjectEmployee->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	// 			$this->set(compact('projects', 'milestones', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	// $count = $this->ProjectEmployee->find('count');
	// $published = $this->ProjectEmployee->find('count',array('conditions'=>array('ProjectEmployee.publish'=>1)));
	// $unpublished = $this->ProjectEmployee->find('count',array('conditions'=>array('ProjectEmployee.publish'=>0)));
		
	// $this->set(compact('count','published','unpublished'));

	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ProjectEmployee->exists($id)) {
			throw new NotFoundException(__('Invalid project employee'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProjectEmployee']['system_table_id'] = $this->_get_system_table_id();
			if ($this->ProjectEmployee->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project employee could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectEmployee.' . $this->ProjectEmployee->primaryKey => $id));
			$this->request->data = $this->ProjectEmployee->find('first', $options);
		}
		$projects = $this->ProjectEmployee->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectEmployee->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$employees = $this->ProjectEmployee->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectEmployee->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectEmployee->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectEmployee->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectEmployee->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectEmployee->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectEmployee->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectEmployee->find('count');
		$published = $this->ProjectEmployee->find('count',array('conditions'=>array('ProjectEmployee.publish'=>1)));
		$unpublished = $this->ProjectEmployee->find('count',array('conditions'=>array('ProjectEmployee.publish'=>0)));
		
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
		if (!$this->ProjectEmployee->exists($id)) {
			throw new NotFoundException(__('Invalid project employee'));
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
			if ($this->ProjectEmployee->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectEmployee->save($this->request->data)) {
                $this->Session->setFlash(__('The project employee has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project employee could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project employee could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectEmployee.' . $this->ProjectEmployee->primaryKey => $id));
			$this->request->data = $this->ProjectEmployee->find('first', $options);
		}
		$projects = $this->ProjectEmployee->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectEmployee->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$employees = $this->ProjectEmployee->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectEmployee->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectEmployee->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectEmployee->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectEmployee->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectEmployee->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectEmployee->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectEmployee->find('count');
		$published = $this->ProjectEmployee->find('count',array('conditions'=>array('ProjectEmployee.publish'=>1)));
		$unpublished = $this->ProjectEmployee->find('count',array('conditions'=>array('ProjectEmployee.publish'=>0)));
		
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
		$this->ProjectEmployee->id = $id;
		if (!$this->ProjectEmployee->exists()) {
			throw new NotFoundException(__('Invalid project employee'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectEmployee->delete()) {
			$this->Session->setFlash(__('Project employee deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project employee was not deleted'));
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
		
		$result = explode('+',$this->request->data['projectEmployees']['rec_selected']);
		$this->ProjectEmployee->recursive = 1;
		$projectEmployees = $this->ProjectEmployee->find('all',array('ProjectEmployee.publish'=>1,'ProjectEmployee.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectEmployee.id'=>$result))));
		$this->set('projectEmployees', $projectEmployees);
		
				$projects = $this->ProjectEmployee->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectEmployee->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$employees = $this->ProjectEmployee->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectEmployee->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectEmployee->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectEmployee->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectEmployee->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectEmployee->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectEmployee->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'milestones', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

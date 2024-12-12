<?php
App::uses('AppController', 'Controller');
/**
 * ProjectQueries Controller
 *
 * @property ProjectQuery $ProjectQuery
 * @property PaginatorComponent $Paginator
 */
class ProjectQueriesController extends AppController {

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
		$conditions = array();
		$this->paginate = array('order'=>array('ProjectQuery.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ProjectQuery->recursive = 0;
		$this->set('projectQueries', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ProjectQuery.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectQuery->recursive = 0;
		$this->set('projectQueries', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectQuery']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectQuery']['search_field'] as $search):
				$search_array[] = array('ProjectQuery.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectQuery.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectQuery->recursive = 0;
		$this->paginate = array('order'=>array('ProjectQuery.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectQuery.soft_delete'=>0 , $cons));
		$this->set('projectQueries', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectQuery.'.$search => $search_key);
					else $search_array[] = array('ProjectQuery.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectQuery.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectQuery.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectQuery.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectQuery.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectQuery.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectQuery->recursive = 0;
		$this->paginate = array('order'=>array('ProjectQuery.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectQuery.soft_delete'=>0 );
		$this->set('projectQueries', $this->paginate());
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
		if (!$this->ProjectQuery->exists($id)) {
			throw new NotFoundException(__('Invalid project query'));
		}
		$options = array('conditions' => array('ProjectQuery.' . $this->ProjectQuery->primaryKey => $id));
		$this->set('projectQuery', $this->ProjectQuery->find('first', $options));

		$PublishedEomployeeLists = $this->_get_employee_list();
		$this->set('PublishedEomployeeLists',$PublishedEomployeeLists);
		$currentStatuses = $this->ProjectQuery->customArray['currentStatuses'];
		$this->set('currentStatuses',$currentStatuses);

		$projectQueryResponses = $this->ProjectQuery->ProjectQueryResponse->find('all',array(
				'conditions'=>array(
					'ProjectQueryResponse.project_query_id'=>$id)
					// 'ProjectQuery.publish'=>1,'ProjectQuery.soft_delete'=>0)
			));
			$this->set('projectQueryResponses',$projectQueryResponses);

	}



/**
 * list method
 *
 * @return void
 */
	public function lists() {
	
        $this->_get_count();		

	}


	public function _uploaddocument($id = null,$file = null){
        $path = WWW_ROOT . DS . 'img'. DS . 'files'. DS . $this->Session->read('User.company_id'). DS . 'qurery_file' . DS . $id;
        try{
            mkdir($path);
        }catch(Exception $e){                

            debug($e);    
        }
        chmod($path,0777);
        $moveLogo = move_uploaded_file($file['tmp_name'], $path . DS . $file['name']); 
        if($moveLogo){
          
        } else {
          
        }
        return true;
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

			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;
			$this->request->data['ProjectQuery']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectQuery->create();
			if ($this->ProjectQuery->save($this->request->data)) {
				foreach ($this->request->data['Files'] as $file) {
					$this->_uploaddocument($this->ProjectQuery->id,$file);
				}
				return true;
				// if ($this->_show_approvals()) $this->_save_approvals();
				// $this->Session->setFlash(__('The project query has been saved'));
				// if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectQuery->id));
				// else $this->redirect(array('action' => 'index'));
			} else {
				// $this->Session->setFlash(__('The project query could not be saved. Please, try again.'));
			}
		}
		$queryTypes = $this->ProjectQuery->QueryType->find('list',array('conditions'=>array('QueryType.publish'=>1,'QueryType.soft_delete'=>0)));
		// $projects = $this->ProjectQuery->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		// $milestones = $this->ProjectQuery->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		// $projectFiles = $this->ProjectQuery->ProjectFile->find('list',array('conditions'=>array('ProjectFile.publish'=>1,'ProjectFile.soft_delete'=>0)));
		// $projectProcessPlans = $this->ProjectQuery->ProjectProcessPlan->find('list',array('conditions'=>array('ProjectProcessPlan.publish'=>1,'ProjectProcessPlan.soft_delete'=>0)));
		// $employees = $this->ProjectQuery->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		// $systemTables = $this->ProjectQuery->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		// $masterListOfFormats = $this->ProjectQuery->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		// $preparedBies = $this->ProjectQuery->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		// $approvedBies = $this->ProjectQuery->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		// $createdBies = $this->ProjectQuery->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		// $modifiedBies = $this->ProjectQuery->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		// $this->set(compact('queryTypes', 'projects', 'milestones', 'projectFiles', 'projectProcessPlans', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		// $count = $this->ProjectQuery->find('count');
		// $published = $this->ProjectQuery->find('count',array('conditions'=>array('ProjectQuery.publish'=>1)));
		// $unpublished = $this->ProjectQuery->find('count',array('conditions'=>array('ProjectQuery.publish'=>0)));
			
		// $this->set(compact('count','published','unpublished'));

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
                        $this->request->data['ProjectQuery']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectQuery->create();
			if ($this->ProjectQuery->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='ProjectQuery';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->ProjectQuery->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The project query has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectQuery->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project query could not be saved. Please, try again.'));
			}
		}
		$queryTypes = $this->ProjectQuery->QueryType->find('list',array('conditions'=>array('QueryType.publish'=>1,'QueryType.soft_delete'=>0)));
		$projects = $this->ProjectQuery->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectQuery->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectFiles = $this->ProjectQuery->ProjectFile->find('list',array('conditions'=>array('ProjectFile.publish'=>1,'ProjectFile.soft_delete'=>0)));
		$projectProcessPlans = $this->ProjectQuery->ProjectProcessPlan->find('list',array('conditions'=>array('ProjectProcessPlan.publish'=>1,'ProjectProcessPlan.soft_delete'=>0)));
		$employees = $this->ProjectQuery->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectQuery->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectQuery->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectQuery->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectQuery->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectQuery->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectQuery->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('queryTypes', 'projects', 'milestones', 'projectFiles', 'projectProcessPlans', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ProjectQuery->find('count');
	$published = $this->ProjectQuery->find('count',array('conditions'=>array('ProjectQuery.publish'=>1)));
	$unpublished = $this->ProjectQuery->find('count',array('conditions'=>array('ProjectQuery.publish'=>0)));
		
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
		if (!$this->ProjectQuery->exists($id)) {
			throw new NotFoundException(__('Invalid project query'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProjectQuery']['system_table_id'] = $this->_get_system_table_id();
			if ($this->ProjectQuery->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project query could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectQuery.' . $this->ProjectQuery->primaryKey => $id));
			$this->request->data = $this->ProjectQuery->find('first', $options);
		}
		$queryTypes = $this->ProjectQuery->QueryType->find('list',array('conditions'=>array('QueryType.publish'=>1,'QueryType.soft_delete'=>0)));
		$projects = $this->ProjectQuery->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectQuery->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectFiles = $this->ProjectQuery->ProjectFile->find('list',array('conditions'=>array('ProjectFile.publish'=>1,'ProjectFile.soft_delete'=>0)));
		$projectProcessPlans = $this->ProjectQuery->ProjectProcessPlan->find('list',array('conditions'=>array('ProjectProcessPlan.publish'=>1,'ProjectProcessPlan.soft_delete'=>0)));
		$employees = $this->ProjectQuery->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectQuery->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectQuery->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectQuery->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectQuery->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectQuery->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectQuery->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('queryTypes', 'projects', 'milestones', 'projectFiles', 'projectProcessPlans', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectQuery->find('count');
		$published = $this->ProjectQuery->find('count',array('conditions'=>array('ProjectQuery.publish'=>1)));
		$unpublished = $this->ProjectQuery->find('count',array('conditions'=>array('ProjectQuery.publish'=>0)));
		
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
		if (!$this->ProjectQuery->exists($id)) {
			throw new NotFoundException(__('Invalid project query'));
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
			if ($this->ProjectQuery->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectQuery->save($this->request->data)) {
                $this->Session->setFlash(__('The project query has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project query could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project query could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectQuery.' . $this->ProjectQuery->primaryKey => $id));
			$this->request->data = $this->ProjectQuery->find('first', $options);
		}
		$queryTypes = $this->ProjectQuery->QueryType->find('list',array('conditions'=>array('QueryType.publish'=>1,'QueryType.soft_delete'=>0)));
		$projects = $this->ProjectQuery->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectQuery->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectFiles = $this->ProjectQuery->ProjectFile->find('list',array('conditions'=>array('ProjectFile.publish'=>1,'ProjectFile.soft_delete'=>0)));
		$projectProcessPlans = $this->ProjectQuery->ProjectProcessPlan->find('list',array('conditions'=>array('ProjectProcessPlan.publish'=>1,'ProjectProcessPlan.soft_delete'=>0)));
		$employees = $this->ProjectQuery->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectQuery->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectQuery->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectQuery->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectQuery->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectQuery->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectQuery->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('queryTypes', 'projects', 'milestones', 'projectFiles', 'projectProcessPlans', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectQuery->find('count');
		$published = $this->ProjectQuery->find('count',array('conditions'=>array('ProjectQuery.publish'=>1)));
		$unpublished = $this->ProjectQuery->find('count',array('conditions'=>array('ProjectQuery.publish'=>0)));
		
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
		$this->ProjectQuery->id = $id;
		if (!$this->ProjectQuery->exists()) {
			throw new NotFoundException(__('Invalid project query'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectQuery->delete()) {
			$this->Session->setFlash(__('Project query deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project query was not deleted'));
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
		
		$result = explode('+',$this->request->data['projectQueries']['rec_selected']);
		$this->ProjectQuery->recursive = 1;
		$projectQueries = $this->ProjectQuery->find('all',array('ProjectQuery.publish'=>1,'ProjectQuery.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectQuery.id'=>$result))));
		$this->set('projectQueries', $projectQueries);
		
				$queryTypes = $this->ProjectQuery->QueryType->find('list',array('conditions'=>array('QueryType.publish'=>1,'QueryType.soft_delete'=>0)));
		$projects = $this->ProjectQuery->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectQuery->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$projectFiles = $this->ProjectQuery->ProjectFile->find('list',array('conditions'=>array('ProjectFile.publish'=>1,'ProjectFile.soft_delete'=>0)));
		$projectProcessPlans = $this->ProjectQuery->ProjectProcessPlan->find('list',array('conditions'=>array('ProjectProcessPlan.publish'=>1,'ProjectProcessPlan.soft_delete'=>0)));
		$employees = $this->ProjectQuery->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectQuery->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectQuery->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectQuery->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectQuery->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectQuery->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectQuery->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('queryTypes', 'projects', 'milestones', 'projectFiles', 'projectProcessPlans', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'queryTypes', 'projects', 'milestones', 'projectFiles', 'projectProcessPlans', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

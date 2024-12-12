<?php
App::uses('AppController', 'Controller');
/**
 * ProjectTimesheets Controller
 *
 * @property ProjectTimesheet $ProjectTimesheet
 * @property PaginatorComponent $Paginator
 */
class ProjectTimesheetsController extends AppController {

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
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('ProjectTimesheet.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ProjectTimesheet->recursive = 0;
		$this->set('projectTimesheets', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ProjectTimesheet.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectTimesheet->recursive = 0;
		$this->set('projectTimesheets', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectTimesheet']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectTimesheet']['search_field'] as $search):
				$search_array[] = array('ProjectTimesheet.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectTimesheet.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectTimesheet->recursive = 0;
		$this->paginate = array('order'=>array('ProjectTimesheet.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectTimesheet.soft_delete'=>0 , $cons));
		$this->set('projectTimesheets', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectTimesheet.'.$search => $search_key);
					else $search_array[] = array('ProjectTimesheet.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectTimesheet.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectTimesheet.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectTimesheet.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectTimesheet.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectTimesheet.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectTimesheet->recursive = 0;
		$this->paginate = array('order'=>array('ProjectTimesheet.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectTimesheet.soft_delete'=>0 );
		$this->set('projectTimesheets', $this->paginate());
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
		if (!$this->ProjectTimesheet->exists($id)) {
			throw new NotFoundException(__('Invalid project timesheet'));
		}
		$options = array('conditions' => array('ProjectTimesheet.' . $this->ProjectTimesheet->primaryKey => $id));
		$this->set('projectTimesheet', $this->ProjectTimesheet->find('first', $options));
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
			$this->request->data['ProjectTimesheet']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectTimesheet->create();
			if ($this->ProjectTimesheet->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The project timesheet has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectTimesheet->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project timesheet could not be saved. Please, try again.'));
			}
		}
		$users = $this->ProjectTimesheet->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$projects = $this->ProjectTimesheet->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$projectActivities = $this->ProjectTimesheet->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$systemTables = $this->ProjectTimesheet->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectTimesheet->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectTimesheet->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectTimesheet->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectTimesheet->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectTimesheet->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectTimesheet->find('count');
		$published = $this->ProjectTimesheet->find('count',array('conditions'=>array('ProjectTimesheet.publish'=>1)));
		$unpublished = $this->ProjectTimesheet->find('count',array('conditions'=>array('ProjectTimesheet.publish'=>0)));
			
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
                        $this->request->data['ProjectTimesheet']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectTimesheet->create();
			if ($this->ProjectTimesheet->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='ProjectTimesheet';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->ProjectTimesheet->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The project timesheet has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectTimesheet->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project timesheet could not be saved. Please, try again.'));
			}
		}
		$users = $this->ProjectTimesheet->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$projects = $this->ProjectTimesheet->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$projectActivities = $this->ProjectTimesheet->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$systemTables = $this->ProjectTimesheet->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectTimesheet->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectTimesheet->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectTimesheet->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectTimesheet->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectTimesheet->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ProjectTimesheet->find('count');
	$published = $this->ProjectTimesheet->find('count',array('conditions'=>array('ProjectTimesheet.publish'=>1)));
	$unpublished = $this->ProjectTimesheet->find('count',array('conditions'=>array('ProjectTimesheet.publish'=>0)));
		
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
		if (!$this->ProjectTimesheet->exists($id)) {
			throw new NotFoundException(__('Invalid project timesheet'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProjectTimesheet']['system_table_id'] = $this->_get_system_table_id();
			if ($this->ProjectTimesheet->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project timesheet could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectTimesheet.' . $this->ProjectTimesheet->primaryKey => $id));
			$this->request->data = $this->ProjectTimesheet->find('first', $options);
		}
		$users = $this->ProjectTimesheet->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$projects = $this->ProjectTimesheet->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$projectActivities = $this->ProjectTimesheet->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$systemTables = $this->ProjectTimesheet->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectTimesheet->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectTimesheet->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectTimesheet->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectTimesheet->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectTimesheet->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectTimesheet->find('count');
		$published = $this->ProjectTimesheet->find('count',array('conditions'=>array('ProjectTimesheet.publish'=>1)));
		$unpublished = $this->ProjectTimesheet->find('count',array('conditions'=>array('ProjectTimesheet.publish'=>0)));
		
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
		if (!$this->ProjectTimesheet->exists($id)) {
			throw new NotFoundException(__('Invalid project timesheet'));
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
			if ($this->ProjectTimesheet->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectTimesheet->save($this->request->data)) {
                $this->Session->setFlash(__('The project timesheet has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project timesheet could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project timesheet could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectTimesheet.' . $this->ProjectTimesheet->primaryKey => $id));
			$this->request->data = $this->ProjectTimesheet->find('first', $options);
		}
		$users = $this->ProjectTimesheet->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$projects = $this->ProjectTimesheet->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$projectActivities = $this->ProjectTimesheet->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$systemTables = $this->ProjectTimesheet->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectTimesheet->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectTimesheet->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectTimesheet->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectTimesheet->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectTimesheet->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectTimesheet->find('count');
		$published = $this->ProjectTimesheet->find('count',array('conditions'=>array('ProjectTimesheet.publish'=>1)));
		$unpublished = $this->ProjectTimesheet->find('count',array('conditions'=>array('ProjectTimesheet.publish'=>0)));
		
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
		$this->ProjectTimesheet->id = $id;
		if (!$this->ProjectTimesheet->exists()) {
			throw new NotFoundException(__('Invalid project timesheet'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectTimesheet->delete()) {
			$this->Session->setFlash(__('Project timesheet deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project timesheet was not deleted'));
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
		
		$result = explode('+',$this->request->data['projectTimesheets']['rec_selected']);
		$this->ProjectTimesheet->recursive = 1;
		$projectTimesheets = $this->ProjectTimesheet->find('all',array('ProjectTimesheet.publish'=>1,'ProjectTimesheet.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectTimesheet.id'=>$result))));
		$this->set('projectTimesheets', $projectTimesheets);
		
				$users = $this->ProjectTimesheet->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$projects = $this->ProjectTimesheet->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$projectActivities = $this->ProjectTimesheet->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
		$systemTables = $this->ProjectTimesheet->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectTimesheet->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectTimesheet->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectTimesheet->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectTimesheet->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectTimesheet->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'users', 'projects', 'projectActivities', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function project_timesheet_ajax(){
		$projectResourcess = $this->ProjectTimesheet->Project->ProjectResource->find('all',
			array(
				'conditions'=>array('ProjectResource.user_id'=>$this->Session->read('User.id')),
				'recursive'=>0
			)
		);
		// Configure::write('debug',1);
		foreach ($projectResourcess as $projectResource) {
			$act = $this->ProjectTimesheet->Project->ProjectActivity->find('list',array(
				'conditions'=>array('ProjectActivity.project_id'=>$projectResource['Project']['id'])
			));
			debug($act);
			$projectResource['ProjectActivities'] = $act;
			$projectResources[] = $projectResource;
		}
		$this->set(compact('projectResources'));
		// $projectTimesheets = $this->ProjectTimesheet->find('all',array(
		// 	'conditions'=>array('ProjectTimesheet.user_id'=>$this->Session->read('User.id'))
		// ));	
		
		$projectTimesheets = $this->ProjectTimesheet->find('all',array(
			'conditions'=>array('ProjectTimesheet.user_id'=>$this->Session->read('User.id')),
			'recursive'=>0,
		));

		$this->set(compact('projectTimesheets'));

		if ($this->request->is('post')) {
			// $this->request->data['ProjectTimesheet']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectTimesheet->create();
			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;
			foreach ($this->request->data['ProjectTimesheet'] as $pt) {
				if($pt['total']>0){
					$this->ProjectTimesheet->create();
					$this->ProjectTimesheet->save($pt,false);	
				}
				
			}
			// if ($this->ProjectTimesheet->save($this->request->data)) {


			// 	if ($this->_show_approvals()) $this->_save_approvals();
			// 	$this->Session->setFlash(__('The project timesheet has been saved'));
			// 	if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectTimesheet->id));
			// 	else $this->redirect(array('action' => 'index'));
			// } else {
			// 	$this->Session->setFlash(__('The project timesheet could not be saved. Please, try again.'));
			// }
		}
	}
}

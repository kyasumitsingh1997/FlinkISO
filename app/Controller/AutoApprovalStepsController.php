<?php
App::uses('AppController', 'Controller');
/**
 * AutoApprovalSteps Controller
 *
 * @property AutoApprovalStep $AutoApprovalStep
 * @property PaginatorComponent $Paginator
 */
class AutoApprovalStepsController extends AppController {

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
		$this->redirect(array('controller'=>'auto_approvals', 'action' => 'index'));
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('AutoApprovalStep.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->AutoApprovalStep->recursive = 0;
		$this->set('autoApprovalSteps', $this->paginate());
		
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
		$this->paginate = array('order'=>array('AutoApprovalStep.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->AutoApprovalStep->recursive = 0;
		$this->set('autoApprovalSteps', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['AutoApprovalStep']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['AutoApprovalStep']['search_field'] as $search):
				$search_array[] = array('AutoApprovalStep.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('AutoApprovalStep.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->AutoApprovalStep->recursive = 0;
		$this->paginate = array('order'=>array('AutoApprovalStep.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'AutoApprovalStep.soft_delete'=>0 , $cons));
		$this->set('autoApprovalSteps', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('AutoApprovalStep.'.$search => $search_key);
					else $search_array[] = array('AutoApprovalStep.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('AutoApprovalStep.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('AutoApprovalStep.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'AutoApprovalStep.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('AutoApprovalStep.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('AutoApprovalStep.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->AutoApprovalStep->recursive = 0;
		$this->paginate = array('order'=>array('AutoApprovalStep.sr_no'=>'DESC'),'conditions'=>$conditions , 'AutoApprovalStep.soft_delete'=>0 );
		$this->set('autoApprovalSteps', $this->paginate());
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
		if (!$this->AutoApprovalStep->exists($id)) {
			throw new NotFoundException(__('Invalid auto approval step'));
		}
		$options = array('conditions' => array('AutoApprovalStep.' . $this->AutoApprovalStep->primaryKey => $id));
		$this->set('autoApprovalStep', $this->AutoApprovalStep->find('first', $options));
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
			$this->request->data['AutoApprovalStep']['system_table_id'] = $this->_get_system_table_id();
			

			$this->request->data['AutoApprovalStep']['system_table_id'] = $this->_get_system_table_id();
			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;
			// check if step already exists
			
			// $step = $this->AutoApprovalStep->find('count',array(
			// 			'recursive'=>-1,
			// 			'conditions'=>array(
			// 				'AutoApprovalStep.step_number'=>$this->request->data['AutoApprovalStep']['step_number'],
			// 				'AutoApprovalStep.branch_id'=>$this->request->data['AutoApprovalStep']['branch_id'],
			// 				'AutoApprovalStep.department_id'=>$this->request->data['AutoApprovalStep']['department_id'],
			// 				'AutoApprovalStep.auto_approval_id'=>$this->request->data['AutoApprovalStep']['auto_approval_id']
			// 				),
			// 			));

			// if($step > 0){
			// 	$this->Session->setFlash(__('Step alreay exist'));
			// 	$this->redirect(array('controller'=>'auto_approvals', 'action' => 'view', $this->request->data['AutoApprovalStep']['auto_approval_id']));
			// }

			
			// Configure::Write('debug',1);
			// debug($this->request->data);

			foreach ($this->request->data['AutoApprovalStep']['branch_id'] as $bid) {
				// debug($bid);
				foreach ($this->request->data['AutoApprovalStep']['Department'] as $data) {
					foreach ($data['department_id'] as $did) {
						// debug($did);
						// debug($bid);
						// debug($data['user_id']);
						if(!empty($data['user_id'])){
							$existing = $this->AutoApprovalStep->find('first',array('conditions'=>array(
							'AutoApprovalStep.step_number'=>$this->request->data['AutoApprovalStep']['step_number'],
							'AutoApprovalStep.branch_id'=>$bid,
							'AutoApprovalStep.department_id'=>$did,
							'AutoApprovalStep.auto_approval_id'=>$this->request->data['AutoApprovalStep']['auto_approval_id']
						)));
						if($existing){
							$newData['id'] = $existing['AutoApprovalStep']['id'];
						}
						$newData['department_id'] = $did;
						$newData['user_id'] = $data['user_id'];
						$newData['auto_approval_id'] = $this->request->data['AutoApprovalStep']['auto_approval_id'];
						$newData['system_table'] = $this->request->data['AutoApprovalStep']['system_table'];
						$newData['step_number'] = $this->request->data['AutoApprovalStep']['step_number'];
						$newData['name'] = $this->request->data['AutoApprovalStep']['name'];
						$newData['branch_id'] = $bid;
						$newData['details'] = $this->request->data['AutoApprovalStep']['details'];
						$newData['allow_approval'] = $this->request->data['AutoApprovalStep']['allow_approval'];
						$newData['show_details'] = $this->request->data['AutoApprovalStep']['show_details'];
						$newData['publish'] = $this->request->data['AutoApprovalStep']['publish'];
						$newData['branchid'] = $this->request->data['AutoApprovalStep']['branchid'];
						$newData['departmentid'] = $this->request->data['AutoApprovalStep']['departmentid'];
						$newData['master_list_of_format_id'] = $this->request->data['AutoApprovalStep']['master_list_of_format_id'];
						// debug($newData);
						$this->AutoApprovalStep->create();
						if ($this->AutoApprovalStep->save($newData)) {
							// $this->redirect(array('controller'=>'auto_approvals', 'action' => 'view', $this->request->data['AutoApprovalStep']['auto_approval_id']));
						} else {
							// $this->Session->setFlash(__('The auto approval step could not be saved. Please, try again.'));
						}
						$newData['id'] = NULL;
						}
					}
				}
			}
			// exit;
			
			// foreach ($this->request->data['AutoApprovalStep']['Department'] as $data) {
			// 	if($data['user_id'] != -1){

			// 		$existing = $this->AutoApprovalStep->find('first',array('conditions'=>array(
			// 				'AutoApprovalStep.step_number'=>$this->request->data['AutoApprovalStep']['step_number'],
			// 				'AutoApprovalStep.branch_id'=>$this->request->data['AutoApprovalStep']['branch_id'],
			// 				'AutoApprovalStep.department_id'=>$data['department_id'],
			// 				'AutoApprovalStep.auto_approval_id'=>$this->request->data['AutoApprovalStep']['auto_approval_id']
			// 			)));
			// 		if($existing){
			// 			$newData['id'] = $existing['AutoApprovalStep']['id'];
			// 		}
			// 		$newData['department_id'] = $data['department_id'];
			// 		$newData['user_id'] = $data['user_id'];
			// 		$newData['auto_approval_id'] = $this->request->data['AutoApprovalStep']['auto_approval_id'];
			// 		$newData['system_table'] = $this->request->data['AutoApprovalStep']['system_table'];
			// 		$newData['step_number'] = $this->request->data['AutoApprovalStep']['step_number'];
			// 		$newData['name'] = $this->request->data['AutoApprovalStep']['name'];
			// 		$newData['branch_id'] = $this->request->data['AutoApprovalStep']['branch_id'];
			// 		$newData['details'] = $this->request->data['AutoApprovalStep']['details'];
			// 		$newData['allow_approval'] = $this->request->data['AutoApprovalStep']['allow_approval'];
			// 		$newData['show_details'] = $this->request->data['AutoApprovalStep']['show_details'];
			// 		$newData['publish'] = $this->request->data['AutoApprovalStep']['publish'];
			// 		$newData['branchid'] = $this->request->data['AutoApprovalStep']['branchid'];
			// 		$newData['departmentid'] = $this->request->data['AutoApprovalStep']['departmentid'];
			// 		$newData['master_list_of_format_id'] = $this->request->data['AutoApprovalStep']['master_list_of_format_id'];
			// 		// debug($newData);
			// 		$this->AutoApprovalStep->create();
			// 		if ($this->AutoApprovalStep->save($newData)) {
			// 			// $this->redirect(array('controller'=>'auto_approvals', 'action' => 'view', $this->request->data['AutoApprovalStep']['auto_approval_id']));
			// 		} else {
			// 			// $this->Session->setFlash(__('The auto approval step could not be saved. Please, try again.'));
			// 		}
			// 		$newData['id'] = NULL;
			// 	}					
			// }

			$this->Session->setFlash(__('The auto approval steps saved.'));
			$this->redirect(array('controller'=>'auto_approvals', 'action' => 'view', $this->request->data['AutoApprovalStep']['auto_approval_id']));

			
		}
		$autoApprovals = $this->AutoApprovalStep->AutoApproval->find('list',array('conditions'=>array('AutoApproval.publish'=>1,'AutoApproval.soft_delete'=>0)));
		$users = $this->AutoApprovalStep->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$systemTables = $this->AutoApprovalStep->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$createdBies = $this->AutoApprovalStep->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AutoApprovalStep->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('autoApprovals', 'users', 'systemTables', 'createdBies', 'modifiedBies'));
	$count = $this->AutoApprovalStep->find('count');
	$published = $this->AutoApprovalStep->find('count',array('conditions'=>array('AutoApprovalStep.publish'=>1)));
	$unpublished = $this->AutoApprovalStep->find('count',array('conditions'=>array('AutoApprovalStep.publish'=>0)));
		
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
 //                        $this->request->data['AutoApprovalStep']['system_table_id'] = $this->_get_system_table_id();
	// 		$this->AutoApprovalStep->create();
	// 		if ($this->AutoApprovalStep->save($this->request->data)) {

	// 			if($this->_show_approvals()){
	// 				$this->loadModel('Approval');
	// 				$this->Approval->create();
	// 				$this->request->data['Approval']['model_name']='AutoApprovalStep';
	// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
	// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
	// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['record']=$this->AutoApprovalStep->id;
	// 				$this->Approval->save($this->request->data['Approval']);
	// 			}
	// 			$this->Session->setFlash(__('The auto approval step has been saved'));
	// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->AutoApprovalStep->id));
	// 			else $this->redirect(array('action' => 'index'));
	// 		} else {
	// 			$this->Session->setFlash(__('The auto approval step could not be saved. Please, try again.'));
	// 		}
	// 	}
	// 	$autoApprovals = $this->AutoApprovalStep->AutoApproval->find('list',array('conditions'=>array('AutoApproval.publish'=>1,'AutoApproval.soft_delete'=>0)));
	// 	$users = $this->AutoApprovalStep->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
	// 	$systemTables = $this->AutoApprovalStep->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	// 	$createdBies = $this->AutoApprovalStep->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	// 	$modifiedBies = $this->AutoApprovalStep->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	// 			$this->set(compact('autoApprovals', 'users', 'systemTables', 'createdBies', 'modifiedBies'));
	// $count = $this->AutoApprovalStep->find('count');
	// $published = $this->AutoApprovalStep->find('count',array('conditions'=>array('AutoApprovalStep.publish'=>1)));
	// $unpublished = $this->AutoApprovalStep->find('count',array('conditions'=>array('AutoApprovalStep.publish'=>0)));
		
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
		if (!$this->AutoApprovalStep->exists($id)) {
			throw new NotFoundException(__('Invalid auto approval step'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        		$this->request->data[$this->modelClass]['publish'] = 0;
      		}

			if ($this->AutoApprovalStep->save($this->request->data)) {

				// if ($this->_show_approvals()) $this->_save_approvals();
				
				// if ($this->_show_evidence() == true)
				//  $this->redirect(array('action' => 'view', $id));
				// else
				$this->redirect(array('controller'=>'auto_approvals', 'action' => 'view', $this->request->data['AutoApprovalStep']['auto_approval_id']));

			} else {
				$this->Session->setFlash(__('The auto approval step could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AutoApprovalStep.' . $this->AutoApprovalStep->primaryKey => $id));
			$this->request->data = $this->AutoApprovalStep->find('first', $options);
		}
		$autoApprovals = $this->AutoApprovalStep->AutoApproval->find('list',array('conditions'=>array('AutoApproval.publish'=>1,'AutoApproval.soft_delete'=>0)));
		$users = $this->AutoApprovalStep->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$systemTables = $this->AutoApprovalStep->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$createdBies = $this->AutoApprovalStep->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AutoApprovalStep->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('autoApprovals', 'users', 'systemTables', 'createdBies', 'modifiedBies'));
		$count = $this->AutoApprovalStep->find('count');
		$published = $this->AutoApprovalStep->find('count',array('conditions'=>array('AutoApprovalStep.publish'=>1)));
		$unpublished = $this->AutoApprovalStep->find('count',array('conditions'=>array('AutoApprovalStep.publish'=>0)));
		
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
		if (!$this->AutoApprovalStep->exists($id)) {
			throw new NotFoundException(__('Invalid auto approval step'));
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
			if ($this->AutoApprovalStep->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->AutoApprovalStep->save($this->request->data)) {
                $this->Session->setFlash(__('The auto approval step has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The auto approval step could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The auto approval step could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AutoApprovalStep.' . $this->AutoApprovalStep->primaryKey => $id));
			$this->request->data = $this->AutoApprovalStep->find('first', $options);
		}
		$autoApprovals = $this->AutoApprovalStep->AutoApproval->find('list',array('conditions'=>array('AutoApproval.publish'=>1,'AutoApproval.soft_delete'=>0)));
		$users = $this->AutoApprovalStep->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$systemTables = $this->AutoApprovalStep->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$createdBies = $this->AutoApprovalStep->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AutoApprovalStep->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('autoApprovals', 'users', 'systemTables', 'createdBies', 'modifiedBies'));
		$count = $this->AutoApprovalStep->find('count');
		$published = $this->AutoApprovalStep->find('count',array('conditions'=>array('AutoApprovalStep.publish'=>1)));
		$unpublished = $this->AutoApprovalStep->find('count',array('conditions'=>array('AutoApprovalStep.publish'=>0)));
		
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
		$this->AutoApprovalStep->id = $id;
		if (!$this->AutoApprovalStep->exists()) {
			throw new NotFoundException(__('Invalid auto approval step'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->AutoApprovalStep->delete()) {
			$this->Session->setFlash(__('Auto approval step deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Auto approval step was not deleted'));
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
		
		$result = explode('+',$this->request->data['autoApprovalSteps']['rec_selected']);
		$this->AutoApprovalStep->recursive = 1;
		$autoApprovalSteps = $this->AutoApprovalStep->find('all',array('AutoApprovalStep.publish'=>1,'AutoApprovalStep.soft_delete'=>1,'conditions'=>array('or'=>array('AutoApprovalStep.id'=>$result))));
		$this->set('autoApprovalSteps', $autoApprovalSteps);
		
				$autoApprovals = $this->AutoApprovalStep->AutoApproval->find('list',array('conditions'=>array('AutoApproval.publish'=>1,'AutoApproval.soft_delete'=>0)));
		$users = $this->AutoApprovalStep->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
		$systemTables = $this->AutoApprovalStep->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$createdBies = $this->AutoApprovalStep->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AutoApprovalStep->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('autoApprovals', 'users', 'systemTables', 'createdBies', 'modifiedBies', 'autoApprovals', 'users', 'systemTables', 'createdBies', 'modifiedBies'));
}

	function deletestep($id = null, $auto_approval_id = null){
		$this->AutoApprovalStep->delete($id);
		$this->Session->setFlash(__('The auto approval steps deleted.'));
		$this->redirect(array('controller'=>'auto_approvals', 'action' => 'view', $auto_approval_id));
	}

	function delete_setp(){
		$this->AutoApprovalStep->deleteAll(array('AutoApprovalStep.id'=>$this->request->params['pass'][0]));
		$this->redirect(array('controller'=>'auto_approvals', 'action' => 'view',$this->request->params['named']['app_id']));
	}
}

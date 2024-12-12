<?php
App::uses('AppController', 'Controller');
/**
 * InternalAuditQuestions Controller
 *
 * @property InternalAuditQuestion $InternalAuditQuestion
 */
class InternalAuditQuestionsController extends AppController {

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
		$this->paginate = array('order'=>array('InternalAuditQuestion.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->InternalAuditQuestion->recursive = 0;
		$this->set('internalAuditQuestions', $this->paginate());
		
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
		$this->paginate = array('order'=>array('InternalAuditQuestion.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->InternalAuditQuestion->recursive = 0;
		$this->set('internalAuditQuestions', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['InternalAuditQuestion']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['InternalAuditQuestion']['search_field'] as $search):
				$search_array[] = array('InternalAuditQuestion.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('InternalAuditQuestion.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->InternalAuditQuestion->recursive = 0;
		$this->paginate = array('order'=>array('InternalAuditQuestion.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'InternalAuditQuestion.soft_delete'=>0 , $cons));
		$this->set('internalAuditQuestions', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('InternalAuditQuestion.'.$search => $search_key);
					else $search_array[] = array('InternalAuditQuestion.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('InternalAuditQuestion.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('InternalAuditQuestion.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'InternalAuditQuestion.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('InternalAuditQuestion.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('InternalAuditQuestion.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->InternalAuditQuestion->recursive = 0;
		$this->paginate = array('order'=>array('InternalAuditQuestion.sr_no'=>'DESC'),'conditions'=>$conditions , 'InternalAuditQuestion.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('internalAuditQuestions', $this->paginate());
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
		if (!$this->InternalAuditQuestion->exists($id)) {
			throw new NotFoundException(__('Invalid internal audit question'));
		}
		$options = array('conditions' => array('InternalAuditQuestion.' . $this->InternalAuditQuestion->primaryKey => $id));
		$this->set('internalAuditQuestion', $this->InternalAuditQuestion->find('first', $options));
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
			$this->request->data['InternalAuditQuestion']['system_table_id'] = $this->_get_system_table_id();
			$this->InternalAuditQuestion->create();
			if ($this->InternalAuditQuestion->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The internal audit question has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->InternalAuditQuestion->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The internal audit question could not be saved. Please, try again.'));
			}
		}
		$approvedBies = $this->InternalAuditQuestion->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->InternalAuditQuestion->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$departments = $this->InternalAuditQuestion->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$systemTables = $this->InternalAuditQuestion->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->InternalAuditQuestion->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->InternalAuditQuestion->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->InternalAuditQuestion->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('approvedBies', 'preparedBies', 'departments', 'systemTables', 'masterListOfFormats', 'statusUserIds', 'createdBies', 'modifiedBies'));
	$count = $this->InternalAuditQuestion->find('count');
	$published = $this->InternalAuditQuestion->find('count',array('conditions'=>array('InternalAuditQuestion.publish'=>1)));
	$unpublished = $this->InternalAuditQuestion->find('count',array('conditions'=>array('InternalAuditQuestion.publish'=>0)));
		
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
                        $this->request->data['InternalAuditQuestion']['system_table_id'] = $this->_get_system_table_id();
			$this->InternalAuditQuestion->create();
			if ($this->InternalAuditQuestion->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='InternalAuditQuestion';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->InternalAuditQuestion->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The internal audit question has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->InternalAuditQuestion->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The internal audit question could not be saved. Please, try again.'));
			}
		}
		$approvedBies = $this->InternalAuditQuestion->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->InternalAuditQuestion->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$departments = $this->InternalAuditQuestion->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$systemTables = $this->InternalAuditQuestion->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->InternalAuditQuestion->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->InternalAuditQuestion->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->InternalAuditQuestion->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('approvedBies', 'preparedBies', 'departments', 'systemTables', 'masterListOfFormats', 'statusUserIds', 'createdBies', 'modifiedBies'));
	$count = $this->InternalAuditQuestion->find('count');
	$published = $this->InternalAuditQuestion->find('count',array('conditions'=>array('InternalAuditQuestion.publish'=>1)));
	$unpublished = $this->InternalAuditQuestion->find('count',array('conditions'=>array('InternalAuditQuestion.publish'=>0)));
		
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
		if (!$this->InternalAuditQuestion->exists($id)) {
			throw new NotFoundException(__('Invalid internal audit question'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['InternalAuditQuestion']['system_table_id'] = $this->_get_system_table_id();
			if ($this->InternalAuditQuestion->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The internal audit question could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('InternalAuditQuestion.' . $this->InternalAuditQuestion->primaryKey => $id));
			$this->request->data = $this->InternalAuditQuestion->find('first', $options);
		}
		$approvedBies = $this->InternalAuditQuestion->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->InternalAuditQuestion->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$departments = $this->InternalAuditQuestion->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$systemTables = $this->InternalAuditQuestion->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->InternalAuditQuestion->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->InternalAuditQuestion->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->InternalAuditQuestion->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies', 'departments', 'systemTables', 'masterListOfFormats', 'statusUserIds', 'createdBies', 'modifiedBies'));
		$count = $this->InternalAuditQuestion->find('count');
		$published = $this->InternalAuditQuestion->find('count',array('conditions'=>array('InternalAuditQuestion.publish'=>1)));
		$unpublished = $this->InternalAuditQuestion->find('count',array('conditions'=>array('InternalAuditQuestion.publish'=>0)));
		
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
		if (!$this->InternalAuditQuestion->exists($id)) {
			throw new NotFoundException(__('Invalid internal audit question'));
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
			if ($this->InternalAuditQuestion->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->InternalAuditQuestion->save($this->request->data)) {
                $this->Session->setFlash(__('The internal audit question has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The internal audit question could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The internal audit question could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('InternalAuditQuestion.' . $this->InternalAuditQuestion->primaryKey => $id));
			$this->request->data = $this->InternalAuditQuestion->find('first', $options);
		}
		$approvedBies = $this->InternalAuditQuestion->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->InternalAuditQuestion->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$departments = $this->InternalAuditQuestion->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$systemTables = $this->InternalAuditQuestion->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->InternalAuditQuestion->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->InternalAuditQuestion->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->InternalAuditQuestion->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies', 'departments', 'systemTables', 'masterListOfFormats', 'statusUserIds', 'createdBies', 'modifiedBies'));
		$count = $this->InternalAuditQuestion->find('count');
		$published = $this->InternalAuditQuestion->find('count',array('conditions'=>array('InternalAuditQuestion.publish'=>1)));
		$unpublished = $this->InternalAuditQuestion->find('count',array('conditions'=>array('InternalAuditQuestion.publish'=>0)));
		
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
		$this->InternalAuditQuestion->id = $id;
		if (!$this->InternalAuditQuestion->exists()) {
			throw new NotFoundException(__('Invalid internal audit question'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->InternalAuditQuestion->delete()) {
			$this->Session->setFlash(__('Internal audit question deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Internal audit question was not deleted'));
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
		
		$result = explode('+',$this->request->data['internalAuditQuestions']['rec_selected']);
		$this->InternalAuditQuestion->recursive = 1;
		$internalAuditQuestions = $this->InternalAuditQuestion->find('all',array('InternalAuditQuestion.publish'=>1,'InternalAuditQuestion.soft_delete'=>1,'conditions'=>array('or'=>array('InternalAuditQuestion.id'=>$result))));
		$this->set('internalAuditQuestions', $internalAuditQuestions);
		
				$approvedBies = $this->InternalAuditQuestion->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->InternalAuditQuestion->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$departments = $this->InternalAuditQuestion->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$systemTables = $this->InternalAuditQuestion->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->InternalAuditQuestion->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->InternalAuditQuestion->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->InternalAuditQuestion->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies', 'departments', 'systemTables', 'masterListOfFormats', 'statusUserIds', 'createdBies', 'modifiedBies', 'approvedBies', 'preparedBies', 'departments', 'systemTables', 'masterListOfFormats', 'statusUserIds', 'createdBies', 'modifiedBies'));
}
}

<?php
App::uses('AppController', 'Controller');
/**
 * SeveriryTypes Controller
 *
 * @property SeveriryType $SeveriryType
 */
class SeveriryTypesController extends AppController {

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
		$this->paginate = array('order'=>array('SeveriryType.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->SeveriryType->recursive = 0;
		$this->set('severiryTypes', $this->paginate());
		
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
		$this->paginate = array('order'=>array('SeveriryType.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->SeveriryType->recursive = 0;
		$this->set('severiryTypes', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['SeveriryType']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['SeveriryType']['search_field'] as $search):
				$search_array[] = array('SeveriryType.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('SeveriryType.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->SeveriryType->recursive = 0;
		$this->paginate = array('order'=>array('SeveriryType.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'SeveriryType.soft_delete'=>0 , $cons));
		$this->set('severiryTypes', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('SeveriryType.'.$search => $search_key);
					else $search_array[] = array('SeveriryType.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('SeveriryType.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('SeveriryType.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'SeveriryType.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('SeveriryType.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('SeveriryType.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->SeveriryType->recursive = 0;
		$this->paginate = array('order'=>array('SeveriryType.sr_no'=>'DESC'),'conditions'=>$conditions , 'SeveriryType.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('severiryTypes', $this->paginate());
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
		if (!$this->SeveriryType->exists($id)) {
			throw new NotFoundException(__('Invalid severiry type'));
		}
		$options = array('conditions' => array('SeveriryType.' . $this->SeveriryType->primaryKey => $id));
		$this->set('severiryType', $this->SeveriryType->find('first', $options));
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
			$this->request->data['SeveriryType']['system_table_id'] = $this->_get_system_table_id();
			$this->SeveriryType->create();
			if ($this->SeveriryType->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The severiry type has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->SeveriryType->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The severiry type could not be saved. Please, try again.'));
			}
		}
		$systemTables = $this->SeveriryType->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->SeveriryType->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->SeveriryType->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->SeveriryType->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->SeveriryType->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->SeveriryType->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->SeveriryType->find('count');
	$published = $this->SeveriryType->find('count',array('conditions'=>array('SeveriryType.publish'=>1)));
	$unpublished = $this->SeveriryType->find('count',array('conditions'=>array('SeveriryType.publish'=>0)));
		
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
                        $this->request->data['SeveriryType']['system_table_id'] = $this->_get_system_table_id();
			$this->SeveriryType->create();
			if ($this->SeveriryType->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='SeveriryType';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->SeveriryType->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The severiry type has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->SeveriryType->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The severiry type could not be saved. Please, try again.'));
			}
		}
		$systemTables = $this->SeveriryType->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->SeveriryType->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->SeveriryType->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->SeveriryType->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->SeveriryType->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->SeveriryType->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->SeveriryType->find('count');
	$published = $this->SeveriryType->find('count',array('conditions'=>array('SeveriryType.publish'=>1)));
	$unpublished = $this->SeveriryType->find('count',array('conditions'=>array('SeveriryType.publish'=>0)));
		
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
		if (!$this->SeveriryType->exists($id)) {
			throw new NotFoundException(__('Invalid severiry type'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['SeveriryType']['system_table_id'] = $this->_get_system_table_id();
			if ($this->SeveriryType->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The severiry type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SeveriryType.' . $this->SeveriryType->primaryKey => $id));
			$this->request->data = $this->SeveriryType->find('first', $options);
		}
		$systemTables = $this->SeveriryType->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->SeveriryType->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->SeveriryType->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->SeveriryType->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->SeveriryType->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->SeveriryType->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->SeveriryType->find('count');
		$published = $this->SeveriryType->find('count',array('conditions'=>array('SeveriryType.publish'=>1)));
		$unpublished = $this->SeveriryType->find('count',array('conditions'=>array('SeveriryType.publish'=>0)));
		
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
		if (!$this->SeveriryType->exists($id)) {
			throw new NotFoundException(__('Invalid severiry type'));
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
			if ($this->SeveriryType->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->SeveriryType->save($this->request->data)) {
                $this->Session->setFlash(__('The severiry type has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The severiry type could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The severiry type could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('SeveriryType.' . $this->SeveriryType->primaryKey => $id));
			$this->request->data = $this->SeveriryType->find('first', $options);
		}
		$systemTables = $this->SeveriryType->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->SeveriryType->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->SeveriryType->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->SeveriryType->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->SeveriryType->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->SeveriryType->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->SeveriryType->find('count');
		$published = $this->SeveriryType->find('count',array('conditions'=>array('SeveriryType.publish'=>1)));
		$unpublished = $this->SeveriryType->find('count',array('conditions'=>array('SeveriryType.publish'=>0)));
		
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
		$this->SeveriryType->id = $id;
		if (!$this->SeveriryType->exists()) {
			throw new NotFoundException(__('Invalid severiry type'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->SeveriryType->delete()) {
			$this->Session->setFlash(__('Severiry type deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Severiry type was not deleted'));
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
		
		$result = explode('+',$this->request->data['severiryTypes']['rec_selected']);
		$this->SeveriryType->recursive = 1;
		$severiryTypes = $this->SeveriryType->find('all',array('SeveriryType.publish'=>1,'SeveriryType.soft_delete'=>1,'conditions'=>array('or'=>array('SeveriryType.id'=>$result))));
		$this->set('severiryTypes', $severiryTypes);
		
				$systemTables = $this->SeveriryType->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->SeveriryType->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->SeveriryType->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->SeveriryType->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->SeveriryType->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->SeveriryType->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

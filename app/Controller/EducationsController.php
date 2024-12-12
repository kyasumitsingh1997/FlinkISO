<?php
App::uses('AppController', 'Controller');
/**
 * Educations Controller
 *
 * @property Education $Education
 */
class EducationsController extends AppController {

public function _get_system_table_id($controller = NULL) {

        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $controller)));
        return $systemTableId['SystemTable']['id'];
    }

/**
 * request handling by - Mayuresh Vaidya - TECHMENTIS GLOBAL SERVICES PVT LTD
 *
 */
/**
 * _check_request method
 *
 * @return void
 */
	
public function _check_request(){

        $onlyBranch = null;
	$onlyOwn = null;
	$con1 = null;
	$con2 = null;

	if($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)$onlyBranch = array('Education.branch_id'=>$this->Session->read('User.branch_id'));
	if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Education.created_by'=>$this->Session->read('User.id'));
	
        if($this->request->params['named']){
        if($this->request->params['named']['published']==null)$con1 = null ; else $con1 = array('Education.publish'=>$this->request->params['named']['published']);
	if($this->request->params['named']['soft_delete']==null)$con2 = null ; else $con2 = array('Education.soft_delete'=>$this->request->params['named']['soft_delete']);
	if($this->request->params['named']['soft_delete']==null)$conditions=array($onlyBranch,$onlyOwn,$con1,'Education.soft_delete'=>0);
        else $conditions=array($onlyBranch,$onlyOwn,$con1,$con2);
	}else{
        $conditions=array($onlyBranch,$onlyOwn,null,'Education.soft_delete'=>0);
        }
        
        return $conditions;
}

/**
 * request handling by - Mayuresh Vaidya - TECHMENTIS GLOBAL SERVICES PVT LTD
 * returns array of records created by user for branch , published / unpublished records & soft_deleted records
 */
/**
 * _get_count method
 *
 * @return void
 */
	
public function _get_count(){
        
        $onlyBranch = null;
	$onlyOwn = null;
	$condition = null;
        
	if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Education.branch_id'=>$this->Session->read('User.branch_id'));
	if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Education.created_by'=>$this->Session->read('User.id'));
	$conditions = array($onlyBranch,$onlyOwn);
	
	$count = $this->Education->find('count',array('conditions'=>$condition));
	$published = $this->Education->find('count',array('conditions'=>array($condition,'Education.publish'=>1,'Education.soft_delete'=>0)));
	$unpublished = $this->Education->find('count',array('conditions'=>array($condition,'Education.publish'=>0,'Education.soft_delete'=>0)));
	$deleted = $this->Education->find('count',array('conditions'=>array($condition,'Education.soft_delete'=>1)));
	$this->set(compact('count','published','unpublished','deleted'));
}


/**
 * index method
 *
 * @return void
 */
	public function index() {
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('Education.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->Education->recursive = 0;
		$this->set('educations', $this->paginate());
		
		$this->_get_count();
	}


 
/**
 * box layout by - Mayuresh Vaidya - TECHMENTIS GLOBAL SERVICES PVT LTD
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('Education.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->Education->recursive = 0;
		$this->set('educations', $this->paginate());
		
		$this->_get_count();
	}

/**
 * search method
 * Dynamic by Mayuresh Vaidya - TECHMENTIS GLOBAL SERVICES PVT LTD
 * @return void
 */
	public function search() {
		if ($this->request->is('post')) {
	
	$search_array = array();
		$search_keys = explode(" ",$this->request->data['Education']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['Education']['search_field'] as $search):
				$search_array[] = array('Education.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('Education.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->Education->recursive = 0;
		$this->paginate = array('order'=>array('Education.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Education.soft_delete'=>0 , $cons));
		$this->set('educations', $this->paginate());
		}
	}

/**
 * adcanced_search method
 * Advanced search by Mayuresh Vaidya - TECHMENTIS GLOBAL SERVICES PVT LTD
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('Education.'.$search => $search_key);
					else $search_array[] = array('Education.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('Education.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('Education.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'Education.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Education.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Education.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->Education->recursive = 0;
		$this->paginate = array('order'=>array('Education.sr_no'=>'DESC'),'conditions'=>$conditions , 'Education.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('educations', $this->paginate());
		}
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Education->exists($id)) {
			throw new NotFoundException(__('Invalid education'));
		}
		$options = array('conditions' => array('Education.' . $this->Education->primaryKey => $id));
		$this->set('education', $this->Education->find('first', $options));
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
	
		if($this->_show_approvals()){
			$this->loadModel('User');
			$this->User->recursive = 0;
			$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
			$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
		}
		
		if ($this->request->is('post')) {
                        $this->request->data['Education']['system_table_id'] = $this->_get_system_table_id();
			$this->Education->create();
			if ($this->Education->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Education';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Education->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The education has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Education->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The education could not be saved. Please, try again.'));
			}
		}
		$systemTables = $this->Education->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Education->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->Education->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Education->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('systemTables', 'masterListOfFormats', 'createdBies', 'modifiedBies'));
	$count = $this->Education->find('count');
	$published = $this->Education->find('count',array('conditions'=>array('Education.publish'=>1)));
	$unpublished = $this->Education->find('count',array('conditions'=>array('Education.publish'=>0)));
		
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
                        $this->request->data['Education']['system_table_id'] = $this->_get_system_table_id();
			$this->Education->create();
			if ($this->Education->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Education';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Education->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The education has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Education->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The education could not be saved. Please, try again.'));
			}
		}
		$systemTables = $this->Education->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Education->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->Education->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Education->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('systemTables', 'masterListOfFormats', 'createdBies', 'modifiedBies'));
	$count = $this->Education->find('count');
	$published = $this->Education->find('count',array('conditions'=>array('Education.publish'=>1)));
	$unpublished = $this->Education->find('count',array('conditions'=>array('Education.publish'=>0)));
		
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
		if (!$this->Education->exists($id)) {
			throw new NotFoundException(__('Invalid education'));
		}
		if($this->_show_approvals()){
			$this->loadModel('User');
			$this->User->recursive = 0;
			$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
			$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
                        $this->request->data['Education']['system_table_id'] = $this->_get_system_table_id();
			if ($this->Education->save($this->request->data)) {
				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Education';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Education->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The education has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The education could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Education.' . $this->Education->primaryKey => $id));
			$this->request->data = $this->Education->find('first', $options);
		}
		$systemTables = $this->Education->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Education->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->Education->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Education->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'createdBies', 'modifiedBies'));
		$count = $this->Education->find('count');
		$published = $this->Education->find('count',array('conditions'=>array('Education.publish'=>1)));
		$unpublished = $this->Education->find('count',array('conditions'=>array('Education.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approval_id = null) {
		if (!$this->Education->exists($id)) {
			throw new NotFoundException(__('Invalid education'));
		}
		
		$this->loadModel('Approval');
		if (!$this->Approval->exists($approval_id)) {
			throw new NotFoundException(__('Invalid approval id'));
		}
		
		$approval = $this->Approval->read(null,$approval_id);
		$this->set('same',$approval['Approval']['user_id']);
		
		//$approval_history = $this->Approval->find('all',array('order'=>array('Approval.sr_no'=>'DESC'),'conditions'=>array('Approval.model_name'=>'Education','Approval.record'=>$id)));
		//$this->set(compact('approval_history'));
		
		if($this->_show_approvals()){
			$this->loadModel('User');
			$this->User->recursive = 0;
			$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0)));
			$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Education->save($this->request->data)) {
				if($this->request->data['Education']['publish'] == 0 && $this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Education';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Education->id;
					$this->Approval->save($this->request->data['Approval']);
					
					$this->Session->setFlash(__('The education has been saved'));
					if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$id));
					else $this->redirect(array('action' => 'index'));
				}else{
					$this->Approval->read(null, $approval_id);
					$data['Approval']['status'] = 'Approved';
					$data['Approval']['modified_by'] = $this->Session->read('User.id');
					$this->Approval->save($data);
					$this->Session->setFlash(__('The branch has been published'));
					if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$id));
					else $this->redirect(array('action' => 'index'));
				}
				
			} else {
				$this->Session->setFlash(__('The education could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Education.' . $this->Education->primaryKey => $id));
			$this->request->data = $this->Education->find('first', $options);
		}
		$systemTables = $this->Education->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Education->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->Education->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Education->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'createdBies', 'modifiedBies'));
		$count = $this->Education->find('count');
		$published = $this->Education->find('count',array('conditions'=>array('Education.publish'=>1)));
		$unpublished = $this->Education->find('count',array('conditions'=>array('Education.publish'=>0)));
		
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
		$this->Education->id = $id;
		if (!$this->Education->exists()) {
			throw new NotFoundException(__('Invalid education'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Education->delete()) {
			$this->Session->setFlash(__('Education deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Education was not deleted'));
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
		
		$result = explode('+',$this->request->data['educations']['rec_selected']);
		$this->Education->recursive = 1;
		$educations = $this->Education->find('all',array('Education.publish'=>1,'Education.soft_delete'=>1,'conditions'=>array('or'=>array('Education.id'=>$result))));
		$this->set('educations', $educations);
		
				$systemTables = $this->Education->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Education->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$createdBies = $this->Education->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Education->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'createdBies', 'modifiedBies', 'systemTables', 'masterListOfFormats', 'createdBies', 'modifiedBies'));
}
}

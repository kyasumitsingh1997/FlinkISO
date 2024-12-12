<?php
App::uses('AppController', 'Controller');
/**
 * AuditTypeMasters Controller
 *
 * @property AuditTypeMaster $AuditTypeMaster
 * @property PaginatorComponent $Paginator
 */
class AuditTypeMastersController extends AppController {

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
		$this->paginate = array('order'=>array('AuditTypeMaster.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->AuditTypeMaster->recursive = 0;
		$this->set('auditTypeMasters', $this->paginate());
		
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
		$this->paginate = array('order'=>array('AuditTypeMaster.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->AuditTypeMaster->recursive = 0;
		$this->set('auditTypeMasters', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['AuditTypeMaster']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['AuditTypeMaster']['search_field'] as $search):
				$search_array[] = array('AuditTypeMaster.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('AuditTypeMaster.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->AuditTypeMaster->recursive = 0;
		$this->paginate = array('order'=>array('AuditTypeMaster.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'AuditTypeMaster.soft_delete'=>0 , $cons));
		$this->set('auditTypeMasters', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('AuditTypeMaster.'.$search => $search_key);
					else $search_array[] = array('AuditTypeMaster.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('AuditTypeMaster.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('AuditTypeMaster.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'AuditTypeMaster.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('AuditTypeMaster.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('AuditTypeMaster.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->AuditTypeMaster->recursive = 0;
		$this->paginate = array('order'=>array('AuditTypeMaster.sr_no'=>'DESC'),'conditions'=>$conditions , 'AuditTypeMaster.soft_delete'=>0 );
		$this->set('auditTypeMasters', $this->paginate());
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
		if (!$this->AuditTypeMaster->exists($id)) {
			throw new NotFoundException(__('Invalid audit type master'));
		}
		$options = array('conditions' => array('AuditTypeMaster.' . $this->AuditTypeMaster->primaryKey => $id));
		$this->set('auditTypeMaster', $this->AuditTypeMaster->find('first', $options));
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
			$this->request->data['AuditTypeMaster']['system_table_id'] = $this->_get_system_table_id();
			$this->AuditTypeMaster->create();
			if ($this->AuditTypeMaster->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The audit type master has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->AuditTypeMaster->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The audit type master could not be saved. Please, try again.'));
			}
		}
		// $statusUsers = $this->AuditTypeMaster->StatusUser->find('list',array('conditions'=>array('StatusUser.publish'=>1,'StatusUser.soft_delete'=>0)));
		$systemTables = $this->AuditTypeMaster->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AuditTypeMaster->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->AuditTypeMaster->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->AuditTypeMaster->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->AuditTypeMaster->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AuditTypeMaster->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('statusUsers', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies'));
	$count = $this->AuditTypeMaster->find('count');
	$published = $this->AuditTypeMaster->find('count',array('conditions'=>array('AuditTypeMaster.publish'=>1)));
	$unpublished = $this->AuditTypeMaster->find('count',array('conditions'=>array('AuditTypeMaster.publish'=>0)));
		
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
                        $this->request->data['AuditTypeMaster']['system_table_id'] = $this->_get_system_table_id();
			$this->AuditTypeMaster->create();
			if ($this->AuditTypeMaster->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='AuditTypeMaster';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->AuditTypeMaster->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The audit type master has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->AuditTypeMaster->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The audit type master could not be saved. Please, try again.'));
			}
		}
		// $statusUsers = $this->AuditTypeMaster->StatusUser->find('list',array('conditions'=>array('StatusUser.publish'=>1,'StatusUser.soft_delete'=>0)));
		$systemTables = $this->AuditTypeMaster->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AuditTypeMaster->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->AuditTypeMaster->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->AuditTypeMaster->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->AuditTypeMaster->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AuditTypeMaster->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('statusUsers', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies'));
	$count = $this->AuditTypeMaster->find('count');
	$published = $this->AuditTypeMaster->find('count',array('conditions'=>array('AuditTypeMaster.publish'=>1)));
	$unpublished = $this->AuditTypeMaster->find('count',array('conditions'=>array('AuditTypeMaster.publish'=>0)));
		
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
		if (!$this->AuditTypeMaster->exists($id)) {
			throw new NotFoundException(__('Invalid audit type master'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['AuditTypeMaster']['system_table_id'] = $this->_get_system_table_id();
			if ($this->AuditTypeMaster->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The audit type master could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AuditTypeMaster.' . $this->AuditTypeMaster->primaryKey => $id));
			$this->request->data = $this->AuditTypeMaster->find('first', $options);
		}
		// $statusUsers = $this->AuditTypeMaster->StatusUser->find('list',array('conditions'=>array('StatusUser.publish'=>1,'StatusUser.soft_delete'=>0)));
		$systemTables = $this->AuditTypeMaster->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AuditTypeMaster->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->AuditTypeMaster->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->AuditTypeMaster->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->AuditTypeMaster->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AuditTypeMaster->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('statusUsers', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies'));
		$count = $this->AuditTypeMaster->find('count');
		$published = $this->AuditTypeMaster->find('count',array('conditions'=>array('AuditTypeMaster.publish'=>1)));
		$unpublished = $this->AuditTypeMaster->find('count',array('conditions'=>array('AuditTypeMaster.publish'=>0)));
		
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
		if (!$this->AuditTypeMaster->exists($id)) {
			throw new NotFoundException(__('Invalid audit type master'));
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
			if ($this->AuditTypeMaster->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->AuditTypeMaster->save($this->request->data)) {
                $this->Session->setFlash(__('The audit type master has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The audit type master could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The audit type master could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('AuditTypeMaster.' . $this->AuditTypeMaster->primaryKey => $id));
			$this->request->data = $this->AuditTypeMaster->find('first', $options);
		}
		$statusUsers = $this->AuditTypeMaster->StatusUser->find('list',array('conditions'=>array('StatusUser.publish'=>1,'StatusUser.soft_delete'=>0)));
		$systemTables = $this->AuditTypeMaster->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AuditTypeMaster->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->AuditTypeMaster->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->AuditTypeMaster->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->AuditTypeMaster->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AuditTypeMaster->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('statusUsers', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies'));
		$count = $this->AuditTypeMaster->find('count');
		$published = $this->AuditTypeMaster->find('count',array('conditions'=>array('AuditTypeMaster.publish'=>1)));
		$unpublished = $this->AuditTypeMaster->find('count',array('conditions'=>array('AuditTypeMaster.publish'=>0)));
		
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
		$this->AuditTypeMaster->id = $id;
		if (!$this->AuditTypeMaster->exists()) {
			throw new NotFoundException(__('Invalid audit type master'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->AuditTypeMaster->delete()) {
			$this->Session->setFlash(__('Audit type master deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Audit type master was not deleted'));
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
		
		$result = explode('+',$this->request->data['auditTypeMasters']['rec_selected']);
		$this->AuditTypeMaster->recursive = 1;
		$auditTypeMasters = $this->AuditTypeMaster->find('all',array('AuditTypeMaster.publish'=>1,'AuditTypeMaster.soft_delete'=>1,'conditions'=>array('or'=>array('AuditTypeMaster.id'=>$result))));
		$this->set('auditTypeMasters', $auditTypeMasters);
		
				$statusUsers = $this->AuditTypeMaster->StatusUser->find('list',array('conditions'=>array('StatusUser.publish'=>1,'StatusUser.soft_delete'=>0)));
		$systemTables = $this->AuditTypeMaster->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->AuditTypeMaster->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->AuditTypeMaster->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->AuditTypeMaster->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->AuditTypeMaster->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->AuditTypeMaster->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('statusUsers', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies', 'statusUsers', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies'));
}
}

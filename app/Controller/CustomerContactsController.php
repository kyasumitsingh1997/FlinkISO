<?php
App::uses('AppController', 'Controller');
/**
 * CustomerContacts Controller
 *
 * @property CustomerContact $CustomerContact
 */
class CustomerContactsController extends AppController {

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
		$this->paginate = array('order'=>array('CustomerContact.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->CustomerContact->recursive = 0;
		$this->set('customerContacts', $this->paginate());
		
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
		$this->paginate = array('order'=>array('CustomerContact.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->CustomerContact->recursive = 0;
		$this->set('customerContacts', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['CustomerContact']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['CustomerContact']['search_field'] as $search):
				$search_array[] = array('CustomerContact.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('CustomerContact.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->CustomerContact->recursive = 0;
		$this->paginate = array('order'=>array('CustomerContact.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'CustomerContact.soft_delete'=>0 , $cons));
		$this->set('customerContacts', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('CustomerContact.'.$search => $search_key);
					else $search_array[] = array('CustomerContact.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('CustomerContact.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('CustomerContact.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'CustomerContact.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('CustomerContact.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('CustomerContact.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->CustomerContact->recursive = 0;
		$this->paginate = array('order'=>array('CustomerContact.sr_no'=>'DESC'),'conditions'=>$conditions , 'CustomerContact.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('customerContacts', $this->paginate());
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
		if (!$this->CustomerContact->exists($id)) {
			throw new NotFoundException(__('Invalid customer contact'));
		}
		$options = array('conditions' => array('CustomerContact.' . $this->CustomerContact->primaryKey => $id));
		$this->set('customerContact', $this->CustomerContact->find('first', $options));
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
			$this->request->data['CustomerContact']['system_table_id'] = $this->_get_system_table_id();
			$this->CustomerContact->create();
			if ($this->CustomerContact->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The customer contact has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('controller'=>'customers','action' => 'index',$this->CustomerContact->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The customer contact could not be saved. Please, try again.'));
			}
		}
		$customers = $this->CustomerContact->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$createdBies = $this->CustomerContact->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CustomerContact->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('customers', 'createdBies', 'modifiedBies'));
	$count = $this->CustomerContact->find('count');
	$published = $this->CustomerContact->find('count',array('conditions'=>array('CustomerContact.publish'=>1)));
	$unpublished = $this->CustomerContact->find('count',array('conditions'=>array('CustomerContact.publish'=>0)));
		
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
                        $this->request->data['CustomerContact']['system_table_id'] = $this->_get_system_table_id();
			$this->CustomerContact->create();
			if ($this->CustomerContact->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='CustomerContact';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->CustomerContact->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The customer contact has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->CustomerContact->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The customer contact could not be saved. Please, try again.'));
			}
		}
		$customers = $this->CustomerContact->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$createdBies = $this->CustomerContact->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CustomerContact->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('customers', 'createdBies', 'modifiedBies'));
	$count = $this->CustomerContact->find('count');
	$published = $this->CustomerContact->find('count',array('conditions'=>array('CustomerContact.publish'=>1)));
	$unpublished = $this->CustomerContact->find('count',array('conditions'=>array('CustomerContact.publish'=>0)));
		
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
		if (!$this->CustomerContact->exists($id)) {
			throw new NotFoundException(__('Invalid customer contact'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['CustomerContact']['system_table_id'] = $this->_get_system_table_id();
			if ($this->CustomerContact->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The customer contact could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CustomerContact.' . $this->CustomerContact->primaryKey => $id));
			$this->request->data = $this->CustomerContact->find('first', $options);
		}
		$customers = $this->CustomerContact->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$createdBies = $this->CustomerContact->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CustomerContact->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('customers', 'createdBies', 'modifiedBies'));
		$count = $this->CustomerContact->find('count');
		$published = $this->CustomerContact->find('count',array('conditions'=>array('CustomerContact.publish'=>1)));
		$unpublished = $this->CustomerContact->find('count',array('conditions'=>array('CustomerContact.publish'=>0)));
		
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
		if (!$this->CustomerContact->exists($id)) {
			throw new NotFoundException(__('Invalid customer contact'));
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
			if ($this->CustomerContact->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->CustomerContact->save($this->request->data)) {
                $this->Session->setFlash(__('The customer contact has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The customer contact could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The customer contact could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CustomerContact.' . $this->CustomerContact->primaryKey => $id));
			$this->request->data = $this->CustomerContact->find('first', $options);
		}
		$customers = $this->CustomerContact->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$createdBies = $this->CustomerContact->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CustomerContact->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('customers', 'createdBies', 'modifiedBies'));
		$count = $this->CustomerContact->find('count');
		$published = $this->CustomerContact->find('count',array('conditions'=>array('CustomerContact.publish'=>1)));
		$unpublished = $this->CustomerContact->find('count',array('conditions'=>array('CustomerContact.publish'=>0)));
		
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
		$this->CustomerContact->id = $id;
		if (!$this->CustomerContact->exists()) {
			throw new NotFoundException(__('Invalid customer contact'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CustomerContact->delete()) {
			$this->Session->setFlash(__('Customer contact deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Customer contact was not deleted'));
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
		
		$result = explode('+',$this->request->data['customerContacts']['rec_selected']);
		$this->CustomerContact->recursive = 1;
		$customerContacts = $this->CustomerContact->find('all',array('CustomerContact.publish'=>1,'CustomerContact.soft_delete'=>1,'conditions'=>array('or'=>array('CustomerContact.id'=>$result))));
		$this->set('customerContacts', $customerContacts);
		
				$customers = $this->CustomerContact->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$createdBies = $this->CustomerContact->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CustomerContact->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('customers', 'createdBies', 'modifiedBies', 'customers', 'createdBies', 'modifiedBies'));
}

public function add_new_contact($company_id = null){
	if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post')) {
		  $this->_add_customer_contact($customer_contact_data = $this->request->data,$customer_id=$company_id);		  
        }
	}
	
	public function _add_customer_contact($customer_contact_data = null,$customer_id=null){
             
		$this->loadModel('Customer');
		$customer_id = $customer_contact_data['CustomerContact']['customer_id'];
		$customer_contact_data['CustomerContact']['publish'] = $customer_contact_data['Customer']['publish'];
		$customer = $this->Customer->find('first',array(
			'fields'=>array('Customer.id','Customer.branchid','Customer.departmentid','Customer.master_list_of_format_id'),
			'conditions'=>array('Customer.id'=>$customer_id)));
		if($customer_contact_data){
			$customer_contact_data['CustomerContact']['branchid'] = $customer['Customer']['branchid'];
			$customer_contact_data['CustomerContact']['departmentid	'] = $customer['Customer']['departmentid'];
			$customer_contact_data['CustomerContact']['master_list_of_format_id'] = $customer['Customer']['master_list_of_format_id'];
			$this->CustomerContact->Create();
			
			if($this->CustomerContact->save($customer_contact_data,false)){
                $this->Session->setFlash(__('The customer contact has been saved'));
                $this->redirect(array('controller'=>'customers', 'action' => 'index'));   
            }else{
                $this->Session->setFlash(__('The customer contact could not be been saved'));
                $this->redirect(array('action' => 'add_new_contact',$company_id));   
            }
		}
	}
}

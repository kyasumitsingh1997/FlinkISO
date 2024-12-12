<?php
App::uses('AppController', 'Controller');
/**
 * CapaRevisedDates Controller
 *
 * @property CapaRevisedDate $CapaRevisedDate
 */
class CapaRevisedDatesController extends AppController {

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
		$this->paginate = array('order'=>array('CapaRevisedDate.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->CapaRevisedDate->recursive = 0;
		$this->set('capaRevisedDates', $this->paginate());
		
		$this->_get_count();
	}


public function capa_assigned() {
		
		
                $this->paginate = array('limit' => 2,
                'order' => array('CapaRevisedDate.sr_no' => 'DESC'),
                'fields' => array(

                    'CorrectivePreventiveAction.name',
                    'CorrectivePreventiveAction.id',
                    'CapaRevisedDate.*',
                    'Employee.id',
                    'Employee.name',
                   

                ),
                'conditions' => array('OR' => array(
                    'CapaRevisedDate.employee_id' => $this->Session->read('User.employee_id'),
                     
                ), 
                'CapaRevisedDate.soft_delete' => 0, 'CapaRevisedDate.publish' => 1),
            'recursive' => 0);

            $assignedCapas = $this->paginate();
            $this->set(array('capaRevisedDates' => $assignedCapas));
	} 
/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('CapaRevisedDate.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->CapaRevisedDate->recursive = 0;
		$this->set('capaRevisedDates', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['CapaRevisedDate']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['CapaRevisedDate']['search_field'] as $search):
				$search_array[] = array('CapaRevisedDate.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('CapaRevisedDate.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->CapaRevisedDate->recursive = 0;
		$this->paginate = array('order'=>array('CapaRevisedDate.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'CapaRevisedDate.soft_delete'=>0 , $cons));
		$this->set('capaRevisedDates', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('CapaRevisedDate.'.$search => $search_key);
					else $search_array[] = array('CapaRevisedDate.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('CapaRevisedDate.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('CapaRevisedDate.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'CapaRevisedDate.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('CapaRevisedDate.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('CapaRevisedDate.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->CapaRevisedDate->recursive = 0;
		$this->paginate = array('order'=>array('CapaRevisedDate.sr_no'=>'DESC'),'conditions'=>$conditions , 'CapaRevisedDate.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('capaRevisedDates', $this->paginate());
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
		if (!$this->CapaRevisedDate->exists($id)) {
			throw new NotFoundException(__('Invalid capa revised date'));
		}
		$options = array('conditions' => array('CapaRevisedDate.' . $this->CapaRevisedDate->primaryKey => $id));
		$this->set('capaRevisedDate', $this->CapaRevisedDate->find('first', $options));
	}



/**
 * list method
 *
 * @return void
 */
	public function lists($capaId = null) {
	
            $this->_get_count();		
            $this->set(compact('capaId'));
	}


/**
 * add_ajax method
 *
 * @return void
 */
	public function add_ajax($capaId = null, $modal = null) {
	
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post')) {
			$this->request->data['CapaRevisedDate']['system_table_id'] = $this->_get_system_table_id();
			$this->CapaRevisedDate->create();
			if ($this->CapaRevisedDate->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The capa revised date has been saved'));
				                     
                                  if ($this->_show_evidence() == true)
                                        $this->redirect(array('action' => 'view', $this->CapaRevisedDate->id));
                                    else
                                        $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The capa revised date could not be saved. Please, try again.'));
			}
		}
		
		$employees = $this->CapaRevisedDate->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRevisedDate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRevisedDate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
                $correctivePreventiveActionIds = $this->CapaRevisedDate->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$companies = $this->CapaRevisedDate->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRevisedDate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRevisedDate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRevisedDate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRevisedDate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('correctivePreventiveActionIds', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->CapaRevisedDate->find('count');
	$published = $this->CapaRevisedDate->find('count',array('conditions'=>array('CapaRevisedDate.publish'=>1)));
	$unpublished = $this->CapaRevisedDate->find('count',array('conditions'=>array('CapaRevisedDate.publish'=>0)));
		
	$this->set(compact('count','published','unpublished','capaId','modal'));

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
                        $this->request->data['CapaRevisedDate']['system_table_id'] = $this->_get_system_table_id();
			$this->CapaRevisedDate->create();
			if ($this->CapaRevisedDate->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='CapaRevisedDate';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->CapaRevisedDate->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The capa revised date has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->CapaRevisedDate->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The capa revised date could not be saved. Please, try again.'));
			}
		}
		$correctivePreventiveActions = $this->CapaRevisedDate->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$employees = $this->CapaRevisedDate->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRevisedDate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRevisedDate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->CapaRevisedDate->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRevisedDate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRevisedDate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRevisedDate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRevisedDate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->CapaRevisedDate->find('count');
	$published = $this->CapaRevisedDate->find('count',array('conditions'=>array('CapaRevisedDate.publish'=>1)));
	$unpublished = $this->CapaRevisedDate->find('count',array('conditions'=>array('CapaRevisedDate.publish'=>0)));
		
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
		if (!$this->CapaRevisedDate->exists($id)) {
			throw new NotFoundException(__('Invalid capa revised date'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['CapaRevisedDate']['system_table_id'] = $this->_get_system_table_id();
			if ($this->CapaRevisedDate->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The capa revised date could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CapaRevisedDate.' . $this->CapaRevisedDate->primaryKey => $id));
			$this->request->data = $this->CapaRevisedDate->find('first', $options);
		}
		$correctivePreventiveActions = $this->CapaRevisedDate->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$employees = $this->CapaRevisedDate->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRevisedDate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRevisedDate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->CapaRevisedDate->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRevisedDate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRevisedDate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRevisedDate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRevisedDate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->CapaRevisedDate->find('count');
		$published = $this->CapaRevisedDate->find('count',array('conditions'=>array('CapaRevisedDate.publish'=>1)));
		$unpublished = $this->CapaRevisedDate->find('count',array('conditions'=>array('CapaRevisedDate.publish'=>0)));
		
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
		if (!$this->CapaRevisedDate->exists($id)) {
			throw new NotFoundException(__('Invalid capa revised date'));
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
			if ($this->CapaRevisedDate->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->CapaRevisedDate->save($this->request->data)) {
                $this->Session->setFlash(__('The capa revised date has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The capa revised date could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The capa revised date could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('CapaRevisedDate.' . $this->CapaRevisedDate->primaryKey => $id));
			$this->request->data = $this->CapaRevisedDate->find('first', $options);
		}
		$correctivePreventiveActions = $this->CapaRevisedDate->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$employees = $this->CapaRevisedDate->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRevisedDate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRevisedDate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->CapaRevisedDate->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRevisedDate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRevisedDate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRevisedDate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRevisedDate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->CapaRevisedDate->find('count');
		$published = $this->CapaRevisedDate->find('count',array('conditions'=>array('CapaRevisedDate.publish'=>1)));
		$unpublished = $this->CapaRevisedDate->find('count',array('conditions'=>array('CapaRevisedDate.publish'=>0)));
		
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
		$this->CapaRevisedDate->id = $id;
		if (!$this->CapaRevisedDate->exists()) {
			throw new NotFoundException(__('Invalid capa revised date'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->CapaRevisedDate->delete()) {
			$this->Session->setFlash(__('Capa revised date deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Capa revised date was not deleted'));
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
		
		$result = explode('+',$this->request->data['capaRevisedDates']['rec_selected']);
		$this->CapaRevisedDate->recursive = 1;
		$capaRevisedDates = $this->CapaRevisedDate->find('all',array('CapaRevisedDate.publish'=>1,'CapaRevisedDate.soft_delete'=>1,'conditions'=>array('or'=>array('CapaRevisedDate.id'=>$result))));
		$this->set('capaRevisedDates', $capaRevisedDates);
		
				$correctivePreventiveActions = $this->CapaRevisedDate->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$employees = $this->CapaRevisedDate->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->CapaRevisedDate->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->CapaRevisedDate->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->CapaRevisedDate->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->CapaRevisedDate->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->CapaRevisedDate->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->CapaRevisedDate->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->CapaRevisedDate->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'correctivePreventiveActions', 'employees', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function pending_tasks() {
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('CapaRevisedDate.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->CapaRevisedDate->recursive = 0;
		$conditions = array('CapaRevisedDate.target_date <' => date('Y-m-d'));
		$this->paginate = array('order'=>array('CapaRevisedDate.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->_get_count();
	}	
}

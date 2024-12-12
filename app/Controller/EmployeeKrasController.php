<?php
App::uses('AppController', 'Controller');
/**
 * EmployeeKras Controller
 *
 * @property EmployeeKra $EmployeeKra
 * @property PaginatorComponent $Paginator
 */
class EmployeeKrasController extends AppController {

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
		$this->paginate = array('order'=>array('EmployeeKra.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->EmployeeKra->recursive = 0;
		$this->set('employeeKras', $this->paginate());
		
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
		$this->paginate = array('order'=>array('EmployeeKra.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->EmployeeKra->recursive = 0;
		$this->set('employeeKras', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['EmployeeKra']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['EmployeeKra']['search_field'] as $search):
				$search_array[] = array('EmployeeKra.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('EmployeeKra.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->EmployeeKra->recursive = 0;
		$this->paginate = array('order'=>array('EmployeeKra.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'EmployeeKra.soft_delete'=>0 , $cons));
		$this->set('employeeKras', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('EmployeeKra.'.$search => $search_key);
					else $search_array[] = array('EmployeeKra.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('EmployeeKra.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('EmployeeKra.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'EmployeeKra.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('EmployeeKra.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('EmployeeKra.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->EmployeeKra->recursive = 0;
		$this->paginate = array('order'=>array('EmployeeKra.sr_no'=>'DESC'),'conditions'=>$conditions , 'EmployeeKra.soft_delete'=>0 );
		$this->set('employeeKras', $this->paginate());
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
		if (!$this->EmployeeKra->exists($id)) {
			throw new NotFoundException(__('Invalid employee kra'));
		}
		$options = array('conditions' => array('EmployeeKra.' . $this->EmployeeKra->primaryKey => $id));
		$this->set('employeeKra', $this->EmployeeKra->find('first', $options));
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
			$this->request->data['EmployeeKra']['system_table_id'] = $this->_get_system_table_id();
			$this->EmployeeKra->create();
			if ($this->EmployeeKra->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The employee kra has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->EmployeeKra->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The employee kra could not be saved. Please, try again.'));
			}
		}
		$employees = $this->EmployeeKra->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		// $this->EmployeeKra->ProjectResource->virtualFields = array('name'=>'select `project_process_plans`.`process` from `project_process_plans` where `project_process_plans`.`id` = ProjectResource.process_id');
		
		// $processes = $this->EmployeeKra->ProjectResource->find('list',array('fields'=>array('ProjectResource.id','ProjectResource.name'), 'conditions'=>array('ProjectResource.soft_delete'=>0)));
		$systemTables = $this->EmployeeKra->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EmployeeKra->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->EmployeeKra->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->EmployeeKra->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->EmployeeKra->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EmployeeKra->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EmployeeKra->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EmployeeKra->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('employees', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','processes'));
	$count = $this->EmployeeKra->find('count');
	$published = $this->EmployeeKra->find('count',array('conditions'=>array('EmployeeKra.publish'=>1)));
	$unpublished = $this->EmployeeKra->find('count',array('conditions'=>array('EmployeeKra.publish'=>0)));
		
	$this->set(compact('count','published','unpublished'));

	}

	public function get_process($employee_id = null){
		$this->EmployeeKra->ProjectResource->virtualFields = array('name'=>'select `project_process_plans`.`process` from `project_process_plans` where `project_process_plans`.`id` = ProjectResource.process_id');
		
		$processes = $this->EmployeeKra->ProjectResource->find('list',
			array(
				'fields'=>array('ProjectResource.id','ProjectResource.name'), 
				'conditions'=>array('ProjectResource.soft_delete'=>0,'ProjectResource.employee_id'=>$this->request->params['named']['employee_id'])));
		$this->set(compact('processes'));
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
                        $this->request->data['EmployeeKra']['system_table_id'] = $this->_get_system_table_id();
			$this->EmployeeKra->create();
			if ($this->EmployeeKra->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='EmployeeKra';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->EmployeeKra->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The employee kra has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->EmployeeKra->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The employee kra could not be saved. Please, try again.'));
			}
		}
		$employees = $this->EmployeeKra->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->EmployeeKra->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EmployeeKra->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->EmployeeKra->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->EmployeeKra->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->EmployeeKra->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EmployeeKra->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EmployeeKra->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EmployeeKra->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('employees', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->EmployeeKra->find('count');
	$published = $this->EmployeeKra->find('count',array('conditions'=>array('EmployeeKra.publish'=>1)));
	$unpublished = $this->EmployeeKra->find('count',array('conditions'=>array('EmployeeKra.publish'=>0)));
		
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
		if (!$this->EmployeeKra->exists($id)) {
			throw new NotFoundException(__('Invalid employee kra'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['EmployeeKra']['system_table_id'] = $this->_get_system_table_id();
			if ($this->EmployeeKra->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The employee kra could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EmployeeKra.' . $this->EmployeeKra->primaryKey => $id));
			$this->request->data = $this->EmployeeKra->find('first', $options);
		}
		$employees = $this->EmployeeKra->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->EmployeeKra->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EmployeeKra->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->EmployeeKra->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->EmployeeKra->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->EmployeeKra->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EmployeeKra->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EmployeeKra->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EmployeeKra->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('employees', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->EmployeeKra->find('count');
		$published = $this->EmployeeKra->find('count',array('conditions'=>array('EmployeeKra.publish'=>1)));
		$unpublished = $this->EmployeeKra->find('count',array('conditions'=>array('EmployeeKra.publish'=>0)));
		
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
		if (!$this->EmployeeKra->exists($id)) {
			throw new NotFoundException(__('Invalid employee kra'));
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
			if ($this->EmployeeKra->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->EmployeeKra->save($this->request->data)) {
                $this->Session->setFlash(__('The employee kra has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The employee kra could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The employee kra could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EmployeeKra.' . $this->EmployeeKra->primaryKey => $id));
			$this->request->data = $this->EmployeeKra->find('first', $options);
		}
		$employees = $this->EmployeeKra->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->EmployeeKra->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EmployeeKra->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->EmployeeKra->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->EmployeeKra->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->EmployeeKra->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EmployeeKra->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EmployeeKra->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EmployeeKra->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('employees', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->EmployeeKra->find('count');
		$published = $this->EmployeeKra->find('count',array('conditions'=>array('EmployeeKra.publish'=>1)));
		$unpublished = $this->EmployeeKra->find('count',array('conditions'=>array('EmployeeKra.publish'=>0)));
		
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
		$this->EmployeeKra->id = $id;
		if (!$this->EmployeeKra->exists()) {
			throw new NotFoundException(__('Invalid employee kra'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->EmployeeKra->delete()) {
			$this->Session->setFlash(__('Employee kra deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Employee kra was not deleted'));
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
		
		$result = explode('+',$this->request->data['employeeKras']['rec_selected']);
		$this->EmployeeKra->recursive = 1;
		$employeeKras = $this->EmployeeKra->find('all',array('EmployeeKra.publish'=>1,'EmployeeKra.soft_delete'=>1,'conditions'=>array('or'=>array('EmployeeKra.id'=>$result))));
		$this->set('employeeKras', $employeeKras);
		
				$employees = $this->EmployeeKra->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->EmployeeKra->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EmployeeKra->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->EmployeeKra->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->EmployeeKra->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->EmployeeKra->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EmployeeKra->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EmployeeKra->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EmployeeKra->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('employees', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'employees', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}


	public function kras(){
		$this->EmployeeKra->Employee->virtualFields = array(
			'res' => 'select count(*) from `project_resources` where `project_resources`.`employee_id` = Employee.id'
		);

		$employees = $this->EmployeeKra->Employee->find('list',array('conditions'=>array(
			'Employee.res >'=>0			
		)));

		$this->set('employees',$employees);
		Configure::Write('debug',1);
		// debug($employees);
		// exit;
		$this->loadModel('ProjectResource');
		foreach ($employees as $key => $value) {
			$employeeKras[$key] = $this->ProjectResource->find('all',array(
				'fields'=>array(
					'ProjectResource.id','ProjectResource.employee_id','ProjectResource.priority','ProjectResource.process_id',
					'Employee.id','Employee.name','Employee.soft_skills','Employee.technical_skills','Employee.vertical_domain',
					'ProjectProcessPlan.id','ProjectProcessPlan.process'


				),
				'conditions'=>array('ProjectResource.employee_id'=>$key),
				// 'group'=>array('Employee.id')
			));
		}
		$this->set('employeeKras',$employeeKras);
		// debug($employeeKras);
		// exit;
	}
}

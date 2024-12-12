<?php
App::uses('AppController', 'Controller');
/**
 * FmeaActions Controller
 *
 * @property FmeaAction $FmeaAction
 * @property PaginatorComponent $Paginator
 */
class FmeaActionsController extends AppController {

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
		$this->paginate = array('order'=>array('FmeaAction.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->FmeaAction->recursive = 0;
		$this->set('fmeaActions', $this->paginate());
		
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
		$this->paginate = array('order'=>array('FmeaAction.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->FmeaAction->recursive = 0;
		$this->set('fmeaActions', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['FmeaAction']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['FmeaAction']['search_field'] as $search):
				$search_array[] = array('FmeaAction.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('FmeaAction.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->FmeaAction->recursive = 0;
		$this->paginate = array('order'=>array('FmeaAction.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'FmeaAction.soft_delete'=>0 , $cons));
		$this->set('fmeaActions', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('FmeaAction.'.$search => $search_key);
					else $search_array[] = array('FmeaAction.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('FmeaAction.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('FmeaAction.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'FmeaAction.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('FmeaAction.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('FmeaAction.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->FmeaAction->recursive = 0;
		$this->paginate = array('order'=>array('FmeaAction.sr_no'=>'DESC'),'conditions'=>$conditions , 'FmeaAction.soft_delete'=>0 );
		$this->set('fmeaActions', $this->paginate());
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
		if (!$this->FmeaAction->exists($id)) {
			throw new NotFoundException(__('Invalid fmea action'));
		}
		$options = array('conditions' => array('FmeaAction.' . $this->FmeaAction->primaryKey => $id));
		$this->set('fmeaAction', $this->FmeaAction->find('first', $options));
	}



/**
 * list method
 *
 * @return void
 */
	public function lists() {
	
        $this->_get_count();	

        if (!$this->request->is('post')) {	
	        if(!$this->request->params['named']['fmea_id']){
				$this->Session->setFlash(__('Please select FMEA first'));
				$this->redirect(array('controller'=>'fmeas','action' => 'index'));
			}elseif($this->request->params['named']['fmea_id']){

			}
		}

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
			$this->request->data['FmeaAction']['system_table_id'] = $this->_get_system_table_id();
			$this->FmeaAction->create();
			if ($this->FmeaAction->save($this->request->data)) {

				// $this->_send_email($this->request->data['FmeaAction']['employee_id']);
				$fmea = $this->FmeaAction->Fmea->find('first',array('conditions'=>array('Fmea.id'=>$this->request->data['FmeaAction']['fmea_id'])));
				$this->_update_fmea($this->request->data,$fmea);

				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The fmea action has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->FmeaAction->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fmea action could not be saved. Please, try again.'));
			}
		}

		if(!$this->request->params['named']['fmea_id']){
			$this->Session->setFlash(__('Please select FMEA first'));
			$this->redirect(array('controller'=>'fmeas','action' => 'index'));
		}elseif($this->request->params['named']['fmea_id']){
			$fmea = $this->FmeaAction->Fmea->find('first',array('conditions'=>array('Fmea.id'=>$this->request->params['named']['fmea_id'])));
			$this->set('fmea',$fmea);
		}

		$fmeas = $this->FmeaAction->Fmea->find('list',array('conditions'=>array('Fmea.publish'=>1,'Fmea.soft_delete'=>0)));
		$employees = $this->FmeaAction->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->FmeaAction->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->FmeaAction->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->FmeaAction->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->FmeaAction->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FmeaAction->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->FmeaAction->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->FmeaAction->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FmeaAction->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FmeaAction->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FmeaAction->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$currentStatuses = $this->FmeaAction->customArray['current_status'];

		$this->set(compact( 'currentStatuses', 'fmeas', 'employees', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->FmeaAction->find('count');
		$published = $this->FmeaAction->find('count',array('conditions'=>array('FmeaAction.publish'=>1)));
		$unpublished = $this->FmeaAction->find('count',array('conditions'=>array('FmeaAction.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		$fmea = $this->FmeaAction->Fmea->find('first',array('conditions'=>array('Fmea.id'=>$this->request->params['named']['fmea_id'])));
		$this->set('fmea',$fmea);

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
                        $this->request->data['FmeaAction']['system_table_id'] = $this->_get_system_table_id();
			$this->FmeaAction->create();
			if ($this->FmeaAction->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='FmeaAction';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->FmeaAction->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The fmea action has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->FmeaAction->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fmea action could not be saved. Please, try again.'));
			}
		}
		$fmeas = $this->FmeaAction->Fmea->find('list',array('conditions'=>array('Fmea.publish'=>1,'Fmea.soft_delete'=>0)));
		$employees = $this->FmeaAction->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->FmeaAction->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->FmeaAction->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->FmeaAction->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->FmeaAction->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FmeaAction->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->FmeaAction->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->FmeaAction->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FmeaAction->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FmeaAction->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FmeaAction->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('fmeas', 'employees', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->FmeaAction->find('count');
	$published = $this->FmeaAction->find('count',array('conditions'=>array('FmeaAction.publish'=>1)));
	$unpublished = $this->FmeaAction->find('count',array('conditions'=>array('FmeaAction.publish'=>0)));
		
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
		if (!$this->FmeaAction->exists($id)) {
			throw new NotFoundException(__('Invalid fmea action'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['FmeaAction']['system_table_id'] = $this->_get_system_table_id();
			if ($this->FmeaAction->save($this->request->data)) {
				
				$fmea = $this->FmeaAction->Fmea->find('first',array('conditions'=>array('Fmea.id'=>$this->request->data['FmeaAction']['fmea_id'])));
				$this->_update_fmea($this->request->data,$fmea);
				
				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fmea action could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FmeaAction.' . $this->FmeaAction->primaryKey => $id));
			$this->request->data = $this->FmeaAction->find('first', $options);
		}
		
		$fmeas = $this->FmeaAction->Fmea->find('list',array('conditions'=>array('Fmea.publish'=>1,'Fmea.soft_delete'=>0)));
		$employees = $this->FmeaAction->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->FmeaAction->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->FmeaAction->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->FmeaAction->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->FmeaAction->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FmeaAction->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->FmeaAction->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->FmeaAction->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FmeaAction->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FmeaAction->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FmeaAction->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$currentStatuses = $this->FmeaAction->customArray['current_status'];

		$this->set(compact( 'currentStatuses', 'fmeas', 'employees', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->FmeaAction->find('count');
		$published = $this->FmeaAction->find('count',array('conditions'=>array('FmeaAction.publish'=>1)));
		$unpublished = $this->FmeaAction->find('count',array('conditions'=>array('FmeaAction.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		$fmea = $this->FmeaAction->Fmea->find('first',array('conditions'=>array('Fmea.id'=>$this->request->params['named']['fmea_id'])));
		$this->set('fmea',$fmea);
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->FmeaAction->exists($id)) {
			throw new NotFoundException(__('Invalid fmea action'));
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
			if ($this->FmeaAction->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->FmeaAction->save($this->request->data)) {
            	
            	$fmea = $this->FmeaAction->Fmea->find('first',array('conditions'=>array('Fmea.id'=>$this->request->data['FmeaAction']['fmea_id'])));
				$this->_update_fmea($this->request->data,$fmea);

                $this->Session->setFlash(__('The fmea action has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The fmea action could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The fmea action could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('FmeaAction.' . $this->FmeaAction->primaryKey => $id));
			$this->request->data = $this->FmeaAction->find('first', $options);
		}
		
		$fmeas = $this->FmeaAction->Fmea->find('list',array('conditions'=>array('Fmea.publish'=>1,'Fmea.soft_delete'=>0)));
		$employees = $this->FmeaAction->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->FmeaAction->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->FmeaAction->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->FmeaAction->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->FmeaAction->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FmeaAction->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->FmeaAction->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->FmeaAction->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FmeaAction->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FmeaAction->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FmeaAction->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$currentStatuses = $this->FmeaAction->customArray['current_status'];

		$this->set(compact( 'currentStatuses', 'fmeas', 'employees', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->FmeaAction->find('count');
		$published = $this->FmeaAction->find('count',array('conditions'=>array('FmeaAction.publish'=>1)));
		$unpublished = $this->FmeaAction->find('count',array('conditions'=>array('FmeaAction.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		$fmea = $this->FmeaAction->Fmea->find('first',array('conditions'=>array('Fmea.id'=>$this->request->params['named']['fmea_id'])));
		$this->set('fmea',$fmea);
	}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function purge($id = null) {
		$this->FmeaAction->id = $id;
		if (!$this->FmeaAction->exists()) {
			throw new NotFoundException(__('Invalid fmea action'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->FmeaAction->delete()) {
			$this->Session->setFlash(__('Fmea action deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Fmea action was not deleted'));
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
		
		$result = explode('+',$this->request->data['fmeaActions']['rec_selected']);
		$this->FmeaAction->recursive = 1;
		$fmeaActions = $this->FmeaAction->find('all',array('FmeaAction.publish'=>1,'FmeaAction.soft_delete'=>1,'conditions'=>array('or'=>array('FmeaAction.id'=>$result))));
		$this->set('fmeaActions', $fmeaActions);
		
				$fmeas = $this->FmeaAction->Fmea->find('list',array('conditions'=>array('Fmea.publish'=>1,'Fmea.soft_delete'=>0)));
		$employees = $this->FmeaAction->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->FmeaAction->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->FmeaAction->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->FmeaAction->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->FmeaAction->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->FmeaAction->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->FmeaAction->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->FmeaAction->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->FmeaAction->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->FmeaAction->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->FmeaAction->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('fmeas', 'employees', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'fmeas', 'employees', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}


	public function _send_email($employee_id = null){
		$employee = $this->FmeaAction->Employee->find('first',array(
          'recursive'=>-1,
          'fields'=>array('Employee.id','Employee.name','Employee.personal_email','Employee.office_email'),
          'conditions'=>array('Employee.id'=>$employee_id)));
        
        $officeEmailId = $employee['Employee']['office_email'];
        $personalEmailId = $employee['Employee']['personal_email'];
        
        if ($officeEmailId != '') {
          $email = $officeEmailId;
        } else if ($personalEmailId != '') {
          $email = $personalEmailId;
        }
        
        if($email){
          $send_message = "Action related FMEA is assigned to you";
          $body = "<p>Admin has assigned FMEA Action to you. More more details, login to the application.</p>";
          try{

            $env = "";

            App::uses('CakeEmail', 'Network/Email');                        

            if($this->Session->read('User.is_smtp') == 1)
              $EmailConfig = new CakeEmail("smtp");

            if($this->Session->read('User.is_smtp') == 0)
              	$EmailConfig = new CakeEmail("default");
	            $EmailConfig->to($email);
	            $EmailConfig->subject($send_message);
	            $EmailConfig->template('fmeaaction');
	            $EmailConfig->viewVars(array(
	              'h2tag'=>$send_message,
	              'msg_content'=>$body,
	             ));
            $EmailConfig->emailFormat('html');
            $EmailConfig->send();
          } catch(Exception $e) {
                    
          }
          
        }else{
          
        }
	}

	public function actions_assigned(){
		$fmeaActions = $this->FmeaAction->find('all',array('conditions'=>array('FmeaAction.current_status'=>0,'FmeaAction.employee_id'=>$this->Session->read('User.employee_id')),'recursive'=>1,
            'fields'=>array('FmeaAction.id','FmeaAction.fmea_id','FmeaAction.target_date','FmeaAction.employee_id','Employee.id','Employee.name', 'FmeaAction.employee_id','Fmea.id','Fmea.name')));
        $this->set(compact('fmeaActions'));
	}

	public function _update_fmea($data = null,$fmea = null){
		$fmea['Fmea']['id'] = $data['FmeaAction']['fmea_id'];
		$fmea['Fmea']['new_fmea_severity_type_id'] = $data['FmeaAction']['fmea_severity_type_id'];
		$fmea['Fmea']['new_fmea_occurence_id'] = $data['FmeaAction']['fmea_occurence_id'];
		$fmea['Fmea']['new_fmea_detection_id'] = $data['FmeaAction']['fmea_detection_id'];
		$fmea['Fmea']['final_rpn'] = $data['FmeaAction']['rpn'];
		$fmea['Fmea']['current_status'] = $data['FmeaAction']['current_status'];
		$this->loadModel('Fmea');
		$this->Fmea->create();
		$this->Fmea->save($fmea['Fmea'],false);		
	}
}

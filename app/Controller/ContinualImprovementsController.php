<?php
App::uses('AppController', 'Controller');
/**
 * ContinualImprovements Controller
 *
 * @property ContinualImprovement $ContinualImprovement
 */
class ContinualImprovementsController extends AppController {

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
		$this->paginate = array('order'=>array('ContinualImprovement.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ContinualImprovement->recursive = 0;
		$this->set('continualImprovements', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ContinualImprovement.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ContinualImprovement->recursive = 0;
		$this->set('continualImprovements', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ContinualImprovement']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ContinualImprovement']['search_field'] as $search):
				$search_array[] = array('ContinualImprovement.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ContinualImprovement.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ContinualImprovement->recursive = 0;
		$this->paginate = array('order'=>array('ContinualImprovement.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ContinualImprovement.soft_delete'=>0 , $cons));
		$this->set('continualImprovements', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ContinualImprovement.'.$search => $search_key);
					else $search_array[] = array('ContinualImprovement.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ContinualImprovement.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ContinualImprovement.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ContinualImprovement.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ContinualImprovement.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ContinualImprovement.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ContinualImprovement->recursive = 0;
		$this->paginate = array('order'=>array('ContinualImprovement.sr_no'=>'DESC'),'conditions'=>$conditions , 'ContinualImprovement.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('continualImprovements', $this->paginate());
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
		if (!$this->ContinualImprovement->exists($id)) {
			throw new NotFoundException(__('Invalid continual improvement'));
		}
		$options = array('conditions' => array('ContinualImprovement.' . $this->ContinualImprovement->primaryKey => $id));
		$this->set('continualImprovement', $this->ContinualImprovement->find('first', $options));
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
			$this->request->data['ContinualImprovement']['system_table_id'] = $this->_get_system_table_id();
			$this->ContinualImprovement->create();
			if ($this->ContinualImprovement->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The continual improvement has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ContinualImprovement->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The continual improvement could not be saved. Please, try again.'));
			}
		}
		$correctivePreventiveActions = $this->ContinualImprovement->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$processes = $this->ContinualImprovement->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$internalAudits = $this->ContinualImprovement->InternalAudit->find('list',array('conditions'=>array('InternalAudit.publish'=>1,'InternalAudit.soft_delete'=>0)));
		$internalAuditDetails = $this->ContinualImprovement->InternalAuditDetail->find('list',array('conditions'=>array('InternalAuditDetail.publish'=>1,'InternalAuditDetail.soft_delete'=>0)));
		$systemTables = $this->ContinualImprovement->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ContinualImprovement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->ContinualImprovement->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->ContinualImprovement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ContinualImprovement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ContinualImprovement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ContinualImprovement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ContinualImprovement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('correctivePreventiveActions', 'processes', 'internalAudits', 'internalAuditDetails', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ContinualImprovement->find('count');
	$published = $this->ContinualImprovement->find('count',array('conditions'=>array('ContinualImprovement.publish'=>1)));
	$unpublished = $this->ContinualImprovement->find('count',array('conditions'=>array('ContinualImprovement.publish'=>0)));
		
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
                        $this->request->data['ContinualImprovement']['system_table_id'] = $this->_get_system_table_id();
			$this->ContinualImprovement->create();
			if ($this->ContinualImprovement->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='ContinualImprovement';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->ContinualImprovement->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The continual improvement has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ContinualImprovement->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The continual improvement could not be saved. Please, try again.'));
			}
		}
		$correctivePreventiveActions = $this->ContinualImprovement->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$processes = $this->ContinualImprovement->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$internalAudits = $this->ContinualImprovement->InternalAudit->find('list',array('conditions'=>array('InternalAudit.publish'=>1,'InternalAudit.soft_delete'=>0)));
		$internalAuditDetails = $this->ContinualImprovement->InternalAuditDetail->find('list',array('conditions'=>array('InternalAuditDetail.publish'=>1,'InternalAuditDetail.soft_delete'=>0)));
		$systemTables = $this->ContinualImprovement->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ContinualImprovement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->ContinualImprovement->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->ContinualImprovement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ContinualImprovement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ContinualImprovement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ContinualImprovement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ContinualImprovement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('correctivePreventiveActions', 'processes', 'internalAudits', 'internalAuditDetails', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ContinualImprovement->find('count');
	$published = $this->ContinualImprovement->find('count',array('conditions'=>array('ContinualImprovement.publish'=>1)));
	$unpublished = $this->ContinualImprovement->find('count',array('conditions'=>array('ContinualImprovement.publish'=>0)));
		
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
		if (!$this->ContinualImprovement->exists($id)) {
			throw new NotFoundException(__('Invalid continual improvement'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ContinualImprovement']['system_table_id'] = $this->_get_system_table_id();
			if ($this->ContinualImprovement->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The continual improvement could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ContinualImprovement.' . $this->ContinualImprovement->primaryKey => $id));
			$this->request->data = $this->ContinualImprovement->find('first', $options);
		}
		$correctivePreventiveActions = $this->ContinualImprovement->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$processes = $this->ContinualImprovement->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$internalAudits = $this->ContinualImprovement->InternalAudit->find('list',array('conditions'=>array('InternalAudit.publish'=>1,'InternalAudit.soft_delete'=>0)));
		$internalAuditDetails = $this->ContinualImprovement->InternalAuditDetail->find('list',array('conditions'=>array('InternalAuditDetail.publish'=>1,'InternalAuditDetail.soft_delete'=>0)));
		$systemTables = $this->ContinualImprovement->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ContinualImprovement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->ContinualImprovement->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->ContinualImprovement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ContinualImprovement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ContinualImprovement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ContinualImprovement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ContinualImprovement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('correctivePreventiveActions', 'processes', 'internalAudits', 'internalAuditDetails', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ContinualImprovement->find('count');
		$published = $this->ContinualImprovement->find('count',array('conditions'=>array('ContinualImprovement.publish'=>1)));
		$unpublished = $this->ContinualImprovement->find('count',array('conditions'=>array('ContinualImprovement.publish'=>0)));
		
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
		if (!$this->ContinualImprovement->exists($id)) {
			throw new NotFoundException(__('Invalid continual improvement'));
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
			if ($this->ContinualImprovement->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ContinualImprovement->save($this->request->data)) {
                $this->Session->setFlash(__('The continual improvement has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The continual improvement could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The continual improvement could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ContinualImprovement.' . $this->ContinualImprovement->primaryKey => $id));
			$this->request->data = $this->ContinualImprovement->find('first', $options);
		}
		$correctivePreventiveActions = $this->ContinualImprovement->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$processes = $this->ContinualImprovement->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$internalAudits = $this->ContinualImprovement->InternalAudit->find('list',array('conditions'=>array('InternalAudit.publish'=>1,'InternalAudit.soft_delete'=>0)));
		$internalAuditDetails = $this->ContinualImprovement->InternalAuditDetail->find('list',array('conditions'=>array('InternalAuditDetail.publish'=>1,'InternalAuditDetail.soft_delete'=>0)));
		$systemTables = $this->ContinualImprovement->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ContinualImprovement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->ContinualImprovement->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->ContinualImprovement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ContinualImprovement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ContinualImprovement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ContinualImprovement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ContinualImprovement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('correctivePreventiveActions', 'processes', 'internalAudits', 'internalAuditDetails', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ContinualImprovement->find('count');
		$published = $this->ContinualImprovement->find('count',array('conditions'=>array('ContinualImprovement.publish'=>1)));
		$unpublished = $this->ContinualImprovement->find('count',array('conditions'=>array('ContinualImprovement.publish'=>0)));
		
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
		$this->ContinualImprovement->id = $id;
		if (!$this->ContinualImprovement->exists()) {
			throw new NotFoundException(__('Invalid continual improvement'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ContinualImprovement->delete()) {
			$this->Session->setFlash(__('Continual improvement deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Continual improvement was not deleted'));
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
		
		$result = explode('+',$this->request->data['continualImprovements']['rec_selected']);
		$this->ContinualImprovement->recursive = 1;
		$continualImprovements = $this->ContinualImprovement->find('all',array('ContinualImprovement.publish'=>1,'ContinualImprovement.soft_delete'=>1,'conditions'=>array('or'=>array('ContinualImprovement.id'=>$result))));
		$this->set('continualImprovements', $continualImprovements);
		
				$correctivePreventiveActions = $this->ContinualImprovement->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$processes = $this->ContinualImprovement->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$internalAudits = $this->ContinualImprovement->InternalAudit->find('list',array('conditions'=>array('InternalAudit.publish'=>1,'InternalAudit.soft_delete'=>0)));
		$internalAuditDetails = $this->ContinualImprovement->InternalAuditDetail->find('list',array('conditions'=>array('InternalAuditDetail.publish'=>1,'InternalAuditDetail.soft_delete'=>0)));
		$systemTables = $this->ContinualImprovement->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ContinualImprovement->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->ContinualImprovement->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->ContinualImprovement->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ContinualImprovement->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ContinualImprovement->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ContinualImprovement->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ContinualImprovement->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('correctivePreventiveActions', 'processes', 'internalAudits', 'internalAuditDetails', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'correctivePreventiveActions', 'processes', 'internalAudits', 'internalAuditDetails', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

<?php
App::uses('AppController', 'Controller');
/**
 * CapaInvestigations Controller
 *
 * @property CapaInvestigation $CapaInvestigation
 */
class CapaInvestigationsController extends AppController {

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

	if($this->request->params['pass'][0]){
		$conditions = null;
		$capa_condition = array('CapaInvestigation.corrective_preventive_action_id'=>$this->request->params['pass'][0]);
	}else{
		$capa_condition = null;	
		$conditions = $this->_check_request();
	} 

	$this->paginate = array('order'=>array('CapaInvestigation.sr_no'=>'DESC'),'conditions'=>array($conditions,$capa_condition));
	
	$this->CapaInvestigation->recursive = 0;
	$this->set('capaInvestigations', $this->paginate());

	$this->_get_count();
}

public function capa_assigned() {
	$this->paginate = array('limit' => 10,
		'order' => array('CapaInvestigation.target_date' => 'DESC'),
		'fields' => array(

			'CorrectivePreventiveAction.name',
			'CapaInvestigation.details',
			'CapaInvestigation.target_date',
			'CapaInvestigation.id',

			),
		'conditions' => array('OR' => array(
			'CapaInvestigation.employee_id' => $this->Session->read('User.employee_id'),

			), 'CapaInvestigation.current_status' => 0,
		'CapaInvestigation.soft_delete' => 0, 'CapaInvestigation.publish' => 1),
		'recursive' => 0);

	$assignedCapas = $this->paginate();
	$this->set(array('capaInvestigations' => $assignedCapas));
}


/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
public function box() {
	
	$conditions = $this->_check_request();
	$this->paginate = array('order'=>array('CapaInvestigation.sr_no'=>'DESC'),'conditions'=>array($conditions));

	$this->CapaInvestigation->recursive = 0;
	$this->set('capaInvestigations', $this->paginate());

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
		$search_keys = explode(" ",$this->request->data['CapaInvestigation']['search']);

		foreach($search_keys as $search_key):
			foreach($this->request->data['CapaInvestigation']['search_field'] as $search):
				$search_array[] = array('CapaInvestigation.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
			endforeach;

			if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('CapaInvestigation.branch_id'=>$this->Session->read('User.branch_id'));
			}

			$this->CapaInvestigation->recursive = 0;
			$this->paginate = array('order'=>array('CapaInvestigation.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'CapaInvestigation.soft_delete'=>0 , $cons));
			$this->set('capaInvestigations', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('CapaInvestigation.'.$search => $search_key);
				else $search_array[] = array('CapaInvestigation.'.$search.' like ' => '%'.$search_key.'%');

				endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
			if($this->request->query['branch_list']){
				foreach($this->request->query['branch_list'] as $branches):
					$branch_conditions[]=array('CapaInvestigation.branch_id'=>$branches);
				endforeach;
				$conditions[]=array('or'=>$branch_conditions);
			}

			if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
			if($this->request->query['from-date']){
				$conditions[] = array('CapaInvestigation.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'CapaInvestigation.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
			}
			unset($this->request->query);


			if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('CapaInvestigation.branch_id'=>$this->Session->read('User.branch_id'));
			if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('CapaInvestigation.created_by'=>$this->Session->read('User.id'));
			$conditions[] = array($onlyBranch,$onlyOwn);

			$this->CapaInvestigation->recursive = 0;
			$this->paginate = array('order'=>array('CapaInvestigation.sr_no'=>'DESC'),'conditions'=>$conditions , 'CapaInvestigation.soft_delete'=>0 );
			if(isset($_GET['limit']) && $_GET['limit'] != 0){
				$this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
			}
			$this->set('capaInvestigations', $this->paginate());
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
	if (!$this->CapaInvestigation->exists($id)) {
		throw new NotFoundException(__('Invalid capa investigation'));
	}
	$options = array('conditions' => array('CapaInvestigation.' . $this->CapaInvestigation->primaryKey => $id));
	$capaInvestigation =  $this->CapaInvestigation->find('first', $options);
	$correctivePreventiveAction = $this->CapaInvestigation->CorrectivePreventiveAction->find('first',array('conditions'=>array('CorrectivePreventiveAction.id'=>$capaInvestigation['CapaInvestigation']['corrective_preventive_action_id']),'recursive'=>0));
	$this->set('capaInvestigation',$capaInvestigation);
	$this->set('correctivePreventiveAction',$correctivePreventiveAction);
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
		$this->request->data['CapaInvestigation']['system_table_id'] = $this->_get_system_table_id();
		$this->CapaInvestigation->create();

		if ($this->CapaInvestigation->save($this->request->data)) {
			$this->capa_investigation_send_reminder($this->CapaInvestigation->id, $exit = 'yes');
			if ($this->_show_approvals()) $this->_save_approvals();
			$this->Session->setFlash(__('The capa investigation has been saved'));
			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->CapaInvestigation->id));
			else $this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash(__('The capa investigation could not be saved. Please, try again.'));
		}
	}
	$approvedBies = $this->CapaInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	$preparedBies = $this->CapaInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	$employeeIds = $this->CapaInvestigation->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
	$correctivePreventiveActionIds = $this->CapaInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
	$correctivePreventiveActionDetails = $this->CapaInvestigation->CorrectivePreventiveAction->find('first',array('conditions'=>array('CorrectivePreventiveAction.id'=>$capaId,'CorrectivePreventiveAction.publish'=>1, 'CorrectivePreventiveAction.soft_delete'=>0)));
	$masterListOfFormats = $this->CapaInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->CapaInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$createdBies = $this->CapaInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	$modifiedBies = $this->CapaInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	$this->set(compact('approvedBies', 'preparedBies', 'employeeIds', 'correctivePreventiveActionIds', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies'));
	$count = $this->CapaInvestigation->find('count');
	$published = $this->CapaInvestigation->find('count',array('conditions'=>array('CapaInvestigation.publish'=>1)));
	$unpublished = $this->CapaInvestigation->find('count',array('conditions'=>array('CapaInvestigation.publish'=>0)));

	$this->set(compact('count','published','unpublished','capaId','modal','correctivePreventiveActionDetails'));
	//get current capa investigation assigned
	$capaInvestigations = $this->CapaInvestigation->find('all',array('conditions'=>array('CapaInvestigation.corrective_preventive_action_id'=>$capaId)));
	$this->set('capaInvestigations',$capaInvestigations);

	$capa = $this->CapaInvestigation->CorrectivePreventiveAction->find('first',array('conditions'=>array('CorrectivePreventiveAction.id'=>$capaId),'recursive'=>0));
	$this->set('correctivePreventiveAction',$capa);

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
// 		$this->request->data['CapaInvestigation']['system_table_id'] = $this->_get_system_table_id();
// 		$this->CapaInvestigation->create();
// 		if ($this->CapaInvestigation->save($this->request->data)) {

// 			if($this->_show_approvals()){
// 				$this->loadModel('Approval');
// 				$this->Approval->create();
// 				$this->request->data['Approval']['model_name']='CapaInvestigation';
// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
// 				$this->request->data['Approval']['record']=$this->CapaInvestigation->id;
// 				$this->Approval->save($this->request->data['Approval']);
// 			}
// 			$this->Session->setFlash(__('The capa investigation has been saved'));
// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->CapaInvestigation->id));
// 			else $this->redirect(array('action' => 'index'));
// 		} else {
// 			$this->Session->setFlash(__('The capa investigation could not be saved. Please, try again.'));
// 		}
// 	}
// 	$approvedBies = $this->CapaInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
// 	$preparedBies = $this->CapaInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
// 	$employeeIds = $this->CapaInvestigation->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
// 	$correctivePreventiveActionIds = $this->CapaInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
// 	$masterListOfFormats = $this->CapaInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
// 	$companies = $this->CapaInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
// 	$statusUserIds = $this->CapaInvestigation->StatusUserId->find('list',array('conditions'=>array('StatusUserId.publish'=>1,'StatusUserId.soft_delete'=>0)));
// 	$createdBies = $this->CapaInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
// 	$modifiedBies = $this->CapaInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
// 	$this->set(compact('approvedBies', 'preparedBies', 'employeeIds', 'correctivePreventiveActionIds', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies'));
// 	$count = $this->CapaInvestigation->find('count');
// 	$published = $this->CapaInvestigation->find('count',array('conditions'=>array('CapaInvestigation.publish'=>1)));
// 	$unpublished = $this->CapaInvestigation->find('count',array('conditions'=>array('CapaInvestigation.publish'=>0)));

// 	$this->set(compact('count','published','unpublished'));

// }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
public function edit($id = null) {
	if (!$this->CapaInvestigation->exists($id)) {
		throw new NotFoundException(__('Invalid capa investigation'));
	}

	if ($this->_show_approvals()) {
		$this->set(array('showApprovals' => $this->_show_approvals()));
	}

	if ($this->request->is('post') || $this->request->is('put')) {

		if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
			$this->request->data[$this->modelClass]['publish'] = 0;
		}

		$this->request->data['CapaInvestigation']['system_table_id'] = $this->_get_system_table_id();
		if ($this->CapaInvestigation->save($this->request->data)) {
			// $this->capa_investigation_send_reminder($id);
			if ($this->_show_approvals()) $this->_save_approvals();

			if ($this->_show_evidence() == true)
				$this->redirect(array('action' => 'view', $id));
			else
				$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash(__('The capa investigation could not be saved. Please, try again.'));
		}
	} else {
		$options = array('conditions' => array('CapaInvestigation.' . $this->CapaInvestigation->primaryKey => $id));
		$this->request->data = $this->CapaInvestigation->find('first', $options);
	}
	$approvedBies = $this->CapaInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	$preparedBies = $this->CapaInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	$employeeIds = $this->CapaInvestigation->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
	$correctivePreventiveActionIds = $this->CapaInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
	$masterListOfFormats = $this->CapaInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->CapaInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$createdBies = $this->CapaInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	$modifiedBies = $this->CapaInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	$this->set(compact('approvedBies', 'preparedBies', 'employeeIds', 'correctivePreventiveActionIds', 'masterListOfFormats', 'companies', 'createdBies', 'modifiedBies'));
	$count = $this->CapaInvestigation->find('count');
	$published = $this->CapaInvestigation->find('count',array('conditions'=>array('CapaInvestigation.publish'=>1)));
	$unpublished = $this->CapaInvestigation->find('count',array('conditions'=>array('CapaInvestigation.publish'=>0)));

	$this->set(compact('count','published','unpublished'));

	$capa = $this->CapaInvestigation->CorrectivePreventiveAction->find('first',array('conditions'=>array('CorrectivePreventiveAction.id'=>$this->request->data['CapaInvestigation']['corrective_preventive_action_id']),'recursive'=>0));
	$this->set('correctivePreventiveAction',$capa);
}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
public function approve($id = null, $approvalId = null) {
	if (!$this->CapaInvestigation->exists($id)) {
		throw new NotFoundException(__('Invalid capa investigation'));
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
		if ($this->CapaInvestigation->save($this->request->data)) {
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
				$this->request->data[$this->modelClass]['publish'] = 0;
			}
			if ($this->CapaInvestigation->save($this->request->data)) {
				$this->Session->setFlash(__('The capa investigation has been saved.'));

				if ($this->_show_approvals()) $this->_save_approvals();

			} else {
				$this->Session->setFlash(__('The capa investigation could not be saved. Please, try again.'));
			}

		} else {
			$this->Session->setFlash(__('The capa investigation could not be saved. Please, try again.'));
		}
	} else {
		$options = array('conditions' => array('CapaInvestigation.' . $this->CapaInvestigation->primaryKey => $id));
		$this->request->data = $this->CapaInvestigation->find('first', $options);
	}
	$approvedBies = $this->CapaInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	$preparedBies = $this->CapaInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	$employeeIds = $this->CapaInvestigation->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
	$correctivePreventiveActionIds = $this->CapaInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
	$masterListOfFormats = $this->CapaInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	$companies = $this->CapaInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	$createdBies = $this->CapaInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	$modifiedBies = $this->CapaInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	$this->set(compact('approvedBies', 'preparedBies', 'employeeIds', 'correctivePreventiveActionIds', 'masterListOfFormats', 'companies', 'createdBies', 'modifiedBies'));
	$count = $this->CapaInvestigation->find('count');
	$published = $this->CapaInvestigation->find('count',array('conditions'=>array('CapaInvestigation.publish'=>1)));
	$unpublished = $this->CapaInvestigation->find('count',array('conditions'=>array('CapaInvestigation.publish'=>0)));

	$this->set(compact('count','published','unpublished'));

	$capa = $this->CapaInvestigation->CorrectivePreventiveAction->find('first',array('conditions'=>array('CorrectivePreventiveAction.id'=>$this->request->data['CapaInvestigation']['corrective_preventive_action_id']),'recursive'=>0));
	$this->set('correctivePreventiveAction',$capa);
}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
public function purge($id = null) {
	$this->CapaInvestigation->id = $id;
	if (!$this->CapaInvestigation->exists()) {
		throw new NotFoundException(__('Invalid capa investigation'));
	}
	$this->request->onlyAllow('post', 'delete');
	if ($this->CapaInvestigation->delete()) {
		$this->Session->setFlash(__('Capa investigation deleted'));
		$this->redirect(array('action' => 'index'));
	}
	$this->Session->setFlash(__('Capa investigation was not deleted'));
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

       	$result = explode('+',$this->request->data['capaInvestigations']['rec_selected']);
       	$this->CapaInvestigation->recursive = 1;
       	$capaInvestigations = $this->CapaInvestigation->find('all',array('CapaInvestigation.publish'=>1,'CapaInvestigation.soft_delete'=>1,'conditions'=>array('or'=>array('CapaInvestigation.id'=>$result))));
       	$this->set('capaInvestigations', $capaInvestigations);

       	$approvedBies = $this->CapaInvestigation->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
       	$preparedBies = $this->CapaInvestigation->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
       	$employeeIds = $this->CapaInvestigation->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
       	$correctivePreventiveActionIds = $this->CapaInvestigation->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
       	$masterListOfFormats = $this->CapaInvestigation->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
       	$companies = $this->CapaInvestigation->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
       	$statusUserIds = $this->CapaInvestigation->StatusUserId->find('list',array('conditions'=>array('StatusUserId.publish'=>1,'StatusUserId.soft_delete'=>0)));
       	$createdBies = $this->CapaInvestigation->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
       	$modifiedBies = $this->CapaInvestigation->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
       	$this->set(compact('approvedBies', 'preparedBies', 'employeeIds', 'correctivePreventiveActionIds', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies', 'approvedBies', 'preparedBies', 'employeeIds', 'correctivePreventiveActionIds', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies'));
       }
       public function pending_tasks(){
       	if($this->request->params['pass'][0]){
       		$conditions = null;
       		$capa_condition = array('CapaInvestigation.corrective_preventive_action_id'=>$this->request->params['pass'][0]);
       	}else{
       		$capa_condition = null;	
       		$conditions = $this->_check_request();
       	} 
       	//1 close 0 open
       	$conditions = array('CapaInvestigation.current_status'=>0);
       	$this->paginate = array('order'=>array('CapaInvestigation.sr_no'=>'DESC'),'conditions'=>array($conditions,$capa_condition));

       	$this->CapaInvestigation->recursive = 0;
       	$this->set('capaInvestigations', $this->paginate());

       	$this->_get_count();		
		// $this->render('index');
       }

       
   }

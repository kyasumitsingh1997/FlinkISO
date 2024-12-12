<?php
App::uses('AppController', 'Controller');
/**
 * MeetingTopics Controller
 *
 * @property MeetingTopic $MeetingTopic
 */
class MeetingTopicsController extends AppController {

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
		$this->paginate = array('order'=>array('MeetingTopic.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->MeetingTopic->recursive = 0;
		$this->set('meetingTopics', $this->paginate());
		
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
		$this->paginate = array('order'=>array('MeetingTopic.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->MeetingTopic->recursive = 0;
		$this->set('meetingTopics', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['MeetingTopic']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['MeetingTopic']['search_field'] as $search):
				$search_array[] = array('MeetingTopic.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('MeetingTopic.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->MeetingTopic->recursive = 0;
		$this->paginate = array('order'=>array('MeetingTopic.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'MeetingTopic.soft_delete'=>0 , $cons));
		$this->set('meetingTopics', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('MeetingTopic.'.$search => $search_key);
					else $search_array[] = array('MeetingTopic.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('MeetingTopic.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('MeetingTopic.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'MeetingTopic.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('MeetingTopic.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('MeetingTopic.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->MeetingTopic->recursive = 0;
		$this->paginate = array('order'=>array('MeetingTopic.sr_no'=>'DESC'),'conditions'=>$conditions , 'MeetingTopic.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('meetingTopics', $this->paginate());
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
		if (!$this->MeetingTopic->exists($id)) {
			throw new NotFoundException(__('Invalid meeting topic'));
		}
		$options = array('conditions' => array('MeetingTopic.' . $this->MeetingTopic->primaryKey => $id));
		$this->set('meetingTopic', $this->MeetingTopic->find('first', $options));
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
			$this->request->data['MeetingTopic']['system_table_id'] = $this->_get_system_table_id();
			$this->MeetingTopic->create();
			if ($this->MeetingTopic->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The meeting topic has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->MeetingTopic->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The meeting topic could not be saved. Please, try again.'));
			}
		}
		$approvedBies = $this->MeetingTopic->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->MeetingTopic->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$meetings = $this->MeetingTopic->Meeting->find('list',array('conditions'=>array('Meeting.publish'=>1,'Meeting.soft_delete'=>0)));
		$employees = $this->MeetingTopic->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$changeAdditionDeletionRequests = $this->MeetingTopic->ChangeAdditionDeletionRequest->find('list',array('conditions'=>array('ChangeAdditionDeletionRequest.publish'=>1,'ChangeAdditionDeletionRequest.soft_delete'=>0)));
		$correctivePreventiveActions = $this->MeetingTopic->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$customerComplaints = $this->MeetingTopic->CustomerComplaint->find('list',array('conditions'=>array('CustomerComplaint.publish'=>1,'CustomerComplaint.soft_delete'=>0)));
		$customerFeedbacks = $this->MeetingTopic->CustomerFeedback->find('list',array('conditions'=>array('CustomerFeedback.publish'=>1,'CustomerFeedback.soft_delete'=>0)));
		$systemTables = $this->MeetingTopic->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MeetingTopic->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->MeetingTopic->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$statusUserIds = $this->MeetingTopic->StatusUserId->find('list',array('conditions'=>array('StatusUserId.publish'=>1,'StatusUserId.soft_delete'=>0)));
		$createdBies = $this->MeetingTopic->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MeetingTopic->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('approvedBies', 'preparedBies', 'meetings', 'employees', 'changeAdditionDeletionRequests', 'correctivePreventiveActions', 'customerComplaints', 'customerFeedbacks', 'systemTables', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies'));
	$count = $this->MeetingTopic->find('count');
	$published = $this->MeetingTopic->find('count',array('conditions'=>array('MeetingTopic.publish'=>1)));
	$unpublished = $this->MeetingTopic->find('count',array('conditions'=>array('MeetingTopic.publish'=>0)));
		
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
                        $this->request->data['MeetingTopic']['system_table_id'] = $this->_get_system_table_id();
			$this->MeetingTopic->create();
			if ($this->MeetingTopic->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='MeetingTopic';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->MeetingTopic->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The meeting topic has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->MeetingTopic->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The meeting topic could not be saved. Please, try again.'));
			}
		}
		$approvedBies = $this->MeetingTopic->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->MeetingTopic->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$meetings = $this->MeetingTopic->Meeting->find('list',array('conditions'=>array('Meeting.publish'=>1,'Meeting.soft_delete'=>0)));
		$employees = $this->MeetingTopic->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$changeAdditionDeletionRequests = $this->MeetingTopic->ChangeAdditionDeletionRequest->find('list',array('conditions'=>array('ChangeAdditionDeletionRequest.publish'=>1,'ChangeAdditionDeletionRequest.soft_delete'=>0)));
		$correctivePreventiveActions = $this->MeetingTopic->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$customerComplaints = $this->MeetingTopic->CustomerComplaint->find('list',array('conditions'=>array('CustomerComplaint.publish'=>1,'CustomerComplaint.soft_delete'=>0)));
		$customerFeedbacks = $this->MeetingTopic->CustomerFeedback->find('list',array('conditions'=>array('CustomerFeedback.publish'=>1,'CustomerFeedback.soft_delete'=>0)));
		$systemTables = $this->MeetingTopic->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MeetingTopic->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->MeetingTopic->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$statusUserIds = $this->MeetingTopic->StatusUserId->find('list',array('conditions'=>array('StatusUserId.publish'=>1,'StatusUserId.soft_delete'=>0)));
		$createdBies = $this->MeetingTopic->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MeetingTopic->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('approvedBies', 'preparedBies', 'meetings', 'employees', 'changeAdditionDeletionRequests', 'correctivePreventiveActions', 'customerComplaints', 'customerFeedbacks', 'systemTables', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies'));
	$count = $this->MeetingTopic->find('count');
	$published = $this->MeetingTopic->find('count',array('conditions'=>array('MeetingTopic.publish'=>1)));
	$unpublished = $this->MeetingTopic->find('count',array('conditions'=>array('MeetingTopic.publish'=>0)));
		
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
		if (!$this->MeetingTopic->exists($id)) {
			throw new NotFoundException(__('Invalid meeting topic'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['MeetingTopic']['system_table_id'] = $this->_get_system_table_id();
			if ($this->MeetingTopic->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('controller'=>'meetings', 'action' => 'index', $id));
				else
		 			$this->redirect(array('controller'=>'meetings', 'action' => 'index', $id));
			} else {
				$this->Session->setFlash(__('The meeting topic could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MeetingTopic.' . $this->MeetingTopic->primaryKey => $id));
			$this->request->data = $this->MeetingTopic->find('first', $options);
		}
		$approvedBies = $this->MeetingTopic->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->MeetingTopic->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$meetings = $this->MeetingTopic->Meeting->find('list',array('conditions'=>array('Meeting.publish'=>1,'Meeting.soft_delete'=>0)));
		$employees = $this->MeetingTopic->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$changeAdditionDeletionRequests = $this->MeetingTopic->ChangeAdditionDeletionRequest->find('list',array('conditions'=>array('ChangeAdditionDeletionRequest.publish'=>1,'ChangeAdditionDeletionRequest.soft_delete'=>0)));
		$correctivePreventiveActions = $this->MeetingTopic->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$customerComplaints = $this->MeetingTopic->CustomerComplaint->find('list',array('conditions'=>array('CustomerComplaint.publish'=>1,'CustomerComplaint.soft_delete'=>0)));
		$customerFeedbacks = $this->MeetingTopic->CustomerFeedback->find('list',array('conditions'=>array('CustomerFeedback.publish'=>1,'CustomerFeedback.soft_delete'=>0)));
		$systemTables = $this->MeetingTopic->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MeetingTopic->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->MeetingTopic->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$statusUserIds = $this->MeetingTopic->StatusUserId->find('list',array('conditions'=>array('StatusUserId.publish'=>1,'StatusUserId.soft_delete'=>0)));
		$createdBies = $this->MeetingTopic->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MeetingTopic->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies', 'meetings', 'employees', 'changeAdditionDeletionRequests', 'correctivePreventiveActions', 'customerComplaints', 'customerFeedbacks', 'systemTables', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies'));
		$count = $this->MeetingTopic->find('count');
		$published = $this->MeetingTopic->find('count',array('conditions'=>array('MeetingTopic.publish'=>1)));
		$unpublished = $this->MeetingTopic->find('count',array('conditions'=>array('MeetingTopic.publish'=>0)));
		
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
		if (!$this->MeetingTopic->exists($id)) {
			throw new NotFoundException(__('Invalid meeting topic'));
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
			if ($this->MeetingTopic->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->MeetingTopic->save($this->request->data)) {
                $this->Session->setFlash(__('The meeting topic has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The meeting topic could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The meeting topic could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MeetingTopic.' . $this->MeetingTopic->primaryKey => $id));
			$this->request->data = $this->MeetingTopic->find('first', $options);
		}
		$approvedBies = $this->MeetingTopic->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->MeetingTopic->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$meetings = $this->MeetingTopic->Meeting->find('list',array('conditions'=>array('Meeting.publish'=>1,'Meeting.soft_delete'=>0)));
		$employees = $this->MeetingTopic->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$changeAdditionDeletionRequests = $this->MeetingTopic->ChangeAdditionDeletionRequest->find('list',array('conditions'=>array('ChangeAdditionDeletionRequest.publish'=>1,'ChangeAdditionDeletionRequest.soft_delete'=>0)));
		$correctivePreventiveActions = $this->MeetingTopic->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$customerComplaints = $this->MeetingTopic->CustomerComplaint->find('list',array('conditions'=>array('CustomerComplaint.publish'=>1,'CustomerComplaint.soft_delete'=>0)));
		$customerFeedbacks = $this->MeetingTopic->CustomerFeedback->find('list',array('conditions'=>array('CustomerFeedback.publish'=>1,'CustomerFeedback.soft_delete'=>0)));
		$systemTables = $this->MeetingTopic->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MeetingTopic->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->MeetingTopic->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$statusUserIds = $this->MeetingTopic->StatusUserId->find('list',array('conditions'=>array('StatusUserId.publish'=>1,'StatusUserId.soft_delete'=>0)));
		$createdBies = $this->MeetingTopic->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MeetingTopic->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies', 'meetings', 'employees', 'changeAdditionDeletionRequests', 'correctivePreventiveActions', 'customerComplaints', 'customerFeedbacks', 'systemTables', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies'));
		$count = $this->MeetingTopic->find('count');
		$published = $this->MeetingTopic->find('count',array('conditions'=>array('MeetingTopic.publish'=>1)));
		$unpublished = $this->MeetingTopic->find('count',array('conditions'=>array('MeetingTopic.publish'=>0)));
		
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
		$this->MeetingTopic->id = $id;
		if (!$this->MeetingTopic->exists()) {
			throw new NotFoundException(__('Invalid meeting topic'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MeetingTopic->delete()) {
			$this->Session->setFlash(__('Meeting topic deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Meeting topic was not deleted'));
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
		
		$result = explode('+',$this->request->data['meetingTopics']['rec_selected']);
		$this->MeetingTopic->recursive = 1;
		$meetingTopics = $this->MeetingTopic->find('all',array('MeetingTopic.publish'=>1,'MeetingTopic.soft_delete'=>1,'conditions'=>array('or'=>array('MeetingTopic.id'=>$result))));
		$this->set('meetingTopics', $meetingTopics);
		
				$approvedBies = $this->MeetingTopic->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$preparedBies = $this->MeetingTopic->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$meetings = $this->MeetingTopic->Meeting->find('list',array('conditions'=>array('Meeting.publish'=>1,'Meeting.soft_delete'=>0)));
		$employees = $this->MeetingTopic->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$changeAdditionDeletionRequests = $this->MeetingTopic->ChangeAdditionDeletionRequest->find('list',array('conditions'=>array('ChangeAdditionDeletionRequest.publish'=>1,'ChangeAdditionDeletionRequest.soft_delete'=>0)));
		$correctivePreventiveActions = $this->MeetingTopic->CorrectivePreventiveAction->find('list',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0)));
		$customerComplaints = $this->MeetingTopic->CustomerComplaint->find('list',array('conditions'=>array('CustomerComplaint.publish'=>1,'CustomerComplaint.soft_delete'=>0)));
		$customerFeedbacks = $this->MeetingTopic->CustomerFeedback->find('list',array('conditions'=>array('CustomerFeedback.publish'=>1,'CustomerFeedback.soft_delete'=>0)));
		$systemTables = $this->MeetingTopic->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MeetingTopic->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->MeetingTopic->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$statusUserIds = $this->MeetingTopic->StatusUserId->find('list',array('conditions'=>array('StatusUserId.publish'=>1,'StatusUserId.soft_delete'=>0)));
		$createdBies = $this->MeetingTopic->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MeetingTopic->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('approvedBies', 'preparedBies', 'meetings', 'employees', 'changeAdditionDeletionRequests', 'correctivePreventiveActions', 'customerComplaints', 'customerFeedbacks', 'systemTables', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies', 'approvedBies', 'preparedBies', 'meetings', 'employees', 'changeAdditionDeletionRequests', 'correctivePreventiveActions', 'customerComplaints', 'customerFeedbacks', 'systemTables', 'masterListOfFormats', 'companies', 'statusUserIds', 'createdBies', 'modifiedBies'));
}
}

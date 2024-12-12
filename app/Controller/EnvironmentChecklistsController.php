<?php
App::uses('AppController', 'Controller');
/**
 * EnvironmentChecklists Controller
 *
 * @property EnvironmentChecklist $EnvironmentChecklist
 */
class EnvironmentChecklistsController extends AppController {

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
		$this->paginate = array('order'=>array('EnvironmentChecklist.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->EnvironmentChecklist->recursive = 0;
		$this->set('environmentChecklists', $this->paginate());
		
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
		$this->paginate = array('order'=>array('EnvironmentChecklist.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->EnvironmentChecklist->recursive = 0;
		$this->set('environmentChecklists', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['EnvironmentChecklist']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['EnvironmentChecklist']['search_field'] as $search):
				$search_array[] = array('EnvironmentChecklist.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('EnvironmentChecklist.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->EnvironmentChecklist->recursive = 0;
		$this->paginate = array('order'=>array('EnvironmentChecklist.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'EnvironmentChecklist.soft_delete'=>0 , $cons));
		$this->set('environmentChecklists', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('EnvironmentChecklist.'.$search => $search_key);
					else $search_array[] = array('EnvironmentChecklist.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('EnvironmentChecklist.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('EnvironmentChecklist.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'EnvironmentChecklist.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('EnvironmentChecklist.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('EnvironmentChecklist.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->EnvironmentChecklist->recursive = 0;
		$this->paginate = array('order'=>array('EnvironmentChecklist.sr_no'=>'DESC'),'conditions'=>$conditions , 'EnvironmentChecklist.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('environmentChecklists', $this->paginate());
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
		if (!$this->EnvironmentChecklist->exists($id)) {
			throw new NotFoundException(__('Invalid environment checklist'));
		}
		$options = array('recursive'=>0, 'conditions' => array('EnvironmentChecklist.' . $this->EnvironmentChecklist->primaryKey => $id));
		$environmentChecklist = $this->EnvironmentChecklist->find('first', $options);
		$this->set('environmentChecklist', $environmentChecklist);
		$environmentQuestionnaireCategories = $this->EnvironmentChecklist->EnvironmentQuestionnaireCategory->find('list',array('conditions'=>array('EnvironmentQuestionnaireCategory.publish'=>1,'EnvironmentQuestionnaireCategory.soft_delete'=>0)));
		foreach ($environmentQuestionnaireCategories as $key => $value) {			
			$questions[$key]['name'] = $value;
			$questions[$key]['questions'] = $this->EnvironmentChecklist->EnvironmentChecklistAnswer->find('all',array(
				'fields'=>array(
					'EnvironmentChecklistAnswer.id',
					'EnvironmentChecklistAnswer.environment_questionnaire_id',
					'EnvironmentChecklistAnswer.environment_checklist_id',
					'EnvironmentChecklistAnswer.environment_questionnaire_category_id',
					'EnvironmentChecklistAnswer.details',
					'EnvironmentChecklistAnswer.answer',
					'EnvironmentQuestionnaire.title'
					),
				'conditions'=>array('EnvironmentChecklistAnswer.environment_questionnaire_category_id'=>$key,'EnvironmentChecklistAnswer.environment_checklist_id'=>$id)));
		}
		$this->set('questions',$questions);
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
			$this->request->data['EnvironmentChecklist']['system_table_id'] = $this->_get_system_table_id();
			$this->EnvironmentChecklist->create();
			if ($this->EnvironmentChecklist->save($this->request->data)) {
				$this->_add_answers($this->EnvironmentChecklist->id,$this->request->data['EnvironmentChecklistAnswer']);
				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The environment checklist has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->EnvironmentChecklist->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The environment checklist could not be saved. Please, try again.'));
			}
		}
		$branches = $this->EnvironmentChecklist->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$departments = $this->EnvironmentChecklist->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$employees = $this->EnvironmentChecklist->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$environmentQuestionnaireCategories = $this->EnvironmentChecklist->EnvironmentQuestionnaireCategory->find('list',array('conditions'=>array('EnvironmentQuestionnaireCategory.publish'=>1,'EnvironmentQuestionnaireCategory.soft_delete'=>0)));
		
		foreach ($environmentQuestionnaireCategories as $key => $value) {			
			$questions[$key]['name'] = $value;
			$questions[$key]['questions'] = $this->EnvironmentChecklist->EnvironmentQuestionnaire->find('list',array('conditions'=>array('EnvironmentQuestionnaire.environment_questionnaire_category_id'=>$key, 'EnvironmentQuestionnaire.publish'=>1,'EnvironmentQuestionnaire.soft_delete'=>0)));
		}
		
		
		$systemTables = $this->EnvironmentChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EnvironmentChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->EnvironmentChecklist->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->EnvironmentChecklist->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->EnvironmentChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EnvironmentChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EnvironmentChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EnvironmentChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('branches', 'departments', 'employees', 'environmentQuestionnaireCategories', 'questions', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->EnvironmentChecklist->find('count');
		$published = $this->EnvironmentChecklist->find('count',array('conditions'=>array('EnvironmentChecklist.publish'=>1)));
		$unpublished = $this->EnvironmentChecklist->find('count',array('conditions'=>array('EnvironmentChecklist.publish'=>0)));
		
	$this->set(compact('count','published','unpublished'));

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
 //                        $this->request->data['EnvironmentChecklist']['system_table_id'] = $this->_get_system_table_id();
	// 		$this->EnvironmentChecklist->create();
	// 		if ($this->EnvironmentChecklist->save($this->request->data)) {

	// 			if($this->_show_approvals()){
	// 				$this->loadModel('Approval');
	// 				$this->Approval->create();
	// 				$this->request->data['Approval']['model_name']='EnvironmentChecklist';
	// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
	// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
	// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['record']=$this->EnvironmentChecklist->id;
	// 				$this->Approval->save($this->request->data['Approval']);
	// 			}
	// 			$this->Session->setFlash(__('The environment checklist has been saved'));
	// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->EnvironmentChecklist->id));
	// 			else $this->redirect(array('action' => 'index'));
	// 		} else {
	// 			$this->Session->setFlash(__('The environment checklist could not be saved. Please, try again.'));
	// 		}
	// 	}
	// 	$branches = $this->EnvironmentChecklist->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
	// 	$departments = $this->EnvironmentChecklist->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
	// 	$employees = $this->EnvironmentChecklist->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
	// 	$environmentQuestionnaireCategories = $this->EnvironmentChecklist->EnvironmentQuestionnaireCategory->find('list',array('conditions'=>array('EnvironmentQuestionnaireCategory.publish'=>1,'EnvironmentQuestionnaireCategory.soft_delete'=>0)));
	// 	$environmentQuestionnaires = $this->EnvironmentChecklist->EnvironmentQuestionnaire->find('list',array('conditions'=>array('EnvironmentQuestionnaire.publish'=>1,'EnvironmentQuestionnaire.soft_delete'=>0)));
	// 	$systemTables = $this->EnvironmentChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	// 	$masterListOfFormats = $this->EnvironmentChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	// 	$divisions = $this->EnvironmentChecklist->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
	// 	$companies = $this->EnvironmentChecklist->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
	// 	$preparedBies = $this->EnvironmentChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	// 	$approvedBies = $this->EnvironmentChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	// 	$createdBies = $this->EnvironmentChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	// 	$modifiedBies = $this->EnvironmentChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	// 			$this->set(compact('branches', 'departments', 'employees', 'environmentQuestionnaireCategories', 'environmentQuestionnaires', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	// $count = $this->EnvironmentChecklist->find('count');
	// $published = $this->EnvironmentChecklist->find('count',array('conditions'=>array('EnvironmentChecklist.publish'=>1)));
	// $unpublished = $this->EnvironmentChecklist->find('count',array('conditions'=>array('EnvironmentChecklist.publish'=>0)));
		
	// $this->set(compact('count','published','unpublished'));

	// }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->EnvironmentChecklist->exists($id)) {
			throw new NotFoundException(__('Invalid environment checklist'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      	
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        		$this->request->data[$this->modelClass]['publish'] = 0;
      	}
						
			$this->request->data['EnvironmentChecklist']['system_table_id'] = $this->_get_system_table_id();
			if ($this->EnvironmentChecklist->save($this->request->data)) {
				$this->_add_answers($this->request->data['EnvironmentChecklist']['id'],$this->request->data['EnvironmentChecklistAnswer']);
				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The environment checklist could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EnvironmentChecklist.' . $this->EnvironmentChecklist->primaryKey => $id));
			$this->request->data = $this->EnvironmentChecklist->find('first', $options);
		}
		$branches = $this->EnvironmentChecklist->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$departments = $this->EnvironmentChecklist->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$employees = $this->EnvironmentChecklist->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		// $environmentQuestionnaireCategories = $this->EnvironmentChecklist->EnvironmentQuestionnaireCategory->find('list',array('conditions'=>array('EnvironmentQuestionnaireCategory.publish'=>1,'EnvironmentQuestionnaireCategory.soft_delete'=>0)));
		$environmentQuestionnaires = $this->EnvironmentChecklist->EnvironmentQuestionnaire->find('list',array('conditions'=>array('EnvironmentQuestionnaire.publish'=>1,'EnvironmentQuestionnaire.soft_delete'=>0)));
		$systemTables = $this->EnvironmentChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EnvironmentChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->EnvironmentChecklist->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->EnvironmentChecklist->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->EnvironmentChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EnvironmentChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EnvironmentChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EnvironmentChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('branches', 'departments', 'employees', 'environmentQuestionnaireCategories', 'environmentQuestionnaires', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->EnvironmentChecklist->find('count');
		$published = $this->EnvironmentChecklist->find('count',array('conditions'=>array('EnvironmentChecklist.publish'=>1)));
		$unpublished = $this->EnvironmentChecklist->find('count',array('conditions'=>array('EnvironmentChecklist.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		$environmentQuestionnaireCategories = $this->EnvironmentChecklist->EnvironmentQuestionnaireCategory->find('list',array('conditions'=>array('EnvironmentQuestionnaireCategory.publish'=>1,'EnvironmentQuestionnaireCategory.soft_delete'=>0)));
		foreach ($environmentQuestionnaireCategories as $key => $value) {			
			$questions[$key]['name'] = $value;
			$questions[$key]['questions'] = $this->EnvironmentChecklist->EnvironmentChecklistAnswer->find('all',array(
				'fields'=>array(
					'EnvironmentChecklistAnswer.id',
					'EnvironmentChecklistAnswer.environment_questionnaire_id',
					'EnvironmentChecklistAnswer.environment_checklist_id',
					'EnvironmentChecklistAnswer.environment_questionnaire_category_id',
					'EnvironmentChecklistAnswer.details',
					'EnvironmentChecklistAnswer.answer',
					'EnvironmentQuestionnaire.title'
					),
				'conditions'=>array('EnvironmentChecklistAnswer.environment_questionnaire_category_id'=>$key,'EnvironmentChecklistAnswer.environment_checklist_id'=>$id)));
		}
		$this->set('questions',$questions);		
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->EnvironmentChecklist->exists($id)) {
			throw new NotFoundException(__('Invalid environment checklist'));
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
			if ($this->EnvironmentChecklist->save($this->request->data)) {
				$this->_add_answers($this->request->data['EnvironmentChecklist']['id'],$this->request->data['EnvironmentChecklistAnswer']);
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->EnvironmentChecklist->save($this->request->data)) {
                $this->Session->setFlash(__('The environment checklist has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The environment checklist could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The environment checklist could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EnvironmentChecklist.' . $this->EnvironmentChecklist->primaryKey => $id));
			$this->request->data = $this->EnvironmentChecklist->find('first', $options);
		}
		$branches = $this->EnvironmentChecklist->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$departments = $this->EnvironmentChecklist->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$employees = $this->EnvironmentChecklist->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		// $environmentQuestionnaireCategories = $this->EnvironmentChecklist->EnvironmentQuestionnaireCategory->find('list',array('conditions'=>array('EnvironmentQuestionnaireCategory.publish'=>1,'EnvironmentQuestionnaireCategory.soft_delete'=>0)));
		$environmentQuestionnaires = $this->EnvironmentChecklist->EnvironmentQuestionnaire->find('list',array('conditions'=>array('EnvironmentQuestionnaire.publish'=>1,'EnvironmentQuestionnaire.soft_delete'=>0)));
		$systemTables = $this->EnvironmentChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EnvironmentChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->EnvironmentChecklist->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->EnvironmentChecklist->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->EnvironmentChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EnvironmentChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EnvironmentChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EnvironmentChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('branches', 'departments', 'employees', 'environmentQuestionnaireCategories', 'environmentQuestionnaires', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->EnvironmentChecklist->find('count');
		$published = $this->EnvironmentChecklist->find('count',array('conditions'=>array('EnvironmentChecklist.publish'=>1)));
		$unpublished = $this->EnvironmentChecklist->find('count',array('conditions'=>array('EnvironmentChecklist.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));
		$environmentQuestionnaireCategories = $this->EnvironmentChecklist->EnvironmentQuestionnaireCategory->find('list',array('conditions'=>array('EnvironmentQuestionnaireCategory.publish'=>1,'EnvironmentQuestionnaireCategory.soft_delete'=>0)));
		foreach ($environmentQuestionnaireCategories as $key => $value) {			
			$questions[$key]['name'] = $value;
			$questions[$key]['questions'] = $this->EnvironmentChecklist->EnvironmentChecklistAnswer->find('all',array(
				'fields'=>array(
					'EnvironmentChecklistAnswer.id',
					'EnvironmentChecklistAnswer.environment_questionnaire_id',
					'EnvironmentChecklistAnswer.environment_checklist_id',
					'EnvironmentChecklistAnswer.environment_questionnaire_category_id',
					'EnvironmentChecklistAnswer.details',
					'EnvironmentChecklistAnswer.answer',
					'EnvironmentQuestionnaire.title'
					),
				'conditions'=>array('EnvironmentChecklistAnswer.environment_questionnaire_category_id'=>$key,'EnvironmentChecklistAnswer.environment_checklist_id'=>$id)));
		}
		$this->set('questions',$questions);
	}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function purge($id = null) {
		$this->EnvironmentChecklist->id = $id;
		if (!$this->EnvironmentChecklist->exists()) {
			throw new NotFoundException(__('Invalid environment checklist'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->EnvironmentChecklist->delete()) {
			$this->Session->setFlash(__('Environment checklist deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Environment checklist was not deleted'));
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
		
		$result = explode('+',$this->request->data['environmentChecklists']['rec_selected']);
		$this->EnvironmentChecklist->recursive = 1;
		$environmentChecklists = $this->EnvironmentChecklist->find('all',array('EnvironmentChecklist.publish'=>1,'EnvironmentChecklist.soft_delete'=>1,'conditions'=>array('or'=>array('EnvironmentChecklist.id'=>$result))));
		$this->set('environmentChecklists', $environmentChecklists);
		
				$branches = $this->EnvironmentChecklist->Branch->find('list',array('conditions'=>array('Branch.publish'=>1,'Branch.soft_delete'=>0)));
		$departments = $this->EnvironmentChecklist->Department->find('list',array('conditions'=>array('Department.publish'=>1,'Department.soft_delete'=>0)));
		$employees = $this->EnvironmentChecklist->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$environmentQuestionnaireCategories = $this->EnvironmentChecklist->EnvironmentQuestionnaireCategory->find('list',array('conditions'=>array('EnvironmentQuestionnaireCategory.publish'=>1,'EnvironmentQuestionnaireCategory.soft_delete'=>0)));
		$environmentQuestionnaires = $this->EnvironmentChecklist->EnvironmentQuestionnaire->find('list',array('conditions'=>array('EnvironmentQuestionnaire.publish'=>1,'EnvironmentQuestionnaire.soft_delete'=>0)));
		$systemTables = $this->EnvironmentChecklist->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->EnvironmentChecklist->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->EnvironmentChecklist->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->EnvironmentChecklist->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->EnvironmentChecklist->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->EnvironmentChecklist->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->EnvironmentChecklist->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->EnvironmentChecklist->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('branches', 'departments', 'employees', 'environmentQuestionnaireCategories', 'environmentQuestionnaires', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'branches', 'departments', 'employees', 'environmentQuestionnaireCategories', 'environmentQuestionnaires', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}

	public function _add_answers($id = null, $answer_data = null){
		$this->loadModel('EnvironmentChecklistAnswer');
		$this->EnvironmentChecklistAnswer->deleteAll(array('EnvironmentChecklistAnswer.environment_checklist_id'=>$id));
		foreach ($answer_data as $answers) {
			$data['EnvironmentChecklistAnswer']['environment_checklist_id'] = $id;
			$data['EnvironmentChecklistAnswer']['environment_questionnaire_id'] = $answers['environment_questionnaire_id'];
			$data['EnvironmentChecklistAnswer']['environment_questionnaire_category_id'] = $answers['environment_questionnaire_category_id'];
			$data['EnvironmentChecklistAnswer']['details'] = $answers['details'];
			$data['EnvironmentChecklistAnswer']['answer'] = $answers['answer'];		
			$this->EnvironmentChecklistAnswer->create();
			$this->EnvironmentChecklistAnswer->save($data);
		}
	}
}

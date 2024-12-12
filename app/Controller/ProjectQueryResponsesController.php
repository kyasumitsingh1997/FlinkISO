<?php
App::uses('AppController', 'Controller');
/**
 * ProjectQueryResponses Controller
 *
 * @property ProjectQueryResponse $ProjectQueryResponse
 * @property PaginatorComponent $Paginator
 */
class ProjectQueryResponsesController extends AppController {

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
		$this->paginate = array('order'=>array('ProjectQueryResponse.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ProjectQueryResponse->recursive = 0;
		$this->set('projectQueryResponses', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ProjectQueryResponse.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectQueryResponse->recursive = 0;
		$this->set('projectQueryResponses', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectQueryResponse']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectQueryResponse']['search_field'] as $search):
				$search_array[] = array('ProjectQueryResponse.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectQueryResponse.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectQueryResponse->recursive = 0;
		$this->paginate = array('order'=>array('ProjectQueryResponse.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectQueryResponse.soft_delete'=>0 , $cons));
		$this->set('projectQueryResponses', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectQueryResponse.'.$search => $search_key);
					else $search_array[] = array('ProjectQueryResponse.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectQueryResponse.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectQueryResponse.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectQueryResponse.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectQueryResponse.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectQueryResponse.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectQueryResponse->recursive = 0;
		$this->paginate = array('order'=>array('ProjectQueryResponse.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectQueryResponse.soft_delete'=>0 );
		$this->set('projectQueryResponses', $this->paginate());
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
		if (!$this->ProjectQueryResponse->exists($id)) {
			throw new NotFoundException(__('Invalid project query response'));
		}
		$options = array('conditions' => array('ProjectQueryResponse.' . $this->ProjectQueryResponse->primaryKey => $id));
		$this->set('projectQueryResponse', $this->ProjectQueryResponse->find('first', $options));
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
			$this->request->data['ProjectQueryResponse']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectQueryResponse->create();
			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;
			if ($this->ProjectQueryResponse->save($this->request->data)) {

				foreach ($this->request->data['Files'] as $file) {
					$this->_uploaddocument($this->ProjectQueryResponse->id,$file);
				}

				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The project query response has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectQueryResponse->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project query response could not be saved. Please, try again.'));
			}
		}
		// $projectQueries = $this->ProjectQueryResponse->ProjectQuery->find('list',array('conditions'=>array('ProjectQuery.publish'=>1,'ProjectQuery.soft_delete'=>0)));
		$employees = $this->ProjectQueryResponse->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectQueryResponse->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectQueryResponse->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectQueryResponse->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectQueryResponse->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectQueryResponse->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectQueryResponse->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projectQueries', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectQueryResponse->find('count');
		$published = $this->ProjectQueryResponse->find('count',array('conditions'=>array('ProjectQueryResponse.publish'=>1)));
		$unpublished = $this->ProjectQueryResponse->find('count',array('conditions'=>array('ProjectQueryResponse.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

		// Configure::write('debug',1);
		if($this->request->params['pass'][0]){
			$projectQuery = $this->ProjectQueryResponse->ProjectQuery->find('first',array(
				'recursive'=>0,
				// 'fields'=>array('ProjectQuery.id','ProjectQuery.name'),
				'conditions'=>array('ProjectQuery.id'=>$this->request->params['pass'][0])
			));

			debug($projectQuery);

			$this->set('projectQuery',$projectQuery);

			$projectQueryResponses = $this->ProjectQueryResponse->find('all',array(
				'conditions'=>array(
					'ProjectQueryResponse.project_query_id'=>$this->request->params['pass'][0])
					// 'ProjectQuery.publish'=>1,'ProjectQuery.soft_delete'=>0)
			));
			$this->set('projectQueryResponses',$projectQueryResponses);
		}else{

		}
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
                        $this->request->data['ProjectQueryResponse']['system_table_id'] = $this->_get_system_table_id();
			$this->ProjectQueryResponse->create();
			if ($this->ProjectQueryResponse->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='ProjectQueryResponse';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->ProjectQueryResponse->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The project query response has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectQueryResponse->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project query response could not be saved. Please, try again.'));
			}
		}
		$projectQueries = $this->ProjectQueryResponse->ProjectQuery->find('list',array('conditions'=>array('ProjectQuery.publish'=>1,'ProjectQuery.soft_delete'=>0)));
		$employees = $this->ProjectQueryResponse->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectQueryResponse->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectQueryResponse->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectQueryResponse->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectQueryResponse->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectQueryResponse->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectQueryResponse->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('projectQueries', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->ProjectQueryResponse->find('count');
	$published = $this->ProjectQueryResponse->find('count',array('conditions'=>array('ProjectQueryResponse.publish'=>1)));
	$unpublished = $this->ProjectQueryResponse->find('count',array('conditions'=>array('ProjectQueryResponse.publish'=>0)));
		
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
		if (!$this->ProjectQueryResponse->exists($id)) {
			throw new NotFoundException(__('Invalid project query response'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProjectQueryResponse']['system_table_id'] = $this->_get_system_table_id();
			if ($this->ProjectQueryResponse->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The project query response could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectQueryResponse.' . $this->ProjectQueryResponse->primaryKey => $id));
			$this->request->data = $this->ProjectQueryResponse->find('first', $options);
		}
		$projectQueries = $this->ProjectQueryResponse->ProjectQuery->find('list',array('conditions'=>array('ProjectQuery.publish'=>1,'ProjectQuery.soft_delete'=>0)));
		$employees = $this->ProjectQueryResponse->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectQueryResponse->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectQueryResponse->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectQueryResponse->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectQueryResponse->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectQueryResponse->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectQueryResponse->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projectQueries', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectQueryResponse->find('count');
		$published = $this->ProjectQueryResponse->find('count',array('conditions'=>array('ProjectQueryResponse.publish'=>1)));
		$unpublished = $this->ProjectQueryResponse->find('count',array('conditions'=>array('ProjectQueryResponse.publish'=>0)));
		
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
		if (!$this->ProjectQueryResponse->exists($id)) {
			throw new NotFoundException(__('Invalid project query response'));
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
			if ($this->ProjectQueryResponse->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectQueryResponse->save($this->request->data)) {
                $this->Session->setFlash(__('The project query response has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project query response could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project query response could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectQueryResponse.' . $this->ProjectQueryResponse->primaryKey => $id));
			$this->request->data = $this->ProjectQueryResponse->find('first', $options);
		}
		$projectQueries = $this->ProjectQueryResponse->ProjectQuery->find('list',array('conditions'=>array('ProjectQuery.publish'=>1,'ProjectQuery.soft_delete'=>0)));
		$employees = $this->ProjectQueryResponse->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectQueryResponse->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectQueryResponse->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectQueryResponse->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectQueryResponse->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectQueryResponse->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectQueryResponse->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projectQueries', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectQueryResponse->find('count');
		$published = $this->ProjectQueryResponse->find('count',array('conditions'=>array('ProjectQueryResponse.publish'=>1)));
		$unpublished = $this->ProjectQueryResponse->find('count',array('conditions'=>array('ProjectQueryResponse.publish'=>0)));
		
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
		$this->ProjectQueryResponse->id = $id;
		if (!$this->ProjectQueryResponse->exists()) {
			throw new NotFoundException(__('Invalid project query response'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectQueryResponse->delete()) {
			$this->Session->setFlash(__('Project query response deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project query response was not deleted'));
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
		
		$result = explode('+',$this->request->data['projectQueryResponses']['rec_selected']);
		$this->ProjectQueryResponse->recursive = 1;
		$projectQueryResponses = $this->ProjectQueryResponse->find('all',array('ProjectQueryResponse.publish'=>1,'ProjectQueryResponse.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectQueryResponse.id'=>$result))));
		$this->set('projectQueryResponses', $projectQueryResponses);
		
				$projectQueries = $this->ProjectQueryResponse->ProjectQuery->find('list',array('conditions'=>array('ProjectQuery.publish'=>1,'ProjectQuery.soft_delete'=>0)));
		$employees = $this->ProjectQueryResponse->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$systemTables = $this->ProjectQueryResponse->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectQueryResponse->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectQueryResponse->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectQueryResponse->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectQueryResponse->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectQueryResponse->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projectQueries', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projectQueries', 'employees', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function _uploaddocument($id = null,$file = null){
		
        $path = WWW_ROOT . DS . 'img'. DS . 'files'. DS . $this->Session->read('User.company_id'). DS . 'qurery_file_responses' . DS . $id;
        try{
            mkdir($path);
        }catch(Exception $e){                

            debug($e);    
        }
        chmod($path,0777);
        $moveLogo = move_uploaded_file($file['tmp_name'], $path . DS . $file['name']); 
        if($moveLogo){
          
        } else {
          
        }
        return true;
    }
}

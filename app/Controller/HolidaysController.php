<?php
App::uses('AppController', 'Controller');
/**
 * Holidays Controller
 *
 * @property Holiday $Holiday
 * @property PaginatorComponent $Paginator
 */
class HolidaysController extends AppController {

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
		$this->paginate = array('order'=>array('Holiday.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->Holiday->recursive = 0;
		$this->set('holidays', $this->paginate());
		
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
		$this->paginate = array('order'=>array('Holiday.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->Holiday->recursive = 0;
		$this->set('holidays', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['Holiday']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['Holiday']['search_field'] as $search):
				$search_array[] = array('Holiday.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('Holiday.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->Holiday->recursive = 0;
		$this->paginate = array('order'=>array('Holiday.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Holiday.soft_delete'=>0 , $cons));
		$this->set('holidays', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('Holiday.'.$search => $search_key);
					else $search_array[] = array('Holiday.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('Holiday.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('Holiday.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'Holiday.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Holiday.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Holiday.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->Holiday->recursive = 0;
		$this->paginate = array('order'=>array('Holiday.sr_no'=>'DESC'),'conditions'=>$conditions , 'Holiday.soft_delete'=>0 );
		$this->set('holidays', $this->paginate());
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
		if (!$this->Holiday->exists($id)) {
			throw new NotFoundException(__('Invalid holiday'));
		}
		$options = array('conditions' => array('Holiday.' . $this->Holiday->primaryKey => $id));
		$this->set('holiday', $this->Holiday->find('first', $options));
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
			$this->request->data['Holiday']['system_table_id'] = $this->_get_system_table_id();
			

			// Configure::write('debug',1);
			// debug($this->request->data);
			if($this->request->data['Holiday']['date'] == '' && $this->request->data['Holiday']['date_range'] == ''){
				$this->Session->setFlash(__('Fill atleast one field'));
				$this->redirect(array('action' => 'lists'));
			}
			// exit;

			if($this->request->data['Holiday']['date']){
				$this->request->data['Holiday']['date'] = date('Y-m-d',strtotime($this->request->data['Holiday']['date']));
				$this->Holiday->create();
				$this->Holiday->save($this->request->data);

				$this->Session->setFlash(__('The holiday has been saved'));
			    $this->redirect(array('action' => 'index'));

			}elseif($this->request->data['Holiday']['date_range']){
				$dates = split(' - ', $this->request->data['Holiday']['date_range']);
				$startDate = date('Y-m-d',strtotime($dates[0]));
				$endDate = date('Y-m-d',strtotime($dates[1]));
				// debug($startDate);
				// debug($endDate);
				while (strtotime($startDate) <= strtotime($endDate)) {
					// debug($startDate);
					$this->request->data['Holiday']['date'] = date('Y-m-d',strtotime($startDate));
					$this->Holiday->create();
					$this->Holiday->save($this->request->data);
			        
			        $startDate = date("Y-m-d", strtotime("+1 day", strtotime($startDate)));
			    }

			    $this->Session->setFlash(__('The holiday has been saved'));
			    $this->redirect(array('action' => 'index'));
			}
			// exit;
			// if ($this->Holiday->save($this->request->data)) {


			// 	if ($this->_show_approvals()) $this->_save_approvals();
			// 	$this->Session->setFlash(__('The holiday has been saved'));
			// 	if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Holiday->id));
			// 	else $this->redirect(array('action' => 'index'));
			// } else {
			// 	$this->Session->setFlash(__('The holiday could not be saved. Please, try again.'));
			// }
		}
		$systemTables = $this->Holiday->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Holiday->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->Holiday->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Holiday->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Holiday->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Holiday->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->Holiday->find('count');
		$published = $this->Holiday->find('count',array('conditions'=>array('Holiday.publish'=>1)));
		$unpublished = $this->Holiday->find('count',array('conditions'=>array('Holiday.publish'=>0)));
			
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
                        $this->request->data['Holiday']['system_table_id'] = $this->_get_system_table_id();
			$this->Holiday->create();
			if ($this->Holiday->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Holiday';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Holiday->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The holiday has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Holiday->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The holiday could not be saved. Please, try again.'));
			}
		}
		$systemTables = $this->Holiday->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Holiday->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->Holiday->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Holiday->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Holiday->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Holiday->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->Holiday->find('count');
	$published = $this->Holiday->find('count',array('conditions'=>array('Holiday.publish'=>1)));
	$unpublished = $this->Holiday->find('count',array('conditions'=>array('Holiday.publish'=>0)));
		
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
		if (!$this->Holiday->exists($id)) {
			throw new NotFoundException(__('Invalid holiday'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['Holiday']['system_table_id'] = $this->_get_system_table_id();
			if ($this->Holiday->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The holiday could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Holiday.' . $this->Holiday->primaryKey => $id));
			$this->request->data = $this->Holiday->find('first', $options);
		}
		$systemTables = $this->Holiday->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Holiday->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->Holiday->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Holiday->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Holiday->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Holiday->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->Holiday->find('count');
		$published = $this->Holiday->find('count',array('conditions'=>array('Holiday.publish'=>1)));
		$unpublished = $this->Holiday->find('count',array('conditions'=>array('Holiday.publish'=>0)));
		
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
		if (!$this->Holiday->exists($id)) {
			throw new NotFoundException(__('Invalid holiday'));
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
			if ($this->Holiday->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->Holiday->save($this->request->data)) {
                $this->Session->setFlash(__('The holiday has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The holiday could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The holiday could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Holiday.' . $this->Holiday->primaryKey => $id));
			$this->request->data = $this->Holiday->find('first', $options);
		}
		$systemTables = $this->Holiday->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Holiday->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->Holiday->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Holiday->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Holiday->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Holiday->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->Holiday->find('count');
		$published = $this->Holiday->find('count',array('conditions'=>array('Holiday.publish'=>1)));
		$unpublished = $this->Holiday->find('count',array('conditions'=>array('Holiday.publish'=>0)));
		
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
		$this->Holiday->id = $id;
		if (!$this->Holiday->exists()) {
			throw new NotFoundException(__('Invalid holiday'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Holiday->delete()) {
			$this->Session->setFlash(__('Holiday deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Holiday was not deleted'));
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
		
		$result = explode('+',$this->request->data['holidays']['rec_selected']);
		$this->Holiday->recursive = 1;
		$holidays = $this->Holiday->find('all',array('Holiday.publish'=>1,'Holiday.soft_delete'=>1,'conditions'=>array('or'=>array('Holiday.id'=>$result))));
		$this->set('holidays', $holidays);
		
				$systemTables = $this->Holiday->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Holiday->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->Holiday->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Holiday->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Holiday->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Holiday->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

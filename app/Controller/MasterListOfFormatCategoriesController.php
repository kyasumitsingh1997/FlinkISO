<?php
App::uses('AppController', 'Controller');
/**
 * MasterListOfFormatCategories Controller
 *
 * @property MasterListOfFormatCategory $MasterListOfFormatCategory
 * @property PaginatorComponent $Paginator
 */
class MasterListOfFormatCategoriesController extends AppController {

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
		$this->paginate = array('order'=>array('MasterListOfFormatCategory.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->MasterListOfFormatCategory->recursive = 0;
		$masterListOfFormatCategories = $this->paginate();
		foreach ($masterListOfFormatCategories as $masterListOfFormatCategory) {
			$masterListOfFormats = $this->MasterListOfFormatCategory->MasterListOfFormat->find('count',array('conditions'=>array('MasterListOfFormat.master_list_of_format_category_id'=>$masterListOfFormatCategory['MasterListOfFormatCategory']['id'])));
			$masterListOfFormatCategory['Forms'] = $masterListOfFormats;
			$newMasterListOfFormatCategories[] = $masterListOfFormatCategory;
		}
		$this->set('masterListOfFormatCategories', $newMasterListOfFormatCategories);
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
		$this->paginate = array('order'=>array('MasterListOfFormatCategory.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->MasterListOfFormatCategory->recursive = 0;
		$this->set('masterListOfFormatCategories', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['MasterListOfFormatCategory']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['MasterListOfFormatCategory']['search_field'] as $search):
				$search_array[] = array('MasterListOfFormatCategory.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('MasterListOfFormatCategory.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->MasterListOfFormatCategory->recursive = 0;
		$this->paginate = array('order'=>array('MasterListOfFormatCategory.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'MasterListOfFormatCategory.soft_delete'=>0 , $cons));
		$this->set('masterListOfFormatCategories', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('MasterListOfFormatCategory.'.$search => $search_key);
					else $search_array[] = array('MasterListOfFormatCategory.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('MasterListOfFormatCategory.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('MasterListOfFormatCategory.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'MasterListOfFormatCategory.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('MasterListOfFormatCategory.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('MasterListOfFormatCategory.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->MasterListOfFormatCategory->recursive = 0;
		$this->paginate = array('order'=>array('MasterListOfFormatCategory.sr_no'=>'DESC'),'conditions'=>$conditions , 'MasterListOfFormatCategory.soft_delete'=>0 );
		$this->set('masterListOfFormatCategories', $this->paginate());
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
		if (!$this->MasterListOfFormatCategory->exists($id)) {
			throw new NotFoundException(__('Invalid master list of format category'));
		}
		$options = array('conditions' => array('MasterListOfFormatCategory.' . $this->MasterListOfFormatCategory->primaryKey => $id));
		$this->set('masterListOfFormatCategory', $this->MasterListOfFormatCategory->find('first', $options));
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
			$this->request->data['MasterListOfFormatCategory']['system_table_id'] = $this->_get_system_table_id();
			$this->MasterListOfFormatCategory->create();
			if ($this->MasterListOfFormatCategory->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The master list of format category has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->MasterListOfFormatCategory->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The master list of format category could not be saved. Please, try again.'));
			}
		}
		$systemTables = $this->MasterListOfFormatCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MasterListOfFormatCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list',array('conditions'=>array('MasterListOfFormatCategory.publish'=>1,'MasterListOfFormatCategory.soft_delete'=>0)));
		$companies = $this->MasterListOfFormatCategory->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->MasterListOfFormatCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->MasterListOfFormatCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->MasterListOfFormatCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MasterListOfFormatCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$standards = $this->MasterListOfFormatCategory->Standard->find('list',array('conditions'=>array('Standard.publish'=>1,'Standard.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','standards','masterListOfFormatCategories'));
		
		$count = $this->MasterListOfFormatCategory->find('count');
		$published = $this->MasterListOfFormatCategory->find('count',array('conditions'=>array('MasterListOfFormatCategory.publish'=>1)));
		$unpublished = $this->MasterListOfFormatCategory->find('count',array('conditions'=>array('MasterListOfFormatCategory.publish'=>0)));
			
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
                        $this->request->data['MasterListOfFormatCategory']['system_table_id'] = $this->_get_system_table_id();
			$this->MasterListOfFormatCategory->create();
			if ($this->MasterListOfFormatCategory->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='MasterListOfFormatCategory';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->MasterListOfFormatCategory->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The master list of format category has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->MasterListOfFormatCategory->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The master list of format category could not be saved. Please, try again.'));
			}
		}
		$systemTables = $this->MasterListOfFormatCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MasterListOfFormatCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->MasterListOfFormatCategory->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->MasterListOfFormatCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->MasterListOfFormatCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->MasterListOfFormatCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MasterListOfFormatCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	$count = $this->MasterListOfFormatCategory->find('count');
	$published = $this->MasterListOfFormatCategory->find('count',array('conditions'=>array('MasterListOfFormatCategory.publish'=>1)));
	$unpublished = $this->MasterListOfFormatCategory->find('count',array('conditions'=>array('MasterListOfFormatCategory.publish'=>0)));
		
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
		if (!$this->MasterListOfFormatCategory->exists($id)) {
			throw new NotFoundException(__('Invalid master list of format category'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['MasterListOfFormatCategory']['system_table_id'] = $this->_get_system_table_id();
			if ($this->MasterListOfFormatCategory->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The master list of format category could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MasterListOfFormatCategory.' . $this->MasterListOfFormatCategory->primaryKey => $id));
			$this->request->data = $this->MasterListOfFormatCategory->find('first', $options);
		}
		
		$systemTables = $this->MasterListOfFormatCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MasterListOfFormatCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list',array('conditions'=>array('MasterListOfFormatCategory.publish'=>1,'MasterListOfFormatCategory.soft_delete'=>0)));
		$companies = $this->MasterListOfFormatCategory->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->MasterListOfFormatCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->MasterListOfFormatCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->MasterListOfFormatCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MasterListOfFormatCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$standards = $this->MasterListOfFormatCategory->Standard->find('list',array('conditions'=>array('Standard.publish'=>1,'Standard.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','standards','masterListOfFormatCategories'));
		
		$count = $this->MasterListOfFormatCategory->find('count');
		$published = $this->MasterListOfFormatCategory->find('count',array('conditions'=>array('MasterListOfFormatCategory.publish'=>1)));
		$unpublished = $this->MasterListOfFormatCategory->find('count',array('conditions'=>array('MasterListOfFormatCategory.publish'=>0)));
			
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
		if (!$this->MasterListOfFormatCategory->exists($id)) {
			throw new NotFoundException(__('Invalid master list of format category'));
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
			if ($this->MasterListOfFormatCategory->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->MasterListOfFormatCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The master list of format category has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The master list of format category could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The master list of format category could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('MasterListOfFormatCategory.' . $this->MasterListOfFormatCategory->primaryKey => $id));
			$this->request->data = $this->MasterListOfFormatCategory->find('first', $options);
		}
		
		$systemTables = $this->MasterListOfFormatCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MasterListOfFormatCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list',array('conditions'=>array('MasterListOfFormatCategory.publish'=>1,'MasterListOfFormatCategory.soft_delete'=>0)));
		$companies = $this->MasterListOfFormatCategory->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->MasterListOfFormatCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->MasterListOfFormatCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->MasterListOfFormatCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MasterListOfFormatCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$standards = $this->MasterListOfFormatCategory->Standard->find('list',array('conditions'=>array('Standard.publish'=>1,'Standard.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','standards','masterListOfFormatCategories'));
		
		$count = $this->MasterListOfFormatCategory->find('count');
		$published = $this->MasterListOfFormatCategory->find('count',array('conditions'=>array('MasterListOfFormatCategory.publish'=>1)));
		$unpublished = $this->MasterListOfFormatCategory->find('count',array('conditions'=>array('MasterListOfFormatCategory.publish'=>0)));
			
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
		$this->MasterListOfFormatCategory->id = $id;
		if (!$this->MasterListOfFormatCategory->exists()) {
			throw new NotFoundException(__('Invalid master list of format category'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->MasterListOfFormatCategory->delete()) {
			$this->Session->setFlash(__('Master list of format category deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Master list of format category was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null,$parent_id = null) {
	
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
		
		$result = explode('+',$this->request->data['masterListOfFormatCategories']['rec_selected']);
		$this->MasterListOfFormatCategory->recursive = 1;
		$masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('all',array('MasterListOfFormatCategory.publish'=>1,'MasterListOfFormatCategory.soft_delete'=>1,'conditions'=>array('or'=>array('MasterListOfFormatCategory.id'=>$result))));
		$this->set('masterListOfFormatCategories', $masterListOfFormatCategories);
		
		$systemTables = $this->MasterListOfFormatCategory->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->MasterListOfFormatCategory->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->MasterListOfFormatCategory->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->MasterListOfFormatCategory->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->MasterListOfFormatCategory->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->MasterListOfFormatCategory->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->MasterListOfFormatCategory->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function get_categories($standard_id = null){
		$this->autoRender = false;
		$standard_id = $this->request->params['named']['standard_id'];
		if($standard_id){
			$masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list',array('conditions'=>array('MasterListOfFormatCategory.standard_id'=>$standard_id)));
			if($masterListOfFormatCategories){
				$con_str .= '<option value=-1>Select</option>' ;
				foreach ($masterListOfFormatCategories as $key => $value) {
					$con_str .= '<option value=' . $key .'>' . $value . '</option>' ;
				}
			}
		}
		return $con_str;
        exit; 
	}
}

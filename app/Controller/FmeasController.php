<?php
App::uses('AppController', 'Controller');
/**
 * Fmeas Controller
 *
 * @property Fmea $Fmea
 * @property PaginatorComponent $Paginator
 */
class FmeasController extends AppController {

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
		$this->paginate = array('order'=>array('Fmea.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->Fmea->recursive = 1;
		$this->set('fmeas', $this->paginate());
		
		$this->_get_count();

		$processes = $this->Fmea->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$designs = $this->Fmea->Design->find('list',array('conditions'=>array('Design.publish'=>1,'Design.soft_delete'=>0)));
		$products = $this->Fmea->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->Fmea->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->Fmea->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->Fmea->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->Fmea->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Fmea->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Fmea->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Fmea->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Fmea->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Fmea->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Fmea->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'designs', 'products', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}


 
/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('Fmea.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->Fmea->recursive = 0;
		$this->set('fmeas', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['Fmea']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['Fmea']['search_field'] as $search):
				$search_array[] = array('Fmea.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('Fmea.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->Fmea->recursive = 0;
		$this->paginate = array('order'=>array('Fmea.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Fmea.soft_delete'=>0 , $cons));
		$this->set('fmeas', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('Fmea.'.$search => $search_key);
					else $search_array[] = array('Fmea.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('Fmea.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('Fmea.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'Fmea.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Fmea.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Fmea.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->Fmea->recursive = 0;
		$this->paginate = array('order'=>array('Fmea.sr_no'=>'DESC'),'conditions'=>$conditions , 'Fmea.soft_delete'=>0 );
		$this->set('fmeas', $this->paginate());
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
		if (!$this->Fmea->exists($id)) {
			throw new NotFoundException(__('Invalid fmea'));
		}
		$options = array('conditions' => array('Fmea.' . $this->Fmea->primaryKey => $id));
		$this->set('fmea', $this->Fmea->find('first', $options));
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
			$this->request->data['Fmea']['system_table_id'] = $this->_get_system_table_id();
			$this->Fmea->create();
			if ($this->Fmea->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The fmea has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Fmea->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fmea could not be saved. Please, try again.'));
			}
		}
		
		$processes = $this->Fmea->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$designs = $this->Fmea->Design->find('list',array('conditions'=>array('Design.publish'=>1,'Design.soft_delete'=>0)));
		$products = $this->Fmea->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->Fmea->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->Fmea->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->Fmea->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->Fmea->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Fmea->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Fmea->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Fmea->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Fmea->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Fmea->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Fmea->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'designs', 'products', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	
		$count = $this->Fmea->find('count');
		$published = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>1)));
		$unpublished = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>0)));
			
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
                        $this->request->data['Fmea']['system_table_id'] = $this->_get_system_table_id();
			$this->Fmea->create();
			if ($this->Fmea->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Fmea';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Fmea->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The fmea has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Fmea->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fmea could not be saved. Please, try again.'));
			}
		}
		
		$processes = $this->Fmea->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$designs = $this->Fmea->Design->find('list',array('conditions'=>array('Design.publish'=>1,'Design.soft_delete'=>0)));
		$products = $this->Fmea->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->Fmea->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->Fmea->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->Fmea->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->Fmea->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Fmea->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Fmea->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Fmea->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Fmea->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Fmea->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Fmea->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'designs', 'products', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	
		$count = $this->Fmea->find('count');
		$published = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>1)));
		$unpublished = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>0)));
			
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
		if (!$this->Fmea->exists($id)) {
			throw new NotFoundException(__('Invalid fmea'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['Fmea']['system_table_id'] = $this->_get_system_table_id();
			if ($this->Fmea->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The fmea could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Fmea.' . $this->Fmea->primaryKey => $id));
			$this->request->data = $this->Fmea->find('first', $options);
		}
		
		$processes = $this->Fmea->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$designs = $this->Fmea->Design->find('list',array('conditions'=>array('Design.publish'=>1,'Design.soft_delete'=>0)));
		$products = $this->Fmea->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->Fmea->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->Fmea->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->Fmea->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->Fmea->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Fmea->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Fmea->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Fmea->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Fmea->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Fmea->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Fmea->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'designs', 'products', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	
		$count = $this->Fmea->find('count');
		$published = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>1)));
		$unpublished = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>0)));
			
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
		if (!$this->Fmea->exists($id)) {
			throw new NotFoundException(__('Invalid fmea'));
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
			if ($this->Fmea->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->Fmea->save($this->request->data)) {
                $this->Session->setFlash(__('The fmea has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The fmea could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The fmea could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Fmea.' . $this->Fmea->primaryKey => $id));
			$this->request->data = $this->Fmea->find('first', $options);
		}
		
		$processes = $this->Fmea->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$designs = $this->Fmea->Design->find('list',array('conditions'=>array('Design.publish'=>1,'Design.soft_delete'=>0)));
		$products = $this->Fmea->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->Fmea->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->Fmea->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->Fmea->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->Fmea->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Fmea->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Fmea->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Fmea->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Fmea->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Fmea->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Fmea->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'designs', 'products', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	
		$count = $this->Fmea->find('count');
		$published = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>1)));
		$unpublished = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>0)));
			
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
		$this->Fmea->id = $id;
		if (!$this->Fmea->exists()) {
			throw new NotFoundException(__('Invalid fmea'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Fmea->delete()) {
			$this->Session->setFlash(__('Fmea deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Fmea was not deleted'));
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
		
		$result = explode('+',$this->request->data['fmeas']['rec_selected']);
		$this->Fmea->recursive = 1;
		$fmeas = $this->Fmea->find('all',array('Fmea.publish'=>1,'Fmea.soft_delete'=>1,'conditions'=>array('or'=>array('Fmea.id'=>$result))));
		$this->set('fmeas', $fmeas);
		
		$processes = $this->Fmea->Process->find('list',array('conditions'=>array('Process.publish'=>1,'Process.soft_delete'=>0)));
		$designs = $this->Fmea->Design->find('list',array('conditions'=>array('Design.publish'=>1,'Design.soft_delete'=>0)));
		$products = $this->Fmea->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		$fmeaSeverityTypes = $this->Fmea->FmeaSeverityType->find('list',array('conditions'=>array('FmeaSeverityType.publish'=>1,'FmeaSeverityType.soft_delete'=>0)));
		$fmeaOccurences = $this->Fmea->FmeaOccurence->find('list',array('conditions'=>array('FmeaOccurence.publish'=>1,'FmeaOccurence.soft_delete'=>0)));
		$fmeaDetections = $this->Fmea->FmeaDetection->find('list',array('conditions'=>array('FmeaDetection.publish'=>1,'FmeaDetection.soft_delete'=>0)));
		$systemTables = $this->Fmea->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Fmea->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->Fmea->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->Fmea->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->Fmea->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->Fmea->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Fmea->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('processes', 'designs', 'products', 'fmeaSeverityTypes', 'fmeaOccurences', 'fmeaDetections', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	
		$count = $this->Fmea->find('count');
		$published = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>1)));
		$unpublished = $this->Fmea->find('count',array('conditions'=>array('Fmea.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));
	}

	public function getvals($s = null, $o = null, $d = null){
		$this->autoRender = false;
		
		$sRanking = $oRanking = $dRanking = 1;
		$total = 0;
		
		if($s != -1){
			$fmeaSeverityType = $this->Fmea->FmeaSeverityType->find('first',array('recursive'=>-1, 'conditions'=>array('FmeaSeverityType.id'=>$s)));
			$sRanking = $fmeaSeverityType['FmeaSeverityType']['ranking'];
		}
		if($o != -1){
			$fmeaOccurence = $this->Fmea->FmeaOccurence->find('first',array('recursive'=>-1, 'conditions'=>array('FmeaOccurence.id'=>$o)));
			$oRanking = $fmeaOccurence['FmeaOccurence']['ranking'];
		}
		if($d != -1){
			$fmeaDetection = $this->Fmea->FmeaDetection->find('first',array('recursive'=>-1, 'conditions'=>array('FmeaDetection.id'=>$d)));
			$dRanking = $fmeaDetection['FmeaDetection']['ranking'];
		}

		$total = $sRanking * $oRanking * $dRanking;
		// $total = $total/3;
		return $total;
		exit;

	}
}

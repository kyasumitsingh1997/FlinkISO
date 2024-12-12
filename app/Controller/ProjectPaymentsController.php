<?php
App::uses('AppController', 'Controller');
/**
 * ProjectPayments Controller
 *
 * @property ProjectPayment $ProjectPayment
 * @property PaginatorComponent $Paginator
 */
class ProjectPaymentsController extends AppController {

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
		$this->paginate = array('order'=>array('ProjectPayment.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ProjectPayment->recursive = 0;
		$this->set('projectPayments', $this->paginate());
		
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
		$this->paginate = array('order'=>array('ProjectPayment.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProjectPayment->recursive = 0;
		$this->set('projectPayments', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProjectPayment']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProjectPayment']['search_field'] as $search):
				$search_array[] = array('ProjectPayment.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProjectPayment.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProjectPayment->recursive = 0;
		$this->paginate = array('order'=>array('ProjectPayment.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProjectPayment.soft_delete'=>0 , $cons));
		$this->set('projectPayments', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProjectPayment.'.$search => $search_key);
					else $search_array[] = array('ProjectPayment.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProjectPayment.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProjectPayment.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProjectPayment.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProjectPayment.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProjectPayment.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProjectPayment->recursive = 0;
		$this->paginate = array('order'=>array('ProjectPayment.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProjectPayment.soft_delete'=>0 );
		$this->set('projectPayments', $this->paginate());
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
		if (!$this->ProjectPayment->exists($id)) {
			throw new NotFoundException(__('Invalid project payment'));
		}
		$options = array('conditions' => array('ProjectPayment.' . $this->ProjectPayment->primaryKey => $id));
		$this->set('projectPayment', $this->ProjectPayment->find('first', $options));
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
			$this->request->data['ProjectPayment']['system_table_id'] = $this->_get_system_table_id();
			$this->request->data['ProjectPayment']['received_date'] = date('Y-m-d',strtotime($this->request->data['ProjectPayment']['received_date']));
			$this->ProjectPayment->create();
			if ($this->ProjectPayment->save($this->request->data)) {


				// if ($this->_show_approvals()) $this->_save_approvals();
				// $this->Session->setFlash(__('The project payment has been saved'));
				// if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectPayment->id));
				// else $this->redirect(array('action' => 'index'));
				if($this->request->data['ProjectPayment']['project_id'])$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->data['ProjectPayment']['project_id']));
			} else {
				$this->Session->setFlash(__('The project payment could not be saved. Please, try again.'));
			}
		}
		$projects = $this->ProjectPayment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectPayment->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		
		$purchaseOrders = $this->ProjectPayment->PurchaseOrder->find('list',array('conditions'=>array(
			'PurchaseOrder.project_id'=>$this->request->params['named']['project_id'], 
			'PurchaseOrder.milestone_id'=>$this->request->params['named']['milestone_id'], 
			'PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));

		$invoices = $this->ProjectPayment->Invoice->find('list',array('fields'=>array('Invoice.id','Invoice.invoice_number'), 'conditions'=>array('Invoice.publish'=>1,'Invoice.soft_delete'=>0)));
		$systemTables = $this->ProjectPayment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectPayment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectPayment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectPayment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectPayment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectPayment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'purchaseOrders', 'invoices', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectPayment->find('count');
		$published = $this->ProjectPayment->find('count',array('conditions'=>array('ProjectPayment.publish'=>1)));
		$unpublished = $this->ProjectPayment->find('count',array('conditions'=>array('ProjectPayment.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

		$this->set('project_id',$this->request->params['named']['project_id']);
		$this->set('milestone_id',$this->request->params['named']['milestone_id']);


		// get payment history

		$this->ProjectPayment->Project->PurchaseOrder->virtualFields = array(
			'po_total'=>'select SUM(total) from purchase_order_details where `purchase_order_details`.`purchase_order_id` LIKE PurchaseOrder.id'
		);

		$project_details['PurchaseOrder']['out'] = $this->ProjectPayment->Project->PurchaseOrder->find('all',array('conditions'=>array('PurchaseOrder.project_id'=>$this->request->params['named']['project_id'],'PurchaseOrder.type' => 1),'recursive'=>-1));

		$project_details['PurchaseOrder']['in'] = $this->ProjectPayment->Project->PurchaseOrder->find('all',array('conditions'=>array('PurchaseOrder.project_id'=>$this->request->params['named']['project_id'], 'PurchaseOrder.type' => 0),'recursive'=>-1));

		

		$project_details['ProjectPayment'] = $this->ProjectPayment->Project->ProjectPayment->find('all',array(
			'recursive'=>-1,
			'conditions'=>array('ProjectPayment.project_id'=>$this->request->params['named']['project_id'])
		));

		$project_details['Invoice'] = $this->ProjectPayment->Project->Invoice->find('all',array('conditions'=>array(
			'Invoice.project_id'=>$this->request->params['named']['project_id'],
			// 'Invoice.milestone_id'=>$milestone['Milestone']['id']
		),'recursive'=>-1));

		$this->set('project_details',$project_details);		

	}


	public function get_po_total($po_id = null){
		$this->autoRender = false;
		$this->loadModel('PurchaseOrder');
		$po_id =  $this->request->params['named']['po_id'];
		
		$this->PurchaseOrder->virtualFields = array(
			'po_total'=>'select SUM(total) from purchase_order_details where `purchase_order_details`.`purchase_order_id` LIKE PurchaseOrder.id'
		);

		$po = $this->PurchaseOrder->find('first',array(
			'recursive'=>-1,
			'fields'=>array('PurchaseOrder.id','PurchaseOrder.po_total'),
			'conditions'=>array('PurchaseOrder.id'=>$po_id)
		));

		if($po){
			return $po['PurchaseOrder']['po_total'];
		}else{
			return 0;
		}

		exit;
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
	// 		$this->request->data['ProjectPayment']['system_table_id'] = $this->_get_system_table_id();

	// 		$this->request->data['ProjectPayment']['received_date'] = date('Y-m-d',strtotime($this->request->data['ProjectPayment']['received_date']));
	// 		$this->ProjectPayment->create();
	// 		if ($this->ProjectPayment->save($this->request->data)) {

	// 			if($this->_show_approvals()){
	// 				$this->loadModel('Approval');
	// 				$this->Approval->create();
	// 				$this->request->data['Approval']['model_name']='ProjectPayment';
	// 				$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
	// 				$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
	// 				$this->request->data['Approval']['from']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
	// 				$this->request->data['Approval']['record']=$this->ProjectPayment->id;
	// 				$this->Approval->save($this->request->data['Approval']);
	// 			}
	// 			$this->Session->setFlash(__('The project payment has been saved'));
	// 			if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProjectPayment->id));
	// 			else $this->redirect(array('action' => 'index'));
	// 		} else {
	// 			$this->Session->setFlash(__('The project payment could not be saved. Please, try again.'));
	// 		}
	// 	}
	// 	$projects = $this->ProjectPayment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
	// 	$milestones = $this->ProjectPayment->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
	// 	$purchaseOrders = $this->ProjectPayment->PurchaseOrder->find('list',array('conditions'=>array('PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));
	// 	$invoices = $this->ProjectPayment->Invoice->find('list',array('conditions'=>array('Invoice.publish'=>1,'Invoice.soft_delete'=>0)));
	// 	$systemTables = $this->ProjectPayment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
	// 	$masterListOfFormats = $this->ProjectPayment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
	// 	$preparedBies = $this->ProjectPayment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
	// 	$approvedBies = $this->ProjectPayment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
	// 	$createdBies = $this->ProjectPayment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
	// 	$modifiedBies = $this->ProjectPayment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
	// 	$deliverableUnits = $this->ProjectPayment->Project->DeliverableUnit->find('list',array('conditions'=>array('DeliverableUnit.publish'=>1,'DeliverableUnit.soft_delete'=>0)));
	// 	$this->set(compact('projects', 'milestones', 'purchaseOrders', 'invoices', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','deliverableUnits'));
	// 	$count = $this->ProjectPayment->find('count');
	// 	$published = $this->ProjectPayment->find('count',array('conditions'=>array('ProjectPayment.publish'=>1)));
	// 	$unpublished = $this->ProjectPayment->find('count',array('conditions'=>array('ProjectPayment.publish'=>0)));

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
		if (!$this->ProjectPayment->exists($id)) {
			throw new NotFoundException(__('Invalid project payment'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProjectPayment']['system_table_id'] = $this->_get_system_table_id();

			$this->request->data['ProjectPayment']['received_date'] = date('Y-m-d',strtotime($this->request->data['ProjectPayment']['received_date']));
			if ($this->ProjectPayment->save($this->request->data)) {

				// if ($this->_show_approvals()) $this->_save_approvals();
				
				// if ($this->_show_evidence() == true)
				//  $this->redirect(array('action' => 'view', $id));
				// else
		 	// 		$this->redirect(array('action' => 'index'));
				if($this->request->data['ProjectPayment']['project_id'])$this->redirect(array('controller'=>'projects', 'action' => 'view',$this->request->data['ProjectPayment']['project_id']));
			} else {
				$this->Session->setFlash(__('The project payment could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectPayment.' . $this->ProjectPayment->primaryKey => $id));
			$this->request->data = $this->ProjectPayment->find('first', $options);
		}
		$projects = $this->ProjectPayment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectPayment->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$purchaseOrders = $this->ProjectPayment->PurchaseOrder->find('list',array('conditions'=>array('PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));
		$invoices = $this->ProjectPayment->Invoice->find('list',array('conditions'=>array('Invoice.publish'=>1,'Invoice.soft_delete'=>0)));
		$systemTables = $this->ProjectPayment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectPayment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectPayment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectPayment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectPayment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectPayment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$deliverableUnits = $this->ProjectPayment->DeliverableUnit->find('list',array('conditions'=>array('DeliverableUnit.publish'=>1,'DeliverableUnit.soft_delete'=>0)));
		print_r($deliverableUnits);

		$this->set(compact('projects', 'milestones', 'purchaseOrders', 'invoices', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies','deliverableUnits'));
		$count = $this->ProjectPayment->find('count');
		$published = $this->ProjectPayment->find('count',array('conditions'=>array('ProjectPayment.publish'=>1)));
		$unpublished = $this->ProjectPayment->find('count',array('conditions'=>array('ProjectPayment.publish'=>0)));
		
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
		if (!$this->ProjectPayment->exists($id)) {
			throw new NotFoundException(__('Invalid project payment'));
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
			if ($this->ProjectPayment->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProjectPayment->save($this->request->data)) {
                $this->Session->setFlash(__('The project payment has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The project payment could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The project payment could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProjectPayment.' . $this->ProjectPayment->primaryKey => $id));
			$this->request->data = $this->ProjectPayment->find('first', $options);
		}
		$projects = $this->ProjectPayment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectPayment->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$purchaseOrders = $this->ProjectPayment->PurchaseOrder->find('list',array('conditions'=>array('PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));
		$invoices = $this->ProjectPayment->Invoice->find('list',array('conditions'=>array('Invoice.publish'=>1,'Invoice.soft_delete'=>0)));
		$systemTables = $this->ProjectPayment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectPayment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectPayment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectPayment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectPayment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectPayment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'purchaseOrders', 'invoices', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->ProjectPayment->find('count');
		$published = $this->ProjectPayment->find('count',array('conditions'=>array('ProjectPayment.publish'=>1)));
		$unpublished = $this->ProjectPayment->find('count',array('conditions'=>array('ProjectPayment.publish'=>0)));
		
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
		$this->ProjectPayment->id = $id;
		if (!$this->ProjectPayment->exists()) {
			throw new NotFoundException(__('Invalid project payment'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProjectPayment->delete()) {
			$this->Session->setFlash(__('Project payment deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Project payment was not deleted'));
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
		
		$result = explode('+',$this->request->data['projectPayments']['rec_selected']);
		$this->ProjectPayment->recursive = 1;
		$projectPayments = $this->ProjectPayment->find('all',array('ProjectPayment.publish'=>1,'ProjectPayment.soft_delete'=>1,'conditions'=>array('or'=>array('ProjectPayment.id'=>$result))));
		$this->set('projectPayments', $projectPayments);
		
				$projects = $this->ProjectPayment->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
		$milestones = $this->ProjectPayment->Milestone->find('list',array('conditions'=>array('Milestone.publish'=>1,'Milestone.soft_delete'=>0)));
		$purchaseOrders = $this->ProjectPayment->PurchaseOrder->find('list',array('conditions'=>array('PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));
		$invoices = $this->ProjectPayment->Invoice->find('list',array('conditions'=>array('Invoice.publish'=>1,'Invoice.soft_delete'=>0)));
		$systemTables = $this->ProjectPayment->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProjectPayment->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$preparedBies = $this->ProjectPayment->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProjectPayment->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProjectPayment->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProjectPayment->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('projects', 'milestones', 'purchaseOrders', 'invoices', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'projects', 'milestones', 'purchaseOrders', 'invoices', 'systemTables', 'masterListOfFormats', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

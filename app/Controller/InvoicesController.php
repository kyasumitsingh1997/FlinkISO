<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
/**
 * Invoices Controller
 *
 * @property Invoice $Invoice
 */
class InvoicesController extends AppController {

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
		$this->paginate = array('order'=>array('Invoice.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->Invoice->recursive = 0;
		$this->set('invoices', $this->paginate());
		
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
		$this->paginate = array('order'=>array('Invoice.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->Invoice->recursive = 0;
		$this->set('invoices', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['Invoice']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['Invoice']['search_field'] as $search):
				$search_array[] = array('Invoice.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('Invoice.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->Invoice->recursive = 0;
		$this->paginate = array('order'=>array('Invoice.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'Invoice.soft_delete'=>0 , $cons));
		$this->set('invoices', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('Invoice.'.$search => $search_key);
					else $search_array[] = array('Invoice.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('Invoice.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('Invoice.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'Invoice.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('Invoice.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('Invoice.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->Invoice->recursive = 0;
		$this->paginate = array('order'=>array('Invoice.sr_no'=>'DESC'),'conditions'=>$conditions , 'Invoice.soft_delete'=>0 );
		if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
		$this->set('invoices', $this->paginate());
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
		if (!$this->Invoice->exists($id)) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		$options = array('conditions' => array('Invoice.' . $this->Invoice->primaryKey => $id));
		$this->set('invoice', $this->Invoice->find('first', $options));

		$this->loadModel('InvoiceSetting');
		$invoice_settings = $this->InvoiceSetting->find('first');
		$this->set('invoice_settings',$invoice_settings);
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
		
        if($this->request->params['pass'][0] && !$this->request->params['named']['project_id']){
        	$options = array('conditions' => array('PurchaseOrder.' . $this->Invoice->PurchaseOrder->primaryKey => $this->request->params['pass'][0]));
        	$purchaseOrder = $this->Invoice->PurchaseOrder->find('first', $options);
	        $this->set('purchaseOrder', $purchaseOrder);
	        $purchaseOrderDetails = $this->Invoice->PurchaseOrder->PurchaseOrderDetail->find('all', array('conditions' => array('PurchaseOrderDetail.purchase_order_id ' => $this->request->params['pass'][0])));
	        $this->set('purchaseOrderDetails', $purchaseOrderDetails);	

	        $customerContacts = $this->Invoice->CustomerContact->find('list',array('conditions'=>array('CustomerContact.publish'=>1,'CustomerContact.soft_delete'=>0,'CustomerContact.customer_id'=>$purchaseOrder['PurchaseOrder']['customer_id'])));
        }
	
		$this->loadModel('Project');			
        
        if($this->request->params['named']['project_id']){
        	$project = $this->Project->find('first',array(
        		'recursive'=>0,
        		'fields'=>array(
        			'Project.id',
        			'Project.title',
        			'Project.estimated_project_cost',
        			'Project.estimated_resource_cost',
        			'Customer.id',
        			'Customer.name',
        			'Customer.residence_address',
        			'Customer.customer_code',
        		),
        		'conditions'=>array('Project.id'=>$this->request->params['named']['project_id'])
        	));

        	if($project){
        		$customerContacts = $this->Project->Customer->CustomerContact->find('list',array(
        			'conditions'=>array('CustomerContact.customer_id'=>$project['Customer']['id'])
        		));
        	}

        	$this->set('project',$project);

        	$milestones = $this->Project->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$this->request->params['named']['project_id'])));
        	$this->set('milestones',$milestones);

        	$projectProcessPlans = $this->Invoice->InvoiceDetail->ProjectProcessPlan->find(
        		'list',array(
        			'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.process'),
        			'conditions'=>array(
        				'ProjectProcessPlan.project_id'=>$this->request->params['named']['project_id']
        			)
        		)
        	);
        	$projectProcessPlanRates = $this->Invoice->InvoiceDetail->ProjectProcessPlan->find(
        		'list',array(
        			'fields'=>array('ProjectProcessPlan.id','ProjectProcessPlan.unit_rate'),
        			'conditions'=>array(
        				'ProjectProcessPlan.project_id'=>$this->request->params['named']['project_id']
        			)
        		)
        	);
        	$this->set('projectProcessPlans',$projectProcessPlans);
        	$this->set('projectProcessPlanRates',$projectProcessPlanRates);
        	
        	// get units completed per process
        	$this->loadModel('FileProcess');

        	$this->FileProcess->virtualFields = array(
        			'toal_units' => 'select SUM(units_completed) from file_processes where file_processes.project_process_plan_id LIKE FileProcess.project_process_plan_id'
        		);

        	foreach($projectProcessPlans as $key => $process){
        		$res = $this->FileProcess->find('first',array(
        			'fields'=>array('FileProcess.project_process_plan_id','FileProcess.toal_units'),
        			'conditions'=>array(
        				'FileProcess.project_process_plan_id'=>$key,
        				'FileProcess.project_id'=>$this->request->params['named']['project_id'])
        		));

        		if($res['FileProcess']['toal_units'])$unitsCompleted[$key] = $res['FileProcess']['toal_units'];
        		else $unitsCompleted[$key] = 0;
        	}
        	// Configure::Write('debug',1);
        	// debug($unitsCompleted);

        	$this->set('unitsCompleted',$unitsCompleted);
        	
        	$currencies = $this->Invoice->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
        	$this->set('currencies',$currencies);
        	
        	$projectCurrency = $this->Invoice->Project->find('first',array('recursive'=>-1,'fields'=>array('Project.id','Project.currency_id'), 'conditions'=>array('Project.id'=>$this->request->params['named']['project_id'])));
		
		$this->set('projectCurrency',$projectCurrency['Project']['currency_id']);
        }

        
        if ($this->request->is('post')) {

        	// Configure::write('debug',1);
        	// debug($this->request->data);
        	// exit;

			$this->request->data['Invoice']['system_table_id'] = $this->_get_system_table_id();
			$this->Invoice->create();

			if ($this->Invoice->save($this->request->data,false)) {
				$this->loadModel('InvoiceDetail');
				foreach($this->request->data['InvoiceDetail'] as $invoice_details){
					if($invoice_details['total'] > 0){
						$data['InvoiceDetail'] = $invoice_details;
						$data['InvoiceDetail']['invoice_id']= $this->Invoice->id;
						$data['InvoiceDetail']['prepared_by']= $this->request->data['Invoice']['prepared_by'];
						$data['InvoiceDetail']['approved_by']= $this->request->data['Invoice']['approved_by'];
						$data['InvoiceDetail']['publish']= $this->request->data['Invoice']['publish'];
						$data['InvoiceDetail']['record_status']= $this->request->data['Invoice']['record_status'];
						$data['InvoiceDetail']['soft_delete']= $this->request->data['Invoice']['soft_delete'];
						$data['InvoiceDetail']['master_list_of_format_id']= $this->request->data['Invoice']['master_list_of_format_id'];
						$data['InvoiceDetail']['system_table_id']= $this->request->data['Invoice']['system_table_id'];
						$data['InvoiceDetail']['devision_id']= $this->request->data['Invoice']['devision_id'];
						$this->InvoiceDetail->create();
						$this->InvoiceDetail->save($data,false);	
					}
					
				}		

				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The invoice has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Invoice->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The invoice could not be saved. Please, try again.'));
			}
		}
		
		$purchaseOrders = $this->Invoice->PurchaseOrder->find('list',array('conditions'=>array('PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));
		$customers = $this->Invoice->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		
		$systemTables = $this->Invoice->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Invoice->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->Invoice->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->Invoice->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->Invoice->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Invoice->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('purchaseOrders', 'customers', 'customerContacts', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies'));
		$count = $this->Invoice->find('count');
		$published = $this->Invoice->find('count',array('conditions'=>array('Invoice.publish'=>1)));
		$unpublished = $this->Invoice->find('count',array('conditions'=>array('Invoice.publish'=>0)));

		$this->loadModel('InvoiceSetting');
		$invoice_settings = $this->InvoiceSetting->find('first');
		$this->set('invoice_settings',$invoice_settings);
		
		$this->set(compact('count','published','unpublished'));

	}





/**
 * add method
 *
 * @return void
 */
	public function add($id=null) {
		echo $this->request->params['pass'][0];
		if($this->_show_approvals()){
			$this->loadModel('User');
			$this->User->recursive = 0;
			$userids = $this->User->find('list',array('order'=>array('User.name'=>'ASC'),'conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.is_approvar'=>1)));
			$this->set(array('userids'=>$userids,'show_approvals'=>$this->_show_approvals()));
		}
		
		$options = array('conditions' => array('PurchaseOrder.' . $this->PurchaseOrder->primaryKey => $id));
        $purchaseOrder = $this->PurchaseOrder->find('first', $options);
        $this->set('purchaseOrder', $purchaseOrder);
        $purchaseOrderDetails = $this->PurchaseOrder->PurchaseOrderDetail->find('all', array('conditions' => array('PurchaseOrderDetail.purchase_order_id ' => $id)));
        $this->set('purchaseOrderDetails', $purchaseOrderDetails);

		if ($this->request->is('post')) {
                        $this->request->data['Invoice']['system_table_id'] = $this->_get_system_table_id();
			$this->Invoice->create();
			if ($this->Invoice->save($this->request->data)) {

				if($this->_show_approvals()){
					$this->loadModel('Approval');
					$this->Approval->create();
					$this->request->data['Approval']['model_name']='Invoice';
					$this->request->data['Approval']['controller_name']=$this->request->params['controller'];
					$this->request->data['Approval']['user_id']=$this->request->data['Approval']['user_id'];
					$this->request->data['Approval']['from']=$this->Session->read('User.id');
					$this->request->data['Approval']['created_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['modified_by']=$this->Session->read('User.id');
					$this->request->data['Approval']['record']=$this->Invoice->id;
					$this->Approval->save($this->request->data['Approval']);
				}
				$this->Session->setFlash(__('The invoice has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->Invoice->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The invoice could not be saved. Please, try again.'));
			}
		}
		$purchaseOrders = $this->Invoice->PurchaseOrder->find('list',array('conditions'=>array('PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));
		$customers = $this->Invoice->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$customerContacts = $this->Invoice->CustomerContact->find('list',array('conditions'=>array('CustomerContact.publish'=>1,'CustomerContact.soft_delete'=>0,'CustomerContact.customer_id'=>$purchaseOrder['PurchaseOrder']['customer_id'])));
		$systemTables = $this->Invoice->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Invoice->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->Invoice->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->Invoice->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->Invoice->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Invoice->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
				$this->set(compact('purchaseOrders', 'customers', 'customerContacts', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies'));
	$count = $this->Invoice->find('count');
	$published = $this->Invoice->find('count',array('conditions'=>array('Invoice.publish'=>1)));
	$unpublished = $this->Invoice->find('count',array('conditions'=>array('Invoice.publish'=>0)));
		
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
		if (!$this->Invoice->exists($id)) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['Invoice']['system_table_id'] = $this->_get_system_table_id();
			if ($this->Invoice->save($this->request->data)) {
				$this->loadModel('InvoiceDetail');
				$this->InvoiceDetail->deleteAll(array('InvoiceDetail.invoice_id'=>$id));
				foreach($this->request->data['InvoiceDetail'] as $invoice_details){
					if($invoice_details['invoice_details'] == 1){
						$data['InvoiceDetail'] = $invoice_details;
						$data['InvoiceDetail']['invoice_id']= $this->Invoice->id;
						$data['InvoiceDetail']['prepared_by']= $this->request->data['Invoice']['prepared_by'];
						$data['InvoiceDetail']['approved_by']= $this->request->data['Invoice']['approved_by'];
						$data['InvoiceDetail']['publish']= $this->request->data['Invoice']['publish'];
						$data['InvoiceDetail']['record_status']= $this->request->data['Invoice']['record_status'];
						$data['InvoiceDetail']['soft_delete']= $this->request->data['Invoice']['soft_delete'];
						$data['InvoiceDetail']['master_list_of_format_id']= $this->request->data['Invoice']['master_list_of_format_id'];
						$data['InvoiceDetail']['system_table_id']= $this->request->data['Invoice']['system_table_id'];
						$data['InvoiceDetail']['devision_id']= $this->request->data['Invoice']['devision_id'];
						$data['InvoiceDetail']['company_id']= $this->request->data['Invoice']['company_id'];
						$this->InvoiceDetail->create();
						$this->InvoiceDetail->save($data);
					}
				}	
				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The invoice could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Invoice.' . $this->Invoice->primaryKey => $id));
			$this->request->data = $this->Invoice->find('first', $options);


			$options = array('conditions' => array('PurchaseOrder.' . $this->Invoice->PurchaseOrder->primaryKey => $this->request->data['Invoice']['purchase_order_id']));
	        $purchaseOrder = $this->Invoice->PurchaseOrder->find('first', $options);
	        $this->set('purchaseOrder', $purchaseOrder);
	        $purchaseOrderDetails = $this->Invoice->PurchaseOrder->PurchaseOrderDetail->find('all', array('conditions' => array('PurchaseOrderDetail.purchase_order_id ' => $this->request->data['Invoice']['purchase_order_id'])));
	        $this->set('purchaseOrderDetails', $purchaseOrderDetails);

	        $invoiceDetails = $this->Invoice->InvoiceDetail->find('all', array('conditions' => array('InvoiceDetail.invoice_id ' => $id)));
	        $this->set('invoiceDetails', $invoiceDetails);
		}
		$purchaseOrders = $this->Invoice->PurchaseOrder->find('list',array('conditions'=>array('PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));
		$customers = $this->Invoice->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$customerContacts = $this->Invoice->CustomerContact->find('list',array('conditions'=>array('CustomerContact.publish'=>1,'CustomerContact.soft_delete'=>0,'CustomerContact.customer_id'=>$purchaseOrder['PurchaseOrder']['customer_id'])));
		$systemTables = $this->Invoice->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Invoice->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->Invoice->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->Invoice->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->Invoice->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Invoice->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$currencies = $this->Invoice->Currency->find('list',array('conditions'=>array('Currency.publish'=>1,'Currency.soft_delete'=>0)));
		$this->set(compact('purchaseOrders', 'customers', 'customerContacts','systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies','currencies'));
		$count = $this->Invoice->find('count');
		$published = $this->Invoice->find('count',array('conditions'=>array('Invoice.publish'=>1)));
		$unpublished = $this->Invoice->find('count',array('conditions'=>array('Invoice.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		$this->loadModel('InvoiceSetting');
		$invoice_settings = $this->InvoiceSetting->find('first');
		$this->set('invoice_settings',$invoice_settings);
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->Invoice->exists($id)) {
			throw new NotFoundException(__('Invalid invoice'));
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
			if ($this->Invoice->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->Invoice->save($this->request->data)) {
                $this->Session->setFlash(__('The invoice has been saved.'));
                $this->loadModel('InvoiceDetail');
				$this->InvoiceDetail->deleteAll(array('InvoiceDetail.invoice_id'=>$id));
				foreach($this->request->data['InvoiceDetail'] as $invoice_details){
					if($invoice_details['invoice_details'] == 1){
						$data['InvoiceDetail'] = $invoice_details;
						$data['InvoiceDetail']['invoice_id']= $this->Invoice->id;
						$data['InvoiceDetail']['prepared_by']= $this->request->data['Invoice']['prepared_by'];
						$data['InvoiceDetail']['approved_by']= $this->request->data['Invoice']['approved_by'];
						$data['InvoiceDetail']['publish']= $this->request->data['Invoice']['publish'];
						$data['InvoiceDetail']['record_status']= $this->request->data['Invoice']['record_status'];
						$data['InvoiceDetail']['soft_delete']= $this->request->data['Invoice']['soft_delete'];
						$data['InvoiceDetail']['master_list_of_format_id']= $this->request->data['Invoice']['master_list_of_format_id'];
						$data['InvoiceDetail']['system_table_id']= $this->request->data['Invoice']['system_table_id'];
						$data['InvoiceDetail']['devision_id']= $this->request->data['Invoice']['devision_id'];
						$data['InvoiceDetail']['company_id']= $this->request->data['Invoice']['company_id'];
						$this->InvoiceDetail->create();
						$this->InvoiceDetail->save($data);
					}
				}	

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The invoice could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The invoice could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('PurchaseOrder.' . $this->Invoice->PurchaseOrder->primaryKey => $this->request->data['Invoice']['purchase_order_id']));
	        $purchaseOrder = $this->Invoice->PurchaseOrder->find('first', $options);
	        $this->set('purchaseOrder', $purchaseOrder);
	        $purchaseOrderDetails = $this->Invoice->PurchaseOrder->PurchaseOrderDetail->find('all', array('conditions' => array('PurchaseOrderDetail.purchase_order_id ' => $this->request->data['Invoice']['purchase_order_id'])));
	        $this->set('purchaseOrderDetails', $purchaseOrderDetails);

	        $invoiceDetails = $this->Invoice->InvoiceDetail->find('all', array('conditions' => array('InvoiceDetail.invoice_id ' => $id)));
	        $this->set('invoiceDetails', $invoiceDetails);
		}
		$purchaseOrders = $this->Invoice->PurchaseOrder->find('list',array('conditions'=>array('PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));
		$customers = $this->Invoice->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$customerContacts = $this->Invoice->CustomerContact->find('list',array('conditions'=>array('CustomerContact.publish'=>1,'CustomerContact.soft_delete'=>0,'CustomerContact.customer_id'=>$purchaseOrder['PurchaseOrder']['customer_id'])));
		$systemTables = $this->Invoice->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Invoice->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->Invoice->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->Invoice->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->Invoice->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Invoice->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('purchaseOrders', 'customers', 'customerContacts','systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies'));
		$count = $this->Invoice->find('count');
		$published = $this->Invoice->find('count',array('conditions'=>array('Invoice.publish'=>1)));
		$unpublished = $this->Invoice->find('count',array('conditions'=>array('Invoice.publish'=>0)));
		
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
		$this->Invoice->id = $id;
		if (!$this->Invoice->exists()) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Invoice->delete()) {
			$this->Session->setFlash(__('Invoice deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Invoice was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null, $parent_id = null) {
	
            $model_name = $this->modelClass;
            if(!empty($id)){
    
            $data['id'] = $id;
            $data['soft_delete'] = 1;
            $model_name=$this->modelClass;
            $this->$model_name->save($data);
    }
    $this->redirect(array('action' => 'index'));
     
    
}
 
	public function send_to_customer($id = null){
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$options = array('conditions' => array('Invoice.' . $this->Invoice->primaryKey => $id));
			$invoice = $this->Invoice->find('first', $options);
        	$data = $this->request->data;
	        //sending email 
	        	$this->loadModel('FileUpload');
		        $files = $this->FileUpload->find('all',array(
		        'conditions'=>array('OR'=>array('FileUpload.id'=>$data['add_file'])),
		        'recursive'=> -1
		        ));
		    foreach ($files as $file) {
		        $attach[$file['FileUpload']['file_details'].'.'.$file['FileUpload']['file_type']] = array('file'=>Configure::read('MediaPath') . 'files' . DS . $this->Session->read('User.company_id'). DS .$file['FileUpload']['file_dir']);
		    }
	        
	        if($invoice['CustomerContact']['email']){
	            $invoice['CustomerContact']['email'] = str_replace(' ', '',$invoice['CustomerContact']['email']);
	            if (strpos($invoice['CustomerContact']['email'],',') !== false) {
	                $to = split(',', $invoice['CustomerContact']['email']);
	            }else{
	                $to = $invoice['CustomerContact']['email'];
	            }

	            $data['Invoice']['invoice_cc'] = str_replace(' ', '', $data['Invoice']['invoice_cc']);
	            if (strpos($data['Invoice']['invoice_cc'],',') !== false) {
	                $cc = split(',', $data['Invoice']['invoice_cc']);
	            }else{
	                $cc = $data['Invoice']['invoice_cc'];
	            }

	            $data['Invoice']['invoice_bcc'] = str_replace(' ', '', $data['Invoice']['invoice_bcc']);
	            if (strpos($data['Invoice']['invoice_bcc'],',') !== false) {
	                $bcc = split(',', $data['Invoice']['invoice_bcc']);
	            }else{
	                $bcc = $data['Invoice']['invoice_bcc'];
	            }

	            try{ 
	            	
	            	if(Configure::read('evnt') == 'Dev')$env = 'DEV';
			        elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
			        else $env = "";

	                if($this->Session->read('User.is_smtp') == 1)
	                    $EmailConfig = new CakeEmail("smtp");
	                if($this->Session->read('User.is_smtp') == 0)
	                    $EmailConfig = new CakeEmail("default");
	                
	                $EmailConfig->to($to);
	                if($data['Invoice']['invoice_cc'])$EmailConfig->cc($cc);
	                if($data['Invoice']['invoice_bcc'])$EmailConfig->bcc($bcc);
	                $EmailConfig->subject($data['Invoice']['email_subject']);
	                $EmailConfig->template('invoice');
	                $EmailConfig->viewVars(array('data'=>$data,'invoice'=>$data,'env' => $env, 'app_url' => FULL_BASE_URL));
	                $EmailConfig->emailFormat('html');
	                if($files)$EmailConfig->attachments($attach);            
	                $EmailConfig->send();

	                $this->Invoice->read(null,$invoice['Invoice']['id']);
	                $savedata['Invoice']['id'] = $invoice['Invoice']['id'];
	                $savedata['Invoice']['to'] = $to;
	                $savedata['Invoice']['cc'] = json_encode($cc);
	                $savedata['Invoice']['bcc'] = json_encode($bcc);
	                $savedata['Invoice']['subject'] = $data['Invoice']['email_subject'];
	                $savedata['Invoice']['message'] = $data['Invoice']['email_body'];
	                $savedata['Invoice']['send_to_customer'] = 1;
	                $this->Invoice->save($savedata,true);

	            } catch(Exception $e) {
	                $this->Session->setFlash(__('Email could not be sent. Please check smtp details.', true), 'smtp');
	                $this->redirect(array('action' => 'index'));
	            }    

	        }else{
	            $this->Session->setFlash(__('Email for customer contact could not be found. Email sending failed.', true), 'smtp');
	            $this->redirect(array('action' => 'index'));
	        } 

	        $this->Session->setFlash(__('Invoice is being sent to the customer.', true), 'smtp');
			$this->redirect(array('action' => 'index'));
		}       

		$options = array('conditions' => array('Invoice.' . $this->Invoice->primaryKey => $id));
		$invoice = $this->Invoice->find('first', $options);
		$this->request->data = $invoice;

		$customers = $this->Invoice->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $employees = $this->Invoice->PreparedBy->find('list', array('conditions' => array('PreparedBy.publish' => 1, 'PreparedBy.soft_delete' => 0)));
         $customerContacts = $this->Invoice->CustomerContact->find('list', array(
            'conditions' => array('CustomerContact.publish' => 1, 'CustomerContact.soft_delete' => 0,'CustomerContact.customer_id'=>$this->request->data['Invoice']['customer_id'])));
        $this->set(compact('customers', 'employees','customerContacts'));
        
        
	}
	
	
	public function report(){
		
		$result = explode('+',$this->request->data['invoices']['rec_selected']);
		$this->Invoice->recursive = 1;
		$invoices = $this->Invoice->find('all',array('Invoice.publish'=>1,'Invoice.soft_delete'=>1,'conditions'=>array('or'=>array('Invoice.id'=>$result))));
		$this->set('invoices', $invoices);
		
				$purchaseOrders = $this->Invoice->PurchaseOrder->find('list',array('conditions'=>array('PurchaseOrder.publish'=>1,'PurchaseOrder.soft_delete'=>0)));
		$customers = $this->Invoice->Customer->find('list',array('conditions'=>array('Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$customerContacts = $this->Invoice->CustomerContact->find('list',array('conditions'=>array('CustomerContact.publish'=>1,'CustomerContact.soft_delete'=>0)));
		$systemTables = $this->Invoice->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->Invoice->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->Invoice->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->Invoice->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$createdBies = $this->Invoice->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->Invoice->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('purchaseOrders', 'customers', 'customerContacts', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies', 'purchaseOrders', 'customers', 'customerContacts', 'statusUsers', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'createdBies', 'modifiedBies'));
	}

	public function invoice_pdf($id = null) {
		if (!$this->Invoice->exists($id)) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		$options = array('conditions' => array('Invoice.' . $this->Invoice->primaryKey => $this->request->params['pass'][0]));
		$invoice = $this->Invoice->find('first', $options);
		$this->set('invoice', $invoice);
		$this->loadModel('InvoiceSetting');
		$invoice_settings = $this->InvoiceSetting->find('first');
		$this->set('invoice_settings',$invoice_settings);
    }

	public function generate_pdf($id = null) {
		if (!$this->Invoice->exists($id)) {
			throw new NotFoundException(__('Invalid invoice'));
		}
		$this->loadModel('FileUpload');
        $rev = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.system_table_id'=>$this->_get_system_table_id(),'FileUpload.record'=>$id)));
        if($rev){
        	$count = count($rev) + 1;
        	$filename = 'invoice-ver-'.$count;
        }else{
        	$filename = 'invoice-ver-1';
        }
        echo $filename;
        $this->loadModel('InvoiceSetting');
		$invoice_settings = $this->InvoiceSetting->find('first');
		$this->set('invoice_settings',$invoice_settings);

		$options = array('conditions' => array('Invoice.' . $this->Invoice->primaryKey => $this->request->params['pass'][0]));
		$this->set('invoice', $this->Invoice->find('first', $options));
		$CakePdf = new CakePdf();
		$CakePdf->template('invoice_pdf', 'invoice');
        $CakePdf->viewVars(array('companyDetails'=>$this->_get_company(), 'invoice_settings' => $invoice_settings, 'invoice'=>$this->Invoice->find('first', $options)));
        $CakePdf->write( APP . 'files' . DS . $this->Session->read('User.company_id')  . DS . 'upload' . DS . $this->Session->read('User.id') . DS . 'invoices' . DS . $id . DS . $filename.'.pdf');
        
        $this->_upload_add($filename, 'pdf', 'Invoice Generated', 'upload' . DS . $this->Session->read('User.id') . DS . 'invoices' . DS . $id );
		

		$this->Session->setFlash(__('Invoice file is created and saved'));
		$this->redirect($this->referer());
    }

}

<?php
App::uses('AppController', 'Controller');
/**
 * ProductionWeeklyPlans Controller
 *
 * @property ProductionWeeklyPlan $ProductionWeeklyPlan
 * @property PaginatorComponent $Paginator
 */
class ProductionWeeklyPlansController extends AppController {

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
		$this->paginate = array('order'=>array('ProductionWeeklyPlan.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ProductionWeeklyPlan->recursive = 0;
		$this->set('productionWeeklyPlans', $this->paginate());
		
		$this->_get_count();

		$currentStatus = $this->ProductionWeeklyPlan->customArray['current_status'];
        $this->set(compact('currentStatus'));
	}


 
/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('ProductionWeeklyPlan.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->ProductionWeeklyPlan->recursive = 0;
		$this->set('productionWeeklyPlans', $this->paginate());
		
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
		$search_keys = explode(" ",$this->request->data['ProductionWeeklyPlan']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['ProductionWeeklyPlan']['search_field'] as $search):
				$search_array[] = array('ProductionWeeklyPlan.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('ProductionWeeklyPlan.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->ProductionWeeklyPlan->recursive = 0;
		$this->paginate = array('order'=>array('ProductionWeeklyPlan.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'ProductionWeeklyPlan.soft_delete'=>0 , $cons));
		$this->set('productionWeeklyPlans', $this->paginate());
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
					if($this->request->query['strict_search'] == 0)$search_array[] = array('ProductionWeeklyPlan.'.$search => $search_key);
					else $search_array[] = array('ProductionWeeklyPlan.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('ProductionWeeklyPlan.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('ProductionWeeklyPlan.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'ProductionWeeklyPlan.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('ProductionWeeklyPlan.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('ProductionWeeklyPlan.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->ProductionWeeklyPlan->recursive = 0;
		$this->paginate = array('order'=>array('ProductionWeeklyPlan.sr_no'=>'DESC'),'conditions'=>$conditions , 'ProductionWeeklyPlan.soft_delete'=>0 );
		$this->set('productionWeeklyPlans', $this->paginate());
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
		if (!$this->ProductionWeeklyPlan->exists($id)) {
			throw new NotFoundException(__('Invalid production weekly plan'));
		}
		$options = array('conditions' => array('ProductionWeeklyPlan.' . $this->ProductionWeeklyPlan->primaryKey => $id));
		$this->set('productionWeeklyPlan', $this->ProductionWeeklyPlan->find('first', $options));
	}



/**
 * list method
 *
 * @return void
 */
	public function lists() {
		if(!$this->request->params['named']['product_id']){
        	$this->Session->setFlash(__('Please goto Products and click "Add Weekly Plan"'));
        	$this->redirect(array('controller'=>'products', 'action' => 'index'));
        }

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

        if(!$this->request->is('post') && !$this->request->params['named']['product_id']){
        	$this->Session->setFlash(__('Please goto Products and click "Add Weekly Plan"'));
        	$this->redirect(array('controller'=>'products', 'action' => 'index'));
        }
		
		if ($this->request->is('post')) {
			$this->request->data['ProductionWeeklyPlan']['system_table_id'] = $this->_get_system_table_id();
			$this->request->data['ProductionWeeklyPlan']['balance'] = $this->request->data['ProductionWeeklyPlan']['production_planned'];
			$product_id = $this->data['ProductionWeeklyPlan']['product_id'];
			
			// $week = $this->data['ProductionWeeklyPlan']['week'];
			$dateRange = split('-', $this->request->data['ProductionWeeklyPlan']['dates']);
            $startDate = rtrim(ltrim($dateRange[0]));
            $endDate = rtrim(ltrim($dateRange[1]));

			// add start - end dates
			$this->request->data['ProductionWeeklyPlan']['start_date'] = $startDate;
			$this->request->data['ProductionWeeklyPlan']['end_date'] = $endDate;

			// $planned = $this->ProductionWeeklyPlan->find('first',array('conditions'=>array('ProductionWeeklyPlan.product_id'=>$product_id,'ProductionWeeklyPlan.week'=>$week)));
			// if($planned){
			// 	$this->ProductionWeeklyPlan->read(null,$planned['ProductionWeeklyPlan']['id']);				
			// 	$this->ProductionWeeklyPlan->set('production_planned',$this->data['ProductionWeeklyPlan']['production_planned']);
			// 	$this->ProductionWeeklyPlan->save();

			// 	$this->Session->setFlash(__('The production weekly plan has been saved'));
			// 	if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProductionWeeklyPlan->id));
			// 	else $this->redirect(array('action' => 'index'));
			// }

			$this->ProductionWeeklyPlan->create();
			if ($this->ProductionWeeklyPlan->save($this->request->data)) {


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The production weekly plan has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->ProductionWeeklyPlan->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The production weekly plan could not be saved. Please, try again.'));
			}
		}
		$products = $this->ProductionWeeklyPlan->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0,'Product.id'=>$this->request->params['named']['product_id'])));
		$systemTables = $this->ProductionWeeklyPlan->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProductionWeeklyPlan->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProductionWeeklyPlan->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProductionWeeklyPlan->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProductionWeeklyPlan->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProductionWeeklyPlan->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProductionWeeklyPlan->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		
		$meterials = $this->ProductionWeeklyPlan->Product->ProductMaterial->find('all',array('fields'=>array('ProductMaterial.id','ProductMaterial.material_id','ProductMaterial.quantity'), 'conditions'=>array('ProductMaterial.product_id'=>$this->request->params['named']['product_id'])));
        foreach ($meterials as $key=>$material) {
        	$stock = $this->requestAction(array('controller'=>'stocks','action'=>'get_stock_details',$material['ProductMaterial']['material_id']));
            $newMaterial[] = array('ProductMaterial'=>$material['ProductMaterial'], 'stock'=> $stock);
            $stocks[] = $stock;
            foreach ($stocks as $stock) {
            	if($stock['stock'] == 0){
            		$this->Session->setFlash(__( $stock['material']['name'] . 'is out of stock.'));
            		$this->set('disableForm',true);
            	}
            }
            
        }
        $stocks = $newMaterial;
        $this->loadModel('Unit');
        $units = $this->Unit->find('list',array('conditions'=>array('Unit.publish'=>1,'Unit.soft_delete'=>0)));
		$this->set(compact('stocks', 'products', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'units'));
		$count = $this->ProductionWeeklyPlan->find('count');
		$published = $this->ProductionWeeklyPlan->find('count',array('conditions'=>array('ProductionWeeklyPlan.publish'=>1)));
		$unpublished = $this->ProductionWeeklyPlan->find('count',array('conditions'=>array('ProductionWeeklyPlan.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		// get weekly plan history

		if($this->request->params['named']['product_id']){
			$productionWeeklyPlans  = $this->ProductionWeeklyPlan->find('all',array('conditions'=>array('ProductionWeeklyPlan.product_id'=>$this->request->params['named']['product_id']),'recursive'=>-1));
			$this->set(compact('productionWeeklyPlans'));
		}


	}


/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ProductionWeeklyPlan->exists($id)) {
			throw new NotFoundException(__('Invalid production weekly plan'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
						
			$this->request->data['ProductionWeeklyPlan']['system_table_id'] = $this->_get_system_table_id();

			// $week = $this->data['ProductionWeeklyPlan']['week'];
			$dateRange = split('-', $this->request->data['ProductionWeeklyPlan']['dates']);
            $startDate = rtrim(ltrim($dateRange[0]));
            $endDate = rtrim(ltrim($dateRange[1]));

			// add start - end dates
			$this->request->data['ProductionWeeklyPlan']['start_date'] = $startDate;
			$this->request->data['ProductionWeeklyPlan']['end_date'] = $endDate;



			if ($this->ProductionWeeklyPlan->save($this->request->data)) {

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The production weekly plan could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProductionWeeklyPlan.' . $this->ProductionWeeklyPlan->primaryKey => $id));
			$this->request->data = $this->ProductionWeeklyPlan->find('first', $options);
			// get production 
			$productions = $this->ProductionWeeklyPlan->Production->find('all',array(
				'conditions'=>array('Production.production_weekly_plan_id'=>$id),
				'recursive'=>-1,
				'fields'=>array('Production.id','Production.production_weekly_plan_id','Production.actual_production_number'),
				));
			foreach ($productions as $production) {
				$total = $total + $production['Production']['actual_production_number'];
			}
			$this->set('total',$total);
		}
		
		$products = $this->ProductionWeeklyPlan->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0,'Product.id'=>$this->request->data['ProductionWeeklyPlan']['product_id'])));
		$systemTables = $this->ProductionWeeklyPlan->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProductionWeeklyPlan->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProductionWeeklyPlan->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProductionWeeklyPlan->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProductionWeeklyPlan->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProductionWeeklyPlan->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProductionWeeklyPlan->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		
		$meterials = $this->ProductionWeeklyPlan->Product->ProductMaterial->find('all',array('fields'=>array('ProductMaterial.id','ProductMaterial.material_id','ProductMaterial.quantity'), 'conditions'=>array('ProductMaterial.product_id'=>$this->request->data['ProductionWeeklyPlan']['product_id'])));
        foreach ($meterials as $key=>$material) {
        	$stock = $this->requestAction(array('controller'=>'stocks','action'=>'get_stock_details',$material['ProductMaterial']['material_id']));
            $newMaterial[] = array('ProductMaterial'=>$material['ProductMaterial'], 'stock'=> $stock);
            $stocks[] = $stock;
            foreach ($stocks as $stock) {
            	if($stock['stock'] == 0){
            		$this->Session->setFlash(__( $stock['material']['name'] . 'is out of stock.'));
            		$this->set('disableForm',true);
            	}
            }
            
        }
        $stocks = $newMaterial;
        $this->loadModel('Unit');
        $units = $this->Unit->find('list',array('conditions'=>array('Unit.publish'=>1,'Unit.soft_delete'=>0)));
		$this->set(compact('stocks', 'products', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'units'));
		$count = $this->ProductionWeeklyPlan->find('count');
		$published = $this->ProductionWeeklyPlan->find('count',array('conditions'=>array('ProductionWeeklyPlan.publish'=>1)));
		$unpublished = $this->ProductionWeeklyPlan->find('count',array('conditions'=>array('ProductionWeeklyPlan.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		// get weekly plan history

		if($this->request->data['ProductionWeeklyPlan']['product_id']){
			$productionWeeklyPlans  = $this->ProductionWeeklyPlan->find('all',array('conditions'=>array('ProductionWeeklyPlan.product_id'=>$this->request->data['ProductionWeeklyPlan']['product_id']),'recursive'=>-1));
			$this->set(compact('productionWeeklyPlans'));
		}

		$currentStatus = $this->ProductionWeeklyPlan->customArray['current_status'];
        $this->set(compact('currentStatus'));
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->ProductionWeeklyPlan->exists($id)) {
			throw new NotFoundException(__('Invalid production weekly plan'));
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
			$dateRange = split('-', $this->request->data['ProductionWeeklyPlan']['dates']);
            $startDate = rtrim(ltrim($dateRange[0]));
            $endDate = rtrim(ltrim($dateRange[1]));

			// add start - end dates
			$this->request->data['ProductionWeeklyPlan']['start_date'] = $startDate;
			$this->request->data['ProductionWeeklyPlan']['end_date'] = $endDate;
			
			if ($this->ProductionWeeklyPlan->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->ProductionWeeklyPlan->save($this->request->data)) {
                $this->Session->setFlash(__('The production weekly plan has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The production weekly plan could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The production weekly plan could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProductionWeeklyPlan.' . $this->ProductionWeeklyPlan->primaryKey => $id));
			$this->request->data = $this->ProductionWeeklyPlan->find('first', $options);
		}
		

		$products = $this->ProductionWeeklyPlan->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0,'Product.id'=>$this->request->data['ProductionWeeklyPlan']['product_id'])));
		$systemTables = $this->ProductionWeeklyPlan->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProductionWeeklyPlan->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProductionWeeklyPlan->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProductionWeeklyPlan->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProductionWeeklyPlan->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProductionWeeklyPlan->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProductionWeeklyPlan->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		
		$meterials = $this->ProductionWeeklyPlan->Product->ProductMaterial->find('all',array('fields'=>array('ProductMaterial.id','ProductMaterial.material_id','ProductMaterial.quantity'), 'conditions'=>array('ProductMaterial.product_id'=>$this->request->data['ProductionWeeklyPlan']['product_id'])));
        foreach ($meterials as $key=>$material) {
        	$stock = $this->requestAction(array('controller'=>'stocks','action'=>'get_stock_details',$material['ProductMaterial']['material_id']));
            $newMaterial[] = array('ProductMaterial'=>$material['ProductMaterial'], 'stock'=> $stock);
            $stocks[] = $stock;
            foreach ($stocks as $stock) {
            	if($stock['stock'] == 0){
            		$this->Session->setFlash(__( $stock['material']['name'] . 'is out of stock.'));
            		$this->set('disableForm',true);
            	}
            }
            
        }
        $stocks = $newMaterial;
        $this->loadModel('Unit');
        $units = $this->Unit->find('list',array('conditions'=>array('Unit.publish'=>1,'Unit.soft_delete'=>0)));
		$this->set(compact('stocks', 'products', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'units'));
		$count = $this->ProductionWeeklyPlan->find('count');
		$published = $this->ProductionWeeklyPlan->find('count',array('conditions'=>array('ProductionWeeklyPlan.publish'=>1)));
		$unpublished = $this->ProductionWeeklyPlan->find('count',array('conditions'=>array('ProductionWeeklyPlan.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

		// get weekly plan history

		if($this->request->data['ProductionWeeklyPlan']['product_id']){
			$productionWeeklyPlans  = $this->ProductionWeeklyPlan->find('all',array('conditions'=>array('ProductionWeeklyPlan.product_id'=>$this->request->data['ProductionWeeklyPlan']['product_id']),'recursive'=>-1));
			$this->set(compact('productionWeeklyPlans'));
		}

		$currentStatus = $this->ProductionWeeklyPlan->customArray['current_status'];
        $this->set(compact('currentStatus'));
	}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function purge($id = null) {
		$this->ProductionWeeklyPlan->id = $id;
		if (!$this->ProductionWeeklyPlan->exists()) {
			throw new NotFoundException(__('Invalid production weekly plan'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProductionWeeklyPlan->delete()) {
			$this->Session->setFlash(__('Production weekly plan deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Production weekly plan was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       
	public function report(){
		
		$result = explode('+',$this->request->data['productionWeeklyPlans']['rec_selected']);
		$this->ProductionWeeklyPlan->recursive = 1;
		$productionWeeklyPlans = $this->ProductionWeeklyPlan->find('all',array('ProductionWeeklyPlan.publish'=>1,'ProductionWeeklyPlan.soft_delete'=>1,'conditions'=>array('or'=>array('ProductionWeeklyPlan.id'=>$result))));
		$this->set('productionWeeklyPlans', $productionWeeklyPlans);
		
				$products = $this->ProductionWeeklyPlan->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		$systemTables = $this->ProductionWeeklyPlan->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProductionWeeklyPlan->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProductionWeeklyPlan->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProductionWeeklyPlan->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProductionWeeklyPlan->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProductionWeeklyPlan->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProductionWeeklyPlan->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('products', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'products', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	}

	public function get_val(){
		$this->autoRender = false;
		$product_id = $this->request->params['named']['product_id'];
		$week = $this->request->params['named']['week'];
		$planned = $this->ProductionWeeklyPlan->find('first',array('conditions'=>array('ProductionWeeklyPlan.product_id'=>$product_id,'ProductionWeeklyPlan.week'=>$week)));
		if($planned){
			return $planned['ProductionWeeklyPlan']['production_planned'];
			exit;
		}
		exit;
	}


	public function delete($id = null){
		$productions = $this->ProductionWeeklyPlan->Production->find('list',array('conditions'=>array('Production.production_weekly_plan_id'=>$id)));
        
        foreach ($productions as $pkey => $pvalue) {
        	$productionRejections = $this->ProductionWeeklyPlan->Production->ProductionRejection->find('list',array('conditions'=>array('ProductionRejection.production_id'=>$pkay)));
	        
	        foreach ($productionRejections as $key => $val) {
	            
	            $rejectionDetails = $this->ProductionWeeklyPlan->Production->ProductionRejection->RejectionDetail->find('list',array('conditions'=>array('RejectionDetail.production_rejection_id'=>$key)));
	            
	            foreach ($rejectionDetails as $rkey => $rvalue) {
	                $this->ProductionWeeklyPlan->Production->ProductionRejection->RejectionDetail->delete(array('RejectionDetail.id'=>$rkey));
	            }
	            
	            $this->ProductionWeeklyPlan->Production->ProductionRejection->delete(array('ProductionRejection.id'=>$key));
	        }
	        $this->ProductionWeeklyPlan->Production->delete(array('ProductionRejection.id'=>$pkey));
        }
        
        $this->ProductionWeeklyPlan->delete(array('RejectionDetail.id'=>$id));
        $this->redirect(array('action' => 'index'));

	}
}

<?php
App::uses('AppController', 'Controller');
/**
 * ProductionRejections Controller
 *
 * @property ProductionRejection $ProductionRejection
 * @property PaginatorComponent $Paginator
 */
class ProductionRejectionsController extends AppController {

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
		$this->paginate = array('order'=>array('ProductionRejection.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->ProductionRejection->recursive = 0;
		$this->set('productionRejections', $this->paginate());
		
		$this->_get_count();
	}


 


/**
 * list method
 *
 * @return void
 */
	public function lists() {
	
        $this->_get_count();		
        if(!$this->request->params['named']['production_id']){
			$this->Session->setFlash(__('Select Production Batch'));
			$this->redirect(array('controller'=>'productions', 'action' => 'index'));
		}
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
			
			$rejections = 0;
			
			foreach ($this->request->data['RejectionDetail'] as $rej) {
				$rejections = $rejections + $rej['number_of_rejections'];
			}
			$this->request->data['ProductionRejection']['number_of_rejections'] = $rejections;
			
			$this->request->data['ProductionRejection']['system_table_id'] = $this->_get_system_table_id();
			
			$this->ProductionRejection->create();
			if ($this->ProductionRejection->save($this->request->data)) {
				
				$this->_rejection_details(
					$this->ProductionRejection->id,
					$this->request->data['RejectionDetail'],
					$this->request->data['ProductionRejection'],
					$this->request->data['ProductionRejection']['production_weekly_plan_id']
				);
				
				$this->_weekly_plan_details($this->request->data['ProductionRejection']['production_weekly_plan_id']);

				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The production rejection has been saved'));
				$this->redirect(array('controller'=>'productions','action' => 'view',$this->request->data['ProductionRejection']['production_id']));
				
			} else {
				$this->Session->setFlash(__('The production rejection could not be saved. Please, try again.'));
			}
		}

		if($this->request->params['named']['production_id']){
			$productions = $this->ProductionRejection->Production->find('list',array('conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0,'Production.id'=>$this->request->params['named']['production_id'])));
			$production = $this->ProductionRejection->Production->find('first',array('conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0,'Production.id'=>$this->request->params['named']['production_id'])));
			$weekPlan = $this->ProductionRejection->Production->ProductionWeeklyPlan->find(
				'first',array(
					'recursive'=>-1,
					'fields'=>array('ProductionWeeklyPlan.id','ProductionWeeklyPlan.week'),
					'conditions'=>array('ProductionWeeklyPlan.publish'=>1,'ProductionWeeklyPlan.soft_delete'=>0,'ProductionWeeklyPlan.id'=>$production['Production']['production_weekly_plan_id'])));
			$productionWeek= $weekPlan['ProductionWeeklyPlan']['week'];
			$year = substr($productionWeek,0,2);
			$week = substr($productionWeek,2);
			$startDate = date('yyyy-MM-dd',strtotime('20'.$year.'W'.$week));
			$this->set('startDate',$startDate);
			$this->set('actual_production_number',$production['Production']['actual_production_number']);

			$newProductionRejections = $this->_rejection_history(
					$this->request->params['named']['production_id'],
					$productions['Production']['product_id']);
			$this->set('newProductionRejections',$newProductionRejections);

		}else{
			$productions = $this->ProductionRejection->Production->find('list',array('conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0)));
		}

		if($this->request->params['named']['product_id']){
			$products = $this->ProductionRejection->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0,'Product.id'=>$this->request->params['named']['product_id'])));
		}else{
			$products = $this->ProductionRejection->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		}
		
		if(!$this->request->params['named']['production_id']){
			$this->Session->setFlash(__('Select Production Batch'));
			$this->redirect(array('controller'=>'productions', 'action' => 'index'));
		}

		$productionInspectionTemplates = $this->ProductionRejection->ProductionInspectionTemplate->find('list',array('conditions'=>array('ProductionInspectionTemplate.publish'=>1,'ProductionInspectionTemplate.soft_delete'=>0)));
		$allDefectTypes = $this->ProductionRejection->DefectType->find('all',array('recursive'=>-1, 'conditions'=>array('DefectType.publish'=>1,'DefectType.soft_delete'=>0)));
		foreach ($allDefectTypes as $defectType) {
			$performanceIndicator = $this->ProductionRejection->PerformanceIndicator->find('first',array(
				'fields'=>array('PerformanceIndicator.id','PerformanceIndicator.name','ValueDriver.id','ValueDriver.name'),
				'conditions'=>array('DefectType.id'=>$defectType['DefectType']['id']),
				'recursive'=>0));
			if($performanceIndicator){
				$defectTypes[$defectType['DefectType']['id']] = $defectType['DefectType']['name'] . ' - (' . $performanceIndicator['PerformanceIndicator']['name'] . '/'  . $performanceIndicator['ValueDriver']['name'].')';	
			}else{
				$defectTypes[$defectType['DefectType']['id']] = $defectType['DefectType']['name'] . ' - (Not Linked : Not Linked)';
			}
			
		}
		
		$employees = $this->ProductionRejection->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$supplierRegistrations = $this->ProductionRejection->SupplierRegistration->find('list',array('conditions'=>array('SupplierRegistration.publish'=>1,'SupplierRegistration.soft_delete'=>0)));
		$customerContacts = $this->ProductionRejection->CustomerContact->find('list',array('conditions'=>array('CustomerContact.publish'=>1,'CustomerContact.soft_delete'=>0)));
		$systemTables = $this->ProductionRejection->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProductionRejection->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProductionRejection->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProductionRejection->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProductionRejection->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProductionRejection->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProductionRejection->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('valueDrivers', 'performanceIndicators', 'defectTypes', 'productions', 'products', 'productionInspectionTemplates', 'employees', 'supplierRegistrations', 'customerContacts', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		
		$count = $this->ProductionRejection->find('count');
		$published = $this->ProductionRejection->find('count',array('conditions'=>array('ProductionRejection.publish'=>1)));
		$unpublished = $this->ProductionRejection->find('count',array('conditions'=>array('ProductionRejection.publish'=>0)));
			
		$this->set(compact('count','published','unpublished'));

	}





/**
 * add method
 *
 * @return void
 */
	

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {

		if($this->Session->read('User.is_mr') == 0){
			$this->Session->setFlash(__('The production rejection can not be editied.'));
			$this->redirect(array('action' => 'index'));
		}

		if (!$this->ProductionRejection->exists($id)) {
			throw new NotFoundException(__('Invalid production rejection'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post') || $this->request->is('put')) {
			
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
        		$this->request->data[$this->modelClass]['publish'] = 0;
      		}
						
			$this->request->data['ProductionRejection']['system_table_id'] = $this->_get_system_table_id();
			
			$rejections = 0;
			foreach ($this->request->data['RejectionDetail'] as $rej) {
				$rejections = $rejections + $rej['number_of_rejections'];
			}
			$this->request->data['ProductionRejection']['number_of_rejections'] = $rejections;
			if ($this->ProductionRejection->save($this->request->data)) {

				$this->_rejection_details(
					$this->request->data['ProductionRejection']['id'],
					$this->request->data['RejectionDetail'],
					$this->request->data['ProductionRejection'],
					$this->request->data['ProductionRejection']['production_weekly_plan_id']
				);
				

				$this->request->data['ProductionRejection']['number_of_rejections'] = $rejections;
				$this->_weekly_plan_details($this->request->data['ProductionRejection']['production_weekly_plan_id']);

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('controller'=>'productions', 'action' => 'view', $this->request->data['ProductionRejection']['production_id']));
			} else {
				$this->Session->setFlash(__('The production rejection could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProductionRejection.' . $this->ProductionRejection->primaryKey => $id));
			$this->request->data = $this->ProductionRejection->find('first', $options);
		}
		
		if($this->request->data['ProductionRejection']['production_id']){
			$this->loadModel('RejectionDetail');
			$rejections = $this->RejectionDetail->find('all',array('conditions'=>array('RejectionDetail.production_rejection_id'=>$this->request->data['ProductionRejection']['id'])));
			$this->set('rejections',$rejections);
			
			$productions = $this->ProductionRejection->Production->find('list',array('conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0,'Production.id'=>$this->request->data['ProductionRejection']['production_id'])));
			$production = $this->ProductionRejection->Production->find('first',array('conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0,'Production.id'=>$this->request->data['ProductionRejection']['production_id'])));
			$weekPlan = $this->ProductionRejection->Production->ProductionWeeklyPlan->find(
				'first',array(
					'recursive'=>-1,
					'fields'=>array('ProductionWeeklyPlan.id','ProductionWeeklyPlan.week'),
					'conditions'=>array('ProductionWeeklyPlan.publish'=>1,'ProductionWeeklyPlan.soft_delete'=>0,'ProductionWeeklyPlan.id'=>$production['Production']['production_weekly_plan_id'])));
			$productionWeek= $weekPlan['ProductionWeeklyPlan']['week'];
			$year = substr($productionWeek,0,2);
			$week = substr($productionWeek,2);
			$startDate = date('yyyy-MM-dd',strtotime('20'.$year.'W'.$week));
			$this->set('startDate',$startDate);
			$this->set('actual_production_number',$production['Production']['actual_production_number']);

			$newProductionRejections = $this->_rejection_history(
					$this->request->data['ProductionRejection']['production_id'],
					$productions['Production']['product_id']);
			$this->set('newProductionRejections',$newProductionRejections);

		}else{
			$productions = $this->ProductionRejection->Production->find('list',array('conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0)));
		}

		if($this->request->data['ProductionRejection']['product_id']){
			$products = $this->ProductionRejection->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0,'Product.id'=>$this->request->data['ProductionRejection']['product_id'])));
		}else{
			$products = $this->ProductionRejection->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		}
		
		if(!$this->request->data['ProductionRejection']['production_id']){
			$this->Session->setFlash(__('Select Production Batch'));
			$this->redirect(array('controller'=>'productions', 'action' => 'index'));
		}
		

		if($production['Production']['publish'] == 1){
            if($production['Production']['rejections'] == 0){
                // echo $this->Html->link('Add QC/Rejections',array('controller'=>'production_rejections','action'=>'lists','production_id'=>$production['Production']['id'],'product_id'=>$production['Production']['product_id']),array('class'=>'btn btn-xs btn-danger'));     
            }elseif($production['Production']['rejections'] > 0 && $this->Session->read('User.is_mr') == 1){
                // echo $this->Html->link('Update QC/Rejections',array('controller'=>'productions','action'=>'view',$production['Production']['id']),array('class'=>'btn btn-xs btn-danger'));     
            }else{
            	$this->Session->setFlash(__('The production could not be updated by non-admin users.'));       
				$this->redirect(array('controller'=>'productions', 'action' => 'index'));
            }
            
        }

		$productionInspectionTemplates = $this->ProductionRejection->ProductionInspectionTemplate->find('list',array('conditions'=>array('ProductionInspectionTemplate.publish'=>1,'ProductionInspectionTemplate.soft_delete'=>0)));		
		$allDefectTypes = $this->ProductionRejection->DefectType->find('all',array('recursive'=>-1, 'conditions'=>array('DefectType.publish'=>1,'DefectType.soft_delete'=>0)));
		foreach ($allDefectTypes as $defectType) {
			$performanceIndicator = $this->ProductionRejection->PerformanceIndicator->find('first',array(
				'fields'=>array('PerformanceIndicator.id','PerformanceIndicator.name','ValueDriver.id','ValueDriver.name'),
				'conditions'=>array('DefectType.id'=>$defectType['DefectType']['id']),
				'recursive'=>0));
			
			if($performanceIndicator){
				$defectTypes[$defectType['DefectType']['id']] = $defectType['DefectType']['name'] . ' - (' . $performanceIndicator['PerformanceIndicator']['name'] . '/'  . $performanceIndicator['ValueDriver']['name'].')';	
			}else{
				$defectTypes[$defectType['DefectType']['id']] = $defectType['DefectType']['name'] . ' - (Not Linked : Not Linked)';
			}
			
		}
		$employees = $this->ProductionRejection->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$supplierRegistrations = $this->ProductionRejection->SupplierRegistration->find('list',array('conditions'=>array('SupplierRegistration.publish'=>1,'SupplierRegistration.soft_delete'=>0)));
		$customerContacts = $this->ProductionRejection->CustomerContact->find('list',array('conditions'=>array('CustomerContact.publish'=>1,'CustomerContact.soft_delete'=>0)));
		$systemTables = $this->ProductionRejection->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProductionRejection->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProductionRejection->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProductionRejection->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProductionRejection->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProductionRejection->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProductionRejection->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('valueDrivers', 'performanceIndicators', 'defectTypes', 'productions', 'products', 'productionInspectionTemplates', 'employees', 'supplierRegistrations', 'customerContacts', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		
		$count = $this->ProductionRejection->find('count');
		$published = $this->ProductionRejection->find('count',array('conditions'=>array('ProductionRejection.publish'=>1)));
		$unpublished = $this->ProductionRejection->find('count',array('conditions'=>array('ProductionRejection.publish'=>0)));
			
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
		if (!$this->ProductionRejection->exists($id)) {
			throw new NotFoundException(__('Invalid production rejection'));
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
			
			$rejections = 0;
			foreach ($this->request->data['RejectionDetail'] as $rej) {
				$rejections = $rejections + $rej['number_of_rejections'];
			}
			
			$this->request->data['ProductionRejection']['number_of_rejections'] = $rejections;
			
			if ($this->ProductionRejection->save($this->request->data)) {


				$this->_rejection_details(
					$this->request->data['ProductionRejection']['id'],
					$this->request->data['RejectionDetail'],
					$this->request->data['ProductionRejection'],
					$this->request->data['ProductionRejection']['production_weekly_plan_id']
				);
				
				
				
				$this->request->data['ProductionRejection']['number_of_rejections'] = $rejections;
				$this->_weekly_plan_details($this->request->data['ProductionRejection']['production_weekly_plan_id']);

				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('controller'=>'productions', 'action' => 'view', $this->request->data['ProductionRejection']['production_id']));
				else
		 			$this->redirect(array('controller'=>'productions', 'action' => 'view', $this->request->data['ProductionRejection']['production_id']));
			} else {
				$this->Session->setFlash(__('The production rejection could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ProductionRejection.' . $this->ProductionRejection->primaryKey => $id));
			$this->request->data = $this->ProductionRejection->find('first', $options);
		}


		if($this->request->data['ProductionRejection']['production_id']){
			$this->loadModel('RejectionDetail');
			$rejections = $this->RejectionDetail->find('all',array('conditions'=>array('RejectionDetail.production_rejection_id'=>$this->request->data['ProductionRejection']['id'])));
			$this->set('rejections',$rejections);
			
			$productions = $this->ProductionRejection->Production->find('list',array('conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0,'Production.id'=>$this->request->data['ProductionRejection']['production_id'])));
			$production = $this->ProductionRejection->Production->find('first',array('conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0,'Production.id'=>$this->request->data['ProductionRejection']['production_id'])));
			$weekPlan = $this->ProductionRejection->Production->ProductionWeeklyPlan->find(
				'first',array(
					'recursive'=>-1,
					'fields'=>array('ProductionWeeklyPlan.id','ProductionWeeklyPlan.week'),
					'conditions'=>array('ProductionWeeklyPlan.publish'=>1,'ProductionWeeklyPlan.soft_delete'=>0,'ProductionWeeklyPlan.id'=>$production['Production']['production_weekly_plan_id'])));
			$productionWeek= $weekPlan['ProductionWeeklyPlan']['week'];
			$year = substr($productionWeek,0,2);
			$week = substr($productionWeek,2);
			$startDate = date('yyyy-MM-dd',strtotime('20'.$year.'W'.$week));
			$this->set('startDate',$startDate);
			$this->set('actual_production_number',$production['Production']['actual_production_number']);

			$newProductionRejections = $this->_rejection_history(
					$this->request->data['ProductionRejection']['production_id'],
					$productions['Production']['product_id']);
			$this->set('newProductionRejections',$newProductionRejections);

		}else{
			$productions = $this->ProductionRejection->Production->find('list',array('conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0)));
		}

		if($this->request->data['ProductionRejection']['product_id']){
			$products = $this->ProductionRejection->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0,'Product.id'=>$this->request->data['ProductionRejection']['product_id'])));
		}else{
			$products = $this->ProductionRejection->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
		}
		
		if(!$this->request->data['ProductionRejection']['production_id']){
			$this->Session->setFlash(__('Select Production Batch'));
			$this->redirect(array('controller'=>'productions', 'action' => 'index'));
		}
		
		$productionInspectionTemplates = $this->ProductionRejection->ProductionInspectionTemplate->find('list',array('conditions'=>array('ProductionInspectionTemplate.publish'=>1,'ProductionInspectionTemplate.soft_delete'=>0)));
		$allDefectTypes = $this->ProductionRejection->DefectType->find('all',array('recursive'=>-1, 'conditions'=>array('DefectType.publish'=>1,'DefectType.soft_delete'=>0)));
		foreach ($allDefectTypes as $defectType) {
			$performanceIndicator = $this->ProductionRejection->PerformanceIndicator->find('first',array(
				'fields'=>array('PerformanceIndicator.id','PerformanceIndicator.name','ValueDriver.id','ValueDriver.name'),
				'conditions'=>array('DefectType.id'=>$defectType['DefectType']['id']),
				'recursive'=>0));
			if($performanceIndicator){
				$defectTypes[$defectType['DefectType']['id']] = $defectType['DefectType']['name'] . ' - (' . $performanceIndicator['PerformanceIndicator']['name'] . '/'  . $performanceIndicator['ValueDriver']['name'].')';	
			}else{
				$defectTypes[$defectType['DefectType']['id']] = $defectType['DefectType']['name'] . ' - (Not Linked : Not Linked)';
			}
			
		}
		$employees = $this->ProductionRejection->Employee->find('list',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0)));
		$supplierRegistrations = $this->ProductionRejection->SupplierRegistration->find('list',array('conditions'=>array('SupplierRegistration.publish'=>1,'SupplierRegistration.soft_delete'=>0)));
		$customerContacts = $this->ProductionRejection->CustomerContact->find('list',array('conditions'=>array('CustomerContact.publish'=>1,'CustomerContact.soft_delete'=>0)));
		$systemTables = $this->ProductionRejection->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->ProductionRejection->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$companies = $this->ProductionRejection->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->ProductionRejection->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->ProductionRejection->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->ProductionRejection->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->ProductionRejection->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('valueDrivers', 'performanceIndicators', 'defectTypes', 'productions', 'products', 'productionInspectionTemplates', 'employees', 'supplierRegistrations', 'customerContacts', 'systemTables', 'masterListOfFormats', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		
		$count = $this->ProductionRejection->find('count');
		$published = $this->ProductionRejection->find('count',array('conditions'=>array('ProductionRejection.publish'=>1)));
		$unpublished = $this->ProductionRejection->find('count',array('conditions'=>array('ProductionRejection.publish'=>0)));
			
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
		$this->ProductionRejection->id = $id;
		if (!$this->ProductionRejection->exists()) {
			throw new NotFoundException(__('Invalid production rejection'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ProductionRejection->delete()) {
			$this->Session->setFlash(__('Production rejection deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Production rejection was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete_details($id = null) {
		// $this->loadModel('RejectionDetail');
		// $rejection_details = $this->ProductionRejection->RejectionDetail->find('first',array('conditions'=>array('RejectionDetail.id'=>$id)));
		// $this->RejectionDetail->delete(array('RejectionDetail.id'=>$id));
		// $this->_weekly_plan_details($rejection_details['ProductionRejection']['production_weekly_plan_id']);

		// Configure::write('debug',1);
		// $rejection_details = $this->ProductionRejection->RejectionDetail->find('first',array('conditions'=>array('RejectionDetail.id'=>$id)));
		// if($rejection_details){
		// 	$this->loadModel('RejectionDetail');
			
		// 	// update production rejection table
			
		// 	$this->loadModel('ProductionRejection');
		// 	$productionRejections = $rejection_details['ProductionRejection'];
		// 	$productionRejections['number_of_rejections'] = $productionRejections['number_of_rejections'] - $rejection_details['RejectionDetail']['number_of_rejections'];
		// 	$this->ProductionRejection->create();
		// 	$this->ProductionRejection->save($productionRejections,false);

			
		// 	// update productions

		// 	$this->loadModel('Production');
		// 	$production = $this->Production->find('first',array( 'recursive'=>-1, 'conditions'=>array('Production.id'=>$rejection_details['ProductionRejection']['production_id'])));
			
		// 	$production['Production']['rejections'] = $production['Production']['rejections'] - $rejection_details['ProductionRejection']['number_of_rejections'];
		// 	$production['Production']['balance'] = $production['Production']['balance'] + $rejection_details['ProductionRejection']['number_of_rejections'];
		
		// 	$this->Production->create();
		// 	$this->Production->save($production['Production'],false);	

		// 	// delete current rejection
		// 	$this->RejectionDetail->delete(array('RejectionDetail.id'=>$id));
		// 	// $this->_weekly_plan_details($rejection_details['ProductionRejection']['production_weekly_plan_id']);
		// }else{
		// // 	// $this->RejectionDetail->delete(array('RejectionDetail.id'=>$id));	

		// }
		
		
		// debug($production);
		// exit;
		// $newRejections = $rejection_details['ProductionRejection']['number_of_rejections'] - $rejection_details['RejectionDetail']['number_of_rejections'];
		// $this->ProductionRejection->read(null,$rejection_details['ProductionRejection']['production_id']);
		// $this->ProductionRejection->set('number_of_rejections',$newRejections);
		// $this->ProductionRejection->save();

		// exit;		


		
		
		$this->redirect(array('controller'=>'productions', 'action' => 'view',$rejection_details['ProductionRejection']['production_id']));
	}
 
	public function delete($id){
		$this->Session->setFlash(__('Production rejection can not be deleted from here. Goto view rejections page and delete'));
		$this->redirect(array('controller'=> 'production_rejections', 'action' => 'index'));
		

	}
	
	
	public function get_template($id = null){
		$this->autoRender = false;
		$productionInspectionTemplate = $this->ProductionRejection->ProductionInspectionTemplate->find('first',array(
			'fields'=>array('ProductionInspectionTemplate.id','ProductionInspectionTemplate.template'),
			'conditions'=>array('ProductionInspectionTemplate.publish'=>1,'ProductionInspectionTemplate.soft_delete'=>0,'ProductionInspectionTemplate.id'=>$this->request->params['named']['id'])));
		$template = $productionInspectionTemplate['ProductionInspectionTemplate']['template'];
		return $template;
		exit;
	}

	public function _update_rejections($production_id = null, $product_id = null, $total_quantity  = null, $number_of_rejections  = null,$rejection_id = null){
		// Configure::write('debug',1);
		// $this->loadModel('RejectionDetail');
		// $this->loadModel('Production');
		
		// $production = $this->Production->find('first',array('recursive'=>-1, 'conditions'=>array('Production.id'=>$production_id)));

		// $productionRejections = $this->ProductionRejection->find('all',array(
		// 	'recursive'=>-1,
		// 	'fields'=>array('ProductionRejection.id','ProductionRejection.production_id','ProductionRejection.production_id','ProductionRejection.total_quantity','ProductionRejection.number_of_rejections'),
		// 	'conditions'=>array('ProductionRejection.production_id'=>$production_id)));
		// $total = 0;
		
		// foreach ($productionRejections as $productionRejection) {
		// 	$rejections = $this->RejectionDetail->find('all',array(
		// 		'recursive'=>-1,
		// 		'conditions'=>array('RejectionDetail.production_rejection_id'=>$productionRejection['ProductionRejection']['id'],'RejectionDetail.publish'=>1)));
		// 	foreach ($rejections as $rejection) {				
		// 		$total = $total + $rejection['RejectionDetail']['number_of_rejections'];
		// 	}
		// }
		// $balance = $production['Production']['balance'];
		// // debug($balance);
		// // exit;
		// // $p['Production']['id'] = $production_id;
		// // $p['Production']['rejections'] = $total;
		// // $p['Production']['balance'] = $balance;
		// // $p['Production']['production_date'] = $production['Production']['production_date'];
		// // $p['Production']['publish'] = $production['Production']['publish'];
		// // $this->ProductionRejection->Production->create();
		// // $this->ProductionRejection->Production->save($p,false);
		
		// // $productions = $this->ProductionRejection->Production->find('all',array(
		// // 	'conditions'=>array(
		// // 		'Production.id != ' => $production_id,
		// // 		'Production.production_weekly_plan_id'=>$production['Production']['production_weekly_plan_id'])));
		
		// $result = $this->_weekly_plan_details($production['Production']['production_weekly_plan_id']);
		// $balance = $planDetails['balance'] - $planDetails['total_number_of_rejections'] - $this->data['Production']['actual_production_number'];
		// // exit;
		// // foreach ($productions as $pro) {
		// // 	$newBalance = $pro['Production']['balance'] + $total;
		// // 	$p['Production']['id'] = $pro['Production']['id'];
		// // 	$p['Production']['rejections'] = $pro['Production']['rejections'];
		// // 	$p['Production']['balance'] = $newBalance;
		// // 	$p['Production']['production_date'] = $pro['Production']['production_date'];
		// // 	$p['Production']['publish'] = $pro['Production']['publish'];
		// // 	$this->ProductionRejection->Production->create();
		// // 	$this->ProductionRejection->Production->save($pro,false);
		// // }


		// return true;




	}

	public function _rejection_history($production_id = null, $product_id = null){
		$this->loadModel('RejectionDetail');
		$productionRejections = $this->ProductionRejection->find('list',array('conditions'=>array('ProductionRejection.production_id'=>$production_id)));
		
		foreach ($productionRejections as $key => $value) {
			$rejectionDetail = $this->RejectionDetail->find('all',array('conditions'=>array('RejectionDetail.production_rejection_id'=>$key))); 
			
			if($rejectionDetail){
				foreach ($rejectionDetail as $rej) {
					$rejectionDetails[] = $rej;
				}
			}
			
		}
		return $rejectionDetails;

	}

	public function add_rejection_details($i = null){
		$this->set('i', $i);

		// $productionInspectionTemplates = $this->ProductionRejection->ProductionInspectionTemplate->find('list',array('conditions'=>array('ProductionInspectionTemplate.publish'=>1,'ProductionInspectionTemplate.soft_delete'=>0)));
		// $valueDrivers = $this->ProductionRejection->ValueDriver->find('list',array('conditions'=>array('ValueDriver.publish'=>1,'ValueDriver.soft_delete'=>0)));
		// $performanceIndicators = $this->ProductionRejection->PerformanceIndicator->find('list',array('conditions'=>array('PerformanceIndicator.publish'=>1,'PerformanceIndicator.soft_delete'=>0)));
		$defectTypes = $this->ProductionRejection->DefectType->find('list',array('recursive'=>-1, 'conditions'=>array('DefectType.publish'=>1,'DefectType.soft_delete'=>0)));
		// foreach ($allDefectTypes as $defectType) {
		// 	$performanceIndicator = $this->ProductionRejection->PerformanceIndicator->find('first',array(
		// 		'fields'=>array('PerformanceIndicator.id','PerformanceIndicator.name','ValueDriver.id','ValueDriver.name'),
		// 		'conditions'=>array('DefectType.id'=>$defectType['DefectType']['id']),
		// 		'recursive'=>0));
		// 	// debug($performanceIndicator);
		// 	if($performanceIndicator){
		// 		$defectTypes[$defectType['DefectType']['id']] = $defectType['DefectType']['name'] . ' - (' . $performanceIndicator['PerformanceIndicator']['name'] . '/'  . $performanceIndicator['ValueDriver']['name'].')';	
		// 	}else{
		// 		$defectTypes[$defectType['DefectType']['id']] = $defectType['DefectType']['name'] . ' - (Not Linked : Not Linked)';
		// 	}
			
		// }
		$this->set(compact('defectTypes'));
	}

	public function _rejection_details($id = null,$data = null,$production_data = null,$production_weekly_plan_id = null){
		$this->loadModel('RejectionDetail');
		$this->RejectionDetail->deleteAll(array('RejectionDetail.production_rejection_id' => $id), false);
		foreach ($data as $rejectionDetail) {	
			if($rejectionDetail['number_of_rejections'] > 0){
				try{
					$newData['production_rejection_id'] = $id;
					$newData['production_weekly_plan_id'] = $production_weekly_plan_id;
					$newData['value_driver_id'] = $rejectionDetail['value_driver_id'];
					// $newData['defect_type_id'] = $rejectionDetail['defect_type_id'];
					$newData['number_of_rejections'] = $rejectionDetail['number_of_rejections'];
					$newData['defect_type_id'] = $rejectionDetail['defect_type_id'];
					$newData['soft_delete'] = 0;
					$newData['publish'] = $production_data['publish'];
					$newData['prepared_by'] = $production_data['prepared_by'];
					$newData['approved_by'] = $production_data['approved_by'];
					$this->RejectionDetail->create();
					$this->RejectionDetail->save($newData, false);					
				}catch (Exception $e) {
				    
				}
			}
		}

	}
}

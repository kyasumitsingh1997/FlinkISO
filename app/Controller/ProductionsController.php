<?php

App::uses('AppController', 'Controller');

/**
 * Productions Controller
 *
 * @property Production $Production
 */
class ProductionsController extends AppController {

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
        $this->paginate = array('order' => array('Production.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->Production->recursive = 1;
        $this->Production->hasMany['ProductionRejection']['fields'] = array('ProductionRejection.id','ProductionRejection.name','ProductionRejection.number_of_rejections','ProductionRejection.production_id');
        $this->set('productions', $this->paginate());

        $this->_get_count();

        $currentStatus = $this->Production->customArray['current_status'];
        $this->set(compact('currentStatus'));
    }

    /**
     * adcanced_search method
     * Advanced search by - TGS
     * @return void
     */
    public function advanced_search() {

        $conditions = array();
        if ($this->request->query['keywords']) {
            $searchArray = array();
            if ($this->request->query['strict_search'] == 0) {
                $searchKeys[] = $this->request->query['keywords'];
            } else {
                $searchKeys = explode(" ", $this->request->query['keywords']);
            }
            foreach ($searchKeys as $searchKey):
                foreach ($this->request->query['search_fields'] as $search):
                    if ($this->request->query['strict_search'] == 0)
                        $searchArray[] = array('Production.' . $search => $searchKey);
                    else
                        $searchArray[] = array('Production.' . $search . ' like ' => '%' . $searchKey . '%');
                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('Production.branchid' => $branches);
            endforeach;

            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }

        if ($this->request->query['product_id'] != -1) {
            $productConditions = array('Production.product_id' => $this->request->query['product_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $productConditions);
            else
                $conditions[] = array('or' => $productConditions);
        }

        if ($this->request->query['employee_id'] != -1) {
            $employeeConditions = array('Production.employee_id' => $this->request->query['employee_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $employeeConditions);
            else
                $conditions[] = array('or' => $employeeConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('Production.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'Production.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);



        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('Production.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('Production.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->Production->recursive = 0;
        $this->paginate = array('order' => array('Production.sr_no' => 'DESC'), 'conditions' => $conditions, 'Production.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('productions', $this->paginate());

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
        if (!$this->Production->exists($id)) {
            throw new NotFoundException(__('Invalid production'));
        }
        $options = array('conditions' => array('Production.' . $this->Production->primaryKey => $id));
        $productions = $this->Production->find('first', $options);
        

        
        foreach ($productions['ProductionRejection'] as $rejections) {
            $rejection_details = $this->Production->ProductionRejection->RejectionDetail->find('all',array(
                'fields'=>array(
                        'RejectionDetail.id','RejectionDetail.production_rejection_id','RejectionDetail.defect_type_id','RejectionDetail.number_of_rejections','RejectionDetail.publish',
                        'DefectType.id','DefectType.name','ProductionRejection.production_inspection_template_id'
                    ),
                'conditions'=>array('RejectionDetail.production_rejection_id'=>$rejections['id'])));
            $rejections['RejectionDetail'] = $rejection_details;
            $newRejections[] = $rejections;
        }   
        $productions['ProductionRejection'] = $newRejections;
        $this->set('production', $productions);

        $currentStatus = $this->Production->customArray['current_status'];
        $this->set(compact('currentStatus'));

        $this->loadModel('Material');
        $materials = $this->Material->find('list');
        $this->set(compact('materials'));

        $this->loadModel('ProductionInspectionTemplate');
        $productionInspectionTemplates = $this->ProductionInspectionTemplate->find('list');
        $this->set(compact('productionInspectionTemplates'));

    }

    /**
     * list method
     *
     * @return void
     */
    public function lists() {

        $this->_get_count();
        // redirect if weekly plan is no selected
        if(!$this->request->params['named']['production_weekly_plan_id']){
            $this->Session->setFlash(__('Select weekly plan first'));
            $this->redirect(array('controller' => 'production_weekly_plans', 'action' => 'index'));
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
            
            $this->request->data['Production']['system_table_id'] = $this->_get_system_table_id();
            
            $this->request->data['Production']['balance'] = $this->request->data['Production']['production_planned'] - $this->request->data['Production']['actual_production_number'];
            if ($this->Production->save($this->request->data)) {
                $week = $this->Production->ProductionWeeklyPlan->find('first',array('recursive'=>-1, 'conditions'=>array('ProductionWeeklyPlan.id'=>$this->data['Production']['production_weekly_plan_id'])));
                
                $this->_weekly_plan_details($week['ProductionWeeklyPlan']['id']);

                // only if production record is published
                if($this->request->data['Production']['publish'] == 1){
                    foreach ($this->data['Stock'] as $stock) {
                        $stocks['Stock']['material_id'] = $stock['material_id'];
                        $stocks['Stock']['production_id'] = $this->Production->id;
                        $stocks['Stock']['production_date'] = $this->request->data['Production']['production_date'];
                        $stocks['Stock']['quantity_consumed'] = $stock['quantity_consumed'];
                        $stocks['Stock']['branch_id'] = $this->data['Production']['branch_id'];
                        $stocks['Stock']['batch_number'] = $this->data['Production']['batch_number'];
                        $stocks['Stock']['type'] = 0;
                        $stocks['Stock']['soft_delete'] = 0;
                        $stocks['Stock']['publish'] = $this->data['Production']['publish'];
                        $this->loadModel('Stock');
                        $this->Stock->create();
                        $this->Stock->save($stocks,false);

                        $this->loadModel('Material');
                        $this->Material->create();
                        
                        $materialStock = $this->Material->find('first',array(
                            'recursive'=>-1,
                            'fields'=>array('Material.id','Material.stock_in_hand'),
                            'conditions'=>array('Material.id'=>$stock['material_id'])));
                        $materialUpdate['Material']['id'] = $stock['material_id'];
                        $materialUpdate['Material']['publish'] = 1;
                        
                        // update stock status                        
                        $this->_update_stocks($stock['material_id'], $stockData['quantity']);    
                    }

                    
                    

                    
                    $this->Production->ProductionWeeklyPlan->read(null,$week['ProductionWeeklyPlan']['id']);
                    $this->Production->ProductionWeeklyPlan->set(array('balance'=>$balance,'current_status'=>$this->request['data']['Production']['current_status']));
                    $this->Production->ProductionWeeklyPlan->save();
            
                }
                
                $this->Session->setFlash(__('The production has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Production->id));
                else
                    $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
            } else {
                $this->Session->setFlash(__('The production could not be saved. Please, try again.'));
            }
        }
        if($this->request->params['named']['product_id'])$products = $this->Production->Product->find('list', array('conditions' => array('Product.id'=>$this->request->params['named']['product_id'], 'Product.publish' => 1, 'Product.soft_delete' => 0)));
        else $products = $this->Production->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        
        if($this->request->params['named']['production_weekly_plan_id'])$productionWeeklyPlans = $this->Production->ProductionWeeklyPlan->find('list',array('fields'=>array('ProductionWeeklyPlan.id','ProductionWeeklyPlan.name'), 'conditions'=>array('ProductionWeeklyPlan.id'=>$this->request->params['named']['production_weekly_plan_id'], 'ProductionWeeklyPlan.publish'=>1,'ProductionWeeklyPlan.soft_delete'=>0)));
        else $productionWeeklyPlans = $this->Production->ProductionWeeklyPlan->find('list',array('fields'=>array('ProductionWeeklyPlan.id','ProductionWeeklyPlan.name'), 'conditions'=>array('ProductionWeeklyPlan.publish'=>1,'ProductionWeeklyPlan.soft_delete'=>0)));
        
        $productionCategories = $this->Production->ProductionCategory->find('list', array('conditions' => array('ProductionCategory.publish' => 1, 'ProductionCategory.soft_delete' => 0)));
        $branches = $this->Production->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $employees = $this->Production->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $currentStatus = $this->Production->customArray['current_status'];
        $this->set(compact('products', 'productionCategories', 'branches', 'employees','currentStatus','productionWeeklyPlans'));

        // get week numbers for 1-5 years
        $startDate = date('Y-m-d',strtotime('-1 year'));
        $endDate = date('Y-m-d',strtotime('+5 year'));
        while ($startDate <= $endDate) 
        {
            $week = date('W',strtotime($startDate));
            $weeks[date('y',strtotime($startDate)).date('W',strtotime($startDate))] = date('y',strtotime($startDate)).date('W',strtotime($startDate));
            $startDate = date("Y-m-d", strtotime("+7 day", strtotime($startDate)));
        }   
        $currentWeek = date('y').date('W');
        // $this->set(compact('weeks','currentWeek'));

        $batch = $this->Production->find('list',array('limit'=>1, 'conditions'=>array(),'order'=>array('CAST(Production.batch_number as SIGNED )'=>'DESC'), 'fields'=>array('Production.id','Production.batch_number')));
        $batch = array_values($batch);
        $batch = $batch[0] + 1;

        // echo $batch;
        
        $this->set(compact('productionWeeklyPlans', 'weeks','currentWeek','batch'));
     }

     // public function prduction_plan(){

     // }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Production->exists($id)) {
            throw new NotFoundException(__('Invalid production'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Production']['system_table_id'] = $this->_get_system_table_id();
            
            $this->request->data['Production']['balance'] = $this->request->data['Production']['production_planned'] - $this->request->data['Production']['actual_production_number'];
            
            if ($this->Production->save($this->request->data)) {
                if($this->request->data['Production']['publish'] == 1){
                    $this->loadModel('Stock');
                    foreach ($this->data['Stock'] as $stock) {
                        $this->Stock->deleteAll(array('Stock.production_id'=>$this->request->data['Production']['id']),false);
                    }
                    foreach ($this->data['Stock'] as $stock) {
                        $stocks['Stock']['material_id'] = $stock['material_id'];
                        $stocks['Stock']['production_id'] = $this->Production->id;
                        $stocks['Stock']['production_date'] = $this->request->data['Production']['production_date'];
                        $stocks['Stock']['quantity_consumed'] = $stock['quantity_consumed'];
                        $stocks['Stock']['branch_id'] = $this->data['Production']['branch_id'];
                        $stocks['Stock']['batch_number'] = $this->data['Production']['batch_number'];
                        $stocks['Stock']['type'] = 0;
                        $stocks['Stock']['soft_delete'] = 0;
                        $stocks['Stock']['publish'] = $this->data['Production']['publish'];
                        
                        $this->Stock->create();
                        $this->Stock->save($stocks,false);

                    $this->loadModel('Material');
                    $this->Material->create();
                    
                    $materialStock = $this->Material->find('first',array(
                        'recursive'=>-1,
                        'fields'=>array('Material.id','Material.stock_in_hand'),
                        'conditions'=>array('Material.id'=>$stock['material_id'])));
                    $materialUpdate['Material']['id'] = $stock['material_id'];
                    $materialUpdate['Material']['publish'] = 1;
                    
                    $this->_update_stocks($stock['material_id'], $stockData['quantity']);    
                }
                    $week = $this->Production->ProductionWeeklyPlan->find('first',array('recursive'=>-1, 'conditions'=>array('ProductionWeeklyPlan.id'=>$this->data['Production']['production_weekly_plan_id'])));
                    $balance = $week['ProductionWeeklyPlan']['production_planned'];
                    $productions = $this->Production->find('all',array(
                        'recursive'=>-1,
                        'conditions'=>array(
                            'Production.product_id'=>$this->data['Production']['product_id'],
                            'Production.production_weekly_plan_id'=>$this->data['Production']['production_weekly_plan_id']
                        )));
                        foreach ($productions as $production) {
                            $balance = $balance - $production['Production']['actual_production_number'];

                        }
                    

                    $this->Production->ProductionWeeklyPlan->read(null,$week['ProductionWeeklyPlan']['id']);
                    $this->Production->ProductionWeeklyPlan->set('balance',$balance);
                    $this->Production->ProductionWeeklyPlan->save();

                    $balance = $week['ProductionWeeklyPlan']['production_planned'];
                    $productions = $this->Production->find('all',array(
                        'recursive'=>-1,
                        'conditions'=>array(
                            'Production.product_id'=>$this->data['Production']['product_id'],
                            'Production.production_date <= '=>$this->data['Production']['production_date'],
                            'Production.production_weekly_plan_id'=>$this->data['Production']['production_weekly_plan_id']
                        )));
                        foreach ($productions as $production) {
                            $balance = $balance - $production['Production']['actual_production_number'];

                        }

                    $this->Production->read(null,$week['Production']['id']);
                    $this->Production->set('balance',$balance);
                    $this->Production->save();

                    $this->_weekly_plan_details($week['ProductionWeeklyPlan']['id']);
            }

                $this->Session->setFlash(__('The production has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The production could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Production.' . $this->Production->primaryKey => $id));
            $this->request->data = $this->Production->find('first', $options);

            if($this->request->data['Production']['publish'] == 1){
                if($this->request->data['Production']['rejections'] == 0){
                    // echo $this->Html->link('Add QC/Rejections',array('controller'=>'production_rejections','action'=>'lists','production_id'=>$production['Production']['id'],'product_id'=>$production['Production']['product_id']),array('class'=>'btn btn-xs btn-danger'));     
                }elseif($this->request->data['Production']['rejections'] > 0 && $this->Session->read('User.is_mr') == 1){
                    // echo $this->Html->link('Update QC/Rejections',array('controller'=>'productions','action'=>'view',$production['Production']['id']),array('class'=>'btn btn-xs btn-danger'));     

                }else{
                    $this->Session->setFlash(__('The production could not be updated by non-admin users.'));       
                    $this->redirect(array('action' => 'index'));
                }
                
            }
        }
        

        $products = $this->Production->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $productionWeeklyPlans = $this->Production->ProductionWeeklyPlan->find('list',array('fields'=>array('ProductionWeeklyPlan.id','ProductionWeeklyPlan.week'), 'conditions'=>array('ProductionWeeklyPlan.publish'=>1,'ProductionWeeklyPlan.soft_delete'=>0)));
        $productionCategories = $this->Production->ProductionCategory->find('list', array('conditions' => array('ProductionCategory.publish' => 1, 'ProductionCategory.soft_delete' => 0)));
        $branches = $this->Production->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $employees = $this->Production->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $currentStatus = $this->Production->customArray['current_status'];
        $this->set(compact('products', 'productionCategories', 'branches', 'employees','currentStatus','productionWeeklyPlans'));

        // get week numbers for 1-5 years
        $startDate = date('Y-m-d',strtotime('-1 year'));
        $endDate = date('Y-m-d',strtotime('+5 year'));
        while ($startDate <= $endDate) 
        {
            $week = date('W',strtotime($startDate));
            $weeks[date('y',strtotime($startDate)).date('W',strtotime($startDate))] = date('y',strtotime($startDate)).date('W',strtotime($startDate));
            $startDate = date("Y-m-d", strtotime("+7 day", strtotime($startDate)));
        }   
        $currentWeek = date('y').date('W');
        $batch = $this->Production->find('list',array('limit'=>1, 'conditions'=>array(),'order'=>array('CAST(Production.batch_number as SIGNED )'=>'DESC'), 'fields'=>array('Production.id','Production.batch_number')));
        $batch = array_values($batch);
        $batch = $batch[0] + 1;
        $this->set(compact('weeks','currentWeek','batch'));
     }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approval_id = null) {
        if (!$this->Production->exists($id)) {
            throw new NotFoundException(__('Invalid production'));
        }

        $this->loadModel('Approval');
        if (!$this->Approval->exists($approval_id)) {
            throw new NotFoundException(__('Invalid approval id'));
        }

        $approval = $this->Approval->read(null, $approval_id);
        $this->set('same', $approval['Approval']['user_id']);

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            unset($this->request->data['Production']['end_date']);
            $dateRange = split('-', $this->request->data['Production']['start_date']);
            $start_date = rtrim(ltrim($dateRange[0]));
            $end_date = rtrim(ltrim($dateRange[1]));
            
            $this->request->data['Production']['start_date'] = date('Y-m-d',strtotime($start_date));
            $this->request->data['Production']['end_date'] = date('Y-m-d',strtotime($end_date));

            if ($this->Production->save($this->request->data)) {
                if($this->request->data['Production']['publish'] == 1){
                    $this->loadModel('Stock');
                    foreach ($this->data['Stock'] as $stock) {
                        $this->Stock->deleteAll(array('Stock.production_id'=>$this->request->data['Production']['id']),false);
                    }
                    foreach ($this->data['Stock'] as $stock) {
                    $stocks['Stock']['material_id'] = $stock['material_id'];
                    $stocks['Stock']['production_id'] = $this->Production->id;
                    $stocks['Stock']['production_date'] = $this->request->data['Production']['production_date'];
                    $stocks['Stock']['quantity_consumed'] = $stock['quantity_consumed'];
                    $stocks['Stock']['branch_id'] = $this->data['Production']['branch_id'];
                    $stocks['Stock']['batch_number'] = $this->data['Production']['batch_number'];
                    $stocks['Stock']['type'] = 0;
                    $stocks['Stock']['soft_delete'] = 0;
                    $stocks['Stock']['publish'] = $this->data['Production']['publish'];
                    // $this->loadModel('Stock');
                    $this->Stock->create();
                    $this->Stock->save($stocks,false);

                    $this->loadModel('Material');
                    $this->Material->create();
                    
                    $materialStock = $this->Material->find('first',array(
                        'recursive'=>-1,
                        'fields'=>array('Material.id','Material.stock_in_hand'),
                        'conditions'=>array('Material.id'=>$stock['material_id'])));
                    $materialUpdate['Material']['id'] = $stock['material_id'];
                    $materialUpdate['Material']['publish'] = 1;
                    
                    // update stock status                        
                    $this->_update_stocks($stock['material_id'], $stockData['quantity']);    
                }

                $week = $this->Production->ProductionWeeklyPlan->find('first',array('recursive'=>-1, 'conditions'=>array('ProductionWeeklyPlan.id'=>$this->data['Production']['production_weekly_plan_id'])));
                $balance = $week['ProductionWeeklyPlan']['production_planned'];
                $productions = $this->Production->find('all',array(
                    'recursive'=>-1,
                    'conditions'=>array('Production.product_id'=>$this->data['Production']['product_id'],'Production.production_weekly_plan_id'=>$this->data['Production']['production_weekly_plan_id'])));
                    foreach ($productions as $production) {
                        $balance = $balance - $production['Production']['actual_production_number'];

                    }
                // $balance = $balance - $this->data['Production']['actual_production_number'];

                $this->Production->ProductionWeeklyPlan->read(null,$week['ProductionWeeklyPlan']['id']);
                $this->Production->ProductionWeeklyPlan->set('balance',$balance);
                $this->Production->ProductionWeeklyPlan->save();

                $balance = $week['ProductionWeeklyPlan']['production_planned'];
                $productions = $this->Production->find('all',array(
                    'recursive'=>-1,
                    'conditions'=>array(
                        'Production.product_id'=>$this->data['Production']['product_id'],
                        'Production.production_date <= '=>$this->data['Production']['production_date'],
                        'Production.production_weekly_plan_id'=>$this->data['Production']['production_weekly_plan_id']
                    )));
                    foreach ($productions as $production) {
                        $balance = $balance - $production['Production']['actual_production_number'];

                    }

                $this->Production->read(null,$week['Production']['id']);
                $this->Production->set('balance',$balance);
                $this->Production->save();
            }

                $this->Session->setFlash(__('The production has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals ();

            } else {
                $this->Session->setFlash(__('The production could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Production.' . $this->Production->primaryKey => $id));
            $this->request->data = $this->Production->find('first', $options);
        }
        $products = $this->Production->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $productionWeeklyPlans = $this->Production->ProductionWeeklyPlan->find('list',array('fields'=>array('ProductionWeeklyPlan.id','ProductionWeeklyPlan.week'), 'conditions'=>array('ProductionWeeklyPlan.publish'=>1,'ProductionWeeklyPlan.soft_delete'=>0)));
        $productionCategories = $this->Production->ProductionCategory->find('list', array('conditions' => array('ProductionCategory.publish' => 1, 'ProductionCategory.soft_delete' => 0)));
        $branches = $this->Production->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $employees = $this->Production->Employee->find('list', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0)));
        $currentStatus = $this->Production->customArray['current_status'];
        $this->set(compact('products', 'productionCategories', 'branches', 'employees','currentStatus','productionWeeklyPlans'));

        // get week numbers for 1-5 years
        $startDate = date('Y-m-d',strtotime('-1 year'));
        $endDate = date('Y-m-d',strtotime('+5 year'));
        while ($startDate <= $endDate) 
        {
            $week = date('W',strtotime($startDate));
            $weeks[date('y',strtotime($startDate)).date('W',strtotime($startDate))] = date('y',strtotime($startDate)).date('W',strtotime($startDate));
            $startDate = date("Y-m-d", strtotime("+7 day", strtotime($startDate)));
        }   
        $currentWeek = date('y').date('W');
        $batch = $this->Production->find('list',array('limit'=>1, 'conditions'=>array(),'order'=>array('CAST(Production.batch_number as SIGNED )'=>'DESC'), 'fields'=>array('Production.id','Production.batch_number')));
        $batch = array_values($batch);
        $batch = $batch[0] + 1;
        $this->set(compact('weeks','currentWeek','batch'));

    }
    public function get_batch($batchNumber = null, $id = null) {
        if ($batchNumber) {
            if ($id){
                $batchNumbers = $this->Production->find('all', array('conditions' => array('Production.batch_number' => $batchNumber, 'Production.id !=' => $id)));
            }else {
                $batchNumbers = $this->Production->find('all', array('conditions' => array('Production.batch_number' => $batchNumber)));
              }
           if(count($batchNumbers))
           {
            echo "Batch number already exists, please enter another batch number";
           }
         exit;
            }
        }

    public function data_backup(){

        if ($this->request->is('post') || $this->request->is('put')) {
            $dateRange = split('-', $this->request->data['Production']['date_range']);
            $startDate = rtrim(ltrim($dateRange[0]));
            $endDate = rtrim(ltrim($dateRange[1]));
            while (strtotime($startDate) <= strtotime($endDate)) {            
                $week = date('yW',strtotime($startDate));
                $month = date('M-Y',strtotime($startDate));
                if($this->data['Production']['product_id'])$products = $this->Production->Product->find('list',array('order'=>array('Product.name'=>'ASC'),'conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0,'Product.id'=>$this->data['Production']['product_id'])));
                else $products = $this->Production->Product->find('list',array('order'=>array('Product.name'=>'ASC'),'conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));
                
                foreach ($products as $key => $value) {
                    $planned = $actual = $rejections = 0;
                    $getweekid = $this->Production->ProductionWeeklyPlan->find('first',array('conditions'=>array('ProductionWeeklyPlan.week'=>$week,'ProductionWeeklyPlan.product_id'=>$key)));
                    $productions = $this->Production->find('all',array(
                        'recursive'=>-1,
                        'fields'=>array('Production.id','Production.production_planned','Production.actual_production_number','Production.rejections','Production.product_id'),
                        'conditions'=>array('Production.publish'=>1,'Production.soft_delete'=>0,'Production.product_id'=>$key,'Production.production_weekly_plan_id'=>$getweekid['ProductionWeeklyPlan']['id'])));
                    if($productions){
                        foreach ($productions as $production) {                        
                            $planned = $getweekid['ProductionWeeklyPlan']['production_planned'];
                            $actual = $actual + $production['Production']['actual_production_number'];
                            $rejections = $rejections + $production['Production']['rejections'];
                        }    
                    }else{
                        $planned = $planned + $getweekid['ProductionWeeklyPlan']['production_planned'];
                        $actual = $actual + 0;
                        $rejections = $rejections + 0;
                    }
                    
                    $planned_sub = $planned_sub + $planned;
                    $actual_sub = $actual_sub + $actual;
                    $rejections_sub = $rejections_sub + $rejections;

                    $results[$value][$month][$week] = array(
                            'planned'=>$planned,'actual'=>$actual,'rejections'=>$rejections,
                            // 'planned_sub'=>$planned_sub,'actual_sub'=>$actual_sub,'rejections_sub'=>$rejections_sub
                        );
                }
                
                $weeks[$month][$week] = $week;
                $startDate = date("Y-m-d", strtotime("+1 week", strtotime($startDate)));
            }
            $this->set('weeks',$weeks);
            $this->set('results',$results);
        }    
        $products = $this->Production->Product->find('list',array('conditions'=>array('Product.publish'=>1,'Product.soft_delete'=>0)));    
        $this->set(compact('products'));
    } 

    public function get_plan(){
        $this->autoRender = false;
        $product_id = $this->request->params['named']['product_id'];
        $planned = $this->Production->ProductionWeeklyPlan->find('all',array('conditions'=>array('ProductionWeeklyPlan.balance >' => 0, 'ProductionWeeklyPlan.product_id'=>$product_id)));
        if($planned){
            $con_str .= '<option value=-1>Select</option>' ;
            foreach ($planned as $plan) {                
                $con_str .= '<option value=' . $plan['ProductionWeeklyPlan']['id'] .'>Week : ' . $plan['ProductionWeeklyPlan']['week'] . ' : Qty Planned : ' . $plan['ProductionWeeklyPlan']['production_planned'] . '</option>' ;
            }            
        }else{
            $con_str .= '<option value=-1>Select</option>' ;            
        }
        return $p['weeks'] = $con_str;
        exit;        
    }

    public function get_history(){

        $product_id = $this->request->params['named']['product_id'];
        $production_weekly_plan_id = $this->request->params['named']['week_id'];
        $productions = $this->Production->find('all',array('conditions'=>array('Production.product_id'=>$product_id,'Production.production_weekly_plan_id'=>$production_weekly_plan_id)));
        
        $weeklyplan = $this->Production->ProductionWeeklyPlan->find('first',array('recursive'=>-1, 'conditions'=>array('ProductionWeeklyPlan.id'=>$production_weekly_plan_id)));
        
        if($weeklyplan)$planned = $weeklyplan['ProductionWeeklyPlan']['production_planned'];
        else $planned = 0;
        $this->set(compact('productions','planned','weeklyplan'));        
        $currentStatus = $this->Production->customArray['current_status'];
        $this->set(compact('currentStatus'));
        // return $planned;
    }

    public function get_stocks(){
        $product_id = $this->request->params['named']['product_id'];
        $meterials = $this->Production->Product->ProductMaterial->find('all',array('fields'=>array('ProductMaterial.id','ProductMaterial.material_id','ProductMaterial.quantity'), 'conditions'=>array('ProductMaterial.product_id'=>$product_id)));
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
        $this->set(compact('stocks','units'));        
    }

    public function check_plan($production_weekly_plan_id = null){
        $plan = $this->Production->ProductionWeeklyPlan->find('first',array('conditions'=>array('ProductionWeeklyPlan.id'=>$production_weekly_plan_id)));
        if($plan){
            $productions = $this->Production->find('all',array('conditions'=>array('Production.production_weekly_plan_id'=>$plan['ProductionWeeklyPlan']['id'])));

            if($productions){
                foreach ($productions as $production) {
                    $qty = $qty + $production['Production']['actual_production_number'];
                }
            }
        }
        if($qty == $plan['ProductionWeeklyPlan']['production_planned']){
            return true;
        }else{
            return false;
        }        
    }

    public function delete($id = null)
    {
        //get weekly id
        $production = $this->Production->find('first',array('conditions'=>array('Production.id'=>$id),'recursive'=>-1));
        $productionRejections = $this->Production->ProductionRejection->find('list',array('conditions'=>array('ProductionRejection.production_id'=>$id)));
        foreach ($productionRejections as $key => $val) {
            $rejectionDetails = $this->Production->ProductionRejection->RejectionDetail->find('list',array('conditions'=>array('RejectionDetail.production_rejection_id'=>$key)));
            foreach ($rejectionDetails as $rkey => $rvalue) {
                $this->Production->ProductionRejection->RejectionDetail->delete(array('RejectionDetail.id'=>$rkey));
            }
            $this->Production->ProductionRejection->delete(array('ProductionRejection.id'=>$key));
        }
        
        $this->_weekly_plan_details($production['Production']['production_weekly_plan_id']);
        
        $this->Production->delete(array('RejectionDetail.id'=>$id));
        $this->redirect(array('action' => 'index'));
    }
    
}

<?php

App::uses('AppController', 'Controller');

/**
 * PurchaseOrders Controller
 *
 * @property PurchaseOrder $PurchaseOrder
 */
class PurchaseOrdersController extends AppController {

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
        $this->paginate = array('order' => array('PurchaseOrder.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->PurchaseOrder->recursive = 0;
        $purchaseOrders = $this->paginate();
        foreach ($purchaseOrders as $purchaseOrder) {
            $dcs = $this->PurchaseOrder->DeliveryChallan->find('list',array('conditions'=>array('DeliveryChallan.purchase_order_id'=>$purchaseOrder['PurchaseOrder']['id'],'DeliveryChallan.publish'=>1,'DeliveryChallan.soft_delete'=>0)));
            $purchaseOrder['DeliveryChallan'] = $dcs;
            $newPurchaseOrders[] = $purchaseOrder;
        }   

        debug($newPurchaseOrders);
        $this->set('purchaseOrders', $newPurchaseOrders);
        $this->_get_count();
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
                        $searchArray[] = array('PurchaseOrder.' . $search => $searchKey);
                    else
                        $searchArray[] = array('PurchaseOrder.' . $search . ' like ' => '%' . $searchKey . '%');
                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }
        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('PurchaseOrder.branchid' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }
        if ($this->request->query['type']) {
            $typeConditions = array('PurchaseOrder.type' => $this->request->query['type']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $typeConditions);
            else
                $conditions[] = array('or' => $typeConditions);
        }
        if ($this->request->query['customer_id'] != -1) {
            $customerConditions[] = array('PurchaseOrder.customer_id' => $this->request->query['customer_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $customerConditions);
            else
                $conditions[] = array('or' => $customerConditions);
        }
        if ($this->request->query['supplier_registration_id'] != -1) {

            $supplierRegistrationConditions[] = array('PurchaseOrder.supplier_registration_id' => $this->request->query['supplier_registration_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $supplierRegistrationConditions);
            else
                $conditions[] = array('or' => $supplierRegistrationConditions);
        }
        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('PurchaseOrder.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'PurchaseOrder.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);



        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('PurchaseOrder.branchid' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('PurchaseOrder.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->PurchaseOrder->recursive = 0;
        $this->paginate = array('order' => array('PurchaseOrder.sr_no' => 'DESC'), 'conditions' => $conditions, 'PurchaseOrder.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('purchaseOrders', $this->paginate());

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
        if (!$this->PurchaseOrder->exists($id)) {
            throw new NotFoundException(__('Invalid purchase order'));
        }
        $options = array('conditions' => array('PurchaseOrder.' . $this->PurchaseOrder->primaryKey => $id));
        $this->set('purchaseOrder', $this->PurchaseOrder->find('first', $options));

        $purchaseOrderDetails = $this->PurchaseOrder->PurchaseOrderDetail->find('all', array('conditions' => array('PurchaseOrderDetail.purchase_order_id ' => $id)));
        $this->set('purchaseOrderDetails', $purchaseOrderDetails);

        $stocks = $this->PurchaseOrder->Stock->find('all',array('conditions'=>array('Stock.purchase_order_id'=>$id)));
        $this->set('stocks',$stocks);        
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
            $this->request->data['PurchaseOrder']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['PurchaseOrder']['created_by'] = $this->Session->read('User.id');
            $this->request->data['PurchaseOrder']['modified_by'] = $this->Session->read('User.id');
            $this->PurchaseOrder->create();
            $this->request->data['PurchaseOrder']['title'] = $this->generate_cp_number('PurchaseOrder',  'PO', 'title');
            if ($this->PurchaseOrder->save($this->request->data, false)) {
                $this->loadModel('PurchaseOrderDetail');
                $valData = array();
                foreach ($this->request->data['PurchaseOrderDetail'] as $valData) {
                    $this->PurchaseOrderDetail->create();
                    $val['order_number'] = $valData['order_number'];
                    $val['item_number'] = $valData['item_number'];
                    $val['purchase_order_id'] = $this->PurchaseOrder->id;
                    $val['product_id'] = $valData['product_id'];
                    $val['device_id'] = $valData['device_id'];
                    $val['material_id'] = $valData['material_id'];
                    $val['other'] = $valData['other'];
                    $val['quantity'] = $valData['quantity'];
                    $val['rate'] = $valData['rate'];
                    $val['discount'] = $valData['discount'];
                    $val['total'] = $valData['total'];
                    $val['quantity_dispatch'] = $valData['quantity_dispatch'];
                    $val['description'] = $valData['description'];
                    $val['publish'] = 1;
                    $val['branchid'] = $valData['branchid'];
                    $val['departmentid'] = $valData['departmentid'];
                    $val['created_by'] = $this->Session->read('User.id');
                    $val['modified_by'] = $this->Session->read('User.id');
                    $val['system_table_id'] = $this->_get_system_table_id();
                    $this->PurchaseOrderDetail->save($val, false);
                }

                $this->Session->setFlash(__('The purchase order has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->PurchaseOrder->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The purchase order could not be saved. Please, try again.'));
            }
        }
        $customers = $this->PurchaseOrder->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $supplierRegistrations = $this->PurchaseOrder->SupplierRegistration->find('list', array('conditions' => array('SupplierRegistration.publish' => 1, 'SupplierRegistration.soft_delete' => 0)));
        $this->set(compact('customers', 'supplierRegistrations'));
        $materials = $this->PurchaseOrder->PurchaseOrderDetail->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $products = $this->PurchaseOrder->PurchaseOrderDetail->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $devices = $this->PurchaseOrder->PurchaseOrderDetail->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0)));
        $currencies = $this->PurchaseOrder->Currency->find('list', array('conditions' => array('Currency.publish' => 1, 'Currency.soft_delete' => 0)));
        $default_currency = key($currencies);

        $this->set(compact('products', 'devices', 'materials','currencies','default_currency'));
        $poNumber = $this->generate_cp_number('PurchaseOrder',  'PO', 'title');
        $this->set('poNumber',$poNumber);

        $stocks = $this->requestAction(array('controller'=>'dashboards','action'=>'get_productions_stock'));
        $this->set(compact('stocks'));


        if($this->request->params['named']['project_id']){
            $projects = $this->PurchaseOrder->Project->find('list', array('conditions' => array('Project.id'=> $this->request->params['named']['project_id'],'Project.publish' => 1, 'Project.soft_delete' => 0)));
            $milestones = $this->PurchaseOrder->Project->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$this->request->params['named']['project_id'],'Milestone.soft_delete'=>0)));
            $projectActivities = $this->PurchaseOrder->Project->Milestone->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.project_id'=>$this->request->data['PurchaseOrder']['project_id'],'ProjectActivity.soft_delete'=>0)));            
            $costCategories = $this->PurchaseOrder->CostCategory->find('list',array('conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
            
            $customers = $this->PurchaseOrder->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
            
            $this->set(compact('milestones','projects','costCategories','projectActivities','customers'));


        }else{
            $projects = $this->PurchaseOrder->Project->find('list', array('conditions' => array('Project.publish' => 1, 'Project.soft_delete' => 0)));
            $this->set(compact('projects')); 
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
        if (!$this->PurchaseOrder->exists($id)) {
            throw new NotFoundException(__('Invalid purchase order'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['PurchaseOrder']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['PurchaseOrder']['modified_by'] = $this->Session->read('User.id');
            if ($this->PurchaseOrder->save($this->request->data)) {

                $this->loadModel('PurchaseOrderDetail');
                $this->PurchaseOrderDetail->deleteAll(array('PurchaseOrderDetail.purchase_order_id ' => $this->PurchaseOrder->id), false);

                foreach ($this->request->data['PurchaseOrderDetail'] as $valData) {
                    $this->PurchaseOrderDetail->create();
                    $val['order_number'] = $valData['order_number'];
                    $val['item_number'] = $valData['item_number'];
                    $val['purchase_order_id'] = $this->PurchaseOrder->id;
                    $val['product_id'] = $valData['product_id'];
                    $val['device_id'] = $valData['device_id'];
                    $val['material_id'] = $valData['material_id'];
                    $val['other'] = $valData['other'];
                    $val['quantity'] = $valData['quantity'];
                    $val['rate'] = $valData['rate'];
                    $val['discount'] = $valData['discount'];
                    $val['total'] = $valData['total'];
                    $val['quantity_dispatch'] = $valData['quantity_dispatch'];
                    $val['description'] = $valData['description'];
                    $val['publish'] = 1;
                    $val['system_table_id'] = $this->_get_system_table_id();
                    $this->PurchaseOrderDetail->save($val, false);
                }

                $this->Session->setFlash(__('The purchase order has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The purchase order could not be saved. Please, try again.'));
            }
        } else {
            $this->loadModel('DeliveryChallan');
            $deliveredPO = $this->DeliveryChallan->find('count', array('conditions' => array('DeliveryChallan.publish' => 1, 'DeliveryChallan.soft_delete' => 0, 'DeliveryChallan.purchase_order_id' => $id), 'recursive' => -1));
            if($deliveredPO != 0){
                $this->Session->setFlash(__('This purchase order could not be edited as it\'s already added to Delivey Challan.'), 'default', array('class' => 'alert-danger'));
                $this->redirect(array('action' => 'view', $id));
            } else {
                $options = array('conditions' => array('PurchaseOrder.' . $this->PurchaseOrder->primaryKey => $id));
                $this->request->data = $this->PurchaseOrder->find('first', $options);
            }
        }
        $customers = $this->PurchaseOrder->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $supplierRegistrations = $this->PurchaseOrder->SupplierRegistration->find('list', array('conditions' => array('SupplierRegistration.publish' => 1, 'SupplierRegistration.soft_delete' => 0)));
        $purchaseOrderDetail = $this->PurchaseOrder->PurchaseOrderDetail->find('all', array('conditions' => array('PurchaseOrderDetail.purchase_order_id ' => $id, 'PurchaseOrderDetail.soft_delete' => 0)));
        $materials = $this->PurchaseOrder->PurchaseOrderDetail->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $products = $this->PurchaseOrder->PurchaseOrderDetail->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $devices = $this->PurchaseOrder->PurchaseOrderDetail->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0)));
        $currencies = $this->PurchaseOrder->Currency->find('list', array('conditions' => array('Currency.publish' => 1, 'Currency.soft_delete' => 0)));
        $default_currency = key($currencies);
        // $projects = $this->PurchaseOrder->Project->find('list', array('conditions' => array('Project.publish' => 1, 'Project.soft_delete' => 0)));
        if($this->request->data['PurchaseOrder']['project_id']){
            $projects = $this->PurchaseOrder->Project->find('list', array('conditions' => array('Project.id'=> $this->request->data['PurchaseOrder']['project_id'],'Project.publish' => 1, 'Project.soft_delete' => 0)));
            $milestones = $this->PurchaseOrder->Project->Milestone->find('list',array('conditions'=>array('Milestone.project_id'=>$this->request->data['PurchaseOrder']['project_id'],'Milestone.soft_delete'=>0)));
            $projectActivities = $this->PurchaseOrder->Project->Milestone->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.project_id'=>$this->request->data['PurchaseOrder']['project_id'],'ProjectActivity.soft_delete'=>0)));
            $costCategories = $this->PurchaseOrder->CostCategory->find('list',array('conditions'=>array('CostCategory.publish'=>1,'CostCategory.soft_delete'=>0)));
            $this->set(compact('milestones','projects','costCategories','projectActivities'));            
        }else{
            $projects = $this->PurchaseOrder->Project->find('list', array('conditions' => array('Project.publish' => 1, 'Project.soft_delete' => 0)));
            $this->set(compact('projects')); 
        }
        $this->set(compact('purchaseOrderDetail', 'customers', 'supplierRegistrations', 'products', 'devices', 'materials','currencies','default_currency','projects'));
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->PurchaseOrder->exists($id)) {
            throw new NotFoundException(__('Invalid purchase order'));
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
            $this->request->data['PurchaseOrder']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['PurchaseOrder']['modified_by'] = $this->Session->read('User.id');
            if ($this->PurchaseOrder->save($this->request->data)) {

                $this->loadModel('PurchaseOrderDetail');
                $this->PurchaseOrderDetail->deleteAll(array('PurchaseOrderDetail.purchase_order_id ' => $this->PurchaseOrder->id), false);

                foreach ($this->request->data['PurchaseOrderDetail'] as $valData) {
                    $this->PurchaseOrderDetail->create();
                    $val['order_number'] = $valData['order_number'];
                    $val['item_number'] = $valData['item_number'];
                    $val['purchase_order_id'] = $this->PurchaseOrder->id;
                    $val['product_id'] = $valData['product_id'];
                    $val['device_id'] = $valData['device_id'];
                    $val['material_id'] = $valData['material_id'];
                    $val['other'] = $valData['other'];
                    $val['quantity'] = $valData['quantity'];
                    $val['rate'] = $valData['rate'];
                    $val['discount'] = $valData['discount'];
                    $val['total'] = $valData['total'];
                    $val['quantity_dispatch'] = $valData['quantity_dispatch'];
                    $val['description'] = $valData['description'];
                    $val['publish'] = 1;
                    $val['branchid'] = $valData['branchid'];
                    $val['departmentid'] = $valData['departmentid'];
                    $val['created_by'] = $this->Session->read('User.id');
                    $val['modified_by'] = $this->Session->read('User.id');
                    $val['system_table_id'] = $this->_get_system_table_id();
                    $this->PurchaseOrderDetail->save($val, false);
                }

                $this->Session->setFlash(__('The purchase order has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

            } else {
                $this->Session->setFlash(__('The purchase order could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('PurchaseOrder.' . $this->PurchaseOrder->primaryKey => $id));
            $this->request->data = $this->PurchaseOrder->find('first', $options);
        }
        $customers = $this->PurchaseOrder->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $supplierRegistrations = $this->PurchaseOrder->SupplierRegistration->find('list', array('conditions' => array('SupplierRegistration.publish' => 1, 'SupplierRegistration.soft_delete' => 0)));
        $purchaseOrderDetail = $this->PurchaseOrder->PurchaseOrderDetail->find('all', array('conditions' => array('PurchaseOrderDetail.purchase_order_id ' => $id, 'PurchaseOrderDetail.soft_delete' => 0)));
        $materials = $this->PurchaseOrder->PurchaseOrderDetail->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $products = $this->PurchaseOrder->PurchaseOrderDetail->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $devices = $this->PurchaseOrder->PurchaseOrderDetail->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0)));
        $currencies = $this->PurchaseOrder->Currency->find('list', array('conditions' => array('Currency.publish' => 1, 'Currency.soft_delete' => 0)));
        $default_currency = key($currencies);
        $projects = $this->PurchaseOrder->Project->find('list', array('conditions' => array('Project.publish' => 1, 'Project.soft_delete' => 0)));
        $this->set(compact('purchaseOrderDetail', 'customers', 'supplierRegistrations', 'products', 'devices', 'materials','currencies','default_currency','projects'));        
    }

    public function add_purchase_order_details($i = null) {
        $this->set('i', $i);
        $materials = $this->PurchaseOrder->PurchaseOrderDetail->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $products = $this->PurchaseOrder->PurchaseOrderDetail->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $devices = $this->PurchaseOrder->PurchaseOrderDetail->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0)));
        $this->set(compact('products', 'devices', 'materials'));
        $this->render('add_purchase_order_details');
    }

    public function get_purchase_order_number($purchaseOrderNumber = null, $id = null) {
        if ($purchaseOrderNumber) {
            if ($id) {
                $purchaseOrderNumbers = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.purchase_order_number' => $purchaseOrderNumber, 'PurchaseOrder.id !=' => $id)));
            } else {
                $purchaseOrderNumbers = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.purchase_order_number' => $purchaseOrderNumber)));
            }
            $this->set('purchaseOrderNumbers', $purchaseOrderNumbers);
        }
    }

    public function get_material_unit($material_id = null){
        $this->autoRender = false;
        $this->loadModel('Material');
        $unit_id = $this->Material->find('first',array(
            'fields'=>array('Material.id','Material.unit_id','Unit.id','Unit.name'),
            'conditions'=>array('Material.id'=>$this->request->params['pass'][0])));
        $unit_id = $unit_id['Unit']['name'];
        return $unit_id;
    }

    public function get_activities(){
      $this->loadModel('ProjectActivity');
      $projectActivities = $this->ProjectActivity->find('list', array('conditions' => array('ProjectActivity.soft_delete' => 0,'ProjectActivity.publish' => 1,'ProjectActivity.milestone_id' => $this->request->params['pass'][0])));
      $this->set('projectActivities',$projectActivities);
      $this->layout = 'ajax';

    }

    public function get_milestones(){        
      $this->loadModel('Milestone');
      $milestones = $this->Milestone->find('list', array('conditions' => array('Milestone.soft_delete' => 0,'Milestone.publish' => 1,
        'Milestone.project_id' => $this->request->params['pass'][0]
    )));
      $this->set('milestones',$milestones);
      $this->layout = 'ajax';

    }

}

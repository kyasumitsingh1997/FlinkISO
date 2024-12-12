<?php

App::uses('AppController', 'Controller');

/**
 * CustomerComplaints Controller
 *
 * @property CustomerComplaint $CustomerComplaint
 */
class CustomerComplaintsController extends AppController {

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
    public function index($current_status = null) {
        
        if($current_status == 0)$status_conditions = array('CustomerComplaint.current_status'=>0);
        elseif($current_status == 1)$status_conditions = array('CustomerComplaint.current_status'=>1);
        else $status_conditions = array();
        $conditions = $this->_check_request();
        $this->paginate = array('order' => array('CustomerComplaint.complaint_date' => 'DESC'), 'conditions' => array($conditions,$status_conditions));

        $this->CustomerComplaint->recursive = 0;
        $this->set('customerComplaints', $this->paginate());

        $this->_get_count();
    }

    /**
     * search method
     * Dynamic by - TGS
     * @return void
     */
    public function customer_complaint_status($status = 0) {


        if ($this->Session->read('User.is_mr') == 0) {
            $cons = array('CustomerComplaint.branchid' => $this->Session->read('User.branch_id'));
        }

        $this->CustomerComplaint->recursive = 0;
        $this->paginate = array('order' => array('CustomerComplaint.sr_no' => 'DESC'), 'conditions' => array('CustomerComplaint.current_status' => $status, 'CustomerComplaint.publish' => 1, 'CustomerComplaint.soft_delete' => 0, $cons));
        $this->set('customerComplaints', $this->paginate());
        $this->_get_count();
        $this->render('index');
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
                        $searchArray[] = array('CustomerComplaint.' . $search => $searchKey);
                    else
                        $searchArray[] = array('CustomerComplaint.' . $search . ' like ' => '%' . $searchKey . '%');

                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('CustomerComplaint.branchid' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }
        if ($this->request->query['product_id'] != -1) {
            $productConditions = array('CustomerComplaint.product_id' => $this->request->query['product_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $productConditions));
            else
                $conditions[] = array('or' => $productConditions);
        }
        if ($this->request->query['delivery_challan_id'] != -1) {
            $deliveryChallanConditions = array('CustomerComplaint.delivery_challan_id' => $this->request->query['delivery_challan_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $deliveryChallanConditions));
            else
                $conditions[] = array('or' => $deliveryChallanConditions);
        }
        if ($this->request->query['assigned_to'] != -1) {
            $assignedToConditions = array('CustomerComplaint.employee_id' => $this->request->query['assigned_to']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $assignedToConditions));
            else
                $conditions[] = array('or' => $assignedToConditions);
        }
        if ($this->request->query['authorised_by'] != -1) {
            $authorisedByConditions = array('CustomerComplaint.authorized_by' => $this->request->query['authorised_by']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $authorisedByConditions));
            else
                $conditions[] = array('or' => $authorisedByConditions);
        }
        if ($this->request->query['customer_id'] != -1) {
            $customerConditions = array('CustomerComplaint.customer_id' => $this->request->query['customer_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $customerConditions));
            else
                $conditions[] = array('or' => $customerConditions);
        }
        if ($this->request->query['current_status'] == 0) {
            $currentStatusConditions = array('CustomerComplaint.current_status' => 0);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $currentStatusConditions));
            else
                $conditions[] = array('or' => $currentStatusConditions);
        }
        if ($this->request->query['current_status'] == 1) {
            $currentStatusConditions = array('CustomerComplaint.current_status' => 1);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $currentStatusConditions));
            else
                $conditions[] = array('or' => $currentStatusConditions);
        }
        if ($this->request->query['complaint_source']) {
            // array('Product' => 'Product', 'Delivery' => 'Delivery', 'Service' => 'Service', 'Customer Care' => 'Customer Care')
            if($this->request->query['complaint_source'] == 'Product')$source = 0;
            if($this->request->query['complaint_source'] == 'Delivery')$source = 1;
            if($this->request->query['complaint_source'] == 'Service')$source = 2;
            if($this->request->query['complaint_source'] == 'Customer Care')$source = 3;
            $complaintSourceConditions = array('CustomerComplaint.complaint_source' => 1);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $complaintSourceConditions));
            else
                $conditions[] = array('or' => $complaintSourceConditions);
        }
        

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('CustomerComplaint.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'CustomerComplaint.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);


        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('CustomerComplaint.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('CustomerComplaint.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->CustomerComplaint->recursive = 0;
        $this->paginate = array('order' => array('CustomerComplaint.sr_no' => 'DESC'), 'conditions' => $conditions, 'CustomerComplaint.soft_delete' => 0);
        if($this->request->query['limit'] != '-1'){
            $this->paginate = array_merge($this->paginate,array('limit'=>$this->request->query['limit']));
        }else{
            $this->paginate = array_merge($this->paginate,array('limit'=>20));
        }
        $this->set('customerComplaints', $this->paginate());

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
        if (!$this->CustomerComplaint->exists($id)) {
            throw new NotFoundException(__('Invalid customer complaint'));
        }
        $options = array('conditions' => array('CustomerComplaint.' . $this->CustomerComplaint->primaryKey => $id));
        $this->set('customerComplaint', $this->CustomerComplaint->find('first', $options));
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
        $this->loadModel('User');
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        if ($this->request->is('post')) {
            if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['CustomerComplaint']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['CustomerComplaint']['created_by'] = $this->Session->read('User.id');
            $this->request->data['CustomerComplaint']['modified_by'] = $this->Session->read('User.id');
            $this->request->data['CustomerComplaint']['created'] = date('Y-m-d H:i:s');
            $this->request->data['CustomerComplaint']['modified'] = date('Y-m-d H:i:s');
            $this->CustomerComplaint->create();
            if ($this->CustomerComplaint->save($this->request->data)) {
                if($this->request->data['CustomerComplaint']['employee_id'])$this->send_notification_email($this->request->data['CustomerComplaint']['employee_id'],'New customer cmplaint is assigned to you.','New customer complaint is assigned to you. Login to FlinkISO for more details');

                if($this->Session->read('User.is_mr') == true){    
                    if (isset($this->request->data['CustomerComplaint']['add_to_capa']) && $this->request->data['CustomerComplaint']['add_to_capa'] == 0) {
                        $this->loadModel('CorrectivePreventiveAction');
                        
                        $this->CorrectivePreventiveAction->create();
                        $capa_number = $this->generate_cp_number('CorrectivePreventiveAction','CPA','number');
                        $capa['name'] = 'From Customer complaint';
                        $capa['number'] = $capa_number;
                        $capa['capa_type'] = 0;
                        $capa['capa_category_id'] = '5245a935-7f58-482c-83c5-41f1c6c3268c';
                        $capa['product_id'] = $this->request->data['CustomerComplaint']['product_id'];
                        $capa['capa_source_id'] = $this->request->data['CustomerComplaint']['capa_source_id'];
                        $capa['raised_by'] = json_encode(array('Soruce' => 'Customer Complaint', 'id' => $this->CustomerComplaint->id));
                        $capa['customer_complaint_id'] = $this->CustomerComplaint->id;
                        $capa['assigned_to'] = $this->request->data['CustomerComplaint']['employee_id'];
                        $capa['target_date'] = $this->request->data['CustomerComplaint']['target_date'];
                        $capa['initial_remarks'] = $this->request->data['CustomerComplaint']['details'];
                        $capa['priority'] = 1;
                        $capa['publish'] = 1;
                        $this->CorrectivePreventiveAction->save($capa);
                        if($capa['assigned_to'])$this->send_notification_email($capa['assigned_to'],'New CAPA is assigned to you.','New CAPA is assigned to you. Login to FlinkISO for more details');
                    }
                }
                $this->Session->setFlash(__('The customer complaint has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->CustomerComplaint->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The customer complaint could not be saved. Please, try again.'));
            }
        }
        $customers = $this->CustomerComplaint->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $products = $this->CustomerComplaint->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));

        $this->loadModel('PurchaseOrder');
        $inboundPurchaseOrders = $this->PurchaseOrder->find('list', array('recursive' => -1, 'conditions' => array('PurchaseOrder.publish' => 1, 'PurchaseOrder.soft_delete' => 0, 'PurchaseOrder.type' => 0)));
        foreach ($inboundPurchaseOrders as $poID => $val){
            $challans[] = $this->CustomerComplaint->DeliveryChallan->find('list', array('conditions' => array('DeliveryChallan.publish' => 1, 'DeliveryChallan.soft_delete' => 0, 'DeliveryChallan.purchase_order_id' => $poID)));
        }
        foreach ($challans as $challan) {
            foreach ($challan as $challanID => $value) {
                $deliveryChallans[$challanID] = $value;
            }
        }
        $employees = $this->User->find('list', array('fields' => array('User.employee_id', 'Employee.name'), 'conditions' => array('User.publish' => 1, 'User.soft_delete' => 0), 'recursive' => 0));

        $this->loadModel('CapaSource');
        $this->CapaSource->recursive = 0;
        $capaSources = $this->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));

        $this->set(compact('capaSources', 'customers', 'products', 'deliveryChallans', 'employees'));
        $customer_number = $this->generate_cp_number('CustomerComplaint','CP','complaint_number');
        $this->set('customer_number',$customer_number);
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->CustomerComplaint->exists($id)) {
            throw new NotFoundException(__('Invalid customer complaint'));
        }
        $this->loadModel('CorrectivePreventiveAction');
        $capa = $this->CorrectivePreventiveAction->find('first', array('conditions' => array('CorrectivePreventiveAction.customer_complaint_id' => $id), 'recursive' => -1));

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['CustomerComplaint']['system_table_id'] = $this->_get_system_table_id();
            if($this->request->data['CustomerComplaint']['current_status'] == 1 && $this->request->data['CustomerComplaint']['settled_date'] == '' || $this->request->data['CustomerComplaint']['action_taken_date'] == ''){
                $this->Session->setFlash(__('Please add action taken / settled date'));
                $this->redirect(array('action' => 'edit', $id));        
            }
            if ($this->CustomerComplaint->save($this->request->data,false)) {
                if($this->Session->read('User.is_mr') == true){
                    if (isset($this->request->data['CustomerComplaint']['add_to_capa']) && $this->request->data['CustomerComplaint']['add_to_capa'] == 0) {
                        $newData = array();
                        if (count($capa) > 0) {
                            $newData['id'] = $capa['CorrectivePreventiveAction']['id'];
                        } else {
                            $this->CorrectivePreventiveAction->create();
                        }
                        $this->CorrectivePreventiveAction->create();
                        $capa_number = $this->generate_cp_number('CorrectivePreventiveAction','CPA','number');
                        $newData['name'] = 'From Customer complaint';
                        $newData['number'] = $capa_number;
                        $newData['capa_type'] = 0;
                        $newData['capa_category_id'] = '5245a935-7f58-482c-83c5-41f1c6c3268c';
                        $newData['product_id'] = $this->request->data['CustomerComplaint']['product_id'];
                        $newData['capa_source_id'] = $this->request->data['CustomerComplaint']['capa_source_id'];
                        $newData['raised_by'] = json_encode(array('Soruce' => 'Customer Complaint', 'id' => $this->CustomerComplaint->id));
                        $newData['customer_complaint_id'] = $this->CustomerComplaint->id;
                        $newData['assigned_to'] = $this->request->data['CustomerComplaint']['employee_id'];
                        $newData['target_date'] = $this->request->data['CustomerComplaint']['target_date'];
                        $newData['initial_remarks'] = $this->request->data['CustomerComplaint']['details'];
                        $newData['priority'] = 1;
                        $newData['publish'] = 1;
                        $this->CorrectivePreventiveAction->save($newData);
                        if($newData['assigned_to'])$this->send_notification_email($newData['assigned_to'],'New CAPA is assigned to you.','New CAPA is assigned to you. Login to FlinkISO for more details');
                    } else {
    //                    if ($this->CorrectivePreventiveAction->deleteAll(array('CorrectivePreventiveAction.customer_complaint_id' => $id), false)) {
    //                        $this->loadModel('Approval');
    //                        $this->Approval->deleteAll(array('Approval.model_name' => 'CorrectivePreventiveAction', 'Approval.record' => $id), false);
    //                    }
    //                      $capa_array = array();
    //                    $capa_array = $this->CorrectivePreventiveAction->find('all',array('conditions'=>array('CorrectivePreventiveAction.customer_complaint_id' => $id)));
    //                    if ($this->CorrectivePreventiveAction->deleteAll(array('CorrectivePreventiveAction.customer_complaint_id' => $id), false)) {
    //                        $this->loadModel('Approval');
    //                        foreach($capa_array as $capa){
    //                        
    //                        $this->Approval->deleteAll(array('Approval.model_name' => 'CorrectivePreventiveAction', 'Approval.record' => $capa['CorrectivePreventiveAction']['id']), false);
    //                        }
    //                    }
                    }
                } 
                $this->Session->setFlash(__('The customer complaint has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The customer complaint could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('CustomerComplaint.' . $this->CustomerComplaint->primaryKey => $id));
            $this->request->data = $this->CustomerComplaint->find('first', $options);
        }

        $customers = $this->CustomerComplaint->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $products = $this->CustomerComplaint->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));

        $this->loadModel('PurchaseOrder');
        $inboundPurchaseOrders = $this->PurchaseOrder->find('list', array('recursive' => -1, 'conditions' => array('PurchaseOrder.publish' => 1, 'PurchaseOrder.soft_delete' => 0, 'PurchaseOrder.type' => 0)));
        foreach ($inboundPurchaseOrders as $poID => $val){
            $challans[] = $this->CustomerComplaint->DeliveryChallan->find('list', array('conditions' => array('DeliveryChallan.publish' => 1, 'DeliveryChallan.soft_delete' => 0, 'DeliveryChallan.purchase_order_id' => $poID)));
        }
        foreach ($challans as $challan) {
            foreach ($challan as $challanID => $value) {
                $deliveryChallans[$challanID] = $value;
            }
        }

        $this->loadModel('User');
        $employees = $this->User->find('list', array('fields' => array('User.employee_id', 'Employee.name'), 'conditions' => array('User.publish' => 1, 'User.soft_delete' => 0), 'recursive' => 0));

        $this->loadModel('CapaSource');
        $this->CapaSource->recursive = 0;
        $capaSources = $this->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));

        $this->set(compact('capaSources', 'customers', 'products', 'deliveryChallans', 'employees', 'capa'));
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->CustomerComplaint->exists($id)) {
            throw new NotFoundException(__('Invalid customer complaint'));
        }
        $this->loadModel('CorrectivePreventiveAction');
        $capa = $this->CorrectivePreventiveAction->find('first', array('conditions' => array('CorrectivePreventiveAction.customer_complaint_id' => $id), 'recursive' => 0));

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
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->CustomerComplaint->save($this->request->data,false)) {
                if($this->Session->read('User.is_mr') == true){
                    if (isset($this->request->data['CustomerComplaint']['add_to_capa']) && $this->request->data['CustomerComplaint']['add_to_capa'] == 0) {
                        $newData = array();
                        if (count($capa) > 0) {
                            $newData['id'] = $capa['CorrectivePreventiveAction']['id'];
                        } else {
                            $this->CorrectivePreventiveAction->create();
                        }
                        $newData['capa_category_id'] = '5245a935-7f58-482c-83c5-41f1c6c3268c';
                        $newData['product_id'] = $this->request->data['CustomerComplaint']['product_id'];
                        $newData['capa_source_id'] = $this->request->data['CustomerComplaint']['capa_source_id'];
                        $newData['raised_by'] = json_encode(array('Soruce' => 'Customer Complaint', 'id' => $this->CustomerComplaint->id));
                        $newData['customer_complaint_id'] = $this->CustomerComplaint->id;
                        $newData['assigned_to'] = $this->request->data['CustomerComplaint']['employee_id'];
                        $newData['target_date'] = $this->request->data['CustomerComplaint']['target_date'];
                        $newData['initial_remarks'] = $this->request->data['CustomerComplaint']['details'];
                        $newData['priority'] = 1;
                        $newData['publish'] = 1;

                        $this->CorrectivePreventiveAction->save($newData);
                    } else {
                        $capa_array = array();
                        $capa_array = $this->CorrectivePreventiveAction->find('all',array('conditions'=>array('CorrectivePreventiveAction.customer_complaint_id' => $id)));
                        if ($this->CorrectivePreventiveAction->deleteAll(array('CorrectivePreventiveAction.customer_complaint_id' => $id), false)) {
                            $this->loadModel('Approval');
                            foreach($capa_array as $capa){
                            
                            $this->Approval->deleteAll(array('Approval.model_name' => 'CorrectivePreventiveAction', 'Approval.record' => $capa['CorrectivePreventiveAction']['id']), false);
                            }
                        }
                    }
                }
                $this->Session->setFlash(__('The customer complaint has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The customer complaint could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('CustomerComplaint.' . $this->CustomerComplaint->primaryKey => $id));
            $this->request->data = $this->CustomerComplaint->find('first', $options);
        }
        $customers = $this->CustomerComplaint->Customer->find('list', array('conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        $products = $this->CustomerComplaint->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));

        $this->loadModel('PurchaseOrder');
        $inboundPurchaseOrders = $this->PurchaseOrder->find('list', array('recursive' => -1, 'conditions' => array('PurchaseOrder.publish' => 1, 'PurchaseOrder.soft_delete' => 0, 'PurchaseOrder.type' => 0)));
        foreach ($inboundPurchaseOrders as $poID => $val){
            $challans[] = $this->CustomerComplaint->DeliveryChallan->find('list', array('conditions' => array('DeliveryChallan.publish' => 1, 'DeliveryChallan.soft_delete' => 0, 'DeliveryChallan.purchase_order_id' => $poID)));
        }
        foreach ($challans as $challan) {
            foreach ($challan as $challanID => $value) {
                $deliveryChallans[$challanID] = $value;
            }
        }

        $this->loadModel('User');
        $employees = $this->User->find('list', array('fields' => array('User.employee_id', 'Employee.name'), 'conditions' => array('User.publish' => 1, 'User.soft_delete' => 0, ), 'recursive' => 0));

        $this->loadModel('CapaSource');
        $this->CapaSource->recursive = 0;
        $capaSources = $this->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        $this->set(compact('capaSources', 'customers', 'products', 'deliveryChallans', 'employees', 'systemTables', 'masterListOfFormats', 'capa'));
    }

    public function get_customer_complaints() {
        if($this->Session->read('User.is_mr') != 1){
            $mr_cc_con = array('CustomerComplaint.employee_id' => $this->Session->read('User.employee_id'));
        }else{
            $mr_cc_con = array();
        }

		$newComplaints = NULL;
        $this->paginate = array('limit' => 2,
            'conditions' => array(
                $mr_cc_con,
                'CustomerComplaint.soft_delete' => 0,  
                'CustomerComplaint.current_status' => 0),
            'recursive' => 0,
            'fields' => array(
                'CustomerComplaint.complaint_source', 
                'CustomerComplaint.complaint_number', 
                'CustomerComplaint.complaint_date', 
                'CustomerComplaint.details', 
                'CustomerComplaint.target_date', 
                'Product.id', 
                'Product.name', 
                'DeliveryChallan.id', 
                'DeliveryChallan.challan_number', 
                'Customer.id', 'Customer.name',
                'CustomerComplaint.id',
                'CustomerComplaint.employee_id',
                'Employee.id','Employee.name'
                ));
        $customerComplaints = $this->paginate();
		if($customerComplaints){
			$this->loadModel('MeetingTopic');
			$i = 0;
			foreach ($customerComplaints as $customerComplaint):
				$meeting = $this->MeetingTopic->find('count', array('conditions' => array('MeetingTopic.publish' => 0, 'MeetingTopic.soft_delete' => 0, 'MeetingTopic.customer_complaint_id' => $customerComplaint['CustomerComplaint']['id'])));
				$newComplaints[$i] = $customerComplaint;
				if ($meeting > 0) {
					$newComplaints[$i]['added_in_meeting'] = 1;
				} else {
					$newComplaints[$i]['added_in_meeting'] = 0;
				}
				$i ++;
			endforeach;
			$i = 0;
		}

        $customerComplaints = $newComplaints;
        $totalComplaints = count($customerComplaints);
        $this->set(compact('customerComplaints', 'totalComplaints'));
        $this->render('/Elements/customer_complaints');
    }

    public function check_complaint_number($checkVal = null, $id = null){
        if($id){
            $conditions = array('CustomerComplaint.id !=' => $id, 'CustomerComplaint.complaint_number' => $checkVal);
        } else{
            $conditions = array('CustomerComplaint.complaint_number' => $checkVal);
}

        $result = $this->CustomerComplaint->find('all', array('recursive' => -1, 'fields' => 'complaint_number', 'conditions'=> $conditions));

        echo count($result) > 0 ? 'Complaint Number already exists! Enter a unique number.' : '';
        exit;
    }

    public function send_notification_email($employee = null, $subject = null, $body = null){
        $employee = $this->CustomerComplaint->Employee->find('first',array('recursive'=>-1,'fields'=>array(),'conditions'=>array('Employee.id'=>$employee)));
        $officeEmailId = $employee['Employee']['office_email'];
        $personalEmailId = $employee['Employee']['personal_email'];
        
        if ($officeEmailId != '') {
            $email = $officeEmailId;
        } else if ($personalEmailId != '') {
            $email = $personalEmailId;
        }
        if ($email) {
           try{
                App::uses('CakeEmail', 'Network/Email');
                if($this->Session->read('User.is_smtp') == 1)
                    $EmailConfig = new CakeEmail("smtp");
                if($this->Session->read('User.is_smtp') == 0)
                    $EmailConfig = new CakeEmail("default");
                
                $EmailConfig->to($email);
                $EmailConfig->subject('FlinkISO:' . $subject);
                $EmailConfig->emailFormat('text');
                $EmailConfig->send($body);
            } catch(Exception $e) {
                 $this->Session->setFlash(__('Can not notify user using email. Please check SMTP details and email address is correct.'));
            }

        }        
    }

    public function pending_tasks() {

        $conditions = $this->_check_request();
        $this->CustomerComplaint->recursive = 0;
        $conditions = array($conditions,'CustomerComplaint.target_date <' => date('Y-m-d'),'CustomerComplaint.current_status'=>0);
        $this->paginate = array('order'=>array('CustomerComplaint.sr_no'=>'DESC'),'conditions'=>array($conditions,$capa_condition));
        $this->set('customerComplaints', $this->paginate());

        $this->_get_count();        
    }

    public function send_reminder($id = null){
        $cc = $this->CustomerComplaint->find('first',array('recursive'=>-1, 'conditions'=>array('CustomerComplaint.id'=>$this->request->params['pass'][0])));
        $employee = $this->CustomerComplaint->Employee->find('first',array(
            'recursive'=>-1,
            'fields'=>array('Employee.id','Employee.name','Employee.personal_email','Employee.office_email'),
            'conditions'=>array('Employee.id'=>$cc['CustomerComplaint']['employee_id'])));

        $officeEmailId = $employee['Employee']['office_email'];
        $personalEmailId = $employee['Employee']['personal_email'];
        if ($officeEmailId != '') {
            $email = $officeEmailId;
        } else if ($personalEmailId != '') {
            $email = $personalEmailId;
        }
        if($cc && $email){
            $send_message = "Pending Customer Complaint For Action";
            $body = "<p>You have pending customer complant to address. Please login to FlinkISO and add details</p>";
            try{
                if(Configure::read('evnt') == 'Dev')$env = 'DEV';
                elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
                else $env = "";

                App::uses('CakeEmail', 'Network/Email');

                if($this->Session->read('User.is_smtp') == 1)
                    $EmailConfig = new CakeEmail("smtp");

                if($this->Session->read('User.is_smtp') == 0)
                    $EmailConfig = new CakeEmail("default");
                    $EmailConfig->to($email);
                    $EmailConfig->subject($send_message);
                    $EmailConfig->template('emailTrigger');
                    $EmailConfig->viewVars(array(
                        'date_time' => date('Y-m-d h:i:s'),
                        'by_user'=>$this->Session->read('User.username'),
                        'employee'=>$this->Session->read('User.name'),
                        'branch' => $this->Session->read('User.branch'),
                        'department' => $this->Session->read('User.department'),
                        'h2tag'=>$send_message,
                        'msg_content'=>$body,
                        'env' => $env, 'app_url' => FULL_BASE_URL
                        ));
                    $EmailConfig->emailFormat('html');
                    $EmailConfig->send();
                } catch(Exception $e) {
                    echo "<span class='btn btn-xs btn-danger'>Failed!</span>";        
                }
            echo "<span class='btn btn-xs btn-success'>Sent</span>";
        }else{
            echo "<span class='btn btn-xs btn-danger'>Failed!</span>";
        }
        
        exit;
    }
}

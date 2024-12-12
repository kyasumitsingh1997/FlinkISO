<?php

App::uses('AppController', 'Controller');

/**
 * SupplierEvaluationReevaluations Controller
 *
 * @property SupplierEvaluationReevaluation $SupplierEvaluationReevaluation
 */
class SupplierEvaluationReevaluationsController extends AppController {

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
        $this->paginate = array('order' => array('SupplierEvaluationReevaluation.sr_no' => 'DESC'), 'conditions' => array($conditions));
        $this->SupplierEvaluationReevaluation->recursive = 0;
        $this->set('supplierEvaluationReevaluations', $this->paginate());
        $this->_get_count();
        $supplierRegistrations = $this->SupplierEvaluationReevaluation->SupplierRegistration->find('list');
        $this->set(compact('supplierRegistrations'));
    }

    public function evaluate($supplierRegistrationId = null) {
        
        $supplierRegistrationId = $this->request->data['SupplierEvaluationReevaluation']['supplier_registration_id'];
        $dateRange = $this->request->data['SupplierEvaluationReevaluation']['date_range'];
        $dateRange = split('-', $dateRange);
        
        $startDate = date('Y-m-d',strtotime($dateRange[0]));
        $endDate = date('Y-m-d',strtotime($dateRange[1]));
        

        // Get supplier evaluation history
        if ($supplierRegistrationId && $supplierRegistrationId != -1) {
            $this->loadModel('SummeryOfSupplierEvaluation');
            $evaluationHistories = $this->SummeryOfSupplierEvaluation->find('all',array(
                'order'=>array('SummeryOfSupplierEvaluation.evaluation_date'=>'DESC'),
                'recursive'=>0,
                'conditions',array('SummeryOfSupplierEvaluation.supplier_registration_id'=>$supplierRegistrationId)));
            
            $this->set(compact('evaluationHistories'));
            // get evaluation data
            $evaluations = $this->SupplierEvaluationReevaluation->find('all',array(
                    'conditions'=>array(
                            'SupplierEvaluationReevaluation.supplier_registration_id'=>$supplierRegistrationId,
                            'PurchaseOrder.order_date BETWEEN ? AND ?'=>array($startDate,$endDate), 

                        ),
                    'recursive'=>0
                ));

        }

        debug($evaluations);
        $this->layout = 'ajax';
        $this->set(compact('evaluations'));
        
        
        $supplierRegistration = $this->SupplierEvaluationReevaluation->SupplierRegistration->find('first',array('conditions'=>array('SupplierRegistration.id'=>$supplierRegistrationId)));
        $this->set(compact('supplierRegistration', 'supplierRegistration'));

        $supplierRegistrations = $this->SupplierEvaluationReevaluation->SupplierRegistration->find('list');
        $this->set(compact('supplierRegistrations', 'supplier_registration_id'));

        $this->loadModel('SupplierCategory');
        $supplierCategories = $this->SupplierCategory->find('list', array('conditions' => array('SupplierCategory.publish' => 1, 'SupplierCategory.soft_delete' => 0)));
        $this->set(compact('supplierCategories'));

        $this->loadModel('SupplierEvaluationTemplate');
        $supplierEvaluationTemplates = $this->SupplierEvaluationTemplate->find('list', array('conditions' => array('SupplierEvaluationTemplate.publish' => 1, 'SupplierEvaluationTemplate.soft_delete' => 0)));
        $this->set(compact('supplierEvaluationTemplates'));
    }

    public function get_supplier_list($id = null) {

        $deliveryChallans = $this->SupplierEvaluationReevaluation->DeliveryChallan->find('list', array(
            'conditions' => array('DeliveryChallan.publish' => 1,
                'DeliveryChallan.soft_delete' => 0,
                'DeliveryChallan.supplier_registration_id' => $id
        )));
        return $deliveryChallans;
        exit;
    }

    public function get_template($supplier_evaluation_template_id = null){
        $this->autoRender = false;

        $this->loadModel('SupplierEvaluationTemplate');
        $template = $this->SupplierEvaluationTemplate->find('first',array('conditions'=>array('SupplierEvaluationTemplate.id'=>$supplier_evaluation_template_id)));
        if($template){
            $html = $template['SupplierEvaluationTemplate']['details'];
            // $html = "<p>Template not found!</p>";
        }else{
            $html = "<p>Template not found!</p>";
        }
        return $html;
        exit;
    }

}

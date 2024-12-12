<?php

App::uses('AppController', 'Controller');

/**
 * CorrectivePreventiveActions Controller
 *
 * @property CorrectivePreventiveAction $CorrectivePreventiveAction
 */
class CorrectivePreventiveActionsController extends AppController {

    public function _get_system_table_id() {

        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $systemTableId['SystemTable']['id'];
    }

    public function _get_count($type = null) {
        $onlyBranch = null;
        $onlyOwn = null;
        $conditions = null;
        $modelName = $this->modelClass;
        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array($modelName . '.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array($modelName . '.created_by' => $this->Session->read('User.id'));
        $conditions = array($onlyBranch, $onlyOwn);
        if (isset($type)) {
            $conditions = array('CorrectivePreventiveAction.capa_type' => $type);
        } else if (isset($this->request->params['named']['capa_type'])) {
            $conditions = array('CorrectivePreventiveAction.capa_type' => $this->request->params['named']['capa_type']);
        }
        $count = $this->$modelName->find('count', array('conditions' => $conditions));
        $published = $this->$modelName->find('count', array('conditions' => array($conditions, $modelName . '.publish' => 1, $modelName . '.soft_delete' => 0)));
        $unpublished = $this->$modelName->find('count', array('conditions' => array($conditions, $modelName . '.publish' => 0, $modelName . '.soft_delete' => 0)));
        $deleted = $this->$modelName->find('count', array('conditions' => array($conditions, $modelName . '.soft_delete' => 1)));
        $this->set(compact('count', 'published', 'unpublished', 'deleted','type'));
        $this->CorrectivePreventiveAction->recursive = 0;
        $this->set('correctivePreventiveActions', $this->paginate());
    }

    public function _check_request() {
        $onlyBranch = null;
        $onlyOwn = null;
        $con1 = null;
        $con2 = null;
        $modelName = $this->modelClass;
        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array($modelName . '.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array($modelName . '.created_by' => $this->Session->read('User.id'));
        if ($this->request->params['named']) {
            if (isset($this->request->params['named']['capa_type'])) {
                if ($this->request->params['named']['published'] == null)
                    $con1 = null;
                else
                    $con1 = array($modelName . '.publish' => $this->request->params['named']['published'], $modelName . '.capa_type' => $this->request->params['named']['capa_type']);
                if ($this->request->params['named']['soft_delete'] == null)
                    $con2 = null;
                else
                    $con2 = array($modelName . '.soft_delete' => $this->request->params['named']['soft_delete'], $modelName . '.capa_type' => $this->request->params['named']['capa_type']);
                if ($this->request->params['named']['soft_delete'] == null)
                    $conditions = array($onlyBranch, $onlyOwn, $con1, $modelName . '.soft_delete' => 0, $modelName . '.capa_type' => $this->request->params['named']['capa_type']);
                else
                    $conditions = array($onlyBranch, $onlyOwn, $con1, $con2);
            }
        }
        else {
            $conditions = array($onlyBranch, $onlyOwn, null, $modelName . '.soft_delete' => 0);
        }

        return $conditions;
    }

    public function capa_assigned() {
        
        $assigned = null;
        $employee = $this->Session->read('User.employee_id');
       
        $options=array(             
        'joins' =>
                  array(
                    array(
                        'table' => 'capa_investigations',
                        'alias' => 'CapaInvestigation',
                        'type' => 'left',
                        'foreignKey' => false,
                        'conditions'=> array('CapaInvestigation.corrective_preventive_action_id= CorrectivePreventiveAction.id', 'CapaInvestigation.current_status' => 0, 'CapaInvestigation.soft_delete' => 0, 'CapaInvestigation.employee_id' => $employee)
                    ),
                    array(
                        'table' => 'capa_revised_dates',
                        'alias' => 'CapaRevisedDate',
                        'type' => 'left',
                        'foreignKey' => false,
                        'conditions'=> array('CapaRevisedDate.corrective_preventive_action_id= CorrectivePreventiveAction.id', 'CapaRevisedDate.soft_delete' => 0,  'CapaRevisedDate.employee_id' => $employee)
                    ), 
                     array(
                        'table' => 'capa_root_cause_analysis',
                        'alias' => 'CapaRootCauseAnalysi',
                        'type' => 'left',
                        'foreignKey' => false,
                        'conditions'=> array('CapaRootCauseAnalysi.corrective_preventive_action_id = CorrectivePreventiveAction.id','CapaRootCauseAnalysi.current_status' => 0, 'CapaRootCauseAnalysi.soft_delete' => 0,  'CapaRootCauseAnalysi.employee_id' => $employee,
                 'CapaRootCauseAnalysi.action_assigned_to' => $employee,
                    'CapaRootCauseAnalysi.determined_by' => $employee)
                    ),
                       
     ), 'fields' => array('CorrectivePreventiveAction.*', 'CapaInvestigation.*', 'CapaRevisedDate.*', 'CapaRootCauseAnalysi.*'),'conditions'=>array('CorrectivePreventiveAction.current_status' => 0, 'CorrectivePreventiveAction.soft_delete' => 0, 'CorrectivePreventiveAction.publish' => 1)
);
         
$coupons = $this->CorrectivePreventiveAction->find('all', $options);
 

        $this->paginate = array('limit' => 2,
            'order' => array('CorrectivePreventiveAction.target_date' => 'ASC'),
            'fields' => array(
                'CapaSource.name', 'CapaCategory.name', 'CorrectivePreventiveAction.initial_remarks',
                'CorrectivePreventiveAction.id',
                'CorrectivePreventiveAction.target_date',
                'CorrectivePreventiveAction.number',
                'SuggestionForm.suggestion',
                'CustomerComplaint.details',
                'SupplierRegistration.title',
                'Product.name',
                'Device.name',
                'Material.name',
                'InternalAudit.clauses'
            ),
            'conditions' => array('OR' => array(
                  //  'CorrectivePreventiveAction.assigned_to' => $employee,
                    //'CorrectivePreventiveAction.action_assigned_to' => $employee,
                ), 'CorrectivePreventiveAction.current_status' => 0,
                'CorrectivePreventiveAction.soft_delete' => 0),
            'recursive' => 0);

        $assignedCapas = $this->paginate();
	if($assignedCapas){
        $this->loadModel('MeetingTopic');
		$i = 0;
        foreach ($assignedCapas as $assignedCapa):
            $meeting = $this->MeetingTopic->find('count', array('conditions' => array('MeetingTopic.publish' => 1, 'MeetingTopic.soft_delete' => 0, 'MeetingTopic.corrective_preventive_action_id' => $assignedCapa['CorrectivePreventiveAction']['id'])));
            $assigned[$i] = $assignedCapa;
            if ($meeting > 0) {
                $assigned[$i]['added_in_meeting'] = 1;
            } else {
                $assigned[$i]['added_in_meeting'] = 0;
            }
            $i ++;
        endforeach;
        $i = 0;
	}
        $openCapa = $this->CorrectivePreventiveAction->find('count', array(
            'order' => array('CorrectivePreventiveAction.target_date' => 'DESC'),
            'conditions' => array('OR' => array(
                  //  'CorrectivePreventiveAction.assigned_to' => $employee,
                   // 'CorrectivePreventiveAction.action_assigned_to' => $employee,
                ), 'CorrectivePreventiveAction.current_status' => 0, 'CorrectivePreventiveAction.soft_delete' => 0, 'CorrectivePreventiveAction.publish' => 1)));

        $closeCapa = $this->CorrectivePreventiveAction->find('count', array(
            'order' => array('CorrectivePreventiveAction.target_date' => 'DESC'),
            'conditions' => array('OR' => array(
               //     'CorrectivePreventiveAction.assigned_to' => $employee,
                 //   'CorrectivePreventiveAction.action_assigned_to' => $employee,
                ), 'CorrectivePreventiveAction.current_status' => 1, 'CorrectivePreventiveAction.soft_delete' => 0, 'CorrectivePreventiveAction.publish' => 1)));

        $from = date('Y-m-d');
        $to = date("Y-m-d", strtotime("+3 days", strtotime($from)));
        $forAlert = $this->CorrectivePreventiveAction->find('count', array(
            'conditions' => array('OR' => array(
                //    'CorrectivePreventiveAction.assigned_to' => $employee,
                 //   'CorrectivePreventiveAction.action_assigned_to' => $employee,
                ),
                'CorrectivePreventiveAction.target_date between ? and ? ' => array($from, $to),
                'CorrectivePreventiveAction.current_status' => 0), 'CorrectivePreventiveAction.soft_delete' => 0));
        if ($forAlert) {
            $this->set('show_nc_alert', true);
        }
        $assignedCount = count($assigned);


        $this->set(array('assigned' => $assigned, 'assignedCount' => $assignedCount, 'openCapa' => $openCapa, 'closeCapa' => $closeCapa));
    }

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $type = $this->request->params['pass'][0];        
        $conditions = $this->_check_request();
        if(isset($type)){
            switch ($type) {
                case '3':                
                    $conditions = array('DATE(CorrectivePreventiveAction.created)' => date('Y-m-d'), 'CorrectivePreventiveAction.soft_delete' => 0);
                    break;
                case '4':
                    $conditions = array('CorrectivePreventiveAction.current_status'=>1, 'CorrectivePreventiveAction.soft_delete' => 0);
                    break;
                case '5':
                    $conditions = array('CorrectivePreventiveAction.current_status'=>0, 'CorrectivePreventiveAction.soft_delete' => 0);
                        break;    
                default:
                    $conditions = array('CorrectivePreventiveAction.capa_type' => $type, 'CorrectivePreventiveAction.soft_delete' => 0);
                    break;
            }
        }
        $this->CorrectivePreventiveAction->hasMany['CapaInvestigation']['fields'] = array('CapaInvestigation.id','CapaInvestigation.current_status','CapaInvestigation.corrective_preventive_action_id','CapaInvestigation.employee_id');
        $this->CorrectivePreventiveAction->hasMany['CapaRootCauseAnalysi']['fields'] = array('CapaRootCauseAnalysi.id','CapaRootCauseAnalysi.current_status','CapaRootCauseAnalysi.corrective_preventive_action_id','CapaRootCauseAnalysi.action_assigned_to');
        $this->paginate = array('recursive'=>1, 'order' => array('CorrectivePreventiveAction.modified' => 'DESC'), 'conditions' => array($conditions));
        $this->set('type', $type);
        $this->_get_count($type);
        $this->set('assigned_to',$this->_get_employee_list());  
    }

    public function get_ncs($i = 0) {
        $condition1 = null;
        $condition2 = null;
        $condition = $this->_check_request();
        $this->loadModel('InternalAudit');
        $modelName = $this->modelClass;
        $internalAuditId = array();
        $internalAudits = $this->InternalAudit->find('all', array('conditions' => array('InternalAudit.non_conformity_found' => 1)));
        foreach ($internalAudits as $key => $internalAudit) {
            $internalAuditId[$key] = $internalAudit['InternalAudit']['id'];
        }

        if ($i == 0) {
            $condition1 = array('CorrectivePreventiveAction.internal_audit_id' => $internalAuditId);
        }
        if ($i == 1) {
            $condition2 = array('CorrectivePreventiveAction.internal_audit_id' => $internalAuditId, 'CorrectivePreventiveAction.current_status' => 0, 'CorrectivePreventiveAction.soft_delete' => 0, 'CorrectivePreventiveAction.publish' => 1);
        }
        $conditions = array($condition1, $condition2);
        $this->paginate = array('order' => array('CorrectivePreventiveAction.sr_no' => 'DESC'), 'conditions' => array($conditions));
        $correctivePreventiveActions = $this->paginate();
        $count = $this->$modelName->find('count', array('conditions' => $conditions));
        $published = $this->$modelName->find('count', array('conditions' => array($conditions, $modelName . '.publish' => 1, $modelName . '.soft_delete' => 0)));
        $unpublished = $this->$modelName->find('count', array('conditions' => array($conditions, $modelName . '.publish' => 0, $modelName . '.soft_delete' => 0)));
        $deleted = $this->$modelName->find('count', array('conditions' => array($conditions, $modelName . '.soft_delete' => 1)));
        $this->set(compact('correctivePreventiveActions', 'count', 'published', 'unpublished', 'deleted'));
    }

    public function get_capa_index() {
        $type = $this->request->params['pass'][0];
        $conditions = $this->_check_request();
        if(isset($type)){
            switch ($type) {
                case '3':                
                    $conditions = array('DATE(CorrectivePreventiveAction.created)' => date('Y-m-d'), 'CorrectivePreventiveAction.soft_delete' => 0);
                    break;
                case '4':
                    $conditions = array('CorrectivePreventiveAction.current_status'=>1, 'CorrectivePreventiveAction.soft_delete' => 0);
                    break;
                case '5':
                    $conditions = array('CorrectivePreventiveAction.current_status'=>0, 'CorrectivePreventiveAction.soft_delete' => 0);
                        break;    
                default:
                    $conditions = array('CorrectivePreventiveAction.capa_type' => $type, 'CorrectivePreventiveAction.soft_delete' => 0);
                    break;
            }
        }
        $this->CorrectivePreventiveAction->hasMany['CapaInvestigation']['fields'] = array('CapaInvestigation.id','CapaInvestigation.current_status','CapaInvestigation.corrective_preventive_action_id','CapaInvestigation.employee_id');
        $this->CorrectivePreventiveAction->hasMany['CapaRootCauseAnalysi']['fields'] = array('CapaRootCauseAnalysi.id','CapaRootCauseAnalysi.current_status','CapaRootCauseAnalysi.corrective_preventive_action_id','CapaRootCauseAnalysi.action_assigned_to');
        $this->paginate = array('recursive'=>1, 'order' => array('CorrectivePreventiveAction.modified' => 'DESC'), 'conditions' => array($conditions));
        $this->set('type', $type);
        $this->_get_count($type);
        $this->set('assigned_to',$this->_get_employee_list());        
    }

    /**
     * capa status
     * Dynamic by - TGS
     * @return void
     */
    public function capa_status($status = 0) {


        $this->CorrectivePreventiveAction->recursive = 0;
        $this->paginate = array('order' => array('CorrectivePreventiveAction.sr_no' => 'DESC'), 'conditions' => array('CorrectivePreventiveAction.current_status' => $status, 'CorrectivePreventiveAction.soft_delete' => 0, 'CorrectivePreventiveAction.publish' => 1));
        $count = $this->CorrectivePreventiveAction->find('count', array('conditions' => array('CorrectivePreventiveAction.current_status' => $status, 'CorrectivePreventiveAction.soft_delete' => 0, 'CorrectivePreventiveAction.publish' => 1)));
        $published = $this->CorrectivePreventiveAction->find('count', array('conditions' => array('CorrectivePreventiveAction.current_status' => $status, 'CorrectivePreventiveAction.publish' => 1)));
        $unpublished = $this->CorrectivePreventiveAction->find('count', array('conditions' => array('CorrectivePreventiveAction.current_status' => $status, 'CorrectivePreventiveAction.publish' => 0)));
        $deleted = $this->CorrectivePreventiveAction->find('count', array('conditions' => array('CorrectivePreventiveAction.current_status' => $status, 'CorrectivePreventiveAction.soft_delete' => 1)));
        $this->set(compact('count', 'published', 'unpublished', 'deleted'));
        $this->set('correctivePreventiveActions', $this->paginate());
    }

    /**
     * adcanced_search method
     * Advanced search by - TGS
     * @return void
     */
    public function advanced_search() {
        $conditions = array();
        $capaType = $this->request->query['capa_type'];
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
                        $searchArray[] = array('CorrectivePreventiveAction.' . $search => $searchKey);
                    else
                        $searchArray[] = array('CorrectivePreventiveAction.' . $search . ' like ' => '%' . $searchKey . '%');
                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('CorrectivePreventiveAction.branchid' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }
        if (($this->request->query['capa_type'] != '') && ($this->request->query['capa_type'] == 0) || ($this->request->query['capa_type'] == 1) || ($this->request->query['capa_type'] == 2)) {
            $capaTypeConditions[] = array('CorrectivePreventiveAction.capa_type' => $this->request->query['capa_type']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $capaTypeConditions));
            else
                $conditions[] = array('or' => $capaTypeConditions);
        }
        //employee type not in db
        if (($this->request->query['employee_type'] != -1) && ($this->request->query['employee_id'] != -1)) {
            
            $this->CorrectivePreventiveAction->hasMany['CapaRootCauseAnalysi']['conditions']['action_assigned_to'] = $this->request->query['employee_id'];
            $this->CorrectivePreventiveAction->hasMany['CapaInvestigation']['conditions']['employee_id'] = $this->request->query['employee_id'];

            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $employeeTypeConditions));
            else
                $conditions[] = array('or' => $employeeTypeConditions);
        }
        if ($this->request->query['capa_source_id'] != -1) {
            $capaSourceConditions[] = array('CorrectivePreventiveAction.capa_source_id' => $this->request->query['capa_source_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $capaSourceConditions);
            else
                $conditions[] = array('or' => $capaSourceConditions);
        }

        if ($this->request->query['capa_category_id'] != -1) {
            $capaCategoryConditions[] = array('CorrectivePreventiveAction.capa_category_id' => $this->request->query['capa_category_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $capaCategoryConditions);
            else
                $conditions[] = array('or' => $capaCategoryConditions);
        }

        if ($this->request->query['document_change_required'] > 0) {
            $documentChangeRequiredConditions[] = array('CorrectivePreventiveAction.document_changes_required' => $this->request->query['document_change_required']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $documentChangeRequiredConditions);
            else
                $conditions[] = array('or' => $documentChangeRequiredConditions);
        }
        if ($this->request->query['current_status'] != '') {
            $currentStatusConditions[] = array('CorrectivePreventiveAction.current_status' => $this->request->query['current_status']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $currentStatusConditions);
            else
                $conditions[] = array('or' => $currentStatusConditions);
        }
        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('CorrectivePreventiveAction.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'CorrectivePreventiveAction.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);
        
        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('CorrectivePreventiveAction.branchid' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('CorrectivePreventiveAction.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);
        
        $this->CorrectivePreventiveAction->recursive = 2;
        $this->paginate = array('order' => array('CorrectivePreventiveAction.sr_no' => 'DESC'),'conditions' => $conditions, 'CorrectivePreventiveAction.soft_delete' => 0);
        
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $correctivePreventiveActions = $this->paginate();
        $this->set(compact('correctivePreventiveActions', 'capaType'));
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->CorrectivePreventiveAction->exists($id)) {
            throw new NotFoundException(__('Invalid corrective preventive action'));
        }
        $options = array('conditions' => array('CorrectivePreventiveAction.' . $this->CorrectivePreventiveAction->primaryKey => $id));

        $currentCapa = $this->CorrectivePreventiveAction->find('first', $options);
        $this->loadModel('ChangeAdditionDeletionRequest');
        $changeRequiredIn = $this->ChangeAdditionDeletionRequest->find('first', array('conditions' => array('ChangeAdditionDeletionRequest.id' => $currentCapa['CorrectivePreventiveAction']['change_addition_deletion_request_id']), 'fields' => array('MasterListOfFormat.id', 'MasterListOfFormat.title', 'ChangeAdditionDeletionRequest.proposed_document_changes', 'ChangeAdditionDeletionRequest.proposed_work_instruction_changes', 'ChangeAdditionDeletionRequest.reason_for_change')));

        $this->set('correctivePreventiveAction', $currentCapa);
        $this->set('changeRequiredIn', $changeRequiredIn);
        $this->set(compact('customerComplaints'));
        return array('correctivePreventiveAction'=>$currentCapa,'changeRequiredIn'=>$changeRequiredIn);
    }

    /**
     * list method
     *
     * @return void
     */
    public function lists($type = null) {
        $this->_get_count($type);
        $this->set($type);
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
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            $this->request->data['CorrectivePreventiveAction']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['CorrectivePreventiveAction']['created_by'] = $this->Session->read('User.id');
            $this->request->data['CorrectivePreventiveAction']['modified_by'] = $this->Session->read('User.id');
            $this->request->data['CorrectivePreventiveAction']['created'] = date('Y-m-d H:i:s');
            $this->request->data['CorrectivePreventiveAction']['modified'] = date('Y-m-d H:i:s');

	      if(isset( $this->request->data['CorrectivePreventiveAction']['suggestion_form_id']) && $this->request->data['CorrectivePreventiveAction']['suggestion_form_id']!= -1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Employee', 'id' => $this->request->data['CorrectivePreventiveAction']['suggestion_form_id']));

          }else  if(isset( $this->request->data['CorrectivePreventiveAction']['customer_complaint_id']) && $this->request->data['CorrectivePreventiveAction']['customer_complaint_id']!= -1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Customer Complaint', 'id' => $this->request->data['CorrectivePreventiveAction']['customer_complaint_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['supplier_registration_id']) && $this->request->data['CorrectivePreventiveAction']['supplier_registration_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Supplier Registration', 'id' => $this->request->data['CorrectivePreventiveAction']['supplier_registration_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['device_id']) && $this->request->data['CorrectivePreventiveAction']['device_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Device', 'id' => $this->request->data['CorrectivePreventiveAction']['device_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['material_id']) && $this->request->data['CorrectivePreventiveAction']['material_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Material', 'id' => $this->request->data['CorrectivePreventiveAction']['material_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['internal_audit_id']) && $this->request->data['CorrectivePreventiveAction']['internal_audit_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Internal Audit ', 'id' => $this->request->data['CorrectivePreventiveAction']['internal_audit_id']));

          }  else if(isset( $this->request->data['CorrectivePreventiveAction']['product_id']) && $this->request->data['CorrectivePreventiveAction']['product_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Product', 'id' => $this->request->data['CorrectivePreventiveAction']['product_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['env_activity_id']) && $this->request->data['CorrectivePreventiveAction']['env_activity_id']!=-1 ){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Env Activity', 'id' => $this->request->data['CorrectivePreventiveAction']['env_activity_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['env_identification_id']) && $this->request->data['CorrectivePreventiveAction']['env_identification_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Env Identification', 'id' => $this->request->data['CorrectivePreventiveAction']['env_activity_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['process_id']) && $this->request->data['CorrectivePreventiveAction']['process_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Process', 'id' => $this->request->data['CorrectivePreventiveAction']['process_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['risk_assessment_id']) && $this->request->data['CorrectivePreventiveAction']['risk_assessment_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Risk', 'id' => $this->request->data['CorrectivePreventiveAction']['risk_assessment_id']));

          }else{
            $this->request->data['CorrectivePreventiveAction']['raised_by'] = 'Notices from other parties';
        }
            $this->CorrectivePreventiveAction->create();

            if ($this->CorrectivePreventiveAction->save($this->request->data, false)) {
                $this->loadModel('CapaInvestigation');
                $this->loadModel('CapaRootCauseAnalysi');

                // add investigations & root cause 
                $investigations = $this->data['CapaInvestigation']['employee_id'];
                if(count($investigations) > 0){
                    foreach ($investigations as $key => $value) {
                        $capaInvestigation = array();
                        $capaInvestigation['corrective_preventive_action_id'] = $this->CorrectivePreventiveAction->id;
                        $capaInvestigation['details'] = $this->data['CorrectivePreventiveAction']['initial_remarks'];
                        $capaInvestigation['proposed_action'] = $this->data['CorrectivePreventiveAction']['proposed_immidiate_action'];
                        $capaInvestigation['employee_id'] = $value;
                        // $capaInvestigation['target_date'] = date('Y-m-d',strtotime($this->data['CapaInvestigation']['target_date']));
                        $capaInvestigation['target_date'] = $this->data['CapaInvestigation']['target_date'];
                        $capaInvestigation['prepared_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaInvestigation['approved_by'] = $this->data['CorrectivePreventiveAction']['approved_by'];
                        $capaInvestigation['created_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaInvestigation['current_status'] = 0;
                        $capaInvestigation['publish'] = $this->data['CorrectivePreventiveAction']['publish'];
                        $this->CapaInvestigation->create();
                        $this->CapaInvestigation->save($capaInvestigation,false);
                        if($this->data['CorrectivePreventiveAction']['publish'] == 1){
                            // send email
                            $this->capa_investigation_send_reminder($this->CapaInvestigation->id,'no');

                        }
                    }
                }

                $roorCauses = $this->data['CapaRootCauseAnalysi']['employee_id'];
                if(count($roorCauses) > 0){
                    foreach ($roorCauses as $key => $value) {
                        $capaRootCauseAnalysis = array();
                        $capaRootCauseAnalysis['corrective_preventive_action_id'] = $this->CorrectivePreventiveAction->id;
                        $capaInvestigation['employee_id'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaRootCauseAnalysis['root_cause_details'] = $capaRootCauseAnalysis['root_cause_remarks'] = $this->data['CorrectivePreventiveAction']['initial_remarks'];
                        $capaRootCauseAnalysis['determined_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaRootCauseAnalysis['determined_on_date'] = date('Y-m-d');
                        $capaRootCauseAnalysis['proposed_action'] = $this->data['CorrectivePreventiveAction']['proposed_immidiate_action'];
                        $capaRootCauseAnalysis['action_assigned_to'] = $value;
                        // $capaRootCauseAnalysis['target_date'] = date('Y-m-d',strtotime($this->data['CapaRootCauseAnalysi']['target_date']));
                        $capaRootCauseAnalysis['target_date'] = $this->data['CapaRootCauseAnalysi']['target_date'];
                        $capaRootCauseAnalysis['prepared_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaRootCauseAnalysis['approved_by'] = $this->data['CorrectivePreventiveAction']['approved_by'];
                        $capaRootCauseAnalysis['created_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaRootCauseAnalysis['current_status'] = 0;
                        $capaRootCauseAnalysis['publish'] = $this->data['CorrectivePreventiveAction']['publish'];
                        
                        $this->CapaRootCauseAnalysi->create();
                        $this->CapaRootCauseAnalysi->save($capaRootCauseAnalysis,false);
                        if($this->data['CorrectivePreventiveAction']['publish'] == 1){
                            $this->root_cause_send_reminder($this->CapaRootCauseAnalysi->id,'no');
                        }
                    }
                }

                // if ($this->request->data['CorrectivePreventiveAction']['material_id'] != '-1') {
                    //add this to new table called "list of non-confirming products / materials
                    $this->loadModel('NonConformingProductsMaterial');
                    $this->loadModel('SystemTable');
                    $this->SystemTable->recursive = 1;

                    $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => 'non_conforming_products_materials'), 'fields' => array('SystemTable.id', 'MasterListOfFormat.id')
                    ));
                    $this->NonConformingProductsMaterial->create();
                    $newData = array();
                    
                    $newData['material_id'] = $this->request->data['CorrectivePreventiveAction']['material_id'];
                    $newData['product_id'] = $this->request->data['CorrectivePreventiveAction']['product_id'];
                    $newData['procedure_id'] = $this->request->data['CorrectivePreventiveAction']['procedure_id'];
                    $newData['process_id'] = $this->request->data['CorrectivePreventiveAction']['process_id'];
                    $newData['risk_assessment_id'] = $this->request->data['CorrectivePreventiveAction']['risk_assessment_id'];
                    $newData['internal_audit_id'] = $this->request->data['CorrectivePreventiveAction']['internal_audit_id'];
                    $newData['suggestion_form_id'] = $this->request->data['CorrectivePreventiveAction']['suggestion_form_id'];
                    $newData['customer_complaint_id'] = $this->request->data['CorrectivePreventiveAction']['customer_complaint_id'];
                    $newData['supplier_registration_id'] = $this->request->data['CorrectivePreventiveAction']['supplier_registration_id'];
                    $newData['device_id'] = $this->request->data['CorrectivePreventiveAction']['device_id'];
                    $newData['risk_assessment_id'] = $this->request->data['CorrectivePreventiveAction']['risk_assessment_id'];
                    $newData['env_identification_id'] = $this->request->data['CorrectivePreventiveAction']['env_identification_id'];
                    $newData['env_activity_id'] = $this->request->data['CorrectivePreventiveAction']['env_activity_id'];

                    if($this->request->data['CorrectivePreventiveAction']['product_id'] != -1)$newData['type '] = 0;
                    if($this->request->data['CorrectivePreventiveAction']['material_id'] != -1)$newData['type '] = 1;
                    if($this->request->data['CorrectivePreventiveAction']['procedure_id'] != -1)$newData['type '] = 2;
                    if($this->request->data['CorrectivePreventiveAction']['process_id'] != -1)$newData['type '] = 3;
                    if($this->request->data['CorrectivePreventiveAction']['risk_assessment_id'] != -1)$newData['type '] = 4;
                    if($this->request->data['CorrectivePreventiveAction']['internal_audit_id'] != -1)$newData['type '] = 5;
                    if($this->request->data['CorrectivePreventiveAction']['suggestion_form_id'] != -1)$newData['type '] = 6;
                    if($this->request->data['CorrectivePreventiveAction']['customer_complaint_id'] != -1)$newData['type '] = 7;
                    if($this->request->data['CorrectivePreventiveAction']['supplier_registration_id'] != -1)$newData['type '] = 8;
                    if($this->request->data['CorrectivePreventiveAction']['device_id'] != -1)$newData['type '] = 9;
                    if($this->request->data['CorrectivePreventiveAction']['risk_assessment_id'] != -1)$newData['type '] = 10;
                    if($this->request->data['CorrectivePreventiveAction']['env_identification_id'] != -1)$newData['type '] = 11;
                    if($this->request->data['CorrectivePreventiveAction']['env_activity_id'] != -1)$newData['type '] = 11;


                    $newData['title'] = $this->request->data['CorrectivePreventiveAction']['name'];
                    $newData['non_confirmity_date'] = date('Y-m-d');
                    $newData['details'] = $this->request->data['CorrectivePreventiveAction']['initial_remarks'];
                    $newData['capa_source_id'] = $this->request->data['CorrectivePreventiveAction']['capa_source_id'];
                    $newData['corrective_preventive_action_id'] = $this->CorrectivePreventiveAction->id;
                    $newData['system_table_id'] = $systemTableId['SystemTable']['id'];
                    $newData['master_list_of_format_id'] = $systemTableId['MasterListOfFormat']['id'];
                    $newData['publish'] = $this->request->data['CorrectivePreventiveAction']['publish'];
                    $newData['soft_delete'] = 0;
                    $this->NonConformingProductsMaterial->save($newData, false);
                // }

                // if ($this->request->data['CorrectivePreventiveAction']['product_id'] != '-1') {
                //     //add this to new table called "list of non-confirming products / materials
                //     $this->loadModel('NonConformingProductsMaterial');
                //     $this->loadModel('SystemTable');
                //     $this->SystemTable->recursive = 1;
                //     $systemTableId = $this->SystemTable->find('first', array(
                //         'conditions' => array('SystemTable.system_name' => 'non_conforming_products_materials'),
                //         'fields' => array('SystemTable.id', 'MasterListOfFormat.id')
                //     ));
                //     $this->NonConformingProductsMaterial->create();
                //     $newData = array();
                //     $newData['product_id'] = $this->request->data['CorrectivePreventiveAction']['product_id'];
                //     $newData['title'] = $this->request->data['CorrectivePreventiveAction']['name'];
                //     $newData['description'] = $this->request->data['CorrectivePreventiveAction']['initial_remarks'];
                //     $newData['capa_source_id'] = $this->request->data['CorrectivePreventiveAction']['capa_source_id'];
                //     $newData['corrective_preventive_action_id'] = $this->CorrectivePreventiveAction->id;
                //     $newData['system_table_id'] = $systemTableId['SystemTable']['id'];
                //     $newData['master_list_of_format_id'] = $systemTableId['MasterListOfFormat']['id'];
                //     $newData['publish'] = $this->request->data['CorrectivePreventiveAction']['publish'];
                //     $newData['soft_delete'] = 0;
                //     $this->NonConformingProductsMaterial->save($newData, false);
                // }

        		if($this->request->data['CorrectivePreventiveAction']['document_changes_required'] == 1){
        		    $this->loadModel('ChangeAdditionDeletionRequest');
        		    $this->ChangeAdditionDeletionRequest->create();
        		    $newData = array();
        		    $newData['title'] = $this->request->data['CorrectivePreventiveAction']['name'];
                    $newData['request_from'] = 'Other';
        		    $newData['others'] = 'CAPA Number: ' . $this->request->data['CorrectivePreventiveAction']['number'];
        		    $newData['master_list_of_format'] = $this->request->data['CorrectivePreventiveAction']['master_list_of_format'];
        //		    $newData['current_document_details'] = $this->request->data['CorrectivePreventiveAction']['current_document_details'];
        //		    $newData['request_details'] = $this->request->data['CorrectivePreventiveAction']['request_details'];
        //		    $newData['reason_for_change'] = $this->request->data['CorrectivePreventiveAction']['reason_for_change'];
        //                    $newData['proposed_document_changes'] = $this->request->data['ChangeAdditionDeletionRequest']['proposed_document_changes'];
        //		    $newData['proposed_work_instruction_changes'] = $this->request->data['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes'];
        		    $newData['document_change_accepted'] = 2;
        		    $newData['prepared_by'] = $this->request->data['CorrectivePreventiveAction']['prepared_by'];
        		    $newData['publish'] = 1;
        		    $this->ChangeAdditionDeletionRequest->save($newData, false);

        		    $updateCapa['id'] = $this->CorrectivePreventiveAction->id;
        		    $updateCapa['change_addition_deletion_request_id'] = $this->ChangeAdditionDeletionRequest->id;
        		    $this->CorrectivePreventiveAction->save($updateCapa);
        		}

                $this->Session->setFlash(__('The corrective preventive action has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->CorrectivePreventiveAction->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The corrective preventive action could not be saved. Please, try again.'));
            }
        }
        $capaSources = $this->CorrectivePreventiveAction->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        $capaCategories = $this->CorrectivePreventiveAction->CapaCategory->find('list', array('conditions' => array('CapaCategory.publish' => 1, 'CapaCategory.soft_delete' => 0)));
        $capaRatings = $this->CorrectivePreventiveAction->CapaRating->find('list', array('conditions' => array('CapaRating.publish' => 1, 'CapaRating.soft_delete' => 0)));
        $internalAudits = $this->CorrectivePreventiveAction->InternalAudit->find('list', array('conditions' => array('InternalAudit.publish' => 1, 'InternalAudit.soft_delete' => 0)));
        $suggestionForms = $this->CorrectivePreventiveAction->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        $customerComplaints = $this->CorrectivePreventiveAction->CustomerComplaint->find('list', array('conditions' => array('CustomerComplaint.publish' => 1, 'CustomerComplaint.soft_delete' => 0)));
        $supplierRegistrations = $this->CorrectivePreventiveAction->SupplierRegistration->find('list', array('conditions' => array('SupplierRegistration.publish' => 1, 'SupplierRegistration.soft_delete' => 0)));
        $products = $this->CorrectivePreventiveAction->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $procedures = $this->CorrectivePreventiveAction->Procedure->find('list', array('conditions' => array('Procedure.publish' => 1, 'Procedure.soft_delete' => 0)));
        $processes = $this->CorrectivePreventiveAction->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        $riskAssessments = $this->CorrectivePreventiveAction->RiskAssessment->find('list', array('conditions' => array('RiskAssessment.publish' => 1, 'RiskAssessment.soft_delete' => 0)));
        $tasks = $this->CorrectivePreventiveAction->Task->find('list', array('conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0)));
        $devices = $this->CorrectivePreventiveAction->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0)));
        $materials = $this->CorrectivePreventiveAction->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $envActivities = $this->CorrectivePreventiveAction->EnvActivity->find('list',array('conditions'=>array('EnvActivity.publish'=>1,'EnvActivity.soft_delete'=>0)));
        $envIdentifications = $this->CorrectivePreventiveAction->EnvIdentification->find('list',array('conditions'=>array('EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));
        $projects = $this->CorrectivePreventiveAction->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
        $projectActivities = $this->CorrectivePreventiveAction->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
        $masterListOfFormats = $this->CorrectivePreventiveAction->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0), 'recursive' => -1));
        $this->set(compact('capaSources', 'capaCategories', 'capaRatings', 'internalAudits', 'suggestionForms', 'customerComplaints', 'supplierRegistrations', 'products', 'devices', 'materials','masterListOfFormats','procedures','processes','riskAssessments', 'tasks','envActivities','envIdentifications','projects','projectActivities'));
        $cap_number = $this->generate_cp_number('CorrectivePreventiveAction','CAR','number');
        $this->set('cap_number',$cap_number);        
    }

    
    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->CorrectivePreventiveAction->exists($id)) {
            throw new NotFoundException(__('Invalid corrective preventive action'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->_check_password();
            $this->_check_ncs();
            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            
            
            $this->request->data['CorrectivePreventiveAction']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['CorrectivePreventiveAction']['modified_by'] = $this->Session->read('User.id');
            $this->request->data['CorrectivePreventiveAction']['modified'] = date('Y-m-d H:i:s');
            $this->request->data['CorrectivePreventiveAction']['created_by'] = $this->Session->read('User.id');



	      if(isset( $this->request->data['CorrectivePreventiveAction']['suggestion_form_id']) && $this->request->data['CorrectivePreventiveAction']['suggestion_form_id']!= -1){

		      $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Employee', 'id' => $this->request->data['CorrectivePreventiveAction']['suggestion_form_id']));

	      }else  if(isset( $this->request->data['CorrectivePreventiveAction']['customer_complaint_id']) && $this->request->data['CorrectivePreventiveAction']['customer_complaint_id']!= -1){

		      $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Customer Complaint', 'id' => $this->request->data['CorrectivePreventiveAction']['customer_complaint_id']));

	      }else if(isset( $this->request->data['CorrectivePreventiveAction']['supplier_registration_id']) && $this->request->data['CorrectivePreventiveAction']['supplier_registration_id']!=-1){

		      $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Supplier Registration', 'id' => $this->request->data['CorrectivePreventiveAction']['supplier_registration_id']));

	      }else if(isset( $this->request->data['CorrectivePreventiveAction']['device_id']) && $this->request->data['CorrectivePreventiveAction']['device_id']!=-1){

		      $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Device', 'id' => $this->request->data['CorrectivePreventiveAction']['device_id']));

	      }else if(isset( $this->request->data['CorrectivePreventiveAction']['material_id']) && $this->request->data['CorrectivePreventiveAction']['material_id']!=-1){

		      $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Material', 'id' => $this->request->data['CorrectivePreventiveAction']['material_id']));

	      }else if(isset( $this->request->data['CorrectivePreventiveAction']['internal_audit_id']) && $this->request->data['CorrectivePreventiveAction']['internal_audit_id']!=-1){

		      $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Internal Audit ', 'id' => $this->request->data['CorrectivePreventiveAction']['internal_audit_id']));

	      }  else if(isset( $this->request->data['CorrectivePreventiveAction']['product_id']) && $this->request->data['CorrectivePreventiveAction']['product_id']!=-1){

		      $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Product', 'id' => $this->request->data['CorrectivePreventiveAction']['product_id']));

	      }else if(isset( $this->request->data['CorrectivePreventiveAction']['env_activity_id']) && $this->request->data['CorrectivePreventiveAction']['env_activity_id']!=-1 ){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Env Activity', 'id' => $this->request->data['CorrectivePreventiveAction']['env_activity_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['env_identification_id']) && $this->request->data['CorrectivePreventiveAction']['env_identification_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Env Identification', 'id' => $this->request->data['CorrectivePreventiveAction']['env_activity_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['process_id']) && $this->request->data['CorrectivePreventiveAction']['process_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Process', 'id' => $this->request->data['CorrectivePreventiveAction']['process_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['risk_assessment_id']) && $this->request->data['CorrectivePreventiveAction']['risk_assessment_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Risk', 'id' => $this->request->data['CorrectivePreventiveAction']['risk_assessment_id']));

          }else{
            $this->request->data['CorrectivePreventiveAction']['raised_by'] = 'Notices from other parties';
        }

            if ($this->CorrectivePreventiveAction->save($this->request->data, false)) {
                
                $this->loadModel('NonConformingProductsMaterial');
                $NonConformingMaterials = $this->NonConformingProductsMaterial->find('first', array('conditions' => array('NonConformingProductsMaterial.corrective_preventive_action_id' => $id)));
                
                $this->SystemTable->recursive = 1;
                $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => 'non_conforming_products_materials'), 'fields' => array('SystemTable.id', 'MasterListOfFormat.id')));
                $newData = array();

                if($NonConformingMaterials['NonConformingProductsMaterial']['id'])$newData['id'] = $NonConformingMaterials['NonConformingProductsMaterial']['id'];
                $newData['material_id'] = $this->request->data['CorrectivePreventiveAction']['material_id'];
                $newData['product_id'] = $this->request->data['CorrectivePreventiveAction']['product_id'];
                $newData['procedure_id'] = $this->request->data['CorrectivePreventiveAction']['procedure_id'];
                $newData['process_id'] = $this->request->data['CorrectivePreventiveAction']['process_id'];
                $newData['risk_assessment_id'] = $this->request->data['CorrectivePreventiveAction']['risk_assessment_id'];
                $newData['internal_audit_id'] = $this->request->data['CorrectivePreventiveAction']['internal_audit_id'];
                $newData['suggestion_form_id'] = $this->request->data['CorrectivePreventiveAction']['suggestion_form_id'];
                $newData['customer_complaint_id'] = $this->request->data['CorrectivePreventiveAction']['customer_complaint_id'];
                $newData['supplier_registration_id'] = $this->request->data['CorrectivePreventiveAction']['supplier_registration_id'];
                $newData['device_id'] = $this->request->data['CorrectivePreventiveAction']['device_id'];
                $newData['risk_assessment_id'] = $this->request->data['CorrectivePreventiveAction']['risk_assessment_id'];
                $newData['env_identification_id'] = $this->request->data['CorrectivePreventiveAction']['env_identification_id'];
                $newData['env_activity_id'] = $this->request->data['CorrectivePreventiveAction']['env_activity_id'];

                $newData['title'] = $this->request->data['CorrectivePreventiveAction']['name'];
                $newData['non_confirmity_date'] = date('Y-m-d');
                $newData['details'] = $this->request->data['CorrectivePreventiveAction']['initial_remarks'];
                $newData['capa_source_id'] = $this->request->data['CorrectivePreventiveAction']['capa_source_id'];
                $newData['corrective_preventive_action_id'] = $this->CorrectivePreventiveAction->id;
                $newData['system_table_id'] = $systemTableId['SystemTable']['id'];
                $newData['master_list_of_format_id'] = $systemTableId['MasterListOfFormat']['id'];
                $newData['publish'] = $this->request->data['CorrectivePreventiveAction']['publish'];
                $newData['soft_delete'] = 0;

                if($this->request->data['CorrectivePreventiveAction']['product_id'] != -1 || $this->request->data['CorrectivePreventiveAction']['product_id'] != NULL)$newData['type '] = 0;
                if($this->request->data['CorrectivePreventiveAction']['material_id'] != -1 || $this->request->data['CorrectivePreventiveAction']['material_id'])$newData['type '] = 1;
                if($this->request->data['CorrectivePreventiveAction']['procedure_id'] != -1 || $this->request->data['CorrectivePreventiveAction']['procedure_id'])$newData['type '] = 2;
                if($this->request->data['CorrectivePreventiveAction']['process_id'] != -1 || $this->request->data['CorrectivePreventiveAction']['process_id'])$newData['type '] = 3;
                if($this->request->data['CorrectivePreventiveAction']['risk_assessment_id'] != -1  || $this->request->data['CorrectivePreventiveAction']['risk_assessment_id'])$newData['type '] = 4;
                if($this->request->data['CorrectivePreventiveAction']['internal_audit_id'] != -1  || $this->request->data['CorrectivePreventiveAction']['internal_audit_id'])$newData['type '] = 5;
                if($this->request->data['CorrectivePreventiveAction']['suggestion_form_id'] != -1  || $this->request->data['CorrectivePreventiveAction']['suggestion_form_id'])$newData['type '] = 6;
                if($this->request->data['CorrectivePreventiveAction']['customer_complaint_id'] != -1  || $this->request->data['CorrectivePreventiveAction']['customer_complaint_id'])$newData['type '] = 7;
                if($this->request->data['CorrectivePreventiveAction']['supplier_registration_id'] != -1  || $this->request->data['CorrectivePreventiveAction']['supplier_registration_id'])$newData['type '] = 8;
                if($this->request->data['CorrectivePreventiveAction']['device_id'] != -1  || $this->request->data['CorrectivePreventiveAction']['device_id'])$newData['type '] = 9;
                if($this->request->data['CorrectivePreventiveAction']['risk_assessment_id'] != -1  || $this->request->data['CorrectivePreventiveAction']['risk_assessment_id'])$newData['type '] = 10;
                if($this->request->data['CorrectivePreventiveAction']['env_identification_id'] != -1  || $this->request->data['CorrectivePreventiveAction']['env_identification_id'])$newData['type '] = 11;
                if($this->request->data['CorrectivePreventiveAction']['env_activity_id'] != -1  || $this->request->data['CorrectivePreventiveAction']['env_activity_id'])$newData['type '] = 11;

                $this->loadModel('NonConformingProductsMaterial');
                $this->loadModel('SystemTable');
                
                $this->NonConformingProductsMaterial->create();
                $this->NonConformingProductsMaterial->save($newData, false); 

                $this->loadModel('CapaInvestigation');
                $this->loadModel('CapaRootCauseAnalysi');

                // add investigations & root cause 
                
                $investigations = $this->data['CapaInvestigation'];
                if(count($investigations) > 0){
                    foreach ($this->data['CapaInvestigation'] as $key => $value) {
                        if($this->data['CorrectivePreventiveAction']['publish'] == 1){
                            // send email
                            $this->capa_investigation_send_reminder($value['id'],'no');

                        }
                    }
                }

                $roorCauses = $this->data['CapaRootCauseAnalysi'];
                if(count($roorCauses) > 0){
                    foreach ($this->data['CapaRootCauseAnalysi'] as $key => $value) {
                        if($this->data['CorrectivePreventiveAction']['publish'] == 1){
                            $this->root_cause_send_reminder($value['id'],'no');
                        }
                    }
                }

		if($this->request->data['CorrectivePreventiveAction']['current_status'] == 0 && $this->request->data['CorrectivePreventiveAction']['document_changes_required'] == 1){
		    if(!empty($this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id'])){
			    $this->loadModel('ChangeAdditionDeletionRequest');
			    $newData = array();
                $newData['title'] = $this->request->data['CorrectivePreventiveAction']['name'];
			    $newData['id'] = $this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id'];
			    $newData['others'] = 'CAPA Number: ' . $this->request->data['CorrectivePreventiveAction']['number'];
			    $newData['master_list_of_format'] = $this->request->data['CorrectivePreventiveAction']['master_list_of_format'];
			    $this->ChangeAdditionDeletionRequest->save($newData, false);

		    } else{
			    $this->loadModel('ChangeAdditionDeletionRequest');
			    $this->ChangeAdditionDeletionRequest->create();
			    $newData = array();
                            $newData['request_from'] = 'Other';
			    $newData['others'] = 'CAPA Number: ' . $this->request->data['CorrectivePreventiveAction']['number'];
			    $newData['master_list_of_format'] = $this->request->data['CorrectivePreventiveAction']['master_list_of_format'];
                            $newData['document_change_accepted'] = 2;
                            $newData['prepared_by'] = $this->request->data['CorrectivePreventiveAction']['prepared_by'];
                            $newData['publish'] = 1;
			    $this->ChangeAdditionDeletionRequest->save($newData, false);

			    $updateCapa['id'] = $this->CorrectivePreventiveAction->id;
			    $updateCapa['change_addition_deletion_request_id'] = $this->ChangeAdditionDeletionRequest->id;
			    $this->CorrectivePreventiveAction->save($updateCapa);
		    }
		} else {
		    if(!empty($this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id'])){
			    $this->loadModel('ChangeAdditionDeletionRequest');
			    $this->ChangeAdditionDeletionRequest->deleteAll(array('ChangeAdditionDeletionRequest.id' => $this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id']), false);

			    $updateCapa['id'] = $this->CorrectivePreventiveAction->id;
			    $updateCapa['change_addition_deletion_request_id'] = 'NULL';
			    $updateCapa['document_changes_required'] = 0;
			    $this->CorrectivePreventiveAction->save($updateCapa);
		    }
		}

                $this->Session->setFlash(__('The corrective preventive action has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The corrective preventive action could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('CorrectivePreventiveAction.' . $this->CorrectivePreventiveAction->primaryKey => $id));
            $this->request->data = $this->CorrectivePreventiveAction->find('first', $options);            
        }
        $capaSources = $this->CorrectivePreventiveAction->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        $capaCategories = $this->CorrectivePreventiveAction->CapaCategory->find('list', array('conditions' => array('CapaCategory.publish' => 1, 'CapaCategory.soft_delete' => 0)));
        $capaRatings = $this->CorrectivePreventiveAction->CapaRating->find('list', array('conditions' => array('CapaRating.publish' => 1, 'CapaRating.soft_delete' => 0)));
        $internalAudits = $this->CorrectivePreventiveAction->InternalAudit->find('list', array('conditions' => array('InternalAudit.publish' => 1, 'InternalAudit.soft_delete' => 0)));
        $suggestionForms = $this->CorrectivePreventiveAction->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        $customerComplaints = $this->CorrectivePreventiveAction->CustomerComplaint->find('list', array('conditions' => array('CustomerComplaint.publish' => 1, 'CustomerComplaint.soft_delete' => 0)));
        $supplierRegistrations = $this->CorrectivePreventiveAction->SupplierRegistration->find('list', array('conditions' => array('SupplierRegistration.publish' => 1, 'SupplierRegistration.soft_delete' => 0)));
        $products = $this->CorrectivePreventiveAction->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $procedures = $this->CorrectivePreventiveAction->Procedure->find('list', array('conditions' => array('Procedure.publish' => 1, 'Procedure.soft_delete' => 0)));
        $processes = $this->CorrectivePreventiveAction->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        $riskAssessments = $this->CorrectivePreventiveAction->RiskAssessment->find('list', array('conditions' => array('RiskAssessment.publish' => 1, 'RiskAssessment.soft_delete' => 0)));
        $tasks = $this->CorrectivePreventiveAction->Task->find('list', array('conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0)));
        $devices = $this->CorrectivePreventiveAction->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0)));
        $materials = $this->CorrectivePreventiveAction->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $masterListOfFormats = $this->CorrectivePreventiveAction->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0), 'recursive' => -1));
        $this->loadModel('ChangeAdditionDeletionRequest');
        $changeAdditionDeletionRequest = $this->ChangeAdditionDeletionRequest->find('first', array('conditions' => array('ChangeAdditionDeletionRequest.id' => $this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id']), 'fields' => array('master_list_of_format', 'current_document_details', 'proposed_document_changes', 'proposed_work_instruction_changes', 'current_document_details', 'reason_for_change', 'request_details'), 'recursive' => -1));
        $envActivities = $this->CorrectivePreventiveAction->EnvActivity->find('list',array('conditions'=>array('EnvActivity.publish'=>1,'EnvActivity.soft_delete'=>0)));
        $envIdentifications = $this->CorrectivePreventiveAction->EnvIdentification->find('list',array('conditions'=>array('EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));        
        $projects = $this->CorrectivePreventiveAction->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
        $projectActivities = $this->CorrectivePreventiveAction->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
        $masterListOfFormats = $this->CorrectivePreventiveAction->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0), 'recursive' => -1));
        $this->set(compact('capaSources', 'capaCategories', 'capaRatings', 'internalAudits', 'suggestionForms', 'customerComplaints', 'supplierRegistrations', 'products', 'devices', 'materials','masterListOfFormats','procedures','processes','tasks','envActivities','envIdentifications','projects','projectActivities','riskAssessments'));

        if($this->request->data['CorrectivePreventiveAction']['number'] == ''){
            $cap_number = $this->generate_cp_number('CorrectivePreventiveAction','CAR','number');
            $this->set('cap_number',$cap_number);
        }

        // load investigations / root cause
        $capaInvestigations = $this->CorrectivePreventiveAction->CapaInvestigation->find('all',array('conditions'=>array('CapaInvestigation.corrective_preventive_action_id'=>$this->request->data['CorrectivePreventiveAction']['id'])));
        $capaRootCauseAnalysis = $this->CorrectivePreventiveAction->CapaRootCauseAnalysi->find('all',array('conditions'=>array('CapaRootCauseAnalysi.corrective_preventive_action_id'=>$this->request->data['CorrectivePreventiveAction']['id'])));
        $this->set('capaInvestigations',$capaInvestigations);
        $this->set('capaRootCauseAnalysis',$capaRootCauseAnalysis);
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->CorrectivePreventiveAction->exists($id)) {
            throw new NotFoundException(__('Invalid corrective preventive action'));
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
            
            $this->_check_password();
            $this->_check_ncs();

            if (!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1) {
                $this->request->data[$this->modelClass]['publish'] = 0;
            }

            if ($this->request->data['CorrectivePreventiveAction']['material_id'] == '-1') {
                $this->loadModel('NonConformingProductsMaterial');
                $NonConformingMaterials = $this->NonConformingProductsMaterial->find('first', array('conditions' => array('NonConformingProductsMaterial.corrective_preventive_action_id' => $id)));
                if (isset($NonConformingMaterials['NonConformingProductsMaterial']['material_id'])) {
                    $this->NonConformingProductsMaterial->delete($NonConformingMaterials['NonConformingProductsMaterial']['id']);
                }
            }
            if ($this->request->data['CorrectivePreventiveAction']['product_id'] == '-1') {
                $this->loadModel('NonConformingProductsMaterial');
                $NonConformingProducts = $this->NonConformingProductsMaterial->find('first', array('conditions' => array('NonConformingProductsMaterial.corrective_preventive_action_id' => $id)));
                if (isset($NonConformingProducts['NonConformingProductsMaterial']['product_id'])) {
                    $this->NonConformingProductsMaterial->delete($NonConformingProducts['NonConformingProductsMaterial']['id']);
                }
            }
            $this->request->data['CorrectivePreventiveAction']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['CorrectivePreventiveAction']['modified_by'] = $this->Session->read('User.id');
            $this->request->data['CorrectivePreventiveAction']['modified'] = date('Y-m-d H:i:s');
            $this->request->data['CorrectivePreventiveAction']['created_by'] = $this->Session->read('User.id');



	      if(isset( $this->request->data['CorrectivePreventiveAction']['suggestion_form_id']) && $this->request->data['CorrectivePreventiveAction']['suggestion_form_id']!= -1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Employee', 'id' => $this->request->data['CorrectivePreventiveAction']['suggestion_form_id']));

          }else  if(isset( $this->request->data['CorrectivePreventiveAction']['customer_complaint_id']) && $this->request->data['CorrectivePreventiveAction']['customer_complaint_id']!= -1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Customer Complaint', 'id' => $this->request->data['CorrectivePreventiveAction']['customer_complaint_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['supplier_registration_id']) && $this->request->data['CorrectivePreventiveAction']['supplier_registration_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Supplier Registration', 'id' => $this->request->data['CorrectivePreventiveAction']['supplier_registration_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['device_id']) && $this->request->data['CorrectivePreventiveAction']['device_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Device', 'id' => $this->request->data['CorrectivePreventiveAction']['device_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['material_id']) && $this->request->data['CorrectivePreventiveAction']['material_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Material', 'id' => $this->request->data['CorrectivePreventiveAction']['material_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['internal_audit_id']) && $this->request->data['CorrectivePreventiveAction']['internal_audit_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Internal Audit ', 'id' => $this->request->data['CorrectivePreventiveAction']['internal_audit_id']));

          }  else if(isset( $this->request->data['CorrectivePreventiveAction']['product_id']) && $this->request->data['CorrectivePreventiveAction']['product_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Product', 'id' => $this->request->data['CorrectivePreventiveAction']['product_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['env_activity_id']) && $this->request->data['CorrectivePreventiveAction']['env_activity_id']!=-1 ){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Env Activity', 'id' => $this->request->data['CorrectivePreventiveAction']['env_activity_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['env_identification_id']) && $this->request->data['CorrectivePreventiveAction']['env_identification_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Env Identification', 'id' => $this->request->data['CorrectivePreventiveAction']['env_activity_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['process_id']) && $this->request->data['CorrectivePreventiveAction']['process_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Process', 'id' => $this->request->data['CorrectivePreventiveAction']['process_id']));

          }else if(isset( $this->request->data['CorrectivePreventiveAction']['risk_assessment_id']) && $this->request->data['CorrectivePreventiveAction']['risk_assessment_id']!=-1){

              $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('Soruce' =>'Risk', 'id' => $this->request->data['CorrectivePreventiveAction']['risk_assessment_id']));

          }else{
            $this->request->data['CorrectivePreventiveAction']['raised_by'] = 'Notices from other parties';
        }
        
            if ($this->CorrectivePreventiveAction->save($this->request->data, false)) {
                
                $this->loadModel('CapaInvestigation');
                $this->loadModel('CapaRootCauseAnalysi');

                // add investigations & root cause 
                $investigations = $this->data['CapaInvestigation']['employee_id'];
                if(count($investigations) > 0){
                    foreach ($investigations as $key => $value) {
                        $capaInvestigation = array();
                        $capaInvestigation['corrective_preventive_action_id'] = $this->CorrectivePreventiveAction->id;
                        $capaInvestigation['details'] = $this->data['CorrectivePreventiveAction']['initial_remarks'];
                        $capaInvestigation['proposed_action'] = $this->data['CorrectivePreventiveAction']['proposed_immidiate_action'];
                        $capaInvestigation['employee_id'] = $value;
                        // $capaInvestigation['target_date'] = date('Y-m-d',strtotime($this->data['CapaInvestigation']['target_date']));
                        $capaInvestigation['target_date'] = $this->data['CapaInvestigation']['target_date'];
                        $capaInvestigation['prepared_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaInvestigation['approved_by'] = $this->data['CorrectivePreventiveAction']['approved_by'];
                        $capaInvestigation['created_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaInvestigation['current_status'] = 0;
                        $capaInvestigation['publish'] = $this->data['CorrectivePreventiveAction']['publish'];
                        $capaInvestigation['soft_delete'] = $this->data['CorrectivePreventiveAction']['soft_delete'];
                        $this->CapaInvestigation->create();
                        $this->CapaInvestigation->save($capaInvestigation,false);
                        if($this->data['CorrectivePreventiveAction']['publish'] == 1){
                            // send email
                            $this->capa_investigation_send_reminder($this->CapaInvestigation->id,'no');

                        }
                    }
                }

                $roorCauses = $this->data['CapaRootCauseAnalysi']['employee_id'];
                if(count($roorCauses) > 0){
                    foreach ($roorCauses as $key => $value) {
                        $capaRootCauseAnalysis = array();
                        $capaRootCauseAnalysis['corrective_preventive_action_id'] = $this->CorrectivePreventiveAction->id;
                        $capaInvestigation['employee_id'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaRootCauseAnalysis['root_cause_details'] = $capaRootCauseAnalysis['root_cause_remarks'] = $this->data['CorrectivePreventiveAction']['initial_remarks'];
                        $capaRootCauseAnalysis['determined_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaRootCauseAnalysis['determined_on_date'] = date('Y-m-d');
                        $capaRootCauseAnalysis['proposed_action'] = $this->data['CorrectivePreventiveAction']['proposed_immidiate_action'];
                        $capaRootCauseAnalysis['action_assigned_to'] = $value;
                        // $capaRootCauseAnalysis['target_date'] = date('Y-m-d',strtotime($this->data['CapaRootCauseAnalysi']['target_date']));
                        $capaRootCauseAnalysis['target_date'] = $this->data['CapaRootCauseAnalysi']['target_date'];
                        $capaRootCauseAnalysis['prepared_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaRootCauseAnalysis['approved_by'] = $this->data['CorrectivePreventiveAction']['approved_by'];
                        $capaRootCauseAnalysis['created_by'] = $this->data['CorrectivePreventiveAction']['prepared_by'];
                        $capaRootCauseAnalysis['current_status'] = 0;
                        $capaRootCauseAnalysis['publish'] = $this->data['CorrectivePreventiveAction']['publish'];
                        $capaRootCauseAnalysis['soft_delete'] = $this->data['CorrectivePreventiveAction']['soft_delete'];
                        
                        $this->CapaRootCauseAnalysi->create();
                        $this->CapaRootCauseAnalysi->save($capaRootCauseAnalysis,false);
                        if($this->data['CorrectivePreventiveAction']['publish'] == 1){
                            $this->root_cause_send_reminder($this->CapaRootCauseAnalysi->id,'no');
                        }
                    }
                }

                // add investigations & root cause 
                
                $investigations = $this->data['CapaInvestigation'];
                if(count($investigations) > 0){
                    foreach ($this->data['CapaInvestigation'] as $key => $value) {
                        if($this->data['CorrectivePreventiveAction']['publish'] == 1){
                            // send email
                            $this->capa_investigation_send_reminder($value['id'],'no');

                        }
                    }
                }

                $roorCauses = $this->data['CapaRootCauseAnalysi'];
                if(count($roorCauses) > 0){
                    foreach ($this->data['CapaRootCauseAnalysi'] as $key => $value) {
                        if($this->data['CorrectivePreventiveAction']['publish'] == 1){
                            $this->root_cause_send_reminder($value['id'],'no');
                        }
                    }
                }

		if($this->request->data['CorrectivePreventiveAction']['document_changes_required'] == 1){
		    if(!empty($this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id'])){
			    $this->loadModel('ChangeAdditionDeletionRequest');
			    $newData = array();
			    $newData['id'] = $this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id'];
			    $newData['others'] = 'CAPA Number: ' . $this->request->data['CorrectivePreventiveAction']['number'];
			    $newData['master_list_of_format'] = $this->request->data['CorrectivePreventiveAction']['master_list_of_format'];
			    $newData['current_document_details'] = $this->request->data['CorrectivePreventiveAction']['current_document_details'];
			    $newData['request_details'] = $this->request->data['CorrectivePreventiveAction']['request_details'];
			    $newData['reason_for_change'] = $this->request->data['CorrectivePreventiveAction']['reason_for_change'];
                            $newData['proposed_document_changes'] = $this->request->data['ChangeAdditionDeletionRequest']['proposed_document_changes'];
                            $newData['proposed_work_instruction_changes'] = $this->request->data['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes'];
			    $this->ChangeAdditionDeletionRequest->save($newData, false);

		    } else{
			    $this->loadModel('ChangeAdditionDeletionRequest');
			    $this->ChangeAdditionDeletionRequest->create();
			    $newData = array();
                $newData['title'] = $this->request->data['CorrectivePreventiveAction']['name'];
                $newData['request_from'] = 'Other';
			    $newData['others'] = 'CAPA Number: ' . $this->request->data['CorrectivePreventiveAction']['number'];
			    $newData['master_list_of_format'] = $this->request->data['CorrectivePreventiveAction']['master_list_of_format'];
			    $newData['current_document_details'] = $this->request->data['CorrectivePreventiveAction']['current_document_details'];
			    $newData['request_details'] = $this->request->data['CorrectivePreventiveAction']['request_details'];
			    $newData['reason_for_change'] = $this->request->data['CorrectivePreventiveAction']['reason_for_change'];
                            $newData['proposed_document_changes'] = $this->request->data['ChangeAdditionDeletionRequest']['proposed_document_changes'];
                            $newData['proposed_work_instruction_changes'] = $this->request->data['ChangeAdditionDeletionRequest']['proposed_work_instruction_changes'];
                            $newData['document_change_accepted'] = 2;
                            $newData['prepared_by'] = $this->request->data['CorrectivePreventiveAction']['prepared_by'];
                            $newData['publish'] = 1;
			    $this->ChangeAdditionDeletionRequest->save($newData, false);

			    $updateCapa['id'] = $this->CorrectivePreventiveAction->id;
			    $updateCapa['change_addition_deletion_request_id'] = $this->ChangeAdditionDeletionRequest->id;
			    $this->CorrectivePreventiveAction->save($updateCapa);
		    }
		} else {
		    if(!empty($this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id'])){
			    $this->loadModel('ChangeAdditionDeletionRequest');
			    $this->ChangeAdditionDeletionRequest->deleteAll(array('ChangeAdditionDeletionRequest.id' => $this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id']), false);

			    $updateCapa['id'] = $this->CorrectivePreventiveAction->id;
			    $updateCapa['change_addition_deletion_request_id'] = 'NULL';
			    $updateCapa['document_changes_required'] = 0;
			    $this->CorrectivePreventiveAction->save($updateCapa);
		    }
		}

                $this->Session->setFlash(__('The corrective preventive action has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The corrective preventive action could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('CorrectivePreventiveAction.' . $this->CorrectivePreventiveAction->primaryKey => $id));
            $this->request->data = $this->CorrectivePreventiveAction->find('first', $options);
        }
        $capaSources = $this->CorrectivePreventiveAction->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        $capaCategories = $this->CorrectivePreventiveAction->CapaCategory->find('list', array('conditions' => array('CapaCategory.publish' => 1, 'CapaCategory.soft_delete' => 0)));
        $capaRatings = $this->CorrectivePreventiveAction->CapaRating->find('list', array('conditions' => array('CapaRating.publish' => 1, 'CapaRating.soft_delete' => 0)));
        $internalAudits = $this->CorrectivePreventiveAction->InternalAudit->find('list', array('conditions' => array('InternalAudit.publish' => 1, 'InternalAudit.soft_delete' => 0)));
        $suggestionForms = $this->CorrectivePreventiveAction->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        $customerComplaints = $this->CorrectivePreventiveAction->CustomerComplaint->find('list', array('conditions' => array('CustomerComplaint.publish' => 1, 'CustomerComplaint.soft_delete' => 0)));
        $supplierRegistrations = $this->CorrectivePreventiveAction->SupplierRegistration->find('list', array('conditions' => array('SupplierRegistration.publish' => 1, 'SupplierRegistration.soft_delete' => 0)));
        $products = $this->CorrectivePreventiveAction->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $procedures = $this->CorrectivePreventiveAction->Procedure->find('list', array('conditions' => array('Procedure.publish' => 1, 'Procedure.soft_delete' => 0)));
        $processess = $this->CorrectivePreventiveAction->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        $tasks = $this->CorrectivePreventiveAction->Task->find('list', array('conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0)));
        $devices = $this->CorrectivePreventiveAction->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0)));
        $materials = $this->CorrectivePreventiveAction->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $masterListOfFormats = $this->CorrectivePreventiveAction->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0), 'recursive' => -1));
        $this->loadModel('ChangeAdditionDeletionRequest');
        $changeAdditionDeletionRequest = $this->ChangeAdditionDeletionRequest->find('first', array('conditions' => array('ChangeAdditionDeletionRequest.id' => $this->request->data['CorrectivePreventiveAction']['change_addition_deletion_request_id']), 'fields' => array('master_list_of_format', 'current_document_details', 'proposed_document_changes', 'proposed_work_instruction_changes', 'current_document_details', 'reason_for_change', 'request_details'), 'recursive' => -1));
        $projects = $this->CorrectivePreventiveAction->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
        $projectActivities = $this->CorrectivePreventiveAction->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));        
        $this->set(compact('capaSources', 'capaCategories', 'capaRatings', 'internalAudits', 'suggestionForms', 'customerComplaints', 'supplierRegistrations', 'products', 'devices', 'materials','masterListOfFormats','procedures','processes','tasks','envActivities','envIdentifications','projects','projectActivities'));

        // load investigations / root cause
        $capaInvestigations = $this->CorrectivePreventiveAction->CapaInvestigation->find('all',array('conditions'=>array('CapaInvestigation.corrective_preventive_action_id'=>$this->request->data['CorrectivePreventiveAction']['id'])));
        $capaRootCauseAnalysis = $this->CorrectivePreventiveAction->CapaRootCauseAnalysi->find('all',array('conditions'=>array('CapaRootCauseAnalysi.corrective_preventive_action_id'=>$this->request->data['CorrectivePreventiveAction']['id'])));
        $this->set('capaInvestigations',$capaInvestigations);
        $this->set('capaRootCauseAnalysis',$capaRootCauseAnalysis);
    }

    public function get_details($detail = null) {
        $category = $this->CorrectivePreventiveAction->CapaCategory->find('first', array('conditions' => array('CapaCategory.id' => $detail)));
        if ($category['CapaCategory']['name'] == 'Product') {
            $this->loadModel('Product');
            $this->Product->recursive = 0;
            $products = $this->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
            $this->set(compact('products'));
        }
    }

    public function capa_investigation_count($capaId = null) {
        $this->loadModel('CapaInvestigation');
       
        return $capaInvestigation_cnt = $this->CapaInvestigation->find('count', array('conditions' => array('CapaInvestigation.corrective_preventive_action_id' => $capaId,
             'CapaInvestigation.soft_delete' => 0)));
     
    }
    
    public function capa_root_cuase_analysis_count($capaId = null) {
        $this->loadModel('CapaRootCauseAnalysi');
        return $capaInvestigation_cnt = $this->CapaRootCauseAnalysi->find('count', array('conditions' => array('CapaRootCauseAnalysi.corrective_preventive_action_id' => $capaId,
             'CapaRootCauseAnalysi.soft_delete' => 0)));
     
    }
    public function capa_revised_dates_count($capaId = null) {
        $this->loadModel('CapaRevisedDate');
        return $capaRevisedDate_cnt = $this->CapaRevisedDate->find('count', array('conditions' => array('CapaRevisedDate.corrective_preventive_action_id' => $capaId,
            'CapaRevisedDate.soft_delete' => 0)));
     
    }

    public function project_activity_id_change($project_id = null){
        $this->loadModel('ProjectActivity');
        $projectActivities = $this->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.project_id'=>$project_id)));
        $this->set('projectActivities',$projectActivities);
    }

    public function capa_monthly_report(){
        $condition1 = $condition2 =$condition3 = $source_conditions = $category_conditions = array();
        if($this->request->data){


            $this->loadModel('Employee');
            if($this->request->data['employee_id'])$employees = $this->Employee->find('list',array('conditions'=>array('Employee.id'=>$this->request->data['employee_id'])));
            
            if($this->request->data['Report']['dates']){
                $dates = split(' - ', $this->request->data['Report']['dates']);
                debug($dates);
                $condition1 = array('CapaInvestigation.target_date BETWEEN ? AND ?' => array(date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1]))));
                $condition2 = array('CapaRootCauseAnalysi.target_date BETWEEN ? AND ?' => array(date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1]))));
                $condition3 = array('CorrectivePreventiveAction.created_date BETWEEN ? AND ?' => array(date('Y-m-d',strtotime($dates[0])),date('Y-m-d',strtotime($dates[1]))));
            }

            if($this->request->data['capa_source_id']){
                $source_conditions = array('CorrectivePreventiveAction.capa_source_id'=>$this->request->data['capa_source_id']);
                $this->set('selected_source',$this->request->data['capa_source_id']);
                
                foreach ($this->request->data['capa_source_id'] as $capa_source_id) {
                    $source_wise_capa = $this->CorrectivePreventiveAction->find('list',array(
                        'fields'=>array('CorrectivePreventiveAction.id','CorrectivePreventiveAction.number'),
                        'conditions'=>array(
                            $condition3,
                            'CorrectivePreventiveAction.publish' => 1,
                            'CorrectivePreventiveAction.soft_delete' => 0,
                            'CorrectivePreventiveAction.capa_source_id'=>$capa_source_id
                        )
                    ));
                    $source_wise_capas[$capa_source_id] = $source_wise_capa;
                }
                
                $this->set('capaSourcesWise',$source_wise_capas);
            }

            if($this->request->data['capa_category_id']){
                $category_conditions = array('CorrectivePreventiveAction.capa_category_id'=>$this->request->data['capa_category_id']);   
                $this->set('selected_cats',$this->request->data['capa_category_id']);

                foreach ($this->request->data['capa_category_id'] as $capa_category_id) {
                    $category_wise_capa = $this->CorrectivePreventiveAction->find('list',array(
                        'fields'=>array('CorrectivePreventiveAction.id','CorrectivePreventiveAction.number'),
                        'conditions'=>array(
                            $condition3,
                            'CorrectivePreventiveAction.publish' => 1,
                            'CorrectivePreventiveAction.soft_delete' => 0,
                            'CorrectivePreventiveAction.capa_category_id'=>$capa_category_id
                        )
                    ));
                    $category_wise_capas[$capa_category_id] = $category_wise_capa;
                }
                $this->set('capaCategoryWise',$category_wise_capas);
            }
        }
        
        foreach ($employees as $employee_id => $name) {
            $total_investigations = $this->CorrectivePreventiveAction->CapaInvestigation->find('count',array('conditions'=>array(
                    'CapaInvestigation.employee_id'=>$employee_id,
                    'CapaInvestigation.publish'=>1,
                    'CapaInvestigation.soft_delete'=>0,
                    $condition1,$source_conditions
                )));
            
            $total_investigations_closed = $this->CorrectivePreventiveAction->CapaInvestigation->find('count',array('conditions'=>array(
                    'CapaInvestigation.employee_id'=>$employee_id,
                    'CapaInvestigation.publish'=>1,
                    'CapaInvestigation.soft_delete'=>0,
                    'CapaInvestigation.current_status'=>1,
                    $condition1,$source_conditions

                )));

            $total_investigations_delayed = $this->CorrectivePreventiveAction->CapaInvestigation->find('count',array('conditions'=>array(
                    'CapaInvestigation.employee_id'=>$employee_id,
                    'CapaInvestigation.publish'=>1,
                    'CapaInvestigation.soft_delete'=>0,
                    'CapaInvestigation.target_date < CapaInvestigation.completed_on_date',
                    $condition1,$source_conditions

                )));


            // root cause 
            $total_rootcause = $this->CorrectivePreventiveAction->CapaRootCauseAnalysi->find('count',array('conditions'=>array(
                    'CapaRootCauseAnalysi.action_assigned_to'=>$employee_id,
                    'CapaRootCauseAnalysi.publish'=>1,
                    'CapaRootCauseAnalysi.soft_delete'=>0,
                    $condition2,$category_conditions
                )));
            
            $total_rootcause_closed = $this->CorrectivePreventiveAction->CapaRootCauseAnalysi->find('count',array('conditions'=>array(
                    'CapaRootCauseAnalysi.action_assigned_to'=>$employee_id,
                    'CapaRootCauseAnalysi.publish'=>1,
                    'CapaRootCauseAnalysi.soft_delete'=>0,
                    'CapaRootCauseAnalysi.current_status'=>1,
                    $condition2,$category_conditions

                )));

            $total_rootcause_delayed = $this->CorrectivePreventiveAction->CapaRootCauseAnalysi->find('count',array('conditions'=>array(
                    'CapaRootCauseAnalysi.action_assigned_to'=>$employee_id,
                    'CapaRootCauseAnalysi.publish'=>1,
                    'CapaRootCauseAnalysi.soft_delete'=>0,
                    'CapaRootCauseAnalysi.target_date < CapaRootCauseAnalysi.action_completed_on_date',
                    $condition2,$category_conditions

                )));

            $open = $total_rootcause - $total_rootcause_closed;
            $result[$name]['Investigations'] = array('total'=>$total_investigations,'closed'=>$total_investigations_closed,'open'=>$open,'delayed'=>$total_investigations_delayed);
            $result[$name]['Root Cause Analysis'] = array('total'=>$total_rootcause,'closed'=>$total_rootcause_closed,'open'=>$open,'delayed'=>$total_rootcause_delayed);
            # code...
        }
        
        $this->set('selected_employees',array_keys($employees));
        $employees = $this->_get_employee_list();
        $this->set('result',$result);
        $this->set('employees',$employees);


        // load masters
        $capaSources = $this->CorrectivePreventiveAction->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        $capaCategories = $this->CorrectivePreventiveAction->CapaCategory->find('list', array('conditions' => array('CapaCategory.publish' => 1, 'CapaCategory.soft_delete' => 0)));
        $capaRatings = $this->CorrectivePreventiveAction->CapaRating->find('list', array('conditions' => array('CapaRating.publish' => 1, 'CapaRating.soft_delete' => 0)));
        // $internalAudits = $this->CorrectivePreventiveAction->InternalAudit->find('list', array('conditions' => array('InternalAudit.publish' => 1, 'InternalAudit.soft_delete' => 0)));
        // $suggestionForms = $this->CorrectivePreventiveAction->SuggestionForm->find('list', array('conditions' => array('SuggestionForm.publish' => 1, 'SuggestionForm.soft_delete' => 0)));
        // $customerComplaints = $this->CorrectivePreventiveAction->CustomerComplaint->find('list', array('conditions' => array('CustomerComplaint.publish' => 1, 'CustomerComplaint.soft_delete' => 0)));
        // $supplierRegistrations = $this->CorrectivePreventiveAction->SupplierRegistration->find('list', array('conditions' => array('SupplierRegistration.publish' => 1, 'SupplierRegistration.soft_delete' => 0)));
        // $products = $this->CorrectivePreventiveAction->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        // $procedures = $this->CorrectivePreventiveAction->Procedure->find('list', array('conditions' => array('Procedure.publish' => 1, 'Procedure.soft_delete' => 0)));
        // $processes = $this->CorrectivePreventiveAction->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        // $riskAssessments = $this->CorrectivePreventiveAction->RiskAssessment->find('list', array('conditions' => array('RiskAssessment.publish' => 1, 'RiskAssessment.soft_delete' => 0)));
        // $tasks = $this->CorrectivePreventiveAction->Task->find('list', array('conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0)));
        // $devices = $this->CorrectivePreventiveAction->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0)));
        // $materials = $this->CorrectivePreventiveAction->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        // $envActivities = $this->CorrectivePreventiveAction->EnvActivity->find('list',array('conditions'=>array('EnvActivity.publish'=>1,'EnvActivity.soft_delete'=>0)));
        // $envIdentifications = $this->CorrectivePreventiveAction->EnvIdentification->find('list',array('conditions'=>array('EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));
        // $projects = $this->CorrectivePreventiveAction->Project->find('list',array('conditions'=>array('Project.publish'=>1,'Project.soft_delete'=>0)));
        // $projectActivities = $this->CorrectivePreventiveAction->ProjectActivity->find('list',array('conditions'=>array('ProjectActivity.publish'=>1,'ProjectActivity.soft_delete'=>0)));
        // $masterListOfFormats = $this->CorrectivePreventiveAction->MasterListOfFormat->find('list', array('conditions' => array('MasterListOfFormat.publish' => 1, 'MasterListOfFormat.soft_delete' => 0), 'recursive' => -1));
        $this->set(compact('capaSources', 'capaCategories', 'capaRatings', 'internalAudits', 'suggestionForms', 'customerComplaints', 'supplierRegistrations', 'products', 'devices', 'materials','masterListOfFormats','procedures','processes','riskAssessments', 'tasks','envActivities','envIdentifications','projects','projectActivities'));
    }

    public function cdates(){
        
        $capas = $this->CorrectivePreventiveAction->find('all');
        foreach ($capas as $capa) {
            $newCapa = $capa['CorrectivePreventiveAction'];
            $newCapa['created_date'] = date('Y-m-d',strtotime($capa['CorrectivePreventiveAction']['created']));
            $newtarget = date('Y-m-d',strtotime($newCapa['created_date'].'+7 days'));
            $newCapa['target_date'] = $newtarget;
            $this->CorrectivePreventiveAction->create();
            $this->CorrectivePreventiveAction->save($newCapa,false);
        
        }
        exit;
    }

    public function _check_password($data = null){
            
            
            $capa = $this->CorrectivePreventiveAction->find('first',array('recursive'=>0, 'fields'=>array('CorrectivePreventiveAction.id','CorrectivePreventiveAction.capa_password'), 'conditions'=>array('CorrectivePreventiveAction.id'=>$this->data['CorrectivePreventiveAction']['id'])));
            if($capa['CorrectivePreventiveAction']['capa_password'] != null){
                if($this->data['CorrectivePreventiveAction']['current_status'] == 1){
                    if($capa['CorrectivePreventiveAction']['capa_password'] != $this->data['CorrectivePreventiveAction']['capa_password']){
                        $this->Session->setFlash(__('You need password to close this CAPA.'));
                        $this->redirect(array('action' => $this->action,$capa['CorrectivePreventiveAction']['id']));                    
                    }
                }                    
            }
        }

    public function _check_ncs(){
        $investigations = $this->CorrectivePreventiveAction->CapaInvestigation->find('count',array('conditions'=>array('CapaInvestigation.corrective_preventive_action_id'=>$this->data['CorrectivePreventiveAction']['id'])));
        $rootcause = $this->CorrectivePreventiveAction->CapaRootCauseAnalysi->find('count',array('conditions'=>array('CapaRootCauseAnalysi.corrective_preventive_action_id'=>$this->data['CorrectivePreventiveAction']['id'])));
        if($this->data['CorrectivePreventiveAction']['current_status'] == 1){
            if(($investigations + $rootcause) == 0){
                $this->Session->setFlash(__('You can not close CAPA without adding investigation or Root Cause.'));
                $this->redirect(array('action' => $this->action,$this->data['CorrectivePreventiveAction']['id']));                    
            }
        }
    }

    public function capa_ratings(){
        $capaRatings = $this->CorrectivePreventiveAction->CapaRating->find('list',array('conditions'=>array('CapaRating.publish'=>1,'CapaRating.soft_delete'=>0)));
        
        foreach ($capaRatings as $key => $value) {
            $result[$value]['Open'] = $this->CorrectivePreventiveAction->find('count',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0,'CorrectivePreventiveAction.capa_rating_id'=>$key,'CorrectivePreventiveAction.current_status'=>0)));
            $result[$value]['Close'] = $this->CorrectivePreventiveAction->find('count',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0,'CorrectivePreventiveAction.capa_rating_id'=>$key,'CorrectivePreventiveAction.current_status'=>1)));
            $result[$value]['Total'] = $this->CorrectivePreventiveAction->find('count',array('conditions'=>array('CorrectivePreventiveAction.publish'=>1,'CorrectivePreventiveAction.soft_delete'=>0,'CorrectivePreventiveAction.capa_rating_id'=>$key)));
        }
        return $result;
    }
}

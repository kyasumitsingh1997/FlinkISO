<?php

App::uses('AppController', 'Controller');

/**
 * NonConformingProductsMaterials Controller
 *
 * @property NonConformingProductsMaterial $NonConformingProductsMaterial
 */
class NonConformingProductsMaterialsController extends AppController {

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
        $this->paginate = array('order' => array('NonConformingProductsMaterial.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->NonConformingProductsMaterial->recursive = 0;
        $this->set('nonConformingProductsMaterials', $this->paginate());

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
            foreach ($searchKeys as $search_key):
                foreach ($this->request->query['search_fields'] as $search):
                    if ($this->request->query['strict_search'] == 0)
                        $searchArray[] = array('NonConformingProductsMaterial.' . $search => $search_key);
                    else
                        $searchArray[] = array('NonConformingProductsMaterial.' . $search . ' like ' => '%' . $search_key . '%');
                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }
        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('NonConformingProductsMaterial.branchid' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }
        if ($this->request->query['product_id'] != '-1') {
            $productConditions[] = array('NonConformingProductsMaterial.product_id' => $this->request->query['product_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $productConditions);
            else
                $conditions[] = array('or' => $productConditions);
        }
        if ($this->request->query['material_id'] != '-1') {
            $materialConditions[] = array('NonConformingProductsMaterial.material_id' => $this->request->query['material_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $materialConditions);
            else
                $conditions[] = array('or' => $materialConditions);
        }
        if ($this->request->query['capa_source_id'] != '-1') {
            $capaSourceConditions[] = array('NonConformingProductsMaterial.capa_source_id' => $this->request->query['capa_source_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $capaSourceConditions);
            else
                $conditions[] = array('or' => $capaSourceConditions);
        }
        if ($this->request->query['corrective_preventive_action_id'] != '-1') {
            $capaConditions[] = array('NonConformingProductsMaterial.corrective_preventive_action_id' => $this->request->query['corrective_preventive_action_id']);
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => $capaConditions);
            else
                $conditions[] = array('or' => $capaConditions);
        }
        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('NonConformingProductsMaterial.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'NonConformingProductsMaterial.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);


        if ($this->Session->read('User.is_mr') == 0)
            $onlyBranch = array('NonConformingProductsMaterial.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('NonConformingProductsMaterial.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);
        $this->NonConformingProductsMaterial->recursive = 0;
        $this->paginate = array('order' => array('NonConformingProductsMaterial.sr_no' => 'DESC'), 'conditions' => $conditions, 'NonConformingProductsMaterial.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('nonConformingProductsMaterials', $this->paginate());
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
        if (!$this->NonConformingProductsMaterial->exists($id)) {
            throw new NotFoundException(__('Invalid non conforming products material'));
        }
        $options = array('conditions' => array('NonConformingProductsMaterial.' . $this->NonConformingProductsMaterial->primaryKey => $id));
        $nonConformingProductsMaterial = $this->NonConformingProductsMaterial->find('first', $options);
        $this->set('nonConformingProductsMaterial', $nonConformingProductsMaterial);

        
        $this->loadModel('CorrectivePreventiveAction');
        $correctiveActions = $this->CorrectivePreventiveAction->find('first', array('conditions' => array('CorrectivePreventiveAction.id' => $nonConformingProductsMaterial['CorrectivePreventiveAction']['id'], 'CorrectivePreventiveAction.publish' => 1, 'CorrectivePreventiveAction.soft_delete' => 0)));
        
        
        $this->loadModel('Division');
        $divisions = $this->Division->find('list', array('conditions' => array('Division.publish' => 1, 'Division.soft_delete' => 0)));
       
        $this->set(compact('correctiveActions','divisions'));
        
        
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
            $this->request->data['NonConformingProductsMaterial']['system_table_id'] = $this->_get_system_table_id();
            $this->request->data['NonConformingProductsMaterial']['nc_number'] = $this->generate_cp_number('NonConformingProductsMaterial','NCR','nc_number'); 
            $this->NonConformingProductsMaterial->create();
            if ($this->NonConformingProductsMaterial->save($this->request->data)) {
                $new_data = array();
                if($this->request->data['NonConformingProductsMaterial']['add_corrective_action'] == 1){
                
                        if($this->request->data['NonConformingProductsMaterial']['product_id'] != '-1' && $this->request->data['NonConformingProductsMaterial']['product_id'] != NULL){
                            $capaCategories = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaCategory->find('first', array(
                                'fields'=>array('id','name'),
                                'conditions' => array('CapaCategory.name' => 'Product')));
                            $this->request->data['CorrectivePreventiveAction']['capa_category_id'] = $capaCategories['CapaCategory']['id'];
                        }else if($this->request->data['NonConformingProductsMaterial']['material_id'] != '-1' && $this->request->data['NonConformingProductsMaterial']['material_id'] != NULL){
                            $capaCategories = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaCategory->find('first', array(
                                'fields'=>array('id','name'),
                                'conditions' => array('CapaCategory.name' => 'Material')));
                            $this->request->data['CorrectivePreventiveAction']['capa_category_id'] = $capaCategories['CapaCategory']['id'];
                        }
                    
                        $this->NonConformingProductsMaterial->CorrectivePreventiveAction->create();
                        $this->request->data['CorrectivePreventiveAction'] = $this->request->data['Corrective']['CorrectivePreventiveAction'];
                        $this->request->data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('source'=>'NonConformingProductsMaterial','id'=>$this->NonConformingProductsMaterial->id));
                        $this->request->data['CorrectivePreventiveAction']['product_id'] = $this->request->data['NonConformingProductsMaterial']['product_id'];
                        $this->request->data['CorrectivePreventiveAction']['material_id'] = $this->request->data['NonConformingProductsMaterial']['material_id'];
                        $this->request->data['CorrectivePreventiveAction']['non_conforming_products_material_id'] = $this->NonConformingProductsMaterial->id;
                        $this->request->data['CorrectivePreventiveAction']['name'] = $this->request->data['NonConformingProductsMaterial']['title'];
                        $this->request->data['CorrectivePreventiveAction']['capa_type'] = 0;
                        $this->request->data['CorrectivePreventiveAction']['publish'] = $this->request->data['NonConformingProductsMaterial']['publish'];;
                        $this->request->data['CorrectivePreventiveAction']['capa_source_id'] = $this->request->data['CorrectivePreventiveAction']['capa_source_id'];
                        $this->request->data['CorrectivePreventiveAction']['capa_category_id'] = $this->request->data['CorrectivePreventiveAction']['capa_category_id'];

                       
                        $this->NonConformingProductsMaterial->CorrectivePreventiveAction->save($this->request->data['CorrectivePreventiveAction'], false);
                        
                    }
                
                if($this->request->data['NonConformingProductsMaterial']['add_preventive_action'] == 1){
                        $this->request->data['CorrectivePreventiveAction'] = array();
                        if($this->request->data['NonConformingProductsMaterial']['product_id'] != '-1' && 
                                $this->request->data['NonConformingProductsMaterial']['product_id'] != NULL){
                                $capaCategories = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaCategory->find('first', 
                                        array(
                                    'fields'=>array('id','name'),
                                    'conditions' => array('CapaCategory.name' => 'Product')));
                                $this->request->data['CorrectivePreventiveAction']['capa_category_id'] = $capaCategories['CapaCategory']['id'];
                        }else if($this->request->data['NonConformingProductsMaterial']['material_id'] != '-1' && 
                                $this->request->data['NonConformingProductsMaterial']['material_id'] != NULL){
                                $capaCategories = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaCategory->find('first', array(
                                    'fields'=>array('id','name'),
                                    'conditions' => array('CapaCategory.name' => 'Material')));
                                $this->request->data['CorrectivePreventiveAction']['capa_category_id'] = $capaCategories['CapaCategory']['id'];
                        }
                    
                        $this->NonConformingProductsMaterial->CorrectivePreventiveAction->create();
                        

                        $new_data['CorrectivePreventiveAction'] = $this->request->data['Preventive']['CorrectivePreventiveAction'];
                        //$new_data['CorrectivePreventiveAction']['raised_by'] = $this->request->data['NonConformingProductsMaterial']['reported_by'];
                        $new_data['CorrectivePreventiveAction']['raised_by'] = json_encode(array('source'=>'NonConformingProductsMaterial','id'=>$this->NonConformingProductsMaterial->id));
                        $new_data['CorrectivePreventiveAction']['product_id'] = $this->request->data['NonConformingProductsMaterial']['product_id'];
                        $new_data['CorrectivePreventiveAction']['procedure_id'] = $this->request->data['NonConformingProductsMaterial']['procedure_id'];
                        $new_data['CorrectivePreventiveAction']['material_id'] = $this->request->data['NonConformingProductsMaterial']['material_id'];
                        $new_data['CorrectivePreventiveAction']['non_conforming_products_material_id'] = $this->NonConformingProductsMaterial->id;
                        $new_data['CorrectivePreventiveAction']['name'] = $this->request->data['NonConformingProductsMaterial']['title'];
                        $new_data['CorrectivePreventiveAction']['capa_type'] = 1;
                        $new_data['CorrectivePreventiveAction']['publish'] = $this->request->data['NonConformingProductsMaterial']['publish'];;
                        $new_data['CorrectivePreventiveAction']['capa_source_id'] = $this->request->data['Preventive']['CorrectivePreventiveAction']['capa_source_id'];
                        $new_data['CorrectivePreventiveAction']['capa_category_id'] = $this->request->data['Preventive']['CorrectivePreventiveAction']['capa_category_id'];
                        $this->NonConformingProductsMaterial->CorrectivePreventiveAction->save($new_data['CorrectivePreventiveAction'], false);
                       
                        
                      
                    }
                  
               

                if($this->request->data['NonConformingProductsMaterial']['product_id'] != '-1' && $this->request->data['NonConformingProductsMaterial']['product_id'] != NULL){
                    $this->NonConformingProductsMaterial->Product->read(null,$this->request->data['NonConformingProductsMaterial']['product_id']);
                    $this->NonConformingProductsMaterial->Product->set('nc_found',1);
                    $this->NonConformingProductsMaterial->Product->save();
                    
                }else if($this->request->data['NonConformingProductsMaterial']['material_id'] != '-1' && $this->request->data['NonConformingProductsMaterial']['material_id'] != NULL){
                    $this->NonConformingProductsMaterial->Material->read(null,$this->request->data['NonConformingProductsMaterial']['material_id']);
                    $this->NonConformingProductsMaterial->Material->set('nc_found',1);
                    $this->NonConformingProductsMaterial->Material->save();
                }


                $this->Session->setFlash(__('The non conforming products material has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->NonConformingProductsMaterial->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The non conforming products material could not be saved. Please, try again.'));
            }
        }
        $reported_bies = $this->NonConformingProductsMaterial->ReportedBy->find('list', array('conditions' => array('ReportedBy.publish' => 1, 'ReportedBy.soft_delete' => 0)));
        $capaSources = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));        
        $capaCategories = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaCategory->find('list', array('conditions' => array('CapaCategory.publish' => 1, 'CapaCategory.soft_delete' => 0)));
        $materials = $this->NonConformingProductsMaterial->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $products = $this->NonConformingProductsMaterial->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $capaSources = $this->NonConformingProductsMaterial->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        $correctivePreventiveActions = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->find('list', array('conditions' => array('CorrectivePreventiveAction.publish' => 1, 'CorrectivePreventiveAction.soft_delete' => 0)));
        $this->loadModel('Division');
        $divisions = $this->Division->find('list', array('conditions' => array('Division.publish' => 1, 'Division.soft_delete' => 0)));
        $procedures = $this->NonConformingProductsMaterial->Procedure->find('list', array('conditions' => array('Procedure.publish' => 1, 'Procedure.soft_delete' => 0)));
        $processes = $this->NonConformingProductsMaterial->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        $riskAssessments = $this->NonConformingProductsMaterial->RiskAssessment->find('list', array('conditions' => array('RiskAssessment.publish' => 1, 'RiskAssessment.soft_delete' => 0)));

        $this->set(compact('materials', 'products', 'capaSources', 'correctivePreventiveActions','reportedBies','capaSources','capaCategories','divisions','procedures','processes','riskAssessments'));

        $nc_number = $this->generate_cp_number('NonConformingProductsMaterial','NCR','nc_number');
        $this->set('nc_number',$nc_number);
    }

    /**
     *  *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->NonConformingProductsMaterial->exists($id)) {
            throw new NotFoundException(__('Invalid non conforming products material'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['NonConformingProductsMaterial']['system_table_id'] = $this->_get_system_table_id();
            if ($this->NonConformingProductsMaterial->save($this->request->data)) {                

                $this->Session->setFlash(__('The non conforming products material has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The non conforming products material could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('NonConformingProductsMaterial.' . $this->NonConformingProductsMaterial->primaryKey => $id));
            $this->request->data = $this->NonConformingProductsMaterial->find('first', $options);
        }
        $reported_bies = $this->NonConformingProductsMaterial->ReportedBy->find('list', array('conditions' => array('ReportedBy.publish' => 1, 'ReportedBy.soft_delete' => 0)));
        $capaSources = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        $capaCategories = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaCategory->find('list', array('conditions' => array('CapaCategory.publish' => 1, 'CapaCategory.soft_delete' => 0)));
        $materials = $this->NonConformingProductsMaterial->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $products = $this->NonConformingProductsMaterial->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $capaSources = $this->NonConformingProductsMaterial->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        
        if($this->request->data['NonConformingProductsMaterial']['product_id'] != NULL){
            $condition = array('CorrectivePreventiveAction.product_id' => $this->request->data['NonConformingProductsMaterial']['product_id']);
        }elseif($this->request->data['NonConformingProductsMaterial']['product_id'] != NULL){
            $condition = array('CorrectivePreventiveAction.material_id' => $this->request->data['NonConformingProductsMaterial']['material_id']);
        }
            
        
        $this->loadModel('CorrectivePreventiveAction');
        $correctiveActions = $this->CorrectivePreventiveAction->find('first', array('conditions' => array('CorrectivePreventiveAction.id' => $this->request->data['CorrectivePreventiveAction']['id'], 'CorrectivePreventiveAction.publish' => 1, 'CorrectivePreventiveAction.soft_delete' => 0)));

        $this->loadModel('Division');
        $divisions = $this->Division->find('list', array('conditions' => array('Division.publish' => 1, 'Division.soft_delete' => 0)));
        $this->set(compact('correctiveActions','preventiveActions','divisions'));
        $procedures = $this->NonConformingProductsMaterial->Procedure->find('list', array('conditions' => array('Procedure.publish' => 1, 'Procedure.soft_delete' => 0)));
        $processes = $this->NonConformingProductsMaterial->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        $riskAssessments = $this->NonConformingProductsMaterial->RiskAssessment->find('list', array('conditions' => array('RiskAssessment.publish' => 1, 'RiskAssessment.soft_delete' => 0)));
        $this->set(compact('materials', 'products', 'capaSources', 'correctivePreventiveActions','reportedBies','capaSources','capaCategories','divisions','procedures','processes','riskAssessments'));
        
    }

      public function get_ncs($i = 2) {
        $condition1 = null;
        $condition2 = null;  
          
          
        $condition1 = $this->_check_request();
        
        //$i == 2 means all
        //$i == 1 means closed
        //$i == 0 means open
        
        if($i != 2){
            $condition2 = array('NonConformingProductsMaterial.status' => $i, 'NonConformingProductsMaterial.soft_delete' => 0, 'NonConformingProductsMaterial.publish' => 1);
        }else{
            $condition2 = array('NonConformingProductsMaterial.soft_delete' => 0, 'NonConformingProductsMaterial.publish' => 1);
        }
        $conditions = array($condition1, $condition2);
        $this->paginate = array('order' => array('NonConformingProductsMaterial.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->NonConformingProductsMaterial->recursive = 0;
        $this->set('nonConformingProductsMaterials', $this->paginate());
        
        $modelName = $this->modelClass;
        $count = $this->$modelName->find('count', array('conditions' => $conditions));
        $published = $this->$modelName->find('count', array('conditions' => array($conditions, $modelName . '.publish' => 1, $modelName . '.soft_delete' => 0)));
        $unpublished = $this->$modelName->find('count', array('conditions' => array($conditions, $modelName . '.publish' => 0, $modelName . '.soft_delete' => 0)));
        $deleted = $this->$modelName->find('count', array('conditions' => array($conditions, $modelName . '.soft_delete' => 1)));
        $this->set(compact('count', 'published', 'unpublished', 'deleted'));
             
      
     
    }
    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->NonConformingProductsMaterial->exists($id)) {
            throw new NotFoundException(__('Invalid non conforming products material'));
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
            if ($this->NonConformingProductsMaterial->save($this->request->data)) {
                

                $this->Session->setFlash(__('The non conforming products material has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals ();

            } else {
                $this->Session->setFlash(__('The non conforming products material could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('NonConformingProductsMaterial.' . $this->NonConformingProductsMaterial->primaryKey => $id));
            $this->request->data = $this->NonConformingProductsMaterial->find('first', $options);
        }
        $reported_bies = $this->NonConformingProductsMaterial->ReportedBy->find('list', array('conditions' => array('ReportedBy.publish' => 1, 'ReportedBy.soft_delete' => 0)));
        $capaSources = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        $capaCategories = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->CapaCategory->find('list', array('conditions' => array('CapaCategory.publish' => 1, 'CapaCategory.soft_delete' => 0)));
        $materials = $this->NonConformingProductsMaterial->Material->find('list', array('conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $products = $this->NonConformingProductsMaterial->Product->find('list', array('conditions' => array('Product.publish' => 1, 'Product.soft_delete' => 0)));
        $capaSources = $this->NonConformingProductsMaterial->CapaSource->find('list', array('conditions' => array('CapaSource.publish' => 1, 'CapaSource.soft_delete' => 0)));
        
        if($this->request->data['NonConformingProductsMaterial']['product_id'] != NULL){
            $condition = array('CorrectivePreventiveAction.product_id' => $this->request->data['NonConformingProductsMaterial']['product_id']);
        }elseif($this->request->data['NonConformingProductsMaterial']['product_id'] != NULL){
            $condition = array('CorrectivePreventiveAction.material_id' => $this->request->data['NonConformingProductsMaterial']['material_id']);
        }
            
        $correctivePreventiveActions = $this->NonConformingProductsMaterial->CorrectivePreventiveAction->find('all', array('conditions' => array(
            $condition ,   
            'CorrectivePreventiveAction.current_status' => 0,
            'CorrectivePreventiveAction.publish' => 1, 
            'CorrectivePreventiveAction.soft_delete' => 0)));
        
        $procedures = $this->NonConformingProductsMaterial->Procedure->find('list', array('conditions' => array('Procedure.publish' => 1, 'Procedure.soft_delete' => 0)));
        $processes = $this->NonConformingProductsMaterial->Process->find('list', array('conditions' => array('Process.publish' => 1, 'Process.soft_delete' => 0)));
        $riskAssessments = $this->NonConformingProductsMaterial->RiskAssessment->find('list', array('conditions' => array('RiskAssessment.publish' => 1, 'RiskAssessment.soft_delete' => 0)));
        $this->set(compact('materials', 'products', 'capaSources', 'correctivePreventiveActions','reportedBies','capaSources','capaCategories','divisions','procedures','processes','riskAssessments'));
    }
}

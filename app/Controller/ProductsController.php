<?php

App::uses('AppController', 'Controller');

/**
 * Products Controller
 *
 * @property Product $Product
 */
class ProductsController extends AppController {

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
        $this->paginate = array('order' => array('Product.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->Product->recursive = 1;
        $this->set('products', $this->paginate());
        $materials = $this->get_model_list('Material');
        $this->set('materials',$materials);
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
                        $searchArray[] = array('Product.' . $search => $searchKey);
                    else
                        $searchArray[] = array('Product.' . $search . ' like ' => '%' . $searchKey . '%');

                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('Product.branch_id' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }
        if ($this->request->query['department_id']) {
            foreach ($this->request->query['department_id'] as $department):
                $departmentConditions[] = array('Product.department_id' => $department);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $departmentConditions));
            else
                $conditions[] = array('or' => $departmentConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('Product.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'Product.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);

        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('Product.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('Product.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->Product->recursive = 0;
        $this->paginate = array('order' => array('Product.sr_no' => 'DESC'), 'conditions' => $conditions, 'Product.soft_delete' => 0, 'recursive' => 1);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $materials = $this->get_model_list('Material');
        $this->set('materials', $materials);
        $this->set('products', $this->paginate());
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
        $week = date('yW');

        if (!$this->Product->exists($id)) {
            throw new NotFoundException(__('Invalid product'));
        }
        $this->Product->hasMany['ProductionWeeklyPlan']['conditions'] = array('ProductionWeeklyPlan.week >='=>date('yW'));
        // /$this->Product->hasMany['Production']['conditions'] = array('Production.current_status '=>date('yW'));
        $options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
        $product = $this->Product->find('first', $options);
        $materials = $this->get_model_list('Material');
        $materialNames = array();
        $prodMatDetails =array();

        $productions = $this->Product->Production->find('all',array(
            'conditions'=>array('Production.product_id'=>$id,'ProductionWeeklyPlan.week >=' => date('yW')),
            'fields'=>array(),
            'order'=>array())
        );
        $this->set(compact('productions'));
        $this->loadModel('Material');
        foreach ($product['ProductMaterial'] as $ProductMaterial):
            $material = $this->Material->find('first',array('recursive'=>1, 'fields'=>array('Material.id','Material.name','Unit.name'), 'conditions'=>array('Material.id'=>$ProductMaterial['material_id'])));
            $productMaterials[] = array('ProductMaterial'=> $ProductMaterial,'Material'=>$material);
        endforeach;
        
        $this->set(compact('product','productMaterials', 'prodMatDetails'));
        $dir = new Folder(Configure::read('MediaPath') . 'files/' . $this->Session->read('User.company_id') . '/upload/' . $product['Product']['created_by'] . '/products/' . $product['Product']['id'] . '/ProductUpload');
        $folders = $dir->read();
        $count = count($folders[1]);
        $this->set('uploadCount', $count);
        $plans = $this->_get_specials()['Product Files'];
        $this->set('product_doc_types', $plans);

        $this->loadModel('FileUpload');
        foreach ($plans as $plan):
            $count = 0;
            $cnt = $this->FileUpload->find('count',array(
                'conditions'=>array('FileUpload.system_table_id'=>$this->_get_system_table_id(),
                    'FileUpload.file_status'=>1,'FileUpload.archived'=>0,
                    'FileUpload.record'=>$id,
                    'FileUpload.file_dir LIKE '=> '%'.Inflector::Classify($plan).'%'
                    )));
            $plan =  str_replace(' ', '', $plan);
            $count = $cnt;
            $files[$plan] = $cnt;
            $this->set($files, $cnt);
        endforeach;        
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
            $this->request->data['Product']['system_table_id'] = $this->_get_system_table_id();
            if(isset($this->request->data['Customer']['publish'])){
                $this->request->data['Product']['publish'] =   $this->request->data['Customer']['publish'];
            }
            $this->Product->create();
            if(isset($this->request->data['Customer']['publish'])){
                $this->request->data['Product']['publish'] =   $this->request->data['Customer']['publish'];
            }
            if ($this->Product->save($this->request->data)) {

                $this->loadModel('ProductMaterial');
                foreach ($this->request->data['ProductMaterial'] as $val) {
                    if($val['quantity']>0){
                        $this->ProductMaterial->create();
                        $valData = array();
                        $valData['product_id'] = $this->Product->id;
                        $valData['material_id'] = $val['material_id'];
                        $valData['quantity'] = $val['quantity'];
                        $valData['publish'] = 1;
                        $valData['soft_delete'] = 0;
                        $valData['system_table_id'] = $this->_get_system_table_id();
                        $this->ProductMaterial->save($valData, false);    
                    }
                    
                }

                $this->Session->setFlash(__('The product has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $this->Product->id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
        }
        $this->loadModel('Material');
        $PublishedMaterialList = $this->Material->find('all', array('order'=>array('Material.name'=>'ASC'), 'conditions' => array('Material.publish' => 1, 'Material.soft_delete' => 0)));
        $productCategories = $this->Product->ProductCategory->find('list',array('conditions'=>array('ProductCategory.publish'=>1,'ProductCategory.soft_delete'=>0)));
        $this->set(compact('PublishedMaterialList','productCategories'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->Product->exists($id)) {
            throw new NotFoundException(__('Invalid product'));
        }

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Product']['system_table_id'] = $this->_get_system_table_id();
            if ($this->Product->save($this->request->data, false)) {
                $this->loadModel('ProductMaterial');
                $this->ProductMaterial->deleteAll(array('ProductMaterial.product_id' => $this->Product->id), false);
                $this->loadModel('ProductMaterial');
                foreach ($this->request->data['ProductMaterial'] as $val) {
                    if($val['quantity']>0){
                        $this->ProductMaterial->create();
                        $valData = array();
                        $valData['product_id'] = $this->Product->id;
                        $valData['material_id'] = $val['material_id'];
                        $valData['quantity'] = $val['quantity'];
                        $valData['publish'] = 1;
                        $valData['soft_delete'] = 0;
                        $valData['system_table_id'] = $this->_get_system_table_id();
                        $this->ProductMaterial->save($valData, false);    
                    }
                    
                }

                $this->Session->setFlash(__('The product has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
            $this->request->data = $this->Product->find('first', $options);
            $this->loadModel('Material');
            foreach ($this->request->data['ProductMaterial'] as $ProductMaterial):
                $materialList[] = $ProductMaterial['material_id'];
                $material = $this->Material->find('first',array('recursive'=>1, 'fields'=>array('Material.id','Material.name','Unit.name'), 'conditions'=>array('Material.id'=>$ProductMaterial['material_id'])));
                $productMaterials[] = array('ProductMaterial'=> $ProductMaterial,'Material'=>$material);
            endforeach;        
            $this->set(compact('product','productMaterials', 'prodMatDetails'));
        }
        $branches = $this->Product->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $departments = $this->Product->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $this->loadModel('Material');
        if(count($materialList) == 1){
            $materialList = $materialList[0];
        }
        $PublishedMaterialList = $this->Material->find('all', array('order'=>array('Material.name'=>'ASC'), 'conditions' => array('Material.id <> ' => $materialList, 'Material.publish' => 1, 'Material.soft_delete' => 0)));
        $productCategories = $this->Product->ProductCategory->find('list',array('conditions'=>array('ProductCategory.publish'=>1,'ProductCategory.soft_delete'=>0)));
        $this->set(compact('branches', 'departments', 'PublishedMaterialList','productCategories'));
     }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approval_id = null) {
        if (!$this->Product->exists($id)) {
            throw new NotFoundException(__('Invalid product'));
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
            if ($this->Product->save($this->request->data)) {

                $this->loadModel('ProductMaterial');
                $this->ProductMaterial->deleteAll(array('ProductMaterial.product_id' => $this->Product->id), false);
                $this->loadModel('ProductMaterial');
                foreach ($this->request->data['ProductMaterial'] as $val) {
                    if($val['quantity']>0){
                        $this->ProductMaterial->create();
                        $valData = array();
                        $valData['product_id'] = $this->Product->id;
                        $valData['material_id'] = $val['material_id'];
                        $valData['quantity'] = $val['quantity'];
                        $valData['publish'] = 1;
                        $valData['soft_delete'] = 0;
                        $valData['system_table_id'] = $this->_get_system_table_id();
                        $this->ProductMaterial->save($valData, false);    
                    }
                    
                }

                $this->Session->setFlash(__('The product has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals ();

            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
            $this->request->data = $this->Product->find('first', $options);
            $this->loadModel('Material');
            foreach ($this->request->data['ProductMaterial'] as $ProductMaterial):
                $materialList[] = $ProductMaterial['material_id'];
                $material = $this->Material->find('first',array('recursive'=>1, 'fields'=>array('Material.id','Material.name','Unit.name'), 'conditions'=>array('Material.id'=>$ProductMaterial['material_id'])));
                $productMaterials[] = array('ProductMaterial'=> $ProductMaterial,'Material'=>$material);
            endforeach;        
            $this->set(compact('product','productMaterials', 'prodMatDetails'));
        }
        $branches = $this->Product->Branch->find('list', array('conditions' => array('Branch.publish' => 1, 'Branch.soft_delete' => 0)));
        $departments = $this->Product->Department->find('list', array('conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0)));
        $this->loadModel('Material');
        $PublishedMaterialList = $this->Material->find('all', array('order'=>array('Material.name'=>'ASC'), 'conditions' => array('Material.id != ' =>$materialList, 'Material.publish' => 1, 'Material.soft_delete' => 0)));
        $productCategories = $this->Product->ProductCategory->find('list',array('conditions'=>array('ProductCategory.publish'=>1,'ProductCategory.soft_delete'=>0)));
        $this->set(compact('branches', 'departments', 'PublishedMaterialList','productCategories'));
    }

    public function product_design() {
        $plans = array('ProductPlan', 'ProductRequirement', 'ProductFeasibility', 'ProductDevelopmentPlan', 'ProductRealisation');
        $this->loadModel('FileUpload');
        foreach ($plans as $plan):
            $cnt = $this->FileUpload->find('count',array(
                'conditions'=>array('FileUpload.system_table_id'=>$this->_get_system_table_id(),
                    'FileUpload.file_status'=>1,'FileUpload.archived'=>0,
                    'FileUpload.record'=>$id,
                    'FileUpload.file_dir LIKE '=> '%'.Inflector::Classify($plan).'%'
                    )));
            $plan =  str_replace(' ', '', $plan);
            $count = $cnt;
            $files[$plan] = $cnt;
            $this->set($files, $cnt);
        endforeach;
    }

    public function product_upload() {

    }

}

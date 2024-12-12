<?php

App::uses('AppController', 'Controller');

/**
 * SoftwareTypes Controller
 *
 * @property SoftwareType $SoftwareType
 */
class SoftwareTypesController extends AppController {

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
        $this->paginate = array('order' => array('SoftwareType.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->SoftwareType->recursive = 0;
        $this->set('softwareTypes', $this->paginate());

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
                        $searchArray[] = array('SoftwareType.' . $search => $searchKey);
                    else
                        $searchArray[] = array('SoftwareType.' . $search . ' like ' => '%' . $searchKey . '%');

                endforeach;
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $searchArray));
            else
                $conditions[] = array('or' => $searchArray);
        }

        if ($this->request->query['branch_list']) {
            foreach ($this->request->query['branch_list'] as $branches):
                $branchConditions[] = array('SoftwareType.branchid' => $branches);
            endforeach;
            if ($this->request->query['strict_search'] == 0)
                $conditions[] = array('and' => array('OR' => $branchConditions));
            else
                $conditions[] = array('or' => $branchConditions);
        }

        if (!$this->request->query['to-date'])
            $this->request->query['to-date'] = date('Y-m-d');
        if ($this->request->query['from-date']) {
            $conditions[] = array('SoftwareType.created >' => date('Y-m-d h:i:s', strtotime($this->request->query['from-date'])), 'SoftwareType.created <' => date('Y-m-d h:i:s', strtotime($this->request->query['to-date'])));
        }
        $conditions =  $this->advance_search_common($conditions);



        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('SoftwareType.branchid' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('SoftwareType.created_by' => $this->Session->read('User.id'));
        $conditions[] = array($onlyBranch, $onlyOwn);

        $this->SoftwareType->recursive = 0;
        $this->paginate = array('order' => array('SoftwareType.sr_no' => 'DESC'), 'conditions' => $conditions, 'SoftwareType.soft_delete' => 0);
        if(isset($_GET['limit']) && $_GET['limit'] != 0){
             $this->paginate = array_merge($this->paginate,array('limit'=>$_GET['limit']));
        }
        $this->set('softwareTypes', $this->paginate());

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
        if (!$this->SoftwareType->exists($id)) {
            throw new NotFoundException(__('Invalid software type'));
        }
        $options = array('conditions' => array('SoftwareType.' . $this->SoftwareType->primaryKey => $id));
        $this->set('softwareType', $this->SoftwareType->find('first', $options));
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
    public function add_ajax($redirect = NULL) {

        $conditions = $this->_check_request();
        $this->paginate = array('order' => array('SoftwareType.sr_no' => 'DESC'), 'conditions' => array($conditions));

        $this->SoftwareType->recursive = 0;
        $this->set('softwareTypes', $this->paginate());

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        if ($this->request->is('post')) {
            $this->request->data['SoftwareType']['system_table_id'] = $this->_get_system_table_id();
            $this->SoftwareType->create();
            if ($this->SoftwareType->save($this->request->data)) {

                $this->Session->setFlash(__('The software type has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                 $redirect = $this->request->data['SoftwareType']['redirect'];
                if ($redirect != '') {
                    unset($this->request->data['SoftwareType']);
                    unset($this->request->data['Approval']);
                } else {
                    if ($this->_show_evidence() == true) {
                        if (isset($this->request->data['SoftwareType']['redirect']) && $this->request->data['SoftwareType']['redirect'] != '') {
                            $this->redirect(array('controller' => $this->request->data['SoftwareType']['redirect'], 'action' => 'add_ajax'));
                        } else {
                            $this->redirect(array('action' => 'view', $this->DataType->id));
                        }
                    } else {
                        if (isset($this->request->data['SoftwareType']['redirect']) && $this->request->data['SoftwareType']['redirect'] != '') {
                            $this->redirect(array('controller' => $this->request->data['SoftwareType']['redirect'], 'action' => 'lists'));
                        } else {
                            $this->redirect(array('action' => 'index'));
                        }
                    }
                }
            } else {
                $this->Session->setFlash(__('The software type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('redirect'));
           $this->index();
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->SoftwareType->exists($id)) {
            throw new NotFoundException(__('Invalid software type'));
        }
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['SoftwareType']['system_table_id'] = $this->_get_system_table_id();
            if ($this->SoftwareType->save($this->request->data)) {

                $this->Session->setFlash(__('The software type has been saved'));

                if ($this->_show_approvals()) $this->_save_approvals ();

                if ($this->_show_evidence() == true)
                    $this->redirect(array('action' => 'view', $id));
                else
                    $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The software type could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('SoftwareType.' . $this->SoftwareType->primaryKey => $id));
            $this->request->data = $this->SoftwareType->find('first', $options);
        }
    }

    /**
     * approve method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function approve($id = null, $approvalId = null) {
        if (!$this->SoftwareType->exists($id)) {
            throw new NotFoundException(__('Invalid software type'));
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
            if ($this->SoftwareType->save($this->request->data)) {

                $this->Session->setFlash(__('The software type has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals ();

            } else {
                $this->Session->setFlash(__('The software type could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('SoftwareType.' . $this->SoftwareType->primaryKey => $id));
            $this->request->data = $this->SoftwareType->find('first', $options);
        }
    }
}

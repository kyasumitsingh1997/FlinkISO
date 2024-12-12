<?php
App::uses('AppController', 'Controller');
/**
 * EnvironmentChecklistAnswers Controller
 *
 * @property EnvironmentChecklistAnswer $EnvironmentChecklistAnswer
 * @property PaginatorComponent $Paginator
 */
class EnvironmentChecklistAnswersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->EnvironmentChecklistAnswer->recursive = 0;
		$this->set('environmentChecklistAnswers', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->EnvironmentChecklistAnswer->exists($id)) {
			throw new NotFoundException(__('Invalid environment checklist answer'));
		}
		$options = array('conditions' => array('EnvironmentChecklistAnswer.' . $this->EnvironmentChecklistAnswer->primaryKey => $id));
		$this->set('environmentChecklistAnswer', $this->EnvironmentChecklistAnswer->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->EnvironmentChecklistAnswer->create();
			if ($this->EnvironmentChecklistAnswer->save($this->request->data)) {
				$this->Session->setFlash(__('The environment checklist answer has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The environment checklist answer could not be saved. Please, try again.'));
			}
		}
		$environmentChecklists = $this->EnvironmentChecklistAnswer->EnvironmentChecklist->find('list');
		$environmentQuestionnaires = $this->EnvironmentChecklistAnswer->EnvironmentQuestionnaire->find('list');
		$environmentQuestionnaireCategories = $this->EnvironmentChecklistAnswer->EnvironmentQuestionnaireCategory->find('list');
		$systemTables = $this->EnvironmentChecklistAnswer->SystemTable->find('list');
		$masterListOfFormats = $this->EnvironmentChecklistAnswer->MasterListOfFormat->find('list');
		$companies = $this->EnvironmentChecklistAnswer->Company->find('list');
		$createdBies = $this->EnvironmentChecklistAnswer->CreatedBy->find('list');
		$modifiedBies = $this->EnvironmentChecklistAnswer->ModifiedBy->find('list');
		$this->set(compact('environmentChecklists', 'environmentQuestionnaires', 'environmentQuestionnaireCategories', 'systemTables', 'masterListOfFormats', 'companies', 'createdBies', 'modifiedBies'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->EnvironmentChecklistAnswer->exists($id)) {
			throw new NotFoundException(__('Invalid environment checklist answer'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->EnvironmentChecklistAnswer->save($this->request->data)) {
				$this->Session->setFlash(__('The environment checklist answer has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The environment checklist answer could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('EnvironmentChecklistAnswer.' . $this->EnvironmentChecklistAnswer->primaryKey => $id));
			$this->request->data = $this->EnvironmentChecklistAnswer->find('first', $options);
		}
		$environmentChecklists = $this->EnvironmentChecklistAnswer->EnvironmentChecklist->find('list');
		$environmentQuestionnaires = $this->EnvironmentChecklistAnswer->EnvironmentQuestionnaire->find('list');
		$environmentQuestionnaireCategories = $this->EnvironmentChecklistAnswer->EnvironmentQuestionnaireCategory->find('list');
		$systemTables = $this->EnvironmentChecklistAnswer->SystemTable->find('list');
		$masterListOfFormats = $this->EnvironmentChecklistAnswer->MasterListOfFormat->find('list');
		$companies = $this->EnvironmentChecklistAnswer->Company->find('list');
		$createdBies = $this->EnvironmentChecklistAnswer->CreatedBy->find('list');
		$modifiedBies = $this->EnvironmentChecklistAnswer->ModifiedBy->find('list');
		$this->set(compact('environmentChecklists', 'environmentQuestionnaires', 'environmentQuestionnaireCategories', 'systemTables', 'masterListOfFormats', 'companies', 'createdBies', 'modifiedBies'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->EnvironmentChecklistAnswer->id = $id;
		if (!$this->EnvironmentChecklistAnswer->exists()) {
			throw new NotFoundException(__('Invalid environment checklist answer'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->EnvironmentChecklistAnswer->delete()) {
			$this->Session->setFlash(__('The environment checklist answer has been deleted.'));
		} else {
			$this->Session->setFlash(__('The environment checklist answer could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}

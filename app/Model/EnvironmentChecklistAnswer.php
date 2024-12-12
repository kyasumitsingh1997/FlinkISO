<?php
App::uses('AppModel', 'Model');
/**
 * EnvironmentChecklistAnswer Model
 *
 * @property EnvironmentChecklist $EnvironmentChecklist
 * @property EnvironmentQuestionnaire $EnvironmentQuestionnaire
 * @property EnvironmentQuestionnaireCategory $EnvironmentQuestionnaireCategory
 * @property StatusUser $StatusUser
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property Company $Company
 */
class EnvironmentChecklistAnswer extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'sr_no' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'environment_checklist_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'environment_questionnaire_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'environment_questionnaire_category_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'EnvironmentChecklist' => array(
			'className' => 'EnvironmentChecklist',
			'foreignKey' => 'environment_checklist_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EnvironmentQuestionnaire' => array(
			'className' => 'EnvironmentQuestionnaire',
			'foreignKey' => 'environment_questionnaire_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'EnvironmentQuestionnaireCategory' => array(
			'className' => 'EnvironmentQuestionnaireCategory',
			'foreignKey' => 'environment_questionnaire_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'SystemTable' => array(
			'className' => 'SystemTable',
			'foreignKey' => 'system_table_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'MasterListOfFormat' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'master_list_of_format_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

<?php
App::uses('AppModel', 'Model');
/**
 * RiskAssessment Model
 *
 * @property Process $Process
 * @property Branch $Branch
 * @property HazardType $HazardType
 * @property HazardSource $HazardSource
 * @property AccidentType $AccidentType
 * @property SeveriryType $SeveriryType
 * @property RiskRating $RiskRating
 * @property StatusUser $StatusUser
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property Company $Company
 */
class RiskAssessment extends AppModel {

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
           'title' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'allowEmpty' => false,
				'required' => true,
			),
		),
            'task' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'allowEmpty' => false,
				'required' => true,
			),
		),
            'ra_date' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'allowEmpty' => false,
				'required' => true,
			),
		),
            'reference_number' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'allowEmpty' => false,
				'required' => true,
			),
		),
		'branchid' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'departmentid' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'created_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
		'modified_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Process' => array(
			'className' => 'Process',
			'foreignKey' => 'process_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'Branch' => array(
			'className' => 'Branch',
			'foreignKey' => 'branch_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'HazardType' => array(
			'className' => 'HazardType',
			'foreignKey' => 'hazard_type_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'InjuryType' => array(
			'className' => 'InjuryType',
			'foreignKey' => 'injury_type_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'HazardSource' => array(
			'className' => 'HazardSource',
			'foreignKey' => 'hazard_source_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'AccidentType' => array(
			'className' => 'AccidentType',
			'foreignKey' => 'accident_type_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'SeveriryType' => array(
			'className' => 'SeveriryType',
			'foreignKey' => 'severiry_type_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'RiskRating' => array(
			'className' => 'RiskRating',
			'foreignKey' => 'risk_rating_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'SystemTable' => array(
			'className' => 'SystemTable',
			'foreignKey' => 'system_table_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'MasterListOfFormat' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'master_list_of_format_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'BranchIds' => array(
			'className' => 'Branch',
			'foreignKey' => 'branchid',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'DepartmentIds' => array(
			'className' => 'Department',
			'foreignKey' => 'departmentid',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'PreparedBy' => array(
			'className' => 'Employee',
			'foreignKey' => 'prepared_by',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'ApprovedBy' => array(
			'className' => 'Employee',
			'foreignKey' => 'approved_by',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Incident' => array(
			'className' => 'Incident',
			'foreignKey' => 'risk_assessment_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	public $report = array(
    	'Risk_Sources' => array(
	    		'Process' => array('model'=>'Process','key_field'=>'process_id'),
	    		'Injury' =>array('model'=>'InjuryType','key_field'=>'injury_type_id'),
	    		'Hazards' =>array('model'=>'HazardType','key_field'=>'hazard_source_id'),
	    		'Accedent' =>array('model'=>'AccidentType','key_field'=>'accident_type_id'),
	    		'SeveriryType' =>array('model'=>'SeveriryType','key_field'=>'severiry_type_id'),
    		)
    	);

}

<?php
App::uses('AppModel', 'Model');
/**
 * InternalAuditPlan Model
 *
 * @property ListOfTrainedInternalAuditor $ListOfTrainedInternalAuditor
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property InternalAuditPlanBranch $InternalAuditPlanBranch
 * @property InternalAuditPlanDepartment $InternalAuditPlanDepartment
 * @property InternalAudit $InternalAudit
 */
class InternalAuditPlan extends AppModel {

	public $actsAs = array('Containable');
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'sr_no' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'plan_type' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'title' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'schedule_date_from' => array(
			'datetime' => array(
				'rule' => array('datetime'),
			),
		),
		'schedule_date_to' => array(
			'datetime' => array(
				'rule' => array('datetime'),
			),
		),
		'branchid' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'departmentid' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'created_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'modified_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
	);
/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
	    'ApprovedBy' => array(
	            'className' => 'Employee',
	            'foreignKey' => 'approved_by',
	            'conditions' => '',
	            'fields' => array('id', 'name'),
	            'order' => '' ),
	    'PreparedBy' => array(
	            'className' => 'Employee',
	            'foreignKey' => 'prepared_by',
	            'conditions' => '',
	            'fields' => array('id', 'name'),
	            'order' => ''
		),'Standard' => array(
			'className' => 'Standard',
			'foreignKey' => 'standard_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),'ListOfTrainedInternalAuditor' => array(
			'className' => 'ListOfTrainedInternalAuditor',
			'foreignKey' => 'list_of_trained_internal_auditor_id',
			'conditions' => '',
			'fields' => array('id', 'employee_id'),
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
			'fields' => array('id', 'title', 'system_table_id'),
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
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'StatusUserId' => array(
			'className' => 'User',
			'foreignKey' => 'status_user_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'AuditTypeMaster' => array(
			'className' => 'AuditTypeMaster',
			'foreignKey' => 'audit_type_master_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Process' => array(
			'className' => 'Process',
			'foreignKey' => 'process_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'RiskAssessment' => array(
			'className' => 'RiskAssessment',
			'foreignKey' => 'risk_assessment_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'InternalAuditPlanBranch' => array(
			'className' => 'InternalAuditPlanBranch',
			'foreignKey' => 'internal_audit_plan_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'InternalAudit' => array(
			'className' => 'InternalAudit',
			'foreignKey' => 'internal_audit_plan_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'InternalAuditPlanDepartment' => array(
			'className' => 'InternalAuditPlanDepartment',
			'foreignKey' => 'internal_audit_plan_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
	);
}

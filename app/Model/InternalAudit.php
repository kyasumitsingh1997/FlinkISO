<?php
App::uses('AppModel', 'Model');
/**
 * InternalAudit Model
 *
 * @property InternalAuditPlan $InternalAuditPlan
 * @property InternalAuditPlanDepartment $InternalAuditPlanDepartment
 * @property Department $Department
 * @property Branch $Branch
 * @property ListOfTrainedInternalAuditor $ListOfTrainedInternalAuditor
 * @property Employee $Employee
 * @property CorrectivePreventiveAction $CorrectivePreventiveAction
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property CorrectivePreventiveAction $CorrectivePreventiveAction
 * @property InternalAuditDetail $InternalAuditDetail
 */
class InternalAudit extends AppModel {
 public $displayField = "question_asked";
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
		'internal_audit_plan_department_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'department_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'branch_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'section' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'start_time' => array(
			'datetime' => array(
				'rule' => array('datetime'),
			),
		),
		'end_time' => array(
			'datetime' => array(
				'rule' => array('datetime'),
			),
		),
		'list_of_trained_internal_auditor_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'employee_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'clauses' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'question_asked' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'finding' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		// 'non_conformity_found' => array(
		// 	'boolean' => array(
		// 		'rule' => array('boolean'),
		// 	),
		// ),
		// 'current_status' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 	),
		// ),
		// 'employeeId' => array(
		// 	'uuid' => array(
		// 		'rule' => array('uuid'),
		// 	),
		// ),
		// 'target_date' => array(
		// 	'date' => array(
		// 		'rule' => array('date'),
		// 	),
		// ),
		// 'notes' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 	),
		// ),
		
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
        ),'InternalAuditPlan' => array(
			'className' => 'InternalAuditPlan',
			'foreignKey' => 'internal_audit_plan_id',
			'conditions' => '',
			'fields' => array('id', 'title','schedule_date_from','schedule_date_to'),
			'order' => ''
		),
		'InternalAuditPlanDepartment' => array(
			'className' => 'InternalAuditPlanDepartment',
			'foreignKey' => 'internal_audit_plan_department_id',
			'conditions' => '',
			'fields' => array('id'),
			'order' => ''
		),
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'department_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Branch' => array(
			'className' => 'Branch',
			'foreignKey' => 'branch_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'ListOfTrainedInternalAuditor' => array(
			'className' => 'ListOfTrainedInternalAuditor',
			'foreignKey' => 'list_of_trained_internal_auditor_id',
			'conditions' => '',
			'fields' => array('id', 'employee_id'),
			'order' => ''
		),
		'EmployeeId' => array(
			'className' => 'Employee',
			'foreignKey' => 'employeeId',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		)
		,
		'Employee' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'CorrectivePreventiveAction' => array(
			'className' => 'CorrectivePreventiveAction',
			'foreignKey' => 'corrective_preventive_action_id',
			'conditions' => '',
			'fields' => array('id', 'name', 'capa_source_id', 'current_status'),
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
}

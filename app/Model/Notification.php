<?php
App::uses('AppModel', 'Model');
/**
 * Notification Model
 *
 * @property NotificationType $NotificationType
 * @property SystemTable $SystemTable
 * @property NotificationUser $NotificationUser
 */
class Notification extends AppModel {

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
		'notification_type_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'title' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'message' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'start_date' => array(
			'date' => array(
				'rule' => array('date'),
			),
		),
		'end_date' => array(
			'date' => array(
				'rule' => array('date'),
			),
		),
		'prepared_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'approved_by' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
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
		'NotificationType' => array(
			'className' => 'NotificationType',
			'foreignKey' => 'notification_type_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
                'Meeting' => array(
			'className' => 'Meeting',
			'foreignKey' => 'meeting_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
                'InternalAuditPlan' => array(
			'className' => 'InternalAuditPlan',
			'foreignKey' => 'internal_audit_plan_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'SystemTable' => array(
			'className' => 'SystemTable',
			'foreignKey' => 'system_table_id',
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
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'NotificationUser' => array(
			'className' => 'NotificationUser',
			'foreignKey' => 'notification_id',
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

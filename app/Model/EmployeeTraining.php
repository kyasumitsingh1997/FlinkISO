<?php
App::uses('AppModel', 'Model');
/**
 * EmployeeTraining Model
 *
 * @property Employee $Employee
 * @property Training $Training
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 */
class EmployeeTraining extends AppModel {

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
		'employee_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'training_id' => array(
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
                    ),
		'Employee' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Training' => array(
			'className' => 'Training',
			'foreignKey' => 'training_id',
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
		)
	);
}
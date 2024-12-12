<?php
App::uses('AppModel', 'Model');
/**
 * MasterListOfFormatDepartment Model
 *
 * @property MasterListOfFormat $MasterListOfFormat
 * @property Department $Department
 * @property SystemTable $SystemTable
 */
class MasterListOfFormatDepartment extends AppModel {

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
		'master_list_of_format_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'department_id' => array(
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
		'MasterListOfFormat' => array(
			'className' => 'MasterListOfFormat',
			'foreignKey' => 'master_list_of_format_id',
			'conditions' => '',
			'fields' => array('id', 'title', 'system_table_id', 'document_number', 'issue_number', 'revision_number', 'revision_date', 'prepared_by', 'approved_by'),
			'order' => ''
		),
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'department_id',
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
			'foreignKey' => 'Company_id',
			'conditions' => '',
			'fields' => array('id','name'),
			'order' => ''
                ),
                'StatusUserId' => array(
                    'className' => 'User',
                    'foreignKey' => 'status_user_id',
                    'conditions' => '',
                    'fields' => array('id'),
                    'order' => ''
                ),
                'ApprovedBy' => array(
                    'className' => 'Employee',
                    'foreignKey' => 'approved_by',
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
        );

}

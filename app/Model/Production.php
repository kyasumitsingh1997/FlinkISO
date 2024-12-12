<?php
App::uses('AppModel', 'Model');


class Production extends AppModel {
	var $name = 'Production';
	var $displayField = 'batch_number';
	var $validate = array(
		'sr_no' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'product_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'production_weekly_plan_id' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'batch_number' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
				'message' => 'Prodction batch no cant be empty',
				'allowEmpty' => false,
				'required' => true,
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'Prodction batch no exists, batch no has to be unique',
				'allowEmpty' => false,
				'required' => true,
			)
		)
		,
		'production_date' => array(
			'date' => array(
				'rule' => array('notBlank'),
			),
		),
		// 'week' => array(
		// 	'notBlank' => array(
		// 		'rule' => array('notBlank'),
		// 	),
		// ),
		'production_planned' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),'numeric' => array(
				'rule' => array('numeric'),
			),
		),'actual_production_number' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => array('Number Only'),
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
        ),'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),'ProductionCategory' => array(
			'className' => 'ProductionCategory',
			'foreignKey' => 'production_category_id',
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
		'Employee' => array(
			'className' => 'Employee',
			'foreignKey' => 'employee_id',
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
			'fields' => array('id', 'title', 'system_table_id'),
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
		'ProductionWeeklyPlan' => array(
			'className' => 'ProductionWeeklyPlan',
			'foreignKey' => 'production_weekly_plan_id',
			'conditions' => '',
			'fields' => array('id', 'week','name', 'production_planned','product_id'),
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Stock' => array(
			'className' => 'Stock',
			'foreignKey' => 'production_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'ProductionRejection' => array(
			'className' => 'ProductionRejection',
			'foreignKey' => 'production_id',
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

	public $customArray = array('current_status' => array(
			'0' => 'Under Process',
			'1' => 'Completed',
			'2' => 'Cancled',
	  	    )
	);

}


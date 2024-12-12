<?php
App::uses('AppModel', 'Model');
/**
 * Product Model
 *
 * @property Branch $Branch
 * @property Department $Department
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property CorrectivePreventiveAction $CorrectivePreventiveAction
 * @property CustomerComplaint $CustomerComplaint
 * @property DeliveryChallanDetail $DeliveryChallanDetail
 * @property OrderDetailsForm $OrderDetailsForm
 * @property PurchaseOrderDetail $PurchaseOrderDetail
 * @property SupplierEvaluationReevaluation $SupplierEvaluationReevaluation
 */
class Product extends AppModel {

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
		'name' => array(
			'notBlank' => array(
				'rule' => array('notBlank'),
			),
		),
		'description' => array(
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
		),'Branch' => array(
			'className' => 'Branch',
			'foreignKey' => 'branch_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),'ProductCategory' => array(
			'className' => 'ProductCategory',
			'foreignKey' => 'product_category_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
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

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ProductMaterial' => array(
			'className' => 'ProductMaterial',
			'foreignKey' => 'product_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => array('product_id','material_id','quantity'),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'NonConformingProductsMaterial' => array(
			'className' => 'NonConformingProductsMaterial',
			'foreignKey' => 'product_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array(),
			'order' => array('NonConformingProductsMaterial.non_confirmity_date'=>'DESC'),
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Production' => array(
			'className' => 'Production',
			'foreignKey' => 'product_id',
			'dependent' => true,
			'conditions' => array('Production.sr_no'=> 0),
			'fields' => array(),
			'order' => array(),
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'ProductionWeeklyPlan' => array(
			'className' => 'ProductionWeeklyPlan',
			'foreignKey' => 'product_id',
			'dependent' => true,
			'conditions' => array('ProductionWeeklyPlan.week'=> 0),
			'fields' => array(),
			'order' => array(),
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
}

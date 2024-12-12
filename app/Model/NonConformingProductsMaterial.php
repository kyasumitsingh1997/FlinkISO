<?php
App::uses('AppModel', 'Model');
/**
 * NonConformingProductsMaterial Model
 *
 * @property Material $Material
 * @property Product $Product
 * @property CapaSource $CapaSource
 * @property CorrectivePreventiveAction $CorrectivePreventiveAction
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property Company $Company
 */
class NonConformingProductsMaterial extends AppModel {

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
		'title' => array(
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
	            'order' => '' 
		),'PreparedBy' => array(
	            'className' => 'Employee',
	            'foreignKey' => 'prepared_by',
	            'conditions' => '',
	            'fields' => array('id', 'name'),
	            'order' => ''
		),'Material' => array(
			'className' => 'Material',
			'foreignKey' => 'material_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Procedure' => array(
			'className' => 'Procedure',
			'foreignKey' => 'procedure_id',
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
			'foreignKey' => 'risk_Assessment_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'ReportedBy' => array(
			'className' => 'Employee',
			'foreignKey' => 'reported_by',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'CapaSource' => array(
			'className' => 'CapaSource',
			'foreignKey' => 'capa_source_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'CorrectivePreventiveAction' => array(
			'className' => 'CorrectivePreventiveAction',
			'foreignKey' => 'corrective_preventive_action_id',
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
		'StatusUserId' => array(
			'className' => 'User',
			'foreignKey' => 'status_user_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'InternalAudit' => array(
			'className' => 'InternalAudit',
			'foreignKey' => 'internal_audit_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $report = array(
    	'CAPA_Category' => array(
	    		'Product_Wise' => array('model'=>'Product','key_field'=>'product_id'),
	    		'Material_Wise' => array('model'=>'Material','key_field'=>'material_id'),
	    		'Procedure' =>array('model'=>'Procedure','key_field'=>'procedure_id'),
	    		'Peocess' =>array('model'=>'Process','key_field'=>'process_id'),
	    		'CAPAs' =>array('model'=>'CorrectivePreventiveAction','key_field'=>'corrective_preventive_action_id'),
	    		'ReportedBy' => array('model'=>'Employee','key_field'=>'reported_by'),
	    		'CAPA_Sources' => array('model'=>'CapaSource','key_field'=>'capa_source_id'),
    		),
    	'CAPA_Source'=>array(
    			'CAPA_Source' => array('model'=>'CapaSource','key_field'=>'capa_source_id')
    		)
    	); 
}

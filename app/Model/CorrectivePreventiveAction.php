<?php
App::uses('AppModel', 'Model');
/**
 * CorrectivePreventiveAction Model
 *
 * @property CapaSource $CapaSource
 * @property CapaCategory $CapaCategory
 * @property InternalAudit $InternalAudit
 * @property SuggestionForm $SuggestionForm
 * @property CustomerComplaint $CustomerComplaint
 * @property SupplierRegistration $SupplierRegistration
 * @property Product $Product
 * @property Device $Device
 * @property Material $Material
 * @property SystemTable $SystemTable
 * @property MasterListOfFormat $MasterListOfFormat
 * @property Company $Company
 * @property InternalAudit $InternalAudit
 * @property MeetingTopic $MeetingTopic
 * @property NonConformingProductsMaterial $NonConformingProductsMaterial
 */
class CorrectivePreventiveAction extends AppModel {

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
		'capa_category_id' => array(
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
        ),'CapaSource' => array(
			'className' => 'CapaSource',
			'foreignKey' => 'capa_source_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'CapaCategory' => array(
			'className' => 'CapaCategory',
			'foreignKey' => 'capa_category_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'CapaRating' => array(
			'className' => 'CapaRating',
			'foreignKey' => 'capa_rating_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'InternalAudit' => array(
			'className' => 'InternalAudit',
			'foreignKey' => 'internal_audit_id',
			'conditions' => '',
			'fields' => array('id', 'internal_audit_plan_id', 'start_time'),
			'order' => ''
		),
		'SuggestionForm' => array(
			'className' => 'SuggestionForm',
			'foreignKey' => 'suggestion_form_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'CustomerComplaint' => array(
			'className' => 'CustomerComplaint',
			'foreignKey' => 'customer_complaint_id',
			'conditions' => '',
			'fields' => array('id', 'customer_id', 'complaint_number', 'name'),
			'order' => ''
		),
		'SupplierRegistration' => array(
			'className' => 'SupplierRegistration',
			'foreignKey' => 'supplier_registration_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Device' => array(
			'className' => 'Device',
			'foreignKey' => 'device_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'Material' => array(
			'className' => 'Material',
			'foreignKey' => 'material_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'EnvActivity' => array(
			'className' => 'EnvActivity',
			'foreignKey' => 'env_activity_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'EnvIdentification' => array(
			'className' => 'EnvIdentification',
			'foreignKey' => 'env_identification_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'Project' => array(
			'className' => 'Project',
			'foreignKey' => 'project_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
			'order' => ''
		),
		'ProjectActivity' => array(
			'className' => 'ProjectActivity',
			'foreignKey' => 'project_activity_id',
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
		'Procedure' => array(
			'className' => 'Procedure',
			'foreignKey' => 'procedure_id',
			'conditions' => '',
			'fields' => array('id', 'name'),
			'order' => ''
		),
		'NonConformingProductsMaterial' => array(
			'className' => 'NonConformingProductsMaterial',
			'foreignKey' => 'non_conforming_products_material_id',
			'conditions' => '',
			'fields' => array('id', 'title'),
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
		),
		'Task' => array(
			'className' => 'Task',
			'foreignKey' => 'task_id',
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
             
    );

	public $customArray = array(
		    'capa_type' => array(
			'0' => 'Corrective Action',
			'1' => 'Preventive Action',
			'2' => 'Corrective and Preventive Action'
		    ),

	    'current_status' => array(
			'0' => 'Open',
			'1' => 'Close'
		),

	     'priority' => array(
			'0' => 'Low',
			'1' => 'Medium',
			'2' => 'High'
		),

	     'root_cause_analysis_required' => array(
			'0' => 'No',
			'1' => 'Yes'

		)

	    );

        public $mergeFields = array(
                'capa_category_id' => array(
                    'Material' => array('material_id', 'Material'),
                    'Non Conformity from Audit' => array('internal_audit_id', 'Internal Audit'),
                    'Suggestion for improvement' => array('suggestion_form_id', 'Suggestion Form'),
                    'Suppliers' => array('supplier_registration_id', 'Supplier Registration'),
                    'Product' => array('product_id', 'Product'),
                    'Device' => array('device_id', 'Device'),
                    'Complaints' => array('customer_complaint_id', 'Customer Complaint'),
                    'Notices External Parties' => array(),
                )
        );

        public $hasMany = array(
            'CapaInvestigation' => array(
			'className' => 'CapaInvestigation',
			'foreignKey' => 'corrective_preventive_action_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'CapaRootCauseAnalysi' => array(
			'className' => 'CapaRootCauseAnalysi',
			'foreignKey' => 'corrective_preventive_action_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),'CapaRevisedDate' => array(
			'className' => 'CapaRevisedDate',
			'foreignKey' => 'corrective_preventive_action_id',
			'dependent' => true,
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
    	'CAPA_Category' => array(    			
	    		'Product_Wise' => array('model'=>'Product','key_field'=>'product_id'),
	    		'Material_Wise' => array('model'=>'Material','key_field'=>'material_id'),
	    		'NCs' =>array('model'=>'NonConformingProductsMaterial','key_field'=>'non_conforming_products_material_id'),
	    		'SuggestionForm' => array('model'=>'SuggestionForm','key_field'=>'suggestion_form_id'),
	    		'Supplier_Wise' => array('model'=>'SupplierRegistration','key_field'=>'supplier_registration_id'),
	    		'Device_Wise' => array('model'=>'Device','key_field'=>'device_id'),
	    		'Complaint_Wise' => array('model'=>'CustomerComplaint','key_field'=>'customer_complaint_id'),
	    		'Activiy_Type' => array('model'=>'EnvActivity','key_field'=>'env_activity_id'),
	    		'Identification_Type' => array('model'=>'EnvIdentification','key_field'=>'env_identification_id'),
	    		'Process_Wise' => array('model'=>'Process','key_field'=>'process_id'),
	    		'Risk_Wise' => array('model'=>'RiskAssessment','key_field'=>'risk_assessment_id'),
    		),
    	'CAPA_Source'=>array(
    			'CAPA_Source' => array('model'=>'CapaSource','key_field'=>'capa_source_id')
    		),
    	'CAPA_Type' => array(
    		'Type' => array('model'=>'CorrectivePreventiveAction','key_field'=>'capa_type'),
    		)
    	);    

}

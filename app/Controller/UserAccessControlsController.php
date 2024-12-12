<?php
App::uses('AppController', 'Controller');
/**
 * UserAccessControls Controller
 *
 * @property UserAccessControl $UserAccessControl
 * @property PaginatorComponent $Paginator
 */
class UserAccessControlsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator','Ctrl');
	// public $components = array('Ctrl');

public function _get_system_table_id() {
        $this->loadModel('SystemTable');
        $this->SystemTable->recursive = -1;
        $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));
        return $systemTableId['SystemTable']['id'];
    }




/**
 * index method
 *
 * @return void
 */
	public function index() {
		
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('UserAccessControl.sr_no'=>'DESC'),'conditions'=>array($conditions));
	
		$this->UserAccessControl->recursive = 0;
		$this->set('userAccessControls', $this->paginate());
		
		$this->_get_count();
	}


 
/**
 * box layout by - TGS
 * box method
 *
 * @return void
 */
	public function box() {
	
		$conditions = $this->_check_request();
		$this->paginate = array('order'=>array('UserAccessControl.sr_no'=>'DESC'),'conditions'=>array($conditions));
		
		$this->UserAccessControl->recursive = 0;
		$this->set('userAccessControls', $this->paginate());
		
		$this->_get_count();
	}

/**
 * search method
 * Dynamic by - TGS
 * @return void
 */
	public function search() {
		if ($this->request->is('post')) {
	
	$search_array = array();
		$search_keys = explode(" ",$this->request->data['UserAccessControl']['search']);
	
		foreach($search_keys as $search_key):
			foreach($this->request->data['UserAccessControl']['search_field'] as $search):
				$search_array[] = array('UserAccessControl.'.$search .' like' => '%'.$search_key.'%');
			endforeach;
		endforeach;
		
		if($this->Session->read('User.is_mr') == 0)
			{
				$cons = array('UserAccessControl.branch_id'=>$this->Session->read('User.branch_id'));
			}
		
		$this->UserAccessControl->recursive = 0;
		$this->paginate = array('order'=>array('UserAccessControl.sr_no'=>'DESC'),'conditions'=>array('or'=>$search_array , 'UserAccessControl.soft_delete'=>0 , $cons));
		$this->set('userAccessControls', $this->paginate());
		}
                $this->render('index');
	}

/**
 * adcanced_search method
 * Advanced search by - TGS
 * @return void
 */
	public function advanced_search() {
		if ($this->request->is('post')) {
		$conditions = array();
			if($this->request->query['keywords']){
				$search_array = array();
				$search_keys = explode(" ",$this->request->query['keywords']);
	
				foreach($search_keys as $search_key):
					foreach($this->request->query['search_fields'] as $search):
					if($this->request->query['strict_search'] == 0)$search_array[] = array('UserAccessControl.'.$search => $search_key);
					else $search_array[] = array('UserAccessControl.'.$search.' like ' => '%'.$search_key.'%');
						
					endforeach;
				endforeach;
				if($this->request->query['strict_search']==0)$conditions[] = array('and'=>$search_array);
				else $conditions[] = array('or'=>$search_array);
			}
			
		if($this->request->query['branch_list']){
			foreach($this->request->query['branch_list'] as $branches):
				$branch_conditions[]=array('UserAccessControl.branch_id'=>$branches);
			endforeach;
			$conditions[]=array('or'=>$branch_conditions);
		}
		
		if(!$this->request->query['to-date'])$this->request->query['to-date'] = date('Y-m-d');
		if($this->request->query['from-date']){
			$conditions[] = array('UserAccessControl.created >'=>date('Y-m-d h:i:s',strtotime($this->request->query['from-date'])),'UserAccessControl.created <'=>date('Y-m-d h:i:s',strtotime($this->request->query['to-date'])));
		}
		unset($this->request->query);
		
		
		if($this->Session->read('User.is_mr') == 0)$onlyBranch = array('UserAccessControl.branch_id'=>$this->Session->read('User.branch_id'));
		if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array('UserAccessControl.created_by'=>$this->Session->read('User.id'));
		$conditions[] = array($onlyBranch,$onlyOwn);
		
		$this->UserAccessControl->recursive = 0;
		$this->paginate = array('order'=>array('UserAccessControl.sr_no'=>'DESC'),'conditions'=>$conditions , 'UserAccessControl.soft_delete'=>0 );
		$this->set('userAccessControls', $this->paginate());
		}
                $this->render('index');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->UserAccessControl->exists($id)) {
			throw new NotFoundException(__('Invalid user access control'));
		}
		$options = array('conditions' => array('UserAccessControl.' . $this->UserAccessControl->primaryKey => $id));
		$this->set('userAccessControl', $this->UserAccessControl->find('first', $options));
	}



/**
 * list method
 *
 * @return void
 */
	public function lists() {
	
        $this->_get_count();		

	}


/**
 * add_ajax method
 *
 * @return void
 */
	public function add_ajax() {

		ini_set('memory_limit', -1);

        // $this->User->recursive = 0;
        // if (!$this->User->exists($id)) {
        //     throw new NotFoundException(__('Invalid user'));
        // }
        
        $allControllers                         = $this->Ctrl->get();
        $otherControllers                       = array();

        // $otherControllers['Documents']                 = array(
        //     'evidences',
        //     'file_uploads'
        // );
        $otherControllers['MR']                 = array(
            'change_addition_deletion_requests',
            'document_amendment_record_sheets',
            'master_list_of_formats',
            'meetings',
            'internal_audits',
            'internal_audit_plans',
            'corrective_preventive_actions',
            'capa_investigations',
            'capa_root_cause_analysis',
            'capa_revised_dates',
            'capa_categories',
            'capa_sources',
            'tasks',
            'benchmarks'
        );
        $otherControllers['HR']                 = array(
            'training_need_identifications',
            'courses',
            'course_types',
            'training_evaluations',
            'competency_mappings',
            'trainings',
            'trainers',
            'trainer_types',
            'appraisals',
            'appraisal_questions'
        );
        $otherControllers['BD']                 = array(
            'customer_meetings',
            'proposals',
            'proposal_followups'
        );
        $otherControllers['Purchase']           = array(
            'supplier_registrations',
            'supplier_categories',
            'list_of_acceptable_suppliers',
            'supplier_evaluation_reevaluations',
            'summery_of_supplier_evaluations',
            'delivery_challans',
            'purchase_orders',
            'invoices',
            'invoice_details'
        );
        $otherControllers['Admin']              = array(
            'fire_extinguishers',
            'housekeeping_checklists',
            'housekeeping_responsibilities',
            'fire_extinguisher_types'
        );
        $otherControllers['Quality Control']    = array(
            'customer_complaints',
            'list_of_measuring_devices_for_calibrations',
            'calibrations',
            'customer_feedbacks',
            'customer_feedback_questions',
            'material_quality_checks',
            'device_maintenances'
        );
        $otherControllers['EDP']                = array(
            'username_password_details',
            'list_of_computers',
            'list_of_softwares',
            'list_of_computer_list_of_softwares',
            'databackup_logbooks',
            'daily_backup_details',
            'data_back_ups'
        );
        $otherControllers['Production']         = array(
            'materials',
            'productions',
            'stocks'
        );
        $otherControllers['Data Entry']         = array(
            'branches',
            'departments',
            'designations',
            'employees',
            'users',
            'products',
            'devices',
            'customers',
            'software_types',
            'training_types'
        );
        $otherControllers['Incident Reporting'] = array(
            'risk_assessments',
            'incidents',
            'incident_investigations',
            'incident_affected_personals',
            'incident_classifications',
            'incident_investigators',
            'incident_witnesses'
        );
        $otherControllers['Objectives']         = array(
            'objectives',
            'objective_monitorings',
            'processes',
            'process_teams'
        );
        $otherControllers['Settings']           = array(
            'auto_approvals',
            'system_tables',
            'companies'
        );
        $otherControllers['FMEA']           = array(
            'fmeas',
            'fmea_actions',
            'fmea_severity_types',
            'fmea_occurences',
            'fmea_detections'
        );
        
        $this->loadModel('MasterListOfFormatDepartment');
        foreach ($otherControllers as $key => $controllers):
            foreach ($controllers as $controller):
                $getActions = Inflector::camelize($controller) . 'Controller';
                if (isset($allControllers[$getActions]) && (!in_array("delete", $allControllers[$getActions]))) {
                    $allControllers[$getActions][] = 'delete';
                }
                $deptWise[$key][$controller]['actions'] = $allControllers[$getActions];
            endforeach;
        endforeach;
        
        $this->set('forms', $deptWise);
        
            
	
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
		
		if ($this->request->is('post')) {

			$dashboard['mr']                                         = 1;
            $dashboard['hr']                                         = 1;
            $dashboard['bd']                                         = 1;
            $dashboard['production']                                 = 1;
            $dashboard['personal_admin']                             = 1;
            $dashboard['quality_control']                            = 1;
            $dashboard['edp']                                        = 1;
            $dashboard['purchase']                                   = 1;
            $dashboard['raicm']                                      = 1;
            $this->request->data['ACL']['user_access']['dashboards'] = $dashboard;
            
            $error['error500']                                   = 1;
            $error['error404']                                   = 1;
            $this->request->data['ACL']['user_access']['errors'] = $error;
            
            $help['view']                                       = 1;
            $help['edit']                                       = 1;
            $help['help']                                       = 1;
            $this->request->data['ACL']['user_access']['helps'] = $help;
            
            $MessageUserSents['index']                                       = 1;
            $MessageUserSents['view']                                        = 1;
            $MessageUserSents['add']                                         = 1;
            $MessageUserSents['edit']                                        = 1;
            $MessageUserSents['delete']                                      = 1;
            $MessageUserSents['delete_all']                                  = 1;
            $this->request->data['ACL']['user_access']['message_user_sents'] = $MessageUserSents;
            
            $MessageUserThrashes['index']                                       = 1;
            $MessageUserThrashes['view']                                        = 1;
            $MessageUserThrashes['add']                                         = 1;
            $MessageUserThrashes['edit']                                        = 1;
            $MessageUserThrashes['delete']                                      = 1;
            $MessageUserThrashes['delete_all']                                  = 1;
            $this->request->data['ACL']['user_access']['message_user_thrashes'] = $MessageUserThrashes;
            
            
            $Messages['inbox']                                     = 1;
            $Messages['sent']                                      = 1;
            $Messages['trash']                                     = 1;
            $Messages['reply']                                     = 1;
            $Messages['index']                                     = 1;
            $Messages['view']                                      = 1;
            $Messages['add']                                       = 1;
            $Messages['edit']                                      = 1;
            $Messages['delete']                                    = 1;
            $Messages['delete_all']                                = 1;
            $Messages['inbox_dashboard']                           = 1;
            $this->request->data['ACL']['user_access']['messages'] = $Messages;
            
            $NotificationUsers['display_notifications_initial']              = 1;
            $NotificationUsers['display_notifications']                      = 1;
            $this->request->data['ACL']['user_access']['notification_users'] = $NotificationUsers;
            
            $Notifications['box']                                       = 1;
            $Notifications['search']                                    = 1;
            $Notifications['advanced_search']                           = 1;
            $Notifications['lists']                                     = 1;
            $Notifications['index']                                     = 1;
            $Notifications['view']                                      = 1;
            $Notifications['add_ajax']                                  = 0;
            $Notifications['edit']                                      = 0;
            $Notifications['delete']                                    = 1;
            $Notifications['delete_all']                                = 1;
            $this->request->data['ACL']['user_access']['notifications'] = $Notifications;
            
            $SuggestionForms['box']             = 1;
            $SuggestionForms['search']          = 1;
            $SuggestionForms['advanced_search'] = 1;
            $SuggestionForms['lists']           = 1;
            $SuggestionForms['index']           = 1;
            $SuggestionForms['view']            = 1;
            $SuggestionForms['add_ajax']        = 1;
            $SuggestionForms['edit']            = 1;
            $SuggestionForms['delete']          = 1;
            $SuggestionForms['delete_all']      = 1;
            
            $this->request->data['ACL']['user_access']['suggestion_forms'] = $SuggestionForms;
            
            
            $this->request->data['ACL']['user_access']['users']['reset_password']       = 1;
            $this->request->data['ACL']['user_access']['users']['save_user_password']   = 1;
            $this->request->data['ACL']['user_access']['users']['login']                = 1;
            $this->request->data['ACL']['user_access']['users']['logout']               = 1;
            $this->request->data['ACL']['user_access']['users']['dashboard']            = 1;
            $this->request->data['ACL']['user_access']['users']['access_denied']        = 1;
            $this->request->data['ACL']['user_access']['users']['terms_and_conditions'] = 0;
            $this->request->data['ACL']['user_access']['users']['branches_gauge']       = 1;
            $this->request->data['ACL']['user_access']['users']['change_password']      = 1;
            $this->request->data['ACL']['user_access']['users']['check_email']          = 1;
            $this->request->data['ACL']['user_access']['users']['activate']             = 1;
            $this->request->data['ACL']['user_access']['users']['unblock_user']         = 1;
            $this->request->data['ACL']['user_access']['users']['register']             = 1;
            $this->request->data['ACL']['user_access']['users']['welcome']              = 1;
            $this->request->data['ACL']['user_access']['users']['timelinetabs']         = 1;
            
            $this->request->data['ACL']['user_access']['tasks']['get_task']         = 1;
            $this->request->data['ACL']['user_access']['task_statuses']['index']         = 1;
            $this->request->data['ACL']['user_access']['task_statuses']['view']         = 1;
            $this->request->data['ACL']['user_access']['task_statuses']['task_completion']         = 1;
            $this->request->data['ACL']['user_access']['tasks']['get_project_task'] = 1;
            $this->request->data['ACL']['user_access']['productions']['get_batch']  = 1;
            
            $this->request->data['ACL']['user_access']['appraisals']['appraisal_notification_email'] = 1;
            $this->request->data['ACL']['user_access']['appraisals']['appraisal_review']             = 1;
            $this->request->data['ACL']['user_access']['appraisals']['self_appraisals']              = 1;
            
            $this->request->data['ACL']['user_access']['internal_audit_plans']['get_dept_clauses']  = 1;
            $this->request->data['ACL']['user_access']['branches']['get_branch_name']               = 1;
            $this->request->data['ACL']['user_access']['internal_audits']['send_email']             = 1;
            $this->request->data['ACL']['user_access']['internal_audits']['audit_details_add_ajax'] = 1;
            $this->request->data['ACL']['user_access']['internal_audit_plans']['add_branches']      = 1;
            $this->request->data['ACL']['user_access']['internal_audit_plans']['add_departments']   = 1;
            $this->request->data['ACL']['user_access']['meetings']['add_after_meeting_topics']      = 1;
            $this->request->data['ACL']['user_access']['meetings']['meeting_view']                  = 1;
            
            
            
            $this->request->data['ACL']['user_access']['customers']['get_unique_values']                 = 1;
            $this->request->data['ACL']['user_access']['customer_complaints']['get_customer_complaints'] = 1;
            $this->request->data['ACL']['user_access']['customer_complaints']['check_complaint_number']  = 1;
            $this->request->data['ACL']['user_access']['customer_meetings']['followup_count']            = 1;
            $this->request->data['ACL']['user_access']['proposal_followups']['followup_count']           = 1;
            $this->request->data['ACL']['user_access']['dashboards']['result_mapping']                   = 1;
            
            
            if ($this->request->data['ACL']['user_access']['customer_meetings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['customer_meetings']['edit'] == 1)
                $this->request->data['ACL']['user_access']['customer_meetings']['add_followups'] = 1;
            else
                $this->request->data['ACL']['user_access']['customer_meetings']['add_followups'] = 0;
            
            //if( $this->request->data['ACL']['user_access']['calibrations']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['calibrations']['edit'] == 1)
            $this->request->data['ACL']['user_access']['calibrations']['get_details'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['calibrations']['get_details'] = 0;
            
            //  if( $this->request->data['ACL']['user_access']['customer_complaints']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['customer_complaints']['edit'] == 1 || $this->request->data['ACL']['user_access']['customer_complaints']['index'] == 1)
            $this->request->data['ACL']['user_access']['customer_complaints']['customer_complaint_status'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['customer_complaints']['customer_complaint_status'] = 0;
            
            //  if( $this->request->data['ACL']['user_access']['trainings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['trainings']['edit'] == 1)
            $this->request->data['ACL']['user_access']['trainings']['get_details'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['trainings']['get_details'] = 0;
            
            //  if( $this->request->data['ACL']['user_access']['corrective_preventive_actions']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['corrective_preventive_actions']['edit'] == 1)
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_assigned']                  = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_investigation_count']       = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_root_cuase_analysis_count'] = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_revised_dates_count']       = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['get_details']                    = 1;
            $this->request->data['ACL']['user_access']['capa_root_cause_analysis']['capa_assigned']                       = 1;
            $this->request->data['ACL']['user_access']['capa_investigations']['capa_assigned']                            = 1;
            $this->request->data['ACL']['user_access']['capa_revised_dates']['capa_assigned']                             = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['corrective_preventive_actions']['get_details'] = 0;
            
            //   if( $this->request->data['ACL']['user_access']['supplier_registrations']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['supplier_registrations']['edit'] == 1)
            $this->request->data['ACL']['user_access']['supplier_registrations']['get_supplier_registration_title'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['supplier_registrations']['get_supplier_registration_title'] = 0;
            
            // if( $this->request->data['ACL']['user_access']['employees']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['employees']['edit'] == 1)
            $this->request->data['ACL']['user_access']['employees']['get_employee_email'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['employees']['get_employee_email'] = 0;
            
            // if( $this->request->data['ACL']['user_access']['materials']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['materials']['edit'] == 1){
            $this->request->data['ACL']['user_access']['materials']['get_material_name']                = 1;
            $this->request->data['ACL']['user_access']['materials']['get_material_qc_required']         = 1;
            $this->request->data['ACL']['user_access']['materials']['get_purchase_order_number']        = 1;
            //            }else{
            //                $this->request->data['ACL']['user_access']['materials']['get_material_name'] = 0;
            //                $this->request->data['ACL']['user_access']['materials']['get_material_qc_required'] = 0;
            //                $this->request->data['ACL']['user_access']['materials']['get_purchase_order_number'] = 0;
            //            }
            //  if( $this->request->data['ACL']['user_access']['purchase_orders']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['purchase_orders']['edit'] == 1){
            $this->request->data['ACL']['user_access']['purchase_orders']['add_purchase_order_details'] = 1;
            $this->request->data['ACL']['user_access']['purchase_orders']['get_purchase_order_number']  = 1;
            //            }else{
            //                $this->request->data['ACL']['user_access']['purchase_orders']['add_purchase_order_details'] = 0;
            //                $this->request->data['ACL']['user_access']['purchase_orders']['get_purchase_order_number'] = 0;
            //            }
            
            // if( $this->request->data['ACL']['user_access']['list_of_computers']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['list_of_computers']['edit'] == 1)
            $this->request->data['ACL']['user_access']['list_of_computers']['add_new_software'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['list_of_computers']['add_new_software'] = 0;
            
            // if( $this->request->data['ACL']['user_access']['appraisals']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['appraisals']['edit'] == 1)
            $this->request->data['ACL']['user_access']['appraisals']['add_questions'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['appraisals']['add_questions'] = 0;
            
            //   if( $this->request->data['ACL']['user_access']['device_maintenances']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['device_maintenances']['edit'] == 1 || $this->request->data['ACL']['user_access']['device_maintenances']['index'] == 1)
            $this->request->data['ACL']['user_access']['device_maintenances']['get_device_maintainance'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['device_maintenances']['get_device_maintainance'] = 0;
            
            //   if( $this->request->data['ACL']['user_access']['calibrations']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['calibrations']['edit'] == 1 || $this->request->data['ACL']['user_access']['calibrations']['index'] == 1)
            $this->request->data['ACL']['user_access']['calibrations']['get_next_calibration'] = 1;
            //            else
            //                   $this->request->data['ACL']['user_access']['calibrations']['get_next_calibration'] = 0;
            
            //            if( $this->request->data['ACL']['user_access']['material_quality_checks']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['material_quality_checks']['edit'] == 1 || $this->request->data['ACL']['user_access']['material_quality_checks']['index'] == 1){
            //
            $this->request->data['ACL']['user_access']['material_quality_checks']['get_process']        = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['get_material_check'] = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['material_count']     = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['quality_check']     = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['add_quality_check']     = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['add_to_stock']     = 1;

            
            //            } else {
            //                   $this->request->data['ACL']['user_access']['material_quality_checks']['get_process'] = 0;
            //                   $this->request->data['ACL']['user_access']['material_quality_checks']['get_material_check'] = 0;
            //                   $this->request->data['ACL']['user_access']['material_quality_checks']['material_count'] = 0;
            //            }
            //if( $this->request->data['ACL']['user_access']['stocks']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['stocks']['edit'] == 1){
            $this->request->data['ACL']['user_access']['stocks']['get_material']                        = 1;
            $this->request->data['ACL']['user_access']['stocks']['get_material_details']                = 1;
            $this->request->data['ACL']['user_access']['stocks']['get_dc_details']                      = 1;
            $this->request->data['ACL']['user_access']['stocks']['get_stock_details']     = 1;
            
            //            }else{
            //               $this->request->data['ACL']['user_access']['stocks']['get_material'] = 0;
            //               $this->request->data['ACL']['user_access']['stocks']['get_material_details'] = 0;
            //               $this->request->data['ACL']['user_access']['stocks']['get_dc_details'] = 0;
            //
            //            }
            if( $this->request->data['ACL']['user_access']['meetings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['meetings']['edit'] == 1 || $this->request->data['ACL']['user_access']['meetings']['add'] == 1){
                $this->request->data['ACL']['user_access']['meetings']['get_department_employee']                        = 1;
             
            }
            if ($this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['evaluate'] == 1) {
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['get_supplier_list'] = 1;
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['index']             = 1;
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['view']              = 1;
            } else {
                // $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['get_supplier_list'] = 1;
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['get_supplier_list'] = 0;
            }
            //  if( $this->request->data['ACL']['user_access']['delivery_challans']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['delivery_challans']['edit'] == 1){
            $this->request->data['ACL']['user_access']['delivery_challans']['get_purchase_order']            = 1;
            $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_details']           = 1;
            $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_number']            = 1;
            $this->request->data['ACL']['user_access']['delivery_challans']['get_delivered_material_qc']     = 1;
            //            }else{
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_purchase_order'] = 0;
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_details'] = 0;
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_number'] = 0;
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_delivered_material_qc'] = 0;
            $this->request->data['ACL']['user_access']['tasks']['task_ajax_file_count']                      = 1;
            $this->request->data['ACL']['user_access']['housekeeping_responsibilities']['housekeeping_ajax'] = 1;
            
            $this->request->data['ACL']['user_access']['reports']['report_center']        = 0;
            $this->request->data['ACL']['user_access']['reports']['manual_reports']       = 0;
            $this->request->data['ACL']['user_access']['users']['two_way_authentication'] = 0;
            $this->request->data['ACL']['user_access']['users']['password_setting']       = 0;
            $this->request->data['ACL']['user_access']['users']['smtp_details']           = 0;
            $this->request->data['ACL']['user_access']['email_triggers']['index']         = 0;
            if ($this->request->data['ACL']['user_access']['risk_assessments']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['risk_assessments']['edit'] == 1) {
                
                $this->request->data['ACL']['user_access']['risk_ratings']['index']      = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['view']       = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['add']        = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['edit']       = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete']     = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['lists']      = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge']      = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['report']     = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['approve']    = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete_all'] = 1;
                
                
                $this->request->data['ACL']['user_access']['hazard_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['approve']    = 1;
                
                
                $this->request->data['ACL']['user_access']['accident_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['accident_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['accident_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['accident_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['accident_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['accident_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['accident_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['accident_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['accident_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['accident_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['accident_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['accident_types']['approve']    = 1;
                
                
                $this->request->data['ACL']['user_access']['severiry_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['approve']    = 1;
                
                $this->request->data['ACL']['user_access']['hazard_sources']['index']      = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['view']       = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['add']        = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['edit']       = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete']     = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['lists']      = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge']      = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['report']     = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['approve']    = 1;
                
                $this->request->data['ACL']['user_access']['injury_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['injury_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['injury_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['injury_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['injury_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['injury_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['injury_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['injury_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['injury_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['injury_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['injury_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['injury_types']['approve']    = 1;
                
                $this->request->data['ACL']['user_access']['body_areas']['index']                 = 1;
                $this->request->data['ACL']['user_access']['body_areas']['view']                  = 1;
                $this->request->data['ACL']['user_access']['body_areas']['add']                   = 1;
                $this->request->data['ACL']['user_access']['body_areas']['edit']                  = 1;
                $this->request->data['ACL']['user_access']['body_areas']['delete']                = 1;
                $this->request->data['ACL']['user_access']['body_areas']['delete_all']            = 1;
                $this->request->data['ACL']['user_access']['body_areas']['lists']                 = 1;
                $this->request->data['ACL']['user_access']['body_areas']['purge']                 = 1;
                $this->request->data['ACL']['user_access']['body_areas']['purge_all']             = 1;
                $this->request->data['ACL']['user_access']['body_areas']['add_ajax']              = 1;
                $this->request->data['ACL']['user_access']['body_areas']['report']                = 1;
                $this->request->data['ACL']['user_access']['body_areas']['approve']               = 1;
                $this->request->data['ACL']['user_access']['meetings']['get_department_employee'] = 1;
            } else {
                
                $this->request->data['ACL']['user_access']['risk_ratings']['index']      = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['view']       = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['add']        = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['edit']       = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete']     = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['lists']      = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge']      = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['report']     = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['approve']    = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete_all'] = 0;
                
                
                $this->request->data['ACL']['user_access']['hazard_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['approve']    = 0;
                
                
                $this->request->data['ACL']['user_access']['accident_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['accident_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['accident_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['accident_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['accident_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['accident_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['accident_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['accident_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['accident_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['accident_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['accident_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['accident_types']['approve']    = 0;
                
                
                $this->request->data['ACL']['user_access']['severiry_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['approve']    = 0;
                
                $this->request->data['ACL']['user_access']['hazard_sources']['index']      = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['view']       = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['add']        = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['edit']       = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete']     = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['lists']      = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge']      = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['report']     = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['approve']    = 0;
                
                $this->request->data['ACL']['user_access']['injury_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['injury_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['injury_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['injury_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['injury_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['injury_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['injury_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['injury_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['injury_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['injury_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['injury_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['injury_types']['approve']    = 0;
                
                $this->request->data['ACL']['user_access']['body_areas']['index']      = 0;
                $this->request->data['ACL']['user_access']['body_areas']['view']       = 0;
                $this->request->data['ACL']['user_access']['body_areas']['add']        = 0;
                $this->request->data['ACL']['user_access']['body_areas']['edit']       = 0;
                $this->request->data['ACL']['user_access']['body_areas']['delete']     = 0;
                $this->request->data['ACL']['user_access']['body_areas']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['body_areas']['lists']      = 0;
                $this->request->data['ACL']['user_access']['body_areas']['purge']      = 0;
                $this->request->data['ACL']['user_access']['body_areas']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['body_areas']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['body_areas']['report']     = 0;
                $this->request->data['ACL']['user_access']['body_areas']['approve']    = 0;

                if( $this->request->data['ACL']['user_access']['meetings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['meetings']['edit'] == 1 || $this->request->data['ACL']['user_access']['meetings']['add'] == 1){
                    $this->request->data['ACL']['user_access']['meetings']['get_department_employee']                        = 1;
             
                }
            }

            $this->request->data['UserAccessControl']['user_access'] = json_encode($this->request->data['ACL']);
            $this->request->data['UserAccessControl']['users'] = json_encode($this->request->data['User_selected_users']);
            $data['UserAccessControl']['user_access'] = json_encode($this->request->data['ACL']);

			// Configure::write('debug',1);
			// debug($this->request->data);
			// exit;

			$this->request->data['UserAccessControl']['system_table_id'] = $this->_get_system_table_id();
			$this->UserAccessControl->create();


			if ($this->UserAccessControl->save($this->request->data)) {

				if($this->request->data['User_selected_users'] && $this->request->data['User_selected_users'] != -1){
					$this->_upadate_users($this->request->data['User_selected_users'],$this->request->data['UserAccessControl']['user_access']);
				}


				if ($this->_show_approvals()) $this->_save_approvals();
				$this->Session->setFlash(__('The user access control has been saved'));
				if($this->_show_evidence() == true)$this->redirect(array('action' => 'view',$this->UserAccessControl->id));
				else $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user access control could not be saved. Please, try again.'));
			}
		}

		$systemTables = $this->UserAccessControl->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->UserAccessControl->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->UserAccessControl->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->UserAccessControl->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->UserAccessControl->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->UserAccessControl->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->UserAccessControl->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->UserAccessControl->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		// $users = $this->PublishedUserList();
		// print_r($users);
        
        $this->loadModel('User');
        // remove users for which ACL is already set
        $acls = $this->UserAccessControl->find('all',array(
            'recursive'=>-1,
            'fields'=>array('UserAccessControl.id','UserAccessControl.users'),
            'conditions'=>array('UserAccessControl.publish'=>1,'UserAccessControl.soft_delete'=>0)));

        // Configure::write('debug',1);
        

        foreach ($acls as $acl) {
            $users = json_decode($acl['UserAccessControl']['users'],false);
            foreach ($users as $key => $value) {
                if($value != null)$selectedUsers[$value] = $value;
            }
        }
        // debug($selectedUsers);
        // exit;

        $users = $this->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.is_mr'=>0, 'User.id !=' =>  array_keys($selectedUsers))));
		$this->set(compact('systemTables', 'users', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
	
		$count = $this->UserAccessControl->find('count');
		$published = $this->UserAccessControl->find('count',array('conditions'=>array('UserAccessControl.publish'=>1)));
		$unpublished = $this->UserAccessControl->find('count',array('conditions'=>array('UserAccessControl.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));

	}


	public function _upadate_users($users = null, $access = null){
		$this->loadModel('User');
		foreach ($users as $user) {
			$userData = $this->User->find('first',array('conditions'=>array('User.id'=>$user)));
			if($userData){
				$userData['User']['user_access'] = $access;
				$this->User->create();
				$this->User->save($userData,false);

			}
		}
	}







/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->UserAccessControl->exists($id)) {
			throw new NotFoundException(__('Invalid user access control'));
		}
		
		if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }

        // ------------------

        ini_set('memory_limit', -1);
        // $this->User->recursive = 0;
        // if (!$this->User->exists($id)) {
        //     throw new NotFoundException(__('Invalid user'));
        // }
        
        $allControllers                         = $this->Ctrl->get();
        $otherControllers                       = array();
        // $otherControllers['Documents']                 = array(
        //     'evidences',
        //     'file_uploads'
        // );
        $otherControllers['MR']                 = array(
            'change_addition_deletion_requests',
            'document_amendment_record_sheets',
            'master_list_of_formats',
            'meetings',
            'internal_audits',
            'internal_audit_plans',
            'corrective_preventive_actions',
            'capa_investigations',
            'capa_root_cause_analysis',
            'capa_revised_dates',
            'capa_categories',
            'capa_sources',
            'tasks',
            'benchmarks'
        );
        $otherControllers['HR']                 = array(
            'training_need_identifications',
            'courses',
            'course_types',
            'training_evaluations',
            'competency_mappings',
            'trainings',
            'trainers',
            'trainer_types',
            'appraisals',
            'appraisal_questions'
        );
        $otherControllers['BD']                 = array(
            'customer_meetings',
            'proposals',
            'proposal_followups'
        );
        $otherControllers['Purchase']           = array(
            'supplier_registrations',
            'supplier_categories',
            'list_of_acceptable_suppliers',
            'supplier_evaluation_reevaluations',
            'summery_of_supplier_evaluations',
            'delivery_challans',
            'purchase_orders',
            'invoices',
            'invoice_details'
        );
        $otherControllers['Admin']              = array(
            'fire_extinguishers',
            'housekeeping_checklists',
            'housekeeping_responsibilities',
            'fire_extinguisher_types'
        );
        $otherControllers['Quality Control']    = array(
            'customer_complaints',
            'list_of_measuring_devices_for_calibrations',
            'calibrations',
            'customer_feedbacks',
            'customer_feedback_questions',
            'material_quality_checks',
            'device_maintenances'
        );
        $otherControllers['EDP']                = array(
            'username_password_details',
            'list_of_computers',
            'list_of_softwares',
            'list_of_computer_list_of_softwares',
            'databackup_logbooks',
            'daily_backup_details',
            'data_back_ups'
        );
        $otherControllers['Production']         = array(
            'materials',
            'productions',
            'stocks'
        );
        $otherControllers['Data Entry']         = array(
            'branches',
            'departments',
            'designations',
            'employees',
            'users',
            'products',
            'devices',
            'customers',
            'software_types',
            'training_types'
        );
        $otherControllers['Incident Reporting'] = array(
            'risk_assessments',
            'incidents',
            'incident_investigations',
            'incident_affected_personals',
            'incident_classifications',
            'incident_investigators',
            'incident_witnesses'
        );
        $otherControllers['Objectives']         = array(
            'objectives',
            'objective_monitorings',
            'processes',
            'process_teams'
        );
        $otherControllers['Settings']           = array(
            'auto_approvals',
            'system_tables',
            'companies'
        );
        $otherControllers['FMEA']           = array(
            'fmeas',
            'fmea_actions',
            'fmea_severity_types',
            'fmea_occurences',
            'fmea_detections'
        );
        
        $this->loadModel('MasterListOfFormatDepartment');
        foreach ($otherControllers as $key => $controllers):
            foreach ($controllers as $controller):
                $getActions = Inflector::camelize($controller) . 'Controller';
                if (isset($allControllers[$getActions]) && (!in_array("delete", $allControllers[$getActions]))) {
                    $allControllers[$getActions][] = 'delete';
                }
                $deptWise[$key][$controller]['actions'] = $allControllers[$getActions];
            endforeach;
        endforeach;
        
        $this->set('forms', $deptWise);
        
            
    
        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
        
        if ($this->request->is('post')) {
            // Configure::write('debug',1);
            // debug($this->request->data);
            // exit;

            $dashboard['mr']                                         = 1;
            $dashboard['hr']                                         = 1;
            $dashboard['bd']                                         = 1;
            $dashboard['production']                                 = 1;
            $dashboard['personal_admin']                             = 1;
            $dashboard['quality_control']                            = 1;
            $dashboard['edp']                                        = 1;
            $dashboard['purchase']                                   = 1;
            $dashboard['raicm']                                      = 1;
            $this->request->data['ACL']['user_access']['dashboards'] = $dashboard;
            
            $error['error500']                                   = 1;
            $error['error404']                                   = 1;
            $this->request->data['ACL']['user_access']['errors'] = $error;
            
            $help['view']                                       = 1;
            $help['edit']                                       = 1;
            $help['help']                                       = 1;
            $this->request->data['ACL']['user_access']['helps'] = $help;
            
            $MessageUserSents['index']                                       = 1;
            $MessageUserSents['view']                                        = 1;
            $MessageUserSents['add']                                         = 1;
            $MessageUserSents['edit']                                        = 1;
            $MessageUserSents['delete']                                      = 1;
            $MessageUserSents['delete_all']                                  = 1;
            $this->request->data['ACL']['user_access']['message_user_sents'] = $MessageUserSents;
            
            $MessageUserThrashes['index']                                       = 1;
            $MessageUserThrashes['view']                                        = 1;
            $MessageUserThrashes['add']                                         = 1;
            $MessageUserThrashes['edit']                                        = 1;
            $MessageUserThrashes['delete']                                      = 1;
            $MessageUserThrashes['delete_all']                                  = 1;
            $this->request->data['ACL']['user_access']['message_user_thrashes'] = $MessageUserThrashes;
            
            
            $Messages['inbox']                                     = 1;
            $Messages['sent']                                      = 1;
            $Messages['trash']                                     = 1;
            $Messages['reply']                                     = 1;
            $Messages['index']                                     = 1;
            $Messages['view']                                      = 1;
            $Messages['add']                                       = 1;
            $Messages['edit']                                      = 1;
            $Messages['delete']                                    = 1;
            $Messages['delete_all']                                = 1;
            $Messages['inbox_dashboard']                           = 1;
            $this->request->data['ACL']['user_access']['messages'] = $Messages;
            
            $NotificationUsers['display_notifications_initial']              = 1;
            $NotificationUsers['display_notifications']                      = 1;
            $this->request->data['ACL']['user_access']['notification_users'] = $NotificationUsers;
            
            $Notifications['box']                                       = 1;
            $Notifications['search']                                    = 1;
            $Notifications['advanced_search']                           = 1;
            $Notifications['lists']                                     = 1;
            $Notifications['index']                                     = 1;
            $Notifications['view']                                      = 1;
            $Notifications['add_ajax']                                  = 0;
            $Notifications['edit']                                      = 0;
            $Notifications['delete']                                    = 1;
            $Notifications['delete_all']                                = 1;
            $this->request->data['ACL']['user_access']['notifications'] = $Notifications;
            
            $SuggestionForms['box']             = 1;
            $SuggestionForms['search']          = 1;
            $SuggestionForms['advanced_search'] = 1;
            $SuggestionForms['lists']           = 1;
            $SuggestionForms['index']           = 1;
            $SuggestionForms['view']            = 1;
            $SuggestionForms['add_ajax']        = 1;
            $SuggestionForms['edit']            = 1;
            $SuggestionForms['delete']          = 1;
            $SuggestionForms['delete_all']      = 1;
            
            $this->request->data['ACL']['user_access']['suggestion_forms'] = $SuggestionForms;
            
            
            $this->request->data['ACL']['user_access']['users']['reset_password']       = 1;
            $this->request->data['ACL']['user_access']['users']['save_user_password']   = 1;
            $this->request->data['ACL']['user_access']['users']['login']                = 1;
            $this->request->data['ACL']['user_access']['users']['logout']               = 1;
            $this->request->data['ACL']['user_access']['users']['dashboard']            = 1;
            $this->request->data['ACL']['user_access']['users']['access_denied']        = 1;
            $this->request->data['ACL']['user_access']['users']['terms_and_conditions'] = 0;
            $this->request->data['ACL']['user_access']['users']['branches_gauge']       = 1;
            $this->request->data['ACL']['user_access']['users']['change_password']      = 1;
            $this->request->data['ACL']['user_access']['users']['check_email']          = 1;
            $this->request->data['ACL']['user_access']['users']['activate']             = 1;
            $this->request->data['ACL']['user_access']['users']['unblock_user']         = 1;
            $this->request->data['ACL']['user_access']['users']['register']             = 1;
            $this->request->data['ACL']['user_access']['users']['welcome']              = 1;
            $this->request->data['ACL']['user_access']['users']['timelinetabs']         = 1;
            
            $this->request->data['ACL']['user_access']['tasks']['get_task']         = 1;
            $this->request->data['ACL']['user_access']['task_statuses']['index']         = 1;
            $this->request->data['ACL']['user_access']['task_statuses']['view']         = 1;
            $this->request->data['ACL']['user_access']['task_statuses']['task_completion']         = 1;
            $this->request->data['ACL']['user_access']['tasks']['get_project_task'] = 1;
            $this->request->data['ACL']['user_access']['productions']['get_batch']  = 1;
            
            $this->request->data['ACL']['user_access']['appraisals']['appraisal_notification_email'] = 1;
            $this->request->data['ACL']['user_access']['appraisals']['appraisal_review']             = 1;
            $this->request->data['ACL']['user_access']['appraisals']['self_appraisals']              = 1;
            
            $this->request->data['ACL']['user_access']['internal_audit_plans']['get_dept_clauses']  = 1;
            $this->request->data['ACL']['user_access']['branches']['get_branch_name']               = 1;
            $this->request->data['ACL']['user_access']['internal_audits']['send_email']             = 1;
            $this->request->data['ACL']['user_access']['internal_audits']['audit_details_add_ajax'] = 1;
            $this->request->data['ACL']['user_access']['internal_audit_plans']['add_branches']      = 1;
            $this->request->data['ACL']['user_access']['internal_audit_plans']['add_departments']   = 1;
            $this->request->data['ACL']['user_access']['meetings']['add_after_meeting_topics']      = 1;
            $this->request->data['ACL']['user_access']['meetings']['meeting_view']                  = 1;
            
            
            
            $this->request->data['ACL']['user_access']['customers']['get_unique_values']                 = 1;
            $this->request->data['ACL']['user_access']['customer_complaints']['get_customer_complaints'] = 1;
            $this->request->data['ACL']['user_access']['customer_complaints']['check_complaint_number']  = 1;
            $this->request->data['ACL']['user_access']['customer_meetings']['followup_count']            = 1;
            $this->request->data['ACL']['user_access']['proposal_followups']['followup_count']           = 1;
            $this->request->data['ACL']['user_access']['dashboards']['result_mapping']                   = 1;
            
            
            if ($this->request->data['ACL']['user_access']['customer_meetings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['customer_meetings']['edit'] == 1)
                $this->request->data['ACL']['user_access']['customer_meetings']['add_followups'] = 1;
            else
                $this->request->data['ACL']['user_access']['customer_meetings']['add_followups'] = 0;
            
            //if( $this->request->data['ACL']['user_access']['calibrations']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['calibrations']['edit'] == 1)
            $this->request->data['ACL']['user_access']['calibrations']['get_details'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['calibrations']['get_details'] = 0;
            
            //  if( $this->request->data['ACL']['user_access']['customer_complaints']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['customer_complaints']['edit'] == 1 || $this->request->data['ACL']['user_access']['customer_complaints']['index'] == 1)
            $this->request->data['ACL']['user_access']['customer_complaints']['customer_complaint_status'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['customer_complaints']['customer_complaint_status'] = 0;
            
            //  if( $this->request->data['ACL']['user_access']['trainings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['trainings']['edit'] == 1)
            $this->request->data['ACL']['user_access']['trainings']['get_details'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['trainings']['get_details'] = 0;
            
            //  if( $this->request->data['ACL']['user_access']['corrective_preventive_actions']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['corrective_preventive_actions']['edit'] == 1)
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_assigned']                  = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_investigation_count']       = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_root_cuase_analysis_count'] = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['capa_revised_dates_count']       = 1;
            $this->request->data['ACL']['user_access']['corrective_preventive_actions']['get_details']                    = 1;
            $this->request->data['ACL']['user_access']['capa_root_cause_analysis']['capa_assigned']                       = 1;
            $this->request->data['ACL']['user_access']['capa_investigations']['capa_assigned']                            = 1;
            $this->request->data['ACL']['user_access']['capa_revised_dates']['capa_assigned']                             = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['corrective_preventive_actions']['get_details'] = 0;
            
            //   if( $this->request->data['ACL']['user_access']['supplier_registrations']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['supplier_registrations']['edit'] == 1)
            $this->request->data['ACL']['user_access']['supplier_registrations']['get_supplier_registration_title'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['supplier_registrations']['get_supplier_registration_title'] = 0;
            
            // if( $this->request->data['ACL']['user_access']['employees']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['employees']['edit'] == 1)
            $this->request->data['ACL']['user_access']['employees']['get_employee_email'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['employees']['get_employee_email'] = 0;
            
            // if( $this->request->data['ACL']['user_access']['materials']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['materials']['edit'] == 1){
            $this->request->data['ACL']['user_access']['materials']['get_material_name']                = 1;
            $this->request->data['ACL']['user_access']['materials']['get_material_qc_required']         = 1;
            $this->request->data['ACL']['user_access']['materials']['get_purchase_order_number']        = 1;
            //            }else{
            //                $this->request->data['ACL']['user_access']['materials']['get_material_name'] = 0;
            //                $this->request->data['ACL']['user_access']['materials']['get_material_qc_required'] = 0;
            //                $this->request->data['ACL']['user_access']['materials']['get_purchase_order_number'] = 0;
            //            }
            //  if( $this->request->data['ACL']['user_access']['purchase_orders']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['purchase_orders']['edit'] == 1){
            $this->request->data['ACL']['user_access']['purchase_orders']['add_purchase_order_details'] = 1;
            $this->request->data['ACL']['user_access']['purchase_orders']['get_purchase_order_number']  = 1;
            //            }else{
            //                $this->request->data['ACL']['user_access']['purchase_orders']['add_purchase_order_details'] = 0;
            //                $this->request->data['ACL']['user_access']['purchase_orders']['get_purchase_order_number'] = 0;
            //            }
            
            // if( $this->request->data['ACL']['user_access']['list_of_computers']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['list_of_computers']['edit'] == 1)
            $this->request->data['ACL']['user_access']['list_of_computers']['add_new_software'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['list_of_computers']['add_new_software'] = 0;
            
            // if( $this->request->data['ACL']['user_access']['appraisals']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['appraisals']['edit'] == 1)
            $this->request->data['ACL']['user_access']['appraisals']['add_questions'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['appraisals']['add_questions'] = 0;
            
            //   if( $this->request->data['ACL']['user_access']['device_maintenances']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['device_maintenances']['edit'] == 1 || $this->request->data['ACL']['user_access']['device_maintenances']['index'] == 1)
            $this->request->data['ACL']['user_access']['device_maintenances']['get_device_maintainance'] = 1;
            //            else
            //               $this->request->data['ACL']['user_access']['device_maintenances']['get_device_maintainance'] = 0;
            
            //   if( $this->request->data['ACL']['user_access']['calibrations']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['calibrations']['edit'] == 1 || $this->request->data['ACL']['user_access']['calibrations']['index'] == 1)
            $this->request->data['ACL']['user_access']['calibrations']['get_next_calibration'] = 1;
            //            else
            //                   $this->request->data['ACL']['user_access']['calibrations']['get_next_calibration'] = 0;
            
            //            if( $this->request->data['ACL']['user_access']['material_quality_checks']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['material_quality_checks']['edit'] == 1 || $this->request->data['ACL']['user_access']['material_quality_checks']['index'] == 1){
            //
            $this->request->data['ACL']['user_access']['material_quality_checks']['get_process']        = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['get_material_check'] = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['material_count']     = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['quality_check']     = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['add_quality_check']     = 1;
            $this->request->data['ACL']['user_access']['material_quality_checks']['add_to_stock']     = 1;

            
            //            } else {
            //                   $this->request->data['ACL']['user_access']['material_quality_checks']['get_process'] = 0;
            //                   $this->request->data['ACL']['user_access']['material_quality_checks']['get_material_check'] = 0;
            //                   $this->request->data['ACL']['user_access']['material_quality_checks']['material_count'] = 0;
            //            }
            //if( $this->request->data['ACL']['user_access']['stocks']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['stocks']['edit'] == 1){
            $this->request->data['ACL']['user_access']['stocks']['get_material']                        = 1;
            $this->request->data['ACL']['user_access']['stocks']['get_material_details']                = 1;
            $this->request->data['ACL']['user_access']['stocks']['get_dc_details']                      = 1;
            $this->request->data['ACL']['user_access']['stocks']['get_stock_details']     = 1;
            
            //            }else{
            //               $this->request->data['ACL']['user_access']['stocks']['get_material'] = 0;
            //               $this->request->data['ACL']['user_access']['stocks']['get_material_details'] = 0;
            //               $this->request->data['ACL']['user_access']['stocks']['get_dc_details'] = 0;
            //
            //            }
            if( $this->request->data['ACL']['user_access']['meetings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['meetings']['edit'] == 1 || $this->request->data['ACL']['user_access']['meetings']['add'] == 1){
                $this->request->data['ACL']['user_access']['meetings']['get_department_employee']                        = 1;
             
            }
            if ($this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['evaluate'] == 1) {
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['get_supplier_list'] = 1;
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['index']             = 1;
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['view']              = 1;
            } else {
                // $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['get_supplier_list'] = 1;
                $this->request->data['ACL']['user_access']['supplier_evaluation_reevaluations']['get_supplier_list'] = 0;
            }
            //  if( $this->request->data['ACL']['user_access']['delivery_challans']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['delivery_challans']['edit'] == 1){
            $this->request->data['ACL']['user_access']['delivery_challans']['get_purchase_order']            = 1;
            $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_details']           = 1;
            $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_number']            = 1;
            $this->request->data['ACL']['user_access']['delivery_challans']['get_delivered_material_qc']     = 1;
            //            }else{
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_purchase_order'] = 0;
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_details'] = 0;
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_challan_number'] = 0;
            //               $this->request->data['ACL']['user_access']['delivery_challans']['get_delivered_material_qc'] = 0;
            $this->request->data['ACL']['user_access']['tasks']['task_ajax_file_count']                      = 1;
            $this->request->data['ACL']['user_access']['housekeeping_responsibilities']['housekeeping_ajax'] = 1;
            
            $this->request->data['ACL']['user_access']['reports']['report_center']        = 0;
            $this->request->data['ACL']['user_access']['reports']['manual_reports']       = 0;
            $this->request->data['ACL']['user_access']['users']['two_way_authentication'] = 0;
            $this->request->data['ACL']['user_access']['users']['password_setting']       = 0;
            $this->request->data['ACL']['user_access']['users']['smtp_details']           = 0;
            $this->request->data['ACL']['user_access']['email_triggers']['index']         = 0;
            if ($this->request->data['ACL']['user_access']['risk_assessments']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['risk_assessments']['edit'] == 1) {
                
                $this->request->data['ACL']['user_access']['risk_ratings']['index']      = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['view']       = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['add']        = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['edit']       = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete']     = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['lists']      = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge']      = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['report']     = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['approve']    = 1;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete_all'] = 1;
                
                
                $this->request->data['ACL']['user_access']['hazard_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['hazard_types']['approve']    = 1;
                
                
                $this->request->data['ACL']['user_access']['accident_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['accident_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['accident_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['accident_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['accident_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['accident_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['accident_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['accident_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['accident_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['accident_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['accident_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['accident_types']['approve']    = 1;
                
                
                $this->request->data['ACL']['user_access']['severiry_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['severiry_types']['approve']    = 1;
                
                $this->request->data['ACL']['user_access']['hazard_sources']['index']      = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['view']       = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['add']        = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['edit']       = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete']     = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['lists']      = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge']      = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['report']     = 1;
                $this->request->data['ACL']['user_access']['hazard_sources']['approve']    = 1;
                
                $this->request->data['ACL']['user_access']['injury_types']['index']      = 1;
                $this->request->data['ACL']['user_access']['injury_types']['view']       = 1;
                $this->request->data['ACL']['user_access']['injury_types']['add']        = 1;
                $this->request->data['ACL']['user_access']['injury_types']['edit']       = 1;
                $this->request->data['ACL']['user_access']['injury_types']['delete']     = 1;
                $this->request->data['ACL']['user_access']['injury_types']['delete_all'] = 1;
                $this->request->data['ACL']['user_access']['injury_types']['lists']      = 1;
                $this->request->data['ACL']['user_access']['injury_types']['purge']      = 1;
                $this->request->data['ACL']['user_access']['injury_types']['purge_all']  = 1;
                $this->request->data['ACL']['user_access']['injury_types']['add_ajax']   = 1;
                $this->request->data['ACL']['user_access']['injury_types']['report']     = 1;
                $this->request->data['ACL']['user_access']['injury_types']['approve']    = 1;
                
                $this->request->data['ACL']['user_access']['body_areas']['index']                 = 1;
                $this->request->data['ACL']['user_access']['body_areas']['view']                  = 1;
                $this->request->data['ACL']['user_access']['body_areas']['add']                   = 1;
                $this->request->data['ACL']['user_access']['body_areas']['edit']                  = 1;
                $this->request->data['ACL']['user_access']['body_areas']['delete']                = 1;
                $this->request->data['ACL']['user_access']['body_areas']['delete_all']            = 1;
                $this->request->data['ACL']['user_access']['body_areas']['lists']                 = 1;
                $this->request->data['ACL']['user_access']['body_areas']['purge']                 = 1;
                $this->request->data['ACL']['user_access']['body_areas']['purge_all']             = 1;
                $this->request->data['ACL']['user_access']['body_areas']['add_ajax']              = 1;
                $this->request->data['ACL']['user_access']['body_areas']['report']                = 1;
                $this->request->data['ACL']['user_access']['body_areas']['approve']               = 1;
                $this->request->data['ACL']['user_access']['meetings']['get_department_employee'] = 1;
            } else {
                
                $this->request->data['ACL']['user_access']['risk_ratings']['index']      = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['view']       = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['add']        = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['edit']       = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete']     = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['lists']      = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge']      = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['report']     = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['approve']    = 0;
                $this->request->data['ACL']['user_access']['risk_ratings']['delete_all'] = 0;
                
                
                $this->request->data['ACL']['user_access']['hazard_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['hazard_types']['approve']    = 0;
                
                
                $this->request->data['ACL']['user_access']['accident_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['accident_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['accident_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['accident_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['accident_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['accident_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['accident_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['accident_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['accident_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['accident_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['accident_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['accident_types']['approve']    = 0;
                
                
                $this->request->data['ACL']['user_access']['severiry_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['severiry_types']['approve']    = 0;
                
                $this->request->data['ACL']['user_access']['hazard_sources']['index']      = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['view']       = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['add']        = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['edit']       = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete']     = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['lists']      = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge']      = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['report']     = 0;
                $this->request->data['ACL']['user_access']['hazard_sources']['approve']    = 0;
                
                $this->request->data['ACL']['user_access']['injury_types']['index']      = 0;
                $this->request->data['ACL']['user_access']['injury_types']['view']       = 0;
                $this->request->data['ACL']['user_access']['injury_types']['add']        = 0;
                $this->request->data['ACL']['user_access']['injury_types']['edit']       = 0;
                $this->request->data['ACL']['user_access']['injury_types']['delete']     = 0;
                $this->request->data['ACL']['user_access']['injury_types']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['injury_types']['lists']      = 0;
                $this->request->data['ACL']['user_access']['injury_types']['purge']      = 0;
                $this->request->data['ACL']['user_access']['injury_types']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['injury_types']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['injury_types']['report']     = 0;
                $this->request->data['ACL']['user_access']['injury_types']['approve']    = 0;
                
                $this->request->data['ACL']['user_access']['body_areas']['index']      = 0;
                $this->request->data['ACL']['user_access']['body_areas']['view']       = 0;
                $this->request->data['ACL']['user_access']['body_areas']['add']        = 0;
                $this->request->data['ACL']['user_access']['body_areas']['edit']       = 0;
                $this->request->data['ACL']['user_access']['body_areas']['delete']     = 0;
                $this->request->data['ACL']['user_access']['body_areas']['delete_all'] = 0;
                $this->request->data['ACL']['user_access']['body_areas']['lists']      = 0;
                $this->request->data['ACL']['user_access']['body_areas']['purge']      = 0;
                $this->request->data['ACL']['user_access']['body_areas']['purge_all']  = 0;
                $this->request->data['ACL']['user_access']['body_areas']['add_ajax']   = 0;
                $this->request->data['ACL']['user_access']['body_areas']['report']     = 0;
                $this->request->data['ACL']['user_access']['body_areas']['approve']    = 0;

                if( $this->request->data['ACL']['user_access']['meetings']['add_ajax'] == 1 || $this->request->data['ACL']['user_access']['meetings']['edit'] == 1 || $this->request->data['ACL']['user_access']['meetings']['add'] == 1){
                    $this->request->data['ACL']['user_access']['meetings']['get_department_employee']                        = 1;
             
                }
            }
        }

            $this->request->data['UserAccessControl']['user_access'] = json_encode($this->request->data['ACL']);
            $this->request->data['UserAccessControl']['users'] = json_encode($this->request->data['User_selected_users']);
            $data['UserAccessControl']['user_access'] = json_encode($this->request->data['ACL']);

        // ------------------
		
		if ($this->request->is('post') || $this->request->is('put')) {
      
			if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
						
            // Configure::write('debug',1);
            // debug($this->request->data);
            // exit;

			$this->request->data['UserAccessControl']['system_table_id'] = $this->_get_system_table_id();
			if ($this->UserAccessControl->save($this->request->data)) {
                $this->_upadate_users($this->request->data['User_selected_users'],$this->request->data['UserAccessControl']['user_access']);
				if ($this->_show_approvals()) $this->_save_approvals();
				
				if ($this->_show_evidence() == true)
				 $this->redirect(array('action' => 'view', $id));
				else
		 			$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user access control could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('UserAccessControl.' . $this->UserAccessControl->primaryKey => $id));
			$this->request->data = $this->UserAccessControl->find('first', $options);
            $newData                    = json_decode($this->request->data['UserAccessControl']['user_access'], true);
            
            $this->request->data['ACL'] = $newData;
            // Configure::write('debug',1);
            // debug($this->request->data);
            // exit;

		}
		$systemTables = $this->UserAccessControl->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->UserAccessControl->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->UserAccessControl->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->UserAccessControl->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->UserAccessControl->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->UserAccessControl->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->UserAccessControl->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->UserAccessControl->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		
        $this->loadModel('User');
        $users = $this->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.is_mr'=>0)));
        $selectedUsers = $this->User->find('list',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.id'=>json_decode($this->data['UserAccessControl']['users']))));
        $this->set(compact('systemTables', 'users', 'selectedUsers', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));

		$count = $this->UserAccessControl->find('count');
		$published = $this->UserAccessControl->find('count',array('conditions'=>array('UserAccessControl.publish'=>1)));
		$unpublished = $this->UserAccessControl->find('count',array('conditions'=>array('UserAccessControl.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));
	}

/**
 * approve method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function approve($id = null, $approvalId = null) {
		if (!$this->UserAccessControl->exists($id)) {
			throw new NotFoundException(__('Invalid user access control'));
		}
		
		$this->loadModel('Approval');
        if (!$this->Approval->exists($approvalId)) {
            throw new NotFoundException(__('Invalid approval id'));
        }

        $approval = $this->Approval->read(null, $approvalId);
        $this->set('same', $approval['Approval']['user_id']);

        if ($this->_show_approvals()) {
            $this->set(array('showApprovals' => $this->_show_approvals()));
        }
				
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->UserAccessControl->save($this->request->data)) {
				if(!isset($this->request->data[$this->modelClass]['publish']) && $this->request->data['Approval']['user_id'] != -1){
                $this->request->data[$this->modelClass]['publish'] = 0;
            }
            if ($this->UserAccessControl->save($this->request->data)) {
                $this->Session->setFlash(__('The user access control has been saved.'));

                if ($this->_show_approvals()) $this->_save_approvals();

            } else {
                $this->Session->setFlash(__('The user access control could not be saved. Please, try again.'));
            }
				
			} else {
				$this->Session->setFlash(__('The user access control could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('UserAccessControl.' . $this->UserAccessControl->primaryKey => $id));
			$this->request->data = $this->UserAccessControl->find('first', $options);
		}
		$systemTables = $this->UserAccessControl->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->UserAccessControl->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->UserAccessControl->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->UserAccessControl->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->UserAccessControl->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->UserAccessControl->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->UserAccessControl->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->UserAccessControl->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
		$count = $this->UserAccessControl->find('count');
		$published = $this->UserAccessControl->find('count',array('conditions'=>array('UserAccessControl.publish'=>1)));
		$unpublished = $this->UserAccessControl->find('count',array('conditions'=>array('UserAccessControl.publish'=>0)));
		
		$this->set(compact('count','published','unpublished'));
	}


/**
 * purge method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function purge($id = null) {
		$this->UserAccessControl->id = $id;
		if (!$this->UserAccessControl->exists()) {
			throw new NotFoundException(__('Invalid user access control'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->UserAccessControl->delete()) {
			$this->Session->setFlash(__('User access control deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User access control was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
        
       /**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null, $parent_id = null) {
	
            $model_name = $this->modelClass;
            if(!empty($id)){
    
            $data['id'] = $id;
            $data['soft_delete'] = 1;
            $model_name=$this->modelClass;
            $this->$model_name->save($data);
    }
    $this->redirect(array('action' => 'index'));
     
    
}
 
	
	
	
	public function report(){
		
		$result = explode('+',$this->request->data['userAccessControls']['rec_selected']);
		$this->UserAccessControl->recursive = 1;
		$userAccessControls = $this->UserAccessControl->find('all',array('UserAccessControl.publish'=>1,'UserAccessControl.soft_delete'=>1,'conditions'=>array('or'=>array('UserAccessControl.id'=>$result))));
		$this->set('userAccessControls', $userAccessControls);
		
				$systemTables = $this->UserAccessControl->SystemTable->find('list',array('conditions'=>array('SystemTable.publish'=>1,'SystemTable.soft_delete'=>0)));
		$masterListOfFormats = $this->UserAccessControl->MasterListOfFormat->find('list',array('conditions'=>array('MasterListOfFormat.publish'=>1,'MasterListOfFormat.soft_delete'=>0)));
		$divisions = $this->UserAccessControl->Division->find('list',array('conditions'=>array('Division.publish'=>1,'Division.soft_delete'=>0)));
		$companies = $this->UserAccessControl->Company->find('list',array('conditions'=>array('Company.publish'=>1,'Company.soft_delete'=>0)));
		$preparedBies = $this->UserAccessControl->PreparedBy->find('list',array('conditions'=>array('PreparedBy.publish'=>1,'PreparedBy.soft_delete'=>0)));
		$approvedBies = $this->UserAccessControl->ApprovedBy->find('list',array('conditions'=>array('ApprovedBy.publish'=>1,'ApprovedBy.soft_delete'=>0)));
		$createdBies = $this->UserAccessControl->CreatedBy->find('list',array('conditions'=>array('CreatedBy.publish'=>1,'CreatedBy.soft_delete'=>0)));
		$modifiedBies = $this->UserAccessControl->ModifiedBy->find('list',array('conditions'=>array('ModifiedBy.publish'=>1,'ModifiedBy.soft_delete'=>0)));
		$this->set(compact('systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies', 'systemTables', 'masterListOfFormats', 'divisions', 'companies', 'preparedBies', 'approvedBies', 'createdBies', 'modifiedBies'));
}
}

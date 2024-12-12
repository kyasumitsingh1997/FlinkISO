<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
App::import('Sanitize');

class DashboardsController extends AppController {
	
    public $components = array('RequestHandler', 'Session', 'AjaxMultiUpload.Upload','Bd');
    public $helpers = array('Js', 'Session', 'Paginator', 'Tinymce');

    public function mr() {
        $this->loadModel('Department');
        $departments = $this->Department->find('all', array('orderby' => array('name' => 'ASC'), 'conditions' => array('Department.publish' => 1, 'Department.soft_delete' => 0), 'recursive' => -1));
        $this->set('departments', $departments);
        //Meeting
        $this->loadModel('Meeting');
        $meetings = $this->Meeting->find('count', array('conditions' => array('Meeting.publish' => 1, 'Meeting.soft_delete' => 0), 'recursive' => -1));
        $this->set('countMeetings', $meetings);

        //InternalAuditDetail
        $this->loadModel('InternalAudit');
        $internalAudits = $this->InternalAudit->find('count', array('conditions' => array('InternalAudit.publish' => 1, 'InternalAudit.soft_delete' => 0), 'group' => 'InternalAudit.internal_audit_plan_id', 'recursive' => -1));
        $countNcs = $this->InternalAudit->find('count', array('conditions' => array('InternalAudit.non_conformity_found' => 1, 'InternalAudit.publish' => 1, 'InternalAudit.soft_delete' => 0), 'recursive' => -1));

        $this->set('countInternalAudits', $internalAudits);
        $this->set('countNcs', $countNcs);

        //CAPA
        $this->loadModel('CorrectivePreventiveAction');
        $capas = $this->CorrectivePreventiveAction->find('count', array('conditions' => array('CorrectivePreventiveAction.publish' => 1, 'CorrectivePreventiveAction.soft_delete' => 0), 'recursive' => -1));
        $this->set('countCapas', $capas);

        //CAPA
        $this->loadModel('Task');
        $tasks = $this->Task->find('count', array('conditions' => array('Task.publish' => 1, 'Task.soft_delete' => 0), 'recursive' => -1));
        $this->set('countTasks', $tasks);

        //CorrectivePreventiveAction
        $this->loadModel('ChangeAdditionDeletionRequest');
        $changeRequest = $this->ChangeAdditionDeletionRequest->find('count', array('conditions' => array('ChangeAdditionDeletionRequest.publish' => 1, 'ChangeAdditionDeletionRequest.soft_delete' => 0), 'recursive' => -1));
        $this->set('countChangeRequest', $changeRequest);

        $data = null;
        $benchmark = 0;
            $average = 0;
        if(file_exists(Configure::read('MediaPath') . "/files/".$this->Session->read('User.company_id')."/graphs/graph_data_branches_total.txt")){
	    $file = new File(Configure::read('MediaPath') . "/files/".$this->Session->read('User.company_id')."/graphs/graph_data_branches_total.txt");
            $data = $file->read();
            $data = json_decode($data, true);
            if(isset($data))
            foreach ($data as $branchAvg):
                $benchmark = $benchmark + $branchAvg['benchmark'];
                $average = $average + $branchAvg['count'];
            endforeach;
        }

        $this->loadModel('History');
        $startDate = $this->History->find('first', array('fields' => array('History.created'), 'order' => array('History.created' => 'asc'), 'recursive' => -1));
        $endDate = $this->History->find('first', array('fields' => array('History.created'), 'order' => array('History.created' => 'desc'), 'recursive' => -1));
        $startDate = date("Y-m-d H:i:s", strtotime($startDate['History']['created']));
        $endDate = date("Y-m-d H:i:s", strtotime($endDate['History']['created']));
        $diff = abs(strtotime($endDate) - strtotime($startDate));

        $diff = floor($diff / (60 * 60 * 24));
        if ($average > 0 && $diff > 0 && $benchmark > 0)
            $branchData = round((100 * ($average / $diff)) / $benchmark);
        else
            $branchData = 0;
        $this->set('branchData', $branchData);

        $data = null;
        if(file_exists(Configure::read('MediaPath') . "/files/".$this->Session->read('User.company_id')."/graphs/graph_data_departments_total.txt")){
	    $file = new File(Configure::read('MediaPath') . "/files/".$this->Session->read('User.company_id')."/graphs/graph_data_departments_total.txt");
            $data = $file->read();
            $data = json_decode($data, true);
            $benchmark = 0;
            $average = 0;
             if(isset($data))
            foreach ($data as $deptAvg):
                $benchmark = $benchmark + $deptAvg['benchmark'];
                $average = $average + $deptAvg['count'];
            endforeach;
        }

        if ($average > 0 && $diff > 0 && $benchmark > 0)
            $departmentData = round((100 * ($average / $diff)) / $benchmark);
        else
            $departmentData = 0;
        $this->set('departmentData', $departmentData);

        // get folders
        $dir = new Folder(Configure::read('MediaPath') . 'files');
        $folders = $dir->read(true);
        $this->set('folders', $folders);
        if (isset($this->request->params['pass'][0]))
            $p = $this->request->params['pass'][0];
        else
            $p = null;
        if (!$p)
            $p = "start";
        $this->set('mId', $p);

        // new standard change
        if($this->request->params['named']['standard_id']){
            $standard = array('MasterListOfFormatCategory.standard_id'=>$this->request->params['named']['standard_id']);
            $this->set('standard_id',$this->request->params['named']['standard_id']);
        }else{
            $standard = array('MasterListOfFormatCategory.standard_id'=>'5927120f-0608-4cff-ab75-9a437abbe57d');
            $this->set('standard_id','5927120f-0608-4cff-ab75-9a437abbe57d');
        }
        $this->loadModel('MasterListOfFormatCategory');
        $masterListOfFormatCategories = $this->MasterListOfFormatCategory->find('list', array('conditions' => array($standard,'MasterListOfFormatCategory.publish' => 1, 'MasterListOfFormatCategory.soft_delete' => 0)));

        $this->loadModel('Standard');
        $standards = $this->Standard->find('list', array('conditions' => array('Standard.publish' => 1, 'Standard.soft_delete' => 0)));

        $this->set(compact('masterListOfFormatCategories','standards'));
        $this->set('standard',$standard);
    }

    public function get_folders() {

        $paths = $this->request->params['pass'];
        foreach ($paths as $p):
            $path = $path . '/' . $p;
            $this->set('mId', $p);
        endforeach;
        $dir = new Folder(Configure::read('MediaPath') . 'files/' . $path . '/');
        $folders = $dir->read(true);

        if ($this->request->params['pass'][0] == 'upload' or $this->request->params['pass'][0] == 'import') {
            $this->loadModel('User');
            foreach ($folders[0] as $folder):
                $getUser = $this->User->find('first', array('conditions' => array('User.id' => $folder), 'fields' => array('User.id', 'User.username')));
                if ($getUser)
                    $getUsers[] = array($getUser['User']['username'], $getUser['User']['id']);
                else
                    $getUsers[] = array($folder, $folder);
            endforeach;
            $folders[0] = $getUsers;
        }else {
            foreach ($folders[0] as $folder):
                $getFolder[] = array($folder, $folder);
            endforeach;
            $folders[0] = $getFolder;
        }

        $this->set('folders', $folders);
        $this->set('path', $path);
    }

    public function get_file() {
        $paths = $this->request->params['pass'];
        foreach ($paths as $p):
            $path = $path . '/' . $p;
            $this->set('mId', $p);
        endforeach;
        $path = str_replace('<>', ' ', $path);
        $file = new File(Configure::read('MediaPath') . 'files/' . $path);
        $fileDetails = $file->info();
        $fileChange = $file->lastChange();
        $this->set('fileDetails', $fileDetails);
        $this->set('fileChange', $fileChange);
        //$c_file = Configure::read('MediaPath') . 'files/' . $path;
        $this->set('path', $path);
    }

    public function hr() {
        $this->loadModel('Employee');
        $employees = $this->Employee->find('count', array('conditions' => array('Employee.publish' => 1, 'Employee.soft_delete' => 0), 'recursive' => -1));
        $this->set('countEmployees', $employees);

        $this->loadModel('Course');
        $courses = $this->Course->find('count', array('conditions' => array('Course.publish' => 1, 'Course.soft_delete' => 0), 'recursive' => -1));
        $this->set('countCourses', $courses);

        $this->loadModel('Training');
        $trainings = $this->Training->find('count', array('conditions' => array('Training.publish' => 1, 'Training.soft_delete' => 0), 'recursive' => -1));
        $this->set('countTrainings', $trainings);

        $this->loadModel('TrainingNeedIdentification');
        $tni = $this->TrainingNeedIdentification->find('count', array('conditions' => array('TrainingNeedIdentification.publish' => 1, 'TrainingNeedIdentification.soft_delete' => 0), 'recursive' => -1));
        $this->set('countTNI', $tni);

        $this->loadModel('TrainingEvaluation');
        $trainingEvaluation = $this->TrainingEvaluation->find('count', array('conditions' => array('TrainingEvaluation.publish' => 1, 'TrainingEvaluation.soft_delete' => 0), 'recursive' => -1));
        $this->set('countTrainingEvaluation', $trainingEvaluation);

        $this->loadModel('CompetencyMapping');
        $competencyMappings = $this->CompetencyMapping->find('count', array('conditions' => array('CompetencyMapping.publish' => 1, 'CompetencyMapping.soft_delete' => 0), 'recursive' => -1));
        $this->set('countCompetencyMappings', $competencyMappings);

        $this->loadModel('Appraisal');
        $countAppraisals = $this->Appraisal->find('count', array('conditions' => array('Appraisal.publish' => 1, 'Appraisal.soft_delete' => 0), 'recursive' => -1));
        $this->set('countAppraisals', $countAppraisals);
    }

    public function personal_admin($id = null) {

        $this->loadModel('FireExtinguisher');
        $FireExt = $this->FireExtinguisher->find('count', array('conditions' => array('FireExtinguisher.publish' => 1, 'FireExtinguisher.soft_delete' => 0), 'recursive' => -1));
        $this->set('countFirExt', $FireExt);

        $this->loadModel('HousekeepingChecklist');
        $houseKeeping = $this->HousekeepingChecklist->find('count', array('conditions' => array('HousekeepingChecklist.publish' => 1, 'HousekeepingChecklist.soft_delete' => 0), 'recursive' => -1));
        $this->set('countHouseKeeping', $houseKeeping);

        $this->loadModel('HousekeepingResponsibility');
        $houseKeepingResp = $this->HousekeepingResponsibility->find('count', array('conditions' => array('HousekeepingResponsibility.publish' => 1, 'HousekeepingResponsibility.soft_delete' => 0), 'recursive' => -1));
        $this->set('countHouseKeepingResp', $houseKeepingResp);

    //Code for house keeping responsibility
        $this->loadModel('Housekeeping');
        if ($this->request->is('post')) {
            foreach ($this->request->data['Housekeeping'] as $houseKeeping) {
                if (!empty($houseKeeping['id']) && (isset($houseKeeping['task_performed']) || isset($houseKeeping['comments']))) {
                    $houseKeeping['backup_date'] = date('Y-m-d');
                    $houseKeeping['publish'] = 1;
                    $houseKeeping['employee_id'] = $this->Session->read('User.employee_id');
                    $houseKeeping['branchid'] = $this->Session->read('User.branch_id');
                    $houseKeeping['departmentid'] = $this->Session->read('User.department_id');
                    $houseKeeping['modified_by'] = $this->Session->read('User.id');
                    $this->Housekeeping->save($houseKeeping, false);
                } else if (empty($houseKeeping['id']) && isset($houseKeeping['task_performed']) && $houseKeeping['task_performed'] > 0) {
                    $this->Housekeeping->create();
                    $houseKeeping['backup_date'] = date('Y-m-d');
                    $houseKeeping['publish'] = 1;
                    $houseKeeping['employee_id'] = $this->Session->read('User.employee_id');
                    $houseKeeping['branchid'] = $this->Session->read('User.branch_id');
                    $houseKeeping['departmentid'] = $this->Session->read('User.department_id');
                    $houseKeeping['created_by'] = $this->Session->read('User.id');
                    $houseKeeping['modified_by'] = $this->Session->read('User.id');
                    $this->Housekeeping->save($houseKeeping, false);
                }
            }
            $this->Session->setFlash(__('The housekeeping has been saved'));
            $this->redirect(array('action' => 'personal_admin'));
        }
        $onlyBranch = null;
        $onlyOwn = null;
        $condition1 = null;
        $condition2 = null;
        $condition3 = null;
        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('HousekeepingResponsibility.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('HousekeepingResponsibility.created_by' => $this->Session->read('User.id'));
        if ($this->Session->read('User.is_mr') == 0) {
            $condition3 = array('HousekeepingResponsibility.employee_id' => $this->Session->read('User.employee_id'));
        }
        $finalCond = array('OR' => array($onlyBranch, $onlyOwn, $condition3));
        if ($this->request->params['named']) {
            if ($this->request->params['named']['published'] == null)
                $condition1 = null;
            else
                $condition1 = array('HousekeepingResponsibility.publish' => $this->request->params['named']['published']);
            if ($this->request->params['named']['soft_delete'] == null)
                $condition2 = null;
            else
                $condition2 = array('HousekeepingResponsibility.soft_delete' => $this->request->params['named']['soft_delete']);
            if ($this->request->params['named']['soft_delete'] == null)
                $conditions = array($onlyBranch, $onlyOwn, $condition1, $condition3, 'HousekeepingResponsibility.soft_delete' => 0);
            else
                $conditions = array($condition1, $condition2, $finalCond);
        }else {
            $conditions = array($finalCond, 'HousekeepingResponsibility.soft_delete' => 0);
        }
        $options = array('order' => array('HousekeepingResponsibility.sr_no' => 'DESC'), 'conditions' => array($conditions));
        $this->HousekeepingResponsibility->recursive = 0;
        $houseKeepings = $this->HousekeepingResponsibility->find('all', $options);
        $this->loadModel('Schedule');
        $scheduleList = $this->Schedule->find('list', array('conditions' => array('Schedule.publish' => 1, 'Schedule.soft_delete' => 0), 'recursive' => -1));
        foreach ($houseKeepings as $key => $houseKeeping) {
            $test = $this->Housekeeping->find('first', 
                array('order' => array('Housekeeping.sr_no' => 'DESC'), 
                    'conditions' => array(
                        'Housekeeping.housekeeping_responsibility_id' => $houseKeeping['HousekeepingResponsibility']['id'], 
                        // 'Housekeeping.employee_id' => $houseKeeping['HousekeepingResponsibility']['employee_id']
                        ), 
                    'recursive' => -1)
                );
            
            if (count($test)) {
                if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'dailly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Dailly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'daily' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Daily') {
                    if (date('Y-m-d', strtotime($test['Housekeeping']['created'])) == date('Y-m-d')) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                } else if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'weekly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Weekly') {

                    if (date('W', strtotime($test['Housekeeping']['created'])) == date('W')) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                } else if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'monthly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Monthly') {
                    if (date('m', strtotime($test['Housekeeping']['created'])) == date('m')) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                } else if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'quarterly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Quarterly') {
                    $created = date('Y-m-d', strtotime($houseKeeping['HousekeepingResponsibility']['created']));
                    $currentDate = date('Y-m-d', strtotime($houseKeeping['HousekeepingResponsibility']['created']));
                    $lastQuarter = date('Y-m-d');
                    $nextQuarter = date('Y-m-d');
                    $dateArray = array();
                    $i = 0;
                    while ($currentDate <= $lastQuarter) {
                        $nextQuarter = date('Y-m-d', strtotime('+3 month', strtotime($currentDate)));
                        $dateArray[$i]['currentDate'] = $currentDate;
                        $dateArray[$i]['nextQuarter'] = $nextQuarter;
                        $currentDate = $nextQuarter;
                        $i++;
                    }
                    $count = count($dateArray);

                    if (date('Y-m-d', strtotime($test['Housekeeping']['created'])) >= $dateArray[$count - 1]['currentDate'] && date('Y-m-d', strtotime($test['Housekeeping']['created'])) <= $dateArray[$count - 1]['nextQuarter']) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                } else if ($scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'yearly' || $scheduleList[$houseKeeping['HousekeepingResponsibility']['schedule_id']] == 'Yearly') {
                    if (date('y', strtotime($test['Housekeeping']['created'])) == date('y')) {
                        $houseKeepings[$key]['Housekeeping'] = $test['Housekeeping'];
                    } else {
                        $houseKeepings[$key]['Housekeeping'] = array();
                    }
                }
            }
        }
        $this->set('editId', $id);
        $this->set('houseKeepings', $houseKeepings);
    }

    public function quality_control() {

        $this->loadModel('CustomerComplaint');
        $openComplaints = $this->CustomerComplaint->find('count', array('conditions' => array('CustomerComplaint.current_status' => 0, 'CustomerComplaint.soft_delete' => 0, 'CustomerComplaint.publish' => 1), 'recursive' => -1));
        $this->set('openComplaints', $openComplaints);

        $complaintResolved = $this->CustomerComplaint->find('count', array('conditions' => array('CustomerComplaint.current_status <> ' => 0, 'CustomerComplaint.soft_delete' => 0, 'CustomerComplaint.publish' => 1), 'recursive' => -1));
        $this->set('complaintResolved', $complaintResolved);

        $this->loadModel('ListOfMeasuringDevicesForCalibration');
        $calibDevices = $this->ListOfMeasuringDevicesForCalibration->find('count', array('conditions' => array('ListOfMeasuringDevicesForCalibration.publish' => 1, 'ListOfMeasuringDevicesForCalibration.soft_delete' => 0), 'recursive' => -1));
        $this->set('countCalibdevices', $calibDevices);

        $this->loadModel('Calibration');
        $calibs = $this->Calibration->find('count', array('conditions' => array('Calibration.publish' => 1, 'Calibration.soft_delete' => 0), 'recursive' => -1));
        $this->set('countCalibs', $calibs);

        $this->loadModel('CustomerFeedback');
        $CustFeedbacks = $this->CustomerFeedback->find('all', array('conditions' => array('CustomerFeedback.publish' => 1, 'CustomerFeedback.soft_delete' => 0), 'group' => 'CustomerFeedback.customer_id', 'recursive' => -1));
        $count = count($CustFeedbacks);
        $this->set('countCustFeedbacks', $count);

        $this->loadModel('MaterialQualityCheck');
        $countMaterialQC = $this->MaterialQualityCheck->find('count', array('conditions' => array('MaterialQualityCheck.publish' => 1, 'MaterialQualityCheck.soft_delete' => 0), 'recursive' => -1, 'group' => 'MaterialQualityCheck.material_id'));
        if($countMaterialQC == false) $countMaterialQC = 0;
        $this->set('countMaterialQC', $countMaterialQC);

        $this->loadModel('DeviceMaintenance');
        $DeviceMaintenance = $this->DeviceMaintenance->find('count', array('conditions' => array('DeviceMaintenance.publish' => 1, 'DeviceMaintenance.soft_delete' => 0), 'recursive' => -1));
        $this->set('countDeviceMaintenance', $DeviceMaintenance);
    }

    public function edp($id = null) {

        $this->loadModel('User');
        $users = $this->User->find('count', array('conditions' => array('User.publish' => 1, 'User.soft_delete' => 0), 'recursive' => -1));
        $this->set('countUsers', $users);

        $this->loadModel('ListOfComputer');
        $listofcomps = $this->ListOfComputer->find('count', array('conditions' => array('ListOfComputer.publish' => 1, 'ListOfComputer.soft_delete' => 0), 'recursive' => -1));
        $this->set('countListofcomps', $listofcomps);

        $this->loadModel('DatabackupLogbook');
        $countDataBakupLogbk = $this->DatabackupLogbook->find('count', array('conditions' => array('DatabackupLogbook.publish' => 1, 'DatabackupLogbook.soft_delete' => 0), 'recursive' => -1));
        $this->set('countDataBakupLogbk', $countDataBakupLogbk);

        $this->loadModel('ListOfSoftware');
        $listOfSofts = $this->ListOfSoftware->find('count', array('conditions' => array('ListOfSoftware.publish' => 1, 'ListOfSoftware.soft_delete' => 0), 'recursive' => -1));
        $this->set('countListOfSofts', $listOfSofts);

        $this->loadModel('ListOfComputerListOfSoftware');
        $listOfCompSofts = $this->ListOfComputerListOfSoftware->find('count', array('conditions' => array('ListOfComputerListOfSoftware.publish' => 1, 'ListOfComputerListOfSoftware.soft_delete' => 0), 'recursive' => -1));
        $this->set('countListOfCompSofts', $listOfCompSofts);

        $this->loadModel('UsernamePasswordDetail');
        $usrPassDetails = $this->UsernamePasswordDetail->find('count', array('conditions' => array('UsernamePasswordDetail.publish' => 1, 'UsernamePasswordDetail.soft_delete' => 0), 'recursive' => -1));
        $this->set('countUsrPassDetails', $usrPassDetails);

        $this->loadModel('DailyBackupDetail');
        $this->loadModel('DatabackupLogbook');
        if ($this->request->is('post')) {
            foreach ($this->request->data['DatabackupLogbook'] as $databackupLogbook) {
                if (!empty($databackupLogbook['id']) && (isset($databackupLogbook['task_performed']) || isset($databackupLogbook['comments']))) {
                    $databackupLogbook['backup_date'] = date('Y-m-d');
                    $databackupLogbook['employee_id'] = $this->Session->read('User.employee_id');
                    $databackupLogbook['branchid'] = $this->Session->read('User.branch_id');
                    $databackupLogbook['publish'] = 1;
                    $databackupLogbook['departmentid'] = $this->Session->read('User.department_id');
                    $databackupLogbook['modified_by'] = $this->Session->read('User.id');

                    $this->DatabackupLogbook->save($databackupLogbook, false);
                } else if (empty($databackupLogbook['id']) && isset($databackupLogbook['task_performed']) && $databackupLogbook['task_performed'] > 0) {
                    $this->DatabackupLogbook->create();
                    $databackupLogbook['backup_date'] = date('Y-m-d');
                    $databackupLogbook['employee_id'] = $this->Session->read('User.employee_id');
                    $databackupLogbook['branchid'] = $this->Session->read('User.branch_id');
                    $databackupLogbook['departmentid'] = $this->Session->read('User.department_id');
                    $databackupLogbook['created_by'] = $this->Session->read('User.id');
                    $databackupLogbook['modified_by'] = $this->Session->read('User.id');
                    $databackupLogbook['publish'] = 1;
                    $this->DatabackupLogbook->save($databackupLogbook, false);
                }
            }
            $this->Session->setFlash(__('The Backup Details has been saved'));
            $this->redirect(array('action' => 'edp'));
        }
        $onlyBranch = null;
        $onlyOwn = null;
        $condition1 = null;
        $condition2 = null;
        $condition3 = null;
        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('DailyBackupDetail.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('DailyBackupDetail.created_by' => $this->Session->read('User.id'));
        if ($this->Session->read('User.is_mr') == 0) {
            $condition3 = array('DailyBackupDetail.employee_id' => $this->Session->read('User.employee_id'));
        }
        $finalCond = array('OR' => array($onlyBranch, $onlyOwn, $condition3));
        if ($this->request->params['named']) {
            if ($this->request->params['named']['published'] == null)
                $condition1 = null;
            else
                $condition1 = array('DailyBackupDetail.publish' => $this->request->params['named']['published']);
            if ($this->request->params['named']['soft_delete'] == null)
                $condition2 = null;
            else
                $condition2 = array('DailyBackupDetail.soft_delete' => $this->request->params['named']['soft_delete']);
            if ($this->request->params['named']['soft_delete'] == null)
                $conditions = array($onlyBranch, $onlyOwn, $condition1, $condition3, 'DailyBackupDetail.soft_delete' => 0);
            else
                $conditions = array($condition1, $condition2, $finalCond);
        }else {
            $conditions = array($finalCond, 'DailyBackupDetail.soft_delete' => 0);
        }
        $options = array('order' => array('DailyBackupDetail.sr_no' => 'DESC'), 'conditions' => array($conditions));
        $this->DailyBackupDetail->recursive = 0;

        $dailyBackupDetails = $this->DailyBackupDetail->find('all', $options);
        $this->loadModel('Schedule');
        $scheduleList = $this->Schedule->find('list', array('conditions' => array('Schedule.publish' => 1, 'Schedule.soft_delete' => 0), 'recursive' => -1));

        foreach ($dailyBackupDetails as $key => $dailyBackupDetail) {

            $test = $this->DatabackupLogbook->find('first', array('order' => array('DatabackupLogbook.sr_no' => 'DESC'), 'conditions' => array('DatabackupLogbook.daily_backup_detail_id' => $dailyBackupDetail['DailyBackupDetail']['id'], 'DatabackupLogbook.employee_id' => $dailyBackupDetail['DailyBackupDetail']['employee_id']), 'recursive' => -1));
            $listOfComp = $this->ListOfComputer->find('first', array('conditions' => array('ListOfComputer.id' => $dailyBackupDetail['DailyBackupDetail']['list_of_computer_id']), 'fields' => array('make'), 'recursive' => -1));

            $dailyBackupDetails[$key]['DataBackUp']['ScheduleName'] = $scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']];

            if (count($test)) {
                if ($scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'dailly' || $scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'Dailly' || $scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'daily' || $scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'Daily') {

                    if (date('Y-m-d', strtotime($test['DatabackupLogbook']['created'])) == date('Y-m-d')) {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = $test['DatabackupLogbook'];
                    } else {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = array();
                    }
                } else if ($scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'weekly' || $scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'Weekly') {
                    if (date('W', strtotime($test['DatabackupLogbook']['created'])) == date('W')) {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = $test['DatabackupLogbook'];
                    } else {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = array();
                    }
                } else if ($scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'monthly' || $scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'Monthly') {

                    if (date('m', strtotime($test['DatabackupLogbook']['created'])) == date('m')) {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = $test['DatabackupLogbook'];
                    } else {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = array();
                    }
                } else if ($scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'quarterly' || $scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'Quarterly') {
                    $created = date('Y-m-d', strtotime($dailyBackupDetail['DailyBackupDetail']['created']));
                    $currentDate = date('Y-m-d', strtotime($dailyBackupDetail['DailyBackupDetail']['created']));
                    $lastQuarter = date('Y-m-d');
                    $nextQuarter = date('Y-m-d');
                    $dateArray = array();
                    $i = 0;
                    while ($currentDate <= $lastQuarter) {
                        $nextQuarter = date('Y-m-d', strtotime('+3 month', strtotime($currentDate)));
                        $dateArray[$i]['currentDate'] = $currentDate;
                        $dateArray[$i]['nextQuarter'] = $nextQuarter;
                        $currentDate = $nextQuarter;
                        $i++;
                    }
                    $count = count($dateArray);
                    if (date('Y-m-d', strtotime($test['DatabackupLogbook']['created'])) >= $dateArray[$count - 1]['currentDate'] && date('Y-m-d', strtotime($test['DatabackupLogbook']['created'])) <= $dateArray[$count - 1]['nextQuarter']) {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = $test['DatabackupLogbook'];
                    } else {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = array();
                    }
                } else if ($scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'yearly' || $scheduleList[$dailyBackupDetail['DataBackUp']['schedule_id']] == 'Yearly') {

                    if (date('y', strtotime($test['DatabackupLogbook']['created'])) == date('y')) {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = $test['DatabackupLogbook'];
                    } else {
                        $dailyBackupDetails[$key]['DatabackupLogbook'] = array();
                    }
                }
            } if (count($listOfComp))
                $dailyBackupDetails[$key]['DatabackupLogbook']['make'] = $listOfComp['ListOfComputer']['make'];
        }

        $this->set('dailyBackupDetails', $dailyBackupDetails);
        $this->set('editId', $id);


        //get folder details :
			$folder = new Folder(ROOT);
			$folderSize = $folder->dirsize();
			$dbSize = $this->_getDbSize();
			$this->set(array('dbSize' => $this->_format_file_size($dbSize), 'folderSize' => $this->_format_file_size($folderSize), 'totalSize' => $this->_format_file_size($dbSize + $folderSize)));
    }

    public function _getDbSize() {

        $src = get_class_vars('DATABASE_CONFIG');
        $link = mysql_connect($src['default']['host'], $src['default']['login'], $src['default']['password']);
        mysql_select_db($src['default']['database']);
        $result = mysql_query("SHOW TABLE STATUS");
        $dbSize = 0;

        while ($row = mysql_fetch_array($result)) {

            $dbSize += $row["Data_length"] + $row["Index_length"];
        }
        return $dbSize;
    }

    public function purchase() {

        $this->loadModel('ListOfAcceptableSupplier');
        $acptSupp = $this->ListOfAcceptableSupplier->find('count', array('conditions' => array('ListOfAcceptableSupplier.soft_delete' => 0), 'recursive' => -1));
        $this->set('countAcptSupp', $acptSupp);

        $this->loadModel('SupplierRegistration');
        $supplierReg = $this->SupplierRegistration->find('count', array('conditions' => array('SupplierRegistration.publish' => 1, 'SupplierRegistration.soft_delete' => 0), 'recursive' => -1));
        $this->set('countSuppreg', $supplierReg);

        $this->loadModel('SupplierEvaluationReevaluation');
        $supplierEval = $this->SupplierEvaluationReevaluation->find('count', array('conditions' => array('SupplierEvaluationReevaluation.publish' => 1, 'SupplierEvaluationReevaluation.soft_delete' => 0), 'recursive' => -1));
        $this->set('countSupplierEval', $supplierEval);

        $this->loadModel('DeliveryChallan');
        $delChallan = $this->DeliveryChallan->find('count', array('conditions' => array('DeliveryChallan.publish' => 1, 'DeliveryChallan.soft_delete' => 0), 'recursive' => -1));
        $this->set('countDelChallan', $delChallan);

        $this->loadModel('PurchaseOrder');
        $purchaseOrder = $this->PurchaseOrder->find('count', array('conditions' => array('PurchaseOrder.publish' => 1, 'PurchaseOrder.soft_delete' => 0), 'recursive' => -1));
        $this->set('countPurchaseOrder', $purchaseOrder);

        $this->loadModel('SummeryOfSupplierEvaluation');
        $SumOfSuppEvals = $this->SummeryOfSupplierEvaluation->find('count', array('conditions' => array('SummeryOfSupplierEvaluation.publish' => 1, 'SummeryOfSupplierEvaluation.soft_delete' => 0), 'recursive' => -1));
        $this->set('countSumOfSuppEvals', $SumOfSuppEvals);
    }

    public function bd($startDate = null,$endDate = null, $duration = null) {

		if(!$startDate && !$endDate){
                    $startDate = date('Y-m-d',strtotime('-1 months'));
                    $endDate = date('Y-m-d');
		}
                
		if($this->request->params['named']['duration'] == 'all')$duration = true;		
        $customers = $this->requestAction('App/get_model_list/Customer/');
        $countCustomers = count($customers);
        $customerMeetings = $this->requestAction('App/get_model_list/CustomerMeeting/');
        $conditions = null;
        $onlyBranch = null;
        $onlyOwn = null;
        $this->loadModel('CustomerMeeting');
        if ($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)
            $onlyBranch = array('CustomerMeeting.branch_id' => $this->Session->read('User.branch_id'));
        if ($this->Session->read('User.is_view_all') == 0)
            $onlyOwn = array('CustomerMeeting.created_by' => $this->Session->read('User.id'));
        $conditions = array($onlyBranch, $onlyOwn);

        $countCustomerMeetings = $this->CustomerMeeting->find('count', array('conditions' => $conditions, 'group' => 'CustomerMeeting.followup_id'));
            if(!$countCustomerMeetings) $countCustomerMeetings = 0;
        $clientProposals = $this->requestAction('App/get_model_list/Proposal/');
        $countClientProposals = count($clientProposals);
        $proposalFollowups = $this->requestAction('App/get_model_list/ProposalFollowup/');
        $countProposalFollowups = count($proposalFollowups);
        $this->set(compact('countCustomers', 'countClients', 'countCustomerMeetings', 'countClientProposals', 'countProposalFollowups'));
        $this->set(array('resultMappings' => $this->result_mapping($startDate, $endDate, $duration)));
		
		$this->db_dashboard_counts($startDate,$endDate, $duration);
    }

    public function result_mapping($startDate = null, $endDate = null, $duration = null) {
        
		if($duration == 1){
			$orderDateRange = array();
			$proposalfollowupDateRange = array();
			$proposalDateRange = array();
			$customerDateRange = array();
		}else{		
        if (!$startDate && !$endDate) {
            $startDate = date('Y-m-d',strtotime('-1 months'));
            $endDate = date('Y-m-d');
        } else {
            $startDate = date('Y-m-d', strtotime($startDate));
            $endDate = date('Y-m-d', strtotime($endDate));
        }
        
			$orderDateRange = array('PurchaseOrder.order_date between ? and ?' => array(date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate))));
			$proposalfollowupDateRange = array('ProposalFollowup.followup_date between ? and ? ' => array($startDate,$endDate));
			$proposalDateRange = array('Proposal.proposal_date between ? and ? ' => array($startDate, $endDate));
			$customerDateRange = array('CustomerMeeting.meeting_date between ? and ? ' => array(date('Y-m-d', strtotime($startDate)), date('Y-m-d', strtotime($endDate))));
		
		}
        

        $this->loadModel('Customer');
        $this->loadModel('CustomerMeeting');
        $this->loadModel('PurchaseOrder');
        $this->loadModel('Proposal');
        $this->loadModel('ProposalFollowup');

        $allCustomers = $this->Customer->find('list', array('recursive' => 0, 'conditions' => array('Customer.publish' => 1, 'Customer.soft_delete' => 0)));
        foreach ($allCustomers as $key => $value):
            $i=0;
            $result['CustomerDetails']['name'] = $value;
            $CustomerMeetings = $this->CustomerMeeting->find('all', array('recursive' => 0,
                'conditions' => array($customerDateRange, 'CustomerMeeting.customer_id' => $key, 'CustomerMeeting.publish' => 1, 'CustomerMeeting.soft_delete' => 0),
                'fields' => array('CustomerMeeting.meeting_date', 'Employee.name'),'group' => 'CustomerMeeting.followup_id'
            ));
            $result['Number_of_meetings'] = count($CustomerMeetings);

            $proposals = $this->Proposal->find('list', array('recursive' => 0, 'conditions' => array($proposalDateRange, 'Proposal.customer_id' => $key, 'Proposal.publish' => 1, 'Proposal.soft_delete' => 0)));
            $result['Number_of_proposals'] = count($proposals);
            $proposalFollowUps = 0;
            foreach ($proposals as $pKey => $pValue):
                $proposalFollowUps = $this->ProposalFollowup->find('count', array('recursive' => 0, 'conditions' => array($proposalfollowupDateRange, 'ProposalFollowup.proposal_id' => $pKey, 'ProposalFollowup.publish' => 1, 'ProposalFollowup.soft_delete' => 0)));
            if($proposalFollowUps){
                $i+=$proposalFollowUps;
            }
            endforeach;
            $result['Number_of_proposal_followups'] = $i;
            $purchaseOrders = $this->PurchaseOrder->find('count', array('conditions' => array($orderDateRange, 'PurchaseOrder.customer_id' => $key, 'PurchaseOrder.publish' => 1, 'PurchaseOrder.soft_delete' => 0)));
            $result['Number_of_purchase_orders'] = $purchaseOrders;
            $results[] = $result;
        endforeach;
        $this->set(array('resultMappings' => $results));
        return $results;
    }

    public function readiness($month = null) {
        $month = $this->request->params['pass'][0];
        if($month == NULL)$month = date('Y-m');        
        $this->set('month',$month);
        $this->loadModel('MasterListOfFormatDepartment');
        $departments = $this->_get_department_list();

        $this->loadModel('User');
        $users = $this->User->find('list');
        $files = 0;
        $count = 0;
        foreach ($departments as $dKey => $dVal):
            $dresults = null;
            $masterListOfFormats = $this->MasterListOfFormatDepartment->find('all', array(
                'conditions' => array('MasterListOfFormatDepartment.publish' => 1, 'MasterListOfFormatDepartment.department_id' => $dKey, 'MasterListOfFormat.company_id' => $this->Session->read('User.company_id')),
                'fields' => array('MasterListOfFormat.id', 'MasterListOfFormat.title', 'SystemTable.system_name','MasterListOfFormat.document_details'),
                'recursive' => 0,
                    )
            );
            $dResults = array();
            $file = 0;
            foreach ($masterListOfFormats as $formats):     
            $result = 0;
				
                if(strlen($formats['MasterListOfFormat']['document_details']) != 0){
                    $files++;
                }
                $srcResult['id'] = $formats['MasterListOfFormat']['id'];
                $srcResult['title'] = $formats['MasterListOfFormat']['title'];
                // $srcResult['file'] = $file;
                $srcResult['chars'] = strlen($formats['MasterListOfFormat']['document_details']);
                $count = $count + 1;
                $dResults[] = $srcResult;
            endforeach;
            $results[$dVal] = $dResults;
        endforeach;
        
        $rediness = round(($files * 100) / $count);
        $this->set(compact('results', 'rediness'));        
        mkdir(Configure::read('MediaPath') . "/files/" . $this->Session->read('User.company_id') , 0777);
        mkdir(Configure::read('MediaPath') . "/files/" . $this->Session->read('User.company_id') . DS . date('Y-m',strtotime($month)) , 0777);
        $file = fopen(Configure::read('MediaPath') . "/files/" . $this->Session->read('User.company_id') . DS . date('Y-m',strtotime($month)) . DS . "/rediness.txt", "w") or die('can not open files');
        fwrite($file, $rediness);
        fclose($file);
        $this->ready($this->request->params['pass'][0]);
        $this->files($this->request->params['pass'][0]);
    }

    public function production() {
        $products = $this->requestAction('App/get_model_list/Product/');
        $countProducts = count($products);

        $productions = $this->requestAction('App/get_model_list/Production/');
        $countProductions = count($productions);

        $this->loadModel('Stock');
        $addToStocks = $this->Stock->find('count', array('conditions' => array('Stock.publish' => 1, 'Stock.soft_delete' => 0, 'Stock.type' => 0)));
        $addFromStocks = $this->Stock->find('count', array('conditions' => array('Stock.publish' => 1, 'Stock.soft_delete' => 0, 'Stock.type' => 1)));

        $this->set(compact('countMaterials', 'countProducts', 'countProductions', 'countStocks', 'addToStocks', 'addFromStocks'));

    }

    public function production_stocks(){
        $stocks = $this->get_productions_stock();
        $this->set(compact('stocks'));
    }
	
	public function db_dashboard_counts($start_date = null, $end_date = null, $duration = null){
		if($duration == 1){
			$date_conditions = array();
			$date_customer_conditions = array();
		}else{
			$date_conditions = array('Proposal.proposal_date > '=>$start_date,'Proposal.proposal_date <' => $end_date);
			$date_customer_conditions = array('Customer.customer_since_date > '=>$start_date,'Customer.customer_since_date <' => $end_date);
		}
		$this->loadModel('Proposal');	
		$this->loadModel('Customer');
		$pipeline_customers = $new_customers = $lost_customers = $pipeline_proposals = $won_proposals = $lost_proposals = 0;
		
		
		$pipeline_customers = $this->Customer->find('count',array('conditions'=>array($date_customer_conditions,'Customer.lead_type'=>0,'Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$new_customers = $this->Customer->find('count',array('conditions'=>array($date_customer_conditions,'Customer.lead_type'=>1,'Customer.customer_since_date > '=>date('Y-m-d',strtotime('-1 months')),'Customer.publish'=>1,'Customer.soft_delete'=>0)));
		$lost_customers = $this->Customer->find('count',array('conditions'=>array($date_customer_conditions,'Customer.lead_type'=>2,'Customer.publish'=>1,'Customer.soft_delete'=>0)));
		
		$pipeline_proposals = $this->Proposal->find('count',array('conditions'=>array($date_conditions,'Proposal.proposal_status'=>array(0,1,2,5),'Proposal.soft_delete'=>0)));
		$won_proposals = $this->Proposal->find('count',array('conditions'=>array($date_conditions,'Proposal.proposal_status'=>4,'Proposal.soft_delete'=>0)));
		$lost_proposals = $this->Proposal->find('count',array('conditions'=>array($date_conditions,'Proposal.proposal_status'=>3,'Proposal.soft_delete'=>0)));
		$proposals = $this->Proposal->find('all',array('conditions'=>array('Proposal.proposal_status' => array(1,2,5),'Proposal.soft_delete'=>0)));
		$previous = 0;
		
		$todays_followups = 0;
		foreach($proposals as $proposal):
			$followup_rule = json_decode($proposal['ProposalFollowupRule']['followup_sequence'],true);
			$proposal_sent_date = $proposal['Proposal']['proposal_sent_date'];
			foreach($followup_rule as $day => $followup_type):				
				$followups = $this->Proposal->ProposalFollowup->find('count',array(
							'recursive'=>-1,
							'conditions'=>array(
							'ProposalFollowup.proposal_id' => $proposal['Proposal']['id'],
							'OR'=>array(
							'ProposalFollowup.followup_day' => $day,
							'ProposalFollowup.followup_date BETWEEN ? AND ? ' =>  array(date('Y-m-d',strtotime($proposal_sent_date. '+ '. ($previous + 1). ' days')),date('Y-m-d',strtotime($proposal_sent_date. '+ '. $day. ' days'))),
							)
						)));
						if(!$followups){
							$days_difference = $this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a');
							if($this->_dateDifference(date('Y-m-d'),$proposal_sent_date, '%a') == $day-1 ){
								$todays_followups = $todays_followups + 1;
							}
						}
						$previous = $day;
			endforeach;
		endforeach;
		//$this->set('proposals', $newProposals);
		
		
		
		$not_sent = $this->Proposal->find('count',array('conditions'=>array('Proposal.proposal_status' => 0,'Proposal.soft_delete'=>0)));
		
		$this->set(array(
			'pipeline_customers'=>$pipeline_customers,
			'new_customers'=>$new_customers,
			'lost_customers'=>$lost_customers,
			'pipeline_proposals'=>$pipeline_proposals,
			'won_proposals'=>$won_proposals,
			'lost_proposals'=>$lost_proposals,
			'todays_followups' => $todays_followups,
			'not_sent' => $not_sent
			));
		
		
		if(!$start_date)$start_date = date('Y-m-1');
		if(!$end_date)$end_date = date('Y-m-d');
		$this->set('followup_details',$this->Bd->proposalFollowupStatusCount($start_date,$end_date,$duration));
	}
	
	public function _dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
		{
			$datetime1 = date_create($date_1);
			$datetime2 = date_create($date_2);
			
			$interval = date_diff($datetime1, $datetime2);
			
			return $interval->format($differenceFormat);
			
		}	


    /*public function ready(){
        $tables = array(
            'User','Material','Device','Customer','Task','Training',
            'Calibration','MaterialQualityCheck','DeviceMaintenance','SupplierRegistration',
            'PurchaseOrder','DeliveryChallan','SupplierEvaluationReevaluation','ListOfAcceptableSupplier',
            'Product','Objective','Process','ObjectiveMonitoring');
        
        
        foreach ($tables as $table) {
            $t = $this->loadModel($table);
            $count[$table]['count'] = $this->$table->find('count');
        }
        $this->set('readiness_count',$count);
        
    }*/


    public function ready($month = null){
        if($month == NULL)$month = date('Y-m');
        $branches = $this->_get_branch_list();
        $departments = $this->_get_department_list();
        
        foreach($branches as $key => $value){
            $users_branch['Branch'][$value] = $this->Branch->User->find('count',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.branch_id'=>$key)));
            $employees_branch['Branch'][$value] = $this->Branch->User->Employee->find('count',array('conditions'=>array('Employee.publish'=>1,'Employee.soft_delete'=>0,'Employee.branch_id'=>$key)));
            $users_branch['Count'] = count($departments);   
        }
        foreach($departments as $key => $value){
            $users_department[$value] = $this->Department->User->find('count',array('conditions'=>array('User.publish'=>1,'User.soft_delete'=>0,'User.department_id'=>$key)));            
        }

        $this->set(array('users_branch'=>$users_branch,'employees_branch'=>$employees_branch,'users_department'=>$users_department));


        $tables = array(
            'static'=>array(
                'User'=>8,
                'Material'=>10,
                'Device'=>5,
                'Process'=>10,
                'Product'=>2,
                'Objective'=>10,
                'SupplierRegistration'=>2,
                'Task'=>10),
            'recurring'=>array(
                'Customer'=>1,
                'Training'=>2,
                'TaskStatus'=>30,
                'Calibration'=>1,
                'MaterialQualityCheck'=>10,
                'DeviceMaintenance'=>1,
                'PurchaseOrder'=>10,
                'DeliveryChallan'=>10,
                'SupplierEvaluationReevaluation'=>1,
                'ListOfAcceptableSupplier'=>1,
                'ObjectiveMonitoring'=>10,
                'Approval'=> 70
            ));
        
        foreach ($tables as $type=>$table) {
            //static
            if($type == 'static'){
                foreach ($table as $key => $value) {
                    $this->loadModel($key);
                    $count[$type][$key]['required'] = $value;
                    $count[$type][$key]['count'] = $this->$key->find('count',array('conditions'=>array($key.'.publish'=>1,$key.'.soft_delete'=>0)));                    
                }
            }

            if($type == 'recurring'){
                foreach ($table as $key => $value) {
                    $this->loadModel($key);
                    $count[$type][$key]['required'] = $value;
                    $count[$type][$key]['count'] = $this->$key->find('count',array('conditions'=>array(
                        $key.'.created BETWEEN ? AND ? ' => array(date('Y-m-1 00:00:0000',strtotime($month)),date('Y-m-t 00:00:0000',strtotime($month))),
                        $key.'.publish'=>1,$key.'.soft_delete'=>0)));                    
                }
            }
            
        }
        $this->set('readiness_count',$count);        
    }    

    public function files($month = null){
        if($month == NULL)$month = date('Y-m');
        $common = array(
            'SystemTable', 'TrainerType', 'Schedule', 'Language', 'Department', 'CourseType' , 'CapaCategory',  'Capa Source' , 
            'SupplierCategory' , 'Timeline' , 'Company' , 'CompanyBenchmark', 'CourierRegister', 'CustomTemplate', 'DataType' , 
            'DepartmentBenchmark' , 'Evidence' , 'FireType' , 'InternalAuditDetail' , 'InternalAuditPlanDepartment' , 
            'MessageUserInbox' ,  'MessageUserSent' , 'MessageUserThrash', 'NotificationType', 'Report' , 'SoftwareType' , 
            'TrainingScheduleBranch' , 'TrainingScheduleDepartment', 'TrainingScheduleEmployee', 'TrainingSchedule' , 'TrainingType' ,
            'ProductMaterial' , 'EmployeeAppraisalQuestion' , 'MeetingAttendee' ,
            'Kra', 'CakeError', 'NotificationUser', 'History', 'UserSession', 'Page', 'Dashboard', 'Error', 'NotificationType', 
            'Benchmark', 'FileUpload', 'DataEntry', 'Help', 'MeetingBranch', 'MeetingDepartment', 'MeetingEmployee', 'MeetingTopic', 
            'Message', 'NotificationUser', 'PurchaseOrderDetail', 'NotificationUser', 'PurchaseOrderDetail', 'MasterListOfFormatBranch', 
            'MasterListOfFormatDepartment', 'MasterListOfFormatDistributor','SeverityType');

        $this->loadModel('SystemTable');
        $this->loadModel('FileUpload');
        
        $tables = $this->SystemTable->find('all',array('recursive'=>'-1'));
        foreach ($tables as $table) {
            $t = Inflector::Classify($table['SystemTable']['system_name']);
            if(!(in_array($t, $common))) {
                $this->loadModel(Inflector::Classify($table['SystemTable']['system_name'])); 
                $rec_count = 0;
                $rec_count = $this->$t->find('count');
                if($rec_count != 0){               
                    $count[$t]['count']= $rec_count;
                    //get files
                    $file_count = $this->FileUpload->find('count',array('conditions'=>array(
                        'FileUpload.created BETWEEN ? AND ? ' => array(date('Y-m-1 00:00:0000',strtotime($month)),date('Y-m-t 00:00:0000',strtotime($month))),
                        'FileUpload.file_status' => 1,
                        'FileUpload.system_table_id'=>$table['SystemTable']['id'])));
                    $count[$t]['files'] = $file_count;                    
                }
            }
        }
        $this->set('file_readiness_count',$count);
    }

    public function readiness_ajax($month = null){
        $month = $this->request->params['pass'][0];        
        if($month == NULL)$month = date('Y-m');        
        if (file_exists(Configure::read('MediaPath') . "files/" . $this->Session->read('User.company_id') .  DS . date('Y-m',strtotime($month)) . DS . "/rediness.txt")) {
             $file = new File(Configure::read('MediaPath') . "files/" . $this->Session->read('User.company_id')  . DS . date('Y-m',strtotime($month)) . DS .  "/rediness.txt");
             $readiness = $file->read();
        }else{
            $readiness = 0;
        }
        
        $this->set('readiness',$readiness);
        $this->set('month',$month);

    }


    public function raicm(){
        
    }

    public function readiness_graph(){
        
    }

    public function quality_dashboard_performance_chart(){
        
        if($this->request->data){
            $data = $this->prepare_dashbord_graphs(null,null,null,$this->request->data['PerformanceChart']['months']);
            if ($data) {
                if($data){
                foreach ($data as $month => $datas) {                 
                    $labels .= '"'.$month .'" ,';
                    if(in_array('CAPA', $this->request->data['PerformanceChart']['Sections'])){
                            $open_capas .= '"'.$datas['CAPA']['Open'] .'" ,';
                            $close_capas .= '"'.$datas['CAPA']['Closed'] .'" ,';
                    }

                    if(in_array('NC', $this->request->data['PerformanceChart']['Sections'])){
                        $open_ncs .= '"'.$datas['NC']['Open'] .'" ,';
                        $close_ncs .= '"'.$datas['NC']['Closed'] .'" ,';
                    }

                    
                    if(in_array('Complaints', $this->request->data['PerformanceChart']['Sections'])){
                        $complaints .= '"'.$datas['Complaints']['Complaints'] .'" ,';                       
                    }
                }
              }
            
                if($open_capas){
                    $open_capas = $open_capas;
                    $this->set('open_capas',$open_capas);
                    $close_capas = $close_capas;
                    $this->set('close_capas',$close_capas);
                }

                if($open_ncs){
                    $open_ncs = $open_ncs;
                    $this->set('open_ncs',$open_ncs);
                    $close_ncs = $close_ncs;
                    $this->set('close_ncs',$close_ncs);
                }
                
                if($complaints){
                    $complaints = $complaints;
                    $this->set('complaints',$complaints);                   
                }
                
                

                $label = rtrim($labels,',');
                $this->set('label',$label);
            }else{
                $this->set('no_reports',true);
            }               
        }else{
            $data = $this->prepare_dashbord_graphs(null,null,null,3);
            if ($data) {
              if($data){
                foreach ($data as $month => $datas) {
                    $labels .= '"'.$month .'" ,';
                   
                        $open_capas .= '"'.$datas['CAPA']['Open'] .'" ,';
                        $close_capas .= '"'.$datas['CAPA']['Closed'] .'" ,';
                        $complaints .= '"'.$datas['Complaints']['Complaints'] .'" ,';
                   
                }
              }
            
            
                if($open_capas){
                    $open_capas = $open_capas;
                    $this->set('open_capas',$open_capas);
                    $close_capas = $close_capas;
                    $this->set('close_capas',$close_capas);
                }

                if($open_ncs){
                    $open_ncs = $open_ncs;
                    $this->set('open_ncs',$open_ncs);
                    $close_ncs = $close_ncs;
                    $this->set('close_ncs',$close_ncs);
                }

                if($complaints){
                    $complaints = $complaints;
                    $this->set('complaints',$complaints);                   
                }

                $label = rtrim($labels,',');
                $this->set('label',$label);
            }else{
                $this->set('no_reports',true);
            }  
        }
        $this->layout = 'ajax';
    }

    public function prepare_dashbord_graphs( $from_date = null, $to_date = null, $result = array(), $months = null){
        
        if(!$from_date && !$to_date){
            if($months){
                $from_date = date('Y-m-1',strtotime('-'.$months.' months'));
                $to_date = date('Y-m-t');
            }           
        }

        while (strtotime($from_date) <= strtotime($to_date)) {

            //get capa
            $this->loadModel('CorrectivePreventiveAction');

            $conditions = array('CorrectivePreventiveAction.soft_delete'=>0,
                                    'CorrectivePreventiveAction.current_status'=>0,'CorrectivePreventiveAction.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
                    );
            $open_capa = $this->CorrectivePreventiveAction->find('count',array(
                'conditions'=> $conditions));
            
            $conditions = array('CorrectivePreventiveAction.soft_delete'=>0,
                                    'CorrectivePreventiveAction.current_status'=>1,'CorrectivePreventiveAction.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
                    );
            $closed_capa = $this->CorrectivePreventiveAction->find('count',array(
                'conditions'=>$conditions));

            $this->loadModel('NonConformingProductsMaterials');
            $conditions = array('NonConformingProductsMaterials.soft_delete'=>0,
                                    'NonConformingProductsMaterials.status != '=>1,'NonConformingProductsMaterials.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
                    );
            $open_ncs = $this->NonConformingProductsMaterials->find('count',array(
                'conditions'=> $conditions));
            
            $conditions = array('NonConformingProductsMaterials.soft_delete'=>0,
                                    'NonConformingProductsMaterials.status'=>1,'NonConformingProductsMaterials.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
                    );
            $close_ncs = $this->NonConformingProductsMaterials->find('count',array(
                'conditions'=>$conditions));            

            //get customer complaints
            $this->loadModel('CustomerComplaint');

            $conditions = array('CustomerComplaint.soft_delete'=>0,
                                    'CustomerComplaint.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
                    );
            $open_customer_complaints = $this->CustomerComplaint->find('count',array(
                'conditions'=> $conditions));
            
            $conditions = array('CustomerComplaint.soft_delete'=>0,
                                    'CustomerComplaint.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
                    );
            $closed_customer_complaints = $this->CustomerComplaint->find('count',array(
                'conditions'=>$conditions));
             
            
            $this->loadModel('CustomerComplaint');
            $conditions = array('CustomerComplaint.soft_delete'=>0,
                                    'CustomerComplaint.publish'=>1,'CustomerComplaint.created BETWEEN ? AND ?' =>array(date('Y-m-1 00:00:00',strtotime($from_date)),date('Y-m-t 00:00:00',strtotime($from_date)))
                    );
            $complaints = $this->CustomerComplaint->find('count',array(
                'conditions'=>$conditions));
            // final array
            $result[date('m-Y',strtotime($from_date))] = array(
                'CAPA'=>array('Open'=>$open_capa,'Closed'=>$closed_capa),
                'NC'=>array('Open'=>$open_ncs,'Closed'=>$close_ncs),
                'Complaints'=>array('Complaints'=>$complaints)
                ); 
            $from_date = date("Y-m-d", strtotime("+1 month", strtotime($from_date)));
        }
        
        $result[date('m-Y',strtotime($from_date))] = array(
                'CAPA'=>array('Open'=>0,'Closed'=>0),
                'NC'=>array('Open'=>0,'Closed'=>0),
                'Complaints'=>array('Complaints'=>0)
                ); 
   
                return $result;
        
    }

    public function audit_cal($type = null,$year = null){
        if(!$this->request->params['named']['year'])$this->request->params['named']['year']= date('Y');
        if(!$this->request->params['named']['type'])$this->request->params['named']['type'] = 'department';

        
        $this->loadModel('InternalAuditPlanDepartment');
        if($this->request->params['named']['type'] == 'branch'){
            $source = $this->_get_branch_list();
            $field = 'branch_id';
        }
        if($this->request->params['named']['type'] == 'department'){
            $source = $this->_get_department_list();
            $field = 'department_id';
        }
        if($this->request->params['named']['type'] == 'auditor'){
            $this->loadModel('ListOfTrainedInternalAuditor');
            $source = $this->ListOfTrainedInternalAuditor->find('list',array('conditions'=>array('ListOfTrainedInternalAuditor.name !=' => 'Internal Auditor')));
            $field = 'list_of_trained_internal_auditor_id';
        }
        if($this->request->params['named']['type'] == 'employee'){
            $source = $this->_get_employee_list();   
            $field = 'employee_id';
        }

        if($this->request->params['named']['audit_type_masters']){
            $con = array('InternalAuditPlan.audit_type_master_id'=>$this->request->params['named']['audit_type_masters']);
        }

        if(isset($this->request->params['named']['ie']) && $this->request->params['named']['ie'] != 0){
            $cont = array('InternalAuditPlan.plan_type'=>$this->request->params['named']['ie']);   
        }
        
        for($i = 1; $i <= 12; $i++){
            $audit_number = 0;
            foreach ($source as $key => $value) {
                $audit_number = 0;
                $audit_number = $this->InternalAuditPlanDepartment->find('count',array(
                    // 'group'=>array('InternalAuditPlan.id'),
                    'conditions'=>array('InternalAuditPlanDepartment.'.$field=>$key,
                        'InternalAuditPlanDepartment.publish'=>1,
                        'InternalAuditPlanDepartment.soft_delete'=>0,
                        'MONTH(InternalAuditPlanDepartment.start_time)' => $i,
                        'YEAR(InternalAuditPlanDepartment.start_time)' => $this->request->params['named']['year'],
                        $con,
                    )));
                $data[$value][] = array($i,$audit_number);

            }            
        }
        $this->loadModel('AuditTypeMaster');
        $audit_type_masters = $this->AuditTypeMaster->find('list',array('conditions'=>array('AuditTypeMaster.publish'=>1,'AuditTypeMaster.soft_delete'=>0)));
        $this->set('data',$data);
        $this->set('year',$this->request->params['named']['year']);
        $this->set('audit_type_masters',$audit_type_masters);
    }

    public function env(){
        $this->loadModel('EnvActivity');
        $envActivities = $this->EnvActivity->find('count',array('condition'=>array('EnvActivity.publish'=>1,'EnvActivity.soft_delete'=>0)));

        $this->loadModel('EvaluationCriteria');
        $evaluationCriterias = $this->EvaluationCriteria->find('count',array('conditions'=>array('EvaluationCriteria.publish'=>1,'EvaluationCriteria.soft_delete'=>0)));
        
        $this->loadModel('EnvIdentification');
        $envIdentifications = $this->EnvIdentification->find('count',array('conditions'=>array('EnvIdentification.publish'=>1,'EnvIdentification.soft_delete'=>0)));

        $this->loadModel('EnvEvaluation');
        $envEvaluations = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));

        $this->loadModel('EnvironmentQuestionnaireCategory');
        $environmentQuestionnaireCategories = $this->EnvironmentQuestionnaireCategory->find('count',array('conditions'=>array('EnvironmentQuestionnaireCategory.publish'=>1,'EnvironmentQuestionnaireCategory.soft_delete'=>0)));

        $this->loadModel('EnvironmentChecklist');
        $environmentChecklists = $this->EnvironmentChecklist->find('count',array('conditions'=>array('EnvironmentChecklist.publish'=>1,'EnvironmentChecklist.soft_delete'=>0)));

        $this->set(compact('envActivities','evaluationCriterias','envIdentifications','envEvaluations','environmentQuestionnaireCategories','environmentChecklists'));
        
        
        $this->loadModel('EnvEvaluationScore');
        $evaluationCriterias = $this->EvaluationCriteria->find('list',array('conditions'=>array('EvaluationCriteria.publish'=>1,'EvaluationCriteria.soft_delete'=>0)));{
            foreach ($evaluationCriterias as $key => $value) {
                $scors[$value] = $this->EnvEvaluationScore->find('count',array('conditions'=>array('EnvEvaluationScore.evaluation_criteria_id'=>$key),'group'=>array('EnvEvaluationScore.env_activity_id')));    
                
            }
            
        $this->set('scors',$scors);   
        }
        // exit;
        $this->loadModel('EnvEvaluation');
        $envEvaluation[10] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>10, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        $envEvaluation[20] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>20, 'EnvEvaluation.score >'=>10, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        $envEvaluation[30] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>30, 'EnvEvaluation.score >'=>20, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        $envEvaluation[40] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>40, 'EnvEvaluation.score >'=>30, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        $envEvaluation[50] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>50, 'EnvEvaluation.score >'=>40, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        $envEvaluation[60] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>60, 'EnvEvaluation.score >'=>50, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        $envEvaluation[70] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>70, 'EnvEvaluation.score >'=>60, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        $envEvaluation[80] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>80, 'EnvEvaluation.score >'=>70, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        $envEvaluation[90] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>90, 'EnvEvaluation.score >'=>80, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        $envEvaluation[100] = $this->EnvEvaluation->find('count',array('conditions'=>array('EnvEvaluation.score <='=>100, 'EnvEvaluation.score >'=>90, 'EnvEvaluation.publish'=>1,'EnvEvaluation.soft_delete'=>0)));
        // // $evals[]
        // $this->set()
        
        
        $this->set('envEvaluation',$envEvaluation);

        $this->loadModel('CorrectivePreventiveAction');
        $capas = $this->CorrectivePreventiveAction->find('all',array(
            'fields'=>array('CorrectivePreventiveAction.id','CorrectivePreventiveAction.name','CorrectivePreventiveAction.number','CorrectivePreventiveAction.capa_source_id','CorrectivePreventiveAction.capa_category_id','CorrectivePreventiveAction.capa_type','CorrectivePreventiveAction.current_status','CorrectivePreventiveAction.root_cause_analysis_required','CorrectivePreventiveAction.env_identification_id','CorrectivePreventiveAction.env_activity_id',
                'EnvActivity.id','EnvActivity.title','EnvIdentification.id','EnvIdentification.title','CapaSource.id','CapaSource.name','CapaCategory.id','CapaCategory.name'
                ),
            'recursive'=>0,
            'conditions'=>array(
                'OR'=>array('CorrectivePreventiveAction.env_activity_id !='=>NULL,'CorrectivePreventiveAction.env_activity_id !='=>-1),
                'OR'=>array('CorrectivePreventiveAction.env_identification_id !='=>NULL,'CorrectivePreventiveAction.env_identification_id !='=>-1),
            )));

        $this->set('capas',$capas);


    }

    public function get_productions_stock(){
        $materials = $this->requestAction('App/get_model_list/Material/');
        $countMaterials = count($materials);
        foreach ($materials as $key => $value) {
            $stocks[] = $this->requestAction(array('controller'=>'stocks','action'=>'get_material_details',$key));
        }        
        return $stocks;
    }
        
}


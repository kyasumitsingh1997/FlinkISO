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
 * @copyright Copyright (c) Cake Software Foundation, Inc.
 * (http://cakefoundation.org)
 * @link http://cakephp.org CakePHP(tm) Project
 * @package app.Controller
 * @since CakePHP(tm) v 0.2.9
 * @license http://www.opensource.org/licenses/mit-license.php MIT License */
  App::uses('Controller', 'Controller');
  App::uses('Folder', 'Utility');
  App::uses('File', 'Utility');
  /** adding new PDF plug in **/
  Configure::write('CakePdf', array(
    'engine' => 'CakePdf.WkHtmlToPdf', 
    'binary' => '/usr/local/bin/wkhtmltopdf',
    'crypto'=>'CakePdf.Pdftk',      
    'options' => array(
      'print-media-type' => false,
      'outline' => false,
      'dpi' => 96,
      'header-html' => Router::url('/', true) .'files/pdf_header.html',
      'footer-center'     => 'Page [page] of [toPage]',
      'footer-right'     => 'Confidential Document. All rights reserved.',
      'footer-font-size'     => '6',
      'footer-line'=>true,
      'header-line'=>true,
      'header-font-name'=>'Trebuchet MS',
      'footer-font-name'=>'Trebuchet MS',
      ),
    'margin' => array(
      'bottom' => 10,
      'left' => 10,
      'right' => 10,
      'top' => 25
      ),    
    'title'=>'Generated via FlinkISO',
    'orientation' => 'portrait',
    'download' => true,        
    )
  );
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller */
App::import('Sanitize');


class AppController extends Controller {

  public $components = array(
    'RequestHandler',
    'Session',
    'AjaxMultiUpload.Upload',
    'Gzip.Gzip'
    );
  public $helpers = array(
    'Js',
    'Session',
    'Paginator',
    'Tinymce',
    'List',
    'Time'
    );


  public $user = null;
  public $userids = null;
  public $message_count = null;
  public $notification_count = null;
  public $branchIDYes = false;




  public function beforeFilter() {
    $_SESSION['show_all_formats'] = null;
    $lang = $this->Session->read('SessionLanguageCode');


    if(isset($lang))
    {
      Configure::write('Config.language', 
        $this->Session->read('SessionLanguageCode'));
    }else{
      $this->loadModel('User');
      $user_data = $this->User->find('first', array(
        'conditions' => array(
          'User.id' => 
          $this->Session->read('User.id')  ),
        'recursive' => - 1
        ));

      $this->loadModel('Language');
      $language_data = array();
      if($user_data){
        $language_data = $this->Language->find('first', array(
          'conditions' => array(
            'Language.id' => $user_data['User']['language_id']  ),
          'recursive' => - 1
          ));
        $this->Session->write('SessionLanguageCode',null);
        $this->Session->write('SessionLanguage',null);
        if($language_data){
          if($language_data['Language']['short_code']){
            Configure::write('Config.language', $language_data['Language']['short_code']);
          }else{
            Configure::write('Config.language', 'eng');
          }
        }
      }
    }

    $track = null;
    if (isset($user)) {
      if ($this->Session->read('TANDC') == 1 or $user['User']['agree'] == 1) {
        if ($this->action != 'terms_and_conditions' && $this->action != 'logout') {
          $this->redirect(array(
            'controller' => 'users',
            'action' => 'terms_and_conditions'
            ));
        }
      }
    } else {
      if ($this->Session->read('TANDC') == 1) {
        if ($this->action != 'terms_and_conditions' &&  $this->action != 'logout' &&  $this->action != 'language_details' ) {
          $this->redirect(array(
            'controller' => 'users',
            'action' => 'terms_and_conditions'
            ));
        }
      }
    }


    if (
      $this->Session->read('User.is_mr') == 0 && 
      $this->action != 'get_history' && 
      $this->action != 'get_task' &&
      $this->action != 'get_project_task' &&
      $this->action != 'get_project_task' &&
      $this->action != 'capa_ratings' &&
      $this->action != 'get_list' &&
      $this->action != 'get_process_task' &&
      $this->action != 'cc_task' &&
      $this->action != 'get_cc_task' &&
      $this->action != 'get_task_name' &&
      $this->action != 'resource_check' &&
      $this->action != 'get_next_process' &&
      $this->action != 'get_employee' &&
      $this->action != 'personal_admin' &&
      $this->action != 'start_stop'
    )
    {
      $this->_check_access();
    }
      
    
    if (
     $this->action != 'display_notifications' &&
     $this->action != 'timeline' &&
     $this->action != 'data_json' &&
     Inflector::Classify($this->name) != 'App' &&
     Inflector::Classify($this->name) != 'CakeError' &&
     $this->action != 'capa_assigned' &&
     $this->action != 'get_customer_complaints' &&
     $this->action != 'get_next_calibration' &&
     $this->action != 'get_material_qc_required' &&
     $this->action != 'get_delivered_material_qc' &&
     $this->action != 'get_device_maintainance' &&
     $this->action != 'dashboard_files' &&
     $this->action != 'get_task' &&
     $this->action != 'get_list' &&
     $this->action != 'dashboard_files' &&
     $this->request->params['controller'] != 'user_sessions' &&
     ($this->request->params['controller'] != 'master_list_of_format_departments' && $this->action != 'listing')
     )
    {
     if($this->Session->read('User') && isset($this->request->params) && ($this->request['controller'] != 'file_uploads' && $this->action != 'get_department_employee') && $this->action !='mlfuserlist' && $this->action !='files'){
      $this->_track_history();
     }
   }


   $this->set('controllerName', $this->request['controller']);
   
   if ($this->request->is('post') || $this->request->is('put')) {
    // trimming the all post values - Apply a user function recursively to every member of an array
    array_walk_recursive($this->request->data, function(&$val, $key) {
      $val = ltrim(rtrim($val));
    });
    $this->request->data = $this->request->data;
    if($this->action != 'login'){
      if (!isset($this->request->data[$this->modelClass]['publish']) && isset($this->request->data['Approval']['user_id']) && $this->request->data['Approval']['user_id'] != -1) {
        $this->request->data[$this->modelClass]['publish'] = 0;
      }
    }
  }
  if ($this->action == 'publish_record' || $this->action == 'delete') {
    $model = Inflector::classify($this->request->params['controller']);
    $this->loadModel($model);
    if($model != 'MessageUserThrash' && $model != 'MessageUserSent' && $model != 'Message' && $model != 'MessageUserInbox' && $model != 'UserSession'){
      $recordStatus = $this->$model->find('first',array('fields'=>array($model.'.record_status'),'conditions'=>array($model.'.id'=>$this->request->params['pass'][0])));
      if($recordStatus[$model]['record_status'] == 1){
       $this->Session->setFlash(__('Access Denied 1'));
       if ($this->params->action != 'access_denied')
        $this->redirect(array(
          'controller' => 'users',
          'action' => 'access_denied',$this->action,1
          ));
    }
  }
}
if($this->request->controller != 'file_uploads'){
    $model = $this->modelClass;
    if($model != 'App'){
      $belongs = $this->$model->belongsTo;
      $hasMany = $this->$model->hasMany;
      
          if($belongs){
              $belongs = array_keys($belongs);
              $remove = array('Company','CreatedBy','ModifiedBy','StatusUserId','Company','DepartmentIds','BranchIds','SystemTable','Notification','ApprovedBy','PreparedBy','SystemTable','MasterListOfFormat','History','ReportedBy','PersonResponsible','PasswordSetting','PasswordSettingManager','ParentDocumentId','Division');
              $belongs = array_diff($belongs,$remove); 

              if($model == 'PurchaseOrder'){
                $belongs[] = 'taxes';
              }
              if($model == 'ListOfComputer'){
                $belongs[] = 'list_of_softwares';
              }

              $this->set('belongLinks',$belongs);              
          }else{
              $this->set('belongLinks',null);
          }

          if($hasMany){
              $hasMany = array_keys($hasMany);
              $remove = array('Company','CreatedBy','ModifiedBy','StatusUserId','Company','DepartmentIds','BranchIds','SystemTable','Notification','ApprovedBy','PreparedBy','SystemTable','MasterListOfFormat','History','ReportedBy','PersonResponsible','PasswordSetting','PasswordSettingManager','ParentDocumentId','Division','ListOfComputerListOfSoftware');
              $hasMany = array_diff($hasMany,$remove); 

              // if($model == 'PurchaseOrder'){
              //   $belongs[] = 'taxes';
              // }
              // if($model == 'ListOfComputer'){
              //   $belongs[] = 'list_of_softwares';
              // }

              $this->set('hasMany',$hasMany);              
          }else{
              $this->set('hasMany',null);
          }            
      }else{
          $this->set('belongLinks',null);
      }
    }
}

public function _check_access() {

  if(
    ($this->request->params['controller'] != 'users' && $this->action != 'personal_admin') && 
    $this->params->action != 'dashboard_files' && $this->Session->read('User')
    ){

    $newData = null;
    $userAccess = null;
    $this->loadModel('User');
    $this->User->recursive = 0;
    $userAccess = $this->User->find('first', array(
      'conditions' => array(
        'User.id' => $this->Session->read('User.id')
        ),
      'fields' => array(
        'User.user_access'
        )
      ));
    if ($userAccess)
      $newData = json_decode($userAccess['User']['user_access'], true);
    if ($this->params->controller == 'updates'){
      $this->Session->setFlash(__('Access Denied 2'), 'default', array('class'=>'alert-danger'));
      $this->redirect(array('controller' => 'users','action' => 'access_denied',$this->action,2));

    }

    if (!$newData) {            
     if ( 
          (($this->params->controller != 'users') && (($this->action != 'terms_and_conditions') || 
          ($this->action != 'register') || ($this->params->action != 'check_email') || 
          ($this->params->action != 'login') || 
          ($this->params->action != 'logout') || 
          ($this->params->controller != 'reset_password') || 
          ($this->params->controller != 'opt_check')  || 
          ($this->params->action != 'check_registration') ||
          ($this->action != 'appraisal_answers') || 
          ($this->action != 'smtp_details'))) && 
          ($this->params->controller != 'password_settings') ) 
      {      
        if (
            $this->params->action != 'access_denied' && 
            $this->params->action != 'testemail' && 
            $this->params->action != 'inbox_dashboard' && 
            $this->params->action != 'inbox_dashboard' && 
            $this->params->action != 'display_notifications' &&    
            $this->params->action != 'get_pre_process' &&    
            $this->params->action != 'language_details')
        {


       if($this->Session->read('User') == null){
         $this->Session->setFlash(__('Please login to continue'), 'default', array('class'=>'alert-danger'));
         $this->redirect(array('controller' => 'users','action' => 'login'));
       }else{
        // allow all project file actions
        if($this->request->controller != 'project_files' && $this->request->controller != 'file_processes' && $this->request->controller != 'projects'){
          $this->Session->setFlash(__('Access Denied 3'), 'default', array('class'=>'alert-danger'));
          $this->redirect(array('controller' => 'users','action' => 'access_denied',$this->action,3)); 
        }
         
       }
     }
   }

 }
 if ($newData) {  

  foreach ($newData['user_access'] as $model => $actionValue):
    if (strtolower(str_replace("_", "", strtolower($model))) == strtolower(str_replace("_", "", $this->params->controller))) {
      if ($this->params->action != '' || $this->params->action != 'timeline') {
        if (isset($actionValue[$this->params->action]) != true || $actionValue[$this->params->action] != 1) {
          $allow_array = array(
            'login','expired','add_cron_jobs','register','check_email','logout','dashboard','smtp_details','terms_and_conditions',
            'check_registration','reset_password','opt_check','appraisal_answers','edit','view','approve','meeting_view','get_material_qc_required',
            'get_delivered_material_qc','capa_assigned','get_customer_complaints','get_next_calibration','capa_ratings','get_device_maintainance','inbox_dashboard','pm_dashboard',
            'team_board','employee_files','start_stop'
        );

          // Configure::write('debug',1);
          // debug($this->params->action);
          // debug(in_array($this->params->action, $allow_array));
          // exit;
                    // if (
                    //   ($this->params->action != 'login') && 
                    //   ($this->params->action != 'expired') && 
                    //   ($this->params->action != 'add_cron_jobs') && 
                    //   ($this->params->action != 'register') && 
                    //   ($this->params->action != 'check_email') && 
                    //   ($this->params->action != 'logout') && 
                    //   ($this->params->action != 'dashboard') && 
                    //   ($this->params->action != 'smtp_details') && 
                    //   ($this->params->action != 'terms_and_conditions') && 
                    //   ($this->params->action != 'check_registration') && 
                    //   ($this->params->action != 'reset_password') && 
                    //   ($this->params->action != 'opt_check') && 
                    //   ($this->params->action != 'appraisal_answers') && 
                    //   ($this->params->action != 'edit') && 
                    //   ($this->params->action != 'view') && 
                    //   ($this->params->action != 'approve') && 
                    //   $this->params->action != 'meeting_view' && 
                    //   $this->params->action != 'get_material_qc_required' && 
                    //   $this->params->action != 'get_delivered_material_qc' && 
                    //   $this->params->action != 'capa_assigned' && 
                    //   $this->params->action != 'get_customer_complaints' && 
                    //   $this->params->action != 'get_next_calibration' && 
                    //   $this->params->action != 'capa_ratings' && 
                    //   $this->params->action != 'get_device_maintainance' &&
                    //   $this->params->action != 'inbox_dashboard' &&
                    //   $this->params->action != 'pm_dashboard'
                    // ) 
          if(in_array($this->params->action, $allow_array) == false)
                    {

                                $this->Session->setFlash(__('Access Denied 4' . $this->action ));
                                if ($this->params->action != 'access_denied')
                                    $this->redirect(array(
                                        'controller' => 'users',
                                        'action' => 'access_denied',$this->action,4
                                    ));
                            }
                            else {
                                if ($this->params->controller == 'meetings' && $this->params->action == 'meeting_view') {
                                    $this->loadModel('MeetingEmployee');
                                    $invite = $this->MeetingEmployee->find('first', array(
                                        'conditions' => array(
                                            'MeetingEmployee.meeting_id' => $this->params['pass'][0],
                                            'MeetingEmployee.employee_id' => $this->Session->read('User.employee_id')
                                        ),
                                        'recursive' => - 1
                                    ));

              if ($invite == 0) {
                $this->Session->setFlash(__('Access Denied 5'));
                if ($this->params->action != 'access_denied')
                  $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'access_denied',$this->action,5
                    ));
              }
              else {
                break;
              }
            } else if ($this->params->controller == 'notifications') {
              $this->loadModel('NotificationUser');
              $invite = $this->NotificationUser->find('first', array(
                'conditions' => array(
                  'NotificationUser.notification_id' => $this->params['pass'][0],
                  'NotificationUser.employee_id' => $this->Session->read('User.employee_id')
                  ),
                'recursive' => - 1
                ));

              if ($invite == 0) {
                $this->Session->setFlash(__('Access Denied 6'));
                if ($this->params->action != 'access_denied')
                  $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'access_denied',$this->action,6
                    ));
              }
              else {
                break;
              }
            }  else
            
            if ($this->params->controller == 'corrective_preventive_actions' && $this->params->action != 'approve' && $this->params->action != 'approve') {
              $this->loadModel('CorrectivePreventiveAction');
              if($this->params->action == 'capa_assigned'){
                                     //   $condition =  array('CorrectivePreventiveAction.assigned_to' => $this->Session->read('User.employee_id'));
              }else{
                $condition = array( 'CorrectivePreventiveAction.id' => $this->params['pass'][0],
                                    //        'CorrectivePreventiveAction.assigned_to' => $this->Session->read('User.employee_id')
                  );
              }
              $capa = $this->CorrectivePreventiveAction->find('first', array(
                'conditions' => array(
                 $condition
                 ),
                'recursive' => - 1
                ));
              if ($this->_count($capa) == 0) {
                if($this->params->action == 'capa_assigned') exit();
                if($this->action = 'approve'){
                 $this->loadModel('Approval');
                 $count = $this->Approval->find('count',array('conditions'=>array( 'Approval.record' => $this->params['pass'][0],
                   'Approval.user_id' => $this->Session->read('User.id'),
                   'Approval.record_status' => 1
                   )));
                 if($count)
                  break;
              }
              if($this->params->action != 'approve'){
                  $this->Session->setFlash(__('Access Denied 7'));
              if ($this->params->action != 'access_denied')
                $this->redirect(array(
                  'controller' => 'users',
                  'action' => 'access_denied',$this->action,7
                  ));
            }
            else {
              break;
            }
          } else
          if ($this->params->controller == 'customer_complaints') {
            $this->loadModel('CustomerComplaint');
            if($this->params->action == 'get_customer_complaints'){
              $condition =  array('CustomerComplaint.employee_id' => $this->Session->read('User.employee_id'));
            }else{
              $condition = array(
                'CustomerComplaint.id' => $this->params['pass'][0],
                'CustomerComplaint.employee_id' => $this->Session->read('User.employee_id'),

                );
            }
            $custComplaint = $this->CustomerComplaint->find('first', array(
              'conditions' => $condition,
              'recursive' => - 1
              ));
            if ($this->_count($custComplaint) == 0) {
              if($this->params->action == 'get_customer_complaints') break;
              if($this->action = 'approve'){
               $this->loadModel('Approval');
               $count = $this->Approval->find('count',array('conditions'=>array( 'Approval.record' => $this->params['pass'][0],
                'Approval.user_id' => $this->Session->read('User.id'),
                'Approval.record_status' => 1
                )));
               if($count)
                 break;
             }
             $this->Session->setFlash(__('Access Denied 8'));
             if ($this->params->action != 'access_denied')
              $this->redirect(array(
                'controller' => 'users',
                'action' => 'access_denied',$this->action,8
                ));
          }
          else {
            break;
          }
        } elseif ($this->params->controller == 'materials' && $this->params->action == 'get_material_qc_required') {
          break;
        } elseif ($this->params->controller == 'delivery_challans' && $this->params->action == 'get_delivered_material_qc') {
          break;
        } elseif ($this->params->controller == 'calibrations' && $this->params->action == 'get_next_calibration') {
          break;
        } elseif ($this->params->controller == 'device_maintenances' && $this->params->action == 'get_device_maintainance') {
          break;
        } elseif ($this->params->action == 'approve') {
          $this->loadModel('Approval');
          $approval = $this->Approval->find('first', array(
            'conditions' => array(
              'Approval.id' => $this->params['pass'][1],
              'Approval.user_id' => $this->Session->read('User.id'),
              'Approval.record_status' => 1
              ),
            'recursive' => - 1
            ));
          if ($this->_count($approval) == 0) {
            $this->Session->setFlash(__('Access Denied 9'));
            if ($this->params->action != 'access_denied')
              $this->redirect(array(
                'controller' => 'users',
                'action' => 'access_denied',$this->action,9
                ));
          }
          else {
            break;
          }
        } else {
         if(isset($this->params['pass'][0])){
          $this->loadModel('Approval');
          $approvals = $this->Approval->find('all', array('conditions' => array('Approval.controller_name' => $this->params->controller, 'Approval.record' => $this->params['pass'][0], 'Approval.user_id' => $this->Session->read('User.id')), 'recursive' => - 1));

          if ($this->_count($approvals) == 0) {
           $this->Session->setFlash(__('Access Denied 10'));
           if ($this->params->action != 'access_denied')
             $this->redirect(array('controller' => 'users', 'action' => 'access_denied',$this->action));
         }

                                   }else if ($this->params->action != 'access_denied'){
                                       $this->redirect(array(
                                           'controller' => 'users',
                                           'action' => 'access_denied',$this->action,10
                                       ));
                                   }
                                }
                            }
                        }
                    }
                }
              }
            endforeach;
        }
        }
        }

public function get_department_employee($departmentId = null)
{
  $this->loadModel('Employee');
  $this->loadModel('User');
  $deptEmployees = $this->User->find('list', array('conditions' => array('User.soft_delete' => 0,'User.publish' => 1,'User.department_id' => $departmentId),'fields'=>array('Employee.id','Employee.name'),'recursive'=>0));
  $this->set('deptEmployees',$deptEmployees);
  $this->render('/Elements/department');
  $this->layout = 'ajax';

}


/**
 * request handling by - TGS
 *
 */
/**
 * _check_request method
 *
 * @return void
 */


public function _check_request()
  {
      $onlyBranch = null;
      $onlyOwn = null;
      $con1 = null;
      $con2 = null;
      $modelName = $this->modelClass;
      if($this->Session->read('User.is_mr') == 0){        
        $onlyBranch = array('or'=>array($modelName.'.branchid'=>json_decode($this->Session->read('User.assigned_branches'),false)));
      }
      // if($this->Session->read('User.is_view_all') == 0 && $this->Session->read('User.assigned_branches' == null)){
      if($this->Session->read('User.is_view_all') == 0){
        $onlyOwn = array($modelName.'.created_by'=>$this->Session->read('User.id'));
      }
      
      if($this->request->params['named'])
      {
          if($this->request->params['named']['published']==null)$con1 = null ; else $con1 = array($modelName.'.publish'=>$this->request->params['named']['published']);
          if($this->request->params['named']['soft_delete']==null)$con2 = null ; else $con2 = array($modelName.'.soft_delete'=>$this->request->params['named']['soft_delete']);
          if($this->request->params['named']['soft_delete']==null)$conditions=array($onlyBranch,$onlyOwn,$con1,$modelName.'.soft_delete'=>0);
          else $conditions=array($onlyBranch,$onlyOwn,$con1,$con2);

      }
      else
      {
             $conditions=array($onlyBranch,$onlyOwn,$modelName.'.publish'=>1,$modelName.'.soft_delete'=>0);  
      }
      if(
          ($this->request['controller'] == 'internal_audit_plans' && $this->action == 'index') &&
          ($this->request['controller'] == 'objectives' && $this->action == 'index')

        ){
        return null;
      }else{
        return $conditions;
      }
        
}

public function beforeRender() {
  // Configure::write('debug',1);
  // debug($this->action);
  // debug($this->request->controller);
  $skiparray = array('projects','project_files');
  // debug(in_array($this->controller, $skiparray));
  // exit;
  if(in_array($this->request->controller, $skiparray) == false){
    if($this->action == 'edit' && $this->action != 'pm_dashboard' && !in_array($this->controller, $skiparray)){
      if($this->Session->read('User.is_view_all') == 0){
        if($this->request->data[$this->modelClass]['created_by'] != $this->Session->read('User.id')){
          $this->redirect(array('controller' => 'users','action' => 'access_denied',$this->action,100));
        }      
      }  
    }
  }
  

  if($this->name == 'CakeError') {
        $this->layout = 'login';
        return true;
  }
  if($this->Session->read('User') != null){
   $this->loadModel('MasterListOfFormat');
   $formatCount = $this->MasterListOfFormat->find('count', array('conditions'=>array('MasterListOfFormat.company_id'=>$this->Session->read('User.company_id'))));
   if($formatCount == 0){
     if(
       $this->action != 'register'
       && $this->action != 'terms_and_conditions'
       && $this->action != 'liscence_key'
       && $this->action != 'add_formats'
       && $this->action != 'display_notifications'
       && $this->action != 'logout'
       && $this->action != 'welcome')
     {
       
     }
   }else if($this->action == 'add_formats'){            
     $this->redirect(array('controller' => 'users','action' => 'dashboard'));
   }
 }

 $this->set('branchIDYes',false);
 if($this->action == 'add_ajax'){
   if($this->request->is('ajax') == false){
    $this->Session->setFlash('Invalid Request');
    $this->redirect(array('controller'=>'users','action'=>'dashboard'));
  }
}
if (!$this->Session->read('User.id') && $this->action != 'login' && $this->action != 'reset_password'  && $this->action != 'opt_check' && $this->action != 'check_registration' && $this->action != 'self_appraisals' && $this->action != 'register'  && $this->action != 'activate' && $this->action != 'check_email' && ($this->action != 'smtp_details')) {

 $this->Session->setFlash(__('Please sign in to access the application'), 'default', array('class'=>'alert-danger'));
 $this->redirect(array(
  'controller' => 'users',
  'action' => 'login'
  ));
}



$this->_display_help();
$this->_maker_checker();
$this->_get_branch_list();
$this->_get_department_list();
$this->_get_employee_list();
$this->_get_user_list();
$this->set('approversList', $this->_get_approvers_list());
        $track_history= isset($this->request->params['pass'][0])?  $this->_get_history() :  NULL ; 
        $this->set('track_history',$track_history);
$this->_get_breadcrumbs_array();
if($this->request['controller'] != 'dashboards' && $this->request['controller'] != 'file_uploads' && $this->request['controller'] != 'messages'){
  $this->_get_objectives();
}



$this->set('prepared_by', $this->_get_employee_list());
$this->set('approved_by', $this->_get_employee_list());
$this->set('companyDetails', $this->_get_company());
if ($this->request['controller'] != 'updates') {
  $this->_get_document_details();
}
$this->_get_notifications();
$this->_get_suggestions();
$this->_get_messages();



if($this->action == 'view' || $this->action == 'internal_audit_uploads' || $this->action == 'approve' || $this->action == 'send_to_customer')
{
  $this->_getRecordFiles($this->request->params['pass'][0],$this->_get_system_table_details(),NULL);
}elseif($this->action == 'clausefiles' or $this->controller == 'clauses'){
  $this->_getRecordFiles($this->request->params['pass'][0],'clauses',NULL);
}elseif($this->request['controller'] == 'dashboards'){
  $this->_getRecordFiles(NULL,NULL,NULL);
}elseif($this->action == 'product_design' or $this->action == 'product_upload' ){
  $this->_getRecordFiles($this->request->params['pass'][1],$this->_get_system_table_details(),$this->request->params['pass'][0]);
}elseif($this->action == 'dashboard_files'){
  $this->_getRecordFiles($this->request->params['pass'][0],'dashboards',NULL);
}
       // check if user has permissions to edit / view these reords

if($this->action == 'approve')$this->_getApprovalFiles();

$this->_check_view_all();
$this->set('templates', $this->_get_template());
$this->set('controllerName', $this->request['controller']);

if ($this->action != 'upload' && $this->modelClass != 'Dashboard' && $this->action != 'save_import_data') {
  $mClass = $this->modelClass;
  $this->set(array('tableFields' => $this->$mClass->schema(),'tableRelations' => $this->$mClass->belongsTo));
}
        

if ($this->request['controller'] == 'employees') {
  $this->loadModel('Education');
  $this->Education->recursive = - 1;
  $education = array();
  $educations = $this->Education->find('list');
  foreach ($educations as $edu):
    $education[$edu] = $edu;
  endforeach;
  $this->set('educations', $education);
  $this->_get_designation_list();
}else if($this->request['controller'] == 'competency_mappings')   {
 $this->loadModel('Education');
 $this->Education->recursive = - 1;
 $educations = $this->Education->find('list',array('conditions'=>array('Education.publish'=>1,'Education.soft_delete'=>0)));
 $this->set('educations', $educations);

}

if($this->request->params['controller'] == 'appraisals' && $this->request->params['action'] == 'edit'){

  $currentDate = strtotime(date('Y-m-d'));
  $appraisalDate = strtotime($this->request->data['Appraisal']['appraisal_date']);

  if($currentDate > $appraisalDate || $this->data['Appraisal']['self_appraisal_status'] == 1){
   $this->Session->setFlash(__('This appraisal can not be edited as the appraisal date has passed or it is being answered by Employee (Appraisee).'), 'default', array('class'=>'alert-danger'));
   $this->redirect(array('action' => 'index'));
 }
}

if($this->action == 'edit') {
  $model = $this->modelClass;
  $this->loadModel($model);
  $recordStatus = $this->request->data[$model]['record_status'];
  if($recordStatus == 1){
    $this->Session->setFlash(__('Access Denied. This record is sent for an approval. You can not access or modify it.'));
      if($this->params->action != 'access_denied')$this->redirect(array('controller' => 'users','action' => 'access_denied',$this->action));
  }
}

$this->_check_auto_approvals();
$skip_controllers = array('dashboards','notifications','notification_users');
if(!(in_array($this->request->params['controller'], $skip_controllers))){
  $this->_get_objectives();
}

if($this->request->controller == 'evidences'){
  $this->_get_specials();
}
}

public function _get_specials(){
  if($this->request->controller == 'evidences' || $this->request->controller == 'products'){
    $special = array(
      'Dashboard Files'=>array(
        'quality_system_manual'=>'IMS Manual',
        'quality_system_procedures'=>'Quality System Procedures <small>Level-2</small>',
        'work_instructions' => 'Work Instructions <small>Level -3</small>',
        'forms'=>'Forms',
        'process_chart'=>'Process Chart',
        'guidelines'=>'Guidelines',
        'formats'=>'Formats'
        ),
      'Product Files'=>array(
        'product_upload'=>'Product Upload',
        'product_plan'=>'Product Plan',
        'product_requirement'=>'Product Requirement',
        'product_feasibility'=>'Product Feasibility',
        'product_development_plan'=>'Product Development Plan',
        'product_realisation'=>'Product Realisation')
      );
    $departments = $this->_get_department_list();
    foreach ($departments as $key => $department) {
      $department_folder = str_replace(' ', '_', $department);
      $department_folder = strtolower($department_folder);
      $department_list[$department_folder] = $department;
    }
    $special['Dashboard Files'] = array_merge($special['Dashboard Files'],$department_list);
    $this->set(array('special'=>$special));
    return $special;
  }
}
/**
 * request handling by - TGS
 * returns array of records created by user for branch , published / unpublished records & soft_deleted records
 */
/**
 * _get_count method
 *
 * @return void
 */

public function _get_breadcrumbs_array() {

		// MR Department
  $breadcrumbs['reports'] = array();
  $breadcrumbs['reports']['dashboard'] = 'MT';
  $breadcrumbs['reports']['dashboard_action'] = 'mr';

  $breadcrumbs['change_addition_deletion_requests'] = array();
  $breadcrumbs['change_addition_deletion_requests']['dashboard'] = 'MT';
  $breadcrumbs['change_addition_deletion_requests']['dashboard_action'] = 'mr';

  $breadcrumbs['document_amendment_record_sheets'] = array();
  $breadcrumbs['document_amendment_record_sheets']['dashboard'] = 'MT';
  $breadcrumbs['document_amendment_record_sheets']['dashboard_action'] = 'mr';

  $breadcrumbs['meetings'] = array();
  $breadcrumbs['meetings']['dashboard'] = 'MT';
  $breadcrumbs['meetings']['dashboard_action'] = 'mr';

  $breadcrumbs['meeting_topics'] = array();
  $breadcrumbs['meeting_topics']['dashboard'] = 'MT';
  $breadcrumbs['meeting_topics']['dashboard_action'] = 'mr';

  $breadcrumbs['list_of_trained_internal_auditors'] = array();
  $breadcrumbs['list_of_trained_internal_auditors']['dashboard'] = 'MT';
  $breadcrumbs['list_of_trained_internal_auditors']['dashboard_action'] = 'mr';

  $breadcrumbs['internal_audits'] = array();
  $breadcrumbs['internal_audits']['dashboard'] = 'MT';
  $breadcrumbs['internal_audits']['dashboard_action'] = 'mr';

  $breadcrumbs['internal_audit_plans'] = array();
  $breadcrumbs['internal_audit_plans']['dashboard'] = 'MT';
  $breadcrumbs['internal_audit_plans']['dashboard_action'] = 'mr';

  $breadcrumbs['corrective_preventive_actions'] = array();
  $breadcrumbs['corrective_preventive_actions']['dashboard'] = 'MT';
  $breadcrumbs['corrective_preventive_actions']['dashboard_action'] = 'mr';

  $breadcrumbs['capa_investigations'] = array();
  $breadcrumbs['capa_investigations']['dashboard'] = 'MT';
  $breadcrumbs['capa_investigations']['dashboard_action'] = 'mr';
  
  $breadcrumbs['capa_root_cause_analysis'] = array();
  $breadcrumbs['capa_root_cause_analysis']['dashboard'] = 'MT';
  $breadcrumbs['capa_root_cause_analysis']['dashboard_action'] = 'mr';
  
  $breadcrumbs['capa_revised_dates'] = array();
  $breadcrumbs['capa_revised_dates']['dashboard'] = 'MT';
  $breadcrumbs['capa_revised_dates']['dashboard_action'] = 'mr';
  $breadcrumbs['capa_sources'] = array();
  $breadcrumbs['capa_sources']['dashboard'] = 'MT';
  $breadcrumbs['capa_sources']['dashboard_action'] = 'mr';

  $breadcrumbs['capa_categories'] = array();
  $breadcrumbs['capa_categories']['dashboard'] = 'MT';
  $breadcrumbs['capa_categories']['dashboard_action'] = 'mr';

  $breadcrumbs['benchmarks'] = array();
  $breadcrumbs['benchmarks']['dashboard'] = 'MT';
  $breadcrumbs['benchmarks']['dashboard_action'] = 'mr';

  $breadcrumbs['tasks'] = array();
  $breadcrumbs['tasks']['dashboard'] = 'MT';
  $breadcrumbs['tasks']['dashboard_action'] = 'mr';

  $breadcrumbs['task_statuses'] = array();
  $breadcrumbs['task_statuses']['dashboard'] = 'MT';
  $breadcrumbs['task_statuses']['dashboard_action'] = 'mr';

		// HR

  $breadcrumbs['employees'] = array();
  $breadcrumbs['employees']['dashboard'] = 'HR';
  $breadcrumbs['employees']['dashboard_action'] = 'hr';

  $breadcrumbs['designations'] = array();
  $breadcrumbs['designations']['dashboard'] = 'HR';
  $breadcrumbs['designations']['dashboard_action'] = 'hr';

  $breadcrumbs['courses'] = array();
  $breadcrumbs['courses']['dashboard'] = 'HR';
  $breadcrumbs['courses']['dashboard_action'] = 'hr';

  $breadcrumbs['course_types'] = array();
  $breadcrumbs['course_types']['dashboard'] = 'HR';
  $breadcrumbs['course_types']['dashboard_action'] = 'hr';

  $breadcrumbs['trainers'] = array();
  $breadcrumbs['trainers']['dashboard'] = 'HR';
  $breadcrumbs['trainers']['dashboard_action'] = 'hr';

  $breadcrumbs['trainer_types'] = array();
  $breadcrumbs['trainer_types']['dashboard'] = 'HR';
  $breadcrumbs['trainer_types']['dashboard_action'] = 'hr';

  $breadcrumbs['training_need_identifications'] = array();
  $breadcrumbs['training_need_identifications']['dashboard'] = 'HR';
  $breadcrumbs['training_need_identifications']['dashboard_action'] = 'hr';

  $breadcrumbs['trainings'] = array();
  $breadcrumbs['trainings']['dashboard'] = 'HR';
  $breadcrumbs['trainings']['dashboard_action'] = 'hr';

  $breadcrumbs['training_types'] = array();
  $breadcrumbs['training_types']['dashboard'] = 'HR';
  $breadcrumbs['training_types']['dashboard_action'] = 'hr';

  $breadcrumbs['training_evaluations'] = array();
  $breadcrumbs['training_evaluations']['dashboard'] = 'HR';
  $breadcrumbs['training_evaluations']['dashboard_action'] = 'hr';

  $breadcrumbs['competency_mappings'] = array();
  $breadcrumbs['competency_mappings']['dashboard'] = 'HR';
  $breadcrumbs['competency_mappings']['dashboard_action'] = 'hr';

  $breadcrumbs['employee_kras'] = array();
  $breadcrumbs['employee_kras']['dashboard'] = 'HR';
  $breadcrumbs['employee_kras']['dashboard_action'] = 'hr';

  $breadcrumbs['kras'] = array();
  $breadcrumbs['kras']['dashboard'] = 'HR';
  $breadcrumbs['kras']['dashboard_action'] = 'hr';

  $breadcrumbs['appraisals'] = array();
  $breadcrumbs['appraisals']['dashboard'] = 'HR';
  $breadcrumbs['appraisals']['dashboard_action'] = 'hr';

  $breadcrumbs['employee_appraisal_questions'] = array();
  $breadcrumbs['employee_appraisal_questions']['dashboard'] = 'HR';
  $breadcrumbs['employee_appraisal_questions']['dashboard_action'] = 'hr';


  $breadcrumbs['appraisal_questions'] = array();
  $breadcrumbs['appraisal_questions']['dashboard'] = 'HR';
  $breadcrumbs['appraisal_questions']['dashboard_action'] = 'hr';


		// BD
  $breadcrumbs['customers'] = array();
  $breadcrumbs['customers']['dashboard'] = 'BD';
  $breadcrumbs['customers']['dashboard_action'] = 'bd';

  $breadcrumbs['customer_meetings'] = array();
  $breadcrumbs['customer_meetings']['dashboard'] = 'BD';
  $breadcrumbs['customer_meetings']['dashboard_action'] = 'bd';
  
  $breadcrumbs['customer_contacts'] = array();
  $breadcrumbs['customer_contacts']['dashboard'] = 'BD';
  $breadcrumbs['customer_contacts']['dashboard_action'] = 'bd';
  
  $breadcrumbs['proposal_followup_rules'] = array();
  $breadcrumbs['proposal_followup_rules']['dashboard'] = 'BD';
  $breadcrumbs['proposal_followup_rules']['dashboard_action'] = 'bd';

  $breadcrumbs['proposals'] = array();
  $breadcrumbs['proposals']['dashboard'] = 'BD';
  $breadcrumbs['proposals']['dashboard_action'] = 'bd';

  $breadcrumbs['proposal_followups'] = array();
  $breadcrumbs['proposal_followups']['dashboard'] = 'BD';
  $breadcrumbs['proposal_followups']['dashboard_action'] = 'bd';

		// Admin
  $breadcrumbs['fire_extinguisher_types'] = array();
  $breadcrumbs['fire_extinguisher_types']['dashboard'] = 'Admin';
  $breadcrumbs['fire_extinguisher_types']['dashboard_action'] = 'personal_admin';

  $breadcrumbs['fire_extinguishers'] = array();
  $breadcrumbs['fire_extinguishers']['dashboard'] = 'Admin';
  $breadcrumbs['fire_extinguishers']['dashboard_action'] = 'personal_admin';

  $breadcrumbs['housekeeping_checklists'] = array();
  $breadcrumbs['housekeeping_checklists']['dashboard'] = 'Admin';
  $breadcrumbs['housekeeping_checklists']['dashboard_action'] = 'personal_admin';

  $breadcrumbs['housekeeping_responsibilities'] = array();
  $breadcrumbs['housekeeping_responsibilities']['dashboard'] = 'Admin';
  $breadcrumbs['housekeeping_responsibilities']['dashboard_action'] = 'personal_admin';

  $breadcrumbs['housekeepings'] = array();
  $breadcrumbs['housekeepings']['dashboard'] = 'Admin';
  $breadcrumbs['housekeepings']['dashboard_action'] = 'personal_admin';

		// Quality Control
  $breadcrumbs['material_quality_checks'] = array();
  $breadcrumbs['material_quality_checks']['dashboard'] = 'Quality Control';
  $breadcrumbs['material_quality_checks']['dashboard_action'] = 'quality_control';

  $breadcrumbs['customer_complaints'] = array();
  $breadcrumbs['customer_complaints']['dashboard'] = 'Quality Control';
  $breadcrumbs['customer_complaints']['dashboard_action'] = 'quality_control';

  $breadcrumbs['list_of_measuring_devices_for_calibrations'] = array();
  $breadcrumbs['list_of_measuring_devices_for_calibrations']['dashboard'] = 'Quality Control';
  $breadcrumbs['list_of_measuring_devices_for_calibrations']['dashboard_action'] = 'quality_control';

  $breadcrumbs['devices'] = array();
  $breadcrumbs['devices']['dashboard'] = 'Quality Control';
  $breadcrumbs['devices']['dashboard_action'] = 'quality_control';

  $breadcrumbs['device_maintenances'] = array();
  $breadcrumbs['device_maintenances']['dashboard'] = 'Quality Control';
  $breadcrumbs['device_maintenances']['dashboard_action'] = 'quality_control';

  $breadcrumbs['calibrations'] = array();
  $breadcrumbs['calibrations']['dashboard'] = 'Quality Control';
  $breadcrumbs['calibrations']['dashboard_action'] = 'quality_control';

  $breadcrumbs['customer_feedbacks'] = array();
  $breadcrumbs['customer_feedbacks']['dashboard'] = 'Quality Control';
  $breadcrumbs['customer_feedbacks']['dashboard_action'] = 'quality_control';

  $breadcrumbs['customer_feedback_questions'] = array();
  $breadcrumbs['customer_feedback_questions']['dashboard'] = 'Quality Control';
  $breadcrumbs['customer_feedback_questions']['dashboard_action'] = 'quality_control';

  $breadcrumbs['non_conforming_products_materials'] = array();
  $breadcrumbs['non_conforming_products_materials']['dashboard'] = 'Quality Control';
  $breadcrumbs['non_conforming_products_materials']['dashboard_action'] = 'quality_control';

  

		// Purchase
  $breadcrumbs['supplier_registrations'] = array();
  $breadcrumbs['supplier_registrations']['dashboard'] = 'Purchase';
  $breadcrumbs['supplier_registrations']['dashboard_action'] = 'purchase';

  $breadcrumbs['purchase_orders'] = array();
  $breadcrumbs['purchase_orders']['dashboard'] = 'Purchase';
  $breadcrumbs['purchase_orders']['dashboard_action'] = 'purchase';

  $breadcrumbs['delivery_challans'] = array();
  $breadcrumbs['delivery_challans']['dashboard'] = 'Purchase';
  $breadcrumbs['delivery_challans']['dashboard_action'] = 'purchase';

  $breadcrumbs['supplier_evaluation_reevaluations'] = array();
  $breadcrumbs['supplier_evaluation_reevaluations']['dashboard'] = 'Purchase';
  $breadcrumbs['supplier_evaluation_reevaluations']['dashboard_action'] = 'purchase';

  $breadcrumbs['summery_of_supplier_evaluations'] = array();
  $breadcrumbs['summery_of_supplier_evaluations']['dashboard'] = 'Purchase';
  $breadcrumbs['summery_of_supplier_evaluations']['dashboard_action'] = 'purchase';

  $breadcrumbs['list_of_acceptable_suppliers'] = array();
  $breadcrumbs['list_of_acceptable_suppliers']['dashboard'] = 'Purchase';
  $breadcrumbs['list_of_acceptable_suppliers']['dashboard_action'] = 'purchase';

  $breadcrumbs['supplier_categories'] = array();
  $breadcrumbs['supplier_categories']['dashboard'] = 'Purchase';
  $breadcrumbs['supplier_categories']['dashboard_action'] = 'purchase';

  $breadcrumbs['invoices'] = array();
  $breadcrumbs['invoices']['dashboard'] = 'Purchase';
  $breadcrumbs['invoices']['dashboard_action'] = 'purchase';		
        // EDP
  $breadcrumbs['list_of_softwares'] = array();
  $breadcrumbs['list_of_softwares']['dashboard'] = 'EDP';
  $breadcrumbs['list_of_softwares']['dashboard_action'] = 'edp';

  $breadcrumbs['list_of_computers'] = array();
  $breadcrumbs['list_of_computers']['dashboard'] = 'EDP';
  $breadcrumbs['list_of_computers']['dashboard_action'] = 'edp';

  $breadcrumbs['list_of_computer_list_of_softwares'] = array();
  $breadcrumbs['list_of_computer_list_of_softwares']['dashboard'] = 'EDP';
  $breadcrumbs['list_of_computer_list_of_softwares']['dashboard_action'] = 'edp';

  $breadcrumbs['data_types'] = array();
  $breadcrumbs['data_types']['dashboard'] = 'EDP';
  $breadcrumbs['data_types']['dashboard_action'] = 'edp';

  $breadcrumbs['data_back_ups'] = array();
  $breadcrumbs['data_back_ups']['dashboard'] = 'EDP';
  $breadcrumbs['data_back_ups']['dashboard_action'] = 'edp';

  $breadcrumbs['daily_backup_details'] = array();
  $breadcrumbs['daily_backup_details']['dashboard'] = 'EDP';
  $breadcrumbs['daily_backup_details']['dashboard_action'] = 'edp';

  $breadcrumbs['username_password_details'] = array();
  $breadcrumbs['username_password_details']['dashboard'] = 'EDP';
  $breadcrumbs['username_password_details']['dashboard_action'] = 'edp';

  $breadcrumbs['databackup_logbooks'] = array();
  $breadcrumbs['databackup_logbooks']['dashboard'] = 'EDP';
  $breadcrumbs['databackup_logbooks']['dashboard_action'] = 'edp';

  $breadcrumbs['software_types'] = array();
  $breadcrumbs['software_types']['dashboard'] = 'EDP';
  $breadcrumbs['software_types']['dashboard_action'] = 'edp';

		// Production
  $breadcrumbs['materials'] = array();
  $breadcrumbs['materials']['dashboard'] = 'Production';
  $breadcrumbs['materials']['dashboard_action'] = 'production';

  $breadcrumbs['production_rejections'] = array();
  $breadcrumbs['production_rejections']['dashboard'] = 'Production';
  $breadcrumbs['production_rejections']['dashboard_action'] = 'production';

  $breadcrumbs['production_weekly_plans'] = array();
  $breadcrumbs['production_weekly_plans']['dashboard'] = 'Production';
  $breadcrumbs['production_weekly_plans']['dashboard_action'] = 'production';
  
  $breadcrumbs['production_inspection_templates'] = array();
  $breadcrumbs['production_inspection_templates']['dashboard'] = 'Production';
  $breadcrumbs['production_inspection_templates']['dashboard_action'] = 'production';

  $breadcrumbs['production_categories'] = array();
  $breadcrumbs['production_categories']['dashboard'] = 'Production';
  $breadcrumbs['production_categories']['dashboard_action'] = 'production';

  $breadcrumbs['production_details'] = array();
  $breadcrumbs['production_details']['dashboard'] = 'Production';
  $breadcrumbs['production_details']['dashboard_action'] = 'production';

  $breadcrumbs['performance_indicators'] = array();
  $breadcrumbs['performance_indicators']['dashboard'] = 'Production';
  $breadcrumbs['performance_indicators']['dashboard_action'] = 'production';

  $breadcrumbs['value_drivers'] = array();
  $breadcrumbs['value_drivers']['dashboard'] = 'Production';
  $breadcrumbs['value_drivers']['dashboard_action'] = 'production';

  $breadcrumbs['products'] = array();
  $breadcrumbs['products']['dashboard'] = 'Production';
  $breadcrumbs['products']['dashboard_action'] = 'production';

  $breadcrumbs['productions'] = array();
  $breadcrumbs['productions']['dashboard'] = 'Production';
  $breadcrumbs['productions']['dashboard_action'] = 'production';

  $breadcrumbs['stocks'] = array();
  $breadcrumbs['stocks']['dashboard'] = 'Production';
  $breadcrumbs['stocks']['dashboard_action'] = 'production';

  $breadcrumbs['stock_status'] = array();
  $breadcrumbs['stock_status']['dashboard'] = 'Production';
  $breadcrumbs['stock_status']['dashboard_action'] = 'production';

  $breadcrumbs['objectives'] = array();
  $breadcrumbs['objectives']['dashboard'] = 'Objectives';
  $breadcrumbs['objectives']['controller'] = 'objectives';
  $breadcrumbs['objectives']['dashboard_action'] = 'index';

  $breadcrumbs['objective_monitorings'] = array();
  $breadcrumbs['objective_monitorings']['dashboard'] = 'Objectives';
  $breadcrumbs['objective_monitorings']['controller'] = 'objectives';
  $breadcrumbs['objective_monitorings']['dashboard_action'] = 'index';

  $breadcrumbs['processes'] = array();
  $breadcrumbs['processes']['dashboard'] = 'Objectives';
  $breadcrumbs['processes']['controller'] = 'objectives';
  $breadcrumbs['processes']['dashboard_action'] = 'index';

  $breadcrumbs['incidents'] = array();
  $breadcrumbs['incidents']['dashboard'] = 'RM ICM';
  $breadcrumbs['incidents']['dashboard_action'] = 'raicm';

  $breadcrumbs['risk_assessments'] = array();
  $breadcrumbs['risk_assessments']['dashboard'] = 'RM ICM';
  $breadcrumbs['risk_assessments']['dashboard_action'] = 'raicm';

  $breadcrumbs['hazard_types'] = array();
  $breadcrumbs['hazard_types']['dashboard'] = 'RM ICM';
  $breadcrumbs['hazard_types']['dashboard_action'] = 'raicm';

  $breadcrumbs['hazard_sources'] = array();
  $breadcrumbs['hazard_sources']['dashboard'] = 'RM ICM';
  $breadcrumbs['hazard_sources']['dashboard_action'] = 'raicm';

  $breadcrumbs['accident_types'] = array();
  $breadcrumbs['accident_types']['dashboard'] = 'RM ICM';
  $breadcrumbs['accident_types']['dashboard_action'] = 'raicm';

  $breadcrumbs['severiry_types'] = array();
  $breadcrumbs['severiry_types']['dashboard'] = 'RM ICM';
  $breadcrumbs['severiry_types']['dashboard_action'] = 'raicm';

  $breadcrumbs['risk_ratings'] = array();
  $breadcrumbs['risk_ratings']['dashboard'] = 'RM ICM';
  $breadcrumbs['risk_ratings']['dashboard_action'] = 'raicm';

  $breadcrumbs['incident_classifications'] = array();
  $breadcrumbs['incident_classifications']['dashboard'] = 'RM ICM';
  $breadcrumbs['incident_classifications']['dashboard_action'] = 'raicm';

  $breadcrumbs['project_activities'] = array();
  $breadcrumbs['project_activities']['dashboard'] = 'Project Management';
  $breadcrumbs['project_activities']['dashboard_action'] = '';

  $breadcrumbs['project_activity_requirements'] = array();
  $breadcrumbs['project_activity_requirements']['dashboard'] = 'Project Management';
  $breadcrumbs['project_activity_requirements']['dashboard_action'] = '';
  
  $breadcrumbs['milestones'] = array();
  $breadcrumbs['milestones']['dashboard'] = 'Project Management';
  $breadcrumbs['milestones']['dashboard_action'] = '';



  $this->set('breadcrumbs', $breadcrumbs);

}

public function _get_count(){

  $onlyBranch = null;
  $onlyOwn = null;
  $conditions = null;
  $modelName = $this->modelClass;
  $branchIDYes = false;
  if($this->Session->read('User.is_mr') == 0 && $branchIDYes == true)$onlyBranch = array($modelName.'.branch_id'=>$this->Session->read('User.branch_id'));
  if($this->Session->read('User.is_view_all') == 0)$onlyOwn = array($modelName.'.created_by'=>$this->Session->read('User.id'));

  $conditions = array($onlyBranch,$onlyOwn);
  $count = $this->$modelName->find('count',array('conditions'=>$conditions));
  $published = $this->$modelName->find('count',array('conditions'=>array($conditions,$modelName.'.publish'=>1,$modelName.'.soft_delete'=>0)));
  $unpublished = $this->$modelName->find('count',array('conditions'=>array($conditions,$modelName.'.publish'=>0,$modelName.'.soft_delete'=>0)));
  $deleted = $this->$modelName->find('count',array('conditions'=>array($conditions,$modelName.'.soft_delete'=>1)));
  $this->set(compact('count','published','unpublished','deleted'));
}


public function _get_company() {
  if($this->action != 'display_notifications'){    
    $this->loadModel('Company');
    $this->Company->recursive = - 1;
    $company = $this->Company->find('first', array(
      'conditions'=>array('Company.id'=>$this->Session->read('User.company_id')),
      'fields' => array(
        'Company.id',
        'Company.logo',
        'Company.company_logo',
        'Company.name'
        ),
      'recursive' => - 1
      ));
    return $company;
  }
}

public function _get_branch_list() {
  $this->loadModel('Branch');
  $PublishedBranchList = $this->Branch->find('list', array(
   'conditions' => array(
    'Branch.soft_delete' => 0,
    'Branch.publish' => 1
    ),
   'recursive' => - 1
   ));
  $this->set(compact('PublishedBranchList'));
  return ($PublishedBranchList);
}

public function _get_department_list() {
  $this->loadModel('Department');
  $PublishedDepartmentList = $this->Department->find('list', array(
   'conditions' => array(
    'Department.soft_delete' => 0,
    'Department.publish' => 1
    ),
   'recursive' => - 1
   ));
  $this->set(compact('PublishedDepartmentList'));
  return ($PublishedDepartmentList);
}

public function _get_employee_list() {
  $this->loadModel('Employee');
  $PublishedEmployeeList = $this->Employee->find('list', array(
    // 'limit'=>10,
   'conditions' => array(
    'Employee.soft_delete' => 0,
    'Employee.publish' => 1
    ),
   'recursive' => - 1
   ));
  $this->set(compact('PublishedEmployeeList'));
  return ($PublishedEmployeeList);
}

public function _get_designation_list() {
  $this->loadModel('Designation');
  $PublishedDesignationList = $this->Designation->find('list', array(
   'conditions' => array(
    'Designation.publish' => 1,
    'Designation.soft_delete' => 0
    ),
   'recursive' => - 1
   ));
  $this->set(compact('PublishedDesignationList'));
  return ($PublishedDesignationList);
}

//Common function to Dynamically load Models and get published, un-deleted records.
public function get_model_list($model = null,$type = null) {

// Configure::write('debug',1);
// debug($model);
// exit;

  if($model){
    $this->loadModel($model);
    if($type){
      return $this->$model->find('count', array('conditions' => array($model.'.publish' => 1, $model.'.soft_delete' => 0),
        'recursive' => -1));
    }else{
      return $this->$model->find('list', array('order'=>array($model.'.'.$this->$model->displayField => 'ASC'), 'conditions' => array($model.'.publish' => 1, $model.'.soft_delete' => 0),
        'recursive' => -1));
    }
  }
}


public function get_usernames() {
  $this->loadModel('User');
  $users = $this->User->find('all', array('conditions' => array('User.soft_delete'=> 0,'User.publish'=>1),'fields' => array('User.id','User.name','User.username')));
  foreach ($users as $user) {
    $employeeUserNames[$user['User']['id']] = $user['User']['name'] . " (" . $user['User']['username'] . ")";
  }
  return($employeeUserNames);
}

public function _get_user_list() {
  $this->loadModel('User');
  $users = $this->User->find('list', array('conditions' => array('User.soft_delete'=> 0,'User.publish'=>1)));
  $this->set('PublishedUserList', $users);
  return ($users);
}

public function get_device_calibration_list() {
  $this->loadModel('Device');
  $DeviceCalibrationList = $this->Device->find('list', array('conditions' => array('Device.publish' => 1, 'Device.soft_delete' => 0, 'Device.calibration_required' => 0),
   'recursive' => -1));
  return($DeviceCalibrationList);
}

public function _show_evidence() {
  $this->loadModel("SystemTable");
  $this->SystemTable->recursive = - 1;
  $tableDetails = $this->SystemTable->find('first', array(
   'conditions' => array(
    'SystemTable.system_name' => $this->name
    ),
   'fields' => array(
    'SystemTable.evidence_required'
    )
   ));
  if ($tableDetails && $tableDetails['SystemTable']['evidence_required'] != false)
   return true;
}

public function _get_approvers_list() {
  if($this->request->controller != 'file_uploads'){
   $userIds = array();
   $this->loadModel('User');
   $this->User->recursive = - 1;
   $userIdsAll = $this->User->find('all', array(
     'order' => array(
      'User.name' => 'ASC'
      ),
     'conditions' => array(
      'User.id <>' => $this->Session->read('User.id'),
      'User.publish' => 1,
      'User.soft_delete' => 0,
      'User.is_approvar' => 1
      )
     ));
   foreach ($userIdsAll as $userId) {
    $userIds[$userId['User']['id']] = $userId['User']['name'] . " (" . $userId['User']['username'] . ")";
  }
        	if($this->data && $this->Session->read('User.id') != $this->data['CreatedBy']['id'])
            $userIds[$this->data['CreatedBy']['id']] = $this->data['CreatedBy']['name'] . " (Creator)";
  return $userIds;
}
}

public function _show_approvals() {
  $this->loadModel("SystemTable");
  $this->SystemTable->recursive = - 1;
  $tableDetails = $this->SystemTable->find('first', array(
   'conditions' => array(
    'SystemTable.system_name' => $this->params['controller']
    ),
   'fields' => array(
    'SystemTable.approvals_required'
    )
   ));
  $approvar = array();

		// echo json_encode($tableDetails);

  if ($tableDetails && $tableDetails['SystemTable']['approvals_required'] == 1) {

   $approvar['show_panel'] = true;
      if ($this->Session->read('User.is_approvar') == true )//|| $this->Session->read('User.is_mr') == true)
{
  $approvar['show_publish'] = true;
}
}

return $approvar;
}

public function _track_history($track = null) {

  $this->loadModel('History');
  $this->History->recursive = - 1;

  $this->History->create();
  $history = array();
  $model_name = Inflector::Classify($this->name);
  $history['History']['model_name'] = $model_name;
  $history['History']['controller_name'] = $this->request->params['controller'];
  $history['History']['action'] = $this->request->action;
  $history['History']['record_id'] = isset($this->request->params['pass'][0])? $this->request->params['pass'][0]: NULL;
  $history['History']['get_values'] = json_encode($this->request->params);
  if(isset($this->request->data['History'])){

    $history['History']['pre_post_values'] = $this->request->data['History']['pre_post_values'];
    $this->loadModel('EmailTrigger');
    $system_table_id = $this->_get_system_table_id();
    $triggers = $this->EmailTrigger->find('first',array(
      'conditions'=>array('EmailTrigger.system_table'=>$system_table_id)));
    if($triggers['EmailTrigger']['if_edited'] != 1 && $triggers['EmailTrigger']['if_publish'] != 1)
     unset($this->request->data['History']);
 }
 $history['History']['post_values'] = json_encode(array(
  $this->request->data
  ));
 $history['History']['branch_id'] = $this->Session->read('User.branch_id');
 $history['History']['department_id'] = $this->Session->read('User.department_id');
 $history['History']['branchid'] = $this->Session->read('User.branch_id');
 $history['History']['departmentid'] = $this->Session->read('User.department_id');
 $history['History']['publish'] = 1;
 $history['History']['soft_delete'] = 0;
 $history['History']['created_by'] = $this->Session->read('User.id');
 $history['History']['user_session_id'] = $this->Session->read('User.user_session_id');
 $this->History->save($history);

  //update usersession .. end time
 $this->History->UserSession->read(null,$this->Session->read('User.user_session_id'));
 $data['UserSession']['end_time'] = date('Y-m-d H:i:s');
 try{
    $this->History->UserSession->save($data['UserSession'], false);
  } catch(Exception $e) {
    
  }
 

}

public function _check_view_all() {

  $action = $this->request->action;
  $controller = $this->request->params['controller'];
  $model = Inflector::Classify($controller);
  $defaultCtrlAccess = array('Messages', 'MessageUserInboxes', 'MessageUserSents', 'MessageUserThrashes');
  if ($this->Session->read('User.is_view_all') == false && !in_array($this->params->controller, $defaultCtrlAccess)) {
   if (($action == 'edit' && $this->data[$model]['created_by'] != $this->Session->read('User.id')) || ($action == 'view' && $this->viewVars[Inflector::variable($model)][$model]['created_by'] != $this->Session->read('User.id')) || ($action == 'meeting_view') || ($action == 'get_material_qc_required' && $this->params->action != 'get_delivered_material_qc' && $this->params->action != 'capa_assigned' && $this->params->action != 'get_customer_complaints' && $this->params->action != 'get_next_calibration' && $this->params->action != 'get_device_maintainance')) {

    if ($this->params->controller == 'corrective_preventive_actions') {
     $this->loadModel('CorrectivePreventiveAction');

     if ($this->params->action == 'capa_assigned') {
          //  $condition = array('CorrectivePreventiveAction.assigned_to' => $this->Session->read('User.employee_id'), 'CorrectivePreventiveAction.publish' => 1, 'CorrectivePreventiveAction.soft_delete' => 0,);
     } else {
      $condition = array('CorrectivePreventiveAction.id' => $this->params['pass'][0],
              //'CorrectivePreventiveAction.assigned_to' => $this->Session->read('User.employee_id'),
       'CorrectivePreventiveAction.publish' => 1, 'CorrectivePreventiveAction.soft_delete' => 0,
       );
    }
    $capa = $this->CorrectivePreventiveAction->find('first', array(
      'conditions' => $condition,
      'recursive' => - 1
      ));
    if ($this->_count($capa) == 0) {
      $this->Session->setFlash(__('Access Denied 11'));
      if ($this->params->action != 'access_denied')
       $this->redirect(array(
        'controller' => 'users',
        'action' => 'access_denied',$this->action,11
        ));
   }
 }else if ($this->params->controller == 'users') {
   $this->loadModel('User');
   $users_count = $this->User->find('first', array(
    'conditions' => array(
     'User.id' => $this->Session->read('User.id')
     ),
    'recursive' => - 1
    ));
   if ($this->_count($users_count) == 0) {
    $this->Session->setFlash(__('Access Denied 12'));
    if ($this->params->action != 'access_denied')
     $this->redirect(array(
      'controller' => 'users',
      'action' => 'access_denied',$this->action,12
      ));
 }
}
else if ($this->params->controller == 'customer_complaints') {
 $this->loadModel('CustomerComplaint');
 if ($this->params->action == 'get_customer_complaints') {
  $condition = array('CustomerComplaint.employee_id' => $this->Session->read('User.employee_id'), 'CustomerComplaint.publish' => 1, 'CustomerComplaint.soft_delete' => 0);
} else {
  $condition = array(
   'CustomerComplaint.id' => $this->params['pass'][0],
   'CustomerComplaint.employee_id' => $this->Session->read('User.employee_id'),
   'CustomerComplaint.publish' => 1, 'CustomerComplaint.soft_delete' => 0,
   );
}
$custComplaints = $this->CustomerComplaint->find('first', array(
  'conditions' => $condition,
  'recursive' => - 1
  ));
if ($this->_count($custComplaints) == 0) {
  $this->Session->setFlash(__('Access Denied 13'));
  if ($this->params->action != 'access_denied')
   $this->redirect(array(
    'controller' => 'users',
    'action' => 'access_denied',$this->action,13
    ));
}
}else if ($this->params->controller == 'meetings' && $this->params->action == 'meeting_view') {

 $this->loadModel('MeetingEmployee');
 $invities = $this->MeetingEmployee->find('first', array(
  'conditions' => array(
   'MeetingEmployee.meeting_id' => $this->params['pass'][0],
   'MeetingEmployee.employee_id' => $this->Session->read('User.employee_id')
   ),
  'recursive' => - 1
  ));
 if ($invities == 0) {
  $this->Session->setFlash(__('Access Denied 14'));
  if ($this->params->action != 'access_denied')
   $this->redirect(array('controller' => 'users', 'action' => 'access_denied',$this->action,14));
}
} else if ($this->params->controller == 'notifications' && $this->params->action == 'view') {
 $this->loadModel('NotificationUser');
 $invities = $this->NotificationUser->find('first', array(
  'conditions' => array('NotificationUser.notification_id' => $this->params['pass'][0], 'NotificationUser.employee_id' => $this->Session->read('User.employee_id')), 'recursive' => - 1));
 if ($invities == 0) {
  $this->Session->setFlash(__('Access Denied 15'));
  if ($this->params->action != 'access_denied')
   $this->redirect(array('controller' => 'users', 'action' => 'access_denied',$this->action,15));
}
} else if ($this->params->controller == 'materials' && $this->params->action == 'get_material_qc_required') {
					//exit();
} else if ($this->params->controller == 'delivery_challans' && $this->params->action == 'get_delivered_material_qc') {
 exit;
} else if ($this->params->controller == 'calibrations' && $this->params->action == 'get_next_calibration') {
 exit;
} else if ($this->params->controller == 'device_maintenances' && $this->params->action == 'get_device_maintainance') {
 exit;
} else if ($this->params->controller == 'tasks') {
 // exit;
} else if ($this->params->controller == 'projects') {
 // exit;
} else if ($this->params->controller == 'meeting_topics' && $this->params->action == 'edit'){
  
}else {

                    $this->loadModel('Approval');
                    $approvals = $this->Approval->find('all', array('conditions' => array('Approval.controller_name' => $this->params->controller, 'Approval.record' => $this->params['pass'][0], 'Approval.user_id' => $this->Session->read('User.id')), 'recursive' => - 1));
                    if ($this->_count($approvals) == 0) {
                        $this->Session->setFlash(__('Access Denied 16'));
                        if ($this->params->action != 'access_denied')
                            $this->redirect(array('controller' => 'users', 'action' => 'access_denied',$this->action,16));
                    }
                }
            }
        }
    }

public function _get_history() {
 $this->loadModel('History');
 $trackhistory = $this->History->find('all',array(
   'conditions'=>array(
    'History.model_name'=>$this->modelClass,
    'History.record_id' =>$this->request->params['pass'][0],
    ),
   'limit'=>10,'order'=>'History.id desc',
   'fields'=>array('History.action','History.id','CreatedBy.name','History.created'),
   'recursive'=>1
   ));
 $this->set('history_record_id',$this->request->params['pass'][0]);
 return $trackhistory;
}

public function _display_help() {
  $this->loadModel('Help');
  $this->Help->recursive = - 1;
  $helps = $this->Help->find('all', array(
   'order' => array(
    'sequence' => 'asc'
    ),
   'conditions' =>
   array('and'=>array(
    'Help.language_id'=>$this->Session->read('SessionLanguage'),
    'Help.table_name' => $this->name,
    'Help.action_name like ' => "%".$this->request->params['action']."%"
    )),
   'recursive' => - 1
   ));
  $this->set('helps', $helps);
  if ($this->request->params['pass'] && $this->action != 'quality_check' && $this->action != 'add_quality_check')
   $this->set('approvalHistory', $this->_get_approval_history($this->request->params['pass'][0]));
}

public function _maker_checker() {
  $this->loadModel('Employee');
  $this->Employee->recursive = - 1;
  $employeesList = $this->Employee->find('list');
  $this->set(compact('employeesList'));
}

public function _get_document_details() {
  $this->loadModel('SystemTable');
  $this->SystemTable->recursive = 0;
  $systemTableId = $this->SystemTable->find('first', array(
   'fields' => array(
    'SystemTable.id'
    ),
   'conditions' => array(
    'SystemTable.system_name' => $this->request->params['controller']
    )
   ));
  if($this->request->params['controller'] == 'master_list_of_formats'){
    $documentDetails = $this->MasterListOfFormat->find('first', array(
     'conditions' => array(
      'MasterListOfFormat.id' => $this->request->params['pass'][0]
      )
     ));
  }else{
    $this->SystemTable->MasterListOfFormat->recursive = - 1;
    $documentDetails = $this->SystemTable->MasterListOfFormat->find('first', array(
     'conditions' => array(
      'MasterListOfFormat.system_table_id' => $systemTableId['SystemTable']['id']
      )
     ));  
  }
  
  $this->set('documentDetails', $documentDetails);
  return $documentDetails;
}

public function _get_system_table_details() {

  $this->loadModel('SystemTable');
  $this->SystemTable->recursive = 0;
  $systemTable = $this->SystemTable->find('first', array(
   'fields' => array(
    'SystemTable.id'
    ),
   'conditions' => array(
    'SystemTable.system_name' => $this->request->params['controller']
    )
   ));

  $this->set('systemTable', $systemTable);
  return $systemTable;
}

public function _get_system_table($dir) {
  $this->loadModel("SystemTable");
  $this->SystemTable->recursive = 0;
  $tableDetails = $this->SystemTable->find('first', array(
   'conditions' => array(
    'SystemTable.system_name' => $dir
    ),
   'fields' => array(
    'SystemTable.id'
    )
   ));
  if ($tableDetails && $tableDetails['SystemTable']['id'] != false)
   return $tableDetails['SystemTable']['id'];
}

public function _get_approval_history($id = null) {
  $this->loadModel('Approval');
  $approvalHistoryCount = $this->Approval->find('count', array(
   'conditions' => array(
    'Approval.controller_name' => $this->request->params['controller'],
    'Approval.record' => $id
    ),
   'recursive' => - 1
   ));
  $approvalHistories = $this->Approval->find('all', array(
   'conditions' => array(
    'Approval.controller_name' => $this->request->params['controller'],
    'Approval.record' => $id
    ),
   'fields' => array(
    'Approval.id',
    'Approval.comments',
    'Approval.created',
    'Approval.status',
    'Approval.status_user_id',
    'From.name',
    'Approval.created_by',
    'To.name',
    'User.name',
    ),
   'order'=> array('Approval.created' => 'DESC'),
   'recursive' => 0
   ));
  $approvalHistory = array(
   'history' => $approvalHistories,
   'count' => $approvalHistoryCount
   );
  return $approvalHistory;
}

public function _prepare_menu() {
  $this->loadModel('MasterListOfFormatDepartment');
  $this->MasterListOfFormatDepartment->Department->recursive = - 1;
  $departments = $this->MasterListOfFormatDepartment->Department->find('all', array(
   'fields' => array(
    'Department.id',
    'Department.name'
    ),
   'conditions' => array(
    'Department.publish' => 1,
    'Department.soft_delete' => 0
    )
   ));
  $menu = array();
  $i = 0;
  foreach ($departments as $department):
   $this->MasterListOfFormatDepartment->recursive = 0;
 $forms = $this->MasterListOfFormatDepartment->find('all', array(
  'fields' => array(
   'MasterListOfFormat.title',
   'MasterListOfFormat.system_table_id'
   ),
  'conditions' => array(
   'MasterListOfFormatDepartment.department_id' => $department['Department']['id']
   )
  ));
 $menu[$i]['Department'] = $department;
 foreach ($forms as $form):
  $this->MasterListOfFormatDepartment->MasterListOfFormat->SystemTable->recursive = - 1;
$tableDetails = $this->MasterListOfFormatDepartment->MasterListOfFormat->SystemTable->find('first', array(
 'fields' => array(
  'SystemTable.id',
  'SystemTable.system_name'
  ),
 'conditions' => array(
  'SystemTable.id' => $form['MasterListOfFormat']['system_table_id']
  )
 ));
$menu[$i]['MasterListOfFormat'] = $form;
$menu[$i]['MasterListOfFormat']['SystemTable'] = $tableDetails;
endforeach;
$i++;
endforeach;
$this->set('menu', $menu);
}

public function _missing_table($table = null) {
  $tableNotExist = array();
  $tables = array(
   'departments',
   'branches',
   'designations',
   'users',
   'employees',
   'supplier_registrations',
   'products',
   'benchmarks'
   );
  foreach ($tables as $table):
   $modelName = null;
 $modelName.= Inflector::Classify($table);
 $this->loadModel($modelName);
 $this->$modelName->recursive = - 1;
 $tableExist = $this->$modelName->find('first', array(
  'conditions' => array(
   'publish' => 1
   )
  ));
 if (empty($tableExist)) {
  $tableNotExist[] = $table;
}

endforeach;
if (empty($tableNotExist)) {
 return true;
} else {
 $this->set('tableNotExist', $tableNotExist);
}
$this->set($tableNotExist);
$this->set('install', true);
if ($tableNotExist[0] == 'benchmarks')
 $getLink = Router::url('/', true) . $tableNotExist[0];
else
 $getLink = Router::url('/', true) . $tableNotExist[0] . '/lists';
$this->Session->setFlash('Please add details for <h4>' . Inflector::Humanize($tableNotExist[0]) . ' <a href="' . $getLink . '" class="btn btn-sm btn-success">Add new</a></h4>
 before you start using the application.You can also import these records if you have them ready. <br />
 <strong>Please not that you will have to publish records which you are importing before you can access those records.</strong>
 <br />List of required tables are  Departments, Branches, Designations, Users, Employees, Suppliers, Devices, Products, Benchmarks');
if (!in_array($this->params->controller, $tables, true)) {
 if ($tableNotExist[0] == '/benchmarks')
  $this->redirect(array(
   'controller' => $tableNotExist[0],
   'action' => 'index'
   ));
else
  $this->redirect(array(
   'controller' => $tableNotExist[0],
   'action' => 'index'
   ));
}
}

public function delete_all($ids = null) {
  // Configure::write('debug',1);
  $i = 0;
  if ($_POST['data'][$this->name]['recs_selected'])
    $ids = explode('+', $_POST['data'][$this->name]['recs_selected']);
    $modelName = $this->modelClass;
    $record = Inflector::underscore($modelName);
    $record = Inflector::pluralize(Inflector::humanize($record));
    $this->loadModel('Approval');
    if (!empty($ids)) {
      foreach ($ids as $id) {
        if (!empty($id)) {
          // debug($id);
          $activeRecord = $this->$modelName->find('first',array('recursive'=>-1, 'conditions'=>array($modelName.'.id'=>$id)));
          debug($activeRecord);
          if($activeRecord[$modelName]['record_status'] == 1){
            $i++;
            continue;
          }else{
            echo "_______";
            $approves = $this->Approval->find('all',array('conditions'=>array('Approval.record'=>$id,'Approval.model_name'=>$modelName)));
            foreach($approves as $approve)
            {
              $approve['Approval']['soft_delete']=1;
              $this->Approval->save($approve, false);
            }
          $data['id'] = $id;
          $data['soft_delete'] = 1;
          // debug($data);
          // debug($modelName);
          $this->$modelName->save($data, false);
        }

        if(isset($id) && ($this->_count($ids)!= $i)){
      // debug($id);
        $data['id'] = $id;
        $data['model_action'] = $this->params['action'];
        $data['soft_delete'] = 1;
        $data['system_table_id'] = $this->_get_system_table_id();
        $modelName = $this->modelClass;
        
        // debug($modelName);
        // debug($data);
        $this->$modelName->save($data, false);
      }
    }

  }

}
if($i){
  $this->Session->setFlash(__('Selected %s deleted <br> Except (%d) values due to their pending approvals',$record,$i));
}else{
  $this->Session->setFlash(__('All selected %s deleted',$record));
}
if($this->request->params['controller'] == 'stocks'){
  $this->redirect($this->referer());

}else{
 $this->redirect(array(
  'action' => 'index'
  ));
}
}

public function purge_all($ids = null) {
  $flag = 0;
  if ($_POST['data'][$this->name]['recs_selected'])
   $ids = explode('+', $_POST['data'][$this->name]['recs_selected']);
 $modelName = $this->modelClass;
 $controller = Inflector::variable(Inflector::pluralize($modelName));
 $record = Inflector::underscore($modelName);
 $record = Inflector::pluralize(Inflector::humanize($record));
 $this->loadModel('Approval');
 $this->loadModel('FileUpload');
 if (!empty($ids)) {
   foreach ($ids as $id) {
    if (!empty($id)) {
     $this->$modelName->id = $id;
     if (!$this->$modelName->exists()) {
      throw new NotFoundException(__('Invalid detail'));
    }

    $this->request->onlyAllow('post', 'delete');
    $approves = $this->Approval->find('all',array('conditions'=>array('Approval.record'=>$id,'Approval.model_name'=>$modelName)));
    $fileUploads = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.record'=>$id)));
    foreach($approves as $approve)
    {
     if ($this->Approval->delete($approve['Approval']['id'], true))
     {
      $flag = 1;
    }
    else
    {
      $flag = 0;
      $this->Session->setFlash(__('All selected value was not deleted'));
      $this->redirect(array('action' => 'index'));
    }
  }

  foreach($fileUploads as $fileUpload)
  {
   if(!($this->FileUpload->delete($fileUpload['FileUpload']['id'], true)))
   {
    $this->Session->setFlash(__('All selected value was not deleted from Upload'));
    $this->redirect(array('action' => 'index'));
  }
}
$this->_deleteFile($id,$controller);

if ($this->$modelName->delete($id, true)) {
 $flag = 1;
}
else
{
 $flag = 0;
 $this->Session->setFlash(__('All %s was not deleted',$record));
 $this->redirect(array('action' => 'index'));
}
}
}
if ($flag) {
  $this->Session->setFlash(__('All selected %s deleted',$record));
  if($this->request->params['controller'] == 'stocks'){
   $this->redirect($this->referer());

 }else{
   $this->redirect(array(
    'action' => 'index'
    ));
 }
}
}
if($this->request->params['controller'] == 'stocks'){
 $this->redirect($this->referer());

}else{
 $this->redirect(array(
  'action' => 'index'
  ));
}
}

public function restore_all($ids = null) {
  if ($_POST['data'][$this->name]['recs_selected'])
   $ids = explode('+', $_POST['data'][$this->name]['recs_selected']);
 $modelName = $this->modelClass;
 $record = Inflector::underscore($modelName);
 $record = Inflector::pluralize(Inflector::humanize($record));
 $this->loadModel('Approval');
 if (!empty($ids)) {
   foreach ($ids as $id) {
    if (!empty($id)) {
      $approves = $this->Approval->find('all',array('conditions'=>array('Approval.record'=>$id,'Approval.model_name'=>$modelName)));
      foreach($approves as $approve)
      {
       $approve['Approval']['soft_delete']=0;
       $this->Approval->save($approve, false);
     }

     $data['id'] = $id;
     $data['soft_delete'] = 0;
     $this->$modelName->save($data, false);
   }
 }
}

$this->Session->setFlash(__('All %s restored',$record));
if($this->request->params['controller'] == 'stocks'){
 $this->redirect($this->referer());

}else{
 $this->redirect(array(
  'action' => 'index'
  ));
}
}

  /**
   * restore method
   *
   * @throws NotFoundException
   * @param string $id
   * @return void
   */
  public function restore($id = null) {
    $modelName = $this->modelClass;
    $record = Inflector::underscore($modelName);
    $record = Inflector::humanize($record);
    $this->loadModel('Approval');
    if (!empty($id)) {
      $approves = $this->Approval->find('all',array('conditions'=>array('Approval.record'=>$id,'Approval.model_name'=>$modelName)));
      foreach($approves as $approve)
      {
        $approve['Approval']['soft_delete']=0;
        $this->Approval->save($approve, false);
      }
      $data['id'] = $id;
      $data['soft_delete'] = 0;
      $modelName = $this->modelClass;
      $this->$modelName->save($data, false);
      $this->Session->setFlash(__('%s restored',$record));
    }

    if($this->request->params['controller'] == 'stocks'){
     $this->redirect($this->referer());

   }else{
     $this->redirect(array(
      'action' => 'index'
      ));
   }
 }

 public function delete($id = null,$parent_id=null) {
  echo "app"; 
  exit;
  $modelName = $this->modelClass;
  $record = Inflector::underscore($modelName);
  $record = Inflector::humanize($record);
  $this->loadModel('Approval');
  if (!empty($id)) {
   $approves = $this->Approval->find('all',array('conditions'=>array('Approval.record'=>$id,'Approval.model_name'=>$modelName)));
   foreach($approves as $approve)
   {
    $approve['Approval']['soft_delete']=1;
    $this->Approval->save($approve, false);
  }
  $data['id'] = $id;
  $data['soft_delete'] = 1;
  $data['model_action'] = $this->params['action'];
  $data['system_table_id'] = $this->_get_system_table_id();
  $modelName = $this->modelClass;
  $this->$modelName->save($data, false);
}
if($this->modelClass == 'FileUpload' || $this->modelClass == 'Stock'){
  $this->redirect($this->referer());
}elseif($this->request->params['controller'] == 'internal_audit_plan_departments') {
  $this->Session->setFlash(__('Selected %s Deleted',$record));
  $this->redirect(array('controller'=>'internal_audit_plans','action' => 'lists', $parent_id));
}elseif($this->request->params['controller'] != 'internal_audits') {
  $this->Session->setFlash(__('Selected %s Deleted',$record));
  $this->redirect(array('action' => 'index'));
} else {
  exit();
}
}

  /**
   * purge method
   *
   * @throws NotFoundException
   * @param string $id
   * @return void
   */
  public function purge($id = null) {
    $modelName = $this->modelClass;
    $controller = Inflector::variable(Inflector::pluralize($modelName));
    $record = Inflector::underscore($modelName);
    $record = Inflector::humanize($record);
    $this->$modelName->id = $id;
    $this->loadModel('Approval');
    $this->loadModel('FileUpload');
    if (!$this->$modelName->exists()) {
      throw new NotFoundException(__('Invalid detail'));
    }

    $approves = $this->Approval->find('all',array('conditions'=>array('Approval.record'=>$id,'Approval.model_name'=>$modelName)));
    $fileUploads = $this->FileUpload->find('all',array('conditions'=>array('FileUpload.record'=>$id)));

    foreach($approves as $approve)
    {
      if(!($this->Approval->delete($approve['Approval']['id'], true)))
      {
        $this->Session->setFlash(__('All selected value was not deleted from Approve'));
        $this->redirect(array('action' => 'index'));
      }
    }
    foreach($fileUploads as $fileUpload)
    {
     if(!($this->FileUpload->delete($fileUpload['FileUpload']['id'], true)))
     {
      $this->Session->setFlash(__('All selected value was not deleted from Upload'));
      $this->redirect(array('action' => 'index'));
    }
  }

  $this->_deleteFile($id, $controller);

  if ($this->$modelName->delete($id, true)) {
   $this->Session->setFlash(__('Selected %s Deleted',$record));
   $this->redirect(array(
    'action' => 'index'
    ));
 }
 $this->Session->setFlash(__('Selected %s was not deleted',$record));
 $this->redirect(array(
   'action' => 'index'
   ));
}

public function _deleteFile($record = null, $controller = null){
  $path = Configure::read('MediaPath') . 'files'. DS . $this->Session->read('User.company_id') . DS . "upload". DS . $this->Session->read('User.id') . DS . $controller . DS .$record;
  $folder = new Folder($path);
  if($folder->delete())
   return;
}


public function _get_notifications()
{
  $notificationCount = 0;
  $this->loadModel('Notification');
  $this->Notification->recursive = 0;
  $view_notifications = $this->Notification->NotificationUser->find('all',array('fields'=>array(''),
   'conditions'=>array('NotificationUser.employee_id'=>$this->Session->read('User.employee_id'))));
  $date = date('Y-m-d');
  $notificationCount = $this->Notification->NotificationUser->find('count',array('conditions'=>array('NotificationUser.employee_id'=>$this->Session->read('User.employee_id'), 'NotificationUser.status'=>0)));

  $this->set(compact('notificationCount'));

}

public function _get_suggestions(){
  $this->loadModel('SuggestionForm');
  $suggestionCount = 0;
  $suggestionCount = $this->SuggestionForm->find('count',array('conditions'=>array('SuggestionForm.publish'=>1, 'SuggestionForm.soft_delete'=>0,'SuggestionForm.status'=>0,'SuggestionForm.employee_id'=>$this->Session->read('User.employee_id')),'recursive'=>-1));
  if((isset($suggestionCount) && $suggestionCount > 0 )){
   $this->set(array('suggestionCount'=>$suggestionCount));
 }else{
   $this->set(array('suggestionCount'=>null));
 }

}


public function _get_messages() {
  $this->loadModel('MessageUserInbox');
  $messageCount = $this->MessageUserInbox->find('count', array(
   'conditions' => array(
    'MessageUserInbox.user_id' => $this->Session->read('User.id'),
    'MessageUserInbox.status' => 0
    ),
   'recursive' => - 1
   ));
  if ($messageCount && $messageCount > 0) {
   $this->set(array(
    'messageCount' => $messageCount
    ));
 } else {
   $this->set(array(
    'messageCount' => null
    ));
 }
}

public  function _format_file_size($data) {

    // Bytes

  if ($data < 1024) {
   return $data . " bytes";
 }

    // Kilobytes
 else
  if ($data < 1024000) {
   return round(($data / 1024), 1) . "k";
 }

    // Megabytes
 else {
   return round(($data / 1024000), 1) . "MB";
 }
}

public function _get_template() {
  return false;
  $this->loadModel('SystemTable');
  $this->SystemTable->recursive = - 1;
  $system_tbl_id = $this->SystemTable->find('first', array(
   'conditions' => array(
    'SystemTable.system_name' => $this->request->params['controller']
    )
   ));
  $this->loadModel('CustomTemplate');
  $this->CustomTemplate->recursive = 0;
  $templates = $this->CustomTemplate->find('list', array(
   'conditions' => array(
    'CustomTemplate.system_table_id' => $system_tbl_id['SystemTable']['id']
    )
   ));
  return $templates;
}

public function get_header($header = null, $formats = null) {
  $this->loadModel('SystemTable');
  $system_table = $this->SystemTable->find('first', array(
   'conditions' => array(
    'SystemTable.id' => $formats
    )
   ));
  $master = $this->SystemTable->MasterListOfFormat->find('first', array(
   'conditions' => array(
    'MasterListOfFormat.system_table_id' => $system_table['SystemTable']['id']
    )
   ));
  $header = str_replace('&lt;FlinkISO&gt;', '<FlinkIso>', $header);
  $header = str_replace('&lt;/FlinkISO&gt;', '</FlinkIso>', $header);
  $header = str_replace('<a class="badge label-info add-margin" href=" ', '', $header);
  $header = str_replace('</a>', '', $header);
  $header = str_replace('&nbsp;', '', $header);
  $to_delete = $this->_get_between($head, '</FlinkIso>', '</a>');
  $header = str_replace($to_delete, '', $header);
  $company_name = $this->_get_company();
  $header = str_replace('Company Name', $company_name['Company']['name'], $header);
  $headers = explode('<td>', $header);
  foreach ($headers as $head):
   $header_fields = $this->_get_between($head, '<FlinkIso>', '</FlinkIso>');
 $split_fields = explode('-', $header_fields);
 $get_field = ltrim(rtrim($split_fields[1]));
 if ($get_field)
  $get_value = $master['MasterListOfFormat'][$get_field];
if ($get_value) {
  $add_value = $this->_get_between($head, '<FlinkIso>', '</td>');
  $field_values = str_replace('<FlinkIso>', '', str_replace($add_value, $get_value, $head));
  $header = str_replace($add_value, $get_value, $header);
}
endforeach;
return $header;
}

public function _generate_report() {
  $this->loadModel('CustomTemplate');
  $template = $this->CustomTemplate->find('first', array(
   'conditions' => array(
    'CustomTemplate.id' => $this->request->data[$this->modelClass]['template_id']
    )
   ));
  $this->set('template', $template);
  $model = $this->modelClass;
  $result = explode('+', $this->request->data[$model]['rec_selected']);
  $reports = $this->$model->find('all', array(
   'conditions' => array(
    'or' => array(
     $this->modelClass . '.id' => $result
     )
    )
   ));
  $getHeader = $this->get_header($template['CustomTemplate']['header'], $template['CustomTemplate']['system_table_id']);
  $tables = explode('<table', $template['CustomTemplate']['template_body']);
  unset($tables[0]);
  foreach ($tables as $table):
   $multiple = strstr($table, 'class=" multiple', true);
 $single = strstr($table, 'class=" single"');
 if ($single) {
  $fields = null;
  $firstRow = $this->_get_between($table, '<tbody>', '<td>');
  $i = 0;
  $x = explode('<td>', $table);
  foreach ($x as $y):
   $z = explode('<br />', $y);
 foreach ($z as $mix):
  $fields[$i][] = $this->_get_between($mix, '%20%3CFlinkISO%3E%20', '%20%3C/FlinkISO%3E');
endforeach;
$i++;
endforeach;
unset($fields[0]);
$i = 0;
$drawSingle = null;
foreach ($reports as $report):
 $drawSingle.= "<tr>";
foreach ($fields as $final):
  $drawSingle.= "<td>";
foreach ($final as $f):
 $xxx = explode('-', $f);
$finalData[$i][$xxx[1]][$xxx[2]] = $report[$xxx[1]][$xxx[2]];
$drawSingle.= $finalData[$i][$xxx[1]][$xxx[2]] . " ";
endforeach;
$drawSingle.= "</td>";
endforeach;
$drawSingle.= "</tr>";
$i++;
endforeach;
$draw.= '<table width="100%" border="1"><tr>' . $firstRow . '</tr>' . $drawSingle . '</table><br />';
}
elseif ($multiple) {
  $firstRow = $this->_get_between($table, '<tbody>', '<td>');
  $i = 0;
  $x = explode('<td>', $table);
  $fields = null;
  foreach ($x as $y):
   $z = explode('<br />', $y);
 foreach ($z as $mix):
  $fields[$i][] = $this->_get_between($mix, '%20%3CFlinkISO%3E%20', '%20%3C/FlinkISO%3E');
endforeach;
$i++;
endforeach;
unset($fields[0]);
$result = explode('+', $this->request->data[Inflector::pluralize($this->modelClass)]['rec_selected']);
$i = 0;
$drawMultiple = null;
foreach ($reports as $report):
 $drawMultiple.= "<tr>";
foreach ($fields as $final):
  $drawMultiple.= "<td>";
foreach ($final as $f):
 $xxx = explode('-', $f);
$finalData[$i][$xxx[1]][$xxx[2]] = $report[$xxx[1]][$xxx[2]];
$drawMultiple.= $finalData[$i][$xxx[1]][$xxx[2]] . " ";
endforeach;
$drawMultiple.= "</td>";
endforeach;
$drawMultiple.= "</tr>";
$i++;
endforeach;
$draw.= '<table width="100%" border="1"><tr>' . $firstRow . '</tr>' . $drawMultiple . '</table><br />';
}
else {
  $this->Session->setFlash(__('Please make sure you have created the proper report. It looks like you have not defined the table class (single/multiple) '));
  $this->redirect(array(
   'controller' => 'custom_templates',
   'action' => 'edit',
   $this->request->data[$this->modelClass]['template_id']
   ));
}

endforeach;
$draw = $getHeader . $draw;
$this->set('drawRecords', $draw);
}

public function _get_between($content, $start, $end) {
  $result = explode($start, $content);
  if (isset($result[1])) {
   $result = explode($end, $result[1]);
   return $result[0];
 }
 return '';
}

public function publish_record($id = null) {
  $modelName = $this->modelClass;
  if($modelName == 'Meeting'){
    $this->Session->setFlash(__("You can't publish meeting records."));
    $this->redirect(array(
     'action' => 'index'
     ));
  }
  if ($modelName == 'MaterialQualityCheck') {
   $allMQC = $this->MaterialQualityCheck->find('all', array('conditions' => array('MaterialQualityCheck.material_id' => $id), 'recursive' => -1));

   foreach ($allMQC as $mQC) {
    $data['id'] = $mQC['MaterialQualityCheck']['id'];
    $data['material_id'] = $mQC['MaterialQualityCheck']['material_id'];
    $data['publish'] = 1;
    $data['model_action'] = $this->params['action'];
    $data['system_table_id'] = $this->_get_system_table_id();
    $this->$modelName->save($data, false);
  }
  $this->Session->setFlash(__("Record published successfully."));

} else {
  $data['id'] = $id;
  $data['publish'] = 1;
  $data['model_action'] = $this->params['action'];
  $data['system_table_id'] = $this->_get_system_table_id();
  $this->$modelName->save($data, false);
  $this->Session->setFlash(__("Record published successfully."));
}
$this->redirect(array(
 'action' => 'index'
 ));
}

public function language_details() {
  $this->loadModel('Language');
  $languageData = array();
  return $languageData = $this->Language->find('all', array('recursive'=>-1));
}

public function advance_search_common($conditions = array()) {

  $model = $this->modelClass;
  if ($this->request->query['prepared_by'] != -1) {
   $prepared_byConditions[] = array($model.'.prepared_by' => $this->request->query['prepared_by']);
   if ($this->request->query['strict_search'] == 0)
    $conditions[] = array('and' => $prepared_byConditions);
  else
    $conditions[] = array('or' => $prepared_byConditions);
}

if ($this->request->query['approved_by'] != -1) {
 $approved_byConditions[] = array($model.'.approved_by' => $this->request->query['approved_by']);
 if ($this->request->query['strict_search'] == 0)
  $conditions[] = array('and' => $approved_byConditions);
else
  $conditions[] = array('or' => $approved_byConditions);
}         

return $conditions;

}

public function _save_approvals($approval_model = null, $data = null)  {
  // clear revision values
  if(isset($this->request->data['MasterListOfFormat']['revision_number'])){
    unset($this->request->data['MasterListOfFormat']['revision_number']);
  }
  
  if ((isset($this->request->data['Approval'])) && 
    ($this->request->data[$this->modelClass]['publish'] == 0 ) && 
    ($this->request->data['Approval']['user_id'] != -1 && $this->request->data['Approval']['user_id'] != 1)) 
    {
    if(Inflector::humanize($this->modelClass) == 'ChangeAdditionDeletionRequest')$m = 'Change Request';
    else $m = Inflector::humanize($this->modelClass);

    $this->loadModel('Approval');
    $this->loadModel($this->modelClass);
    if ($this->request->action == 'approve' || $this->request->action == 'add_quality_check') 
    {

      if($this->request->data['Approval']['send_back'] == 1)$status = 'Sent Back';
      if($this->request->data['Approval']['send_forward'] == 1)$status = 'Forwarded';

      if($status == 'Sent Back')$user_id = $this->request->data['Approval']['send_back_user_id'];
      else $user_id = $this->request->data['Approval']['send_forward_user_id'];

      if($status  == 'Forwarded')
      {
        $step = $this->request->data['Approval']['next_auto_step'];
        $step_id = $this->data['Approval']['next_auto_approval_step_id'];

      }
      else{
        $step = $this->request->data['Approval']['auto_step'];
        $step_id = $this->data['Approval']['auto_approval_step_id'];
      }  

      if($this->request->data['Approval']['send_back'] == 0 && $this->request->data['Approval']['send_forward'] == 0){
        $user_id = $this->request->data['Approval']['user_id'];
        $status = 'Forwarded';
      }    

      

      if(strlen($user_id) > 2){
        $approval_data = array();
        $approval_data['id'] =  $this->request->params['pass'][1];
        $approval_data['record_status'] =  '0';
        $approval_data['status'] = $status;
        $this->Approval->save($approval_data, false);

        $this->Approval->create();
        $this->request->data['Approval']['model_name'] = $this->modelClass;
        $this->request->data['Approval']['controller_name'] = $this->request->params['controller'];
        $this->request->data['Approval']['user_id'] = $user_id;
        $this->request->data['Approval']['from'] = $this->Session->read('User.id');
        $this->request->data['Approval']['auto_approval_id'] = $this->request->data['Approval']['auto_approval_id'];
        $this->request->data['Approval']['auto_approval_step_id'] = $step_id;
        $this->request->data['Approval']['approval_step'] = $step;
        $this->request->data['Approval']['record'] = $this->{$this->modelClass}->id;
        $this->request->data['Approval']['status'] = $status;
        $this->request->data['Approval']['record_status'] = 1;
        $this->request->data['Approval']['status_user_id'] = $this->request->data['Approval']['user_id'];
        $this->Approval->save($this->request->data['Approval'], false);

        $this->request->data[$this->modelClass]['id'] = $this->{$this->modelClass}->id;
        $this->request->data[$this->modelClass]['record_status'] = 1;
        $this->request->data[$this->modelClass]['status_user_id'] = $this->request->data['Approval']['user_id'];
        $this->{$this->modelClass}->save($this->request->data[$this->modelClass],false);

        $this->Session->setFlash(__('The ' . $m . ' has been saved and sent for approval.'));
        $this->_send_approval_email($user_id);
        $this->redirect(array('controller' => $this->request->params['controller'], 'action' => 'view', $this->{$this->modelClass}->id));
      }else{
        $this->Session->setFlash(__('The ' . $m . ' has been saved but not sent for approval yet. Click on Edit to sent record for approval'));
        $this->redirect(array('controller' => $this->request->params['controller'], 'action' => 'view', $this->{$this->modelClass}->id));
      }

    }else{

      if($this->request->data['Approval']['send_back'] == 1)$status = 'Sent Back';
      if($this->request->data['Approval']['send_forward'] == 1)$status = 'Forwarded';

      if($status  == 'Forwarded')
      {
        $step = $this->request->data['Approval']['auto_step'];
        $step_id = $this->data['Approval']['next_auto_approval_step_id'];
        $user_id = $this->request->data['Approval']['send_forward_user_id'];

      }
      else{
        $step = $this->request->data['Approval']['auto_step'];
        $step_id = $this->data['Approval']['auto_approval_step_id'];
        $user_id = $this->request->data['Approval']['send_back_user_id'];
      }    

      if($this->request->data['Approval']['send_back'] == 0 && $this->request->data['Approval']['send_forward'] == 0){
        $user_id = $this->request->data['Approval']['user_id'];
        $status = 'Forwarded';
      }

      if(strlen($user_id) > 2){
        $this->Approval->create();
        $this->request->data['Approval']['model_name'] = $this->modelClass;
        $this->request->data['Approval']['controller_name'] = $this->request->params['controller'];
        $this->request->data['Approval']['user_id'] = $user_id;
        $this->request->data['Approval']['from'] = $this->Session->read('User.id');
        $this->request->data['Approval']['auto_approval_id'] = $this->request->data['Approval']['auto_approval_id'];
        $this->request->data['Approval']['auto_approval_step_id'] = $step_id;
        $this->request->data['Approval']['approval_step'] = $step;
        $this->request->data['Approval']['record'] = $this->{$this->modelClass}->id;
        $this->request->data['Approval']['status'] = $status;
        $this->request->data['Approval']['record_status'] = 1;
        $this->request->data['Approval']['status_user_id'] = $this->request->data['Approval']['user_id'];
        $this->request->data['Approval']['approved_by'] = $this->Session->read('User.employee_id');
        $this->request->data['Approval']['prepared_by'] = $this->request->data[$this->modelClass]['prepared_by'];
        $this->Approval->save($this->request->data['Approval'], false);
        unset($this->request->data[$this->modelClass]['issue_number']);
        
        $this->request->data[$this->modelClass]['id'] = $this->{$this->modelClass}->id;
        $this->request->data[$this->modelClass]['record_status'] = 1;
        $this->request->data[$this->modelClass]['status_user_id'] = $this->request->data['Approval']['user_id'];
        $this->{$this->modelClass}->save($this->request->data[$this->modelClass],false);
        $this->_send_approval_email($user_id);
        $this->Session->setFlash(__('The ' . $m . ' has been saved and sent for approval.'));
      }else{
        $this->Session->setFlash(__('The ' . $m . ' has been saved but not sent for approval yet. Click on Edit to sent record for approval'));
        $this->redirect(array('controller' => $this->request->params['controller'], 'action' => 'view', $this->{$this->modelClass}->id));
      }

    }
  } elseif ($this->request->data[$this->modelClass]['publish'] == 1) {
    if($this->request->data['Approval']['send_back'] == 1)$status = 'Sent Back';
    if($this->request->data['Approval']['send_forward'] == 1)$status = 'Forwarded';

    if($status  == 'Forwarded')
    {
      $step = $this->request->data['Approval']['auto_step'];
      $step_id = $this->data['Approval']['next_auto_approval_step_id'];
      $user_id = $this->request->data['Approval']['send_forward_user_id'];

    }
    else{
      $step = $this->request->data['Approval']['auto_step'];
      $step_id = $this->data['Approval']['auto_approval_step_id'];
      $user_id = $this->request->data['Approval']['send_back_user_id'];
    }    

    if($this->request->data['Approval']['send_back'] == 0 && $this->request->data['Approval']['send_forward'] == 0){
      $user_id = $this->request->data['Approval']['user_id'];
      $status = 'Forwarded';
    }



    $this->loadModel('Approval');
    if ($this->request->action == 'approve' || $this->request->action == 'add_quality_check') {

      if($this->request->params['pass'][1]){
        
        $approve['Approval']['id'] =  $this->request->params['pass'][1];
        $approve['Approval']['status'] = 'Approved';
        $approve['Approval']['record_status'] = 0;
        $approve['Approval']['approved_by'] = $this->Session->read('User.employee_id');
        $approve['Approval']['prepared_by'] = $this->request->data[$this->modelClass]['prepared_by'];
        $approve['Approval']['comments'] = $this->request->data['Approval']['comments'];
        $this->Approval->save($approve, false);

      }
        
      unset($this->request->data[$this->modelClass]['issue_number']);
      
      $this->request->data[$this->modelClass]['id'] = $this->{$this->modelClass}->id;
      $this->request->data[$this->modelClass]['record_status'] = 0;
      $this->request->data[$this->modelClass]['approved_by'] = $this->Session->read('User.employee_id');
      $this->{$this->modelClass}->save($this->request->data[$this->modelClass],false);
      if($approval_model == 'tni')$this->_send_tni_email($data);
      $this->Session->setFlash(__('The ' . $m . ' has been approved.'));
      
      if($this->modelClass == 'MaterialQualityCheckDetail'){
        $this->redirect(array(
            'controller'=>'material_quality_checks',
            'action'=>'quality_check',
            'delivery_challan_id'=>$this->data['MaterialQualityCheckDetail']['delivery_challan_id'],
            'material_id'=>$this->data['MaterialQualityCheckDetail']['material_id'],
            'delivery_challan_detail_id'=>$this->data['MaterialQualityCheckDetail']['delivery_challan_detail_id']            
        ));
      }else{
        if($this->request->params['controller'] == 'production_rejections'){
          $this->redirect(array('controller'=>'productions', 'action' => 'view', $this->request->data['ProductionRejection']['production_id']));
        }else{
          $this->redirect(array('controller' => $this->request->params['controller'], 'action' => 'view', $this->{$this->modelClass}->id));    
        }
        
      }
      
    } else {
      $this->Session->setFlash(__('The ' . $m . ' has been published.'));
      if($approval_model == 'tni')$this->_send_tni_email($data);

    }
  }else{

    $this->Session->setFlash(__('Please select a user to send this record for further approval or publish the record.'), 'default', array('class'=>'alert-danger'));
  }

}

public function _send_approval_email($user_to_send = null){
  $this->loadModel('User');
  $get_employee = $this->User->find('first',array(
    'fields'=>array('User.id','User.employee_id'),
    'conditions'=>array('User.id'=>$user_to_send),
    'recursive'=>0
    ));
  $this->loadModel('Employee');
  $employee = $this->Employee->find('first',array('conditions'=>array('Employee.id'=>$get_employee['User']['employee_id']), 
    'fields'=>array('id', 'office_email','personal_email'), 'recursive'=>-1));
  $officeEmailId = $employee['Employee']['office_email'];
  $personalEmailId = $employee['Employee']['personal_email'];
  if ($officeEmailId != '') {
    $email = $officeEmailId;
  } else if ($personalEmailId != '') {
    $email = $personalEmailId;
  }

  try{
    App::uses('CakeEmail', 'Network/Email');
    if($this->Session->read('User.is_smtp') == 1)
      $EmailConfig = new CakeEmail("smtp");
    if($this->Session->read('User.is_smtp') == 0)
      $EmailConfig = new CakeEmail("default");
    $EmailConfig->to($email);
   
    $model = Inflector::classify($this->request->controller);
    $this->loadModel($model);
    $title = $this->request->data[$model][$this->$model->displayField];
    
    if($this->request->controller == 'master_list_of_formats' or $this->request->controller == 'change_addition_deletion_requests' ){
      $EmailConfig->subject('FlinkISO : Document Approval Email');  
    }else{
      $EmailConfig->subject('FlinkISO : You have a approval request');
    }
    
    if(Configure::read('evnt') == 'Dev')$env = 'DEV';
    elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
    else $env = "";

    $EmailConfig->template('approval_request');
    $EmailConfig->viewVars(array('user' => $user_to_send,'controller'=>$this->request->controller,'title'=>$title,'env' => $env, 'app_url' => FULL_BASE_URL));
    $EmailConfig->emailFormat('html');
    $EmailConfig->send();
  } catch(Exception $e) {
    $this->Session->setFlash(__('The user has been saved but fail to send email. Please check smtp details.', true), 'smtp');
    $this->redirect(array('action' => 'index'));
  }
}
public function _getRecordFiles($rec_id = null,$system_table_id = null, $product = NULL){
    if($rec_id && $product == NULL && $system_table_id != 'dashboards'  && $system_table_id != 'clauses')$conditions = array('FileUpload.publish'=>1,'FileUpload.soft_delete'=>0,'FileUpload.record'=>$this->request->params['pass'][0],'FileUpload.system_table_id'=>$system_table_id['SystemTable']['id']);
    elseif($rec_id && $product)$conditions = array('FileUpload.publish'=>1,'FileUpload.soft_delete'=>0,'FileUpload.record'=>$this->request->params['pass'][1],'FileUpload.system_table_id'=>$system_table_id['SystemTable']['id'],'FileUpload.file_dir Like'=>'%'.$this->request->params['pass'][0].'%');
    elseif($rec_id && $product == NULL && $system_table_id == 'dashboards' )$conditions = array('FileUpload.publish'=>1,'FileUpload.soft_delete'=>0,'FileUpload.record'=>str_replace(' ', '_', strtolower($this->request->params['pass'][0])),'FileUpload.system_table_id'=>$system_table_id);
    elseif($system_table_id == 'clauses' )$conditions = array( 'FileUpload.publish'=>1,'FileUpload.soft_delete'=>0,'FileUpload.record'=>$rec_id,'FileUpload.file_dir LIKE'=> '%'.$this->request->params['pass'][1].'%', 'FileUpload.system_table_id'=>'clauses');
    elseif($system_table_id['SystemTable']['id'] == '5297b2e6-82b4-4d86-bb85-2d8f0a000005')$conditions = array('FileUpload.system_table_id'=>'5297b2e7-3538-4360-97fc-2d8f0a000005', 'FileUpload.publish'=>1,'FileUpload.soft_delete'=>0,'FileUpload.record'=>$this->viewVars['changeAdditionDeletionRequest']['MasterListOfFormat']['id']);
    else $conditions = array('FileUpload.publish'=>1,'FileUpload.soft_delete'=>0,'FileUpload.record'=>$this->request->action);
    $conditions = array($conditions,'FileUpload.archived'=>0);
    $this->loadModel('FileUpload');
    $this->FileUpload->recursive = 0;
    $files = $this->FileUpload->find('all',array(
        'order'=>array('FileUpload.file_details'=>'ASC','FileUpload.created'=>'DESC'),
        'group'=>array('FileUpload.version_key'),
        'fields'=>array(
            'User.id','User.name',
            'CreatedBy.id','CreatedBy.name',
            'FileUpload.file_details',
            'FileUpload.record',
            'FileUpload.system_table_id',
            'FileUpload.file_type',
            'FileUpload.file_dir',
            'FileUpload.version',
            'FileUpload.comment',
            'FileUpload.created',
            'FileUpload.created_by',
            'PreparedBy.name',
            'ApprovedBy.name',
            'FileUpload.modified',
            'FileUpload.modified_by',
            'FileUpload.file_status',
            'FileUpload.archived'
            ),
        'conditions'=>$conditions));
    $this->set('files',$files);
}

public function _getApprovalFiles($rec_id = null,$system_table_id = null, $product = NULL){
  $conditions = array('FileUpload.publish'=>1,'FileUpload.soft_delete'=>0,'FileUpload.record'=>'approvals','FileUpload.archived'=>0);
  $this->loadModel('FileUpload');
  $this->FileUpload->recursive = 0;
  $files = $this->FileUpload->find('all',array(
    'order'=>array('FileUpload.result'=>'DESC'),
    'fields'=>array(
      'User.id','User.name',
      'CreatedBy.id','CreatedBy.name',
      'FileUpload.file_details',
      'FileUpload.record',
      'FileUpload.file_type',
      'FileUpload.file_dir',
      'FileUpload.version',
      'FileUpload.comment',
      'FileUpload.created',
      'FileUpload.created_by',
      'PreparedBy.name',
      'ApprovedBy.name',
      'FileUpload.modified',
      'FileUpload.modified_by',
      'FileUpload.file_status',
      'FileUpload.archived'
      ),
    'conditions'=>$conditions));
  $this->set('approvalfiles',$approvalfiles);

    //get system table id for approvals table
  $this->loadModel('SystemTable');
  $this->SystemTable->recursive = -1;

  $this->set('approvalSystemTableId',$this->SystemTable->find('first',array('fields'=>array('SystemTable.id'),'conditions'=>array('SystemTable.system_name'=>'approvals'))));

}

/**
   * Gettting list of revisions
   *
   */
public function document_revisions($id = null){
  $this->loadModel('MasterListOfFormat');
  $revisions = $this->MasterListOfFormat->DocumentAmendmentRecordSheet->find('all',array('conditions'=>
     array('DocumentAmendmentRecordSheet.master_list_of_format'=>$id),
     'fields'=>array(
      'DocumentAmendmentRecordSheet.id',
      'DocumentAmendmentRecordSheet.master_list_of_format',
      'DocumentAmendmentRecordSheet.document_number',
      'DocumentAmendmentRecordSheet.issue_number',
      'DocumentAmendmentRecordSheet.revision_number',
      'DocumentAmendmentRecordSheet.amendment_details',
      'DocumentAmendmentRecordSheet.reason_for_change',
      'DocumentAmendmentRecordSheet.revision_date',
      'DocumentAmendmentRecordSheet.reason_for_change',
      'DocumentAmendmentRecordSheet.prepared_by',
      'DocumentAmendmentRecordSheet.approved_by',
      'PreparedBy.name','PreparedBy.id',
      'ApprovedBy.name','ApprovedBy.id',
      'MasterListOfFormatID.title')
     ));
    $this->set(compact('revisions'));
}

public function _upload_add($filename, $ext, $message, $dir,$prepared_by) {
  if($message != 'Issue'){
        $path = $dir;
        $newpath = explode(DS, $path);
        $this->loadModel('FileUpload');
        $this->FileUpload->create();
        $newFileUploadData = array();
        $filename=str_replace(' ','',$filename);
        $filename=str_replace('.','',$filename);
        $filename = preg_replace('/\s+/', '',$filename);

  if(isset($prepared_by) && $prepared_by != '')$prepared_by = $prepared_by;
  else $prepared_by = $this->Session->read('User.employee_id');
  
  $this->loadModel('MasterListOfFormat');
  $master = $this->MasterListOfFormat->find('first', array(
   'conditions' => array(
    'MasterListOfFormat.system_table_id' => $this->_get_system_table($newpath[2])
    ),
   'fields'=>array('MasterListOfFormat.id')

   ));       

  if($newpath[1]=='documents' && $newpath[2] == NULL){
    $newFileUploadData['FileUpload']['system_table_id'] = $this->_get_system_table('users');
    if(!$newpath[3])$newFileUploadData['FileUpload']['record'] = $newpath[2];
  }elseif($newpath[1]=='clauses'){
    $newFileUploadData['FileUpload']['system_table_id'] = "clauses";
    $newFileUploadData['FileUpload']['record'] = $newpath[2];
  }elseif($newpath[1]=='documents' && $newpath[2] != NULL){
    $newFileUploadData['FileUpload']['system_table_id'] = 'dashboards';
    if(!$newpath[3])$newFileUploadData['FileUpload']['record'] = $newpath[2];
  }elseif($newpath[1]=='products' && $newpath[2] != NULL){
    $newFileUploadData['FileUpload']['system_table_id'] = $this->_get_system_table($newpath[1]);
    $newFileUploadData['FileUpload']['record'] = $newpath[2];            
  }else{
    if(!$this->_get_system_table($newpath[2]))$newFileUploadData['FileUpload']['system_table_id'] = 'dashboards';
    else $newFileUploadData['FileUpload']['system_table_id'] = $this->_get_system_table($newpath[2]);
    
    if(!$newpath[3])$newFileUploadData['FileUpload']['record'] = $newpath[2];
    else $newFileUploadData['FileUpload']['record'] = $newpath[3];
  }
             //find if file is under revision, if yes, revert the upload and delete the file and rollback the version

      $newFileUploadData['FileUpload']['file_details'] = $filename;
      $newFile = explode('-ver-',$filename);
      $newFileUploadData['FileUpload']['version'] = $newFile[1];
      if($newFile[1] == 1)
      {
          $newFileUploadData['FileUpload']['comment'] = "First Upload";
      }else{
          $newFileUploadData['FileUpload']['comment'] = "Revision";
      }
      $newFileUploadData['FileUpload']['user_id'] = $this->Session->read('User.id');
      $newFileUploadData['FileUpload']['file_type'] = $ext;
      $newFileUploadData['FileUpload']['file_dir'] = str_replace('//','/',$dir .'/'.$filename .'.'.$ext);
      $newFileUploadData['FileUpload']['file_status'] = 1;
      $newFileUploadData['FileUpload']['result'] = $message;
      $newFileUploadData['FileUpload']['publish'] = 1;
      $newFileUploadData['FileUpload']['soft_delete'] = 0;
      $newFileUploadData['FileUpload']['user_session_id'] = $this->Session->read('User.user_session_id');
      $newFileUploadData['FileUpload']['created_by'] = $this->Session->read('User.id');
      $newFileUploadData['FileUpload']['prepared_by'] = $prepared_by;
      $newFileUploadData['FileUpload']['approved_by'] = $this->Session->read('User.employee_id');
      $newFileUploadData['FileUpload']['modified_by'] = $this->Session->read('User.id');
      $newFileUploadData['FileUpload']['master_list_of_format_id'] = $master['MasterListOfFormat']['id'];
      $hash = Security::hash($fileUpload['FileUpload']['system_table_id'].$fileUpload['FileUpload']['record']);       
      $newFileUploadData['FileUpload']['file_key'] = $hash;
      $this->FileUpload->save($newFileUploadData);
      $this->FileUpload->read(null,$this->FileUpload->id);
      $this->FileUpload->set('version_key',$this->FileUpload->id);
      $this->FileUpload->save();

      $this->loadModel('FileShare');
      $data['FileShare']['file_upload_id'] =  $this->FileUpload->id;
      $data['FileShare']['branch_id'] = $this->Session->read('User.branch_id'); ;
      $data['FileShare']['everyone'] = 0 ;
      $data['FileShare']['users'] = json_encode(array($this->Session->read('User.id')));
      $data['FileShare']['publish'] = 1;
      $data['FileShare']['soft_delete'] = 0;
      $data['FileShare']['created_by'] = $this->Session->read('User.id');                 
      $data['FileShare']['modified_by'] = $this->Session->read('User.id');                
      $data['FileShare']['user_session_id'] = $this->Session->read('User.user_session_id');
      $data['FileShare']['branchid'] = $this->Session->read('User.branch_id');
      $data['FileShare']['departmentid'] = $this->Session->read('User.department_id') ;
      $data['FileShare']['company_id'] = $this->Session->read('User.company_id');
      $data['FileShare']['master_list_of_format_id'] = $master['MasterListOfFormat']['id'];
      $this->FileShare->create();
      $this->FileShare->save($data['FileShare'],false);
      if($newFile[1] > 1)
        {
            $this->_add_revs($newFileUploadData,$this->FileUpload->id);
        }
        return $this->FileUpload->id;
    }else{
      return false;
    }
}


public function _add_revs($newFileUploadData = null, $id = null){
    $this->loadModel('FileUpload');
    $file_dir = explode("-ver-",$newFileUploadData['FileUpload']['file_details']);
    $file_direcoty = explode( DS ,$newFileUploadData['FileUpload']['file_dir']);
    if($file_direcoty[2]=='products'){
        $rev_conditions = array(
            'FileUpload.system_table_id'=>$newFileUploadData['FileUpload']['system_table_id'],
            'FileUpload.record'=>$newFileUploadData['FileUpload']['record'],
            'FileUpload.id <> ' => $id,
            'FileUpload.file_dir like' => '%' . $file_direcoty[4] . DS  . $file_dir[0]."-ver-%".$newFileUploadData['FileUpload']['file_type']
            );
    }else{
        $rev_conditions = array(
            'FileUpload.system_table_id'=>$newFileUploadData['FileUpload']['system_table_id'],
            'FileUpload.record'=>$newFileUploadData['FileUpload']['record'],
            'FileUpload.id <> ' => $id,
            'FileUpload.file_dir like' => '%' . $file_dir[0]."-ver-%".$newFileUploadData['FileUpload']['file_type']
            );
    }
    $revs = $this->FileUpload->find('all',array(
        'conditions'=> $rev_conditions,
        'fields'=>array(
            'FileUpload.file_details',
            'FileUpload.id',
            )
        ));

    foreach($revs as $rev):
        $this->FileUpload->read(null,$rev['FileUpload']['id']);
    $revData['FileUpload']['id'] = $rev['FileUpload']['id'];
    $revData['FileUpload']['archived'] = 1;
    if($rev['FileUpload']['status'] == 3){
        $revData['FileUpload']['comment'] = 'New Document uploaded on ' . date('Y-m-d');
    }
    $this->FileUpload->set($revData);
    $this->FileUpload->save($revData,true);
    endforeach;
}


public function send_customise(){

  try{
   $url = "https://www.flinkiso.com/customization_requests.php";
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
   curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
   curl_setopt($ch, CURLOPT_HEADER, TRUE);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64; rv:31.0) Gecko/20100101 Firefox/31.0"));
   $postfields = array();
   $postfields['customization_title'] = urlencode($this->request->data['customize']['customization_title']);
   $postfields['company'] = urlencode($this->request->data['customize']['company']);
   $postfields['branch_name'] = urlencode($this->request->data['customize']['branch_name']);
   $postfields['employee'] = urlencode($this->request->data['customize']['employee']);
   $postfields['request_for'] = urlencode($this->request->data['customize']['request_for']);
   $postfields['customization_details'] = urlencode($this->request->data['customize']['customization_details']);

   curl_setopt($ch, CURLOPT_POST, $this->_count($postfields));
   curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
   $ret = curl_exec($ch);
   curl_close($ch);

   echo '<div class="alert alert-success">Thank you for suggestion. <br/><br/>We will get back to you soon.<br /></div>';
 } catch(Exception $e){
   echo '<div class="alert alert-danger">We are unable to forward your reqest at this time. You can call us directly on +91 8451956565.<br />Thank You.</div>';
 }
 exit;
}

public function export_pdf_data() {
  $records = $this->request->data['file_uploads']['rec_selected'];
  $newRecords = explode("+", $records);
  unset($newRecords[0]);
  $openModel = $this->request->data['file_uploads']['model_name'];
  $this->loadModel($openModel);
  foreach ($this->request->data['file_uploads']['fields'] as $key => $value):
   $newModel = $this->request->data['file_uploads']['model_name'];
 if (strpos($value, "_id") || $value == 'assigned_to' || $value == 'calibration_frequency' || $value == 'maintenance_frequency' || $value == 'master_list_of_format') {
  foreach ($this->$newModel->belongsTo as $belongToKey => $belongToVal) {
   if ($belongToVal['foreignKey'] == $value) {
    if ($this->$belongToVal['className']->isVirtualField($this->$belongToVal['className']->displayField)) {
     $displayField = $this->$belongToVal['className']->getVirtualField($this->$belongToVal['className']->displayField) . ' as ' . $belongToKey . '_' . $this->$belongToVal['className']->displayField;
   } else {
     $displayField = $belongToKey . '.' . $this->$belongToVal['className']->displayField;
   }
   $getFields[] = $displayField;
   $getTitles[] = Inflector::humanize(str_replace('_id', '', $value));
 }
}
} else if (strpos($value, "_by")) {
  foreach ($this->$newModel->belongsTo as $belongToKey => $belongToVal) {
   if ($belongToVal['foreignKey'] == $value) {
    if ($this->$belongToVal['className']->isVirtualField($this->$belongToVal['className']->displayField)) {
     $displayField = $this->$belongToVal['className']->getVirtualField($this->$belongToVal['className']->displayField) . ' as ' . $belongToKey . '_' . $this->$belongToVal['className']->displayField;
   } else {
     $displayField = $belongToKey . '.' . $this->$belongToVal['className']->displayField;
   }
   $getFields[] = $displayField;
   $getTitles[] = Inflector::humanize(str_replace('_by', '', $value));
 }
}
} else {
  $getFields[] = $newModel . '.' . $value;
  $getTitles[] = Inflector::humanize($value);
}
endforeach;

$this->$openModel->recursive = 0;
$records = $this->$openModel->find('all', array('fields' => $getFields, 'conditions' => array('OR' => array($openModel . '.id' => $newRecords))));
foreach ($getTitles as $y) {
 $a['fields'][] = $y;
}
$x = 0;
$splModels = array("DocumentAmendmentRecordSheet", "CorrectivePreventiveAction", "CustomerComplaint");
foreach ($records as $record):
 foreach ($this->request->data['file_uploads']['fields'] as $key => $value):
  $openModel = $this->request->data['file_uploads']['model_name'];
if (strpos($value, "_id") || strpos($value, "_by") || $value == 'assigned_to' || $value == 'calibration_frequency' || $value == 'maintenance_frequency' || $value == 'master_list_of_format') {
 foreach ($this->$openModel->belongsTo as $belongToKey => $belongToVal):
  if ($belongToVal['foreignKey'] == $value) {
   if ($this->$belongToVal['className']->isVirtualField($this->$belongToVal['className']->displayField)) {
    $a['records'][$x][$value] = $record[0][$belongToKey . '_' . $this->$belongToVal['className']->displayField];
  } else {
    $a['records'][$x][$value] = $record[$belongToKey][$this->$belongToVal['className']->displayField];
  }
}
endforeach;
} else {
 if (array_key_exists($value, $this->$newModel->customArray)) {
  $a['records'][$x][$value] = $this->$newModel->customArray[$value][$record[$openModel][$value]];
} else {
  $a['records'][$x][$value] = $record[$openModel][$value];
}
}
endforeach;

if (in_array($openModel, $splModels)) {
  $a['records'][$x] = $this->showConditionalData($openModel, $a['records'][$x]);
  $a['fields'] = $this->showConditionalFields($openModel, $a['fields']);
}
$x++;
endforeach;
$this->set(array('records' => $a));

$this->set('companyDetails', $this->_get_company());
if ($this->request['controller'] != 'updates') {
 $this->_get_document_details();
}
$this->set(array('pdf_model' => Inflector::Humanize($openModel)));
}

public function showConditionalData($openModel, $record) {
  $mergeFields = $this->$openModel->mergeFields;

  foreach ($mergeFields as $mergeKey => $mergeValue):
   $unsetFields = $mergeValue;
 foreach ($mergeValue as $mKey => $mValue):
  if ($record[$mergeKey] == $mKey) {
   if (isset($mValue[0])) {
    $record[$mergeKey] = "<strong>{$mKey}:</strong> " . $record[$mValue[0]];
  } else {
    $record[$mergeKey] = "<strong>{$mKey}</strong>";
  }
  foreach ($unsetFields as $unsetField):
    unset($record[$unsetField[0]]);
  endforeach;
}
endforeach;
endforeach;
return $record;
}

public function showConditionalFields($openModel, $fields) {
  $mergeFields = $this->$openModel->mergeFields;

  foreach ($mergeFields as $unsetFields):
   foreach ($unsetFields as $unsetField) {
    if (($key = array_search($unsetField[1], $fields)) !== false) {
     unset($fields[$key]);
   }
 }
 endforeach;
 return $fields;
}


public function _check_auto_approvals($system_table_id = NULL){
  
    // Check if the approval process is available
    // Get the Approval Preocess details
    // Get the Approval Process Steps
    // Find out 1sr or current steps details
    // Find out envolved users and their list   
  $approvalClassName = $this->modelClass;
  $this->set('approvalClassName',$approvalClassName);

  $this->loadModel('SystemTable');
  $this->SystemTable->recursive = -1;
  $systemTable = $this->SystemTable->find('first', array('fields'=>array('SystemTable.id','SystemTable.system_name'),'conditions' => array('SystemTable.system_name' => $this->request->params['controller'])));

  $this->loadModel('AutoApproval');
  $if_approval_process_exists = $this->AutoApproval->find('count',array('conditions'=>array(
   'AutoApproval.system_table' => $systemTable['SystemTable']['id'],'AutoApproval.publish'=>1, 'AutoApproval.soft_delete'=>0)));
  
  if($if_approval_process_exists > 0 )
    {
      $this->set('show_auto_approval_panel',true);
    }else{ return false;
    }
  // Configure::write('debug',1);
  // debug($this->Session->read('User'));
  // exit;
  // debug($if_approval_process_exists);
  // exit;
        //Get AutoApproval details
  $auto_approval_details = $this->AutoApproval->find('first',array('conditions'=>array(
   'AutoApproval.system_table' => $systemTable['SystemTable']['id'],'AutoApproval.publish'=>1, 'AutoApproval.soft_delete'=>0),
  'recursive'=> -1
  ));

  $this->set('auto_approval_details',$auto_approval_details);        


        // Get current steps
  $this->loadModel('Approval'); 
  $current_approval = $this->Approval->find('first',array('conditions'=>array(
    'Approval.auto_approval_id'=>$auto_approval_details['AutoApproval']['id'],
    'Approval.record' => $this->request->params['pass'][0],

    ),
  'order'=>array('Approval.approval_step'=>'DESC')

  ));
  
  $this->set('current_approval',$current_approval);

        // Get 	Auto Approval Process Details
  $this->loadModel('AutoApprovalStep');
  if($this->action != 'approve'){

    if(!$current_approval){        

      $auto_approval_steps = $this->AutoApprovalStep->find('all',array('conditions'=>array(
        'AutoApprovalStep.auto_approval_id' => $auto_approval_details['AutoApproval']['id'],
        'AutoApprovalStep.branch_id' => $this->Session->read('User.branch_id'),    
        'AutoApprovalStep.department_id' => $this->Session->read('User.department_id'),
        ),
      'recursive'=>0,
      'order'=>array('AutoApprovalStep.step_number'=>'ASC') ));  
    }else{      

      $auto_approval_steps = $this->AutoApprovalStep->find('all',array('conditions'=>array(
        'AutoApprovalStep.auto_approval_id' => $auto_approval_details['AutoApproval']['id'],
        'AutoApprovalStep.branch_id' => $current_approval['AutoApprovalStep']['branch_id'],    
        'AutoApprovalStep.department_id' => $current_approval['AutoApprovalStep']['department_id'],
        ),'order'=>array('AutoApprovalStep.step_number'=>'ASC') ));
    }
    
  
  $this->Set('auto_approval_steps',$auto_approval_steps);
  }else{
    
    $auto_approval_steps = $this->AutoApprovalStep->find('all',array('conditions'=>array(
    'AutoApprovalStep.auto_approval_id' => $auto_approval_details['AutoApproval']['id'],
    'AutoApprovalStep.branch_id' => $this->Session->read('User.branch_id'),    
    'AutoApprovalStep.department_id' => $this->Session->read('User.department_id'),
    ),
    'recursive'=>0,
    'order'=>array('AutoApprovalStep.step_number'=>'ASC') ));
    
    $this->Set('auto_approval_steps',$auto_approval_steps);  
  }
  
  


//get users involved
  
  foreach ($auto_approval_steps as $steps) { 
   $users[$steps['AutoApprovalStep']['user_id']] = $this->Session->read('User.branch') . ' : ' . $steps['User']['name'];
   $users[$this->Session->read('User.id')] = $this->Session->read('User.branch') . ' : ' .$this->Session->read('User.name');
 }
  if($current_approval['From']['name'])$users[$current_approval['Approval']['from']] = $current_approval['From']['name'];
        //set users 
  // Configure::write('debug',1);
  // debug($users);
 $this->set('users_group',$users);
 if($users == NULL){
  // echo "ASd";
    // $auto_approval_details = NULL;
    $this->set('show_auto_approval_panel',false);
    return false;
 }
  // $this->set('auto_approval_details',$auto_approval_details);
  // return false;
// Configure::write('debug',1);
debug($auto_approval_steps);
 if($current_approval){

          //if exist find latest approvals
   $current_approval_step = $this->AutoApprovalStep->find('first',array('conditions'=>array(
      // 'AutoApprovalStep.branch_id' => $current_approval['AutoApprovalStep']['branch_id'],    
      // 'AutoApprovalStep.department_id' => $current_approval['AutoApprovalStep']['department_id'],
     'AutoApprovalStep.auto_approval_id' => $auto_approval_details['AutoApproval']['id'],
     'AutoApprovalStep.step_number' => $current_approval['Approval']['approval_step']
     ))); 

   $next_approval_step = $this->AutoApprovalStep->find('first',array('conditions'=>array(
    //  'AutoApprovalStep.branch_id' => $current_approval['AutoApprovalStep']['branch_id'],    
    // 'AutoApprovalStep.department_id' => $current_approval['AutoApprovalStep']['department_id'],
     'AutoApprovalStep.auto_approval_id' => $auto_approval_details['AutoApproval']['id'],
     'AutoApprovalStep.step_number' => $current_approval['Approval']['approval_step'] + 1
     )));

   $previous_approval_step = $this->AutoApprovalStep->find('first',array('conditions'=>array(
    //  'AutoApprovalStep.branch_id' => $current_approval['AutoApprovalStep']['branch_id'],    
    // 'AutoApprovalStep.department_id' => $current_approval['AutoApprovalStep']['department_id'],
     'AutoApprovalStep.auto_approval_id' => $auto_approval_details['AutoApproval']['id'],
     'AutoApprovalStep.step_number' => $current_approval['AutoApprovalStep']['approval_step'] - 1
     )));   

   // Configure::write('debug',1);
   debug($current_approval_step);
   $this->set('next_approval_step',$next_approval_step);          
   $this->set('current_approval_step',$current_approval_step);
   $this->set('previous_approval_step',$previous_approval_step);

 }else{
  // echo "asdsadd";
          // else 1st approval
   $current_approval_step = $this->AutoApprovalStep->find('first',array('conditions'=>array(
     'AutoApprovalStep.step_number' => 1,
     'AutoApprovalStep.auto_approval_id' => $auto_approval_details['AutoApproval']['id'],
     'AutoApprovalStep.branch_id' => $this->Session->read('User.branch_id'),
     'AutoApprovalStep.department_id' => $this->Session->read('User.department_id'),
     ))); 
   
   $this->set('current_approval_step',$current_approval_step);

   $next_approval_step = $this->AutoApprovalStep->find('first',array('conditions'=>array(
    'AutoApprovalStep.branch_id' => $this->Session->read('User.branch_id'),
    'AutoApprovalStep.department_id' => $this->Session->read('User.department_id'),
    'AutoApprovalStep.auto_approval_id' => $auto_approval_details['AutoApproval']['id'],
    'AutoApprovalStep.step_number' => $current_approval['Approval']['approval_step'] + 1
    )));
   $this->set('next_approval_step',$next_approval_step); 

 }

}
function filename_extension($filename) {
  $pos = strrpos($filename, '.');
  if($pos===false) {
    return false;
  } else {
    return substr($filename, $pos+1);
  }
}

public function getFileCount($id = null, $controller = null){

  $this->loadModel('SystemTable');
  $this->SystemTable->recursive = -1;
  $systemTableId = $this->SystemTable->find('first', array('conditions' => array('SystemTable.system_name' => $controller)));
  $system_table_id =  $systemTableId['SystemTable']['id'];
  $this->loadModel('FileUpload');
  return  $filecount =  $this->FileUpload->find('count', array('conditions' => array('FileUpload.record' =>$id, 'FileUpload.system_table_id'=>$system_table_id,  'FileUpload.archived' =>0, 'FileUpload.publish'=>1, 'FileUpload.soft_delete'=>0)));
}

public function _get_objectives(){        
        $this->loadModel('ProcessTeam');
        if(!$this->$systemTableId['SystemTable']['id']){
            if($this->request->controller != 'material_quality_checks'){
                $system_table_id = $this->_get_system_table_id();
            }            
        }else{
            $system_table_id = $this->$systemTableId['SystemTable']['id'];
        }
        $objective_teams = $this->ProcessTeam->find('all',array(
                'conditions'=>array('ProcessTeam.system_table'=>$system_table_id, 'or'=>array('ProcessTeam.team LIKE' => '%' . $this->Session->read('User.id') . '%', 'Process.owner_id'=>$this->Session->read('User.id'))),
                'fields'=>array('Process.id','Process.title','Objective.id','Objective.title','Objective.clauses', 'Objective.objective_description', 'Objective.desired_output', 'ProcessTeam.start_date','ProcessTeam.end_date'),
                'order' => array('ProcessTeam.end_date' => 'ASC'),
                'limit'=>3
            ));
        $this->set('get_objectives',$objective_teams);   
}

public function _send_tni_email($data = null){
  $this->loadModel('TrainingNeedIdentification');
  $employee = $this->TrainingNeedIdentification->Employee->find('first',array('recursive'=>-1, 'conditions'=>array('Employee.id'=>$data['TrainingNeedIdentification']['employee_id'])));
  $course = $this->TrainingNeedIdentification->Course->find('first',array('recursive'=>-1,'conditions'=>array('Course.id'=>$data['TrainingNeedIdentification']['course_id'])));

  $officeEmailId = $employee['Employee']['office_email'];
  $personalEmailId = $employee['Employee']['personal_email'];
  if ($officeEmailId != '') {
    $email = $officeEmailId;
  } else if ($personalEmailId != '') {
    $email = $personalEmailId;
  }
  $this->loadModel('User');
  $users = $this->User->find('all',array('conditions'=>array('User.employee_id'=>$employee['Employee']['id'])));
  $new_session = $_SESSION;
  try{
    App::uses('CakeEmail', 'Network/Email');
    $new_session = $this->Session->read('User');
    if($new_session['is_smtp'] == 1)
      $EmailConfig = new CakeEmail("smtp");

    if($new_session['is_smtp'] == 0)
      $EmailConfig = new CakeEmail("default");

    if(Configure::read('evnt') == 'Dev')$env = 'DEV';
    elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
    else $env = "";

    $EmailConfig->to($email);
    $EmailConfig->subject('Training Need Identification Generated');
    $EmailConfig->template('tni');
    $EmailConfig->viewVars(array(
      'date_time' => date('Y-m-d'),
      'by_user'=>$this->Session->read('User.name'),
      'course'=>$course['Course']['title'],
      'details' => $course['Course']['description'],    
      'env' => $env, 'app_url' => FULL_BASE_URL                        
      ));
    $EmailConfig->emailFormat('html');
    $EmailConfig->send();
  } catch(Exception $e) {
    exit;
  }
}

public function generate_cp_number($model = null,  $pre_fix = null, $field_name = null){
  
  $pattern = $pre_fix .'-'.date('y').'-'.date('m').'-'.date('d').'-';
  $this->loadModel($model);
  $get_last = $this->$model->find('first',array(
    'recursive'=>-1,
    'conditions'=>array($model.'.'.$field_name .' like' => '%'.$pattern .'%'),
    'fields'=>array($model.'.id',$model.'.'.$field_name),
    'order'=>array($model.'.'.$field_name => 'DESC')));

  if(!$get_last){
    $last = $pre_fix .'-'.date('y').'-'.date('m').'-'.date('d').'-001';
  }else{            
    $last =  substr($get_last[$model][$field_name], -3);
    $last = $last + 1;
    switch (strlen($last)) {
      case 1:
      $last = $pre_fix .'-'.date('y').'-'.date('m').'-'.date('d').'-00'.$last;
      break;

      case 2:
      $last = $pre_fix .'-'.date('y').'-'.date('m').'-'.date('d').'-0'.$last;
      break;
      case 3:
      $last = $pre_fix .'-'.date('y').'-'.date('m').'-'.date('d').$last;
      break;  
    }
  }
  return $last;

}

public function special_report($base_model = null, $curr_date = null){
  $from = date('Y-m-1',strtotime($curr_date));
  $to = date('Y-m-t',strtotime($curr_date));
  $date_condition = array($base_model.'.created between ? and ?'=> array($from,$to));
  $this->loadModel($base_model);
  $reports = $this->$base_model->report;
  foreach ($reports as $report_name => $report_values) {
    foreach($report_values as $report){
      $model = $report['model'];
      $this->loadModel($model);
      $records = $this->$model->find('list');
      foreach($records as $rec_key => $rec_value){
        $val = $this->$base_model->find('count',array('conditions'=>array($date_condition, $base_model.'.'.$report['key_field'] => $rec_key)));
        if($val){
          $results[$report_name][$model][$rec_value] = $val;
          $results[$report_name]['count'] = $results[$report_name]['count'] + $val;
          $results['count'] = $results['count'] + $val;
        }

      }
    }
  }
  return($results);        
}

public function quick_search(){
  $model = $this->modelClass;

  $fields = array_keys($this->$model->schema());
  $belongsToCondition = array();
  $x = 0;
  foreach(array_keys($this->$model->belongsTo) as $belongsToModel){
    
    if($belongsToModel != 'ModifiedBies' && $belongsToModel != 'CreatedBies' && $belongsToModel != 'ApprovedBy' && $belongsToModel != 'PreparedBy'  && $belongsToModel != 'BranchIds'&& $belongsToModel != 'DepartmentIds' && $belongsToModel != 'StatusUserId' && $belongsToModel != 'CreatedBy' && $belongsToModel != 'ModifiedBy'  && $belongsToModel != 'Company' && $belongsToModel != 'SystemTable' && $belongsToModel != 'CalibrationFrequency' && $belongsToModel != 'MaintenanceFrequency'){
      if($belongsToModel == 'Owner')$belongsToModel = 'User';
      if($belongsToModel == 'InputProcess')$belongsToModel = 'Process';
      if($belongsToModel == 'OutputProcess')$belongsToModel = 'Process';
      if($belongsToModel == 'ParentId')$belongsToModel = 'MasterListOfFormat';
      if($belongsToModel == 'ParentDocumentId')$belongsToModel = 'MasterListOfFormat';
      if($belongsToModel == 'To')$belongsToModel = 'Employee';
      if($belongsToModel == 'By')$belongsToModel = 'Employee';
      $this->loadModel($belongsToModel);
      $belongsToCondition['or'][$x] = array($belongsToModel.'.'.$this->$belongsToModel->displayField .' LIKE' => '%'.$this->request->params['named']['search'].'%');
    }


  }

  $search_keys = array('id', 'name','title');
  if($this->modelClass == 'MasterListOfFormat'){
    $search_keys = array('id', 'name','title','document_number');
    $documentStatuses = $this->MasterListOfFormat->customArray['document_status'];
    $this->set('documentStatuses',$documentStatuses);
  }
  $src = $this->$model->displayField;
  $conditions = array($model.'.'.$src .' LIKE ' => '%'.$this->request->params['named']['search'].'%');
  foreach ($search_keys as $keys) {
    if(in_array($keys, $fields)){
      $field_condition = array($model.'.'.$keys.' LIKE' => '%'.$this->request->params['named']['search'].'%');
      $conditions = array('OR'=>array($conditions,$belongsToCondition, $field_condition));
    }
  }        
  if($this->modelClass != 'MasterListOfFormat'){
    $this->paginate = array('limit'=>25, 'order' => array($model.'.id' => 'DESC'), 'conditions' => array($conditions));
    $this->$model->recursive = 0;
    $this->set(Inflector::variable(Inflector::tableize($model)), $this->paginate());
  }else{
    $this->paginate = array('limit'=>25, 'order' => array($model.'.id' => 'DESC'), 'conditions' => array($conditions));
    $master_list_of_formats = $this->paginate();
        foreach ($master_list_of_formats as $key => $master_list_of_format) {
            $masterListOfFormatBranches = array();
            $masterListOfFormatBranches = $this->MasterListOfFormat->MasterListOfFormatBranch->find('all', array('conditions' => array('MasterListOfFormatBranch.master_list_of_format_id' => $master_list_of_format['MasterListOfFormat']['id'], 'MasterListOfFormatBranch.soft_delete' => 0, 'MasterListOfFormatBranch.publish' => 1), 'fields' => 'Branch.name', 'order' => array('Branch.name' => 'DESC')));

            $branches = array();
            foreach ($masterListOfFormatBranches as $masterListOfFormatBranch)
                if ($masterListOfFormatBranch['Branch']['name'])
                    $branches[] = $masterListOfFormatBranch['Branch']['name'];
            $master_list_of_formats[$key]['MasterListOfFormat']['Branches'] = implode(', ', $branches);

            $depts = array();
            $masterListOfFormatDepartments = $this->MasterListOfFormat->MasterListOfFormatDepartment->find('all', array('conditions' => array('MasterListOfFormatDepartment.master_list_of_format_id' => $master_list_of_format['MasterListOfFormat']['id'], 'MasterListOfFormatDepartment.soft_delete' => 0, 'MasterListOfFormatDepartment.publish' => 1), 'fields' => 'Department.name',
                'order' => array('Department.name' => 'DESC')));
            foreach ($masterListOfFormatDepartments as $masterListOfFormatDepartment)
                if ($masterListOfFormatDepartment['Department']['name'])
                    $depts[] = $masterListOfFormatDepartment['Department']['name'];
            $master_list_of_formats[$key]['MasterListOfFormat']['Departments'] = implode(', ', $depts);
        }
        $this->set('masterListOfFormats', $master_list_of_formats);
  }
  
  debug($conditions);
  // exit;
  $this->render('index');
}

  public function ckeditor(){
    if(isset($_FILES['upload'])){
      $controller = $this->request->params['pass'][0];
      $user = $this->request->params['pass'][1];
      $dest = WWW_ROOT. "img/ckeditor". DS . $controller . DS . $user;
      // chmod($dest, 0777);
      mkdir($dest, 0777,true);
      // Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS .
      // print_r($_FILES);
      // ------ Process your file upload code -------
            $filen = $_FILES['upload']['tmp_name']; 
            $con_images = $dest. DS . $_FILES['upload']['name'];
            // echo $con_images;
            move_uploaded_file($filen, $con_images );
           // $url = $con_images;
            $url = Router::url("/", true) . '/img/ckeditor/' . $controller . '/'  . $user . '/'.$_FILES['upload']['name'];

       $funcNum = $_GET['CKEditorFuncNum'] ;
       // Optional: instance name (might be used to load a specific configuration file or anything else).
       $CKEditor = $_GET['CKEditor'] ;
       // Optional: might be used to provide localized messages.
       $langCode = $_GET['langCode'] ;
        
       // Usually you will only assign something here if the file could not be uploaded.
       $message = '';
       echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
       exit;
}
  }

  public function _update_stocks($material_id = null){
    if($material_id){
      $this->loadModel('Material');
      $this->Material->create();
      $stock = $this->requestAction(array('controller'=>'stocks','action'=>'get_stock_details',$material_id));
      $this->Material->read(null,$material_id);
      $this->Material->set('stock_in_hand',$stock['stock']);
      $this->Material->save();
    }
      
  }

  public function root_cause_send_reminder($id = null,$exit = null){
        if($id)$id = $id;
    else $id = $this->request->params['pass'][0];
        $cc = $this->CapaRootCauseAnalysi->find('first',array('recursive'=>-1, 'conditions'=>array('CapaRootCauseAnalysi.id'=>$id)));
        $employee = $this->CapaRootCauseAnalysi->Employee->find('first',array(
          'recursive'=>-1,
          'fields'=>array('Employee.id','Employee.name','Employee.personal_email','Employee.office_email'),
          'conditions'=>array('Employee.id'=>$cc['CapaRootCauseAnalysi']['action_assigned_to'])));
        $officeEmailId = $employee['Employee']['office_email'];
        $personalEmailId = $employee['Employee']['personal_email'];
        if ($officeEmailId != '') {
          $email = $officeEmailId;
        } else if ($personalEmailId != '') {
          $email = $personalEmailId;
        }
        if($cc && $email){
          $send_message = "Pending CAPA Capa Root Cause Analysis For Action";
          $body = "<p>You have pending capa root cause analysis to address. Please login to FlinkISO and add details</p>";
          try{
            if(Configure::read('evnt') == 'Dev')$env = 'DEV';
            elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
            else $env = "";

            App::uses('CakeEmail', 'Network/Email');                        

            if($this->Session->read('User.is_smtp') == 1)
              $EmailConfig = new CakeEmail("smtp");

            if($this->Session->read('User.is_smtp') == 0)
              $EmailConfig = new CakeEmail("default");
            $EmailConfig->to($email);
            $EmailConfig->subject($send_message);
            $EmailConfig->template('emailTrigger');
            $EmailConfig->viewVars(array(
              'date_time' => date('Y-m-d h:i:s'),
              'by_user'=>$this->Session->read('User.username'),
              'employee'=>$this->Session->read('User.name'),
              'branch' => $this->Session->read('User.branch'),
              'department' => $this->Session->read('User.department'),
              'h2tag'=>$send_message,
              'msg_content'=>$body,
              'env' => $env, 'app_url' => FULL_BASE_URL));
            $EmailConfig->emailFormat('html');
            $EmailConfig->send();
          } catch(Exception $e) {
            if($exit != 'no')echo "<span class='btn btn-xs btn-danger'>Failed!</span>";        
          }
          if($exit != 'no')echo "<span class='btn btn-xs btn-success'>Sent</span>";
        }else{
          if($exit != 'no')echo "<span class='btn btn-xs btn-danger'>Failed!</span>";
        }         
        if($exit != 'no') exit;
       }

  public function capa_investigation_send_reminder($id = null,$exit = null){
        if($id)$id = $id;
    else $id = $this->request->params['pass'][0];
        $cc = $this->CapaInvestigation->find('first',array('recursive'=>-1, 'conditions'=>array('CapaInvestigation.id'=>$id)));
        $employee = $this->CapaInvestigation->Employee->find('first',array(
          'recursive'=>-1,
          'fields'=>array('Employee.id','Employee.name','Employee.personal_email','Employee.office_email'),
          'conditions'=>array('Employee.id'=>$cc['CapaInvestigation']['employee_id'])));
        $officeEmailId = $employee['Employee']['office_email'];
        $personalEmailId = $employee['Employee']['personal_email'];
        if ($officeEmailId != '') {
          $email = $officeEmailId;
        } else if ($personalEmailId != '') {
          $email = $personalEmailId;
        }
        if($cc && $email){
          $send_message = "Pending CAPA Investigation For Action";
          $body = "<p>You have pending capa investigation to address. Please login to FlinkISO and add details</p>";
          try{

            if(Configure::read('evnt') == 'Dev')$env = 'DEV';
            elseif(Configure::read('evnt') == 'Qa')$env = 'QA';
            else $env = "";

            App::uses('CakeEmail', 'Network/Email');                        

            if($this->Session->read('User.is_smtp') == 1)
              $EmailConfig = new CakeEmail("smtp");

            if($this->Session->read('User.is_smtp') == 0)
              $EmailConfig = new CakeEmail("default");
            $EmailConfig->to($email);
            $EmailConfig->subject($send_message);
            $EmailConfig->template('emailTrigger');
            $EmailConfig->viewVars(array(
              'date_time' => date('Y-m-d h:i:s'),
              'by_user'=>$this->Session->read('User.username'),
              'employee'=>$this->Session->read('User.name'),
              'branch' => $this->Session->read('User.branch'),
              'department' => $this->Session->read('User.department'),
              'h2tag'=>$send_message,
              'msg_content'=>$body,
              'env' => $env, 'app_url' => FULL_BASE_URL));
            $EmailConfig->emailFormat('html');
            $EmailConfig->send();
          } catch(Exception $e) {
            if($exit != 'no')echo "<span class='btn btn-xs btn-danger'>Failed!</span>";        
          }
          if($exit != 'no')echo "<span class='btn btn-xs btn-success'>Sent</span>";
        }else{
          if($exit != 'no')echo "<span class='btn btn-xs btn-danger'>Failed!</span>";
        }

       if($exit != 'no') exit;
       }

  public function _weekly_plan_details($production_weekly_plan_id = null){
        
        $this->loadModel('Production');
        $total_number_of_rejections = 0;
        $actual_production_number = 0;
        
        $plan = $this->Production->ProductionWeeklyPlan->find('list',array(
            'fields'=>array('ProductionWeeklyPlan.id','ProductionWeeklyPlan.production_planned'),
            'conditions'=>array('ProductionWeeklyPlan.id'=>$production_weekly_plan_id)));

        $productions = $this->Production->find('all',array(
            'recursive'=>-1,
            // 'fields'=>array('Production.id', 'Production.actual_production_number','Production.rejections','Production.balance','Production.production_planned','Production.total_balance'),
            'conditions'=>array('publish'=>1, 'Production.production_weekly_plan_id'=>$production_weekly_plan_id)));

        foreach ($productions as $production) {
            $actual_production_number = $actual_production_number + $production['Production']['actual_production_number'];
        }
        
        
        $productionRejections = $this->Production->ProductionRejection->find('list',array('conditions'=>array('ProductionRejection.production_weekly_plan_id'=>$production_weekly_plan_id)));
        
        foreach ($productionRejections as $key => $value) {
            $rejectionDetails = $this->Production->ProductionRejection->RejectionDetail->find('all',array(
                'recursive'=>-1,
                'conditions'=>array('RejectionDetail.publish'=>1, 'RejectionDetail.production_rejection_id'=>$key)));
            foreach ($rejectionDetails as $rejectionDetail) {
                $total_number_of_rejections = $total_number_of_rejections + $rejectionDetail['RejectionDetail']['number_of_rejections'];
            }
        }

        $plan = array_values($plan);
        $plan = $plan[0];
        $balance = $plan - $actual_production_number + $total_number_of_rejections;
       
        $result = array('actual_production_number'=>$actual_production_number,'total_number_of_rejections'=>$total_number_of_rejections,'balance'=>$balance);
        $this->_reset_productions($production_weekly_plan_id);
        return $result;
        exit;
    }

    public function reset_productions($production_weekly_plan_id = null){ 
      $this->_reset_productions($production_weekly_plan_id);
    }
    
    public function _reset_productions($production_weekly_plan_id = null){
      $this->loadModel('ProductionWeeklyPlan');
      $this->loadModel('Production');
      $this->loadModel('ProductionRejection');
      $this->loadModel('RejectionDetail');

      $plan = $this->ProductionWeeklyPlan->find('first',array('recursive'=>-1, 'conditions'=>array('ProductionWeeklyPlan.id'=>$production_weekly_plan_id)));
      if($plan)
      {

        // fetch production batched
        $productions = $this->Production->find('all',array('recursive'=>-1,'order'=>array('Production.created'=>'ASC','Production.batch_number'=>'ASC'), 'conditions'=>array('Production.production_weekly_plan_id'=>$production_weekly_plan_id)));
        if($productions)
        {
          $total_production = 0;
          $total_rejections = 0;
          foreach ($productions as $production) 
          {
            
            //fetch rejections
            $production_rejections = $this->ProductionRejection->find('all',array('recursive'=>-1,'conditions'=>array('ProductionRejection.production_id'=>$production['Production']['id'])));
            
            
            if($production_rejections)
            {
              foreach ($production_rejections as $production_rejection) 
              {
                $batch_rejections = 0;
                // fetch rejection details
                  $rejection_details = $this->RejectionDetail->find('all',array('recursive'=>-1, 'conditions'=>array('RejectionDetail.production_rejection_id'=>$production_rejection['ProductionRejection']['id'])));
                  $rejections_for_current_rejections = 0;      
                  // fetch rejection details
                  if($rejection_details){
                    foreach ($rejection_details as $rejection_detail) 
                    {
                       // echo "0 rejections found <br />";
                      // update rejection to production
                      $rejections_for_current_rejections = $rejections_for_current_rejections + $rejection_detail['RejectionDetail']['number_of_rejections'];
                      $total_rejections = $total_rejections + $rejection_detail['RejectionDetail']['number_of_rejections'];
                      $batch_rejections = $batch_rejections + $rejection_detail['RejectionDetail']['number_of_rejections'];
                    }
                    // update production_rejections here
                    // echo  ">>>" . $production_rejection['ProductionRejection']['id'] .">>> " . $rejections_for_current_rejections ."<br />";
                    $newProductionRejection = $production_rejection['ProductionRejection'];
                    $newProductionRejection['number_of_rejections'] = $rejections_for_current_rejections;
                    $this->ProductionRejection->create();
                    $this->ProductionRejection->save($newProductionRejection,false);

                  }else{
                    $newProductionRejection = $production_rejection['ProductionRejection'];
                    $newProductionRejection['number_of_rejections'] = 0;
                    $this->ProductionRejection->create();
                    $this->ProductionRejection->save($newProductionRejection,false);
                  }
              }
              // echo ">>> : : : " . $production['Production']['batch_number'] ."<br />";
              $total_production = $total_production + $production['Production']['actual_production_number'];
              $newProduction = $production['Production'];
              $newProduction['rejections'] = $batch_rejections;
              $newProduction['production_planned'] = $plan['ProductionWeeklyPlan']['production_planned'];
              $newProduction['balance'] = $plan['ProductionWeeklyPlan']['production_planned'] -  $total_production + $total_rejections ;
              $this->Production->create();
              $this->Production->save($newProduction,false);

            }else{
              
              // echo "2 Rejection not found <br />";
              $total_production = $total_production + $production['Production']['actual_production_number'];
              $newProduction = $production['Production'];
              $newProduction['rejections'] = 0;
              $newProduction['production_planned'] = $plan['ProductionWeeklyPlan']['production_planned'];
              $newProduction['balance'] = $plan['ProductionWeeklyPlan']['production_planned'] -  $total_production + $total_rejections ;
              $this->Production->create();
              $this->Production->save($newProduction,false);
            }
        
          }
          
        }else{
          
        }


      }else{
        
      }
      
    }

    public function advance_search(){

      if ($this->request->is('post')) {
        
        $modal = $this->modelClass;
        $this->loadModel($modal);
        
        foreach ($this->request->data['order'][$modal] as $field_name => $value) {
          if($value['value'] != -1){
            if($value['value'] == 0)$ord = 'ASC';
            if($value['value'] == 1)$ord = 'DESC';
            $oderarray[$modal.'.'.$field_name] = $ord;
          }
        }
      
        foreach ($this->request->data['basic'][$modal] as $field_name => $details) {
          if($details['value'] != ''){
            switch ($details['oprator']) {
              case '==':
                $condition[] = array($modal.'.'.$field_name => $details['value']);
                break;

              case '!=':
                $condition[] = array($modal.'.'.$field_name . ' !=' => $details['value']);
                break;

              case '>':
                $condition[] = array($modal.'.'.$field_name . ' >' => $details['value']);
                break;

              case '<':
                $condition[] = array($modal.'.'.$field_name . ' <' => $details['value']);
                break;

              case '%*':
                $condition[] = array($modal.'.'.$field_name . ' LIKE ' => '%' .$details['value']);
                break;

              case '*%':
                $condition[] = array($modal.'.'.$field_name . ' LIKE ' => $details['value']. '%');
                break;

              case 'between':
                $dates = split('-', $details['value']);
                $startdate = date('Y-m-d',strtotime($dates[0]));
                $enddate = date('Y-m-d',strtotime($dates[1]));
                $condition[] = array('DATE(' . $modal.'.'.$field_name . ') BETWEEN ? and ? ' => array($startdate,$enddate));
                break;

              case '%*%':
                $condition[] = array($modal.'.'.$field_name . ' LIKE ' => '%' . $details['value']. '%');
                break;  
              
              default:
                # code...
                break;
            }
          }
        }
        foreach ($this->request->data['advance'][$modal] as $field_name => $details) {
          if($details['value'] != ''){
            switch ($details['oprator']) {
              case '==':
                $condition[] = array($modal.'.'.$field_name => $details['value']);
                break;

              case '!=':
                $condition[] = array($modal.'.'.$field_name . ' !=' => $details['value']);
                break;

              case '>':
                $condition[] = array($modal.'.'.$field_name . ' >' => $details['value']);
                break;

              case '<':
                $condition[] = array($modal.'.'.$field_name . ' <' => $details['value']);
                break;

              case '%*':
                $condition[] = array($modal.'.'.$field_name . ' LIKE ' => '%' .$details['value']);
                break;

              case '*%':
                $condition[] = array($modal.'.'.$field_name . ' LIKE ' => $details['value']. '%');
                break;

              case 'between':

                break;

              case '%*%':
                $condition[] = array($modal.'.'.$field_name . ' LIKE ' => '%' . $details['value']. '%');
                break;  
              
              default:
                # code...
                break;
            }
          }
        }
        
        if($condition == null){
          $this->Session->setFlash(__('No records to display! Plese select search criteria.'), 'default', array('class' => 'alert alert-danger'));
          $variable =  Inflector::variable(Inflector::pluralize($this->modelClass));
          $this->set($variable, false);
        }else{
          $conditions = $this->_check_request();
          $this->paginate = array('order'=>$oderarray,'conditions' => array($condition,$conditions),'maxLimit'=>500,'limit'=>500);
          $variable =  Inflector::variable(Inflector::pluralize($this->modelClass));
          $this->set($variable, $this->paginate());
            
        }
        $this->request->data = $this->request->data;
        $this->render('index');
        // $result = $this->$modal->find('all',array('conditions'=>$condition));
        

        
      }else{
        $modal = $this->modelClass;
        $this->loadModel($modal);
        $fields = $this->$modal->schema();
        $belongs = $this->$modal->belongsTo;
        $fields_to_unset = array('id','sr_no','system_table_id','company_id','modified','modified_by','branchid','departmentid',
            'soft_delete','record_status','status_user_id','division_id','division_id','user_session_id','divisionid',
            'document_status','parent_id','work_instructions','record','file_key','version_key','file_dir','result','file_status','file_content');
        
        unset($fields['id']);
        unset($fields['sr_no']);
        unset($fields['system_table_id']);
        unset($fields['company_id']);
        unset($fields['modified']);
        unset($fields['modified_by']);
        unset($fields['branchid']);
        unset($fields['departmentid']);
        unset($fields['soft_delete']);
        unset($fields['record_status']);
        unset($fields['status_user_id']);
        unset($fields['division_id']);
        unset($fields['master_list_of_format_id']);
        unset($fields['system_table_id']);
        unset($fields['current_status']);
        unset($fields['list_of_kpi_ids']);
        unset($fields['system_table']);
        unset($fields['risk_assesment_id']);
        unset($fields['state_id']);
        unset($fields['lead_type']);
        unset($fields['task_type']);
        unset($fields['task_status']);
        unset($fields['rag_status']);
        unset($fields['priority']);
        unset($fields['login_status']);
        unset($fields['password']);
        unset($fields['user_access']);
        unset($fields['assigned_branches']);
        unset($fields['copy_acl_from']);
        unset($fields['password_token']);
        unset($fields['status']);
        unset($fields['document_status']);
        unset($fields['parent_id']);
        unset($fields['work_instructions']);

        unset($fields['record']);
        unset($fields['file_key']);
        unset($fields['version_key']);
        unset($fields['file_dir']);
        unset($fields['result']);
        unset($fields['file_status']);
        unset($fields['file_content']);

        foreach ($belongs as $key => $value) {
            if(in_array($value['foreignKey'], array_keys($fields))){
              unset($fields[$value['foreignKey']]);
            }
        }

        foreach ($fields as $field_name => $field_type) {
          switch ($field_type['type']) {
            case 'string':
                $src[$field_name] = array('=='=>'Equal To', '!='=>'Not Equal To','%*'=>'Starts With','*%'=>'Ends With','%*%'=>'Contains Word');
              break;
            case 'date':
                $src[$field_name] = array('=='=>'Equal To','>'=>'Greater Than','<'=>'Less Than','between'=>'Between');
              break;
            case 'integer':
              if($field_type['length']!=1){
                $src[$field_name] = array('=='=>'Equal To','!='=>'Not Equal To','>'=>'Greater Than','<'=>'Less Than');
                // $src[$field_name] = array('==','!=','>','<');
              }else{
                $src[$field_name] = array('=='=>'Equal To','!='=>'Not Equal To','>'=>'Greater Than','<'=>'Less Than');
                // $src[$field_name] = array('==','!=','>','<');
              }
                
              break;
            case 'text':
                $src[$field_name] = array('%*%'=>'Contains Word');
              break;
            
            default:
              # code...
              break;
          }
        }
        foreach ($belongs as $bkey => $bvalue) {
          if(in_array($bvalue['foreignKey'], array_values($fields_to_unset))){
            unset($belongs[$bkey]);
          }
        }
        
        foreach ($belongs as $key => $value) {
          // check belongs model
          // load model and fect list
          // add to result
          $m = $value['className'];
          $this->loadModel($m);
          $getrecs = $this->$m->find('list',array('conditions'=>array()));
          $belongsToModels[$key] = array('field_name'=>$value['foreignKey'],'records'=>$getrecs);
        }

        $customArray = $this->$modal->customArray;
        foreach ($customArray as $key => $value) {
          $belongsToModels[Inflector::Classify($key)] = array('field_name'=>$key,'records'=>$value);
        }
        
        $this->set('src',$src);
        $this->set('belongsToModels',$belongsToModels);
        $this->set('modal',$modal);
        $this->render('/Elements/advance-search');

      }
      
  }

  public function get_list($name = null){
    $model = $this->request->params['named']['model'];
    $this->autoRender = false;
    $results = null;
    switch ($model) {
      case 'ErpProject':
        $this->loadModel($model);    
        $result = $this->$model->find('list',array(
          'fields'=>array('ErpProject.SysRowID','ErpProject.OrderNum'),
          'limit'=>5,
          // 'conditions'=>array('ErpProject.OrderNum LIKE ' => '%'. $this->request->query['term'])
        ));
        foreach ($result as $key => $value) {
          $results[] = array('id'=>$key,'value'=>$value);
        }        
        if($results){
          return json_encode($results);  
        }else{
          $results = array(0=>'No results');
          return json_encode($results);
        }
        
        break;
      
      default:
        $this->loadModel($model);    
        $result = $this->$model->find('list',array('conditions'=>array($model.'.'.$this->$model->displayField  .'  LIKE ' => '%'. $this->request->query['term']. '%')));
        // Configure::write('debug',1);
        // debug($result);
        foreach ($result as $key => $value) {
          $results[] = array('id'=>$key,'value'=>$value);
        }
        if($results){
          return json_encode($results);  
        }else{
          $results = array(0=>'No results');
          return json_encode($results);
        }
        break;
    }
    
    // exit;
    


  }

  public function _updateprojectestimate($id = null){
    $this->loadModel('Project');
    // $project = $this->projectdates($project_id);
    // Configure::write('debug',1);
      
    $project = $this->requestAction(array('controller'=>'projects','action'=>'projectdates',$id));
    if($project){
      $newestimate = $project['Project']['resource_cost'] + $project['Project']['other_estimate'];
      debug($newestimate);
      $project['Project']['estimated_project_cost'] = $newestimate;
      
      
      debug($project);
      // exit;
      $this->Project->read(null,$id);
      $data['Project']['estimated_project_cost'] = $newestimate;
      $this->Project->save($data,false);  
    }
    // exit;
    return true;
  }

  public function get_holidays($date = null,$days = null,$project_id = null){

    $this->autoRender = false;

    $project_id = $this->request->params['pass'][1];
    if(!$project_id)$project_id = $this->request->params['named']['project_id'];
    $start_date = date('Y-m-d',strtotime(base64_decode($this->request->params['named']['start_date'])));
    if($start_date == '1970-01-01')return false;
    
    $this->loadModel('Holiday');
    // get weekends
    
    $this->loadModel('Project');
    $project = $this->Project->find('first',array('conditions'=>array('Project.id'=>$project_id),'fields'=>array('Project.id','Project.weekends')));
    $weekends = json_decode($project['Project']['weekends']);
    
    $x = 1;
    
    while ($x <= $this->request->params['named']['days'] - 1) {
      $start_date = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));  
      $yes = false;
      $holiday = $this->Holiday->find('first',array('conditions'=>array('DATE(Holiday.date)'=>date('Y-m-d',strtotime($start_date)))));
      
      if($holiday){        
        $yes = true;
      }else{
        $yes = false;
        foreach ($weekends as $wkey => $daynumber) {
          if($daynumber == date('N',strtotime($start_date))){
            $yes = true;            
          }
        }
      }

      if($yes == true){        
        // $x = $x;  
      }else{
        $x++;       
        $end_date = date('yyyy-MM-dd',strtotime($start_date));
      }
    }    
    // echo $x.'<br />';
    // $end_date = date('yyyy-MM-dd',strtotime($start_date));
    return $end_date;
  }

  public function _track_file($project_file_id = null, $project_id = null, $milestone_id = null, $from = null, $to = null, $by = null, $current_status = null, $change_type = null, $function = null, $comments = null,$project_process_plan_id = null){

      // Configure::Write('debug',1);
      // debug($this->request->params);
      // exit;
      // if($project_file_id){
        $this->loadModel('FileTracking');
        $data['FileTracking']['project_file_id'] = $project_file_id;
        $data['FileTracking']['project_id'] = $project_id;
        $data['FileTracking']['milestone_id'] = $milestone_id;
        $data['FileTracking']['from'] = $from;
        $data['FileTracking']['to'] = $to;
        $data['FileTracking']['by'] = $by;
        $data['FileTracking']['current_status'] = $current_status;
        $data['FileTracking']['change_type'] = $change_type;
        $data['FileTracking']['function'] = $function;
        $data['FileTracking']['comment'] = $comments;
        $data['FileTracking']['changed_on'] = date('Y-m-d H:i:s');
        $data['FileTracking']['publish'] = 1;
        $data['FileTracking']['soft_delete'] = 0;
        $data['FileTracking']['project_process_plan_id'] = $project_process_plan_id;

        debug($data);
        // exit;

        $this->FileTracking->create();
        $this->FileTracking->save($data, false);  
      // }
      

      return true;
  }

  public function _sectohr($sec = null){
    // first conver to mins
    if($sec)$mins = $sec / 60;
    else $mins = 0;
    
    if($mins < 60){
      $final = '00:'.$mins;
    }else{
      $hours = round(($mins / 60),2);

      // debug($hours);
      
      $hours = explode('.',$hours);
      
      if($hours[1]>60){
        $hours[0] = $hours[0] + 1;
        $hours[1] = $hours[1] - 60;
      }
      
      if(!$hours[0])$hours[0] = '00';
      if(!$hours[1])$hours[1] = '00';
      

      $final = $hours[0].':'.$hours[1];
    }
    
    return $final;
  }

  public function _count($c = null){
    if($c){      
      return count($c);    
    }else{      
      return null;
    }
  }

  public function count($c = null){
    if($c){
      return count($c);    
    }else{
      return null;
    }
  }

}

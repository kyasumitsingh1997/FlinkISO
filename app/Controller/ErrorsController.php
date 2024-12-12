<?php

class ErrorsController extends Controller {

    public $name = 'Errors';

    public function error500() {
        // parent::beforeFilter();
        // $this->layout = 'default';
    }

    public function beforeFilter() {
        // parent::beforeFilter();
        // $this->layout = 'default';
    }

    public function error404() {
        // $this->layout = 'default';
        
    }

    public function missing_database() {
        // $this->layout = 'default';
        
    }

    public function missing_datasource() {
        // $this->layout = 'default';
        
    }

    public function missing_connection(){
        echo "ASdasd";
        $this->layout = 'error';
    }

}

?>

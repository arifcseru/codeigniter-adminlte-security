<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
class Entity extends BaseController {

	public function __construct(){
		parent::__construct();
		$this->load->library('javascript');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('session');
		$this->load->model('user_model');
		$this->load->model('EntityModel');
		$this->isLoggedIn(); 
	}
	public function index(){
		 $this->global['pageTitle'] = 'Demo : Entity';
        
        
		if($this->isAdmin() == TRUE){
            $this->loadThis();
        }
        else{        
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->userListingCount($searchText);

			$returns = $this->paginationCompress ( "userListing/", $count, 10 );
            
            $data['entityList'] = $this->EntityModel->getAll();
            
            $this->global['pageTitle'] = 'CodeInsect : User Listing';
            //$this->loadViews("entity/entityList", $this->global, $data, NULL);
			$this->loadMaterialViews("entity/entityList", $this->global, $data , NULL);
        }
		
	}
	public function form($userId = NULL){
		
		if($this->isAdmin() == TRUE){
            $this->loadThis();
        }
        else {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();
            
            $this->global['pageTitle'] = 'CodeInsect : Add New User';
			$entity = array(
				'primaryKeyField'=>2,
				'field1'=>'',
				'field2'=>''
			); 
			
			$data['entity'] = $entity;
            $this->loadMaterialViews("entity/entityForm", $this->global, $data, NULL);
        }
		
		//$entityList = $this->getEntityList($limit);
		//$data['entityList'] = $entityList;
		//$data['error'] = '';
		//$data['success'] = '';
		
		//$entitys = $this->read($primaryKeyField);
		//$data['entity'] = $entitys;
		
		//$this->load->view('entity/entityForm', $data);
	}
	public function create(){
		$isValid = false;
		$message = "";
	
		$post = $this->input->post();
		//$data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
			
		$primaryKeyField = 0;
		$this->load->model('EntityModel');
					
		try {
			$primaryKeyField = $this->EntityModel->create($post);
			
		} catch (Exception $e) {
			echo 'error to create data';
		}
		
		$entityList = array();
		$limit = 10;
		$this->load->library('pagination');	
		//$entityList = $this->getEntityList($limit);
		$data['entityList'] = $this->EntityModel->getAll();
		//$data['entityList'] = $entityList;
		$data['error'] = '';
		$data['success'] = 'Successfully Entity Published.';
		 $data['searchText'] = '';
            
        $this->loadMaterialViews("entity/entityList", $this->global, $data, NULL);	
		//$this->load->view('entity/entityList', $data);
	}
	public function read($primaryKeyField){
		$this->load->model('EntityModel');
		$this->load->helper('url');
		$blog = array();
		try {
			$blog = $this->EntityModel->findOne($primaryKeyField);
		} catch (Exception $e) {
			echo 'error to fetch data';
		}
		return $blog;
	}
	public function readList($limit){
		$this->load->model('EntityModel');
		$this->load->helper('url');
		try {
			$entityList = $this->EntityModel->get($limit);
		} catch (Exception $e) {
			echo 'error to insert data';
		}
	
		return $entityList;
	
	}
	public function edit($primaryKeyField){
		$entityList = array();
		$limit = 10;
		$data = array();
		
		$data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
		
		$entityList = $this->getEntityList($limit);
		$data['entityList'] = $entityList;
		$data['error'] = '';
		$data['success'] = '';
		
		$entitys = $this->read($primaryKeyField);
		$data['entity'] = $entitys;
		
		$this->load->view('entity/entityForm', $data);
	}

	public function update(){
		$post = $this->input->post();
		$primaryKeyField = $post['primaryKeyField'];
		
		$userfile = $post['fileName'];

		$data = array();
		$data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
		
		$this->load->model('EntityModel');
		$entitys = $this->EntityModel->findOne($primaryKeyField);
		$entity = $entitys[0];
		try {
			$primaryKeyField = $this->EntityModel->update($post);
		} catch (Exception $e) {
			echo 'error to insert data';
		}
	
		$entityList = array();
		$limit = 10;
	
		$entityList = $this->getEntityList($limit);
		$data['entityList'] = $entityList;
		$data['error'] = '';
		$data['success'] = 'Successfully Entity Published.';
	
		$this->load->view('entity/entityList', $data);
			
	
	}
	public function delete($primaryKeyField){
        //$data['user'] = $this->user->getRows(array('id'=>$this->session->userdata('userId')));
		$this->load->database();
		$this->load->model('EntityModel');
		$post = $this->input->post();
		try {
			$post = $this->input->post();
			$entitys = array();
			
			$entitys = $this->EntityModel->findOne($primaryKeyField);
			$this->EntityModel->delete($primaryKeyField);
			
		$entityList = array();
		$limit = 10;
		$this->load->library('pagination');	
		//$entityList = $this->getEntityList($limit);
		$data['entityList'] = $this->EntityModel->getAll();
		//$data['entityList'] = $entityList;
		$data['error'] = '';
		$data['success'] = 'Successfully Entity Deleted.';
		 $data['searchText'] = '';
            
        $this->loadViews("entity/entityList", $this->global, $data, NULL);	
		} catch (Exception $e) {
			echo 'error to delete data';
		}
	
		
	}
	
}

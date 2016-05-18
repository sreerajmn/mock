<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam extends CI_Controller {

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->model("exam_model");
	   $this->load->model("user_model");
	   $this->lang->load('basic', $this->config->item('language'));
		// redirect if not loggedin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');		
		redirect('login');
		}
	 }

	public function index($limit='0')
	{
		
		$logged_in=$this->session->userdata('logged_in');
			 
			
			
		$data['limit']=$limit;
		$data['title']=$this->lang->line('exam');
		// fetching exam list
		$data['result']=$this->exam_model->exam_list($limit);
		$this->load->view('header',$data);
		$this->load->view('exam_list',$data);
		$this->load->view('footer',$data);
	}
	
	public function add_new()
	{
		
		$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}
			
			
	 
		$data['title']=$this->lang->line('add_new').' '.$this->lang->line('exam');
		// fetching group list
		$data['group_list']=$this->user_model->group_list();
		$this->load->view('header',$data);
		$this->load->view('new_exam',$data);
		$this->load->view('footer',$data);
	}
	
		
		
	
	
	
	
	
	
	
		public function edit_exam($quid)
	{
		
		$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}
			
			
	 
		$data['title']=$this->lang->line('edit').' '.$this->lang->line('exam');
		// fetching group list
		$data['group_list']=$this->user_model->group_list();
		$data['exam']=$this->exam_model->get_exam($quid);
		if($data['exam']['question_selection']=='0'){
		$data['questions']=$this->exam_model->get_questions($data['exam']['qids']);
			 
		}else{
			$this->load->model("qbank_model");
	   $data['qcl']=$this->exam_model->get_qcl($data['exam']['quid']);
		
			 $data['category_list']=$this->qbank_model->category_list();
		 $data['level_list']=$this->qbank_model->level_list();
		
		}
		$this->load->view('header',$data);
		$this->load->view('edit_exam',$data);
		$this->load->view('footer',$data);
	}
	
	
	
	
	function no_q_available($cid,$lid){
		$val="<select name='noq[]'>";
		$query=$this->db->query(" select * from mock_qbank where cid='$cid' and lid='$lid' ");
		$nor=$query->num_rows();
		for($i=0; $i<= $nor; $i++){
			$val.="<option value='".$i."' >".$i."</option>";
			
			
		}
		$val.="</select>";
		echo $val;
		
	}
	
	
	
	
	function remove_qid($quid,$qid){
		
		if($this->exam_model->remove_qid($quid,$qid)){
                        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('removed_successfully')." </div>");
		}
		redirect('exam/edit_exam/'.$quid);
	}
	
	function add_qid($quid,$qid){
		
		 $this->exam_model->add_qid($quid,$qid);
          echo 'added';              
	}
	
	
	
	function pre_add_question($quid,$limit='0',$cid='0',$lid='0'){
		$cid=$this->input->post('cid');
		$lid=$this->input->post('lid');
		redirect('exam/add_question/'.$quid.'/'.$limit.'/'.$cid.'/'.$lid);
	}
	
	
	
		public function add_question($quid,$limit='0',$cid='0',$lid='0')
	{
		$this->load->model("qbank_model");
	   
		
		$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
			exit($this->lang->line('permission_denied'));
			}
			
			
	 
		 $data['exam']=$this->exam_model->get_exam($quid);
		$data['title']=$this->lang->line('add_question_into_exam').': '.$data['exam']['exam_name'];
		if($data['exam']['question_selection']=='0'){
		
		$data['result']=$this->qbank_model->question_list($limit,$cid,$lid);
		 $data['category_list']=$this->qbank_model->category_list();
		 $data['level_list']=$this->qbank_model->level_list();
			 
		}else{
			
			exit($this->lang->line('permission_denied'));
		}
		$data['limit']=$limit;
		$data['cid']=$cid;
		$data['lid']=$lid;
		$data['quid']=$quid;
		
		$this->load->view('header',$data);
		$this->load->view('add_question_into_exam',$data);
		$this->load->view('footer',$data);
	}
	
	
	
	
	function up_question($quid,$qid,$not='1'){
	$logged_in=$this->session->userdata('logged_in');
	if($logged_in['su']!="1"){
	exit($this->lang->line('permission_denied'));
	return;
	}		
	for($i=1; $i <= $not; $i++){
	$this->exam_model->up_question($quid,$qid);
	}
	redirect('exam/edit_exam/'.$quid, 'refresh');
	}
	
	
	
	
	
	
	function down_question($quid,$qid,$not='1'){
	$logged_in=$this->session->userdata('logged_in');
	if($logged_in['su']!="1"){
	exit($this->lang->line('permission_denied'));
	return;
	}	
			for($i=1; $i <= $not; $i++){
	$this->exam_model->down_question($quid,$qid);
	}
	redirect('exam/edit_exam/'.$quid, 'refresh');
	}
	
	
	
	
		public function insert_exam()
	{
		
	 
	
	
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('exam_name', 'exam_name', 'required');
           if ($this->form_validation->run() == FALSE)
                {
                     $this->session->set_flashdata('message', "<div class='alert alert-danger'>".validation_errors()." </div>");
					redirect('exam/add_new/');
                }
                else
                {
					$quid=$this->exam_model->insert_exam();
                   
					redirect('exam/edit_exam/'.$quid);
                }       

	}
	
		public function update_exam($quid)
	{
		
		
			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('exam_name', 'exam_name', 'required');
           if ($this->form_validation->run() == FALSE)
                {
                     $this->session->set_flashdata('message', "<div class='alert alert-danger'>".validation_errors()." </div>");
					redirect('exam/edit_exam/'.$quid);
                }
                else
                {
					$quid=$this->exam_model->update_exam($quid);
                   
					redirect('exam/edit_exam/'.$quid);
                }       

	}
	
	
	
	

	
	
	
	
	
	
	public function remove_exam($quid){

			$logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			} 
			
			if($this->exam_model->remove_exam($quid)){
                        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('removed_successfully')." </div>");
					}else{
						    $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_remove')." </div>");
						
					}
					redirect('exam');
                     
			
		}
	



	public function exam_detail($quid){
		
		$logged_in=$this->session->userdata('logged_in');
		$gid=$logged_in['gid'];
		$data['title']=$this->lang->line('attempt').' '.$this->lang->line('exam');
		
		$data['exam']=$this->exam_model->get_exam($quid);
		$this->load->view('header',$data);
		$this->load->view('exam_detail',$data);
		$this->load->view('footer',$data);
		
	}
	
	public function validate_exam($quid){
		 
		$logged_in=$this->session->userdata('logged_in');
		$gid=$logged_in['gid'];
		$uid=$logged_in['uid'];
		 
		 // if this exam already opened by user then resume it
		 $open_result=$this->exam_model->open_result($quid,$uid);
		if($open_result != '0'){
		$this->session->set_userdata('rid', $open_result);
		redirect('exam/attempt/'.$open_result);	
		}
		$data['exam']=$this->exam_model->get_exam($quid);
		// validate assigned group
		if(!in_array($gid,explode(',',$data['exam']['gids']))){
		$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('exam_not_assigned_to_your_group')." </div>");
		redirect('exam/exam_detail/'.$quid);
		 }
		// validate start end date/time
		if($data['exam']['start_date'] > time()){
		$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('exam_not_available')." </div>");
		redirect('exam/exam_detail/'.$quid);
		 }
		// validate start end date/time
		if($data['exam']['end_date'] < time()){
		$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('exam_ended')." </div>");
		redirect('exam/exam_detail/'.$quid);
		 }

		// validate ip address
		if($data['exam']['ip_address'] !=''){
		$ip_address=explode(",",$data['exam']['ip_address']);
		$myip=$_SERVER['REMOTE_ADDR'];
		if(!in_array($myip,$ip_address)){
		$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('ip_declined')." </div>");
		redirect('exam/exam_detail/'.$quid);
		 }
		}
		 // validate maximum attempts
		$maximum_attempt=$this->exam_model->count_result($quid,$uid);
		if($data['exam']['maximum_attempts'] <= $maximum_attempt){
		$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('reached_maximum_attempt')." </div>");
		redirect('exam/exam_detail/'.$quid);
		 }
		
		// insert result row and get rid (result id)
		$rid=$this->exam_model->insert_result($quid,$uid);
		
		$this->session->set_userdata('rid', $rid);
		redirect('exam/attempt/'.$rid);	
		
		
	}
	
	
	
	function attempt($rid){
		  $srid=$this->session->userdata('rid');
						// if linked and session rid is not matched then something wrong.
			if($rid != $srid){
		 
		$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('exam_ended')." </div>");
		redirect('exam/');

			}

		if(!$this->session->userdata('logged_in')){
			exit($this->lang->line('permission_denied'));
		}
		// get result and exam info and validate time period
		$data['exam']=$this->exam_model->exam_result($rid);
		$data['saved_answers']=$this->exam_model->saved_answers($rid);
		

			
			
		// end date/time
		if($data['exam']['end_date'] < time()){
		$this->exam_model->submit_result($rid);
		$this->session->unset_userdata('rid');
		$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('exam_ended')." </div>");
		redirect('exam/exam_detail/'.$data['exam']['quid']);
		 }

		
		// end date/time
		if(($data['exam']['start_time']+($data['exam']['duration']*60)) < time()){
		$this->exam_model->submit_result($rid);
		$this->session->unset_userdata('rid');
		$this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('time_over')." </div>");
		redirect('exam/exam_detail/'.$data['exam']['quid']);
		 }
		// remaining time in seconds 
		$data['seconds']=($data['exam']['duration']*60) - (time()- $data['exam']['start_time']);
		// get questions
		$data['questions']=$this->exam_model->get_questions($data['exam']['r_qids']);
		// get options
		$data['options']=$this->exam_model->get_options($data['exam']['r_qids']);
		$data['title']=$data['exam']['exam_name'];
		$this->load->view('header',$data);
		$this->load->view('exam_attempt',$data);
		$this->load->view('footer',$data);
			
		}
		
		
	
	
	function save_answer(){
		echo "<pre>";
		print_r($_POST);
		  // insert user response and calculate scroe
		echo $this->exam_model->insert_answer();
		
		
	}
 function set_ind_time(){
		  // update questions time spent
		$this->exam_model->set_ind_time();
		
		
	}
 
 
 
 function upload_photo(){

if(isset($_FILES['webcam'])){
			$targets = 'photo/';
			$filename=time().'.jpg';
			$targets = $targets.''.$filename;
			if(move_uploaded_file($_FILES['webcam']['tmp_name'], $targets)){
			
				$this->session->set_flashdata('photoname', $filename);
				}
				}
}



 function submit_exam(){
	 
				if($this->exam_model->submit_result()){
                        $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('exam_submit_successfully')." </div>");
					}else{
						    $this->session->set_flashdata('message', "<div class='alert alert-danger'>".$this->lang->line('error_to_submit')." </div>");
						
					}
			$this->session->unset_userdata('rid');		
					
 redirect('exam');
 }
 
 
 
 function assign_score($rid,$qno,$score){
	 $logged_in=$this->session->userdata('logged_in');
			if($logged_in['su']!='1'){
				exit($this->lang->line('permission_denied'));
			} 
			$this->exam_model->assign_score($rid,$qno,$score);
			
			echo '1';
	 
 }
 
 
	
}

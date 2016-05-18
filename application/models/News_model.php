<?php
Class News_model extends CI_Model
{
 
  function news_list($limit){
	  
	  $logged_in=$this->session->userdata('logged_in');	
	 if($this->input->post('search') && $logged_in['su']=='1'){
		 $search=$this->input->post('search');
		 $this->db->or_like('title',$search);
		 $this->db->or_like('content',$search);

	 }
        $this->db->limit($this->config->item('number_of_rows'),$limit);
        $this->db->order_by('news_id','desc');
        $query=$this->db->get('mock_news');
        return $query->result_array();
	 
 }
 
 function num_news(){
	 
	 $query=$this->db->get('mock_news');
		return $query->num_rows();
 }
 
 function insert_news(){
	 
	 $userdata=array(
	 'title'=>$this->input->post('title'),
	 'content'=>$this->input->post('content'),
	 'created_at'=>date('Y-m-d H:i:s')
	 );
	 
	  $this->db->insert('mock_news',$userdata);
	 $news_id=$this->db->insert_id();
	return $news_id;
	 
 }
 
 
 function update_news($news_id){
	 
	 $userdata=array(
	 'title'=>$this->input->post('title'),
	 'content'=>$this->input->post('content')
	 );
	 
	  $this->db->where('news_id',$news_id);
	  $this->db->update('mock_news',$userdata);
	  
	return $news_id;
	 
 }
 
 function get_news($news_id){
	 $this->db->where('news_id',$news_id);
	 $query=$this->db->get('mock_news');
	 return $query->row_array();	 
 } 
 
 function remove_news($news_id){
	 
	 $this->db->where('news_id',$news_id);
	 if($this->db->delete('mock_news')){
		 
		 return true;
	 }else{
		 
		 return false;
	 }	 
 }
}
?>
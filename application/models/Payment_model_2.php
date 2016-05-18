<?php
Class Payment_model_2 extends CI_Model
{
  
   
 
  
 function activate_group($uid,$gid,$amount,$transaction_id,$payment_gateway){
	 
	 
	 $this->db->where('gid',$gid);
	 $query=$this->db->get('mock_group');
	 $group=$query->row_array();
	 
	 $vd=($group['valid_for_days']*24*60*60);
	 $expiry_date=time()+$vd;
	 
	 $userdata=array(
	 'subscription_expired'=>$expiry_date
	 );
	  $this->db->where('uid',$uid);
	$this->db->update('mock_users',$userdata);



	 $userdata=array(
	 'uid'=>$uid,
	 'gid'=>$gid,
	 'amount'=>$amount,
	 'transaction_id'=>$transaction_id,
	 'paid_date'=>time(),
	 'payment_gateway'=>$payment_gateway,
	 'payment_status'=>$this->lang->line('success')
	 );
	  
	$this->db->insert('mock_payment',$userdata);
	
 }
 
 
 function validate_transaction_id($transaction_id){
	 
	 	 $this->db->where('gid',$gid);
	 $query=$this->db->get('mock_group');
	 return  $query->num_rows();
 }
 
 

}












?>
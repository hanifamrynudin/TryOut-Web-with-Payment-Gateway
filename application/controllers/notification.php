<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends CI_Controller {

	 function __construct()
	 {
	   parent::__construct();
	   $this->load->database();
	   $this->load->helper('url');
	   $this->load->model("notification_model");
	   $this->lang->load('basic', $this->config->item('language'));
	   $params = array('server_key' => 'SB-Mid-server-ux5ErGxneruH2wYtu4s7bwwY', 'production' => false);
	   $this->load->library('veritrans');
	   $this->veritrans->config($params);
	 }
	public function index()
	{
		echo 'test notification handler';
		$json_result = file_get_contents('php://input');
		$result = json_decode($json_result);
		
		$order_id = $result['order_id'];
		$data[
			"status_code" => $result['status_code']
		];
		if ($result['status_code' == 200]){
			this->db->update('trans_midtrans', $data, array('order_id'=>$order_id));
		}

	// 	error_log(print_r($result,TRUE));

		//notification handler sample

		/*
		$transaction = $notif->transaction_status;
		$type = $notif->payment_type;
		$order_id = $notif->order_id;
		$fraud = $notif->fraud_status;

		if ($transaction == 'capture') {
		  // For credit card transaction, we need to check whether transaction is challenge by FDS or not
		  if ($type == 'credit_card'){
		    if($fraud == 'challenge'){
		      // TODO set payment status in merchant's database to 'Challenge by FDS'
		      // TODO merchant should decide whether this transaction is authorized or not in MAP
		      echo "Transaction order_id: " . $order_id ." is challenged by FDS";
		      } 
		      else {
		      // TODO set payment status in merchant's database to 'Success'
		      echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
		      }
		    }
		  }
		else if ($transaction == 'settlement'){
		  // TODO set payment status in merchant's database to 'Settlement'
		  echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
		  } 
		  else if($transaction == 'pending'){
		  // TODO set payment status in merchant's database to 'Pending'
		  echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
		  } 
		  else if ($transaction == 'deny') {
		  // TODO set payment status in merchant's database to 'Denied'
		  echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
		}*/

	// }

	public function index($limit='0')
	{
		// AWal Midtrans
		echo 'test notification handler';
		$json_result = file_get_contents('php://input');
		$result = json_decode($json_result);

		if($result){
		$notif = $this->veritrans->status($result->order_id);
		}

		error_log(print_r($result,TRUE));

		// Akhir Midtrans

		
		// redirect if not loggedin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');		
		redirect('login');
		}
		
	 
			
		
		 	 
		$uid=$logged_in['uid'];
$this->db->query("update savsoft_notification set viewed='1' where uid='$uid' ");		
			
	        $data['limit']=$limit;
		$data['title']=$this->lang->line('notification');
		// fetching quiz list
		$data['result']=$this->notification_model->notification_list($limit);
		$this->load->view('header',$data);
		$this->load->view('notification_list',$data);
		$this->load->view('footer',$data);
	}
	
	
	
	public function register_token($device,$uid){
	 if($device=='web'){
	 $userdata=array(
	 'web_token'=>$_POST['currentToken']	 
	 );
	 }else{
	  $userdata=array(
	 'android_token'=>$_POST['currentToken']	 
	 );
	 }
	$this->db->where('uid',$uid);
	$this->db->update('savsoft_users',$userdata);
	
	}
	
	
	
	public function add_new($tuid='0'){
	
	// redirect if not loggedin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');		
		redirect('login');
		}
		
                        $acp=explode(',',$logged_in['setting']);
			if(!in_array('All',$acp)){
			exit($this->lang->line('permission_denied'));
			} 
		
	$data['title']=$this->lang->line('send_notification');
	$data['tuid']=$tuid;	
	        $this->load->view('header',$data);
		$this->load->view('new_notification',$data);
		$this->load->view('footer',$data);
	}
	
	
	public function send_notification(){
	
	
	// redirect if not loggedin
		if(!$this->session->userdata('logged_in')){
			redirect('login');
			
		}
		$logged_in=$this->session->userdata('logged_in');
		if($logged_in['base_url'] != base_url()){
		$this->session->unset_userdata('logged_in');		
		redirect('login');
		}
		
                        $acp=explode(',',$logged_in['setting']);
			if(!in_array('All',$acp)){
			exit($this->lang->line('permission_denied'));
			} 
		
	foreach($_POST['notification_to'] as $nk => $nval){
	if($nval != ''){	
	 $fields = array(
            'to' => $nval,
            'icon' => 'logo',
	    'sound'=>'default',
            'data' =>array('message'=> $_POST['message']),
            'notification' =>array('title'=> $_POST['title'],'body'=> $_POST['message'],'click_action'=>$_POST['click_action']),
        );
   // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
 $firebase_serverkey=$this->config->item('firebase_serverkey');
        $headers = array(
            'Authorization: key='.$firebase_serverkey,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        // Close connection
        curl_close($ch);
        
        
	
	$this->notification_model->insert_notification($result,$nval);
	}
	}
	  $this->session->set_flashdata('message', "<div class='alert alert-success'>".$this->lang->line('notification_sent')." </div>");
		 
		redirect('notification/index');
	
	}
	
	
	
	
 
	
}

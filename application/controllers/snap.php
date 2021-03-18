<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Snap extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */


	public function __construct()
    {
		parent::__construct();
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: PUT,GET,POST");
		header("Access-Control-Allow-header: Origin, X-Requested-With, Content-Type, Accept");
        $params = array('server_key' => 'SB-Mid-server-ux5ErGxneruH2wYtu4s7bwwY', 'production' => false);
		$this->load->library('midtrans');
		$this->midtrans->config($params);
		$this->load->helper('url');	
    }

    public function index()
    {
    	$this->load->view('checkout_snap');
    }


    public function token()
    {
		$email = $this->input->post('email');
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$kelas = $this->input->post('kelas');
		$jmlbayar = $this->input->post('jmlbayar');
		// Required
		$transaction_details = array(
		  'order_id' => rand(),
		  'gross_amount' => $jmlbayar, // no decimal allowed for creditcard
		);

		// Optional
		$item1_details = array(
		  'id' => 'a1',
		  'price' => $jmlbayar,
		  'quantity' => 1,
		  'name' => $kelas
		);

		// // Optional
		// $item2_details = array(
		//   'id' => 'a2',
		//   'price' => 20000,
		//   'quantity' => 2,
		//   'name' => "Orange"
		// );

		// Optional
		$item_details = array ($item1_details);

		// Optional
		// $billing_address = array(
		//   'first_name'    => "Andri",
		//   'last_name'     => "Litani",
		//   'address'       => "Mangga 20",
		//   'city'          => "Jakarta",
		//   'postal_code'   => "16602",
		//   'phone'         => "081122334455",
		//   'country_code'  => 'IDN'
		// );

		// // Optional
		// $shipping_address = array(
		//   'first_name'    => "Obet",
		//   'last_name'     => "Supriadi",
		//   'address'       => "Manggis 90",
		//   'city'          => "Jakarta",
		//   'postal_code'   => "16601",
		//   'phone'         => "08113366345",
		//   'country_code'  => 'IDN'
		// );

		// Optional
		$customer_details = array(
		  'first_name'    => $first_name,
		  'last_name'     => $last_name,
		  'email'         => $email,
		);

		// Data yang akan dikirim untuk request redirect_url.
        $credit_card['secure'] = true;
        //ser save_card true to enable oneclick or 2click
        //$credit_card['save_card'] = true;

        $time = time();
        $custom_expiry = array(
            'start_time' => date("Y-m-d H:i:s O",$time),
            'unit' => 'minute', 
            'duration'  => 2
        );
        
        $transaction_data = array(
            'transaction_details'=> $transaction_details,
            'item_details'       => $item_details,
            'customer_details'   => $customer_details,
            'credit_card'        => $credit_card,
            'expiry'             => $custom_expiry
        );

		error_log(json_encode($transaction_data));
		$snapToken = $this->midtrans->getSnapToken($transaction_data);
		error_log($snapToken);
		echo $snapToken;
    }

    public function finish()
    {

		$result = json_decode($this->input->post('result_data'),true);
		// echo"<pre>";
		// var_dump($result);
		// echo"</pre>";
		// echo $result['va_numbers'][0]["bank"];
		// die;
		if(isset($result['va_numbers'][0]["bank"])){
			$bank = $result['va_numbers'][0]["bank"];
		}else{
			$bank = '-';
		}
		if(isset($result['va_numbers'][0]["va_number"])){
			$va_number = $result['va_numbers'][0]["va_number"];
		}else{
			$va_number = '-';
		}
		$email = $this->input->post('email');
		$kelas = $this->input->post('group_name');
		
		$data = [
			'order_id' => $result['order_id'],
			'email' => $email,
			'kelas' => $kelas,
			'gross_amount' => $result['gross_amount'],
			'payment_type' => $result['payment_type'],
			'transaction_time' => $result['transaction_time'],
			'bank' => $bank,
			'va_number' => $va_number,
			'status_code' => $result['status_code']
		];

		$simpan = $this->db->insert('trans_midtrans', $data);
		if ($simpan){
			redirect('user/change_group_history');
		}else{
			echo"Gagal";	
		}
    	

    }
}

<!-- <?php if (!define('BASEPATH')) exit('No direct script access allowed');

class Snapmodel extends CI_model
{
    public $table = 'transaksi_midtrans';
    public $primaryKey = 'order_id';

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
        return $this->db->insert($this->table, $data);
    }
}
?> -->
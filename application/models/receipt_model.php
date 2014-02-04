<?php
class Receipt_Model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
//after orders added	
	public function add_receipt(){
		$invoice_id = $this->input->post('invoice_id');
		if($this->check_invoice_available($invoice_id)){
			$data = array(
				'invoice_id' =>$invoice_id,
				'amount' => $this->input->post('amount'),
				'type' =>$this->input->post('type'),
				'confirmed' => ($this->input->post('type')=='cash'?1:0),
				'date_paid' =>$this->get_current_date()
			);
			if($this->db->insert('receipts',$data)){
				return $this->db->insert_id();
			}else{
				return false;
			}
		}else{
			$this->session->set_flashdata('alert_error','The invoice you put does not exist');
			return FALSE;
		}		
	}
	
	public function get_total_payments($invoice_id){
		$this->db->select('sum(amount) as total')->where('confirmed',1)->where('invoice_id', $invoice_id);
		$result = $this->db->get('receipts')->row_array();
		return $result['total'];
	}
	
	public function get_payment_options(){
		return $this->db->get('payment_options')->result_array();
	}
	
	public function get_receipts($condition = array()){
		$this->db->select('*')->from('receipts')->where($condition);
		return $this->db->get()->result_array();
	}
	public function receipt_exists($receipt_id){
		$this->db->select()->form()->where();
		$result = $this->db->get();
		if($result->num_rows() == 1)
			return TRUE;
		return FALSE;
	}
	
	public function get_client_receipts($client_id){
		$this->db->select('*')->from('receipts')->join('invoices','receipts.invoice_id = invoices.id')
				->join('orders','invoices.order_id = orders.id')->join('clients','clients.id = orders.client_id')
				->where('clients.id',$client_id);
		return $this->db->get()->result_array();
	}
	//
	
	
	private function check_active_order(){}
	
	private function activate_order(){}
	
	private function check_invoice_available($invoice_id){
		$this->db->select('id')->from('invoices')->where('id',$invoice_id);
		$result = $this->db->count_all_results();
		if($result>0)return TRUE;
		else return FALSE;
	}
	
	public function get_receipt_details($receipt_id){
		$this->db->where('id', $receipt_id);
		$result = $this->db->get('receipts');
		return $result->row_array();
	}
	
	public function get_current_date(){
		$this->db->select('now() as date');
		$result =$this->db->get()->row_array();
		return $result['date'];
	}

	public function get_balance(){
	
	}
	
	public function get_invoice_id($receipt_id){
		$result =$this->get_receipt_details($receipt_id);
		return $result['invoice_id'];
	}
	
	public function addReceipt(){
		$data = array(
			'invoice_id' =>$this->input->post('invoice_id'),
			'amount' => $this->input->post('amount')
		);
		$this->db->insert('receipt',$data);	
	}
	public function getReceipts(){
		$result = $this->db->get('receipt');
		return $result->result_array();
	}
	public function listFields($table){
		$query = $this->db->list_fields($table);
		return $query;
	}


}

?>
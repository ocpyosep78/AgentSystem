<?php
class Receipt_Model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}
//after orders added	

	public function count_agent_receipts($id){
		$this->db->select('*')->from('receipts')->where('receipient_id', $id);
		return $this->db->count_all_results();
	}
	public function count_marketer_receipts($id){
		$this->db->select()->from('receipts')->join('agentlinks','agentlinks.agent = receipts.receipient_id')
				->where('agentlinks.marketer',$id);
		return $this->db->count_all_results();
	}
	public function add_receipt($id){
		$invoice_id = $this->input->post('invoice_id');
		if($this->check_invoice_available($invoice_id)){
			$data = array(
				'invoice_id' =>$invoice_id,
				'amount' => $this->input->post('amount'),
				'type' =>$this->input->post('type'),
				'confirmed' => ($this->input->post('type')=='cash'?1:0),
				'date_paid' =>$this->get_current_date(),
				'ref_no' =>$this->input->post('Ref_no'),
				'receipient_id'=>$id

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
		$this->db->select('*')->from('receipts')->where('id',$receipt_id);
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
	public function confirm_receipt($receipt_id){
	
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
	
	public function addReceipt($id){
		$ref_no = (!empty($this->input->post('Ref_no'))?$this->input->post('Ref_no'):Null);
		$data = array(
			'invoice_id' =>$this->input->post('invoice_id'),
			'amount' => $this->input->post('amount'),
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
	
	public function get_unconfirmed(){
		$condition = array('confirmed' =>0);
		return $this->get_receipts($condition);
	}
	
	public function count_unconfirmed(){
		$this->db->select('*')->from('receipts')->where('confirmed',0);
		return $this->db->count_all_results();
	}
	
	public function update_confirmed($receipt_id){
		$data =array('confirmed' =>1);
		$this->db->update_receipt($receipt_id,$data);
	}
	
	public function update_receipt($receipt_id, $data){
		return $this->db->update('orders',$data, array('id'=>$receipt_id));
	}



}

?>
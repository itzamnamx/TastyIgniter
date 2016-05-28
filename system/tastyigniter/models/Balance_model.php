<?php
/**
 * TastyIgniter
 *
 * An open source online ordering, reservation and management system for restaurants.
 *
 * @package   TastyIgniter
 * @author    SamPoyigi
 * @copyright TastyIgniter
 * @link      http://tastyigniter.com
 * @license   http://opensource.org/licenses/GPL-3.0 The GNU GENERAL PUBLIC LICENSE
 * @since     File available since Release 1.0
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Balance Model Class
 *
 * @category       Models
 * @package        TastyIgniter\Models\Balance_model.php
 * @link           http://docs.tastyigniter.com
 */
class Balance_model extends TI_Model {

	

	public function getBalance($customer_id) {
		if  ($customer_id !== '0') {
			$this->db->from('balance');			
			$this->db->where('customer_id', $customer_id);

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row_array();
			}
		}
	}

	

	public function getDefault($customer_id) {
		if  ($customer_id !== '0') {
			$this->db->from('balance');			
			$this->db->where('customer_id', $customer_id);

			$query = $this->db->get();
			if ($query->num_rows() > 0) {
				return $query->row_array();
			}
		}
	}

	public function updateDefault($customer_id = '', $amount = '') {
		$query = FALSE;

		if ($amount !== '' AND $customer_id !== '') {
			$this->db->set('amount', $amount);
			$this->db->where('customer_id', $customer_id);

			$query = $this->db->update('balance');
		}

		return $query;
	}

	public function saveBalance($customer_id = FALSE ) {

		
		if ($customer_id) {
			$this->db->set('customer_id', $customer_id);
		}

		if ($amount) {
			$this->db->set('amount', $amount);
		}

		
		if (is_numeric($customer_id)) {
			$this->db->where('customer_id', $customer_id);
			$query = $this->db->update('balance');
		} else {
			$query = $this->db->insert('balance');
			$balance_id = $this->db->insert_id();
		}

		return ($query === TRUE AND is_numeric($balance_id)) ? $balance_id : FALSE;
	}

	public function deleteBalance($customer_id) {

		$this->db->where('customer_id', $customer_id);		
		$this->db->delete('balance');

		if ($this->db->affected_rows() > 0) {
			return TRUE;
		}
	}
}

/* End of file balance_model.php */
/* Location: ./system/tastyigniter/models/balance_model.php */
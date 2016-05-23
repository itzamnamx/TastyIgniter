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
 * Transactions Model Class
 *
 * @category       Transactions
 * @package        TastyIgniter\Models\Transactions_model.php
 * @link           http://docs.tastyigniter.com
 */
class Transactions_model extends TI_Model {

	public function getCount($filter = array()) {
		if (APPDIR === ADMINDIR) {
			if ( ! empty($filter['filter_search'])) {
				$this->db->like('transaction_id', $filter['filter_search']);
				$this->db->or_like('location_name', $filter['filter_search']);
				$this->db->or_like('first_name', $filter['filter_search']);
				$this->db->or_like('last_name', $filter['filter_search']);
			}

			if ( ! empty($filter['filter_location'])) {
				$this->db->where('transactions.location_id', $filter['filter_location']);
			}

			if (isset($filter['filter_type']) AND is_numeric($filter['filter_type'])) {
				$this->db->where('transaction_type', $filter['filter_type']);
			}

			if (isset($filter['filter_status']) AND is_numeric($filter['filter_status'])) {
				$this->db->where('transactions.status_id', $filter['filter_status']);
			}

			if ( ! empty($filter['filter_date'])) {
				$date = explode('-', $filter['filter_date']);
				$this->db->where('YEAR(date_added)', $date[0]);
				$this->db->where('MONTH(date_added)', $date[1]);
			}
		} else {
			if ( ! empty($filter['customer_id']) AND is_numeric($filter['customer_id'])) {
				$this->db->where('customer_id', $filter['customer_id']);
			}

			$this->db->where('transactinos.status_id !=', '0');
		}

		$this->db->from('transactions');
		$this->db->join('locations', 'locations.location_id = transactions.location_id', 'left');

		return $this->db->count_all_results();
	}

	public function getList($filter = array()) {
		if ( ! empty($filter['page']) AND $filter['page'] !== 0) {
			$filter['page'] = ($filter['page'] - 1) * $filter['limit'];
		}

		if ($this->db->limit($filter['limit'], $filter['page'])) {
			$this->db->select('*, transactions.status_id, status_name, status_color, transactions.date_added, transactions.date_modified');
			$this->db->from('transactions');
			$this->db->join('statuses', 'statuses.status_id = transactions.status_id', 'left');
			$this->db->join('locations', 'locations.location_id = transactions.location_id', 'left');

			if ( ! empty($filter['sort_by']) AND ! empty($filter['order_by'])) {
				$this->db->order_by($filter['sort_by'], $filter['order_by']);
			}

			if ( ! empty($filter['customer_id']) AND is_numeric($filter['customer_id'])) {
				$this->db->where('customer_id', $filter['customer_id']);
			}

			if ( ! empty($filter['filter_location'])) {
				$this->db->where('transactions.location_id', $filter['filter_location']);
			}

			if ( ! empty($filter['filter_search'])) {
				$this->db->like('transaction_id', $filter['filter_search']);
				$this->db->or_like('location_name', $filter['filter_search']);
				$this->db->or_like('first_name', $filter['filter_search']);
				$this->db->or_like('last_name', $filter['filter_search']);
			}

			if (isset($filter['filter_type']) AND is_numeric($filter['filter_type'])) {
				$this->db->where('transaction_type', $filter['filter_type']);
			}

			if (isset($filter['filter_status']) AND is_numeric($filter['filter_status'])) {
				$this->db->where('transactions.status_id', $filter['filter_status']);
			}

			if ( ! empty($filter['filter_date'])) {
				$date = explode('-', $filter['filter_date']);
				$this->db->where('YEAR(date_added)', $date[0]);
				$this->db->where('MONTH(date_added)', $date[1]);
			}

			if (APPDIR === MAINDIR) {
				$this->db->where('transactions.status_id !=', '0');
			}

			$query = $this->db->get();
			$result = array();

			if ($query->num_rows() > 0) {
				$result = $query->result_array();
			}

			return $result;
		}
	}

	public function getTransaction($transaction_id = FALSE, $customer_id = '') {
		if ( ! empty($transaction_id)) {
			$this->db->from('transactions');
			$this->db->join('statuses', 'statuses.status_id = transactions.status_id', 'left');
			$this->db->join('locations', 'locations.location_id = transactions.location_id', 'left');
			$this->db->where('transaction_id', $transaction_id);

			if ( ! empty($customer_id)) {
				$this->db->where('customer_id', $customer_id);
			}

			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				return $query->row_array();
			}
		}

		return $transaction_id;
	}

	public function getInvoice($transaction_id = NULL) {
		if ( ! empty($transaction_id) AND is_numeric($transaction_id)) {
			$this->db->from('transactions');
			$this->db->join('statuses', 'statuses.status_id = transactions.status_id', 'left');
			$this->db->join('locations', 'locations.location_id = transactions.location_id', 'left');
			$this->db->where('transaction_id', $transaction_id);

			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				return $query->row_array();
			}
		}

		return FALSE;
	}

	public function getCheckoutTransaction($transaction_id, $customer_id) {
		if (isset($transaction_id, $customer_id)) {
			$this->db->from('transactions');

			$this->db->where('transaction_id', $transaction_id);
			$this->db->where('customer_id', $customer_id);
			$this->db->where('status_id', NULL);

			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				return $query->row_array();
			}
		}

		return FALSE;
	}

	public function getTransactionMenus($transaction_id) {
		$this->db->from('transaction_menus');
		$this->db->where('transaction_id', $transaction_id);

		$query = $this->db->get();
		$result = array();

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
		}

		return $result;
	}

	public function getTransactionMenuOptions($transaction_id) {
		$result = array();

		if ( ! empty($transaction_id)) {
			$this->db->from('transaction_options');
			$this->db->where('transaction_id', $transaction_id);

			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					$result[] = $row;
				}
			}
		}

		return $result;
	}

	public function getTransactionTotals($transaction_id) {
		$this->db->from('transaction_totals');
		$this->db->order_by('priority', 'ASC');
		$this->db->where('transaction_id', $transaction_id);

		$query = $this->db->get();
		$result = array();

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
		}

		return $result;
	}

	public function getTransactionCoupon($transaction_id) {
		$this->db->from('coupons_history');
		$this->db->where('transaction_id', $transaction_id);

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row_array();
		}
	}

	public function getTransactionDates() {
		$this->db->select('date_added, MONTH(date_added) as month, YEAR(date_added) as year');
		$this->db->from('transactions');
		$this->db->group_by('MONTH(date_added)');
		$this->db->group_by('YEAR(date_added)');
		$query = $this->db->get();
		$result = array();

		if ($query->num_rows() > 0) {
			$result = $query->result_array();
		}

		return $result;
	}

	public function updateTransaction($transaction_id = NULL, $update = array()) {
		$query = FALSE;

		if (isset($update['transaction_status'])) {
			$this->db->set('status_id', $update['transaction_status']);
		}

		if (isset($update['assignee_id'])) {
			$this->db->set('assignee_id', $update['assignee_id']);
		}

		if (isset($update['notify'])) {
			$this->db->set('notify', $update['notify']);
		}

		$this->db->set('date_modified', mdate('%Y-%m-%d', time()));

		if (is_numeric($transaction_id)) {
			$this->db->where('transaction_id', $transaction_id);
			$query = $this->db->update('transactions');

			if ($query === TRUE) {
				$this->load->model('Statuses_model');
				$status = $this->Statuses_model->getStatus($update['transaction_status']);

				if (isset($update['status_notify']) AND $update['status_notify'] === '1') {
					$mail_data = $this->getMailData($transaction_id);

					$mail_data['status_name'] = $status['status_name'];
					$mail_data['status_comment'] = ! empty($update['status_comment']) ? $update['status_comment'] : $this->lang->line('text_no_comment');

					$this->load->model('Mail_templates_model');
					$mail_template = $this->Mail_templates_model->getTemplateData($this->config->item('mail_template_id'), 'transaction_update');
					$update['status_notify'] = $this->sendMail($mail_data['email'], $mail_template, $mail_data);
				}

				if ((int) $update['old_status_id'] !== (int) $update['transaction_status']) {
					if (APPDIR === ADMINDIR) {
						$update['staff_id'] = $this->user->getStaffId();
					}

					$status_update['object_id']    = (int) $transaction_id;
					$status_update['status_id']    = (int) $update['transaction_status'];
					$status_update['comment']      = isset($update['status_comment']) ? $update['status_comment'] : $status['status_comment'];
					$status_update['notify']       = isset($update['status_notify']) ? $update['status_notify'] : $status['notify_customer'];
					$status_update['date_added']   = mdate('%Y-%m-%d %H:%i:%s', time());

					$this->Statuses_model->addStatusHistory('transaction', $status_update);
				}

				if ($this->config->item('auto_invoicing') === '1' AND in_array($update['transaction_status'], (array) $this->config->item('completed_transaction_status'))) {
					$this->createInvoiceNo($transaction_id);
				}

				if (in_array($update['transaction_status'], (array) $this->config->item('processing_transaction_status'))) {
					$this->subtractStock($transaction_id);

					$this->load->model('Coupons_model');
					$this->Coupons_model->redeemCoupon($transaction_id);
				}
			}
		}

		return $query;
	}

	public function createInvoiceNo($transaction_id = NULL) {

		$transaction_status_exists = $this->Statuses_model->statusExists('transaction', $transaction_id, $this->config->item('completed_transaction_status'));
		if ($transaction_status_exists !== TRUE) return TRUE;

		$transaction_info = $this->getTransaction($transaction_id);

		if ($transaction_info AND empty($transaction_info['invoice_no'])) {
			$transaction_info['invoice_prefix'] = str_replace('{year}', date('Y'), str_replace('{month}', date('m'), str_replace('{day}', date('d'), $this->config->item('invoice_prefix'))));

			$this->db->select_max('invoice_no');
			$this->db->from('transactions');
			$this->db->where('invoice_prefix', $transaction_info['invoice_prefix']);
			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				$row = $query->row_array();
				$invoice_no = $row['invoice_no'] + 1;
			} else {
				$invoice_no = 1;
			}

			$this->db->set('invoice_prefix', $transaction_info['invoice_prefix']);
			$this->db->set('invoice_no', $invoice_no);
			$this->db->set('invoice_date', mdate('%Y-%m-%d %H:%i:%s', time()));
			$this->db->where('transaction_id', $transaction_id);
			$this->db->update('transactions');

			return $transaction_info['invoice_prefix'].$invoice_no;
		}

		return FALSE;
	}

	public function addTransaction($transaction_info = array(), $cart_contents = array()) {
		if (empty($transaction_info) OR empty($cart_contents)) return FALSE;

		if (isset($transaction_info['location_id'])) {
			$this->db->set('location_id', $transaction_info['location_id']);
		}

		if (isset($transaction_info['customer_id'])) {
			$this->db->set('customer_id', $transaction_info['customer_id']);
		} else {
			$this->db->set('customer_id', '0');
		}

		if (isset($transaction_info['first_name'])) {
			$this->db->set('first_name', $transaction_info['first_name']);
		}

		if (isset($transaction_info['last_name'])) {
			$this->db->set('last_name', $transaction_info['last_name']);
		}

		if (isset($transaction_info['email'])) {
			$this->db->set('email', $transaction_info['email']);
		}

		if (isset($transaction_info['telephone'])) {
			$this->db->set('telephone', $transaction_info['telephone']);
		}

		if (isset($transaction_info['transaction_type'])) {
			$this->db->set('transaction_type', $transaction_info['transaction_type']);
		}

		if (isset($transaction_info['transaction_time'])) {
			$current_time = time();
			$transaction_time = (strtotime($transaction_info['transaction_time']) < strtotime($current_time)) ? $current_time : $transaction_info['transaction_time'];
			$this->db->set('transaction_time', mdate('%H:%i', strtotime($transaction_time)));
			$this->db->set('date_added', mdate('%Y-%m-%d %H:%i:%s', $current_time));
			$this->db->set('date_modified', mdate('%Y-%m-%d', $current_time));
			$this->db->set('ip_address', $this->input->ip_address());
			$this->db->set('user_agent', $this->input->user_agent());
		}

		if (isset($transaction_info['address_id'])) {
			$this->db->set('address_id', $transaction_info['address_id']);
		}

		if (isset($transaction_info['payment'])) {
			$this->db->set('payment', $transaction_info['payment']);
		}

		if (isset($transaction_info['comment'])) {
			$this->db->set('comment', $transaction_info['comment']);
		}

		if (isset($cart_contents['transaction_total'])) {
			$this->db->set('transaction_total', $cart_contents['transaction_total']);
		}

		if (isset($cart_contents['total_items'])) {
			$this->db->set('total_items', $cart_contents['total_items']);
		}

		if ( ! empty($transaction_info)) {
			if (isset($transaction_info['transaction_id'])) {
				$_action = 'updated';
				$this->db->where('transaction_id', $transaction_info['transaction_id']);
				$query = $this->db->update('transactions');
				$transaction_id = $transaction_info['transaction_id'];
			} else {
				$_action = 'added';
				$query = $this->db->insert('transactions');
				$transaction_id = $this->db->insert_id();
			}

			if ($query AND $transaction_id) {
				if (isset($transaction_info['address_id'])) {
					$this->load->model('Addresses_model');
					$this->Addresses_model->updateDefault($transaction_info['customer_id'], $transaction_info['address_id']);
				}

				$this->addTransactionMenus($transaction_id, $cart_contents);

				$transaction_totals = array(
					'cart_total'  => array('title' => 'Sub Total', 'value' => (isset($cart_contents['cart_total'])) ? $cart_contents['cart_total'] : '', 'priority' => '1'),
					'transaction_total' => array('title' => 'Total', 'value' => (isset($cart_contents['transaction_total'])) ? $cart_contents['transaction_total'] : '', 'priority' => '5'),
					'delivery'    => array('title' => 'Envio', 'value' => (isset($cart_contents['delivery'])) ? $cart_contents['delivery'] : '', 'priority' => '3'),
					'coupon'      => array('title' => 'Cupon (' . $cart_contents['coupon']['code'] . ') ', 'value' => $cart_contents['coupon']['discount'], 'priority' => '2'),
				);

				if (!empty($cart_contents['taxes'])) {
					$transaction_totals['taxes'] = array('title' => $cart_contents['taxes']['title'], 'value' => $cart_contents['taxes']['amount'], 'priority' => '4');
				}

				$this->addTransactionTotals($transaction_id, $transaction_totals);

				if ( ! empty($cart_contents['coupon'])) {
					$this->addTransactionCoupon($transaction_id, $transaction_info['customer_id'], $cart_contents['coupon']);
				}

				return $transaction_id;
			}
		}
	}

	public function completeTransaction($transaction_id, $transaction_info, $cart_contents = array()) {

		if ($transaction_id AND ! empty($transaction_info)) {

			$notify = $this->sendConfirmationMail($transaction_id);

			$update = array(
				'old_status_id' => '',
				'transaction_status'  => ! empty($transaction_info['status_id']) ? (int) $transaction_info['status_id'] : (int) $this->config->item('default_transaction_status'),
				'notify'        => $notify,
			);

			if ($this->updateTransaction($transaction_id, $update)) {
				if (APPDIR === MAINDIR) {
					log_activity($transaction_info['customer_id'], 'created', 'transactions', get_activity_message('activity_created_transaction',
						array('{customer}', '{link}', '{transaction_id}'),
						array($transaction_info['first_name'] . ' ' . $transaction_info['last_name'], admin_url('transactions/edit?id=' . $transaction_id), $transaction_id)
					));
				}

				Events::trigger('after_create_transaction', array('transaction_id' => $transaction_id));

				return TRUE;
			}
		}
	}

	public function addTransactionMenus($transaction_id, $cart_contents = array()) {
		if (is_array($cart_contents) AND ! empty($cart_contents) AND $transaction_id) {
			$this->db->where('transaction_id', $transaction_id);
			$this->db->delete('transaction_menus');

			foreach ($cart_contents as $key => $item) {
				if (is_array($item) AND isset($item['rowid']) AND $key === $item['rowid']) {

					if (isset($item['id'])) {
						$this->db->set('menu_id', $item['id']);
					}

					if (isset($item['name'])) {
						$this->db->set('name', $item['name']);
					}

					if (isset($item['qty'])) {
						$this->db->set('quantity', $item['qty']);
					}

					if (isset($item['price'])) {
						$this->db->set('price', $item['price']);
					}

					if (isset($item['subtotal'])) {
						$this->db->set('subtotal', $item['subtotal']);
					}

					if (isset($item['comment'])) {
						$this->db->set('comment', $item['comment']);
					}

					if ( ! empty($item['options'])) {
						$this->db->set('option_values', serialize($item['options']));
					}

					$this->db->set('transaction_id', $transaction_id);

					if ($query = $this->db->insert('transaction_menus')) {
						$transaction_menu_id = $this->db->insert_id();

						if ( ! empty($item['options'])) {
							$this->addTransactionMenuOptions($transaction_menu_id, $transaction_id, $item['id'], $item['options']);
						}
					}
				}
			}

			return TRUE;
		}
	}

	public function addTransactionMenuOptions($transaction_menu_id, $transaction_id, $menu_id, $menu_options) {
		if ( ! empty($transaction_id) AND ! empty($menu_id) AND ! empty($menu_options)) {
			$this->db->where('transaction_menu_id', $transaction_menu_id);
			$this->db->where('transaction_id', $transaction_id);
			$this->db->where('menu_id', $menu_id);
			$this->db->delete('transaction_options');

			foreach ($menu_options as $menu_option_id => $options) {
				foreach ($options as $option) {
					$this->db->set('transaction_menu_option_id', $menu_option_id);
					$this->db->set('transaction_menu_id', $transaction_menu_id);
					$this->db->set('transaction_id', $transaction_id);
					$this->db->set('menu_id', $menu_id);
					$this->db->set('menu_option_value_id', $option['value_id']);
					$this->db->set('transaction_option_name', $option['value_name']);
					$this->db->set('transaction_option_price', $option['value_price']);

					$this->db->insert('transaction_options');
				}
			}
		}
	}

	public function addTransactionTotals($transaction_id, $transaction_totals) {
		if (is_numeric($transaction_id) AND ! empty($transaction_totals)) {
			$this->db->where('transaction_id', $transaction_id);
			$this->db->delete('transaction_totals');

			foreach ($transaction_totals as $key => $value) {
				if (is_numeric($value['value'])) {
					$this->db->set('transaction_id', $transaction_id);
					$this->db->set('code', $key);
					$this->db->set('title', $value['title']);
					$this->db->set('priority', $value['priority']);

					if ($key === 'coupon') {
						$this->db->set('value', '-' . $value['value']);
					} else {
						$this->db->set('value', $value['value']);
					}

					$this->db->insert('transaction_totals');
				}
			}

			return TRUE;
		}
	}

	public function addTransactionCoupon($transaction_id, $customer_id, $coupon) {
		if (is_array($coupon) AND is_numeric($coupon['discount'])) {
			$this->db->where('transaction_id', $transaction_id);
			$this->db->delete('coupons_history');

			$this->load->model('Coupons_model');
			$temp_coupon = $this->Coupons_model->getCouponByCode($coupon['code']);

			$this->db->set('transaction_id', $transaction_id);
			$this->db->set('customer_id', empty($customer_id) ? '0' : $customer_id);
			$this->db->set('coupon_id', $temp_coupon['coupon_id']);
			$this->db->set('code', $temp_coupon['code']);
			$this->db->set('amount', '-' . $coupon['discount']);
			$this->db->set('date_used', mdate('%Y-%m-%d %H:%i:%s', time()));

			if ($this->db->insert('coupons_history')) {
				return $this->db->insert_id();
			}
		}
	}

	public function subtractStock($transaction_id) {
		$this->load->model('Menus_model');

		$transaction_menus = $this->getTransactionMenus($transaction_id);

		foreach ($transaction_menus as $transaction_menu) {
			$this->Menus_model->updateStock($transaction_menu['menu_id'], $transaction_menu['quantity'], 'subtract');
		}
	}

	public function sendConfirmationMail($transaction_id) {
		$this->load->model('Mail_templates_model');

		$mail_data = $this->getMailData($transaction_id);
		$config_transaction_email = is_array($this->config->item('transaction_email')) ? $this->config->item('transaction_email') : array();

		$notify = '0';
		if ($this->config->item('customer_transaction_email') === '1' OR in_array('customer', $config_transaction_email)) {
			$mail_template = $this->Mail_templates_model->getTemplateData($this->config->item('mail_template_id'), 'transaction');
			$notify = $this->sendMail($mail_data['email'], $mail_template, $mail_data);
		}

		if ($this->location->getEmail() AND ($this->config->item('location_transaction_email') === '1' OR in_array('location', $config_transaction_email))) {
			$mail_template = $this->Mail_templates_model->getTemplateData($this->config->item('mail_template_id'), 'transaction_alert');
			$this->sendMail($this->location->getEmail(), $mail_template, $mail_data);
		}

		if (in_array('admin', $config_transaction_email)) {
			$mail_template = $this->Mail_templates_model->getTemplateData($this->config->item('mail_template_id'), 'transaction_alert');
			$this->sendMail($this->config->item('site_email'), $mail_template, $mail_data);
		}

		return $notify;
	}

	public function getMailData($transaction_id) {
		$data = array();

		$result = $this->getTransaction($transaction_id);
		if ($result) {
			$this->load->library('country');
			$this->load->library('currency');

			$data['transaction_number'] = $result['transaction_id'];
			$data['transaction_view_url'] = root_url('account/transactions/view/' . $result['transaction_id']);
			$data['transaction_type'] = ($result['transaction_type'] === '1') ? 'delivery' : 'collection';
			$data['transaction_time'] = mdate('%H:%i', strtotime($result['transaction_time']));
			$data['transaction_date'] = mdate('%d %M %y', strtotime($result['date_added']));
			$data['first_name'] = $result['first_name'];
			$data['last_name'] = $result['last_name'];
			$data['email'] = $result['email'];
			$data['telephone'] = $result['telephone'];
			$data['transaction_comment'] = $result['comment'];

			if ($payment = $this->extension->getPayment($result['payment'])) {
				$data['transaction_payment'] = !empty($payment['ext_data']['title']) ? $payment['ext_data']['title']: $payment['title'];
			} else {
				$data['transaction_payment'] = $this->lang->line('text_no_payment');
			}

			$data['transaction_menus'] = array();
			$menus = $this->getTransactionMenus($result['transaction_id']);
			$options = $this->getTransactionMenuOptions($result['transaction_id']);
			if ($menus) {
				foreach ($menus as $menu) {
					$option_data = array();

					if (!empty($options)) {
						foreach ($options as $key => $option) {
							if ($menu['transaction_menu_id'] === $option['transaction_menu_id']) {
								$option_data[] = $option['transaction_option_name'] . $this->lang->line('text_equals') . $this->currency->format($option['transaction_option_price']);
							}
						}
					}

					$data['transaction_menus'][] = array(
						'menu_name'     => (strlen($menu['name']) > 20) ? substr($menu['name'], 0, 20) . '...' : $menu['name'],
						'menu_quantity' => $menu['quantity'],
						'menu_price'    => $this->currency->format($menu['price']),
						'menu_subtotal' => $this->currency->format($menu['subtotal']),
						'menu_options'  => implode(', ', $option_data),
					);
				}
			}

			$transaction_totals = $this->getTransactionTotals($result['transaction_id']);
			if ($transaction_totals) {
				$totals = array('cart_total' => '1', 'coupon' => '2', 'delivery' => '3', 'taxes' => '4', 'transaction_total' => '5');

				$data['transaction_totals'] = array();
				foreach ($transaction_totals as $total) {

					$priority = isset($totals[$total['code']]) ? $totals[$total['code']] : '0';

					$data['transaction_totals'][] = array(
						'transaction_total_title' => $total['title'],
						'transaction_total_value' => $this->currency->format($total['value']),
						'priority' => empty($total['priority']) ? $priority : $total['priority'],
					);
				}

				$data['transaction_totals'] = sort_array($data['transaction_totals'], 'priority');
			}

			$data['transaction_address'] = $this->lang->line('text_collection_transaction_type');
			if ( ! empty($result['address_id'])) {
				$this->load->model('Addresses_model');
				$transaction_address = $this->Addresses_model->getAddress($result['customer_id'], $result['address_id']);
				$data['transaction_address'] = $this->country->addressFormat($transaction_address);
			}

			if ( ! empty($result['location_id'])) {
				$this->load->model('Locations_model');
				$location = $this->Locations_model->getLocation($result['location_id']);
				$data['location_name'] = $location['location_name'];
			}
		}

		return $data;
	}

	public function sendMail($email, $mail_template = array(), $mail_data = array()) {
		if (empty($mail_template) OR !isset($mail_template['subject'], $mail_template['body']) OR empty($mail_data)) {
			return FALSE;
		}

		$this->load->library('email');

		$this->email->initialize();

		if (!empty($mail_data['status_comment'])) {
			$mail_data['status_comment'] = $this->email->parse_template($mail_data['status_comment'], $mail_data);
		}

		$this->email->from($this->config->item('site_email'), $this->config->item('site_name'));
		$this->email->to(strtolower($email));
		$this->email->subject($mail_template['subject'], $mail_data);
		$this->email->message($mail_template['body'], $mail_data);

		if ( ! $this->email->send()) {
			log_message('error', $this->email->print_debugger(array('headers')));
			$notify = '0';
		} else {
			$notify = '1';
		}

		return $notify;
	}

	public function validateTransaction($transaction_id) {
		if ( ! empty($transaction_id)) {
			$this->db->from('transactions');
			$this->db->where('transaction_id', $transaction_id);

			$query = $this->db->get();

			if ($query->num_rows() > 0) {
				return TRUE;
			}
		}

		return FALSE;
	}

	public function deleteTransaction($transaction_id) {
		if (is_numeric($transaction_id)) $transaction_id = array($transaction_id);

		if ( ! empty($transaction_id) AND ctype_digit(implode('', $transaction_id))) {
			$this->db->where_in('transaction_id', $transaction_id);
			$this->db->delete('transactions');

			if (($affected_rows = $this->db->affected_rows()) > 0) {
				$this->db->where_in('transaction_id', $transaction_id);
				$this->db->delete('transaction_menus');

				$this->db->where_in('transaction_id', $transaction_id);
				$this->db->delete('transaction_options');

				$this->db->where_in('transaction_id', $transaction_id);
				$this->db->delete('transaction_totals');

				$this->db->where_in('transaction_id', $transaction_id);
				$this->db->delete('coupons_history');

				return $affected_rows;
			}
		}
	}
}

/* End of file transactions_model.php */
/* Location: ./system/tastyigniter/models/transactions_model.php */
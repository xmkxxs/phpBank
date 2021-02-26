<?php defined('BASEPATH') or exit ('No direct script access allowed');

	class User_model extends CI_Model{

		public function __construct()
		{
			parent::__construct();
			//连接数据库
			$this->load->database();
			//加载Login_model
			$this->load->model('Login_model');
		}

		//根据用户名获取用户所有信息
		public function get_user_info_by_account($account){
			$this->db->where('account',$account);
			$user_info = $this->db->get('tb_user');
			return $user_info;
		}

		//根据账号查询用户余额
		public function get_balance_by_account($account){
			$this->db->where('account',$account);
			$this->db->select('balance');
			$balance = $this->db->get('tb_user');
			return $balance;
		}

		//根据账号存钱
		public function save_money_by_account($account,$money){
			/*先查询当前账户余额*/
			$query = $this->get_balance_by_account($account);
			$balance='';
			foreach ($query->result() as $row){
				$balance = $row->balance;
			}
			/*将要存入的钱与账户余额相加*/
			$result = (double)$money + (double)$balance;
			/*将价格存入用户账户*/
			$this->db->set('balance', $result);
			$this->db->where('account', $account);
			$this->db->update('tb_user');
		}

		//根据账号取钱
		public function get_money_by_account($account,$money){
			/*首先查询账户余额*/
			$query = $this->get_balance_by_account($account);
			$balance='';
			foreach ($query->result() as $row){
				(double)$balance = $row->balance;
			}
			if($balance == 0){
				/*如果账户就剩0元了*/
				return 'zero';
			}elseif ($balance < (double)$money){
				/*如果取出额大于余额*/
				return 'less';
			}else {
				/*可以取出*/
				$result = (double)$balance - (double)$money;
				/*将取款后的额度存放数据库*/
				$this->db->set('balance', $result);
				$this->db->where('account', $account);
				$this->db->update('tb_user');
				return 'success';
			}
		}

		//根据账号转账
		public function transfer_by_account($account1,$account2,$money){
			/*首先查询账户余额*/
			$query = $this->get_balance_by_account($account1);
			$balance='';
			foreach ($query->result() as $row){
				(double)$balance = $row->balance;
			}
			if($balance == 0){
				/*如果账户就剩0元了*/
				return 'zero';
			}elseif ($balance < (double)$money){
				/*如果转出额大于余额*/
				return 'less';
			}else {
				/*判断转入账户是否存在*/
				$does_the_account_exist = $this->Login_model->exist_account($account2);
				if($does_the_account_exist == 1){
					/*可以转出*/
					$result1 = (double)$balance - (double)$money;
					/*更新自己账户余额*/
					$this->db->set('balance', $result1);
					$this->db->where('account', $account1);
					$this->db->update('tb_user');
					/*获取转入账户的余额*/
					$query2 = $this->get_balance_by_account($account2);
					$balance2='';
					foreach ($query2->result() as $row2){
						(double)$balance2 = $row2->balance;
					}
					/*更新转入账户的余额*/
					$result2 = (double)$balance2 + (double)$money;
					$this->db->set('balance', $result2);
					$this->db->where('account', $account2);
					$this->db->update('tb_user');
					return 'success';
				}else{
					return 'noexist';
				}
			}
		}

		/*注销*/
		public function destruction_by_account($account){
			$this->db->where('account', $account);
			$this->db->delete('tb_user');
		}

	}

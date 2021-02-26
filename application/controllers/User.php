<?php defined('BASEPATH') or exit ('No direct script access allowed');//防止直接访问此文件

	class User extends CI_Controller{

		public function __construct()
		{
			parent::__construct();
			$this->load->helper('url');
			//加载session类
			$this->load->library('session');
			// -----------------------------------------------------------
			## 判断session是否存在key='account'，不存在代表没登陆
			$session_account=$this->session->userdata('account');
			if ($session_account=="" || $session_account==NULL){
				## 直接调用Home.php的index方法返回主页
				redirect('home');
			}
			// -----------------------------------------------------------
			// 加载模型
			$this->load->model('User_model');
			//加载表单验证类
			$this->load->library('form_validation');
		}

		//进入用户页面
		public function index(){
			$this->load->view("user/user.html");
		}

		//查询用户全部信息，在user.html进行部分用户信息展示
		public function get_user_info(){
			//获取session中的用户账户
			$session_account=$this->session->userdata('account');
			//根据账号获取用户信息
			$user_info = $this->User_model->get_user_info_by_account($session_account);

			/*	## 测试是否可以输入
			|	foreach ($user_info->result() as $row){
			|	echo $row -> account . "</br>";
			|	echo $row -> name . "</br>";
			|	echo $row -> password . "</br>";
			|	echo $row -> mobile . "</br>";
			|	echo $row -> email . "</br>";
			|	echo $row -> balance . "</br>";
			|	echo $row -> question . "</br>";
			|	echo $row -> answer . "</br>";
			|	}
			*/
			// 返回数据给ajax用echo,先将数组转换成关联数组，在变成对象json字符串发送。
			echo  json_encode($user_info->result());
		}

		//用户退出
		public function login_out(){
			$this->session->unset_userdata('account');
			//直接调用Home.php的index方法
			redirect('home');
		}

		/*来到存钱页面*/
		public function save(){
			$this->load->view('business/recharge/recharge.html');
		}

		/*来到取钱页面*/
		public function get(){
			$this->load->view('business/getMoney/getMoney.html');
		}

		/*来到转账页面*/
		public function transfer(){
			$account['account']=$this->session->userdata('account');
			$this->load->view('business/transfer/transfer.html',$account);
		}

		/*查询余额*/
		public function balance(){
			$account = $this->session->userdata('account');
			$query = $this->User_model->get_balance_by_account($account);
			$balance='';
			foreach ($query->result() as $row){
				$balance = $row->balance;
			}
			echo $balance;
		}

		/*来到销户页面*/
		public function destruction(){
			$this->load->view('business/destruction/destruction.html');
		}

		/*存钱业务*/
		public function save_bsn(){
			/*验证用户输入的存钱金额是否符合要求*/
			$this->form_validation->set_rules('money','金额','required|numeric|greater_than[0]');
			if($this->form_validation->run() == false){
				$info['info']= validation_errors();
				$this->load->view('business/recharge/recharge.html',$info);
			}else{
				/*判断用户输入不能以0开头*/
				$money = $this->input->post('money');
				$first_num=substr($money,0,1);
				if($first_num == 0){
					$info['info']= '不能以0开头';
					$this->load->view('business/recharge/recharge.html',$info);
				}
				/*从session中获取账号*/
				$account=$this->session->userdata('account');
				/*调入数据库，将金额存放入数据库*/
				$this->User_model->save_money_by_account($account,$money);
				$info['info']= '存钱成功！';
				$this->load->view('business/recharge/recharge.html',$info);
			}
		}

		/*取钱业务*/
		public function get_bsn(){
			/*验证用户输入的取钱金额是否符合要求*/
			$this->form_validation->set_rules('number','金额','required|numeric|greater_than[0]');
			if($this->form_validation->run() == false){
				$info['info']= validation_errors();
				$this->load->view('business/getMoney/getMoney.html',$info);
			}else{
				/*判断用户输入不能以0开头*/
				$money = $this->input->post('number');
				$first_num=substr($money,0,1);
				if($first_num == 0){
					$info['info']= '不能以0开头';
					$this->load->view('business/getMoney/getMoney.html',$info);
				}
				/*从session中获取账号*/
				$account=$this->session->userdata('account');
				/*调入数据库，将余额减去取值额，model首先检查余额是否还有钱，取钱是否大于余额*/
				$result = $this->User_model->get_money_by_account($account,$money);
				/*如果$result为zero，代表账户余额还有0（不能取），less代表余额小于要取出额，success代表取出成功*/
				if($result == 'zero'){
					$info['info']= '您的余额为0，无法取出！';
					$this->load->view('business/getMoney/getMoney.html',$info);
				}elseif ($result == 'less'){
					$info['info']= '您的余额小于您要取出的额度！';
					$this->load->view('business/getMoney/getMoney.html',$info);
				}else{
					$info['info']= '取款成功！';
					$this->load->view('business/getMoney/getMoney.html',$info);
				}
			}
		}

		/*转账业务*/
		public function transfer_bsn(){
			$account = $this->session->userdata('account');
			/*验证收款账户和转账金额是否符合格式要求*/
			$this->form_validation->set_rules('account2','收款账户','required|numeric|integer|exact_length[15]');
			$this->form_validation->set_rules('number','金额','required|numeric|greater_than[0]');
			if($this->form_validation->run() == false){
				$data = array(
					'account' => $account,
					'info' => validation_errors()
				);
				$this->load->view('business/transfer/transfer.html',$data);
			}else{
				/*转账金额不能0开头*/
				$money = $this->input->post('number');
				$first_num=substr($money,0,1);
				if($first_num == 0){
					$data = array(
						'account' => $this->session->userdata('account'),
						'info' => '金额不能以0开头！'
					);
					$this->load->view('business/transfer/transfer.html',$data);
					return false;
				}
				/*不能给自己转账*/
				$account2 = $this->input->post('account2');
				if($account2 == $account){
					$data = array(
						'account' => $this->session->userdata('account'),
						'info' => '不能给自己转账！'
					);
					$this->load->view('business/transfer/transfer.html',$data);
					return false;
				}
				/*调取model转账，转账会判断转账金额是否大于余额，且余额是否为0，以及转出账户是否存在*/
				$result = $this->User_model->transfer_by_account($account,$account2,$money);
				if($result == 'zero'){
					$data = array(
						'account' => $this->session->userdata('account'),
						'info' => '您的账户余额为0，无法转账！'
					);
					$this->load->view('business/transfer/transfer.html',$data);
				}elseif ($result == 'less'){
					$data = array(
						'account' => $this->session->userdata('account'),
						'info' => '转账金额大于您的余额！'
					);
					$this->load->view('business/transfer/transfer.html',$data);
				}elseif ($result == 'noexist'){
					$data = array(
						'account' => $this->session->userdata('account'),
						'info' => '您要转账的账户不存在！'
					);
					$this->load->view('business/transfer/transfer.html',$data);
				}elseif ($result == 'success'){
					$data = array(
						'account' => $this->session->userdata('account'),
						'info' => '转账成功！'
					);
					$this->load->view('business/transfer/transfer.html',$data);
				}
			}
		}

		/*销户*/
		public function destruction_bsn(){
			$result = $this->input->post('a');
			if($result == '确定'){
				/*查询账户是否还有余额*/
				$account = $this->session->userdata('account');
				$query = $this->User_model->get_balance_by_account($account);
				$balance = '';
				foreach ($query->result() as $row){
					$balance = $row->balance;
				}
				if((double)$balance > 0){
					$info['info'] = '账户尚存余额，无法销户！';
					$this->load->view('business/destruction/destruction.html',$info);
				}else{
					/*根据账户删除数据库信息*/
					$this->User_model->destruction_by_account($account);
					/*销毁session*/
					$this->session->sess_destroy();
					$is['is'] = '谢谢使用，我们有缘再见！';
					$this->load->view('business/destruction/destruction.html',$is);
				}
			}else{
				$back['back'] = '不注销，回到首页';
				$this->load->view('business/destruction/destruction.html',$back);
			}
		}

	}

<?php defined('BASEPATH') or exit ('No direct script access allowed');//防止直接访问此文件

	class Password_reset extends CI_Controller{

		public function __construct()
		{
			parent::__construct();
			/*加载路径辅助函数*/
			$this->load->helper('url');
			/*加载表单验证类*/
			$this->load->library('form_validation');
			// 加载模型
			$this->load->model('Login_model');
			$this->load->model('PasswordReset_model');
		}

		public function index(){
			$this->load->view('findpasswd/findpasswd1.html');
		}

		/*找回密码前验证账户格式及是否账户存在*/
		public function check(){
			/*获取三个找回密码页面隐藏域的值，用以执行不同的业务*/
			$hd = $this->input->post('hd');
			/*如果请求来自找回密码的第一个页面*/
			if($hd == '111111111111111'){
				/*验证账户是否符合条件*/
				$this->form_validation->set_rules('account','账号','required|integer|exact_length[15]');
				if($this->form_validation->run() == false){
					//如果验证不通过，报错提示信息
					$error_info['error_info'] = validation_errors();
					$this->load->view("findpasswd/findpasswd1.html",$error_info);
				}else{
					/*获取用户卡号*/
					$account = $this->input->post('account');
					/*判断银行卡号是否存在，调用Login_model中,存在则返回1行，不存在返回0*/
					$does_the_account_exist = $this->Login_model->exist_account($account);
					if ($does_the_account_exist == 1){
						$ac['account'] = $account;
						$this->load->view("findpasswd/findpasswd2.html",$ac);
					}else{
						$error_info['error_info'] = '账户不存在！';
						$this->load->view("findpasswd/findpasswd1.html",$error_info);
					}
				}
			}elseif ($hd == '222222222222222'){/*如果请求来自找回密码的第er个页面*/
				/*获取页面hidden保存的账号，如果用户输入不满足要求，继续回到findpasswd2页面还需要用到account，页面发送ajax需要参数account*/
				$ac = $this->input->post('ac');
				/*验证用户输入的密保答案*/
				$this->form_validation->set_rules('answer','您的密保答案','required');
				if($this->form_validation->run() == false){
					//如果验证不通过，报错提示信息，并将账号继续传给页面
					$data = array(
						'account' => $ac,
						'error_info' => validation_errors()
					);
					$this->load->view("findpasswd/findpasswd2.html",$data);
				}else{
					/*获取用户输入的密保答案*/
					$answer = $this->input->post('answer');
					/*查询此账户密保答案与用户输入的答案进行对比*/
					$answer_by_db = $this->PasswordReset_model->get_answer_by_account_model($ac);
					if ($answer == $answer_by_db){
						$account['account'] = $ac;
						$this->load->view("findpasswd/findpasswd3.html",$account);
					}else{
						$data = array(
							'account' => $ac,
							'error_info' => '密保问题回答错误！'
						);
						$this->load->view("findpasswd/findpasswd2.html",$data);
					}
				}
			}elseif ($hd == '333333333333333'){/*如果请求来自找回密码的第san个页面*/
				$ac = $this->input->post('ac');
				/*验证用户输入是否符合规则*/
				$this->form_validation->set_rules('password1','新密码','required');
				$this->form_validation->set_rules('password2','确认密码','required');
				if($this->form_validation->run() == false){
					$data = array(
						'account' => $ac,
						'error_info' => validation_errors()
					);
					$this->load->view("findpasswd/findpasswd3.html",$data);
				}else{
					/*后端验证密码是否一致*/
					$password1 = $this->input->post('password1');
					$password2 = $this->input->post('password2');
					if($password1 == $password2){
						/*根据账号来更新密码*/
						$this->PasswordReset_model->updata_pwd_by_account($ac,$password1);
						$data = array(
							'account' => $ac,
							'error_info' => '更新密码成功！'
						);
						$this->load->view("findpasswd/findpasswd3.html",$data);
					}else{
						$data = array(
							'account' => $ac,
							'error_info' => '两个密码不一致'
						);
						$this->load->view("findpasswd/findpasswd3.html",$data);
					}
				}
			}

		}

		/*根据用户名获取用户密保问题*/
		public function get_question_by_account($account){
			$question = $this->PasswordReset_model->get_question_by_account_model($account);
			echo $question;
		}

	}

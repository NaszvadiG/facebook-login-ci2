<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function index()
	{
		
		if($this->session->userdata('login') == true){
			redirect('welcome/profile');
		}

		if(isset($_GET['state'])){

			$user = $this->facebook->getUser();

			if($user){
				try{
					$user_profile = $this->facebook->api('/me?fields=email,first_name,last_name,gender,name,birthday,location');
				} catch(FacebookApiException $e){
					$user = null;
				}
			} else {
				$this->facebook->destroySession();
			}

			$this->session->set_userdata('login',true);
			$this->session->set_userdata('user_profile',$user_profile);
			redirect('welcome/profile');
			
			
		} else {
	

			$contents['link'] = $this->facebook->getLoginUrl(array(
				'redirect_url' => site_url('index.php/home/index'),
				'scope' => array("email,user_birthday,user_location,public_profile")
			));
		
			$this->load->view('welcome_message',$contents);
		
	
		}

	}
	
	public function profile(){
		if($this->session->userdata('login') != true){
			redirect('');
		}

		$contents['user_profile'] = $this->session->userdata('user_profile');
		$this->load->view('profile',$contents);
		
	}

	
	public function logout(){
		$this->session->sess_destroy();
		redirect('');
		
	}
	
}

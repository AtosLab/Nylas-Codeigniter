<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'vendor/nylas-php/src/Nylas.php';

class nylas_mail extends CI_Controller {

	protected $app_id = '29ls5zw0xjckd97d3umfnrt43';
	protected $app_secret = '93dx1sybvjt9ott2pcjnzpz9l';
	function __construct() {
        parent::__construct();

        $this->load->helper('url');
    }

    public function nylas_mail_start()
    {

		$client = new Nylas($this->app_id, $this->app_secret);
		$redirect_url = 'http://localhost/index.php/nylas_mail/auth_callback';
		$get_auth_url = $client->createAuthURL($redirect_url);

		redirect($get_auth_url);
    }

    public function auth_callback(){
    	$access_code = isset($_GET['code']) ? $_GET['code'] : NULL;
		if( $access_code == NULL )
			return;

		$client = new Nylas($this->app_id, $this->app_secret);
		$get_token = $client->getAuthToken($access_code);
		//var_dump($get_token); exit;
		$this->session->set_userdata('nylas_auth_token', $get_token);

		redirect(site_url().'index.php/nylas_mail');
    }

	public function index($labels = 'inbox', $page_start = 0, $page_end = 50)
	{
		if(!$this->session->userdata('nylas_auth_token')){
			return;
		}

		$get_token = $this->session->userdata('nylas_auth_token');
		
		$client = new Nylas($this->app_id, $this->app_secret, $get_token);
		
		$sub_menu['account'] = $client->account();
		$sub_menu['label_active'] = $labels;

		///Get Message Limit 10
		$msg_where = array();
		if($labels == 'starred')
			$msg_where['starred'] = 'true';
		else
			$msg_where['in'] = $labels;

		$messages = $client->messages()->where($msg_where)->range($page_start, 10);

		//Get Message Count 
		$msg_where['view'] = 'count';
		$count = $client->messages()->where($msg_where)->first()->json();


		$data['messages'] = $messages;

		$data['page_nav'] = array('start'=>$page_start, 'end'=>$page_start + count($data['messages']), 'total'=>$count );
		$data['sub_menu'] = $sub_menu;

		$this->load->view('nylas/header');
		$this->load->view('nylas/menu_top');
		$this->load->view('nylas/menu_left');

		$this->load->view('nylas/menu_sub_left', $sub_menu);
		$this->load->view('nylas/inbox', $data);

		$this->load->view('nylas/menu_right');
		$this->load->view('nylas/footer');
	}

	public function mail_view($labels = 'inbox', $id = '')
	{
		if(!$this->session->userdata('nylas_auth_token')){
			return;
		}
		$get_token = $this->session->userdata('nylas_auth_token');
		
		$client = new Nylas($this->app_id, $this->app_secret, $get_token);
		
		$sub_menu['account'] = $client->account();
		$sub_menu['label_active'] = $labels;

		//set unread message by id;
		$client->messages()->update(array("unread"=>false), $id);
		//get message by id
		$data['msg'] = $client->messages()->find($id)->json();
		$data['id'] = $id;
		
		$this->load->view('nylas/header');
		$this->load->view('nylas/menu_top');
		$this->load->view('nylas/menu_left');

		$this->load->view('nylas/menu_sub_left', $sub_menu);
		$this->load->view('nylas/view', $data);

		$this->load->view('nylas/menu_right');
		$this->load->view('nylas/footer');
	}

	public function set_starred()
	{
		if(!$this->session->userdata('nylas_auth_token')){
			return;
		}

		$get_token = $this->session->userdata('nylas_auth_token');
		$client = new Nylas($this->app_id, $this->app_secret, $get_token);

		//set starred message by id;
		$id = $_POST['msg_id'];
		$set_value = $_POST['set_value'] == "true" ? true : false;
		$client->messages()->update(array("starred"=>$set_value), $id);

		echo json_encode('success');
	}
	//move to any
	private function _updateTags($id, $add = [], $delete = [])
    {
    	if(!$this->session->userdata('nylas_auth_token')){
			return;
		}
		$get_token = $this->session->userdata('nylas_auth_token');
		
		$client = new Nylas($this->app_id, $this->app_secret, $get_token);

        $allLabels = $client->labels()->all();
        $currentLabels = $client->messages()->find($id)->json()['labels'];
        $labels = [];

        foreach($allLabels as $label) {
            if ((!empty($label->name[0]) && in_array($label->name, $add)) ||
                (empty($label->name[0]) && in_array($label->display_name, $add))) 
            {
                array_push($labels, $label->id);
            }
        }
        //var_dump($labels); exit;

        foreach($currentLabels as $index => $label) {
            if ((!empty($label['name']) && in_array($label['name'], $delete)) ||
                (empty($label['name']) && in_array($label['display_name'], $delete))
            ) {
                continue;
            }

            array_push($labels, $label['id']);
        }

        $payload = [
            "label_ids" => $labels
        ];

        $client->messages()->update($payload, $id);
    }
    private function _updateFolder($id, $folder)
    {
    	if(!$this->session->userdata('nylas_auth_token')){
			return;
		}
		$get_token = $this->session->userdata('nylas_auth_token');
		
		$client = new Nylas($this->app_id, $this->app_secret, $get_token);

        $allFolders = $client->folders()->all();
        $folderId = null;

        foreach($allFolders as $currentFolder) {
            if ($currentFolder->name == $folder) {
                $folderId = $currentFolder->id;
                break;
            }
        }
        if (!empty($folderId)) {
            $payload = [
                "folder_id" => $folderId
            ];

            $client->messages()->update($payload, $id);
        }

        return ["success" => false];
    }

	//move trash 
	private function move_any_by_id($id, $from, $to)
	{
		if(!$this->session->userdata('nylas_auth_token')){
			return;
		}
		$get_token = $this->session->userdata('nylas_auth_token');
		
		$client = new Nylas($this->app_id, $this->app_secret, $get_token);
		
		$sub_menu['account'] = $client->account();

		if($sub_menu['account']->provider == 'gmail'){
			$this->_updateTags($id, $to, $from);
		}
		else
		{
			$this->_updateFolder($id, $to);
		}
		

		//redirect(site_url().'index.php/nylas_mail/index');
	}
	public function mail_to_trash_bulk()
	{
		$recipients = $_POST['check_list'];
		$sub_menu = isset($_POST['sub_menu']) ? $_POST['sub_menu'] : 'inbox';
		foreach($recipients as $item){
			$this->move_any_by_id($item, ['inbox'], ['trash']);
		}
		redirect(site_url().'index.php/nylas_mail/index/'.$sub_menu);
	}
	
	public function mail_to_spam_bulk()
	{
		$recipients = $_POST['check_list'];
		$sub_menu = isset($_POST['sub_menu']) ? $_POST['sub_menu'] : 'inbox';
		foreach($recipients as $item){
			$this->move_any_by_id($item, ['inbox'], ['spam']);
		}
		redirect(site_url().'index.php/nylas_mail/index/'.$sub_menu);
	}

	//move inbox
	private function move_inbox_by_id($id)
	{
		if(!$this->session->userdata('nylas_auth_token')){
			return;
		}
		$get_token = $this->session->userdata('nylas_auth_token');
		
		$client = new Nylas($this->app_id, $this->app_secret, $get_token);
		
		$sub_menu['account'] = $client->account();
	
		// //move msg to other folders
		if($sub_menu['account']->provider == "yahoo")
			$client->messages()->update(array("folder_id"=>"c9kjtq81gihlpleqfa3mtdy6z"), $id);
		else if($sub_menu['account']->provider == "eas")
			$client->messages()->update(array("folder_id"=>"grhulm08ekcdhe1wr0yzfw9y"), $id);
		else if($sub_menu['account']->provider == 'gmail')
		{
			$labels = [];
	        array_push($labels, 'iduuqsdvz36zmji6ob6qe5');
	        array_push($labels, 'a3htbw2ueqxjslcdn7rk3ki9w');
	        $payload = [
	            "label_ids" => $labels
	        ];

	        $client->messages()->update($payload, $id);
		}

		//redirect(site_url().'index.php/nylas_mail/index');
	}
	public function mail_to_inbox_bulk()
	{
		$recipients = $_POST['check_list'];
		$sub_menu = isset($_POST['sub_menu']) ? $_POST['sub_menu'] : 'inbox';
		foreach($recipients as $item){
			$this->move_trash_by_id($item);
		}
		redirect(site_url().'index.php/nylas_mail/index/'.$sub_menu);
	}

	public function mail_new()
	{
		if(!$this->session->userdata('nylas_auth_token')){
			return;
		}
		$get_token = $this->session->userdata('nylas_auth_token');
		
		$client = new Nylas($this->app_id, $this->app_secret, $get_token);
		
		$sub_menu['account'] = $client->account();
		$sub_menu['label_active'] = 'inbox';

		$this->load->view('nylas/header');
		$this->load->view('nylas/menu_top');
		$this->load->view('nylas/menu_left');

		$this->load->view('nylas/menu_sub_left', $sub_menu);
		$this->load->view('nylas/new_msg', $sub_menu);

		$this->load->view('nylas/menu_right');
		$this->load->view('nylas/footer');
	}

	public function mail_send()
	{
		$recipients = $_POST['txt_recipients'];
		$subject = $_POST['txt_subject'];
		$txt_body = $_POST['txt_body'];

		if(!$this->session->userdata('nylas_auth_token')){
			return;
		}
		$get_token = $this->session->userdata('nylas_auth_token');
		
		$client = new Nylas($this->app_id, $this->app_secret, $get_token);

		$to_mails = [];
		array_push($to_mails, array("name"=>"", "email"=>$recipients));
		$payload = [
            "body" => $txt_body,
            "subject" => $subject,
            "to" => $to_mails
        ];
		$client->sendMessage($payload);

		redirect(site_url().'index.php/nylas_mail/index');
	}


}

/*  out look folders
array(5) { ["account_id"]=> string(25) "3pgadwm8a2bz9wvsabiu3feda" ["display_name"]=>  "Archive" ["id"]=> string(25) "6f1ayh8vhi98lo29ovldbji5q" ["name"]=> string(7) "archive"   } 
array(5) { ["account_id"]=> string(25) "3pgadwm8a2bz9wvsabiu3feda" ["display_name"]=>  "Conversation History" ["id"]=> string(24) "ligpwbvwarnuf9uo8v8x1qah" ["name"]=> NULL   }
array(5) { ["account_id"]=> string(25) "3pgadwm8a2bz9wvsabiu3feda" ["display_name"]=>  "Deleted Items" ["id"]=> string(25) "3oxn2cuwn4bkh31sooxn8g983" ["name"]=> string(5) "trash"   } 
array(5) { ["account_id"]=> string(25) "3pgadwm8a2bz9wvsabiu3feda" ["display_name"]=>  "Drafts" ["id"]=> string(25) "dz29nic37mvy5ts02j37584rl" ["name"]=> string(6) "drafts"   } 
array(5) { ["account_id"]=> string(25) "3pgadwm8a2bz9wvsabiu3feda" ["display_name"]=>  "Inbox" ["id"]=> string(24) "grhulm08ekcdhe1wr0yzfw9y" ["name"]=> string(5) "inbox"   } 
array(5) { ["account_id"]=> string(25) "3pgadwm8a2bz9wvsabiu3feda" ["display_name"]=>  "Junk Email" ["id"]=> string(25) "erlemeepeq6848x577x4elif9" ["name"]=> string(4) "spam"   } 
array(5) { ["account_id"]=> string(25) "3pgadwm8a2bz9wvsabiu3feda" ["display_name"]=>  "Sent Items" ["id"]=> string(25) "1cs15ltjn9y8acal9gp3hkt6s" ["name"]=> string(4) "sent"   }*/

/* yahoo folders
array(5) { ["account_id"]=> string(24) "e6eq404k19qyeqj4yy0ddjih" ["display_name"]=> string(9) "Bulk Mail" ["id"]=> string(25) "7ul8w364ogc9nhoxe5ivsmahz" ["name"]=> string(4) "spam"   } 
array(5) { ["account_id"]=> string(24) "e6eq404k19qyeqj4yy0ddjih" ["display_name"]=> string(7) "Archive" ["id"]=> string(25) "bb1hq98d826w5zo91xhpe9jbb" ["name"]=> string(7) "archive"   } 
array(5) { ["account_id"]=> string(24) "e6eq404k19qyeqj4yy0ddjih" ["display_name"]=> string(13) "Deleted Items" ["id"]=> string(25) "dtez5jjlu15x3c96ktyhrt39r" ["name"]=> NULL   } 
array(5) { ["account_id"]=> string(24) "e6eq404k19qyeqj4yy0ddjih" ["display_name"]=> string(5) "Draft" ["id"]=> string(25) "ea9srzx45pf3feriomge7ykse" ["name"]=> string(6) "drafts"   } 
array(5) { ["account_id"]=> string(24) "e6eq404k19qyeqj4yy0ddjih" ["display_name"]=> string(5) "Inbox" ["id"]=> string(25) "c9kjtq81gihlpleqfa3mtdy6z" ["name"]=> string(5) "inbox"   } 
array(5) { ["account_id"]=> string(24) "e6eq404k19qyeqj4yy0ddjih" ["display_name"]=> string(4) "Sent" ["id"]=> string(25) "adxujdwp9d6w5tgbsjtlmgvhx" ["name"]=> string(4) "sent"   } 
array(5) { ["account_id"]=> string(24) "e6eq404k19qyeqj4yy0ddjih" ["display_name"]=> string(5) "Trash" ["id"]=> string(25) "egefkd8zkrg325flln1jtyltr" ["name"]=> string(5) "trash"   }*/

/* gmail folders
array(5) { ["account_id"]=> string(25) "2dqxxr0qmlg8cwwxritl6g91b" ["display_name"]=> string(5) "Inbox" ["id"]=> string(25) "a3htbw2ueqxjslcdn7rk3ki9w" ["name"]=> string(5) "inbox"  " } 
array(5) { ["account_id"]=> string(25) "2dqxxr0qmlg8cwwxritl6g91b" ["display_name"]=> string(8) "All Mail" ["id"]=> string(22) "iduuqsdvz36zmji6ob6qe5" ["name"]=> string(3) "all"  " } 
array(5) { ["account_id"]=> string(25) "2dqxxr0qmlg8cwwxritl6g91b" ["display_name"]=> string(6) "Drafts" ["id"]=> string(25) "a9f2404xh3c5hhxz24tbjti20" ["name"]=> string(6) "drafts"  " } 
array(5) { ["account_id"]=> string(25) "2dqxxr0qmlg8cwwxritl6g91b" ["display_name"]=> string(9) "Important" ["id"]=> string(25) "7ctbaociwyt3k42rk6lang9or" ["name"]=> string(9) "important"  " } 
array(5) { ["account_id"]=> string(25) "2dqxxr0qmlg8cwwxritl6g91b" ["display_name"]=> string(9) "Sent Mail" ["id"]=> string(25) "3vas5if0kzs9oorqxhv9qg1h3" ["name"]=> string(4) "sent"  " } 
array(5) { ["account_id"]=> string(25) "2dqxxr0qmlg8cwwxritl6g91b" ["display_name"]=> string(4) "Spam" ["id"]=> string(25) "83mft3e5s96jkva60axo76jaa" ["name"]=> string(4) "spam"  " } 
array(5) { ["account_id"]=> string(25) "2dqxxr0qmlg8cwwxritl6g91b" ["display_name"]=> string(5) "Trash" ["id"]=> string(25) "8qrqlt058jxpyuty3rpfxg6je" ["name"]=> string(5) "trash"  " }
*/
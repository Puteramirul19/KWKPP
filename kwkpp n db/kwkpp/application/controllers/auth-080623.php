<?php
require_once('basecontroller.php');

class Auth extends BaseController {


	function Auth()
	{

		parent::BaseController(); 

	}



  ##### index #####
  function index()
  {
// rapydlib("popwin");
    $this->_render("auth", null, 
                    array(
                      array("file"=>VIEWPATH."auth.php"),
                    )
                  );                  
  }






  ##### login #####
	function login()
  {
  
    //authlogin//

    if ($this->rapyd->auth->is_logged())
    {
      redirect("auth");
      
    } else {


// ldap_connect("ad.wjgilmore.com") or die("Couldn't connect to AD!"); 
//ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
//ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
//$ad=0;$bd=0;//tnb.my //ad.wjgilmore.com

//$ad = ldap_connect ('ad.wjgilmore.com');// or die("Couldn't connect to AD!");
//$bd = ldap_bind($ad,"ad-web@ad.wjgilmore.com","secret");// or die("Couldn't bind to AD!");

//$ad = ldap_connect ('tnb.my'); // or die("Couldn't connect to AD!");
//$bd = ldap_bind($ad,"kwkpp@tnb.my",""); // or die("Couldn't bind to AD!");
$ad = ldap_connect ('ldap://tnb.my') or die("Couldn't connect to AD!");
ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3) or die ("Could not set ldap protocol");
//ldap_set_option($ad, LDAP_OPT_REFERRALS, 0) or die("Could not set ldap protocol");
//$bd = ldap_bind($ad,"10027612", "si2satan"); // or die("Couldn't bind to AD!");

//ldap_unbind($ad);
	if($ad )
	{
      $this->rapyd->load('dataform'); 
      $a=array("01"=>"01","02"=>"Feb");
      $form = new DataForm("auth/login/process");
      
      $form->nick = new inputField("Nama Pengguna", "user_");
      $form->nick->rule = "required";
      
      $form->pass = new passwordField("Kata Laluan", "pass_");
      $form->pass->type = "password"; 
      $form->pass->rule = "required";
      
      //$form->captcha = new captchaField("Pengesahan", "captcha");
      
      $form->submit("btn_submit", "Log Masuk");  

      $form->build_form(); 
      $data["form"] = $form->output;
      
      if ($form->on_show() || $form->on_error())
      {
        //do nothing
      }
      
      if ($form->on_success())
      {
        //is a valid user
        $valid_user = $this->rapyd->auth->trylogin(
           $this->input->post("user_"),
           $this->input->post("pass_"));

        //has needed minimum role/level
        if ($this->rapyd->auth->check_role(3))
        { 
          redirect(keep_lang("auth"));
        
        //username/password error OR user has not needed role/level
        } else {
                
          if ($valid_user)
          {
            $form->error_string = "Anda tidak dibenarkan masuk ke dalam sistem ini";
			//"Your role does not grant access to the resource requested";
            $this->rapyd->auth->logout(); 
          }
          else
          {
            $form->error_string = "Salah Nama Pengguna ATAU Kata Laluan";//"Wrong username or password";
          }
          $form->build_form(); 
          
          $data["form"] = $form->output;
          
        }
      
      }
      

    //endauthlogin//

    $content["content"] = $this->load->view('auth_login', $data, true);    
    $content["rapyd_head"] = $this->rapyd->get_head();

    $content["code"] = highlight_code_file(THISFILE, "//authlogin//", "//endauthlogin//");
    //$content["code"] .= '<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #FF8000">//related function<br /></span><br/>';    
    $this->load->view('template', $content);
    
    $this->_render("auth_login", $data, 
                    array(
                      array("file"=>THISFILE, "id"=>"authlogin"),
                    )
                  );
    
    
    
    
		}
		else
		{ echo "Maaf, anda tidak dibenarkan untuk menggunakan sistem ini...";}
    }
    
  }
  
  function logout_yes()
  {

	$this->rapyd->auth->logout();
    redirect("auth/login");
   }

  function logout()
  {
?>
	<script language="JavaScript"> 
 	var where_to= confirm("Adakah anda pasti untuk log keluar dari sistem ini?"); 
	if (where_to== true) 
	{ window.location="<?php echo base_url()?>index.php/auth/logout_yes"; } 
	else { window.location="<?php echo base_url()?>index.php/auth"; } 
	</script>
<?php

  }





}
?>
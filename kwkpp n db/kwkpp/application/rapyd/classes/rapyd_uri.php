<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Rapyd Components
 *
 * An open source library for CodeIgniter application development framework for PHP 4.3.2 or newer
 *
 * @package		rapyd.components
 * @author		Felice Ostuni
 * @license		http://www.fsf.org/licensing/licenses/lgpl.txt LGPL
 * @version		0.9.6
 * @filesource
 */
 
/**
 * Rapyd Components URI management class
 *
 * @package    rapyd.components
 * @author     Thierry Rey
 * @access     public
 * @version    0.0.2
 */
class rapyd_uri{


  var $all_segments = array("base_uri", "gfid", "orderby", "uri_search", "search", "reset", "osp",
                            "create", "insert", "show", "modify", "delete", "do_delete", "update");


 /**
  * PHP4 constructor.
  *
  * @access   public
  */
  function rapyd_uri(&$rapyd){

    $this->rapyd =& $rapyd;
    $this->session =& $this->rapyd->session;
    $this->ci =& get_instance();
    $this->router =& load_class('Router');
    
    $this->uri =& $this->ci->uri;
    $this->gfid = null;

    $this->uri_array = $this->explode_uri(); 
    
    if($this->is_set("gfid")){
      $this->gfid = $this->get("gfid",1);
    }
    
    $this->_keep_persistence = false;
    
  }

  //if uri is passed, i use it as an utility function to prep actions link (by adding dinamically persistence segments)
  function explode_uri($uri=null)
  {
    if(!isset($uri)){
			//MODIF 0 URI
     	$segment_array =($this->rapyd->config->item('rapyd_ruri_ON')===TRUE)? array_values($this->uri->rsegment_array()): array_values($this->uri->segment_array());

    } else {
    
      $segment_array = array();
      foreach(explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $uri)) as $val)
      {
        $val = trim($val);
        if ($val != '') $segment_array[] = $val;
      }
    }
    
    $segment_count = count($segment_array);

    //<<<<URI clause '/search':>>>>
    $do_search = array_search(RAPYD_URI_SEARCH,$segment_array);
    if ($do_search!==false){
      $uri_array["search"]  =	array_splice($segment_array, $do_search, 1);
    }
    //<<<<URI clause '/reset':>>>>
    $do_reset = array_search(RAPYD_URI_RESET,$segment_array);
    if ($do_reset!==false) {
      $uri_array["reset"]  = array_splice($segment_array, $do_reset, 1);
    }

    //<<<<URI clause '/orderby/fieldname/sort':>>>>
    $do_orderby = array_search(RAPYD_URI_ORDERBY,$segment_array);    
    if ($do_orderby!==false){
      $uri_array["orderby"]  = array_splice($segment_array, $do_orderby,3);
    }
    
    // 2 different lenght possible
    // /ops URI lenght = 1 ,all other page, /ops/n URI lenght = 2
    $do_osp = array_search(RAPYD_URI_OSP,$segment_array);    
    if ($do_osp!==false){
    	//if($do_osp != count($segment_array)-1 && $do_osp != count($segment_array)-2){echo " Error in osp URI range<br>";}
    	$uri_array["osp"]  = array_splice($segment_array, $do_osp,2);
    }
     else{
      $uri_array["osp"]  = array(RAPYD_URI_OSP);
    }
    
    //<<<<URI clause '/uri_search/field1/value1/field2/value2....':>>>>
    $do_uri_search = array_search(RAPYD_URI_URI_SEARCH,$segment_array);    
    if ($do_uri_search!==false) { 	

    	$max_length = count(array_slice($segment_array,$do_uri_search));
			if(($max_length % 2)==0){$max_length --;}
			//With $max_length we are sure that we take a right number of couple value. we leave the last URI segment if it is a single.
    	$uri_array["uri_search"]  = array_splice($segment_array,$do_uri_search,$max_length);
    }
    
  	//<<<<URI clause '/gfid/GFID':>>>>
    $do_gfid = array_search(RAPYD_URI_GFID,$segment_array);    
    if ($do_gfid!==false){
    	$uri_array["gfid"]  = array_splice($segment_array, $do_gfid,2);
    }    
 
    //dataedit related
 
    $do_create = array_search(RAPYD_URI_CREATE,$segment_array);
    if ($do_create!==false){
      $uri_array["create"]  = array_splice($segment_array, $do_create,1);
    } 
 
    $do_show = array_search(RAPYD_URI_SHOW,$segment_array);
    if ($do_show!==false){
      $uri_array["show"]  = array_splice($segment_array, $do_show, $segment_count-$do_show);
    }  

    $do_modify = array_search(RAPYD_URI_MODIFY,$segment_array);    
    if ($do_modify!==false){
      $uri_array["modify"]  = array_splice($segment_array, $do_modify, $segment_count-$do_modify);
    }  

    $do_delete = array_search(RAPYD_URI_DELETE,$segment_array);
    if ($do_delete!==false){
      $uri_array["delete"]  = array_splice($segment_array, $do_delete, $segment_count-$do_delete);
    }  
    
    $do_do_delete = array_search(RAPYD_URI_DO_DELETE,$segment_array);    
    if ($do_do_delete!==false){
      $uri_array["do_delete"]  = array_splice($segment_array, $do_do_delete, $segment_count-$do_do_delete);
    }
    
    $do_insert = array_search(RAPYD_URI_INSERT,$segment_array);    
    if ($do_insert!==false){
      $uri_array["insert"]  = array_splice($segment_array, $do_insert,1);
    } 

    $do_update = array_search(RAPYD_URI_UPDATE,$segment_array);    
    if ($do_update!==false){
      $uri_array["update"]  = array_splice($segment_array, $do_update, $segment_count-$do_update);
    } 
		
    //MODIF 0 URI
    //$uri_array["base_uri"]  = $segment_array;
    $uri_array["base_uri"]  = $this->complete_uri($segment_array);

    return $uri_array;
  }
  
  //MODIF 0 URI
  //New function that add the default controller and index method if it is need into an arg segment array by reading the 
  //segments instead of the router method and class.
  //As module concept default controleur is more complicate if we work with module we don't add anything
  
 	function complete_uri($segment_arr)
	{
		if(count($segment_arr)==0)
		{
			return array($this->ci->uri->router->default_controller,'index');
		}

		$complet_segm_arr=array();
		$controller_ind =0;
		$first_param_ind=0;
		$directory = "";
		
		//We follow the directory???
		if (is_dir(APPPATH.'controllers/'.$segment_arr[0]))
		{
			$controller_ind =1;	
			$first_param_ind=1;
			$complet_segm_arr[] = $segment_arr[0];
			$directory = $segment_arr[0].'/';
		}
		
		if(isset($segment_arr[$controller_ind]))
		{
				//There is something in the controller place, we test if it is a valid controller and then
				// add the index method if it is missing
				if ( file_exists(APPPATH.'controllers/'.$directory.$segment_arr[$controller_ind].EXT))
				{
						if(!isset($segment_arr[$controller_ind+1]))$segment_arr[] = "index";
						$complet_segm_arr=$segment_arr;
				}
				else
				{		
					//MODIF 0 URI
					//There is somthing into the controler But It is not an existing one 2 reasons:
					//We work on module concept so the folder and controller files are Out of the CI controller directory
					//Or we use the fonction as utility function on a non valide controller, but like it is just to work on 
					//URI (may be a route source to complete or filter with Rapyd_URI features) we don't take care of the 
					//routing thru a default_contrl/index including Rapyd component BUG (we don't route we work on uri!)
					$complet_segm_arr=$segment_arr;
				}
		}
		else
		{
				//If ther is only a directory in URI we first test if there is a default controller into this directory if not we 
				//return to controller root folder.
				if(!file_exists(APPPATH.'controllers/'.$directory.$this->ci->uri->router->default_controller.EXT) && $directory !="")
				{
					$complet_segm_arr = array();
				}		
				//and we add the controller and method...
				$complet_segm_arr[] = $this->ci->uri->router->default_controller;
				$complet_segm_arr[] = "index";
		}
		return $complet_segm_arr;
	}
  
  
  /**
  * Rebuild reordered Controller URI with rapyd component rules
  *
  * @access   public
  * @param    string  can be every URI clause name + "base_uri" and "page_offset". Takes account of the order you give it...
  * @return   string Controller standard URI string
  *
  * sample call:
  *   implode_uri() => return the complete controller entry URI but reordered
  *   implode_uri("base_uri","gfid","orderby","osp") => return the controller entry URI formated in the Datagrid most used URI
  */
  function implode_uri()
  {
  	$args = func_get_args();
    
    //just a workaround to use implode_uri as helper (to implode a given array instead $this->uri_array) 
    if (isset($args[0]) && is_array($args[0])){
      $uri_array = array_shift($args);
    } else {
      $uri_array =& $this->uri_array;
    }

  	$return_uri = "";

  	if(count($args)<1){

      $return_uri = $this->_build_uri($uri_array);

  	}else{
  		
      $return_uri = $this->_build_uri($uri_array, $args);

	  }
  	return $return_uri;
  }
  

  /**
  * Build and return a uri
  *
  * @access   private
  * @param    array $uri_array (structured array of a uri, tipically generated from explode_uri)
  * @return   array $clauses (a simple array of clauses needed in the result)
  *
  * sample call:
  *   implode_uri() => return the complete controller entry URI but reordered
  *   implode_uri("base_uri","gfid","orderby","osp") => return the controller entry URI formated in the Datagrid most used URI
  */
  function _build_uri($uri_array, $clauses=null)
  {
    $clauses = (isset($clauses)) ? $clauses : $this->all_segments;

    $compiled_segments = array();

    //to keep order i cycle over all_segments instead $clauses.
    foreach($this->all_segments as $clause)
    {
      if(in_array($clause,$clauses) && isset($uri_array[$clause])){
        $compiled_segments[] = join("/",$uri_array[$clause]);
      }
    }
    $return_uri = join("/",$compiled_segments);
    
    //remove double slashed
    $return_uri = preg_replace("#([^:])//+#", "\\1/", $return_uri);
    
    return $return_uri;
  }
  

  function reverse_implode_uri()
  {
     $args = func_get_args();

    //just a workaround to use implode_uri as helper (to implode a given array instead $this->uri_array) 
    if (isset($args[0]) && is_array($args[0])){
      $uri_array = array_shift($args);
    } else {
      $uri_array =& $this->uri_array;
    }

    if(count($args)<1)
    {
      return false;
    } else {
      foreach ($args as $arg)
      {
        if(isset($uri_array[$arg])) unset($uri_array[$arg]);
      }
    }
    return $this->implode_uri($uri_array);
  }

	/*
		mapping Fonction for the CI->uri->uri_string() and CI->uri->ruri_string()according to the Rapyd config about RURI
		This way we can call it in all case didn't care about the Rapyd current setting.
		
		Additional features:
		We have add the possibility to skip the Slash prefix and suffix addition (according to CI->uri->ruri_string()we add it by default)
		this way we have the same result of a simple join() or implode(): implode('/', $this->uri->rsegment_array());
	*/
	
  function uri_string($psfix = true)
  {
  		$pre_suf_fix = ($psfix === true)?"/":"";
  		
			if($this->rapyd->config->item('rapyd_ruri_ON')===TRUE)
			{ 			
  			return $pre_suf_fix.implode('/', $this->uri->rsegment_array()).$pre_suf_fix;
  		}
  		else
  		{
  			return $pre_suf_fix.implode('/', $this->uri->segment_array()).$pre_suf_fix;
  		}
  }
  
  /**
  * segment count of a filtered URI or the entry URI with gfid and osp
  *
  * @access   public
  * @param    string  (optional),can be every URI clause name + "base_uri" and "osp".
  * @return   int     sum of the array count of the URI clause array give in arg or entry URI if no arg.
  *
  * sample call:
  *   count_uri() => return the entry URI segment count
  *   count_uri("base_uri","gfid","orderby","osp") => return the segment count of implode_uri("base_uri","gfid","orderby","osp")
  */
  function count_uri()
  {
    $count_uri = 0;
  	$args = func_get_args();

  	if(count($args)>0)
    {
      return count(explode("/", $this->_build_uri($this->uri_array, $args)));
    } else {
      return count(explode("/", $this->_build_uri($this->uri_array)));
    }
  }
  
  /**
  * sort order of clauses in a given uri
  *
  * @access   public
  * @param    string  $uri for example controller/function/osp/5/orderby/article_id/desc
  * @return   string  reordered uri  controller/function/orderby/article_id/desc/osp/5
  */
  function sort($uri)
  {
    $uri_array = $this->explode_uri($uri);
    return $this->_build_uri($uri_array);
  }
  


  /**
  * return the position of the page offset value in URI
  *
  * @access   public
  * @param    
  * @return   int.
  */
  function offset_pos()
  {
   // $this->count_uri();

	 	if(isset($this->uri_array["osp"]))
    {
	 		//ops without and with offset page value
	 		if(count($this->uri_array["osp"])==1){
	 			$pos = $this->count_uri()+1;
	 		}else{
        //MODIF 0 URI (support of routed URI)
	 			$segment_array =($this->rapyd->config->item('rapyd_ruri_ON')===TRUE)? $this->uri->rsegment_array():$this->uri->segment_array();
        $pos = array_search(RAPYD_URI_OSP,$segment_array)+1;
	 		}
	 	}
	 	/*****************************************************************************
	 	 * We take care of this case even if we have added it in explode_uri for more
	 	 * security in URI reading
	 	 *****************************************************************************/
	 	else{
	 		$pos = $this->count_uri()+1;
	 	}
  	return $pos;
  } 

    
  //by default  uri/gfid persistence is off, it enable gfid in the links/actions
  //it must be called before rapyd components:
  //...
  //$this->rapyd->uri->keep_persistence();
  //...
  //$filter = new DataFilter....
  //$grid = new DataGrid..
  
  function keep_persistence()
  {
    $this->_keep_persistence = true;
    $this->rapyd->session->persistence_duration = $this->rapyd->config->item("persistence_duration"); //max seconds before persistence expire
    $this->rapyd->session->persistence_limit = $this->rapyd->config->item("persistence_limit"); //max page-status stored for each URI (olders are shifted out)
  
    if($this->is_set("gfid"))
    {
      $this->gfid = $this->get("gfid",1);
    } else {
      $page = array();
      $gfid = $this->rapyd->session->save_persistence($this->implode_uri("base_uri"), $page);
      $this->gfid = $gfid;
      $this->set("gfid",$gfid);
    }
  }


  //currently unused..  by default  uri/gfid persistence is off ..
  //the idea here is to shutdown "keep_persistence" directive.
  function stop_persistence()
  {
    $this->rapyd->session->persistence_duration = 3600; //max seconds before persistence expire
    $this->rapyd->session->persistence_limit = 1; //max page-status stored for each URI (olders are shifted out)

    $this->rapyd->session->clear_persistence($this->rapyd->uri->implode_uri("base_uri"), $this->rapyd->uri->gfid);
    unset($this->uri_array["gfid"]);
    $this->rapyd->uri->gfid = null;
  }


  /***********************************************************************
   * manupulation functions                                              *
   * $this->uri_array  getters & setters                                 *
   ***********************************************************************/ 

  function is_set($segment)
  {
    return isset($this->uri_array[$segment]);
  }

  function get($segment,$pos=null)
  {
    if ($this->is_set($segment)){
      if (isset($pos)){
        if (isset($this->uri_array[$segment][$pos])) {
          return $this->uri_array[$segment][$pos];
        } else {
          return null;
        }
      } else {
        return $this->uri_array[$segment];
      }
    } else {
      return null;
    }
  }

  function set()
  {
    $args = func_get_args();
    $uri_keywords = $this->rapyd->config->item("uri_keywords");

    if(count($args)>0)
    {
      //replace a passed keyword (like "orderby") with a rapyd config uri_keyword
      $key = $args[0];
      $args[0] = $uri_keywords[$key];
      
      //if i use $this->rapyd->uri->set("reset") it is a explicit request to add "/request" to the uri
      //so i add simply a empty item so im shure that "implode" add /request.
      if (count($args)<2) $args[] = "";
      $this->uri_array[$key] = $args;
    }
  }


  function un_set()
  {
    $args = func_get_args();
    if(count($args)>0){      
      foreach ($args as $arg){
        if(isset($this->uri_array[$arg])) unset ($this->uri_array[$arg]);;
      }
    }

  }
  
  /**
  * build an assoc array of the clause 
  * the truncate arg is use for deciding what to do if the array have
  * an odd number of elements. As we work on URI clause they mostly have
  * an identifier just before the coupled values. like for 'orderby' or 
  * 'uri_search' the thruncate code keep only the value  
  *
  * @access   public
  * @param    string URI clause name 
  * @return   array 
  */ 
  function assoc_uri_clause($clause ,$truncate="left")
  {
  	$clause_assoc = array();
  	if(!isset($this->uri_array[$clause])){ return false;} 	 
  		 
    $clause_arr = $this->uri_array[$clause];
    
    if((count($clause_arr)%2)!= 0)
    {
      switch($truncate){
      	case "left":
      		array_shift($clause_arr);
      	break;
      	case "right":
      		array_pop($clause_arr);
        break;
    	}
    }
		for($i=0;$i< count($clause_arr);$i+=2){
			$clause_assoc[$clause_arr[$i]]=$clause_arr[$i+1];
		}
		return $clause_assoc;
  }

	//useful in the DE workflow to know the currently edited Pkey and use it in contextual submenu...
	function get_edited_id($string_output = TRUE)
	{
		$id_uri = "";
		$_clause = False;
		if($this->is_set(RAPYD_URI_SHOW)){
			$_clause = RAPYD_URI_SHOW;
		}elseif($this->is_set(RAPYD_URI_MODIFY)){
			$_clause = RAPYD_URI_MODIFY;
		}elseif($this->is_set(RAPYD_URI_DELETE)){
			$_clause = RAPYD_URI_DELETE;
		}elseif($this->is_set(RAPYD_URI_UPDATE)){
			$_clause = RAPYD_URI_UPDATE;
		}
		if($_clause!==False){
			$_clause_arr = $this->get($_clause);
			array_shift($_clause_arr);
			$id_uri = ($string_output === TRUE)? implode("/",$_clause_arr):$_clause_arr;
		}
		return $id_uri;
	}

/***********************************************************************
 * uri helper functions                                                *
 * not affects $this->uri_array and  use a custom uri                  *
 ***********************************************************************/ 

 /**
  * build_clause replace the uri_keywords with config values:
  * $this->rapyd->uri->build_clause("orderby/article_id/desc"); 
  * {orderby}/article_id/desc
  *
  * (not affect $this->uri_array):
  *
  * @access   public
  * @return   void
  */
  function build_clause($clause)
  {
    $uri_keywords = $this->rapyd->config->item("uri_keywords");
    $segments = explode("/",trim($clause,"/"));

    $key = $segments[0];
    $segments[0] = $uri_keywords[$key];
      
    return join("/",$segments);
  }


  function change_clause($uri, $from_clause, $to_clause)
  {
    $uri_keywords = $this->rapyd->config->item("uri_keywords");  
    $uri_array = (is_array($uri))? $uri : $this->explode_uri($uri);
    
    $uri_array[$to_clause] = $uri_array[$from_clause];
    $uri_array[$to_clause][0] = $uri_keywords[$to_clause];
    unset($uri_array[$from_clause]);
    
    return $this->_build_uri($uri_array, array_keys($uri_array));
  }
  
  
  function unset_clause($uri, $clause)
  {
    $uri_keywords = $this->rapyd->config->item("uri_keywords");  
    $uri_array = (is_array($uri))? $uri : $this->explode_uri($uri);
    
    unset($uri_array[$clause]);
    
    return $this->_build_uri($uri_array, array_keys($uri_array));
  }


  function add_clause($uri, $clause)
  {
    $uri_keywords = $this->rapyd->config->item("uri_keywords");  
    $uri_array = (is_array($uri))? $uri : $this->explode_uri($uri);
    //var_dump($uri_array);

    $clause_segments = explode("/",$clause);
    $key = $clause_segments[0];
    $clause_segments[0] = $uri_keywords[$key];
    $uri_array[$key] = $clause_segments;
    
    return $this->_build_uri($uri_array, array_keys($uri_array));
  }
  
  //Add only the gfid to the gived URI. also useful to work on links when we are into a DG+DE persistence workflow....
	function include_gfid($uri)
	{
		if (isset($this->gfid))
    {      
      $uri_array = $this->explode_uri($uri);
      $uri_array[RAPYD_URI_GFID] = array(RAPYD_URI_GFID, $this->gfid);
      
      //explode_uri add an empty osp clause if it is missing, so in this case we unset it because this uri helper functions
      //have to add only the GFID. because if we include gfid in a link to DE for example the osp clause can be a problem......  

      if(count($uri_array[RAPYD_URI_OSP]) < 2)
      {
      	unset($uri_array[RAPYD_URI_OSP]);
      }
      $uri = $this->rapyd->uri->_build_uri($uri_array);
    }
    return $uri;
	}
}



?>
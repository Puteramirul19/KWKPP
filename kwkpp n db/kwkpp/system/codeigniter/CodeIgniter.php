<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		Rick Ellis
 * @copyright	Copyright (c) 2006, EllisLab, Inc.
 * @license		http://www.codeignitor.com/user_guide/license.html
 * @link		http://www.codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * System Front Controller
 *
 * Loads the base classes and executes the request.
 *
 * @package		CodeIgniter
 * @subpackage	codeigniter
 * @category	Front-controller
 * @author		Rick Ellis
 * @link		http://www.codeigniter.com/user_guide/
 */

// CI Version
define('CI_VERSION',	'1.5.4');
 
/*
 * ------------------------------------------------------
 *  Load the global functions
 * ------------------------------------------------------
 */
 
require(BASEPATH.'codeigniter/Common'.EXT);

/*
 * ------------------------------------------------------
 *  Define a custom error handler so we can log PHP errors
 * ------------------------------------------------------
 */
set_error_handler('_exception_handler');
//set_magic_quotes_runtime(0); // Kill magic quotes
// Check if magic_quotes_runtime is active
if(get_magic_quotes_runtime())
{
    // Deactivate
    set_magic_quotes_runtime(false);
}

/*
 * ------------------------------------------------------
 *  Start the timer... tick tock tick tock...
 * ------------------------------------------------------
 */

//$BM =& load_class('Benchmark');
$BM = load_class('Benchmark');
$BM->mark('total_execution_time_start');
$BM->mark('loading_time_base_classes_start');

/*
 * ------------------------------------------------------
 *  Instantiate the hooks class
 * ------------------------------------------------------
 */

//$EXT =& load_class('Hooks');
$EXT = load_class('Hooks');
/*
 * ------------------------------------------------------
 *  Is there a "pre_system" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('pre_system');

/*
 * ------------------------------------------------------
 *  Instantiate the base classes
 * ------------------------------------------------------
 */

/*$CFG =& load_class('Config');
$RTR =& load_class('Router');
$OUT =& load_class('Output'); */

$CFG = load_class('Config');
//echo "Hello";
$RTR = load_class('Router');  //  <--- boleh check route ini
//echo "Hello";
$OUT = load_class('Output'); 
//echo "Hello";
/*
 * ------------------------------------------------------
 *	Is there a valid cache file?  If so, we're done...
 * ------------------------------------------------------
 */

if ($EXT->_call_hook('cache_override') === FALSE)
{  
	if ($OUT->_display_cache($CFG, $RTR) == TRUE)
	{
		exit;
	}
}

/*
 * ------------------------------------------------------
 *  Load the remaining base classes
 * ------------------------------------------------------
 */

$IN		=& load_class('Input');
$URI	=& load_class('URI');
$LANG	=& load_class('Language');

/*
 * ------------------------------------------------------
 *  Load the app controller and local controller
 * ------------------------------------------------------
 *
 *  Note: Due to the poor object handling in PHP 4 we'll
 *  conditionally load different versions of the base
 *  class.  Retaining PHP 4 compatibility requires a bit of a hack.
 *
 *  Note: The Loader class needs to be included first
 *
 */
if (floor(phpversion()) < 5)
{  
	load_class('Loader', FALSE);
	require(BASEPATH.'codeigniter/Base4'.EXT);
}
else
{ 
	require(BASEPATH.'codeigniter/Base5'.EXT);
}
 
// Load the base controller class
load_class('Controller', FALSE);

// Load the local application controller
// Note: The Router class automatically validates the controller path.  If this include fails it 
// means that the default controller in the Routes.php file is not resolving to something valid.
if ( ! include(APPPATH.'controllers/'.$RTR->fetch_directory().$RTR->fetch_class().EXT))
{
	show_error('Unable to load your default controller.  Please make sure the controller specified in your Routes.php file is valid.');
}

// Set a mark point for benchmarking
$BM->mark('loading_time_base_classes_end');


/*
 * ------------------------------------------------------
 *  Security check
 * ------------------------------------------------------
 *
 *  None of the functions in the app controller or the
 *  loader class can be called via the URI, nor can
 *  controller functions that begin with an underscore
 */
$class  = $RTR->fetch_class();
$method = $RTR->fetch_method();


if ( ! class_exists($class)
	OR $method == 'controller'
	OR substr($method, 0, 1) == '_'
	OR in_array($method, get_class_methods('Controller'), TRUE)
	)
{
	show_404();
}

/*
 * ------------------------------------------------------
 *  Is there a "pre_controller" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('pre_controller');

/*
 * ------------------------------------------------------
 *  Instantiate the controller and call requested method
 * ------------------------------------------------------
 */

// Mark a start point so we can benchmark the controller
$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_start');

// Instantiate the Controller
$CI = new $class();

// Is this a scaffolding request?
if ($RTR->scaffolding_request === TRUE)
{
	if ($EXT->_call_hook('scaffolding_override') === FALSE)
	{
		$CI->_ci_scaffolding();
	}
}
else
{
	/*
	 * ------------------------------------------------------
	 *  Is there a "post_controller_constructor" hook?
	 * ------------------------------------------------------
	 */
	$EXT->_call_hook('post_controller_constructor');
	
	// Is there a "remap" function?
	if (method_exists($CI, '_remap'))
	{
		$CI->_remap($method);
	}
	else
	{
		if ( ! method_exists($CI, $method))
		{
			show_404();
		}

		// Call the requested method.
		// Any URI segments present (besides the class/function) will be passed to the method for convenience		
		call_user_func_array(array(&$CI, $method), array_slice($RTR->rsegments, (($RTR->fetch_directory() == '') ? 2 : 3)));		
	}
}

// Mark a benchmark end point
$BM->mark('controller_execution_time_( '.$class.' / '.$method.' )_end');

/*
 * ------------------------------------------------------
 *  Is there a "post_controller" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('post_controller');

/*
 * ------------------------------------------------------
 *  Send the final rendered output to the browser
 * ------------------------------------------------------
 */

if ($EXT->_call_hook('display_override') === FALSE)
{
	$OUT->_display();
}

/*
 * ------------------------------------------------------
 *  Is there a "post_system" hook?
 * ------------------------------------------------------
 */
$EXT->_call_hook('post_system');

/*
 * ------------------------------------------------------
 *  Close the DB connection if one exists
 * ------------------------------------------------------
 */
if (class_exists('CI_DB') AND isset($CI->db))
{
	$CI->db->close();
}


?>
<?php
/**
 * autoUpdateField - is a field widhout output
 * use it for add easily auto-updated fields (widthout: $field->insertValue, $field->updateValue)
 *
 * @package rapyd.components.fields
 * @author Felice Ostuni <felix@rapyd.com>
 * @license http://www.fsf.org/licensing/licenses/lgpl.txt LGPL
 * @copyright Copyright (c) 2006 Felice Ostuni - http://www.rapyd.com
 * @version 1.0
 */
 
 
/**
 * captchaImage
 *
 * @package    rapyd.components.fields
 * @author     Felice Ostuni
 * @access     private
 */
class captchaImage{

    var $length;
    var $string;
    var $ttfFont;
    var $charWidth;

 /**
  * PHP4 constructor.
  *
  * @access   public
  * @param    string   $length   captcha string length
  * @param    string   $string   captcha string
  * @return   void
  */
  function captchaImage($length = 5, $string = null){

    $this->length    = $length;
    $this->ttfFont   = './captcha.ttf';
    $this->charWidth = 12;

    if (!isset($string)){
      $this->string = $this->randomString($length);
    } else {
      $this->string = $string;
    }

  }

  function randomString($length){
    
    //alphanumeric array widthout ambiguous chars
    //$alphanumericArr  = array ('A','B','C','D','E','F','G','H','L','M','P','R','2','3','8','9'); 
	$alphanumericArr  = array ('A','B','C','D','E','F','G','H','L','M','P','R','2','3','8','9','a','b','c');
    shuffle($alphanumericArr);
    $alphanumericArr = array_slice($alphanumericArr, 0, $length-1);
    $string = join("",$alphanumericArr);
    return $string;
    
  }


  function draw(){
  
    header('Content-type: image/png');

    $length     = $this->length * $this->charWidth + 20;
    $height     = 25;
    
    $image      = imagecreate($length, $height);
    $background = imagecolorallocate($image, 220, 220, 220);

    $textcolor  = imagecolorallocate($image, 0, 0, 0);
    $linecolor  = imagecolorallocate($image, 0, 0, 0);

    imagettftext($image, 25, 0, 4, 18,  
                 $textcolor,
                 $this->ttfFont,
                 $this->string);
                 
    imagepng($image);
    
  }


  function getString(){
    return $this->string;
  }

}


  ob_start();
  session_start();

  $mycaptcha = new captchaImage();
  $_SESSION['captcha'] = $mycaptcha->getString();
  $mycaptcha->draw();
  echo time(); //needed to force image expiration
  session_write_close();
  ob_flush();

?>
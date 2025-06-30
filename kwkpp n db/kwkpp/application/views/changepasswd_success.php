
<?php
/*
  this is a view file with some auth & language helper
  
  - is_logged() check user status
  - check_role() ""   ""   role
  - get_user_data()  get & decrypt user data
  - anchor_lang()  is a replacement of anchor from url helper,
    that add/keep "language-segment" in the uri
*/

?>
<!--  <h2>KWKPP - TNB</h2> -->
<div>
<?php if(is_logged()):?>

<h3 align="center"><?php echo strtoupper(get_user_data("name"));?>, Kata Laluan Telah Diperbaharui<br><br>
<img src="<?php echo base_url()?>application/images/logo/logotnbwhite-small.png"></h3>
<?php else: redirect('auth/login'); ?>

  <!-- you are not logged in, <?php echo anchor_lang("auth/login","Log in")?> -->
  
<?php endif;?>
</div>

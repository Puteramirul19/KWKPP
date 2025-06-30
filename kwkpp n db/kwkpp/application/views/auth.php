
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

<h4 align="center"><?php echo strtoupper(get_user_data("name"));?>, Selamat Datang ke Sistem Maklumat Kumpulan Wang Khairat Pekerja dan Pesara TNB<br><br>
<img src="<?php echo base_url()?>application/images/logo/logotnb.bmp"></h4>
<!-- <h1>Selamat Datang ke Sistem Maklumat Kumpulan Wang Khairat Pekerja dan Pesara TNB</h1>
<h2>Selamat Datang ke Sistem Maklumat Kumpulan Wang Khairat Pekerja dan Pesara TNB</h2>
<h3>Selamat Datang ke Sistem Maklumat Kumpulan Wang Khairat Pekerja dan Pesara TNB</h3>
<h5>Selamat Datang ke Sistem Maklumat Kumpulan Wang Khairat Pekerja dan Pesara TNB</h5>
<h6>Selamat Datang ke Sistem Maklumat Kumpulan Wang Khairat Pekerja dan Pesara TNB</h6>
<h7>Selamat Datang ke Sistem Maklumat Kumpulan Wang Khairat Pekerja dan Pesara TNB</h7> -->
<?php else: redirect('ms/auth/login'); ?>

  <!-- you are not logged in, <?php echo anchor_lang("auth/login","Log in")?> -->
  
<?php endif;?>
</div>

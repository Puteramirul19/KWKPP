<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>KWKPP - TNB</title>
<link rel="shortcut icon" href="<?php echo base_url()?>application/images/logo/favicon.ico" />
<script language="javascript" type="text/javascript" src="<?php echo base_url()?>application/rapyd/libraries/popwin/popwin.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>application/rapyd/libraries/jsmenu/menu.css">
<style type="text/css">
<?php echo $style?>
</style>
<?php echo $rapyd_head?>


</head>
<?php
	if($this->uri->segment(1)==="auth")
	{$setfocus="onload=\"document.f1.user_.focus()\"";}
	else
	{$setfocus="";}
?><!-- onunload="checkClose()" --><!-- onbeforeunload="go_there()" --><!-- href="javascript:popwin('http://www.tnb.com.my',800,600)" onunload="window.opener.opener=''; window.opener.close()" -->
<body  <?php if(!$this->rapyd->auth->is_logged()){ echo $setfocus; } ?> class="" background="<?php echo base_url()?>application/images/logo/" > <!-- logotnb_blur.png backgroundExpertFootballBlue.gif -->
<div id="content">
<div class="header">
<table width="100%" border="0">
<tr>
	<td rowspan="2" width="281px" height="65px" valign="bottom"><a href="<?php echo base_url()?>index.php/ms" title="TNB"><img border="0" src="<?php echo base_url()?>application/images/logo/logotnb_small.png" valign="bottom"></a></td>
	<td align="left" valign="bottom"><i><font size="5" face="Arial"><strong>&nbsp;&nbsp;&nbsp;SISTEM MAKLUMAT KWKPPTNB</font></strong><!-- <font size="1" face="Arial">[<?php echo base_url()?>]</font> rowspan="2" --></i></td>

	<td align="right" valign="top"><?php if(is_logged()): ?><a href="<?php echo base_url()?>index.php/auth/logout" title="Log Keluar"><img border="0" src="<?php echo base_url()?>application/images/labyrinth.ICO" valign="bottom"></a><?php endif; ?></td>
</tr>
<tr>
	<td align="left" valign="bottom"><i><font size="1" face="Arial"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;KUMPULAN WANG KHAIRAT PEKERJA & PESARA TNB (8592-U) <!--  <?php echo date("Y-m-d H:i:s")?>--></strong></font></i></td>
	
	<td align="right" valign="bottom"><?php if(is_logged()): if(get_role()==='2'){$bantuan_pdf="Panduan_Pengguna_Admin.pdf";}elseif(get_role()==='3'){$bantuan_pdf="Panduan_Pengguna_Operator.pdf";}else{$bantuan_pdf="";}?> <a href="javascript:popwin('<?php echo base_url().'application/help/'.$bantuan_pdf ?>',800,600)" title="Bantuan"><img border="0" src="<?php echo base_url()?>application/images/faq_icon_small-white.jpg" valign="bottom"></a><?php endif;?></td>
</tr>
</table>
</div>
<br>

	<?php if(is_logged()):?> 

	<div class="left">
	<div class="menubackground">Anda telah log masuk sebagai:<br>
	<div class="littletableheader" align="center"><?php echo strtoupper(get_user_data("user_name"));?>
	</div>
<!-- menu script itself. you should not modify this file -->
<script language="JavaScript" src="<?php echo base_url()?>application/rapyd/libraries/jsmenu/menu.js"></script>
<!-- items structure. used for all menus on this page, but may be personal 
<script language="JavaScript" src="<?php echo base_url()?>application/rapyd/libraries/jsmenu/menu_items.js"></script>-->
<script language="JavaScript">
var base_url_index='<?php echo base_url()?>index.php/';
var MENU_ITEMS = [
//	['Log masuk sebagai:', null, null],
//	['<?php echo strtoupper(get_user_data("user_name"));?>', null, null],
<?php if(check_role(1)):?>//root
	['Root - Level 1', base_url_index+'auth', null],
<?php endif;?>

<?php if(check_role(2)):?>//admin
	['Pengurusan Pengguna', null, null,
		['Senarai Pengguna', base_url_index+'crudusers', null],
		['Tambah Pengguna', base_url_index+'crudusers/dataedit/create', null],
	],
	['Aturan Sistem', null, null,
		['Hubungan',null, null,
			['Senarai Hubungan', base_url_index+'crudrelationship', null],
			['Tambah Hubungan', base_url_index+'crudrelationship/dataedit/create', null],
		],
		['Bank',null, null,
			['Senarai Bank', base_url_index+'crudbank', null],
			['Tambah Bank', base_url_index+'crudbank/dataedit/create', null],			
		],	
		['Stesen',null, null,
			['Senarai Stesen', base_url_index+'crudstation', null],
			['Tambah Stesen', base_url_index+'crudstation/dataedit/create', null],			
		],
		['Jenis Ahli',null, null,
			['Senarai Jenis Ahli', base_url_index+'crudmembertype', null],
			['Tambah Jenis Ahli', base_url_index+'crudmembertype/dataedit/create', null],			
		],
		['Negeri',null, null,
			['Senarai Negeri', base_url_index+'crudstate', null],
			['Tambah Negeri', base_url_index+'crudstate/dataedit/create', null],
		],
		['Jenis Bil',null, null,
			['Senarai Jenis Bil', base_url_index+'crudbilltype', null],
			['Tambah Jenis Bil', base_url_index+'crudbilltype/dataedit/create', null],
		],
		['Cara Pembayaran',null, null,
			['Senarai Cara Pembayaran', base_url_index+'crudpaymentmode', null],
			['Tambah Cara Pembayaran', base_url_index+'crudpaymentmode/dataedit/create', null],			
		],
		['Pembayar',null, null,
			['Senarai Pembayar', base_url_index+'crudpayee', null],
			['Tambah Pembayar', base_url_index+'crudpayee/dataedit/create', null],			
		],
		['Status Ahli',null, null,
			['Senarai Status Ahli', base_url_index+'crudmemberstatus', null],
			['Tambah Status Ahli', base_url_index+'crudmemberstatus/dataedit/create', null],			
		],
		['Jenis Pekerja',null, null,
			['Senarai Jenis Pekerja', base_url_index+'crudstafftype', null],
			['Tambah Jenis Pekerja', base_url_index+'crudstafftype/dataedit/create', null],			
		],
	],
<?php endif;?>

<?php if(check_role(3)):?>//operator
		['Ahli Khairat', null, null,
			['Senarai Ahli Khairat', base_url_index+'crudmembers', null],
			['Tambah Ahli Khairat', base_url_index+'crudmembers/dataedit/create', null],
		],
		['Status Semasa Ahli', null, null,
			<?php echo $statusAhli;?> 
		],
		['Penama', null, null,
			['Senarai Penerima Tuntutan', base_url_index+'crudbeneficiaries', null],
			['Tambah Penama', base_url_index+'crudbeneficiaries/dataedit/create', null],
		],
		['Ahli dan Penama', base_url_index+'crudmemberbeneficiarie', null],
		['Kematian', null, null,
			['Senarai Kematian', base_url_index+'cruddecease', null],
			/* commented because user want Kematian Baru to be open always without date limitation<?php if(date("d")<=$this->config->item('max_date_decease')):?><?php endif;?> */
			['Kematian Baru', base_url_index+'cruddecease/dataedit/create', null],
			
		],
		['Billing', null, null,
			['Bil Pendaftaran dan Penama', null, null,
				['Senarai Bil (Pendaftaran, Penama)', base_url_index+'crudbilling', null],
				['Tambah Bil (Pendaftaran, Penama)', base_url_index+'crudbilling/dataedit/create', null],
			],
			['Bil Sumbangan Kematian', null, null,
				['Senarai Bil Sumbangan Kematian', base_url_index+'crudbilling_decease', null],	
				['Buat Bil Sumbangan Kematian', base_url_index+'createmonthlycontri', null],
			],
		],
		['Penyata Akaun', base_url_index+'crudaccount',null],

		['Bayaran Bil', null, null,
			['Senarai Bayaran', base_url_index+'crudpaybill', null],
			['Buat Bayaran', base_url_index+'crudpaybill/dataedit/create', null],
			['Bayaran Pendaftaran Ahli', null, null,
				['Import Bayaran Pendaftaran', base_url_index+'importregfee', null],
				['Export Bayaran Pendaftaran', base_url_index+'exportregfee', null],
			],
			['Bayaran Tukar Penama', null, null,
				['Import Bayaran Tukar Penama', base_url_index+'importbeneficiariesfee', null],
				['Export Bayaran Tukar Penama', base_url_index+'exportbeneficiariesfee', null],
			],
			['Bayaran Sumbangan Kematian', null, null,
				['Import Sumbangan Kematian', base_url_index+'importcontri', null],
				['Export Sumbangan Kematian', base_url_index+'exportcontri', null],
			],
		],
		['Tuntutan', null, null,
			['Senarai Tuntutan', base_url_index+'crudclaim', null],
			['Tambah Tuntutan', base_url_index+'crudclaim/dataedit/create', null],
		],
<?php endif;?>
		<!-- disable because of AD ['Tukar Kata Laluan', base_url_index+'changepasswd', null], -->
		['Log Keluar', base_url_index+'auth/logout', null],
];
</script>
<!-- files with geometry and styles structures for coresponding menus -->
<script language="JavaScript" src="<?php echo base_url()?>application/rapyd/libraries/jsmenu/menu_tpl.js"></script>
<script language="JavaScript">

	// each menu gets two parameters 
	// 1. items structure
	// 2. geometry structure
	new menu (MENU_ITEMS, MENU_POS);
	// also take a look at stylesheets loaded in header in order to set styles

</script>	  

	</div></div>
	<?php endif;?>
		<div class="right">
			<?php echo $content?>  
		</div>   
	<div class="footer">
		<p><?php echo $this->config->item('system_version');?> | <a href="javascript:popwin('http://www.tnb.com.my',800,600)" title="TNB"> &copy;2008 Tenaga Nasional Berhad</a></p>
	</div>	
</div>

</body>
</html>
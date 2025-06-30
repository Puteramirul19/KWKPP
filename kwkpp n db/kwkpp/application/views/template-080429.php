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
<body class="" background="<?php echo base_url()?>application/images/logo/" > <!-- logotnb_blur.png backgroundExpertFootballBlue.gif -->
<div id="content">
<div class="header">
<table width="100%" border="0">
<tr>
<td width="281px" height="65px" valign="top"><a href="<?php echo base_url()?>index.php/my" title="TNB"><img border="0" src="<?php echo base_url()?>application/images/logo/logotnb_small.png" valign="bottom"></a></td>
<td align="left" valign="bottom"><i><font size="5" face="Arial"><strong>&nbsp;&nbsp;&nbsp;SISTEM MAKLUMAT KWKPP TNB [DEV SERVER]</strong></font></i></td>
<?php if(is_logged()):?>
<td align="right" valign="top"><a href="javascript:popwin('<?php echo base_url().'application/help/Outtask Proposal KWKPP.pdf'?>',800,600)" title="Bantuan">Bantuan</a></td>
<?php endif;?>
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
	['Pengurusan Pengguna ..', null, null,
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
	],
<?php endif;?>

<?php if(check_role(3)):?>//operator
		['Ahli ...', null, null,
			['Senarai Ahli', base_url_index+'crudmembers', null],
			['Tambah Ahli', base_url_index+'crudmembers/dataedit/create', null],
			['Status Semasa Ahli', null, null,
				<?php echo $statusAhli;?> 
			],
		],
		['Penama', null, null,
			['Senarai Penerima Faedah', base_url_index+'crudbeneficiaries', null],
			['Tambah Penama', base_url_index+'crudbeneficiaries/dataedit/create', null],
		],
		['Ahli dan Penama', base_url_index+'crudmemberbeneficiarie', null],
		['Kematian', null, null,
			['Senarai Kematian', base_url_index+'cruddecease', null],
			<?php if(date("d")<=$this->config->item('max_date_decease')):?>
			['Kematian Baru', base_url_index+'cruddecease/dataedit/create', null],
			<?php endif;?>
		],
		['Billing', null, null,
			['Senarai Bil (Pendaftaran, Penama)', base_url_index+'crudbilling', null],
			['Senarai Bil Sumbangan Kematian', base_url_index+'crudbilling_decease', null],	
			['Tambah Bil', base_url_index+'crudbilling/dataedit/create', null],
			['Bil Bulanan Sumbangan Kematian', base_url_index+'createmonthlycontri', null],
			
		],
		['Penyata Akaun', base_url_index+'crudaccount',null],
			//['Senarai Bil', base_url_index+'crudbilling', null],

		//['Bil Sumbangan Kematian', base_url_index+'createmonthlycontri', null,],
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
		['Tukar Kata Laluan *', base_url_index+'changepasswd', null],
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
    
<!-- <?php if(is_logged()):?><?php endif;?>  -->  
		<div class="right">
			<?php echo $content?>  
		</div>


   
	<div class="footer">
		<p><?php echo $this->config->item('system_version');?> | <a href="javascript:popwin('http://www.tnb.com.my',800,600)" title="TNB">Copyright &copy; 2008 Tenaga Nasional Berhad</a></p>
<!-- 		<a href="javascript:popwin('http://www.w3.org/WAI/WCAG1AA-Conformanc',800,600)" title="Explanation of Level Double-A Conformance">
		<img height="32" width="88" src="http://www.w3.org/WAI/wcag1AA-blue" alt="Level Double-A conformance icon, W3C-WAI Web Content Accessibility Guidelines 1.0"></a> -->
	</div>
	
</div>

</body>
</html>
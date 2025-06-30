<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>KWKPP - TNB</title> 
<script language="javascript" type="text/javascript" src="<?php echo base_url()?>application/rapyd/libraries/popwin/popwin.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>application/rapyd/libraries/jsmenu/menu.css">
<style type="text/css">


<?php echo $style?>


</style>

<?php echo $rapyd_head?>
</head>
<body class="">
<div id="content">

<h1><img src="<?php echo base_url()?>application/images/logo/logotnb.png" align="absmiddle"></h1>


	<?php if(is_logged()):?> 
<!-- <div cols="200,*" border="1">

<script language="JavaScript" src="<?php echo base_url()?>application/views/menu.js"></script>

<script language="JavaScript" src="<?php echo base_url()?>application/views/menu_items.js"></script>

<script language="JavaScript" src="<?php echo base_url()?>application/views/menu_tpl.js"></script>
<script language="JavaScript">

	new menu (MENU_ITEMS, MENU_POS);
</script>
</div> -->

	<div class="left"><div class="menubackground"><h2><?php echo anchor('auth','KWKPP') ?></h2>
		Anda telah log masuk sebagai:<br><div class="littletableheader" align="center"><?php echo strtoupper(get_user_data("user_name"));?>
	</div>
<!-- menu script itself. you should not modify this file -->
<script language="JavaScript" src="<?php echo base_url()?>application/rapyd/libraries/jsmenu/menu.js"></script>
<!-- items structure. used for all menus on this page, but may be personal 
<script language="JavaScript" src="<?php echo base_url()?>application/rapyd/libraries/jsmenu/menu_items.js"></script>-->
<script language="JavaScript">
var base_url_index='<?php echo base_url()?>index.php/';
var MENU_ITEMS = [
<?php if(check_role(1)):?>//root
	['Root', base_url_index+'auth', null],
<?php endif;?>

<?php if(check_role(2)):?>//admin
	['Administrator', null, null],
	['Aturan Sistem', null, null,
		['Hubungan',null, null,
			['Senarai Hubungan', base_url_index+'crudrelationship', null],
			['Tambah Hubungan', base_url_index+'crudrelationship/dataedit/create', null],
		],
		['Bank',null, null,
			['Senarai Bank', base_url_index+'crudbank', null],
			['Tambah Bank', base_url_index+'crudbank/dataedit/create', null],			
		],
		['Jenis Ahli',null, null,
			['Senarai Jenis Ahli', base_url_index+'crudmembertype', null],
			['Tambah Jenis Ahli', base_url_index+'crudmembertype/dataedit/create', null],			
		],
		['Negeri',null, null,
			['Senarai Negeri', base_url_index+'cruddeadstate', null],
			['Tambah Negeri', base_url_index+'cruddeadstate/dataedit/create', null],
		],
		['Cara Pembayaran',null, null,
			['Senarai Cara Pembayaran', base_url_index+'crudpaymentmode', null],
			['Tambah Cara Pembayaran', base_url_index+'crudpaymentmode/dataedit/create', null],			
		],
		['Pembayar',null, null,
			['Senarai Pembayar', base_url_index+'crudpayee', null],
			['Tambah Pembayar', base_url_index+'crudpayee/dataedit/create', null],			
		],
	],
<?php endif;?>

<?php if(check_role(3)):?>//operator
	['Operator', base_url_index+'auth', null],
	['Ahli', base_url_index+'auth', null],
	['Penama', base_url_index+'auth', null],
	['Kematian', base_url_index+'auth', null],
<?php endif;?>
	['Tukar Kata Laluan', base_url_index+'changepasswd', null],
	['Log Keluar', base_url_index+'auth/logout', null],
];
</script>
<!-- files with geometry and styles structures for coresponding menus -->
<script language="JavaScript" src="<?php echo base_url()?>application/rapyd/libraries/jsmenu/menu_tpl.js"></script>
<script language="JavaScript">
	<!--//
	// each menu gets two parameters (see demo files)
	// 1. items structure
	// 2. geometry structure
	new menu (MENU_ITEMS, MENU_POS);
	// also take a look at stylesheets loaded in header in order to set styles
	//-->
</script>	  
		  <?php if(check_role(1)):?>
			<br><div class="mainheader">Root:<font size="1"></font></div>
		  <?php endif;?>

		  <?php if(check_role(2)):?> 
			<br><div class="littletableheader">Pengurusan Pengguna:<font size="1"></font></div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudusers','Senarai Pengguna') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudusers/dataedit/gfid/bcaehadcbad/create','Tambah Pengguna') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('','Tukar Kata Laluan') ?>&nbsp;](todo)</div>

			<br><div class="littletableheader">Aturan Sistem:<font size="1"></font></div>
			<div class="littletableheader">&#9679 Hubungan</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudrelationship','Senarai Hubungan') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudrelationship/dataedit/gfid/bcaehadcbad/create','Tambah Hubungan') ?>&nbsp;]</div>
			<div class="littletableheader">&#9679 Bank</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudbank','Senarai Bank') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudbank/dataedit/gfid/bcaehadcbad/create','Tambah Bank') ?>&nbsp;]</div>
			<div class="littletableheader">&#9679 Jenis Ahli</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudmembertype','Senarai Jenis Ahli') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudmembertype/dataedit/gfid/bcaehadcbad/create','Tambah Jenis Ahli') ?>&nbsp;]</div>
			<div class="littletableheader">&#9679 Negeri</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('cruddeadstate','Senarai Negeri') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('cruddeadstate/dataedit/gfid/bcaehadcbad/create','Tambah Negeri') ?>&nbsp;]</div>
			<div class="littletableheader">&#9679 Cara Pembayaran</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudpaymentmode','Senarai Cara Pembayaran') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudpaymentmode/dataedit/gfid/bcaehadcbad/create','Tambah Cara Pembayaran') ?>&nbsp;]</div>
			<div class="littletableheader">&#9679 Pembayar</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudpayee','Senarai Pembayar') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudpayee/dataedit/gfid/bcaehadcbad/create','Tambah Pembayar') ?>&nbsp;]</div>
		  <?php endif;?>

		  <?php if(check_role(3)):?> 
			<br><div class="littletableheader">Ahli KWKPP:<font size="1"> 3</font></div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudmembers','Senarai Ahli') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudmembers/dataedit/gfid/bcaehadcbad/create','Tambah Ahli') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('printmembers','Cetak Sijil Ahli') ?>&nbsp;](todo)</div>
			<br><div class="littletableheader">Daftar Kematian:<font size="1"> 3</font></div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('cruddecease','Senarai Kematian') ?>&nbsp;]</div>

			<br><div class="littletableheader">Penama:<font size="1"> 3</font></div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudbeneficiaries','Penerima Faedah') ?>&nbsp;]</div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('crudmemberbeneficiarie','Ahli dan Penerima Faedah') ?>&nbsp;]</div>
		  <?php endif;?>

		  <?php if(check_role(4)):?> 
			<br><div class="littletableheader">Pilihan<font size="1"> 4</font></div>
			<div>&nbsp;&nbsp;&nbsp;[&nbsp;<?php echo anchor('changepasswd','Tukar Kata Laluan') ?>&nbsp;]</div>
			<!-- '.strtoupper(get_user_data("user_name")).' -->
		  <?php endif;?>
	</div></div>
	<?php endif;?>
    
    <div class="targetbackground">
		<div class="right">
			<?php echo $content?>  
		</div>
	</div>
   
	<div class="footer">
		<p>rendered in {elapsed_time} seconds (development only) | <a href="javascript:popwin('http://www.erp21.com.my',800,600)" title="ERP21">Copyright &copy; 2008 ERP21 Sdn Bhd</a> | Best Viewed With Internet Explorer </p>
<a href="javascript:popwin('http://www.w3.org/WAI/WCAG1AA-Conformanc',800,600)"
      title="Explanation of Level Double-A Conformance">
  <img height="32" width="88" 
          src="http://www.w3.org/WAI/wcag1AA-blue"
          alt="Level Double-A conformance icon, 
          W3C-WAI Web Content Accessibility Guidelines 1.0"></a>
	</div>
	
</div>

<!--  "javascript:popwin('http://localhost/kwkpp',25,50)"<div class="line"></div> -->      <!-- <div class="code"><?php echo $code?></div> -->
<!--  | ver <?php echo RAPYD_VERSION?> | -->
<!-- <h1>Tenaga Nasional Berhad</h1> --><!-- <?php $this->load->view('view_logo', $content); ?> <div>-->
 	  <!-- <div style="float:left"> <img src="../images/logo/logotnb.png" align="absmiddle">
       
      </div> <div>&lt; <a href="<?php echo base_url()?>user_guide/">User Guide</a></div> -->

	  
<!--       <div style="float:left; width:250px">
      </div>
      <div style="float:right; width150px;">
       current language: <?php echo $this->config->item("language")?>&nbsp;<?php echo $language_links?>
      </div>
      <div style="float:right; width150px;margin-right:20px">
       current theme: <?php echo $theme?>
       <?php echo anchor("rapyd/utils/theme/default","default")?>
       <?php echo anchor("rapyd/utils/theme/clean","clean")?>
       <?php echo anchor("rapyd/utils/theme/black","black")?>
      </div>
      <div style="clear:both"></div>
    </div>
  
		<div class="line"></div> -->

<!--	<div id="content">
  
  
     <h1>Rapyd Samples</h1> 
		<div>
       <div style="float:left; width:230px">
        Rapyd <?php echo RAPYD_VERSION?> | CI 1.5.4
      </div> 
      <div style="float:left; width:250px">
      </div>
      <div style="float:right; width150px;">
       current language: <?php echo $this->config->item("language")?>&nbsp;<?php echo $language_links?>
      </div>
      <div style="float:right; width150px;margin-right:20px">
       current theme: <?php echo $theme?>
       <?php echo anchor("rapyd/utils/theme/default","default")?>
       <?php echo anchor("rapyd/utils/theme/clean","clean")?>
       <?php echo anchor("rapyd/utils/theme/black","black")?>
      </div>
      <div style="clear:both"></div>
    </div>
  
		<div class="line"></div>-->


 <!--    <div class="left">

      <div>&lt; <?php echo anchor("","Welcome")?></div>
      <div>&lt; <a href="<?php echo base_url()?>user_guide/">User Guide</a></div>
      <div>&lt; <a href="<?php echo base_url()?>rapyd_guide/">Rapyd Guide</a></div>
      
      <br />
      <div><?php echo anchor("rapyd/samples/index","Index")?></div>

      <div class="line"></div>

      <h3>data presentation</h3>
      <div><?php echo anchor("rapyd/samples/dataset","DataSet")?></div>
      <div><?php echo anchor("rapyd/samples/datatable","DataTable")?></div>
      <div><?php echo anchor("rapyd/samples/datagrid","DataGrid")?></div>
      
      <h3>data editing</h3>
      <div><?php echo anchor("rapyd/datam/dataform","DataForm")?></div>
      <div><?php echo anchor("rapyd/crudsamples/filteredgrid","DataFilter + DataGrid")?></div>
      <br />
      <div><?php echo anchor("rapyd/crudsamples/dataedit/show/1","DataEdit")?> + one-to-many</div>
      <div><?php echo anchor("rapyd/supercrud/dataedit/show/1","DataEdit")?> + many-to-many</div>
      <br />
      <div><?php echo anchor("rapyd/crudworkflow/gridedit/osp/0","DataGrid + DataEdit")?></div>

      
      <h3>prototype &amp; ajax</h3>
      <div><?php echo anchor("rapyd/ajaxsamples/ajaxsearch","DataFilter + Ajax")?></div>
      
      <div class="line"></div>
      
      <h3>orm &amp; dataobject</h3>
      <div><?php echo anchor("rapyd/datam/dataobject","DataObject")?> &amp; rel support</div>
      <div><?php echo anchor("rapyd/datam/prepostprocess","DataObject")?> &amp; callbacks</div>

      <div class="line"></div>
      
      <h3>auth class &amp; helper</h3>
      <div><?php echo anchor("rapyd/auth","Auth")?> login, logged, logout</div>

      <div class="line"></div>
      
      <h3>lang class &amp; helper</h3>
      <div><?php echo anchor("rapyd/lang","Lang")?> switch &amp; browser detect</div>
      
      <div class="line"></div>
      
      <h3>Support</h3>
      <div><a href="http://www.rapyd.com">Rapyd Website</a></div>
      <div><a href="http://www.rapyd.com/main/support">Donate</a></div>

      <div class="line"></div>

      <div><?php //anchor("rapyd/tests","tests (dev)")?></div>
      
      <div class="line"></div>
      
      note: from DataGrid sample a <?php echo anchor("rapyd/samples/index","test database")?> is required<br />

      <div class="line"></div> 
    </div> --> 
</body>
</html>
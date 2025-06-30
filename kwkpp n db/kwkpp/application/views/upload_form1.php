<div>
<!-- <h3>Kumpulan Wang Khairat Pekerja & Pesara TNB</h3> -->
<!-- <h3><?php echo $this->config->item('system_name'); ?></h3> class=\"select\" -->

<?php echo form_open_multipart('importregfee/do_read');?>
<p class="mainheader">Import Data dari Fail Bayaran Pendaftaran Ahli</p>
<?php
echo form_open_multipart('importregfee/do_read');
echo "<table>";
echo "<tr><td class=\"littletableheader\" style=\"width:120px;\">Sila pilih fail</td>";
echo "<td><input type=\"file\" name=\"userfile\" size=\"20\" /></td></tr>";
echo "</table>";
?>
<hr noshade size="1%" color="#ECECEC">
<?php echo $error; ?>
<input type="submit" value="Import" /><br /><br />
<fieldset class="alert">
<legend class="alert">Perhatian:</legend>
<UL>
<li>Maksimum saiz fail yang boleh diimport adalah <?php echo ini_get('upload_max_filesize') ?> (megabytes)
<li>Format fail yang hendak diimport adalah format .csv (comma-separated values)
<li>Contoh data-format yang betul:
<img border="0" src="<?php echo base_url()?>application/images/data-format/dataFormat-Import_Bayaran_Pendaftaran.png" valign="bottom">
</UL>
</fieldset>

</form>

</div>
<div>
<!-- <h3><?php echo $this->config->item('system_name'); ?></h3> -->
<p class="mainheader">Buat Bil Bulanan Sumbangan Kematian</p>
<?php
echo form_open('createmonthlycontri/insert_contri');
echo "<table>";
echo "<tr><td class=\"littletableheader\" style=\"width:120px;\">Bulan</td><td>".form_dropdown('bulan', $bulan,date("m")-1, 'class="select"')."</td></tr>";
echo "<tr><td class=\"littletableheader\">Tahun</td><td>".form_dropdown('tahun', $tahun,date("Y"), 'class="select"')."</td></tr>";
echo "</table>";
?>
<hr noshade size="1%" color="#ECECEC"><input type="submit" value="Buat Sumbangan Kematian" /><br /><br />
<fieldset class="alert">
<legend class="alert">Perhatian:</legend>
<UL>
<li>Bil Sumbangan Kematian adalah hanya untuk Ahli Biasa dan berstatus Aktif sahaja.
<li>Bil Sumbangan Kematian hendaklah dibuat sekurang-kurangnya sekali setiap bulan walaupun tiada kematian untuk memastikan setiap baki bawa hadapan ahli adalah tepat.
<li>Sila pastikan <?php echo anchor('crudbilling_decease','Bil Sumbangan Kematian'); ?> bulan sebelumnya telah dibuat untuk mendapatkan baki bawa hadapan 
</UL>
</fieldset>
</form>
</div>
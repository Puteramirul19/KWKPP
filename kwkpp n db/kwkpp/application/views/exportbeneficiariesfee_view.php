<div>
<!-- <h3><?php echo $this->config->item('system_name'); ?></h3> -->
<p class="mainheader">Senarai Bayaran Tukar Penama Diterima</p>

<?php

echo form_open('exportbeneficiariesfee/mysql_excel');
echo "<table>";

echo "<tr><td class=\"littletableheader\" style=\"width:120px;\">Bulan</td><td>".form_dropdown('bulan',$bulan,date("m"), 'class="select"')."</td></tr>";
echo "<tr><td class=\"littletableheader\">Tahun</td><td>".form_dropdown('tahun',$tahun,date("Y"), 'class="select"')."</td></tr>";
echo "<tr><td class=\"littletableheader\">Nama Ahli</td><td>".form_dropdown('ahliKhairatID',$ahliKhairatID,'','class="select"')."</td></tr>";

echo "</table>";


?>

<br />
<input type="submit" value="Paparkan" />

</form>

</div>

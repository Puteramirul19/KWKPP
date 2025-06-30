<div>
<!-- <h3><?php echo $this->config->item('system_name'); ?></h3> -->
<?php echo $back;?>
<p class="mainheader">Jumlah Kematian</p>
<?php
echo "<table>";
echo "<tr><td class=\"littletableheader\" style=\"width:120px;\">Bulan</td><td>".$bulan."</td></tr>";
echo "<tr><td class=\"littletableheader\">Tahun</td><td>".$tahun."</td></tr>";
echo "<tr><td class=\"littletableheader\">Jumlah Kematian</td><td>".$no_decease."</td></tr>";
echo "</table>";
?>
<p class="mainheader">Senarai Bil Sumbangan Kematian Bagi Bulan: <?php echo $bulan ?> Tahun: <?php echo $tahun ?></p>
<?php echo $insert_row; echo "<br>".$back;?>
</div>
<div>
<p class="mainheader">Penyata Akaun</p>
<?php
echo form_open('crudaccount/account');
echo "<table>";
echo "<tr><td class=\"littletableheader\" style=\"width:120px;\">Nama Ahli (No.Pekerja)</td><td>".form_dropdown('ahliKhairatID',$ahliKhairatID,$selected_ahliKhairatID,'class="select"')."</td></tr>";
echo "<tr><td class=\"littletableheader\">Tahun</td><td>".form_dropdown('tahun',$tahun,'class="select"')."</td></tr>";
echo "</table>";
?>
<br />
<input name="submit" type="submit" value="Paparkan" />
</form>

</div>


<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename, $paper_size, $orientation)
{
require_once("dompdf/dompdf_config.inc.php");

$dompdf = new DOMPDF();
$dompdf->load_html($html);
$dompdf->set_paper($paper_size, $orientation);
$dompdf->render();
$dompdf->stream($filename.".pdf");
}
?>
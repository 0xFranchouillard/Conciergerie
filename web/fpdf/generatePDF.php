<?php
session_start();
require_once('fpdf.php');
$pdf=new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',18);
$pdf->Cell(40,10,'This is FPDF Demo by Helpfolder');
$pdf->Output('D', $_SESSION['nameFileEstimate'],true);
?>

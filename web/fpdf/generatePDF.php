<?php
session_start();
require_once('../lang/'.$_SESSION['lang'].'.php');
require_once('fpdf.php');

$pdf=new FPDF();
$pdf->AddPage();
$pdf->Image('../Pictures/Separateur4.png',5,5,200,10);
$pdf->Image('../Pictures/Separateur4.png',5,280,200,10);
$pdf->Image('../Pictures/Logo_LuxeryService.png',170,20,25,25);
$pdf->SetFont('Arial', '',25);
$pdf->SetTextColor(53,52,67);
$pdf->Ln(15);
if($_SESSION['estimate'] == true) {
    $pdf->Cell(25,15,strtoupper(_ESTIMATE), 0, 0);
} else {
    $pdf->Cell(25,15,strtoupper(_BILL), 0, 0);
}

$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(52,58,64);
$pdf->Ln(20);
$pdf->Cell(25,15,utf8_decode('Luxery Service'), 0, 0);
$pdf->Cell(160,15,utf8_decode($_SESSION['billDate']), 0, 0, 'R');
$pdf->Line(170,56,195,56);
$pdf->Ln(7);
$pdf->Cell(25,15,utf8_decode(_STREETLUXERYSERVICE), 0, 0);
$pdf->Ln(7);
$pdf->Cell(25,15,utf8_decode('75012 Paris'), 0, 0);
$pdf->Cell(153,15,utf8_decode('N° ' . $_SESSION['billID']), 0, 0,'R');
$pdf->Line(170,70,195,70);
$pdf->Ln(7);
$pdf->Cell(25,15,utf8_decode('ls_paris@luxeryservice.com'), 0, 0);

$pdf->Ln(20);
$pdf->Cell(25,15,utf8_decode(_ADDRESSEE.' :'), 0, 0);
$pdf->Line(10,97,70,97);
$pdf->SetFont('Arial','',10);
$pdf->Ln(8);
$pdf->Cell(25,15,utf8_decode(_LASTNAME.' : ' . $_SESSION['lastNameBill']), 0, 0);
$pdf->Ln(6);
$pdf->Cell(25,15,utf8_decode(_FIRSTNAME.' : ' . $_SESSION['firstNameBill']), 0, 0);
$pdf->Ln(6);
$pdf->Cell(25,15,utf8_decode(_ADDRESS.' : ' . $_SESSION['addressBill']), 0, 0);
$pdf->Ln(6);
$pdf->Cell(25,15,utf8_decode(_CITY.' : ' . $_SESSION['cityBill']), 0, 0);
$pdf->Ln(6);
$pdf->Cell(25,15,utf8_decode(_REGISTRATIONDATE.' : ' . $_SESSION['registrationDateBill']), 0, 0);

$pdf->Ln(20);
$pdf->SetFont('Arial','',12);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(42,45,46);
$pdf->Cell(48,10,utf8_decode(_SERVICE), 0, 0, 'C',true);
$pdf->Cell(47,10,utf8_decode(_QUANTITY), 0, 0,'C', true);
$pdf->Cell(48,10,utf8_decode(_UNITPRICE), 0, 0,'C', true);
$pdf->Cell(47,10,utf8_decode(_TOTAL), 0, 1,'C', true);

$pdf->SetTextColor(52,58,64);
$color = 0;
for ($i = 0; $i < count($_SESSION['nameServiceBill']); $i++) {
    if($color%2 == 1) {
        $pdf->SetFillColor(223, 223, 223);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->Cell(48,10,utf8_decode($_SESSION['nameServiceBill'][$i]), 1, 0, 'C',true);
    $pdf->Cell(47,10,utf8_decode($_SESSION['nbTakeBill'][$i]), 1, 0, 'C',true);
    if(isset($_SESSION['minimumTypeBill'][$i]) && !empty($_SESSION['minimumTypeBill'][$i]) && $_SESSION['nbTakeBill'][$i] >= $_SESSION['minimumTypeBill'][$i]) {
        $pdf->Cell(48,10,utf8_decode($_SESSION['priceRecurrentServiceBill'][$i]), 1, 0, 'C',true);
        $pdf->Cell(47,10,utf8_decode(floatval($_SESSION['priceRecurrentServiceBill'][$i])*intval($_SESSION['nbTakeBill'][$i])), 1, 1, 'R',true);
    } else {
        $pdf->Cell(48,10,utf8_decode($_SESSION['priceServiceBill'][$i]), 1, 0, 'C',true);
        $pdf->Cell(47,10,utf8_decode(floatval($_SESSION['priceServiceBill'][$i])*intval($_SESSION['nbTakeBill'][$i])), 1, 1, 'R',true);
    }
    $color++;
}

if($_SESSION['estimate'] == true) {
    $pdf->Cell(96, 10,  utf8_decode(_VALIDESTIMATE.' '.$_SESSION['validityDate']));
    $pdf->Cell(48,10,utf8_decode(_TOTALESTIMATE.' : '),0,0,'R');
} else {
    $pdf->Cell(96, 10);
    $pdf->Cell(48,10,utf8_decode(_TOTALBILL.' : '),0,0,'R');
}
$pdf->Cell(47,10,utf8_decode($_SESSION['totalPriceBill']).chr(128),0,0,'R');

$pdf->Output('D', $_SESSION['nameFileBill'],true);
$_SESSION['serviceIDCart'] = [];
$_SESSION['nbTakeCart'] = [];
$_SESSION['nameServiceBill'] = [];
$_SESSION['nbTakeBill'] = [];
$_SESSION['priceServiceBill'] = [];
$_SESSION['priceRecurrentServiceBill'] = [];
$_SESSION['minimumTypeBill'] = [];
$_SESSION['totalPriceBill'] = [];
$_SESSION['validityDate'] = [];
$_SESSION['billDate'] = [];
$_SESSION['billID'] = [];
$_SESSION['lastNameBill'] = [];
$_SESSION['firstNameBill'] = [];
$_SESSION['cityBill'] = [];
$_SESSION['addressBill'] = [];
$_SESSION['registrationDateBill'] = [];
$_SESSION['nameFileBill'] = [];
?>

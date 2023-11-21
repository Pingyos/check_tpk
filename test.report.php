<?php
require('fpdf186/fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        $this->AddFont('THSarabunPSK', '', 'THSarabunPSK.php');
        $this->SetFont('THSarabunPSK', '', 15);

        $this->Cell(80);
        $this->Cell(30, 10, iconv('UTF-8', 'TIS-620', 'ทดสอบ'), 1, 0, 'C');
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->AddFont('THSarabunPSK', '', 'THSarabunPSK.php');
        $this->SetFont('THSarabunPSK', '', 8);

        $this->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'หน้า ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
for ($i = 1; $i <= 40; $i++)
    $pdf->Cell(0, 10, 'Printing line number ' . $i, 0, 1);
$pdf->Output();

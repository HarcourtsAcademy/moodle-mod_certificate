<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from view.php
}

$pdf = new TCPDF($certificate->orientation, 'mm', 'A4', true, 'UTF-8', false);

$pdf->SetTitle($certificate->name);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(false, 0);
$pdf->AddPage();

// Define variables
// Landscape
if ($certificate->orientation == 'L') {
    $x = 10;
    $y = 30;
    $brdrx = 0;
    $brdry = 0;
    $brdrw = 297;
    $brdrh = 210;
    $codey = 207.5;
    $namey = 213;
} else { //Portrait
    $x = 60;
    $y = 40;
    $brdrx = 0;
    $brdry = 0;
    $brdrw = 210;
    $brdrh = 297;
    $codey = 207.5;
    $namey = 213;
}

// Add background
certificate_print_image($pdf, $certificate, CERT_IMAGE_BORDER, $brdrx, $brdry, $brdrw, $brdrh);

// Add text
$pdf->SetTextColor(0, 0, 0);
certificate_print_text($pdf, $x, $codey, 'L', 'Helvetica', 'B', 14, certificate_get_code($certificate, $certrecord));
certificate_print_text($pdf, $x, $namey, 'L', 'Helvetica', 'B', 14, fullname($USER));

?>
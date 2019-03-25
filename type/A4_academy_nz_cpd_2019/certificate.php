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
    $sealx = 230;
    $sealy = 150;
    $sigx = 47;
    $sigy = 155;
    $custx = $x + 98.5;
    $custy = $y + 150;
    $wmarkx = 40;
    $wmarky = 31;
    $wmarkw = 212;
    $wmarkh = 148;
    $brdrx = 0;
    $brdry = 0;
    $brdrw = 297;
    $brdrh = 210;
} else { //Portrait
    $x = 10;
    $y = 40;
    $sealx = 150;
    $sealy = 220;
    $sigx = 30;
    $sigy = 230;
    $custx = $x + 98.5;
    $custy = 230;
    $wmarkx = 26;
    $wmarky = 58;
    $wmarkw = 158;
    $wmarkh = 170;
    $brdrx = 0;
    $brdry = 0;
    $brdrw = 210;
    $brdrh = 297;
}

// Add images and lines
certificate_print_image($pdf, $certificate, CERT_IMAGE_BORDER, $brdrx, $brdry, $brdrw, $brdrh);
certificate_draw_frame($pdf, $certificate);
// Set alpha to semi-transparency
$pdf->SetAlpha(0.2);
certificate_print_image($pdf, $certificate, CERT_IMAGE_WATERMARK, $wmarkx, $wmarky, $wmarkw, $wmarkh);
$pdf->SetAlpha(1);
certificate_print_image($pdf, $certificate, CERT_IMAGE_SEAL, $sealx, $sealy, '', '');
certificate_print_image($pdf, $certificate, CERT_IMAGE_SIGNATURE, $sigx, $sigy, '', '');

// Add text
$pdf->SetTextColor(108, 163, 80);
certificate_print_text($pdf, $x, $y + 22, 'C', 'Helvetica', 'B', 36,  "Real estate continuing professional development");
$pdf->SetTextColor(0, 0, 0);
certificate_print_text($pdf, $x, $y + 60, 'C', 'Helvetica', null, 30,  "Verifiable " . $certificate->customtext);
certificate_print_text($pdf, $x, $y + 80, 'C', 'Helvetica', null, 20,  "This is to certify that on");
certificate_print_text($pdf, $x, $y + 90, 'C', 'Helvetica', null, 20,  certificate_get_date($certificate, $certrecord, $course));
certificate_print_text($pdf, $x, $y + 100, 'C', 'Helvetica', 'B', 30, fullname($USER));
certificate_print_text($pdf, $x, $y + 115, 'C', 'Helvetica', null, 20, $USER->profile['licenseenumber']);
if ($certificate->printhours) {
    $certificatesummary = "Completed ".$certificate->printhours." hours verifiable continuing education (".$course->shortname.") as required "
            . "under Section 15 of the Real Estate Agents Act 2008";
    certificate_print_text($pdf, $x + 40, $y + 140, 'C', 'Helvetica', null, 14, $certificatesummary, 200);
}
certificate_print_text($pdf, $x, $y + 160, 'C', 'Helvetica', null, 9, "Certificate serial number: ".certificate_get_code($certificate, $certrecord));

?>
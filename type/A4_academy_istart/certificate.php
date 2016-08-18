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
$pdf->SetTextColor(0, 0, 0);
certificate_print_text($pdf, $x + 90, $y + 24, 'L', 'Helvetica', null, 30, fullname($USER));
certificate_print_text($pdf, $x + 101, $y + 132, 'L', 'Helvetica', null, 14,  certificate_get_date($certificate, $certrecord, $course));
certificate_print_text($pdf, $x + 90, $y + 102, 'L', 'Helvetica', null, 10, certificate_get_grade($certificate, $course));
certificate_print_text($pdf, $x + 90, $y + 112, 'L', 'Helvetica', null, 10, certificate_get_outcome($certificate, $course));
if ($certificate->printhours) {
    certificate_print_text($pdf, $x + 89.5, $y + 145, 'L', 'Helvetica', null, 14, get_string('credithours', 'certificate') . ': ' . $certificate->printhours);
}
certificate_print_text($pdf, $x + 116, $y + 140.1, 'L', 'Helvetica', null, 14, certificate_get_code($certificate, $certrecord));

$i = 0;
if ($certificate->printteacher) {
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    if ($teachers = get_users_by_capability($context, 'mod/certificate:printteacher', '', $sort = 'u.lastname ASC', '', '', '', '', false)) {
        foreach ($teachers as $teacher) {
            $i++;
            certificate_print_text($pdf, $sigx, $sigy + ($i * 4), 'L', 'freeserif', null, 12, fullname($teacher));
        }
    }
}

certificate_print_text($pdf, $custx, $custy, 'L', 'Helvetica', null, 14, $certificate->customtext);
?>

<?php
// Include TCPDF library for PDF generation
require_once '../vendor/autoload.php';

use TCPDF;

function generateCertificate($name, $student_id) {
    try {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_PAGE_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document properties
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('KPR College');
        $pdf->SetTitle('Certificate of Participation');

        // Set margins
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();

        // Set colors
        $pdf->SetDrawColor(212, 175, 55); // Gold border
        $pdf->SetFillColor(255, 255, 255); // White background

        // Draw decorative border
        $pdf->SetLineWidth(0.8);
        $pdf->Rect(5, 5, 200, 287, 'D');

        // Header background
        $pdf->SetFillColor(0, 31, 63); // Dark blue
        $pdf->Rect(5, 5, 200, 35, 'F');

        // College name and logo
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(212, 175, 55); // Gold
        $pdf->SetXY(15, 12);
        $pdf->Cell(0, 8, 'KPR COLLEGE', 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetXY(15, 20);
        $pdf->Cell(0, 6, 'of Arts Science and Research', 0, 1, 'C');

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY(15, 26);
        $pdf->Cell(0, 5, 'Arasur, Coimbatore', 0, 1, 'C');

        // Main content - Certificate title
        $pdf->SetFont('helvetica', 'B', 36);
        $pdf->SetTextColor(0, 31, 63); // Dark blue
        $pdf->SetXY(15, 50);
        $pdf->Cell(0, 15, 'CERTIFICATE', 0, 1, 'C');

        // Subtitle
        $pdf->SetFont('helvetica', '', 16);
        $pdf->SetTextColor(212, 175, 55); // Gold
        $pdf->SetXY(15, 65);
        $pdf->Cell(0, 8, 'OF PARTICIPATION', 0, 1, 'C');

        // Decorative line
        $pdf->SetDrawColor(212, 175, 55);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(40, 76, 170, 76);

        // Certificate text
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(15, 85);
        $pdf->Cell(0, 8, 'This is to certify that', 0, 1, 'C');

        // Student name (in a box)
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(0, 31, 63);
        $pdf->SetXY(15, 100);
        $pdf->Cell(0, 12, strtoupper($name), 0, 1, 'C', false);

        // Decorative line under name
        $pdf->SetDrawColor(212, 175, 55);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(40, 113, 170, 113);

        // Certificate body text
        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetXY(15, 120);
        $pdf->MultiCell(0, 6, 'has participated in the event organized by\n\nDEPARTMENT OF COMPUTER APPLICATIONS\n\nat KPR College of Arts, Science and Research.', 0, 'C');

        // Appreciation text
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(15, 160);
        $pdf->MultiCell(0, 5, 'We appreciate your enthusiasm, active participation and commendable effort.', 0, 'C');

        // Date
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(15, 185);
        $pdf->Cell(0, 6, 'Date: ' . date('d/m/Y'), 0, 1, 'C');

        // Signature line
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(50, 210, 100, 210);

        $pdf->SetXY(15, 212);
        $pdf->Cell(0, 6, 'HOD - Department of Computer Applications', 0, 1, 'C');

        // Certificate ID at bottom
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor(150, 150, 150);
        $pdf->SetXY(15, 270);
        $pdf->Cell(0, 4, 'Certificate ID: KPRCERT' . $student_id . date('Ymd'), 0, 1, 'C');

        // Create certificates directory if not exists
        if (!is_dir('../certificates')) {
            mkdir('../certificates', 0755, true);
        }

        // Save PDF
        $filename = 'Certificate_' . $student_id . '_' . time() . '.pdf';
        $filepath = '../certificates/' . $filename;

        $pdf->Output($filepath, 'F');

        return $filepath;
    } catch (Exception $e) {
        error_log('Certificate generation error: ' . $e->getMessage());
        return false;
    }
}
?>

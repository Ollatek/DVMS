<?php

require_once('C:/xampp/htdocs/DVMS-PHP/assets/libraries/TCPDF-main/tcpdf.php');
include('assets/inc/config.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['pat_number'])) {
    die("Unauthorized access!");
}

$pat_number = $_SESSION['pat_number'];

// Fetch patient and lab details including profile image
$stmt = $mysqli->prepare("SELECT p.pat_fname, p.pat_lname, p.profile_picture, l.lab_date_rec, l.lab_pat_tests, l.lab_pat_results 
                         FROM his_patients p 
                         LEFT JOIN his_laboratory l ON p.pat_number = l.lab_pat_number 
                         WHERE p.pat_number = ?");
$stmt->bind_param("s", $pat_number);
$stmt->execute();
$result = $stmt->get_result();
$lab_data = $result->fetch_assoc();

// ✅ Check if data was found
if (!$lab_data || empty($lab_data['lab_date_rec'])) {
    die("⚠ No lab records found for this patient!");
}

// Create PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('DVMS Lab System');
$pdf->SetTitle('Lab Report');
$pdf->SetHeaderData('', 0, 'Lab Report - DVMS', "Generated on: " . date("Y-m-d H:i:s"));
$pdf->SetMargins(15, 15, 15);
$pdf->SetAutoPageBreak(true, 10);
$pdf->AddPage();

// ✅ Add Watermark
$watermarkImage = 'assets/images/dvms-dark2.jpg'; // Uploaded file path
$pageWidth = $pdf->GetPageWidth();
$pageHeight = $pdf->GetPageHeight();
$watermarkWidth = 120;  // Adjust size as needed
$watermarkHeight = 50;  // Adjust size as needed
$xWatermark = ($pageWidth - $watermarkWidth) / 2;
$yWatermark = ($pageHeight - $watermarkHeight) / 2;

// Set transparency for watermark
$pdf->SetAlpha(0.2);
$pdf->Image($watermarkImage, $xWatermark, $yWatermark, $watermarkWidth, $watermarkHeight, '', '', '', false, 300, '', false, false, 0, false, false, false);
$pdf->SetAlpha(1); // Reset transparency

// ✅ Add Patient Image
$profile_image = $lab_data['profile_picture'] ?? '';  // Corrected column name
$imagePath = 'C:/xampp/htdocs/DVMS-PHP/backend/User/uploads/profile_pics/' . $profile_image;

if (!empty($profile_image) && file_exists($imagePath)) {
    $imageWidth = 50;
    $imageHeight = 50;
    $xPosition = ($pageWidth - $imageWidth) / 2;
    $yPosition = 30;
    $pdf->Image($imagePath, $xPosition, $yPosition, $imageWidth, $imageHeight, '', '', '', false, 300, '', false, false, 0, false, false, false);
} else {
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->Cell(0, 10, 'No Profile Image Available', 0, 1, 'C');
}

// Move text below image
$pdf->Ln(60);

// Patient Details
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, "Patient: " . ($lab_data['pat_fname'] ?? 'N/A') . " " . ($lab_data['pat_lname'] ?? 'N/A'), 0, 1);
$pdf->Cell(0, 10, "Date: " . ($lab_data['lab_date_rec'] ?? 'N/A'), 0, 1);
$pdf->Ln(5);

// Test Details
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Test Performed:', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML('<strong>' . ($lab_data['lab_pat_tests'] ?? 'N/A') . '</strong>', true, false, true, false, '');
$pdf->Ln(5);

// Results
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Results:', 0, 1);
$pdf->SetFont('helvetica', '', 12);
$pdf->writeHTML('<strong>' . ($lab_data['lab_pat_results'] ?? 'N/A') . '</strong>', true, false, true, false, '');
$pdf->Ln(10);

// Footer
$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 10, 'Doctor’s Signature: ____________________', 0, 1);

// Output PDF
$pdf->Output('Lab_Report.pdf', 'D');

?>

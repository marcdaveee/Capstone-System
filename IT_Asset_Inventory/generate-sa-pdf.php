<?php

// Authenticate if user is logged in
include ("../auth_session.php");

    
if($_SESSION["role"] != "admin"){
    header("Location: /Capstone_System/404.php");
    exit();
}
//============================================================+
// File name   : example_004.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 004 for TCPDF class
//               Cell stretching
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Cell stretching
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('../TCPDF-main/TCPDF-main/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('IT Department');
$pdf->SetTitle('IT Software Asset Report');
$pdf->SetSubject('IT Inventory');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


// ---------------------------------------------------------

// Filter information
$softwareType =  "";

$softwareType = $_POST["software-type"];

// query from database

require("../config/db_config.php");
if($softwareType == "all"){
    $sql = "SELECT * FROM software_asset ORDER BY software_type";
    $result = mysqli_query($conn, $sql);
    $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
}
else{
    $sql = "SELECT * FROM software_asset WHERE software_type = '$softwareType' ORDER BY software_type";
    $result = mysqli_query($conn, $sql);
    $assets = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
}

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

    //Page header
    public function Header() {        
        // Set font
        $image_file = "./img/lgu-saq-logo.jpg";        
        $this->Image($image_file, 0, 0, 15, 25, "jpg");
        // Set font
        $this->SetFont('helvetica', 'B', 18);
        // Title          
        $this->Cell(0, 15, 'LGU of San Antonio Quezon', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(15);
        $this->SetFont('helvetica', 'I', 12);
        $txt = "IT Software Assets Report as of ". date("M. d, Y");
        $this->Cell(0, 15, $txt, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(10);
        $this->SetFont('helvetica', '', 12);
        $txt = "Generated By: Admin";        
        $this->Cell(0, 12, $txt, 0, 0, 'L', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        // $txt = "Date Generated: ". date("m-d-Y");
        // $this->Cell(0, 25, $txt, '', 0, 'L', 0, '', 0, false, 'M', 'M');       
        $this->Cell(0, 15, "", 0, 0, 'L', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER); 

// set font
$pdf->SetFont('times', '', 11);

// add a page
$pdf->AddPage('L');

$count = 1;

$header = array('Product ID', 'Software Name', 'Type', 'No. of Installation', 'Validity', 'Status');




// Colored table 

// Colors, line width and bold font
$pdf->SetFillColor(120, 0, 0);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(128, 0, 0);
$pdf->SetLineWidth(0.3);
$pdf->SetFont('', 'B');
// Header
$w = array(40, 45, 45, 40, 35, 60);

$num_headers = count($header);
$pdf->SetFont('helvetica', 'B', 10);
for($i = 0; $i < $num_headers; ++$i) {
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
}
$pdf->Ln();
// Color and font restoration
$pdf->SetFillColor(224, 235, 255);
$pdf->SetTextColor(0);
$pdf->SetFont('');
$pdf->SetFont('helvetica', '', 10);
// Data
$fill = 0;
foreach($assets as $asset) {
    $pdf->Cell($w[0], 12, $asset['product_id'], 'LR', 0, 'L', $fill, '', true);
    $pdf->Cell($w[1], 12, $asset['software'], 'LR', 0, 'C', $fill, '', true);
    $pdf->Cell($w[2], 12, $asset['software_type'], 'LR', 0, 'C', $fill, '', true);
    $pdf->Cell($w[3], 12, $asset['no_of_installation'], 'LR', 0, 'C', $fill, '', true);
    $pdf->Cell($w[4], 12, $asset['validity'], 'LR', 0, 'C', $fill, '', true);
    $pdf->Cell($w[5], 12, $asset['curr_status'], 'LR', 0, 'C', $fill, '', true);    
    $pdf->Ln();
    $fill=!$fill;
}
$pdf->Cell(array_sum($w), 0, '', 'T');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_004.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
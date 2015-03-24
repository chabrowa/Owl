<?php

include '../templates/sessionstart.php';
require('../fpdf/fpdf.php');

//@ $pdfTestVersionId = $_GET['version'];
//$pdfTestVersionId = 0;

class PDF extends FPDF {

// Page header
    function Header() {
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(10);
        // Title
        $pdfTestVersionId = $_GET['version'];

        $pdfTestsQuery = mysql_query("SELECT subjects.name AS subjectName, pdftests.name AS pdftest_name "
                . "FROM pdftests INNER JOIN qbases ON qbases.id = pdftests.qbase_id "
                . "INNER JOIN subjects ON subjects.id = qbases.subject_id "
                . "INNER JOIN pdf_test_occ ON pdftests.id = pdf_test_occ.pdftest_id "
                . "WHERE pdf_test_occ.version_nr = $pdfTestVersionId ");
        $chosenTestData = mysql_fetch_array($pdfTestsQuery);
        $this->Cell(170, 10, "Subject: " . $chosenTestData["subjectName"] . " version: " . $pdfTestVersionId . " test: " . $chosenTestData["pdftest_name"] . "maxpoints obtainded points", 0, 0, 'C');
        // Line break
        $this->Ln(20);
    }

// Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
$pdfTestVersionId = $_GET['version'];
$pdfTestId = $_GET['pdftest'];
$pdfTestsQuery = mysql_query("SELECT DISTINCT questions.qcontent AS question_name, questions.id AS question_id "
        . " FROM pdf_test_occ_answer "
        . " INNER JOIN pdf_test_occ_question ON pdf_test_occ_question.id = pdf_test_occ_answer.pdf_test_question_id "
        . " INNER JOIN pdf_test_occ ON pdf_test_occ.id = pdf_test_occ_question.pdf_test_occ_id "
        . " INNER JOIN pdftests ON pdftests.id = pdf_test_occ.pdftest_id "
        . " INNER JOIN qbases ON qbases.id = pdftests.qbase_id "
        . " INNER JOIN questions ON qbases.id = questions.qbase_id "
        . " INNER JOIN answers ON questions.id = answers.question_id "
        . " WHERE pdf_test_occ.version_nr = $pdfTestVersionId AND pdf_test_occ.pdftest_id = $pdfTestId ");



while ($nextRow = mysql_fetch_assoc($pdfTestsQuery)) {
    $pdf->MultiCell(0, 6, $nextRow['question_name'], 0, 1);
    $questionId = $nextRow['question_id'];
    $questionAnswersQuery = mysql_query("SELECT * FROM answers WHERE question_id = $questionId ");
    while ($nextAnswerRow = mysql_fetch_assoc($questionAnswersQuery)) {
        $pdf->Cell(10);
        $pdf->MultiCell(0, 6, $nextAnswerRow['acontent'], 0, 1);
    }
}
$pdf->Output();
?>
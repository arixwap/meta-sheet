<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'html_lang');
$sheet->setCellValue('C1', 'url');
$sheet->setCellValue('D1', 'canonical');
$sheet->setCellValue('E1', 'title');
$sheet->setCellValue('F1', 'meta_description');
$sheet->setCellValue('G1', 'robots');

$sheet->setCellValue('A2', '1');
$sheet->setCellValue('B2', $data['html_lang']);
$sheet->setCellValue('C2', $data['url']);
$sheet->setCellValue('D2', $data['canonical']);
$sheet->setCellValue('E2', $data['title']);
$sheet->setCellValue('F2', $data['description']);
$sheet->setCellValue('G2', $data['robots']);

$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);

$writer = new Xlsx($spreadsheet);
$writer->save('Sheet.xlsx');

// // redirect output to client browser
// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="myfile.xlsx"');
// header('Cache-Control: max-age=0');

// $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
// $writer->save('php://output');

?>

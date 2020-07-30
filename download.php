<?php

session_start();

if ( ! isset($_SESSION['cache']) ) die('403');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Read cache file
$cachePath = dirname(__FILE__) . '/cache/' . $_SESSION['cache'] . '.json';
$cache = fopen( $cachePath, 'r' ) or die("Unable to read cache file");
$data = fread($cache, filesize($cachePath));
$data = json_decode($data);
fclose($cache);

// Clear cache and destroy session
unlink($cachePath);
session_destroy();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'html_lang');
$sheet->setCellValue('C1', 'url');
$sheet->setCellValue('D1', 'canonical');
$sheet->setCellValue('E1', 'title');
$sheet->setCellValue('F1', 'meta_description');
$sheet->setCellValue('G1', 'robots');

// Loop data cache and write into excel
$i = 2;
foreach ( $data as $item ) {

    $sheet->setCellValue('A'.$i, '1');
    $sheet->setCellValue('B'.$i, $item->html_lang);
    $sheet->setCellValue('C'.$i, $item->url);
    $sheet->setCellValue('D'.$i, $item->canonical);
    $sheet->setCellValue('E'.$i, $item->title);
    $sheet->setCellValue('F'.$i, $item->description);
    $sheet->setCellValue('G'.$i, $item->robots);

    $i++;
}

$sheet->getColumnDimension('A')->setAutoSize(true);
$sheet->getColumnDimension('B')->setAutoSize(true);
$sheet->getColumnDimension('C')->setAutoSize(true);
$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);
$sheet->getColumnDimension('G')->setAutoSize(true);

// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="sheet.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');

?>

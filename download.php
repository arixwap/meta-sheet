<?php

session_start();

if ( ! isset($_SESSION['cache']) ) die('403');

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$cacheExpire = 3600;
$cacheDir = dirname(__FILE__) . '/cache/';

// Read cache file
$cachePath = $cacheDir . $_SESSION['cache'] . '.json';
$cache = fopen( $cachePath, 'r' ) or die("Unable to read cache file");
$data = fread($cache, filesize($cachePath));
$data = json_decode($data);
fclose($cache);

// Clear cache and destroy session
unlink($cachePath);
session_destroy();

// Clean old cache files
if ( $handle = opendir($cacheDir) ) {
    while ( false !== ($entry = readdir($handle)) ) {
        if ( ! in_array($entry, array('.', '..')) ) {
            $cacheTime = time() - filemtime( $cacheDir . $entry );
            if ( $cacheTime > $cacheExpire ) {
                unlink($cacheDir . $entry);
            }
        }
    }
    closedir($handle);
}

// Ordering Data - This need because AJAX Async randomly order send data
for ( $x = 0; $x < count($data); $x++ ) {
    for ( $y = $x + 1; $y < count($data); $y++ ) {
        if ( $data[$x]->number > $data[$y]->number ) {
            $temp = $data[$x];
            $data[$x] = $data[$y];
            $data[$y] = $temp;
        }
    }
}

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
$row = 2;
foreach ( $data as $item ) {

    $sheet->setCellValue('A'.$row, $item->number);
    if ( isset($item->html_lang) ) $sheet->setCellValue('B'.$row, $item->html_lang);
    if ( isset($item->url) ) $sheet->setCellValue('C'.$row, $item->url);
    if ( isset($item->canonical) ) $sheet->setCellValue('D'.$row, $item->canonical);
    if ( isset($item->title) ) $sheet->setCellValue('E'.$row, $item->title);
    if ( isset($item->description) ) $sheet->setCellValue('F'.$row, $item->description);
    if ( isset($item->robots) ) $sheet->setCellValue('G'.$row, $item->robots);

    $row++;
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

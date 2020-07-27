<?php
/**
 * Kalo engga ngerti, coba aja baca pelan pelan dari atas ke bawah
 * Dan pahami alurnya setiap baris
 * :)
 */

$data = array(
    'url' => 'https://www.villabalisale.com/blog/how-foreigner-can-buy-a-property-in-bali'
);

// Get external content with curl
$curl = curl_init();
curl_setopt($curl, CURLOPT_HEADER, 0);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_URL, $data['url']);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
$content = curl_exec($curl);
curl_close($curl);

// Parsing content into html
$dom = new DOMDocument();
@$dom->loadHTML($content);

// Get html locale lang
$nodes = $dom->getElementsByTagName('html');
$data['html_lang'] = $nodes->item(0)->getAttribute('lang');

// Get canonical
$tags = $dom->getElementsByTagName('link');
for ( $i = 0; $i < $tags->length; $i++ ) {

    $tag = $tags->item($i);

    if ( $tag->getAttribute('rel') == 'canonical' ) {
        $data['canonical'] = $tag->getAttribute('href');
    }
}

// Get html head title
$nodes = $dom->getElementsByTagName('title');
$data['title'] = trim($nodes->item(0)->nodeValue);

// Get all meta tags
$tags = $dom->getElementsByTagName('meta');
for ( $i = 0; $i < $tags->length; $i++ ) {

    $tag = $tags->item($i);

    // Get meta name description & robots
    if ( $tag->getAttribute('name') != '' ) {
        $data[ $tag->getAttribute('name') ] = trim($tag->getAttribute('content'));
    }

    // Get meta og:
    if ( strpos($tag->getAttribute('property'), 'og:') !== false ) {
        $data[ $tag->getAttribute('property') ] = trim($tag->getAttribute('content'));
    }
}

echo '<pre>';
print_r($data);
echo '</pre>';


//-----------------------------------


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
$writer->save('otometa.xlsx');

?>

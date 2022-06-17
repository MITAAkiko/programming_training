<?php
namespace XcelFolder
{
    require_once('/var/www/html/training/programming_training/config.php');
    require(HOME.'/vendor/autoload.php');
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    class MakePdf
    {

        public function makeexl($value)
        {
            
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(HOME.'/template/invoice-temp.xlsx');

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('B4', $value["name"]);//会社
            $sheet->setCellValue('D9', $value["pay"]);//支払期限
            $sheet->setCellValue('D13', $value["total"]);//金額
            $sheet->setCellValue('E5', $value["manager"]);//担当者名
            $sheet->setCellValue('O4', $value["num"]);//請求番号
            $sheet->setCellValue('O5', $value["date"]);//請求日

            $writer = new Xlsx($spreadsheet);
            $xlname = HOME.'/xcelFolder/invoice'.date("Y-m-d_H-i").'.xlsx';

            // ブラウザでxcelダウンロード
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition:inline;filename="' . basename($xlname) . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }
        public function makepdf($value)
        {
            
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(HOME.'/template/invoice-temp.xlsx');

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setCellValue('B4', $value["name"]);//会社
            $sheet->setCellValue('D9', $value["pay"]);//支払期限
            $sheet->setCellValue('D13', $value["total"]);//金額
            $sheet->setCellValue('E5', $value["manager"]);//担当者名
            $sheet->setCellValue('O4', $value["num"]);//請求番号
            $sheet->setCellValue('O5', $value["date"]);//請求日

            $writer = new Xlsx($spreadsheet);
            $xlname = HOME.'/xcelFolder/com-invoice'.date("Y-m-d_H-i").'.xlsx';
            // $filename = HOME.'/xcelFolder/com-invoice'.date("Y-m-d_H-i").'.pdf';
            $dir = HOME.'/xcelFolder';

            $writer->save($xlname); //xcelFolderにエクセルが保存される

            // LibreOfficeを使用してpdfエクスポート
            $command = "/usr/bin/soffice --headless --convert-to pdf --outdir " . $dir . " $xlname";
            exec($command);

            // pdfを新しいウィンドウにダウンロード
            // header('Content-Type: application/pdf');
            // // inlineで画面内部で開く。attachmentでダウンロードさせる
            // header('Content-Disposition: attachment; filename="' . basename($filename) . '" ');
            // header('Cache-Control: max-age=0');
            echo ($command);
        }
    }
}

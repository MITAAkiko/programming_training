<?php
namespace App\Controllers
{
    require_once('/var/www/html/training/programming_training/config.php');
    require(HOME.'/vendor/autoload.php');
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    //モデルのファイルを読み込む
    require_once(APP.'/models/InvoicesModel.php');
    use App\Models\InvoicesModel;

    class MakeExcelController
    {
        private $invMdl;
        public function __construct()
        {
            $this->invMdl = new InvoicesModel;
        }
        public function excel($get)
        {
            $check = $this->invMdl->checkId($get['cid']);
            $checkInvoiceId = $this->invMdl->checkInvoiceId($get['id']);
            if (!$check || !$checkInvoiceId) {
                header('Location:../');
            } else {
                $id = $get['id'];
                $cid = $get['cid'];
            }
            $data = $this->invMdl->fetchDataById($id);
            $cDate = $this->invMdl->fetchCompanyNameById($cid);
            $value = [
                "name" => $cDate['company_name'],
                "title" => $data['title'],
                "pay" => str_replace('-', '/', $data['payment_deadline']),
                "total" => $data['total'],
                "manager" => $cDate['manager_name'],
                "num" => $data['no'],
                "date" => $data['date_of_issue'],
            ];
            // $this->makeexl($value);
            $this->makepdf($value);
        }
        public function makeexl($value)
        {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(HOME.'/template/invoice-temp.xlsx');
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('B4', $value["name"]);//会社
                $sheet->setCellValue('D7', $value["title"]);//件名
                $sheet->setCellValue('D9', $value["pay"]);//支払期限
                $sheet->setCellValue('D13', $value["total"]);//金額
                $sheet->setCellValue('E5', $value["manager"]);//担当者名
                $sheet->setCellValue('O4', $value["num"]);//請求番号
                $sheet->setCellValue('O5', $value["date"]);//請求日

                $xlname = HOME.'/xcelFolder/invoice'.date("Y-m-d_H-i").'.xlsx';

                // ブラウザでxcelダウンロード
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: inline;filename="' . basename($xlname) . '"');
                header('Cache-Control: max-age=0');

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
            } catch (\Exception $e) { //phpSpreadsheetの中に例外処理の記述あり
                echo $e->getMessage();
            }
        }
        public function makepdf($value)
        {
            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(HOME.'/template/invoice-temp.xlsx');
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('B4', $value["name"]);//会社
                $sheet->setCellValue('D7', $value["title"]);//件名
                $sheet->setCellValue('D9', $value["pay"]);//支払期限
                $sheet->setCellValue('D13', $value["total"]);//金額
                $sheet->setCellValue('E5', $value["manager"]);//担当者名
                $sheet->setCellValue('O4', $value["num"]);//請求番号
                $sheet->setCellValue('O5', $value["date"]);//請求日

                //ファイル名・ディレクトリ準備
                $xlname = HOME.'/xcelFolder/com-invoice'.date("Y-m-d_H-i").'.xlsx';
                $filename = HOME.'/xcelFolder/com-invoice'.date("Y-m-d_H-i").'.pdf';
                $dir = HOME.'/xcelFolder';
                //Excel保存
                $writer = new Xlsx($spreadsheet);
                $writer->save($xlname); //xcelFolderにエクセルが保存される

                // LibreOfficeを使用してpdfエクスポート
                $command = "/usr/bin/soffice --headless --convert-to pdf --outdir " . $dir . " $xlname";
                exec($command);

                // pdfを新しいウィンドウにダウンロード準備
                header('Content-Type: application/pdf');
                // inlineで画面内部で開く。attachmentでダウンロードさせる
                header('Content-Disposition: attachment; filename="' . basename($filename) . '" ');
                header('Cache-Control: max-age=0');
                readfile($filename);
            } catch (\Exception $e) { //phpSpreadsheetの中に例外処理の記述あり
                echo $e->getMessage();
            }
        }
    }
}

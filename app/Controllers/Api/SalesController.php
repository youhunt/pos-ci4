<?php

namespace App\Controllers\Api;

use App\Models\TransactionModel;
use App\Models\TransactionItemModel;
use CodeIgniter\Controller;

class SalesController extends BaseController
{
    public function index()
    {
        $shopId = user()->shop_id;

        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');

        // Default: hari ini
        if (!$start || !$end) {
            $start = date('Y-m-d');
            $end   = date('Y-m-d');
        }

        $trxModel = new TransactionModel();

        $data['transactions'] = $trxModel
            ->where('shop_id', $shopId)
            ->where("DATE(created_at) >=", $start)
            ->where("DATE(created_at) <=", $end)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Total omzet
        $data['total_sales'] = $trxModel
            ->where('shop_id', $shopId)
            ->where("DATE(created_at) >=", $start)
            ->where("DATE(created_at) <=", $end)
            ->selectSum('total_amount')
            ->first()['total'] ?? 0;

        // Total item terjual
        $itemModel = new TransactionItemModel();
        $data['total_items'] = $itemModel
            ->where("DATE(created_at) >=", $start)
            ->where("DATE(created_at) <=", $end)
            ->selectSum('qty')
            ->first()['qty'] ?? 0;

        $data['start'] = $start;
        $data['end']   = $end;

        return view('reports/sales', $data);
    }

    // ============================================================
    // EXPORT EXCEL
    // ============================================================

    public function exportExcel()
    {
        $shopId = user()->shop_id;

        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');

        $trxModel = new TransactionModel();

        $rows = $trxModel
            ->where('shop_id', $shopId)
            ->where("DATE(created_at) >=", $start)
            ->where("DATE(created_at) <=", $end)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=laporan-penjualan.xls");

        echo "Tanggal\tInvoice\tTotal\n";

        foreach ($rows as $r) {
            echo "{$r['created_at']}\t{$r['invoice_number']}\t{$r['total']}\n";
        }
        exit;
    }

    // ============================================================
    // EXPORT PDF
    // ============================================================

    public function exportPdf()
    {
        $start = $this->request->getGet('start_date');
        $end   = $this->request->getGet('end_date');

        $html = view('reports/pdf_sales', [
            'start' => $start,
            'end'   => $end
        ]);

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('laporan-penjualan.pdf', ["Attachment" => true]);
    }
}

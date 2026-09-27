<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintNotaController extends Controller
{
    public function print(WorkOrder $workOrder)
    {
        $workOrder->load(['customer', 'vehicle', 'items']);

        $pdf = Pdf::loadView('print.nota', ['workOrder' => $workOrder])
            ->setPaper('a5', 'portrait'); // ukuran nota, ganti 'a4' kalau mau lebih besar

        return $pdf->stream('nota-' . $workOrder->invoice_number . '.pdf');
    }
}

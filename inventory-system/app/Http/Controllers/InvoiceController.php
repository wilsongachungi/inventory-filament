<?php

namespace App\Http\Controllers;


use id;
use App\Models\User;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\InvoiceRecord;
use Filament\Facades\Filament;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;

class InvoiceController extends Controller
{
    //records pprinting information and prints the invoice
    public function printPurchaseInvoice($tenantId, $id)
    {
        $purchase = Purchase::with(['provider','products'])->find($id);
        if($purchase) {

            InvoiceRecord::create([
                "user_id" => auth()->user()->id,
                "purchase_id" => $id,
            ]);
            $settings = getSettings([
                'Tenant Name','Address','Zip','Email'
            ],$tenantId);

           $pdf = \PDF::loadView('pdf.invoice_purchase',compact('purchase','settings'));

           return $pdf->stream();
        }
        else{
            Notification::make()
            ->title("No purchase record found..")
            ->danger()
            ->send();
            return redirect()->back();
        }

    }


}

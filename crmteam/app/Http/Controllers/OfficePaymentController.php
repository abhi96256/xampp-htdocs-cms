<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\OfficePayment;

class OfficePaymentController extends Controller
{
    public function index()
    {
        $payments = OfficePayment::orderBy('id', 'DESC')->get();
        return view('office_payments.index', compact('payments'));
    }

    public function create()
    {
        return view('office_payments.create');
    }

    public function store(Request $request)
    {
        OfficePayment::create($request->all());
        return redirect()->route('office_payment.index')->with('success', 'Office Payment added successfully.');
    }

    public function edit($id)
    {
        $office_payment = OfficePayment::findOrFail($id);
        return view('office_payments.edit', compact('office_payment'));
    }

    public function update(Request $request, $id)
    {
        $office_payment = OfficePayment::findOrFail($id);
        $office_payment->update($request->all());
        return redirect()->route('office_payment.index')->with('success', 'Office Payment updated successfully.');
    }

    public function destroy($id)
    {
        OfficePayment::findOrFail($id)->delete();
        return back()->with('success', 'Office Payment deleted successfully.');
    }

    public function export(Request $request) {
        $fileName = 'office_payments_' . date('Y-m-d') . '.csv';
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '";');

        $columns = array('Date (YYYY-MM-DD)', 'Financial Year', 'Location', 'Cash Amount', 'Cheque Nos', 'Cheque Date (YYYY-MM-DD)', 'Cheque Amount', 'Online Amount', 'UTR Nos', 'Topology', 'In Favour Of', 'Remarks');
        
        $data = OfficePayment::orderBy('id', 'DESC')->get();

        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        foreach ($data as $d) {
            fputcsv($file, array($d->date, $d->financial_year, $d->location, $d->cash_amount, $d->cheque_nos, $d->cheque_date, $d->cheque_amount, $d->online_amount, $d->utr_nos, $d->topology, $d->in_favour_of, $d->remarks));
        }
        fclose($file);
        exit();
    }

    public function import_template()
    {
        $fileName = 'office_payments_template.csv';
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '";');

        $columns = array('Date (YYYY-MM-DD)', 'Financial Year', 'Location', 'Cash Amount', 'Cheque Nos', 'Cheque Date (YYYY-MM-DD)', 'Cheque Amount', 'Online Amount', 'UTR Nos', 'Topology', 'In Favour Of', 'Remarks');

        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        fclose($file);
        exit();
    }
    
    public function import(Request $request)
    {
        return view('office_payments.import');
    }

    public function store_import(Request $request)
    {
        if ($request->hasFile('import_file')) {
            $file = $request->file('import_file');
            $filename = $file->getClientOriginalName();
            $destinationPath = public_path('/storage/galeryImages/');
            $file->move($destinationPath, $filename);
            $filePath = public_path('/storage/galeryImages/') . '/' . $filename;
            
            if (($handle = fopen($filePath, "r")) !== FALSE) {
                $i = 0;
                while (($getData = fgetcsv($handle, 10000, ",")) !== FALSE) {
                    if ($i > 0) {
                        try {
                            OfficePayment::create([
                                'date' => !empty($getData[0]) ? date('Y-m-d', strtotime($getData[0])) : null,
                                'financial_year' => $getData[1] ?? '',
                                'location' => $getData[2] ?? '',
                                'cash_amount' => $getData[3] ?? null,
                                'cheque_nos' => $getData[4] ?? '',
                                'cheque_date' => !empty($getData[5]) ? date('Y-m-d', strtotime($getData[5])) : null,
                                'cheque_amount' => $getData[6] ?? null,
                                'online_amount' => $getData[7] ?? null,
                                'utr_nos' => $getData[8] ?? '',
                                'topology' => $getData[9] ?? '',
                                'in_favour_of' => $getData[10] ?? '',
                                'remarks' => $getData[11] ?? '',
                            ]);
                        } catch (\Exception $e) {
                            // skip row on error 
                        }
                    }
                    $i++;
                }
                fclose($handle);  
            }
        }
        return redirect()->route('office_payment.index')->with('success', 'Office Payments Imported Successfully.');
    }
}

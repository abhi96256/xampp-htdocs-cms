<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\commision;
use App\Models\Client;
use App\Models\Configuration;
use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\Project;
use App\Models\User;
use App\Models\Incentive;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lancer\fpdf\PDF;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Invoices::latest();
        
        if ($request->has('fyFilter') && !empty($request->fyFilter)) {
            $parts = explode('-', $request->fyFilter);
            if (count($parts) == 2) {
                $startYear = $parts[0];
                $endYear = substr($parts[0], 0, 2) . $parts[1]; 
                $startDate = $startYear . '-04-01';
                $endDate = $endYear . '-03-31';
                $query->whereBetween('bill_date', [$startDate, $endDate]);
            }
        }

        $clients = $query->paginate(15);
        $clients->appends($request->all());
		
        return view('invoices.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('invoices.create'); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
		
        DB::beginTransaction();
        try {
			
            $Invoice = Invoices::create([
                'bill_no' => ucwords($request->input('bill_no')),
                'bill_date' => $request->input('bill_date'),
                'bill_type' => $request->input('bill_type'),
                'customer_name' => $request->input('customer_name'),
                'company_developer' => $request->input('company_developer'),
                'company_address' => $request->input('company_address'),
                'property_amount' => $request->input('property_amount'),
                'gst' => $request->input('gst'),
                'amount_for_developer' => $request->input('amount_for_developer'),
                'gst_status' => $request->input('gst_status'),
                'bill_status' => $request->input('bill_status'),
                'tds' => $request->input('tds'),
                'amount_after_tds' => $request->input('amount_after_tds'),
                'after_customer_passon' => $request->input('after_customer_passon'),
                'remarks' => $request->input('remarks'),
                'gst_percentage' => $request->input('gst_percentage'),
                'tds_percentage' => $request->input('tds_percentage'),
                'customer_pass_on' => $request->input('customer_pass_on'),
                'customer_details' => $request->input('customer_details'),
                'commission_1_name' => $request->input('commission_1_name'),
                'commission_1_percentage' => $request->input('commission_1_percentage'),
                'commission_1_date' => $request->input('commission_1_date'),
                'commission_1_cheque_no' => $request->input('commission_1_cheque_no'),
                'commission_1_company_amount' => $request->input('commission_1_company_amount'),
                'commission_1_tds' => $request->input('commission_1_tds'),
                'commission_1_new' => $request->input('commission_1_new'),
                'commission_1_amount' => $request->input('commission_1_amount'),
				'commission_2_name' => $request->input('commission_2_name'),
                'commission_2_percentage' => $request->input('commission_2_percentage'),
                'commission_2_date' => $request->input('commission_2_date'),
                'commission_2_cheque_no' => $request->input('commission_2_cheque_no'),
                'commission_2_company_amount' => $request->input('commission_2_company_amount'),
                'commission_2_tds' => $request->input('commission_2_tds'),
                'commission_2_new' => $request->input('commission_2_new'),
                'commission_2_amount' => $request->input('commission_2_amount'),
                'commission_3_name' => $request->input('commission_3_name'),
                'commission_3_percentage' => $request->input('commission_3_percentage'),
                'commission_3_date' => $request->input('commission_3_date'),
                'commission_3_cheque_no' => $request->input('commission_3_cheque_no'),
                'commission_3_company_amount' => $request->input('commission_3_company_amount'),
                'commission_3_tds' => $request->input('commission_3_tds'),
                'commission_3_new' => $request->input('commission_3_new'),
                'commission_3_amount' => $request->input('commission_3_amount'),
				'commission_4_name' => $request->input('commission_4_name'),
                'commission_4_percentage' => $request->input('commission_4_percentage'),
                'commission_4_date' => $request->input('commission_4_date'),
                'commission_4_cheque_no' => $request->input('commission_4_cheque_no'),
                'commission_4_company_amount' => $request->input('commission_4_company_amount'),
                'commission_4_tds' => $request->input('commission_4_tds'),
                'commission_4_new' => $request->input('commission_4_new'),
                'commission_4_amount' => $request->input('commission_4_amount'),
				'commission_5_name' => $request->input('commission_5_name'),
                'commission_5_percentage' => $request->input('commission_5_percentage'),
                'commission_5_date' => $request->input('commission_5_date'),
                'commission_5_cheque_no' => $request->input('commission_5_cheque_no'),
                'commission_5_company_amount' => $request->input('commission_5_company_amount'),
                'commission_5_tds' => $request->input('commission_5_tds'),
                'commission_5_new' => $request->input('commission_5_new'),
                'commission_5_amount' => $request->input('commission_5_amount'),
				'commission_6_name' => $request->input('commission_6_name'),
                'commission_6_percentage' => $request->input('commission_6_percentage'),
                'commission_6_date' => $request->input('commission_6_date'),
                'commission_6_cheque_no' => $request->input('commission_6_cheque_no'),
                'commission_6_company_amount' => $request->input('commission_6_company_amount'),
                'commission_6_tds' => $request->input('commission_6_tds'),
                'commission_6_new' => $request->input('commission_6_new'),
                'commission_6_amount' => $request->input('commission_6_amount'),
            ]);

            $Invoice->save();

            if ($request->hasFile('bill_attachment')) {
                $file = $request->file('bill_attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/invoices'), $filename);
                $Invoice->bill_attachment = 'storage/invoices/' . $filename;
                $Invoice->save();
            }
			
			
           
            
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('invoices.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Invoices::findorfail($id);

        return view('invoices.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoices::findorfail($id);
        
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        DB::beginTransaction();
        try {
			
            $invoice = Invoices::findorfail($id);
            $invoice->update([
                'bill_no' => ucwords($request->input('bill_no')),
                'bill_date' => $request->input('bill_date'),
                'bill_type' => $request->input('bill_type'),
                'customer_name' => $request->input('customer_name'),
                'company_developer' => $request->input('company_developer'),
                'company_address' => $request->input('company_address'),
                'property_amount' => $request->input('property_amount'),
                'gst' => $request->input('gst'),
                'amount_for_developer' => $request->input('amount_for_developer'),
                'gst_status' => $request->input('gst_status'),
                'bill_status' => $request->input('bill_status'),
                'tds' => $request->input('tds'),
                'amount_after_tds' => $request->input('amount_after_tds'),
                'after_customer_passon' => $request->input('after_customer_passon'),
                'remarks' => $request->input('remarks'),
                'gst_percentage' => $request->input('gst_percentage'),
                'tds_percentage' => $request->input('tds_percentage'),
                'customer_pass_on' => $request->input('customer_pass_on'),
                'customer_details' => $request->input('customer_details'),
				'commission_1_name' => $request->input('commission_1_name'),
                'commission_1_percentage' => $request->input('commission_1_percentage'),
                'commission_1_date' => $request->input('commission_1_date'),
                'commission_1_cheque_no' => $request->input('commission_1_cheque_no'),
                'commission_1_company_amount' => $request->input('commission_1_company_amount'),
                'commission_1_tds' => $request->input('commission_1_tds'),
                'commission_1_new' => $request->input('commission_1_new'),
                'commission_1_amount' => $request->input('commission_1_amount'),
				'commission_2_name' => $request->input('commission_2_name'),
                'commission_2_percentage' => $request->input('commission_2_percentage'),
                'commission_2_date' => $request->input('commission_2_date'),
                'commission_2_cheque_no' => $request->input('commission_2_cheque_no'),
                'commission_2_company_amount' => $request->input('commission_2_company_amount'),
                'commission_2_tds' => $request->input('commission_2_tds'),
                'commission_2_new' => $request->input('commission_2_new'),
                'commission_2_amount' => $request->input('commission_2_amount'),
                'commission_3_name' => $request->input('commission_3_name'),
                'commission_3_percentage' => $request->input('commission_3_percentage'),
                'commission_3_date' => $request->input('commission_3_date'),
                'commission_3_cheque_no' => $request->input('commission_3_cheque_no'),
                'commission_3_company_amount' => $request->input('commission_3_company_amount'),
                'commission_3_tds' => $request->input('commission_3_tds'),
                'commission_3_new' => $request->input('commission_3_new'),
                'commission_3_amount' => $request->input('commission_3_amount'),
				'commission_4_name' => $request->input('commission_4_name'),
                'commission_4_percentage' => $request->input('commission_4_percentage'),
                'commission_4_date' => $request->input('commission_4_date'),
                'commission_4_cheque_no' => $request->input('commission_4_cheque_no'),
                'commission_4_company_amount' => $request->input('commission_4_company_amount'),
                'commission_4_tds' => $request->input('commission_4_tds'),
                'commission_4_new' => $request->input('commission_4_new'),
                'commission_4_amount' => $request->input('commission_4_amount'),
				'commission_5_name' => $request->input('commission_5_name'),
                'commission_5_percentage' => $request->input('commission_5_percentage'),
                'commission_5_date' => $request->input('commission_5_date'),
                'commission_5_cheque_no' => $request->input('commission_5_cheque_no'),
                'commission_5_company_amount' => $request->input('commission_5_company_amount'),
                'commission_5_tds' => $request->input('commission_5_tds'),
                'commission_5_new' => $request->input('commission_5_new'),
                'commission_5_amount' => $request->input('commission_5_amount'),
				'commission_6_name' => $request->input('commission_6_name'),
                'commission_6_percentage' => $request->input('commission_6_percentage'),
                'commission_6_date' => $request->input('commission_6_date'),
                'commission_6_cheque_no' => $request->input('commission_6_cheque_no'),
                'commission_6_company_amount' => $request->input('commission_6_company_amount'),
                'commission_6_tds' => $request->input('commission_6_tds'),
                'commission_6_new' => $request->input('commission_6_new'),
                'commission_6_amount' => $request->input('commission_6_amount'),
            ]);

            if ($request->hasFile('bill_attachment')) {
                $file = $request->file('bill_attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('storage/invoices'), $filename);
                $invoice->bill_attachment = 'storage/invoices/' . $filename;
            }

			$invoice->save();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('invoices.index'));
    }
	public function download(Request $request, $id)
    {
        
        DB::beginTransaction();
        try {
			
            $invoice = Invoices::findorfail($id);
           			require app_path().'/Lancer/fpdf/html_table.php';
			$pdf=new PDF();

$pdf->AddPage();
/*output the result*/

/*set font to arial, bold, 14pt*/
$pdf->SetFont('Arial','B',20);

/*Cell(width , height , text , border , end line , [align] )*/

$pdf->Cell(71 ,10,'',0,0);
$pdf->Cell(59 ,5,'Invoice',0,0);
$pdf->Cell(59 ,10,'',0,1);

$pdf->SetFont('Arial','B',15);
$pdf->Cell(71 ,5,"A2p Realtech Private Limited",0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->Cell(59 ,5,'Customer Details',0,1);

$pdf->SetFont('Arial','',10);

$pdf->Cell(130 ,5,"S-3 2nd floor Malik plaza plot No -5 sector 4, Dwarka,",0,0);
$pdf->Cell(28 ,5,'Customer Name:',0,0);
$pdf->Cell(34 ,5,$invoice->customer_name,0,1);

$pdf->Cell(130 ,5,"NewDelhi,110078",0,0);
$pdf->Cell(25 ,5,'Invoice Date:',0,0);
$pdf->Cell(34 ,5,$invoice->bill_date,0,1);
 
$pdf->Cell(130 ,5,'',0,0);
$pdf->Cell(25 ,5,'Invoice No:',0,0);
$pdf->Cell(34 ,5,$invoice->bill_no,0,1);


$pdf->SetFont('Arial','B',15);
$pdf->Cell(130 ,5,'Bill To',0,0);
$pdf->Cell(59 ,5,$invoice->customer_name,0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(189 ,10,'',0,1);



$pdf->Cell(50 ,10,'',0,1);

$pdf->SetFont('Arial','B',10);
/*Heading Of the table*/
$pdf->Cell(10 ,6,'#',1,0,'C');
$pdf->Cell(25 ,6,'Description',1,0,'C');
$pdf->Cell(25 ,6,'Type of Bill',1,0,'C');
$pdf->Cell(30 ,6,'Property Amount',1,0,'C');
$pdf->Cell(14 ,6,'GST',1,0,'C');
$pdf->Cell(35 ,6,'Developer Amount',1,0,'C');/*end of line*/
$pdf->Cell(14 ,6,'TDS',1,0,'C');/*end of line*/
$pdf->Cell(35 ,6,'Customer Amount',1,1,'C');/*end of line*/
/*Heading Of the table end*/
$pdf->SetFont('Arial','',10);
		$pdf->Cell(10 ,6,1,1,0);
		$pdf->Cell(25 ,6,$invoice->company_developer,1,0);
		$pdf->Cell(25 ,6,$invoice->bill_type,1,0,'R');
		$pdf->Cell(30 ,6,$invoice->property_amount,1,0,'R');
		$pdf->Cell(14 ,6,$invoice->gst,1,0,'R');
		$pdf->Cell(35 ,6,$invoice->amount_for_developer,1,0,'R');
		$pdf->Cell(14 ,6,$invoice->tds,1,0,'R');
		$pdf->Cell(35 ,6,$invoice->after_customer_passon,1,1,'R');
		

$pdf->Cell(118 ,6,'',0,0);
$pdf->Cell(25 ,6,'Subtotal',0,0);
$pdf->Cell(45 ,6,$invoice->after_customer_passon,1,1,'R');


$pdf->SetFont('Arial','B',15);
$pdf->Cell(130 ,5,'Commissions',0,0);
$pdf->Cell(59 ,5,'',0,0);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(189 ,10,'',0,1);
$pdf->SetFont('Arial','B',10);

/*Heading Of the table*/
$pdf->Cell(10 ,6,'#',1,0,'C');
$pdf->Cell(27 ,6,'Name',1,0,'C');
$pdf->Cell(25 ,6,'Date',1,0,'C');
$pdf->Cell(30 ,6,'Cheque no',1,0,'C');
$pdf->Cell(40 ,6,'Company Amount',1,0,'C');
$pdf->Cell(35 ,6,'TDS',1,0,'C');/*end of line*/
$pdf->Cell(20 ,6,'Amount',1,1,'C');/*end of line*/
/*Heading Of the table end*/
		$pdf->SetFont('Arial','',10);
		if($invoice->commission_1_name){
		$pdf->Cell(10 ,6,1,1,0);
		$pdf->Cell(27 ,6,$invoice->commission_1_name,1,0);
		$pdf->Cell(25 ,6,$invoice->commission_1_date,1,0,'R');
		$pdf->Cell(30 ,6,$invoice->commission_1_cheque_no,1,0,'R');
		$pdf->Cell(40 ,6,$invoice->commission_1_company_amount,1,0,'R');
		$pdf->Cell(35 ,6,$invoice->commission_1_tds,1,0,'R');
		$pdf->Cell(20 ,6,$invoice->commission_1_amount,1,1,'R');
		}
		if($invoice->commission_2_name){
		$pdf->Cell(10 ,6,2,1,0);
		$pdf->Cell(27 ,6,$invoice->commission_2_name,1,0);
		$pdf->Cell(25 ,6,$invoice->commission_2_date,1,0,'R');
		$pdf->Cell(30 ,6,$invoice->commission_2_cheque_no,1,0,'R');
		$pdf->Cell(40 ,6,$invoice->commission_2_company_amount,1,0,'R');
		$pdf->Cell(35 ,6,$invoice->commission_2_tds,1,0,'R');
		$pdf->Cell(20 ,6,$invoice->commission_2_amount,1,1,'R');
		}	
		if($invoice->commission_3_name){
		$pdf->Cell(10 ,6,3,1,0);
		$pdf->Cell(27 ,6,$invoice->commission_3_name,1,0);
		$pdf->Cell(25 ,6,$invoice->commission_3_date,1,0,'R');
		$pdf->Cell(30 ,6,$invoice->commission_3_cheque_no,1,0,'R');
		$pdf->Cell(40 ,6,$invoice->commission_3_company_amount,1,0,'R');
		$pdf->Cell(35 ,6,$invoice->commission_3_tds,1,0,'R');
		$pdf->Cell(20 ,6,$invoice->commission_3_amount,1,1,'R');
		}if($invoice->commission_4_name){
		$pdf->Cell(10 ,6,4,1,0);
		$pdf->Cell(27 ,6,$invoice->commission_4_name,1,0);
		$pdf->Cell(25 ,6,$invoice->commission_4_date,1,0,'R');
		$pdf->Cell(30 ,6,$invoice->commission_4_cheque_no,1,0,'R');
		$pdf->Cell(40 ,6,$invoice->commission_4_company_amount,1,0,'R');
		$pdf->Cell(35 ,6,$invoice->commission_4_tds,1,0,'R');
		$pdf->Cell(20 ,6,$invoice->commission_4_amount,1,1,'R');
		}if($invoice->commission_5_name){
		$pdf->Cell(10 ,6,5,1,0);
		$pdf->Cell(27 ,6,$invoice->commission_5_name,1,0);
		$pdf->Cell(25 ,6,$invoice->commission_5_date,1,0,'R');
		$pdf->Cell(30 ,6,$invoice->commission_5_cheque_no,1,0,'R');
		$pdf->Cell(40 ,6,$invoice->commission_5_company_amount,1,0,'R');
		$pdf->Cell(35 ,6,$invoice->commission_5_tds,1,0,'R');
		$pdf->Cell(20 ,6,$invoice->commission_5_amount,1,1,'R');
		}if($invoice->commission_6_name){
		$pdf->Cell(10 ,6,6,1,0);
		$pdf->Cell(27 ,6,$invoice->commission_6_name,1,0);
		$pdf->Cell(25 ,6,$invoice->commission_6_date,1,0,'R');
		$pdf->Cell(30 ,6,$invoice->commission_6_cheque_no,1,0,'R');
		$pdf->Cell(40 ,6,$invoice->commission_6_company_amount,1,0,'R');
		$pdf->Cell(35 ,6,$invoice->commission_6_tds,1,0,'R');
		$pdf->Cell(20 ,6,$invoice->commission_6_amount,1,1,'R');
		}


			$pdf->Output('D','Invoice_12.pdf');
			exit;
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('invoices.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findorfail($id);
        $client->deletedBy()->associate(auth()->user());
        $client->saveQuietly();
        $client->delete();

        return back();
    }

    public function export(Request $request) {
        $fileName = 'invoices_' . date('Y-m-d') . '.csv';
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '";');

        $columns = [
            'Bill No', 'Bill Date', 'Bill Type', 'Customer Name', 'Company/Developer', 
            'Company Address', 'Property Amount', 'GST', 'Amount for Developer', 
            'GST Status', 'Bill Status', 'TDS', 'Amount After TDS', 'After Customer Passon', 
            'Remarks'
        ];
        
        $query = Invoices::latest();
        
        if ($request->has('fyFilter') && !empty($request->fyFilter)) {
            $parts = explode('-', $request->fyFilter);
            if (count($parts) == 2) {
                $startYear = $parts[0];
                $endYear = substr($parts[0], 0, 2) . $parts[1]; 
                $startDate = $startYear . '-04-01';
                $endDate = $endYear . '-03-31';
                $query->whereBetween('bill_date', [$startDate, $endDate]);
            }
        }

        $data = $query->get();

        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        foreach ($data as $d) {
            fputcsv($file, [
                $d->bill_no, $d->bill_date, $d->bill_type, $d->customer_name, $d->company_developer, 
                $d->company_address, $d->property_amount, $d->gst, $d->amount_for_developer, 
                $d->gst_status, $d->bill_status, $d->tds, $d->amount_after_tds, $d->after_customer_passon, 
                $d->remarks
            ]);
        }
        fclose($file);
        exit();
    }

    public function import_template()
    {
        $fileName = 'invoices_template.csv';
        header('Content-Type: application/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '";');

        $columns = [
            'Bill No', 'Bill Date (YYYY-MM-DD)', 'Bill Type', 'Customer Name', 'Company/Developer', 
            'Company Address', 'Property Amount', 'GST', 'Amount for Developer', 
            'GST Status', 'Bill Status', 'TDS', 'Amount After TDS', 'After Customer Passon', 
            'Remarks'
        ];

        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        fclose($file);
        exit();
    }
    
    public function import(Request $request)
    {
        return view('invoices.import');
    }

    public function store_import(Request $request)
    {
        if ($request->hasFile('import_file')) {
            $file = $request->file('import_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('/storage/galeryImages/');
            $file->move($destinationPath, $filename);
            $filePath = public_path('/storage/galeryImages/') . '/' . $filename;
            
            if (($handle = fopen($filePath, "r")) !== FALSE) {
                $i = 0;
                while (($getData = fgetcsv($handle, 10000, ",")) !== FALSE) {
                    if ($i > 0) {
                        try {
                            Invoices::create([
                                'bill_no' => $getData[0] ?? '',
                                'bill_date' => !empty($getData[1]) ? date('Y-m-d', strtotime($getData[1])) : null,
                                'bill_type' => $getData[2] ?? '',
                                'customer_name' => $getData[3] ?? '',
                                'company_developer' => $getData[4] ?? '',
                                'company_address' => $getData[5] ?? '',
                                'property_amount' => $getData[6] ?? null,
                                'gst' => $getData[7] ?? null,
                                'amount_for_developer' => $getData[8] ?? null,
                                'gst_status' => $getData[9] ?? '',
                                'bill_status' => $getData[10] ?? '',
                                'tds' => $getData[11] ?? null,
                                'amount_after_tds' => $getData[12] ?? null,
                                'after_customer_passon' => $getData[13] ?? null,
                                'remarks' => $getData[14] ?? '',
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
        return redirect()->route('invoices.index')->with('success', 'Invoices Imported Successfully.');
    }
}

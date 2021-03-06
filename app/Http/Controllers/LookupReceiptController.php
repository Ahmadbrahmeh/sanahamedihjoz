<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Validator;
use App\Receipt;
use App\ReceiptCash;
use App\ReceiptCheque;
use App\Helpers\UserHelper;
use DB;
use App\Customer;
use App\OrganizationCurrency;
use App\Supplier;
use App\Employee;

class LookupReceiptController extends Controller
{
    public function index()
    {
        return view('manager.pages.receipt-lookup');
    }
	
	public function getSpecificReceipts($id) {
		
		$organization_currency = OrganizationCurrency::select("currencies.name", "organization_currencies.currency_id")
            ->where('organization_currencies.organization_id', UserHelper::getManagerOrganizationId())
            ->join('currencies', 'currencies.id', 'organization_currencies.currency_id')->firstOrFail();
		
		
		$currencies = DB::table('currencies')->select('currencies.id as id','currencies.name', 'exhange_rates.value')
        ->join('exhange_rates', function ($join) {
            $join->on('currencies.id', '=', 'exhange_rates.to')->where('exhange_rates.organization_id', '=', UserHelper::getManagerOrganizationId());
        })
		->where(function ($query) {
                $query->where("currencies.organization_id", UserHelper::getManagerOrganizationId())
                    ->orWhere("currencies.default", true);
            })
        ->where("currencies.id", "!=", $organization_currency->currency_id)
        ->get();
		
		$organization = auth()->user()->manager()->organization();
        $organization_id =$organization->id;

        $customers = Customer::select("name", "id")
            ->where("organization_id",  $organization_id)->get();
        
        $suppliers =  Supplier::select("name", "id")
			->where("organization_id",  $organization_id)->get();
	
		$employees = Employee::select("name", "id")
		->where("organization_id",  $organization_id)->get();

		$receipt = Receipt::where('id', $id)->first();
		

        return view('manager.pages.receipt-specific')->with(['organization_currency' => $organization_currency,
		    "currencies" => $currencies, 'customers' => $customers, 'receipt' => $receipt,
            'suppliers' => $suppliers , 'employees' =>  $employees ]);
    }
	

    public function getReceipts()
    {
        $receipts = DB::table('receipts')
            ->where('organization_id', UserHelper::getManagerOrganizationId())
            ->whereNotNull('customer_id')
            ->orwhereNotNull('supplier_id')
            ->orwhereNotNull('employee_id')
            ->get();

        $receipts = $receipts->map(function ($receipt) {
            $client ='';
            if($receipt->client_type == 'C')
			    $client= Customer::select("name")->where("id",  $receipt->customer_id)->first();	
            else if($receipt->client_type == 'S')	
                $client= Supplier::select("name")->where("id",  $receipt->supplier_id)->first();	
            else
                $client= Employee::select("name")->where("id",  $receipt->employee_id)->first();	
 
			$receipt->customer_name = $client->name;
			$organization = DB::table('organizations')->select('name')->where('id', $receipt->organization_id)->first();
			$receipt->organization_id = $organization->name;
            $receipt->receipt_date =  date('d/m/Y', strtotime($receipt->receipt_date));
            $receipt->created_at =  date('d/m/Y', strtotime($receipt->created_at));
            $receipt->updated_at =  date('d/m/Y', strtotime($receipt->updated_at));
            return $receipt;
        });

        return DataTables::of($receipts)->make(true);
    }

    /**
     * Remove current receipt from lookup
     * @param $request
     * @return redirect with validation message
    */

    public function removeReceipt(Request $request) {
        $validator = Validator::make($request->all(), ['id' => 'required|integer|exists:receipts']);
        
        if ($validator->fails()) {
            return redirect()->back()->with(['success' => 'false',"message"=>"???????? ?????????? ?????? ?????? ??????????"]);
        }
        
        $id = $request['id'];
		ReceiptCash::where('receipt_id', $id)->delete();
		ReceiptCheque::where('receipt_id', $id)->delete();
		Receipt::where('id', $id)->where('organization_id', UserHelper::getManagerOrganizationId())->delete();

        return redirect()->back()->with(['success' => 'true', "message"=>"???? ?????? ?????? ?????????? ??????????"]); 
    }

}

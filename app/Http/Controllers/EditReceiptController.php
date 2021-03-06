<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Helpers\UserHelper;
use App\OrganizationCurrency;
use DB;
use App\Currency;
use App\Receipt;
use App\ReceiptCash;
use App\ReceiptCheque;
use App\Customer;
use App\Supplier;
use App\Employee;
use Session;
use DateTime;

class EditReceiptController extends Controller {
    public function show($id) {
		
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
            ->where("organization_id",  $organization_id)->orderBy('id','desc')->get();
        
			$suppliers =  Supplier::select("name", "id")
			->where("organization_id",  $organization_id)->orderBy('id','desc')->get();
	
		$employees = Employee::select("name", "id")
		->where("organization_id",  $organization_id)->orderBy('id','desc')->get();

		$receipt = Receipt::where('id', $id)->first();
		

        return view('manager.pages.receipt-edit')->with(['organization_currency' => $organization_currency,
		"currencies" => $currencies, 'customers' => $customers, 'receipt' => $receipt,
		'suppliers' => $suppliers, 'employees' => $employees]);
    }

    public function edit(Request $request, $id) {
		
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


		$array = json_decode($request->allcheques, true);
		
		$check = false;
		
		$value = $request->cash_basecurrency;

		if(is_numeric($value)) {
			$net = round($value);
			if($net > 0) {
				$check = true;
			}
		}	

		foreach($currencies as $index => $currency) {
			
			$value = $request->input('cash_'.$currency->id);
			$exchange = $request->input('cashexchangeinput_'.$currency->id);
			
			if(is_numeric($value) && is_numeric($exchange)) {
				$net = round($value * $exchange);
				if($net > 0) {
					$check = true;
				}
			}
		}
		
			if($array != null) {
				foreach ($array as $key => $value) {					
					$bank_account = $value["?????? ???????? ??????????"];
					$bank_name = $value["??????????"];
					$amount = $value["???????? ??????????"];
					$bank_branch = $value["??????????"];
					$cheque_date = $value["?????????? ??????????"];
					$cheque_num = $value["?????? ??????????"];
					$note = $value["????????????"];
					$exchangeRate = $value["?????? ??????????"];
					$currency_name = $value["????????????"];

					$currency_id = '';
							

					if($currency_name == $organization_currency->name) {
						$currecny_id = $organization_currency->currency_id;
					} else {
						foreach($currencies as $index => $currency) {
							if($currency->name == $currency_name) {
								$currency_id = $currency->id;
								break;
							}
						}
					}	

					if (DateTime::createFromFormat('Y-m-d', $cheque_date) == FALSE) {
						Session::flash('errorReceipt','?????????????? ?????? ???????? ???????????? ??????????????');
						return redirect()->back();
					}

					
					if(is_numeric($amount) && is_numeric($exchangeRate) && strlen($bank_account) > 0 
					&& strlen($bank_name) > 0 && strlen($bank_branch) > 0 && strlen($cheque_date) > 0 
					&& strlen($cheque_num) > 0) {
						$net = round($amount * $exchangeRate);
						if($net > 0) {
							$check = true;
						}
					}
				}
			}
			
		$customer =  $request->customer;
		$date = $request->datepicker_main;
		
		if(strlen($customer) == 0 || $date == "") {
			$check = false;
		}
		
		if (DateTime::createFromFormat('Y-m-d', $date) == FALSE) {
			$check = false;
		} 
			
		if($check) {		
		DB::transaction(function() use ($request, $organization_currency, $currencies, $array, $id) {
             
				$receipt = $this->getReceipt($request, $id);
				$receipt->save();

				ReceiptCash::where('receipt_id', $id)->delete();
				ReceiptCheque::where('receipt_id', $id)->delete();
				
				
				$receiptCashCollection = collect();
                $receiptChequeCollection = collect();				

				$value = $request->cash_basecurrency;

				if(is_numeric($value)) {
					$net = round($value);

					$receiptCash = ['amount' => $value, 'currency_id' => $organization_currency->currency_id, 'receipt_id' => $receipt->id,
								'exhange_rate' => 1.0, "net_amount" => $net];
						
					$receiptCashCollection->push($receiptCash);
				}
				

				foreach($currencies as $index => $currency) {
					
					$value = $request->input('cash_'.$currency->id);
					$exchange = $request->input('cashexchangeinput_'.$currency->id);
					
					if(is_numeric($value) && is_numeric($exchange)) {
						$net = round($value * $exchange);
						$receiptCash = ['amount' => $value, 'currency_id' => $currency->id, 'receipt_id' => $receipt->id,
								'exhange_rate' => $exchange, "net_amount" => $net];
						
						$receiptCashCollection->push($receiptCash);
					}
				}


				if($array != null) {
				foreach ($array as $key => $value) {					
					$bank_account = $value["?????? ???????? ??????????"];
					$bank_name = $value["??????????"];
					$amount = $value["???????? ??????????"];
					$bank_branch = $value["??????????"];
					$cheque_date = $value["?????????? ??????????"];
					$cheque_num = $value["?????? ??????????"];
					$note = $value["????????????"];
					$exchangeRate = $value["?????? ??????????"];
					$currency_name = $value["????????????"];

					$currency_id = '';
							
					if($currency_name == $organization_currency->name) {
						$currency_id = $organization_currency->currency_id;
					} else {
						foreach($currencies as $index => $currency) {
							if($currency->name == $currency_name) {
								$currency_id = $currency->id;
								break;
							}
						}
					}	
					
					if(is_numeric($amount) && is_numeric($exchangeRate) && $currency_id != '') {
						$net = round($amount * $exchangeRate);					
					
						$receiptCheque = ['amount' => $amount, 'currency_id' => $currency_id,'bank_account' => $bank_account,
								'bank_name' => $bank_name, 'bank_branch' => $bank_branch, 'cheque_date' => $cheque_date,
								'cheque_num' => $cheque_num, 'note'=> $note, 'receipt_id' => $receipt->id,
								'exhange_rate' => $exchangeRate, "net_amount" => $net];
						
						$receiptChequeCollection->push($receiptCheque);
					}
				}
				}
				

				if(count($receiptCashCollection) > 0) {
					ReceiptCash::insert($receiptCashCollection->all());
				}
				
				if(count($receiptChequeCollection) > 0) {
					ReceiptCheque::insert($receiptChequeCollection->all());
				}
				
				                
				$total_net_amount = 0;
				
				if(count($receiptCashCollection) > 0) {
					$total_net_amount += $receiptCashCollection->sum("net_amount");
					$receipt->total_cash =  $receiptCashCollection->sum("net_amount");
				}
				
				if(count($receiptChequeCollection) > 0) {
					$total_net_amount += $receiptChequeCollection->sum("net_amount");
					$receipt->total_cheque =  $receiptChequeCollection->sum("net_amount");
				}
                
                $receipt->total = $total_net_amount;
                $receipt->save();
				
				Session::flash('addReceipt','???? ?????????? ?????? ?????????? ??????????');

                
            });	

		} else {
			Session::flash('errorReceipt','???????? ?????????? ???????????? ????????????????');
		}
	
	
	return redirect()->back();			
		
		
    }
	
	private function getReceipt($request, $id) {
        $organization = auth()->user()->manager()->organization();
        $last_receipt = Receipt::latest()->where("organization_id",  $organization->id)->first();
        $number = 0;
        if(isset($last_receipt)) {
            $number = $last_receipt->part_number;
        }
        $number = $number + 1;

        $receipt = Receipt::where('id', $id)->first();
       // $receipt->customer_id =  $request->customer;
	   $client_id = $request->customer;

		$fields = explode(".", $client_id);

		$receipt->client_type = $fields[0];
		if($fields[0] =='C'){
			$receipt->customer_id = $fields[1];
		}
		elseif($fields[0]=='S'){
			$receipt->supplier_id = $fields[1];
		}
		else{
			$receipt->employee_id = $fields[1];
			}
        $receipt->organization_id = $organization->id;
        $receipt->total = 0;
		$receipt->receipt_date = $request->datepicker_main;
        $receipt->updated_by = auth()->user()->id;
		$receipt->notes = $request->notes;

        return $receipt;
    }	

}
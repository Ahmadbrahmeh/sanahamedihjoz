<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use DataTables;
use App\Reservation;
use App\Customer;
use App\Address;
use App\Supplier;
use App\Employee;
use Config;
use Validator;

define("RESERVATION_STATMENT", "حجز ");
define("SERVICE_STATMENT", "خدمة ");
define("RECEIPT_STATMENT", "سند قبض ");
define("PAYMENT_STATMENT", "سند صرف ");
define("DEPIT", 1);
define("CREDIT", -1);
define("DEPIT_TYPE", 'DEPIT');
define("CREDIT_TYPE", 'CREDIT');

class LookupAccountReportController extends Controller
{
    private $balance = 0;

    public function index(Request $request) {
        // $validator = Validator::make($request->all(), ['customer_id' => 'integer|exists:customers,id']);

        // if ($validator->fails()) {
        //     return redirect()->back();
        // }

        if($request->has('customer_id') && $request-> customer_id=='-1') {
            return $this->defaultAccountReportView($request);
        }
        if($request->has('customer_id')) {
            return $this->accountReportView($request);
        }

        return $this->defaultAccountReportView($request);
    }

    private function defaultAccountReportView(Request $request) {
        $organization = auth()->user()->manager()->organization();
        $organization_id =$organization->id;
           
        $customers = Customer::select("name", "id")
            ->where("organization_id",  $organization_id)->orderBy('id','desc')->get();
        
        $suppliers = Supplier::select("name", "id")
            ->where("organization_id",  $organization_id)->orderBy('id','desc')->get();
        
        $employees = Employee::select("name", "id")
            ->where("organization_id",  $organization_id)->orderBy('id','desc')->get();
        
        $transactions = [];

        return view('manager.pages.report-account-lookup')->with(
            ['customers' => $customers, 
             'suppliers' => $suppliers,
             'employees' => $employees,
             'transactions' =>$transactions,
             'organization' =>  $organization,
             'hasCustomer' => false]);
    }

    private function accountReportView(Request $request) {
        $organization = auth()->user()->manager()->organization();
        $organization_id =$organization->id;

        $client_id = $request->customer_id;
		$fields = explode(".", $client_id);

		$client_type = $fields[0];
        if($client_type == 'C'){
            $request->customer_id = $fields[1];
            $customer = Customer::where('id', $request->customer_id )
            ->where('organization_id', $organization_id)->first();
            $customer->address = $this->getCity($customer->address_id);
            $organization->customer = $customer;

        }
        else if($client_type == 'S'){
                $request->customer_id = $fields[1];
                $customer = Supplier::where('id', $request->customer_id )
                ->where('organization_id', $organization_id)->first();
                $customer->address = $this->getCity($customer->address_id);
                $organization->customer = $customer;
                $organization->supplier = $customer;
        }
        else if($client_type == 'E'){
            $request->customer_id = $fields[1];
            $customer = Employee::where('id', $request->customer_id )
            ->where('organization_id', $organization_id)->first();
            $customer->address = $this->getCity($customer->address_id);
            $organization->customer = $customer;
            $organization->employee = $customer;
    }
        // $customer = Customer::where('id', $request->customer_id)
        //     ->where('organization_id', $organization_id)->first();
        // $customer->address = $this->getCity($customer->address_id);


        $customers = Customer::select("name", "id")
            ->where("organization_id",  $organization_id)->get();

        $suppliers = Supplier::select("name", "id")
            ->where("organization_id",  $organization_id)->orderBy('id','desc')->get();
        
        $employees = Employee::select("name", "id")
            ->where("organization_id",  $organization_id)->orderBy('id','desc')->get();
        
        $transactions =  collect([]); 
        $reservations = Reservation::select("*")
            ->where("customer_id", $request->customer_id)
            ->where("organization_id",  $organization->id)->get(); 
        
        foreach($reservations as $reservation) {
            $status_list = Config::get('constants.reservations.status.arabic');
            $reservation->status = $status_list[$reservation->status];
            foreach($reservation->halls() as $reservationHall) {
                $depit = $reservationHall->cost;
                $credit = 0;

                $title = $reservation->title . " - " . (RESERVATION_STATMENT . $reservationHall->hall()->name );
                $transaction = ["title" => $title, "debit" => $depit,
                    "credit" => $credit, 'balance' => 0, 'status' => $reservation->status, 'note' => $reservationHall->note,
                    "type" => DEPIT_TYPE, "date" => $reservationHall->created_at];
                $transactions->push($transaction);
            }
            $reservation->services = $reservation->services()->where("active", true);
            foreach($reservation->services as $reservationService) {
                $depit = $reservationService->cost;
                $credit = 0;
                $title = $reservation->title . " - " . (SERVICE_STATMENT . $reservationService->service()->name );
                $transaction = ["title" => $title, "debit" => $depit,
                    "credit" => $credit, 'balance' => 0, 'status' => $reservation->status, 'note' => $reservationService->note,
                    "type" => DEPIT_TYPE, "date" => $reservation->created_at];
                $transactions->push($transaction);
            }
        }
        
        foreach($customer->receipts() as $receipt) {
            $credit = $receipt->total;
            $depit = 0;
            $title = RECEIPT_STATMENT . " رقم " . $receipt->invoice_number;
            $transaction = ["title" => $title , "debit" => $depit, "credit" => $credit, 'balance' => 0,
                'status' => '', 'note' => '', "type" => CREDIT_TYPE, "date" => $receipt->created_at];
            $transactions->push($transaction);
        }

        foreach($customer->receiptPayments() as $receiptPayment) {
            $credit = 0;
            $depit =  $receiptPayment->total;
            $title = PAYMENT_STATMENT . " رقم " . $receiptPayment->invoice_number;
            $transaction = ["title" => $title , "debit" => $depit, "credit" => $credit, 'balance' => 0,
                'status' => '', 'note' => $receiptPayment->notes, "type" => DEPIT_TYPE, "date" => $receiptPayment->created_at];
            $transactions->push($transaction);
        }

        
        $transactions = $transactions->filter(function ($transaction, $key) {
            return $transaction['date'] != null;
        });

        $transactions = $transactions->sortBy('date');

        $transactions = $transactions->map(function($transaction) {
            if($transaction['type'] == DEPIT_TYPE) {
                $transaction['balance'] = $this->moveBalance($transaction['debit'], DEPIT);
            }
            else {
                $transaction['balance'] = $this->moveBalance($transaction['credit'], CREDIT);
            }
            return $transaction;
        });

        $account = ["balance" => $this->balance];

        return view('manager.pages.report-account-lookup')->with([
                'customers' => $customers,
                'suppliers' => $suppliers,
                'employees' => $employees,
                'transactions' =>$transactions,
                'hasCustomer' => true,
                'organization' => $organization,
                'account' => $account,
            ]);
    }

    private function moveBalance($value, $factor) {
        $this->balance = $this->balance + ($value * $factor);
        return $this->balance;
    }

    private function getCity($id)
    {
        $addresses_chain = collect([]);
        $current_address = Address::where("id", $id)->first();
        while($current_address != null) {
            if($current_address->type == "city") {
                return $current_address->name;
            }
            $current_address = $current_address->parent();
        }
        return "";
    }


    /* unsed function */
    public function getReports(Request $request) {
        $manager = auth()->user()->manager();
        $organization = $manager->organization();
        $reservations = DB::table('reservations')
            ->select('reservations.id', 'reservations.code', 'reservations.customer_name', 'reservations.title', 'reservations.status',
                'customers.phone1', 'users.fname', 'users.lname', 'reservations.created_at','reservations.remaining_amount as total')
        ->where('reservations.organization_id', $organization->id)
        ->join("users", "users.id", "reservations.created_by")
        ->join("customers", "customers.id", "reservations.customer_id")
        ->orderBy("created_at", "desc");
        if($request->has("user_id")) {
            $reservations->where("customers.id", $request->user_id);
        }
        $reservations = $reservations->get();
        $reservations = $reservations->map(function ($reservation)  {
            $status_list = Config::get('constants.reservations.status.arabic');
            $reservation->status = $status_list[$reservation->status];
            $reservation->created_at =  date('d/m/Y', strtotime($reservation->created_at));
            return $reservation;
        });

    return DataTables::of($reservations)->make(true);
    }
}

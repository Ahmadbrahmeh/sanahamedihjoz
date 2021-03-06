<?php

namespace App\Http\Controllers;
use Config;
use DB;
use Validator;
use Exception;
use App\Reservation;
use App\Currency;
use App\Payment;
use App\PaymentCash;
use App\PaymentCheque;
use App\ReservationExhangeRate;
use Illuminate\Http\Request;

define("INITIAL_STATUS", Config::get('constants.reservations.status.mapping.initial'));
define("PENDING_STATUS", Config::get('constants.reservations.status.mapping.pending'));

class AddPaymentController extends Controller
{
    public function show($reservation_id) {
        $max_fields = Config("constants.payments.form.max");
        $user = auth()->user();
        $organization = $user->manager()->organization();
        
        $reservation = Reservation::select("*")
            ->where("id", $reservation_id)
            ->where("organization_id",  $organization->id)->firstOrFail();

        $currencies = DB::table('currencies')->select('*')
            ->where(function ($query) use ($organization) {
                $query->where("organization_id", $organization->id)
                    ->orWhere("default", true);
            })->get();
        $currencies = ReservationExhangeRate::select("currencies.id", "currencies.name", "reservation_exhange_rates.value")
            ->join("currencies", "currencies.id", "reservation_exhange_rates.from")
            ->where("reservation_id", $reservation->id)->get();

        $currency = Currency::find($reservation->currency_id);
        $currencies->prepend($currency);

        return view('manager.pages.payment-add')->with([
            "currencies" => $currencies,
            "reservation" => $reservation,
            "max_fields" => $max_fields,
            "customer_name" => $reservation->customer_name,
        ]);
    }

    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'string|exists:reservations,id',
            'payment' => 'required',
            'payment.*.pay_type' => 'required|string|in:cash,cheque',
            'payment.*.cash_amount' => 'nullable|integer|min:1',
            'payment.*.cash_currency' => 'nullable|string|exists:currencies,id',
            'payment.*.cheque_num' => 'nullable|string',
            'payment.*.bank_name' => 'nullable|string',
            'payment.*.bank_branch' => 'nullable|string',
            'payment.*.cheque_date' => 'nullable|date',
            'payment.*.cheque_amount' => 'nullable|integer|min:1',
            'payment.*.cheque_currency' => 'nullable|string|exists:currencies,id',
            'payment.*.note' => 'nullable|string'
        ]);

        $message = "فشلت عملية الدفع, الرجاء المحاولة مرة اخرى";
        if ($validator->fails()) {
            return redirect()->route('payment-add')
                ->with(['success' => 'false', "message" => $message]);
        }
        try {
            DB::transaction(function() use ($request) {
                $organization = auth()->user()->manager()->organization();
                $reservation = Reservation::select("*")
                    ->where("id", $request->id)
                    ->where("organization_id",  $organization->id)->firstOrFail();
                $reservation_currency = $reservation->currency_id; 

                $rates = ReservationExhangeRate::select("*")
                    ->where("reservation_id", $reservation->id)->get()->keyBy("from");
                    
                if($request->has('payment')) {
                    $count = 1;
                    $payment = $this->getPayment($request);
                    $payment->save();

                    $paymentCashCollection = collect();
                    $paymentChequeCollection = collect();
                    foreach($request->get('payment') as $paymentItem) {
                        $exhangeRate = 1;
                        if(($paymentItem['cash_currency'] != $reservation_currency) && $rates->contains("from", $paymentItem['cash_currency'])) {
                            $exhangeRate = $rates->get($paymentItem['cash_currency'])->value;
                        }
                        $netAmount = round($paymentItem['cash_amount'] * $exhangeRate);
                        if($paymentItem['pay_type'] == 'cash') {
                            $paymentCash = ['amount' => $paymentItem['cash_amount'], 'currency_id' => $paymentItem['cash_currency'], 'payment_id' => $payment->id,
                                'exhange_rate' => $exhangeRate, "net_amount" => $netAmount];
                            $paymentCashCollection->push($paymentCash);
                        }
                        else if($paymentItem['pay_type'] == 'cheque') {
                            $exhangeRate = 1;
                            if(($paymentItem['cheque_currency'] != $reservation_currency) && $rates->contains("from", $paymentItem['cheque_currency'])) {
                                $exhangeRate = $rates->get($paymentItem['cheque_currency'])->value;
                            }
                            $netAmount = round($paymentItem['cheque_amount'] * $exhangeRate);
                            $paymentCheque = ['amount' => $paymentItem['cheque_amount'], 'currency_id' => $paymentItem['cheque_currency'],'bank_account' => $paymentItem['bank_account'],
                                'bank_name' => $paymentItem['bank_name'], 'bank_branch' => $paymentItem['bank_branch'], 'cheque_date' => $paymentItem['cheque_date'],
                                'cheque_num' => $paymentItem['cheque_num'], 'note'=> $paymentItem['note'], 'payment_id' => $payment->id,
                                'exhange_rate' => $exhangeRate, "net_amount" => $netAmount];
                            $paymentChequeCollection->push($paymentCheque);
                        }
                    }
                    PaymentCash::insert($paymentCashCollection->all());
                    PaymentCheque::insert($paymentChequeCollection->all());
                }
                
                $total_net_amount = $paymentCashCollection->sum("net_amount") + $paymentChequeCollection->sum("net_amount");
                $payment->total = $total_net_amount;
                $payment->total_cash =  $paymentCashCollection->sum("net_amount");
                $payment->total_cheque =  $paymentChequeCollection->sum("net_amount");
                $payment->save();

                if($reservation->status == INITIAL_STATUS) {
                    $reservation->status = PENDING_STATUS;
                }
                $reservation->deposit_amount = $reservation->deposit_amount + $total_net_amount;
               
                if($reservation->deposit_amount == $reservation->total_cost) {
                    $reservation->payment_status = true;
                }
                else if($reservation->deposit_amount > $reservation->total_cost) {
                    $message = "فشلت عملية الدفع, يرجى ادخال مبلغ اقل او يساوي المبلغ المتبقي";
                    throw new Exception("The the payment abount bigger than the required amount");
                }
                
                $reservation->remaining_amount = $reservation->total_cost - $reservation->deposit_amount;
                $reservation->save();
            });
        } catch (Exception $e) {
            return redirect()->back()
                ->with(['success' => 'false',"message" => $message])
                ->withInput();
        }

        return redirect()->route('payment-add', $request->id)
            ->with(['success' => 'true', "message" => "تم الدفع بنجاح"]);
    }

    private function generateInvoiceNumber($number)
    {
        $prefix = "0000";
        $invoice_number = substr($prefix, strlen($number)-1, strlen($prefix)) .$number;
        return "R$invoice_number";
    }

    private function getPayment($request) {
        $organization = auth()->user()->manager()->organization();
        $last_payment = Payment::latest()
            ->where("organization_id",  $organization->id)->first();
        $number = 0;
        if(isset($last_payment)) {
            $number = $last_payment->part_number;
        }
        $number = $number + 1;

        $payment = new Payment();
        $payment->reservation_id =  $request->id;
        $payment->organization_id = $organization->id;
        $payment->total = 0;
        $payment->part_number =$number;
        $payment->invoice_number = $this->generateInvoiceNumber($number);
        $payment->created_by = auth()->user()->id;
        $payment->updated_by = auth()->user()->id;

        return $payment;
    }
}

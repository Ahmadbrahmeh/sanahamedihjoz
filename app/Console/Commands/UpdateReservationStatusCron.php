<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Config;
use DB;

define("FINISHED_RESERVATION", Config::get('constants.reservations.status.mapping.finished'));
define("INITIAL_STATUS", Config::get('constants.reservations.status.mapping.initial'));
define("PENDING_STATUS", Config::get('constants.reservations.status.mapping.pending'));
define("CANCELED_RESERVATION", Config::get('constants.reservations.status.mapping.cancel'));
define("DELAYED_RESERVATION", Config::get('constants.reservations.status.mapping.delay'));

class UpdateReservationStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Reservation Status';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('reservations')
            ->join("events", "reservations.id", "events.reservation_id")
            ->whereIn("status", [PENDING_STATUS])
            ->whereDate('events.reservation_date', '<=', now())->update(['reservations.status' => FINISHED_RESERVATION]);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\NewBill;
use App\Models\User;
use App\Notifications\BillPaymentNotification;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class DailyNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications of unpaid bills';

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
     */
    public function handle()
    {
        /** @var User $users */
        $users = User::all();

        foreach ($users as $user) {
            /** @var NewBill|Collection $bills */
            $bills = $user->bills()->with('provider')->where('is_paid', false)->get();

            $now = \Carbon\Carbon::now();
            $bills->each(function ($item) use ($user, $now) {
                $deadLine = Carbon::parse($item->dead_line);
                $diff = $now->diff($deadLine)->days;
                $remainingDay = $now > $deadLine
                    ? 'lejárt'
                    : $diff + 1 . ' nap';

                if ($remainingDay <= 3 || $remainingDay === 'lejárt') {
                    $item->remaining_day = $remainingDay;
                    $user->notify(new BillPaymentNotification($item, $item->provider));
                }
            });
        }
    }
}

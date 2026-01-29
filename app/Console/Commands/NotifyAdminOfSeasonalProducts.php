<?php

namespace App\Console\Commands;

use App\Mail\SeasonalProductEndingNotification;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfSeasonalProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-admin-of-seasonal-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify admin about seasonal products that will no longer be available';

    /**
     * Execute the console command.
     */

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $nextMonth = Carbon::now()->addMonth()->format('F'); // Get the next month name
        $products = Product::whereHas('months', function ($query) use ($nextMonth) {
                            $query->where('name', $nextMonth);
                        })->where('is_seasonal', 1)->get();
        if ($products->count() > 0) {
            $productNames = $products->pluck('product_name')->toArray();
            $productList = implode(', ', $productNames);
            Mail::to('yared.debela.ayele@gmail.com')->send(new SeasonalProductEndingNotification($products, $nextMonth));
            $this->info("Notification sent to admin.");
        } else {
            $this->info('No seasonal products ending soon.');
        }
    }
}

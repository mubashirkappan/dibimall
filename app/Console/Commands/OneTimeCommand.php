<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class OneTimeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'one:time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to create all users dibi refferel code';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $customers = Customer::get();
        foreach ($customers as  $value) {
            $randomString = str::random(6); // Adjust the length as needed
            $referralCode = 'dbmall'.$randomString;
            $value->update(['referal_code'=>$referralCode]);
        } 
    }
}

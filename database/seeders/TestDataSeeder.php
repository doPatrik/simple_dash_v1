<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Expenditure;
use App\Models\ExpenditureType;
use App\Models\NewBill;
use App\Models\Provider;
use App\Models\ProviderAddress;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{

    const USER_COUNT = 10;
    const PROVIDER_COUNT = 10;
    const BILL_COUNT = 20;
    const EXPENDITURE_TYPE_COUNT = 5;
    const EXPENDITURE_COUNT = 20;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addTestUsers();
        $this->addTestProviders();
        $this->addTestBills();
        $this->addTestExpenditures();
    }

    private function addTestUsers()
    {
        User::factory()->count(self::USER_COUNT)->create();
        Address::factory()->count(2)->create();
    }

    private function addTestProviders()
    {
        Provider::factory()->count(self::PROVIDER_COUNT)->create();
    }

    private function addTestBills()
    {
        NewBill::factory()->count(self::BILL_COUNT)->create();
        NewBill::factory()->create(['dead_line' => Carbon::now()->addDays(1)->format('Y-m-d'), 'is_paid' => false]);
        NewBill::factory()->create(['dead_line' => Carbon::now()->addDays(2)->format('Y-m-d'), 'is_paid' => false]);
        NewBill::factory()->create(['dead_line' => Carbon::now()->addDays(3)->format('Y-m-d'), 'is_paid' => false]);
    }

    private function addTestExpenditures()
    {
        ExpenditureType::factory()->count(self::EXPENDITURE_TYPE_COUNT)->create();
        Expenditure::factory()->count(self::EXPENDITURE_COUNT)->create();
    }
}

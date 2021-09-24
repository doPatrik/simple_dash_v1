<?php

namespace Database\Seeders;

use App\Models\DefaultMenu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->addDefaultRoles();
        $this->addDefaultUsers();
        $this->addDefaultMenuItems();
    }

    private function addDefaultRoles()
    {
        Role::create([
            'name' => 'user'
        ]);

        Role::create([
            'name' => 'admin'
        ]);
    }

    private function addDefaultUsers()
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('secret19'),
            'first_name' => 'Web',
            'last_name' => 'Admin',
            'id_role' => 2
        ]);

        User::factory()->create([
            'name' => 'teszt',
            'email' => 'teszt@teszt.com',
            'password' => Hash::make('secret19'),
            'first_name' => 'Web',
            'last_name' => 'Teszt',
            'id_role' => 1
        ]);
    }

    private function addDefaultMenuItems()
    {
        $menuFactory = DefaultMenu::factory();

        $menuFactory->create([
            'name' => 'dashboard',
            'icon' => 'fa fa-home',
            'route' => 'home',
            'type' => 'default',
            'is_active' => true,
        ]);

        $menuFactory->create([
            'name' => 'új számla',
            'icon' => 'fa fa-receipt',
            'route' => 'newBill.index',
            'type' => 'default',
            'is_active' => true,
        ]);

        $menuFactory->create([
            'name' => 'befizetés',
            'icon' => 'fa fa-money',
            'route' => 'payment.index',
            'type' => 'default',
            'is_active' => true,
        ]);

        $menuFactory->create([
            'name' => 'kiadások',
            'icon' => 'fas fa-wallet',
            'route' => 'expenditure',
            'type' => 'default',
            'is_active' => true,
        ]);

        $menuFactory->create([
            'name' => 'szolgáltatók',
            'icon' => 'fa fa-file-signature',
            'route' => 'provider.index',
            'type' => 'default',
            'is_active' => true,
        ]);

        $menuFactory->create([
            'name' => 'áttekintés',
            'icon' => 'fa fa-chart-line',
            'route' => 'overview',
            'type' => 'default',
            'is_active' => true,
        ]);

        $menuFactory->create([
            'name' => 'naptár',
            'icon' => 'fa fa-calendar-week',
            'route' => 'calendar',
            'type' => 'default',
            'is_active' => true,
        ]);

        $menuFactory->create([
            'name' => 'profil',
            'icon' => 'fa fa-user-circle',
            'route' => 'profile',
            'type' => 'default',
            'is_active' => true,
        ]);

        $menuFactory->create([
            'name' => 'admin',
            'icon' => 'fas fa-user-lock',
            'route' => 'list_users',
            'type' => 'admin',
            'is_active' => true,
        ]);
    }
}

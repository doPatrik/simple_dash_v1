<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\DefaultMenu;
use App\Models\Provider;
use App\Models\ProviderAddress;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RegisteredUserTest extends TestCase
{
    use DatabaseMigrations;

    //Admin
    public function testRegisteredUserCanNotAccessAdminUsers()
    {
        $role = Role::create(['name' => 'user']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function testRegisteredUserCanNotAccessAdminMenu()
    {
        $role = Role::create(['name' => 'user']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/menu/loadMenuItems');

        $response->assertStatus(403);
    }

    //Profile

    public function testRegisteredUserCanAccessProfile()
    {
        $role = Role::create(['name' => 'user']);
        $user = User::factory()->create();
        DefaultMenu::factory()->create(['route' => 'profile', 'type' => 'default']);
        $response = $this->actingAs($user)->get('/profile');

        $response->assertStatus(200);
    }

    public function testRegisteredUserCanChangeProfile()
    {
        $role = Role::create(['name' => 'user']);
        $user = User::factory()->create();
        DefaultMenu::factory()->create(['route' => 'profile', 'type' => 'default']);

        $userData = ['name' => 'teszt123', 'email' => 'teszt123@teszt.hu', 'first_name' => 'Teszt', 'last_name' => 'Teszt'];
        $response = $this->actingAs($user)->put('/profile/' . $user->id, $userData);

        $response->assertRedirect();
    }

    public function testRegisteredUserCanCreateAddress()
    {
        $role = Role::create(['name' => 'user']);
        $user = User::factory()->create();
        DefaultMenu::factory()->create(['route' => 'profile', 'type' => 'default']);

        $response = $this->actingAs($user)->get('/address/create');
        $response->assertStatus(200);

        $addressData = ['post_code' => '2131', 'city' => 'Affa', 'street_name' => 'asgafsad', 'id_user' => $user->id];

        $response = $this->actingAs($user)->post('/address', $addressData);
        $response->assertRedirect();
    }

    public function testRegisteredUserCanAccessHomeChartApis()
    {
        $role = Role::create(['name' => 'user']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/home/getBarChartData');
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get('/home/getLineChartData');
        $response->assertStatus(200);
        $response = $this->actingAs($user)->get('/home/getDoughnutChartData');
        $response->assertStatus(200);
    }

    public function testRegisteredUserProviderCRUD()
    {
        $role = Role::create(['name' => 'user']);
        $user = User::factory()->create();
        DefaultMenu::factory()->create(['route' => 'provider.index', 'type' => 'default']);

        $response = $this->actingAs($user)->get('/provider');
        $response->assertStatus(200);

        $userAddress = Address::create(['post_code' => '2131', 'city' => 'Affa', 'street_name' => 'asgafsad', 'id_user' => $user->id]);
        $providerAddress = ProviderAddress::factory()->create();

        $providerData = [
            'post_code' => '1234',
            'city' => 'dada',
            'street' => 'dasda',
            'user_address' => $userAddress->id_address,
            'name' => 'asdas',
            'owner_name' => 'dadas',
            'number' => 1234,
            'color_code' => '#fff',
            'label' => 'qeqe',
        ];
        $response = $this->actingAs($user)->post('/provider', $providerData);
        $response->assertRedirect();

        $provider = Provider::query()->firstOrFail();
        $response = $this->actingAs($user)->put('/provider/' . $provider->id_provider, $providerData);
        $response->assertRedirect();

        $response = $this->actingAs($user)->delete('/provider/' . $provider->id_provider);
        $response->assertRedirect();
    }
}

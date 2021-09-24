<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UnregisteredUserTest extends TestCase
{
    use DatabaseMigrations;

    public function testUnregisteredUserCanNotAccessHome()
    {
        $response = $this->get('/home');

        $response->assertRedirect('login');
    }

    public function testUnregisteredUserCanNotAccessProvider()
    {
        $response = $this->get('/provider');

        $response->assertRedirect('login');
    }

    public function testUnregisteredUserCanNotAccessProfile()
    {
        $response = $this->get('/profile');

        $response->assertRedirect('login');
    }

    public function testUnregisteredUserCanNotAccessPayment()
    {
        $response = $this->get('/payment');

        $response->assertRedirect('login');
    }

    public function testUnregisteredUserCanNotAccessOverview()
    {
        $response = $this->get('/overview');

        $response->assertRedirect('login');
    }


    public function testUnregisteredUserCanNotAccessNewBill()
    {
        $response = $this->get('/newBill');

        $response->assertRedirect('login');
    }

    public function testUnregisteredUserCanNotAccessExpenditure()
    {
        $response = $this->get('/expenditure');

        $response->assertRedirect('login');
    }

    public function testUnregisteredUserCanNotAccessCalendar()
    {
        $response = $this->get('/calendar');

        $response->assertRedirect('login');
    }

    public function testUnregisteredUserCanNotAccessAddress()
    {
        $response = $this->get('/address/create');

        $response->assertRedirect('login');
    }

    public function testUnregisteredUserCanNotAccessAdminUsers()
    {
        $response = $this->get('/admin/users');

        $response->assertRedirect('login');
    }
}

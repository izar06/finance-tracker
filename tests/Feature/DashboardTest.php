<?php

namespace Tests\Feature;

use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_loads_successfully(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_transaction_page_loads(): void
    {
        $response = $this->get('/transaksi');
        $response->assertStatus(200);
    }

    public function test_goals_page_loads(): void
    {
        $response = $this->get('/tujuan');
        $response->assertStatus(200);
    }

    public function test_assets_page_loads(): void
    {
        $response = $this->get('/aset');
        $response->assertStatus(200);
    }

    public function test_root_redirects_to_dashboard(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/dashboard');
    }
}

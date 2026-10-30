<?php

namespace Tests\Feature;

use App\Filament\Pages\Pos;
use App\Models\Customer;
use App\Models\Part;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PosTest extends TestCase
{
    use RefreshDatabase;

    public function test_pos_page_can_be_accessed_by_kasir_and_admin(): void
    {
        $kasir = User::factory()->kasir()->create();
        $this->actingAs($kasir);
        $this->assertTrue(Pos::canAccess());

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);
        $this->assertTrue(Pos::canAccess());

        $mekanik = User::factory()->mekanik()->create();
        $this->actingAs($mekanik);
        $this->assertFalse(Pos::canAccess());
    }

    public function test_pos_checkout_creates_work_order_and_decrements_part_stock(): void
    {
        $kasir = User::factory()->kasir()->create();
        $customer = Customer::create(['name' => 'Doni', 'phone' => '08555']);
        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'plate_number' => 'D 1111 AB',
            'type' => 'Motor',
            'brand' => 'Yamaha',
        ]);

        $part = Part::create([
            'sku' => 'BUSI-CPR',
            'name' => 'Busi NGK CPR',
            'buy_price' => 15000,
            'sell_price' => 25000,
            'stock' => 5,
            'min_stock' => 1,
            'is_active' => true,
        ]);

        $service = Service::create([
            'name' => 'Tune Up Ringan',
            'price' => 40000,
            'duration_minutes' => 30,
            'is_active' => true,
        ]);

        Livewire::actingAs($kasir)
            ->test(Pos::class)
            ->set('customerId', $customer->id)
            ->set('vehicleId', $vehicle->id)
            ->call('addItem', 'part', $part->id)
            ->call('addItem', 'service', $service->id)
            ->set('discount', 5000)
            ->set('paid', 60000)
            ->call('checkout')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('work_orders', [
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'user_id' => $kasir->id,
            'subtotal' => 65000,
            'discount' => 5000,
            'total' => 60000,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('work_order_items', [
            'part_id' => $part->id,
            'qty' => 1,
            'price' => 25000,
        ]);

        $this->assertDatabaseHas('work_order_items', [
            'service_id' => $service->id,
            'qty' => 1,
            'price' => 40000,
        ]);

        // Verify part stock decremented from 5 to 4
        $this->assertEquals(4, $part->fresh()->stock);
    }
}


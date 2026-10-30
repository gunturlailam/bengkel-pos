<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Part;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use App\Models\WorkOrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_work_order_relationships_work_correctly(): void
    {
        $user = User::factory()->kasir()->create();
        $customer = Customer::create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
            'address' => 'Jl. Merdeka No. 1',
        ]);

        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'plate_number' => 'B 1234 ABC',
            'type' => 'Motor',
            'brand' => 'Honda',
            'model' => 'Vario 150',
            'year' => 2021,
        ]);

        $service = Service::create([
            'name' => 'Ganti Oli Mesin',
            'price' => 15000,
            'duration_minutes' => 15,
            'is_active' => true,
        ]);

        $part = Part::create([
            'sku' => 'OLI-MPX2',
            'name' => 'Oli AHM MPX2',
            'buy_price' => 45000,
            'sell_price' => 55000,
            'stock' => 10,
            'min_stock' => 2,
            'is_active' => true,
        ]);

        $workOrder = WorkOrder::create([
            'invoice_number' => 'WO-20261030-0001',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'subtotal' => 70000,
            'discount' => 0,
            'total' => 70000,
        ]);

        $serviceItem = WorkOrderItem::create([
            'work_order_id' => $workOrder->id,
            'service_id' => $service->id,
            'name' => $service->name,
            'qty' => 1,
            'price' => 15000,
            'subtotal' => 15000,
        ]);

        $partItem = WorkOrderItem::create([
            'work_order_id' => $workOrder->id,
            'part_id' => $part->id,
            'name' => $part->name,
            'qty' => 1,
            'price' => 55000,
            'subtotal' => 55000,
        ]);

        $this->assertEquals($customer->id, $workOrder->customer->id);
        $this->assertEquals($vehicle->id, $workOrder->vehicle->id);
        $this->assertEquals($user->id, $workOrder->user->id);
        $this->assertCount(2, $workOrder->items);
        $this->assertEquals($service->id, $serviceItem->service->id);
        $this->assertEquals($part->id, $partItem->part->id);
    }

    public function test_authenticated_user_can_stream_pdf_invoice(): void
    {
        $user = User::factory()->kasir()->create();
        $customer = Customer::create(['name' => 'Andi', 'phone' => '08123']);
        $vehicle = Vehicle::create([
            'customer_id' => $customer->id,
            'plate_number' => 'B 9999 XYZ',
            'type' => 'Mobil',
            'brand' => 'Toyota',
        ]);

        $workOrder = WorkOrder::create([
            'invoice_number' => 'WO-20261030-0002',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'subtotal' => 50000,
            'discount' => 0,
            'total' => 50000,
        ]);

        $response = $this->actingAs($user)->get(route('work-orders.print', $workOrder));
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_root_url_redirects_to_admin(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/admin');
    }
}


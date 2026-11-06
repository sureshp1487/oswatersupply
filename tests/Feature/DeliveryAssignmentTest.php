<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\DeliveryAssignment;
use App\Models\OrderStatusHistory;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class DeliveryAssignmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
    }

    public function test_admin_can_assign_driver_and_driver_can_deliver(): void
    {
        $admin = User::where('role', 'admin')->first();
        $driver = User::where('role', 'driver')->first();
        $order = Order::first();

        // 1. Admin assigns driver
        $response = $this->actingAs($admin)->post(route('admin.orders.assign_driver', $order->id), [
            'driver_id' => $driver->id,
        ]);
        $response->assertStatus(302);

        $order->refresh();
        $this->assertEquals('preparing', $order->order_status);

        $assignment = DeliveryAssignment::where('order_id', $order->id)->first();
        $this->assertNotNull($assignment);
        $this->assertEquals($driver->id, $assignment->driver_id);
        $this->assertEquals('assigned', $assignment->status);

        // 2. Driver starts transit
        $transitResponse = $this->actingAs($driver)->post(route('driver.delivery.status', $assignment->id), [
            'status' => 'in_transit',
        ]);
        $transitResponse->assertStatus(302);

        $order->refresh();
        $assignment->refresh();
        $this->assertEquals('out_for_delivery', $order->order_status);
        $this->assertEquals('in_transit', $assignment->status);

        // 3. Driver completes delivery
        $deliverResponse = $this->actingAs($driver)->post(route('driver.delivery.status', $assignment->id), [
            'status' => 'delivered',
            'collected_cash' => 50.00,
        ]);
        $deliverResponse->assertStatus(302);

        $order->refresh();
        $assignment->refresh();
        $this->assertEquals('delivered', $order->order_status);
        $this->assertEquals('delivered', $assignment->status);
        $this->assertEquals(50.00, (float)$assignment->collected_cash);
        $this->assertNotNull($order->delivered_at);
    }
}

<?php

namespace Tests\Feature;

use App\Commands\Handlers\Order\CreateOrderCommandHandler;
use App\Events\Order\CreateOrderEvent;
use App\Models\Order;
use App\Models\User;
use App\Queries\Handlers\Order\GetAllOrdersQueryHandler;
use Illuminate\Cache\CacheManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{

     //index method Testing

    public function test_index_returns_ok_response()
    {

        $user =User::factory()->create();


        $fakeOrders = [
            [
            'user_id' => $user->id,
            'total_price' => 250.75,
            'status' => 'paid'
             ]

        ];

        $mock = $this->mock(GetAllOrdersQueryHandler::class);
        $mock->shouldReceive('handle')
             ->once()
             ->andReturn($fakeOrders);

        $response = $this->getJson('/api/v1/orders');

        $response->assertOk();
        $response->assertJson([
            [
                'user_id' => $user->id,
                'total_price' => 250.75,
                'status' => 'paid',
            ]
        ]);
    }


    public function test_index_returns_empty_array_when_no_orders_exist()
    {
        $user =User::factory()->create();


        $fakeOrders = [];

        $mock = $this->mock(GetAllOrdersQueryHandler::class);
        $mock->shouldReceive('handle')
             ->once()
             ->andReturn($fakeOrders);

        $response = $this->getJson('/api/v1/orders');

        $response->assertOk();
        $response->assertExactJson([]);

    }

    public function test_index_returns_false_when_data_not_match()
    {
        $user = User::factory()->create();

        $expected = [
            [
                'user_id' => $user->id,
                'total_price' => 250.75,
                'status' => 'paid'
            ]
        ];

        $actualFromHandler = [
            [
                'user_id' => $user->id,
                'total_price' => 999.99, // تغییر عمدی
                'status' => 'pending'
            ]
        ];

        $mock = $this->mock(GetAllOrdersQueryHandler::class);
        $mock->shouldReceive('handle')
             ->once()
             ->andReturn($actualFromHandler);

        $response = $this->getJson('/api/v1/orders');

        $response->assertOk();
        $responseData = $response->json();

        $this->assertNotEquals($expected, $responseData);

    }



        // store method Testing
        public function test_store_creates_order_for_authenticated_user()
        {
            // ایجاد یوزر تست و ساخت توکن
            $user = User::factory()->create();
            $token = $user->createToken('TestToken')->plainTextToken;

            // داده‌هایی که از طرف کاربر ارسال می‌شن
            $requestData = [
                'total_price' => 250.75,
                'status' => 'paid',
            ];

            // داده‌ای که قراره هندلر برگردونه (شبیه‌سازی با موک)
            $fakeOrder = new Order([
                'id' => 123, // مطمئن شو id مشخص باشه
                'user_id' => $user->id,
                'total_price' => 250.75,
                'status' => 'paid',
            ]);

            // موک کردن هندلر
            $this->mock(CreateOrderCommandHandler::class, function ($mock) use ($fakeOrder) {
                $mock->shouldReceive('handle')
                     ->once()
                     ->andReturn($fakeOrder);
            });

            // موک کردن کش
            // $cacheMock = Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);
            // $cacheMock->shouldReceive('tags')
            //     ->once()
            //     ->with(['orders'])
            //     ->andReturnSelf();

            // $cacheMock->shouldReceive('put')
            //     ->once()
            //     ->with("order_{$fakeOrder->id}", $fakeOrder, Mockery::any())
            //     ->andReturn(true);

            // $cacheMock->shouldReceive('has')
            //     ->once()
            //     ->with("order_{$fakeOrder->id}")
            //     ->andReturn(true);

            // $this->app->instance('cache', $cacheMock);


            // موک کردن ایونت
            Event::fake();

            // ارسال درخواست با هدر Authorization
            $response = $this->withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json'
            ])->postJson('/api/v1/orders', $requestData);

            // بررسی پاسخ
            $response->assertStatus(201);
            $response->assertJsonFragment([
                'user_id' => $user->id,
                'total_price' => 250.75,
                'status' => 'paid',
            ]);

            // بررسی اینکه کش ذخیره شده (با توجه به موک بالا)
            // $this->assertTrue(Cache::has("order_{$fakeOrder->id}"));

            // بررسی اینکه ایونت ارسال شده
            Event::assertDispatched(CreateOrderEvent::class);
        }


//         public function test_store_returns_failed_response_on_exception()
// {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $input = [
//         'total_price' => 500.00,
//         'status' => 'pending',
//     ];

//     $mockHandler = $this->mock(CreateOrderCommandHandler::class);
//     $mockHandler->shouldReceive('handle')
//                 ->once()
//                 ->andThrow(new \Exception('something went wrong'));

//     $response = $this->postJson('/api/v1/orders', $input);

//     $response->assertStatus(500); // یا هر وضعیت مورد انتظار شما
// }

}

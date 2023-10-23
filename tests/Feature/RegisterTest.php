<?php

declare(strict_types=1);

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic test example.
     */
    public function test_register(): void
    {
        parent::setUp();

        // Выполнить миграции из новой папки
        Artisan::call('migrate', ['--path' => 'app/Modules/Admin/User/Migrations/']);

        // Создаем данные для регистрации пользователя
        $userData = [
            'firstname' => $this->faker->name,
            'lastname' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'password' => $this->faker->password,
        ];
        $hashedPassword = Hash::make($userData['password']);
        // Отправляем POST-запрос на маршрут регистрации (замените на ваш реальный маршрут)
        $response = $this->post('/pub/auths/register', $userData);

        // Проверяем, что ответ имеет код 200 OK
        $response->assertStatus(200);




// Извлекаем хешированный пароль из базы данных
        $hashedPasswordInDatabase = DB::table('users')->where([
            'firstname' => $userData['firstname'],
            'lastname' => $userData['lastname'],
            'phone' => $userData['phone'],
])->value('password');

// Проверяем, что введенный пароль совпадает с хешированным паролем в базе данных
        $this->assertTrue(Hash::check($userData['password'], $hashedPasswordInDatabase));

        // Можете добавить дополнительные проверки, связанные с вашей бизнес-логикой
    }
}

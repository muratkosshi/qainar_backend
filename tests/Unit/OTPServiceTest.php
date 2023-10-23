<?php

declare(strict_types=1);

namespace Tests\Unit;

use OTPHP\HOTP;
use OTPHP\OTP;
use PHPUnit\Framework\TestCase;

use App\Modules\Pub\Auth\Controllers\OTPService;
use App\Models\User;
use Mockery\MockInterface;
use Mobizon\MobizonApi;

class OTPServiceTest extends TestCase
{
    // Необходимо подключить трейты и методы для инициализации Laravel-приложения,
    // чтобы можно было использовать зависимости и фасады, такие как MobizonApi.
    // Обратитесь к документации Laravel или PHPUnit для настройки тестового окружения.

//    protected function setUp(): void
//    {
//        parent::setUp();
//    }
//
//    protected function tearDown(): void
//    {
//        parent::tearDown();
//    }
//
//    public function testSendOTP()
//    {
//        // Создайте мок объекта MobizonApi для теста
//        $api = $this->mock(MobizonApi::class);
//
//        // Ожидаем, что будет вызван метод call с ожидаемыми параметрами
//        $api->shouldReceive('call')
//            ->withArgs([
//                'message',
//                'sendSMSMessage',
//                [
//                    'recipient' => '77751987868',
//                    'text' => \Mockery::type('string'), // Любой текст сообщения
//                    'params[validity]' => 1440
//                ]
//            ])
//            ->once()
//            ->andReturn(true);
//
//        // Создайте экземпляр OTPService с моком MobizonApi
//        $otpService = new OTPService($api);
//
//        // Создайте фейкового пользователя (или используйте Mockery для этого)
//        $user = new User([
//            'phone' => '77751987868',
//        ]);
//
//        // Вызовите метод sendOTP и убедитесь, что он возвращает код
//        $code = $otpService->sendOTP($user);
//
//        // Добавьте дополнительные проверки, если необходимо
//        $this->assertIsString($code);
//        $this->assertNotEmpty($code);
//        $this->generatedOTP = $code;
//    }
//
//    public function testVerifyOTP()
//    {
//        // Создайте экземпляр OTPService
//        $otpService = new OTPService();
//        $otpSecret = $otpService -> generateHOTP();
//
//        // Создайте фейкового пользователя (или используйте Mockery для этого)
//        $user = new User([
//            'otp_secret' => $otpSecret, // Установите заранее известный секрет
//            'phone' => '77751987868'
//        ]);
//
//        // Создайте фейковый код OTP (подходящий к секрету)
//        $fakeCode = $this->generatedOTP;
//
//        // Проверьте, что метод verifyOTP возвращает true для правильного кода
//        $this->assertTrue($otpService->verifyOTP($user, $fakeCode));
//
//        // Проверьте, что метод verifyOTP возвращает false для неправильного кода
//        $this->assertFalse($otpService->verifyOTP($user, 'incorrect_code'));
//    }
}





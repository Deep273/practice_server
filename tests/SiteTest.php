<?php

use Model\User;
use PHPUnit\Framework\TestCase;
use Src\Application;
use Src\Settings;
class SiteTest extends TestCase
{
    protected Application $app;
    /**
     * @dataProvider additionProvider
     * @runInSeparateProcess
     */
    public function testSignup(string $httpMethod, array $userData, string $message): void
    {
        // Выбираем занятый логин из базы данных
        if ($userData['login'] === 'login is busy') {
            $userData['login'] = User::get()->first()->login;
        }

        // Создаем заглушку для класса Request
        $request = $this->createMock(\Src\Request::class);

        // Переопределяем метод all() и свойство method
        $request->expects($this->any())
            ->method('all')
            ->willReturn($userData);
        $request->method = $httpMethod;

        // Сохраняем результат работы метода в переменную
        $result = (new \Controller\Site())->signup($request);

        if (!empty($result)) {
            // Проверяем варианты с ошибками валидации
            $message = '/' . preg_quote($message, '/') . '/';
            $this->expectOutputRegex($message);
            return;
        }

        // Проверяем добавился ли пользователь в базу данных
        $this->assertTrue((bool)User::where('login', $userData['login'])->count());

        // Удаляем созданного пользователя из базы данных
        User::where('login', $userData['login'])->delete();

        // Проверяем редирект при успешной регистрации
        $this->assertContains($message, xdebug_get_headers());
    }

//Метод, возвращающий набор тестовых данных
    public static function additionProvider(): array
    {
        return [
            ['GET', ['name' => '', 'login' => '', 'password' => ''],
                '<h3></h3>'
            ],
            ['POST', ['name' => '', 'login' => '', 'password' => ''],
                '<h3>{"name":["Поле name пусто"],"login":["Поле login пусто"],"password":["Поле password пусто"]}</h3>',
            ],
            ['POST', ['name' => 'admin', 'login' => 'login is busy',
                'password' => 'admin'],
                '<h3>{"login":["Поле login должно быть уникально"]}</h3>',
            ],
            ['POST', ['name' => 'admin', 'login' => md5(time()),
                'password' => 'admin'],
                'Location: /pop-it-mvc/hello',
            ],
        ];
    }
    // Настройка конфигурации окружения
    protected function setUp(): void
    {
        parent::setUp();

        $_SERVER['DOCUMENT_ROOT'] = '/var/www/html';

        $settings = new \Src\Settings([
            'app' => include __DIR__ . '/../config/app.php',
            'db' => include __DIR__ . '/../config/db.php',
            'path' => include __DIR__ . '/../config/path.php',
        ]);

        $this->app = new \Src\Application($settings);

        // Глобальная функция для доступа к приложению
        if (!function_exists('app')) {
            function app() {
                global $app;
                return $app;
            }
        }

        $GLOBALS['app'] = $this->app;
    }


}
<?php

namespace Providers;

use Src\Provider\AbstractProvider;
use Src\Route;
class RouteProvider extends AbstractProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if ($this->checkPrefix('/api')) {
            $this->app->settings->removeAppMiddleware('csrf');
            $this->app->settings->removeAppMiddleware('specialChars');

            Route::group('/api', function() {
                require_once __DIR__ . '/../..' . $this->app->settings->getRoutePath() . '/api.php';
            });

            return;
        }

// Для всех остальных маршрутов
        Route::group('', function() {
            require_once __DIR__ . '/../..' . $this->app->settings->getRoutePath() . '/web.php';
        });
    }

    private function getUri(): string
    {
        return substr($_SERVER['REQUEST_URI'], strlen($this->app->settings->getRootPath()));
        //Возвращает адрес без пути до директории
    }

    private function checkPrefix(string $prefix): bool
    {
//Получение маршрута
        $uri = $this->getUri();
//Проверка на вхождение префикса
        return strpos($uri, $prefix) === 0;
    }
}

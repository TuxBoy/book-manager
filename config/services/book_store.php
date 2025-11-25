<?php

declare(strict_types=1);

use App\BookStore\Infrastructure\ApiPlatform\State\Provider\BookCollectionDataProvider;
use App\BookStore\Infrastructure\ApiPlatform\State\Provider\BookItemProvider;
use App\BookStore\Infrastructure\ApiPlatform\State\Provider\GetUserBooksCollectionProvider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\BookStore\\', dirname(__DIR__, 2).'/src/BookStore');

    $services->set(BookItemProvider::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 1]);

    $services->set(BookCollectionDataProvider::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);

    $services->set(GetUserBooksCollectionProvider::class)
        ->autoconfigure(false)
        ->tag('api_platform.state_provider', ['priority' => 0]);
};

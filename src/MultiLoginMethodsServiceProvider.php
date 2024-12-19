<?php
namespace StormCode\MultiLoginMethods;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MultiLoginMethodsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('multi-login-methods')
            ->hasConfigFile('multiLoginMethods')
            ->hasRoute('api');
    }
}

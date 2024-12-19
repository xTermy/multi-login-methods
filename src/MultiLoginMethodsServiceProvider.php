<?php
namespace StormCode\MultiLoginMethods;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MultiLoginMethodsServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('multiLoginMethods') // Nazwa pakietu
            ->hasConfigFile() // Czy pakiet ma plik konfiguracyjny
            ->hasRoute('api')
        ; // Routing pakietu
    }
}

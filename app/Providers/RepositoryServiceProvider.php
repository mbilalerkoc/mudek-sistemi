<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $interfacePath = app_path('Repositories/Interfaces');

        foreach (File::files($interfacePath) as $file) {
            $interfaceName = pathinfo(
                $file->getFilename(),
                PATHINFO_FILENAME
            );

            if ($interfaceName === 'BaseRepositoryInterface') {
                continue;
            }

            $repositoryName = str_replace(
                'RepositoryInterface',
                'Repository',
                $interfaceName
            );

            $interface = 'App\\Repositories\\Interfaces\\' . $interfaceName;
            $repository = 'App\\Repositories\\' . $repositoryName;

            if (interface_exists($interface) && class_exists($repository)) {
                $this->app->bind($interface, $repository);
            }
        }
    }

    public function boot(): void
    {
        //
    }
}
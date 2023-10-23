<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ModuleMake extends Command
{
    private $files;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}
                                                   {--all}
                                                   {--migration}
                                                   {--react}
                                                   {--view}
                                                   {--controller}
                                                   {--model}
                                                   {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->files = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('all')) {
            $this->input->setOption('migration', true);
            $this->input->setOption('react', true);
            $this->input->setOption('view', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('model', true);
            $this->input->setOption('api', true);
        }

        if ($this->option('model')) {
            $this->createModel();
        }

        if ($this->option('controller')) {
            $this->createController();
        }

        if ($this->option('api')) {
            $this->createApiController();
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }
        //Возможно данная реализация понадобиться, но пока не нужна.
        //        if ($this->option('react')) {
        //            $this->createReactComponent();
        //        }
        //
        //        if ($this->option('view')) {
        //            $this->createView();
        //        }

    }

    private function createModel()
    {
        $model = Str::singular(Str::studly(class_basename($this->argument('name'))));

        $this->call('make:model', [
            'name' => 'App\\Modules\\'.trim($this->argument('name')).'\\Models\\'.$model,
        ]);

    }

    private function createController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));
        $modelName = Str::singular(Str::studly(class_basename($this->argument('name'))));

        $path = $this->getControllerPath($this->argument('name'));

        if ($this->alreadyExists($path)) {
            $this->error('Controller already exists!');
        } else {
            $this->makeDirectory($path);
            $stub = $this->files->get(base_path('resources/stubs/controller.model.api.stub'));

            $stub = str_replace(
                [
                    'DummyNamespace',
                    'DummyRootNamespace',
                    'DummyClass',
                    'DummyFullModelClass',
                    'DummyModelClass',
                    'DummyModelVariable',
                ],
                [
                    'App\\Modules\\'.trim($this->argument('name')).'\\Controllers',
                    $this->laravel->getNamespace(),
                    $controller.'Controller',
                    'App\\Modules\\'.trim($this->argument('name'))."\\Module\\{$modelName}",
                    $modelName,
                    lcfirst(($modelName)),
                ],
                $stub
            );

            $this->files->put($path, $stub);
            $this->info('Controller created successfully');
            //$this->updateModularConfig();

        }
        $this->createRoutes($controller, $modelName);
    }

    private function createApiController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = Str::singular(Str::studly(class_basename($this->argument('name'))));

        $path = $this->getApiControllerPath($this->argument('name'));

        if ($this->alreadyExists($path)) {
            $this->error('Controller already exists!');
        } else {
            $this->makeDirectory($path);

            $stub = $this->files->get(base_path('resources/stubs/controller.model.api.stub'));

            $stub = str_replace(
                [
                    'DummyNamespace',
                    'DummyRootNamespace',
                    'DummyClass',
                    'DummyFullModelClass',
                    'DummyModelClass',
                    'DummyModelVariable',
                ],
                [
                    'App\\Modules\\'.trim($this->argument('name')).'\\Controllers\\Api',
                    $this->laravel->getNamespace(),
                    $controller.'Controller',
                    'App\\Modules\\'.trim($this->argument('name'))."\\Models\\{$modelName}",
                    $modelName,
                    lcfirst(($modelName)),
                ],
                $stub
            );

            $this->files->put($path, $stub);
            $this->info('Controller created successfully.');
            //$this->updateModularConfig();
        }

        $this->createApiRoutes($controller, $modelName);
    }

    private function createMigration()
    {
        try {
            $argumentName = explode('\\', $this->argument('name'));
            $table = Str::plural(Str::snake($argumentName[1]));
            // Use forward slashes (/) instead of backslashes (\) in the --path option
            $this->call('make:migration', [
                'name' => "create_{$table}_table",
                '--create' => $table,
                '--path' => "app/Modules/{$argumentName[0]}/{$argumentName[1]}/Migrations", // Adjust the path as needed
            ]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    //    private function createReactComponent()
    //    {
    //
    //    }
    //
    //    private function createView()
    //    {
    //
    //    }

    private function getControllerPath($argument)
    {
        $controller = Str::studly(class_basename($argument));

        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $argument).'/Controllers/'."{$controller}Controller.php";

    }

    private function getApiControllerPath($name)
    {
        $controller = Str::studly(class_basename($name));

        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $name).'/Controllers/Api/'."{$controller}Controller.php";

    }

    private function makeDirectory(string $path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    private function createRoutes(string $controller, string $modelName): void
    {
        $routePath = $this->getRoutesPath($this->argument('name'));

        if ($this->alreadyExists($routePath)) {
            $this->error('Routes already exists!');

        } else {
            $this->makeDirectory($routePath);
            $stub = $this->files->get(base_path('resources/stubs/routes.web.stub'));
            $stub = str_replace(
                [
                    'DummyClass',
                    'DummyRoutePrefix',
                    'DummyModelVariable',
                ],
                [
                    $controller.'Controller',
                    Str::plural(Str::snake(lcfirst($modelName), '-')),
                    lcfirst($modelName),
                ],
                $stub
            );
            $this->files->put($routePath, $stub);
            $this->info('Routes created successfully.');
        }
    }

    private function createApiRoutes(string $controller, string $modelName): void
    {

        $routePath = $this->getApiRoutesPath($this->argument('name'));

        if ($this->alreadyExists($routePath)) {
            $this->error('Routes already exists!');
        } else {

            $this->makeDirectory($routePath);

            $stub = $this->files->get(base_path('resources/stubs/routes.api.stub'));

            $stub = str_replace(
                [
                    'DummyClass',
                    'DummyRoutePrefix',
                    'DummyModelVariable',
                ],
                [
                    'Api\\'.$controller.'Controller',
                    Str::plural(Str::snake(lcfirst($modelName), '-')),
                    lcfirst($modelName),
                ],
                $stub
            );

            $this->files->put($routePath, $stub);
            $this->info('Routes created successfully.');
        }

    }

    private function getApiRoutesPath($name): string
    {
        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $name).'/Routes/api.php';
    }

    private function getRoutesPath($name): string
    {
        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $name).'/Routes/web.php';

    }

    protected function alreadyExists($path): bool
    {
        return $this->files->exists($path);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeRepositoryCommand extends Command
{
    protected $signature = 'make:repository {name : The name of the repository} {--model= : The model class to be used by the repository}';
    protected $description = 'Create a new repository class with CRUD functionality';

    public function handle()
    {
        $name = $this->argument('name');
        $className = $this->getClassName($name);
        $namespace = $this->getNamespace($name);
        $path = $this->getPath($namespace, $className);

        if (file_exists($path)) {
            $this->error("Repository [{$path}] already exists!");
            return;
        }

        // Create necessary directories and files
        $this->createTraitsDirectory();
        $this->createSimpleCRUDTrait();
        $this->makeDirectory($path);

        // Create the repository class
        file_put_contents($path, $this->buildClass($namespace, $className));

        // New format for success message
        $relativePath = str_replace(app_path(), 'app', $path);
        $relativePath = str_replace('//', '/', $relativePath);
        $this->components->info(sprintf("Repository [%s] created successfully.", $relativePath));
    }

    protected function getClassName($name)
    {
        return class_basename($name);
    }

    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    protected function getPath($namespace, $className)
    {
        $namespace = str_replace('\\', '/', $namespace);
        return app_path("Repositories/{$namespace}/{$className}.php");
    }

    protected function makeDirectory($path)
    {
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
    }

    protected function createTraitsDirectory()
    {
        $path = app_path('Repositories/Traits');
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    protected function createSimpleCRUDTrait()
    {
        $path = app_path('Repositories/Traits/SimpleCRUD.php');
        if (!file_exists($path)) {
            file_put_contents($path, $this->getSimpleCRUDTraitContent());
        }
    }

    protected function buildClass($namespace, $className)
    {
        $stub = file_get_contents(__DIR__.'/stubs/repository.stub');

        $model_path = $this->option('model') ? 'App\\Models\\' . $this->option('model') :
            'App\\Models\\' . str_replace('Repository', '', $className);
        $model_path = trim($model_path, '\\');

        $stub = str_replace('{{namespace}}', $namespace ? "App\\Repositories\\{$namespace}" : 'App\\Repositories', $stub);
        $stub = str_replace('{{class}}', $className, $stub);
        $stub = str_replace('{{model_path}}', $model_path, $stub);
        $stub = str_replace('{{model}}', class_basename($model_path), $stub);


        return $stub;
    }

    protected function getSimpleCRUDTraitContent()
    {
        return <<<'PHP'
<?php

namespace App\Repositories\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

trait SimpleCRUD
{
    public function getAll(): Collection
    {
        return $this->model::all();
    }

    public function get(int $limit = 10): LengthAwarePaginator
    {
        return $this->model::paginate($limit);
    }

    public function getById(int $id)
    {
        return $this->model::findOrFail($id);
    }

    public function isExists(int $id): bool
    {
        return $this->model::where('id', $id)->exists();
    }

    public function count(): int
    {
        return $this->model::count();
    }

    public function create(array $attributes)
    {
        return $this->model::create($attributes);
    }

    public function update(array $attributes, int $id)
    {
        return $this->model::where('id', $id)->update($attributes);
    }

    public function delete(int $id)
    {
        return $this->model::where('id', $id)->delete();
    }
}
PHP;
    }
}

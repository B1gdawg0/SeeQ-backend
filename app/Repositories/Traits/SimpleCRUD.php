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
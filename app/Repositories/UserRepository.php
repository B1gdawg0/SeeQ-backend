<?php

namespace App\Repositories;

use App\Repositories\Traits\SimpleCRUD;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    use SimpleCRUD;

    private string $model = User::class;


    public function getByEmail(string $email) {
        return $this->model::where('email', $email)->first();
    }

    public function updateOrCreate(array $attributes, array $values)
    {
        return $this->model::updateOrCreate($attributes, $values);
    }

    public function getAllCustomerWithTrashedPaginate() {
        return $this->model::withTrashed()->where('role', 'CUSTOMER')->orderByDesc('updated_at')->paginate(6);
    }

    public function getAllCustomerWithTrashed(): Collection
    {
        return $this->model::withTrashed()->where('role', 'CUSTOMER')->orderByDesc('updated_at')->get();
    }

    public function getByIdWithTrashed(string $id)
    {
        return $this->model::withTrashed()->findOrFail($id);
    }
}

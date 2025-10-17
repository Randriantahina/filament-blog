<?php

namespace App\Repositories\Contracts;
use Illuminate\Database\Eloquent\Collection;
use App\Models\AlertContact;

interface AlertContactRepositoryInterface
{
    public function create(array $data): AlertContact;
    public function update(int $id, array $data): AlertContact;
    public function delete(int $id): bool;
    public function getForUser(int $userId): Collection;
}

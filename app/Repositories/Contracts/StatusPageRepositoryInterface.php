<?php

namespace App\Repositories\Contracts;

use App\Models\StatusPage;

interface StatusPageRepositoryInterface
{
    public function create(array $data): StatusPage;
    public function update(int $id, array $data): StatusPage;
    public function findBySlug(string $slug): ?StatusPage;
}

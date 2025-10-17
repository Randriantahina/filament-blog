<?php

namespace App\Repositories;

use App\Models\StatusPage;
use App\Repositories\Contracts\StatusPageRepositoryInterface;
use Illuminate\Support\Arr;

class StatusPageRepository implements StatusPageRepositoryInterface
{
    public function create(array $data): StatusPage
    {
        $statusPage = StatusPage::create(Arr::except($data, 'monitors'));
        $statusPage->monitors()->sync($data['monitors']);
        return $statusPage;
    }

    public function update(int $id, array $data): StatusPage
    {
        $statusPage = StatusPage::findOrFail($id);
        $statusPage->update(Arr::except($data, 'monitors'));
        if (isset($data['monitors'])) {
            $statusPage->monitors()->sync($data['monitors']);
        }
        return $statusPage;
    }

    public function findBySlug(string $slug): ?StatusPage
    {
        return StatusPage::where('slug', $slug)->firstOrFail();
    }
}

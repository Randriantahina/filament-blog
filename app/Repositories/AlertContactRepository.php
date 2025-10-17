<?php

namespace App\Repositories;

use App\Models\AlertContact;
use App\Repositories\Contracts\AlertContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AlertContactRepository implements AlertContactRepositoryInterface
{
    public function create(array $data): AlertContact
    {
        return AlertContact::create($data);
    }

    public function update(int $id, array $data): AlertContact
    {
        $alertContact = AlertContact::findOrFail($id);
        $alertContact->update($data);
        return $alertContact;
    }

    public function delete(int $id): bool
    {
        return AlertContact::findOrFail($id)->delete();
    }
    public function getForUser(int $userId): Collection
    {
        return AlertContact::where("user_id", $userId)->get();
    }
}

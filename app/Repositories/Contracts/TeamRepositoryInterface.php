<?php

namespace App\Repositories\Contracts;

use App\Models\Team;
use Illuminate\Support\Collection;

interface TeamRepositoryInterface
{
    public function create(array $data): Team;
    public function find(int $id): ?Team;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function all(): Collection;
    public function addMember(Team $team, int $userId, string $role = 'member'): void;
    public function removeMember(Team $team, int $userId): void;
    public function updateMemberRole(Team $team, int $userId, string $role): void;
    public function getMembers(Team $team): Collection;
}

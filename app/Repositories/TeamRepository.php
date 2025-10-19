<?php

namespace App\Repositories;

use App\Models\Team;
use App\Models\User;
use App\Repositories\Contracts\TeamRepositoryInterface;
use Illuminate\Support\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    public function create(array $data): Team
    {
        return Team::create($data);
    }

    public function find(int $id): ?Team
    {
        return Team::find($id);
    }

    public function update(int $id, array $data): bool
    {
        $team = $this->find($id);
        if ($team) {
            return $team->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $team = $this->find($id);
        if ($team) {
            return $team->delete();
        }
        return false;
    }

    public function all(): Collection
    {
        return Team::all();
    }

    public function addMember(Team $team, int $userId, string $role = 'member'): void
    {
        $team->members()->attach($userId, ['role' => $role]);
    }

    public function removeMember(Team $team, int $userId): void
    {
        $team->members()->detach($userId);
    }

    public function updateMemberRole(Team $team, int $userId, string $role): void
    {
        $team->members()->updateExistingPivot($userId, ['role' => $role]);
    }

    public function getMembers(Team $team): Collection
    {
        return $team->members;
    }
}

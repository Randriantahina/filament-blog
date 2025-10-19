<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use App\Repositories\Contracts\TeamRepositoryInterface;
use Illuminate\Support\Collection;

class TeamService
{
    public function __construct(
        protected TeamRepositoryInterface $teamRepository,
    ) {}

    public function createTeam(array $data, User $owner): Team
    {
        $data["owner_id"] = $owner->id;
        $team = $this->teamRepository->create($data);
        $team->members()->attach($owner->id, ["role" => "owner"]);

        return $team;
    }

    public function addMember(Team $team, TeamMemberDto $memberDto): void
    {
        $team
            ->members()
            ->attach($memberDto->userId, ["role" => $memberDto->role]);
    }

    public function removeMember(Team $team, int $userId): void
    {
        $team->members()->detach($userId);
    }

    public function updateMemberRole(Team $team, TeamMemberDto $memberDto): void
    {
        $team
            ->members()
            ->updateExistingPivot($memberDto->userId, [
                "role" => $memberDto->role,
            ]);
    }

    public function getMembers(Team $team): Collection
    {
        return $this->teamRepository->getMembers($team);
    }

    public function updateTeam(int $teamId, array $data): ?Team
    {
        $this->teamRepository->update($teamId, $data);
        return $this->teamRepository->find($teamId);
    }

    public function deleteTeam(int $teamId): bool
    {
        return $this->teamRepository->delete($teamId);
    }
}

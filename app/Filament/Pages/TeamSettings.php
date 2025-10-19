<?php

namespace App\Filament\Pages;

use App\DataTransferObjects\TeamMemberDto;
use App\Models\User;
use App\Services\TeamService;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TeamSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = "heroicon-o-cog-6-tooth";

    protected static string $view = "filament.pages.team-settings";

    protected static ?string $navigationGroup = "Teams";

    public ?array $data = [];
    public Collection $members;
    public TeamService $teamService;

    public function mount(): void
    {
        $user = Auth::user();
        if ($user && $user->currentTeam) {
            $this->form->fill($user->currentTeam->toArray());
            $this->loadMembers();
        } else {
            $this->form->fill([]); // Fill with empty data to prevent crash
            $this->members = collect(); // Ensure members is also initialized
            Notification::make()
                ->title(
                    "No current team found. Please create or select a team.",
                )
                ->warning()
                ->persistent()
                ->send();
        }
    }

    protected function loadMembers(): void
    {
        $this->members = $this->teamService->getMembers(
            Auth::user()->currentTeam,
        );
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([TextInput::make("name")->label("Team Name")->required()])
            ->statePath("data");
    }

    public function save()
    {
        $team = Auth::user()->currentTeam;
        $team->update($this->form->getState());

        Notification::make()
            ->title("Team name updated successfully")
            ->success()
            ->send();
    }

    public function removeMember(int $userId): void
    {
        $team = Auth::user()->currentTeam;
        $this->teamService->removeMember($team, $userId);

        Notification::make()
            ->title("Member removed successfully")
            ->success()
            ->send();

        $this->loadMembers();
    }

    public function updateMemberRole(int $userId, string $role): void
    {
        $team = Auth::user()->currentTeam;
        $this->teamService->updateMemberRole(
            $team,
            new TeamMemberDto($userId, $role),
        );

        Notification::make()
            ->title("Member role updated successfully")
            ->success()
            ->send();

        $this->loadMembers();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make("addMember")
                ->label("Add Member")
                ->form([
                    TextInput::make("email")
                        ->label("User Email")
                        ->email()
                        ->required()
                        ->exists("users", "email"),
                    Select::make("role")
                        ->options([
                            "owner" => "Owner",
                            "member" => "Member",
                        ])
                        ->required()
                        ->default("member"),
                ])
                ->action(function (array $data) {
                    $userToAdd = User::where("email", $data["email"])->first();
                    $team = Auth::user()->currentTeam;

                    if ($team->members->contains($userToAdd->id)) {
                        Notification::make()
                            ->title("User is already a member of this team.")
                            ->warning()
                            ->send();
                        return;
                    }

                    $this->teamService->addMember(
                        $team,
                        new TeamMemberDto($userToAdd->id, $data["role"]),
                    );

                    Notification::make()
                        ->title("Member added successfully")
                        ->success()
                        ->send();

                    $this->loadMembers();
                }),
        ];
    }
}

<?php

namespace App\Services;

use App\Models\AlertContact;
use App\Repositories\Contracts\AlertContactRepositoryInterface;
use App\DataTransferObjects\AlertContactDto;
use Illuminate\Database\Eloquent\Collection;

class AlertContactService
{
    public function __construct(
        protected AlertContactRepositoryInterface $alertContactRepository,
    ) {}

    public function createContact(AlertContactDto $dto): AlertContact
    {
        return $this->alertContactRepository->create((array) $dto);
    }

    public function updateContact(int $id, array $data): AlertContact
    {
        return $this->alertContactRepository->update($id, $data);
    }

    public function deleteContact(int $id): bool
    {
        return $this->alertContactRepository->delete($id);
    }

    public function getContactsForUser(int $userId): Collection
    {
        return $this->alertContactRepository->getForUser($userId);
    }
}

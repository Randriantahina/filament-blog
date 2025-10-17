<?php

namespace App\Services;

use App\DataTransferObjects\StatusPageDto;
use App\Models\StatusPage;
use App\Repositories\Contracts\StatusPageRepositoryInterface;

class StatusPageService
{
    public function __construct(
        protected StatusPageRepositoryInterface $statusPageRepository
    ) {}

    public function createPage(StatusPageDto $dto): StatusPage
    {
        return $this->statusPageRepository->create((array) $dto);
    }

    public function updatePage(int $id, array $data): StatusPage
    {
        return $this->statusPageRepository->update($id, $data);
    }

    public function getPageForPublicView(string $slug): StatusPage
    {
        $statusPage = $this->statusPageRepository->findBySlug($slug);
        $statusPage->load('monitors.checkLogs');
        return $statusPage;
    }
}

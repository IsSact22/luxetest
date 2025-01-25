<?php

namespace App\DTOs;

class CamoDTO
{
    public string $customer;

    public int $ownerId;

    public string $contract;

    public int $camId;

    public int $aircraftId;

    public string $description;

    public string $startDate;

    public string $estimatedFinishDate;

    public ?string $finishDate;

    public string $location;

    public function __construct(
        string $customer,
        int $ownerId,
        string $contract,
        int $camId,
        int $aircraftId,
        string $description,
        string $startDate,
        string $estimatedFinishDate,
        ?string $finishDate,
        string $location
    ) {
        $this->customer = $customer;
        $this->ownerId = $ownerId;
        $this->contract = $contract;
        $this->camId = $camId;
        $this->aircraftId = $aircraftId;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->estimatedFinishDate = $estimatedFinishDate;
        $this->finishDate = $finishDate ?? null;
        $this->location = $location;
    }
}

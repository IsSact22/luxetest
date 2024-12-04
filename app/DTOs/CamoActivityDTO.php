<?php

namespace App\DTOs;

use App\ActivityStatus;
use App\ApprovalStatus;

class CamoActivityDTO
{
    public ?int $id;

    public int $camoId;

    public int $laborRateId;

    public bool $required;

    public string $date;

    public string $name;

    public ?string $description;

    public string $estimateTime;

    public ?string $startedAt;

    public ?string $completedAt;

    public ActivityStatus $status;

    public ?string $comments;

    public float $laborMount;

    public ?float $materialMount;

    public ?string $materialInformation;

    public ?string $awr;

    public ApprovalStatus $approvalStatus;

    public function __construct(
        ?int $id,
        int $camoId,
        int $laborRateId,
        bool $required,
        string $date,
        string $name,
        ?string $description,
        string $estimateTime,
        ?string $startedAt,
        ?string $completedAt,
        ActivityStatus $status,
        ?string $comments,
        float $laborMount,
        ?float $materialMount,
        ?string $materialInformation,
        ?string $awr,
        ApprovalStatus $approvalStatus
    ) {
        $this->id = $id;
        $this->camoId = $camoId;
        $this->laborRateId = $laborRateId;
        $this->required = $required;
        $this->date = $date;
        $this->name = $name;
        $this->description = $description;
        $this->estimateTime = $estimateTime;
        $this->startedAt = $startedAt;
        $this->completedAt = $completedAt;
        $this->status = $status;
        $this->comments = $comments;
        $this->laborMount = $laborMount;
        $this->materialMount = $materialMount;
        $this->materialInformation = $materialInformation;
        $this->awr = $awr;
        $this->approvalStatus = $approvalStatus;
    }
}

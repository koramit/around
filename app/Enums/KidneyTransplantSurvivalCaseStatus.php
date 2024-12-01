<?php

namespace App\Enums;

enum KidneyTransplantSurvivalCaseStatus: int
{
    case ACTIVE = 1;
    case GRAFT_LOSS = 2;
    case DEAD = 3;
    case LOSS_FOLLOW_UP = 4;
    case DELETED = 5;

    public function label(): string
    {
        return match ($this) {
            KidneyTransplantSurvivalCaseStatus::ACTIVE => 'active',
            KidneyTransplantSurvivalCaseStatus::GRAFT_LOSS => 'graft loss',
            KidneyTransplantSurvivalCaseStatus::LOSS_FOLLOW_UP => 'loss f/u',
            KidneyTransplantSurvivalCaseStatus::DEAD => 'dead',
            KidneyTransplantSurvivalCaseStatus::DELETED => 'deleted',
        };
    }

    public static function fromGraftPatientStatus(string $graft, string $patient): self
    {
        $status = $graft.' / '.$patient;

        return match ($status) {
            'graft function / alive' => self::ACTIVE,
            'graft loss / alive' => self::GRAFT_LOSS,
            'graft loss / dead' => self::DEAD,
            'loss follow up / loss follow up' => self::LOSS_FOLLOW_UP,
        };
    }

    public static function fromLabel(string $label): self
    {
        return match ($label) {
            'active' => self::ACTIVE,
            'graft loss' => self::GRAFT_LOSS,
            'loss f/u' => self::LOSS_FOLLOW_UP,
            'dead' => self::DEAD,
        };
    }
}

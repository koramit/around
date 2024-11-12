<?php

namespace App\Enums;

enum KidneyTransplantSurvivalCaseStatus: int
{
    case ACTIVE = 1;
    case LOSS_FOLLOW_UP = 2;
    case GRAFT_FUNCTION_DEAD = 3;
    case GRAFT_LOSS_DEAD = 4;
    case GRAFT_FUNCTION_ALIVE = 5;
    case GRAFT_LOSS_ALIVE = 6;
}

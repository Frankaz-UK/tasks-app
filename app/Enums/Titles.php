<?php

declare(strict_types=1);

namespace App\Enums;

enum Titles: string
{
    case MR = 'Mr';
    case MASTER = 'Master';
    case SIR = 'Sir';
    case MRS = 'Mrs';
    case MISS = 'Miss';
    case MS = 'Ms';
    case DR = 'Dr';
    case PROFESSOR = 'Professor';
    case LORD = 'Lord';
    case LADY = 'Lady';
    case THE_RIGHT_HONOURABLE = 'The Right Honourable';
    case MX = 'Mx';
}

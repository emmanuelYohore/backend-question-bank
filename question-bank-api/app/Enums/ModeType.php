<?php

namespace App\Enums;

enum ModeType: string
{
    case SYSTEMATIQUE = 'systematique';
    case ALEATOIRE = 'aleatoire';
    case ADAPTATIF = 'adaptatif';
}
<?php

namespace App\Enums;

enum FormatReponseType: string
{
    case QCM = 'qcm';
    case QCU = 'qcu';
    case TEXTE = 'texte';
    case EVN = 'evn';
}
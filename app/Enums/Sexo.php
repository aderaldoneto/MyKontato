<?php

declare(strict_types=1);

namespace App\Enums;

enum Sexo: string
{
    case Masculino = 'masculino';
    case Feminino = 'feminino';
    case Outro = 'outro';
    case PrefiroNaoInformar = 'prefiro_nao_informar';
}

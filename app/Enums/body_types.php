<?php
namespace App\Enums;

enum body_types: string
{
    case Slim_slender = 'slim slender';
    case Average_medium = 'average medium';
    case A_few_extra_pounds = 'A few exta pounds';
    case Muscular_athletic = 'Muscular_athletic';
    case Curvy = 'Curvy';
    case Voluptuous = 'Voluptuous';
    case Big_and_beautiful = 'Big and beautiful';
    case Stocky = 'stocky';
    case Large = 'large';
    case Extra_large = 'extra large';
    case Disabled = 'disabled';

    public function label()
    {
        return (string) str($this->name)->replace('_', ' ');
    }

}

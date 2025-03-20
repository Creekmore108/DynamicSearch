<?php
namespace App\Enums;

enum relationship_types: string
{
    case casual = 'casual';
    case serious = 'serious';
    case marriage = 'marriage';
    case friendship = 'friendship';
    case polyamorous = 'polyamorous';
    case open = 'open';

    public function label()
    {
        return (string) str($this->name)->replace('_', ' ');
    }
}

<?php
namespace App\Enums;

enum sexual_preferences: string
{
    case straight  = 'straight';
    case gay = 'gay';
    case lesbian = 'lesbian';
    case bisexual = 'bisexual';
    case pansexual = 'pansexual';
    case asexual = 'asexual';
    case queer = 'queer';
    case questioning = 'questioning';


    public function label()
    {
        return (string) str($this->name)->replace('_', ' ');
    }
}

    // public function label()
    // {
    //     return (string) str($this->name)->replace('_', ' ');
    // }

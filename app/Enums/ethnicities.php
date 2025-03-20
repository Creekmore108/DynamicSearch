<?php
namespace App\Enums;

enum ethnicities: string
{
    case asian = 'Asian';
    case black = 'Black/African';
    case hispanic = 'Hispanic/Latino';
    case indian = 'Indian';
    case middle_eastern = 'Middle Eastern';
    case native_american = 'Native American';
    case pacific_islander = 'Pacific Islander';
    case white = 'White/Caucasian';
    case multiracial = 'Multiracial';
    case other = 'Other';


    public function label()
    {
        return (string) str($this->name)->replace('_', ' ');
    }

}

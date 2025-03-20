<?php
namespace App\Enums;

enum Spiritual_beliefs: string
{
    case Personal_Spiritual_Path  = '1';
    case Conscious_Spiritual = '2';
    case New_Thought = '3';
    case Self_Realization_Fellowship = '4';
    case Bahai = '5';
    case Advaita = '6';
    case Humanism = '7';
    case Native_American = '8';
    case Rastafarian = '9';
    case Eastern_Philosophy = '10';
    case Buddhism = '11';
    case Taoism = '12';
    case Falun_Gong = '13';
    case Hinduism = '14';
    case Sufism = '15';
    case Sikh = '16';
    case Religious_Science = '17';
    case Unitarian = '18';
    case Eckankar = '19';
    case Paganism = '20';
    case Jewish_Spiritual = '21';
    case Jewish= '22';
    case Christian_Spiritual = '23';
    case Christian = '24';
    case Catholic_Spiritual = '25';
    case Catholic = '26';
    case Muslim = '27';
    case Scientology = '28';
    case Agnostic = '29';
    case Atheist = '30';
    case Gnosticism = '31';
    case Tell_you_later = '32';
    case None = '33';
    case Other = '34';

    public function label()
    {
        return (string) str($this->name)->replace('_', ' ');
    }

    // get_dbName() maps to enums returning table row name
    // it is called from the preferences view and passes to the preferences.check-item component
    public function get_dbName(): string
    {
        return match($this){
            self::Personal_Spiritual_Path => 'spiritual_personal_path',
            self::Conscious_Spiritual => 'spiritual_concious',
            self::New_Thought => 'spiritual_new_thought',
            self::Self_Realization_Fellowship => 'spiritual_srf',
            self::Bahai => 'spiritual_bahai',
            self::Advaita => 'spiritual_advaita',
            self::Humanism => 'spiritual_humanism',
            self::Native_American => 'spiritual_native_american',
            self::Rastafarian => 'spiritual_rastafarian',
            self::Eastern_Philosophy => 'spiritual_eastern',
            self::Buddhism => 'spiritual_buddhism',
            self::Taoism => 'spiritual_taoism',
            self::Falun_Gong => 'spiritual_falun_gong',
            self::Hinduism => 'spiritual_hinduism',
            self::Sufism => 'spiritual_sufism',
            self::Sikh => 'spiritual_sikh',
            self::Religious_Science => 'spiritual_religious_science',
            self::Unitarian => 'spiritual_unitarian',
            self::Eckankar => 'spiritual_eckankar',
            self::Paganism => 'spiritual_paganism',
            self::Jewish_Spiritual => 'spiritual_jewish_spiritual',
            self::Jewish => 'spiritual_jewish',
            self::Christian_Spiritual => 'spiritual_christian_spiritual',
            self::Christian => 'spiritual_christian',
            self::Catholic_Spiritual => 'spiritual_catholic_spiritual',
            self::Catholic => 'spiritual_catholic',
            self::Muslim => 'spiritual_muslim',
            self::Scientology => 'spiritual_scientology',
            self::Agnostic => 'spiritual_agnostic',
            self::Atheist => 'spiritual_atheist',
            self::Gnosticism => 'spiritual_gnosticism',
            self::None => 'spiritual_none',
            self::Other => 'spiritual_other',
            self::Tell_you_later => 'spiritual_later',
            default => 'No Name Found',
        };
    }

    public static function group(): string
    {
        return 'spiritual';
    }

}

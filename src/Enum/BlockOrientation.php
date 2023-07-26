<?php

namespace App\Enum;

enum BlockOrientation: string
{
    case BT = 'BT';
    case TB = 'TB';
    case TT = 'TT';
    case BB = 'BB';
    case RL = 'RL';
    case LR = 'LR';
    case RR = 'RR';
    case LL = 'LL';
    case TR = 'TR';
    case LB = 'LB';
    case RT = 'RT';  
    case BL = 'BL';
    case RB = 'RB';
    case TL = 'TL';
    case BR = 'BR';
    case LT = 'LT';
    case V = 'V';
    case H = 'H';

    public static function orientation(BlockOrientation $blockOrientation): string
    {
        return match($blockOrientation) {
            BlockOrientation::BT, BlockOrientation::TB, BlockOrientation::TT, BlockOrientation::BB => 'V',
            BlockOrientation::RL, BlockOrientation::LR, BlockOrientation::RR, BlockOrientation::LL => 'H',
            BlockOrientation::LB, BlockOrientation::TR => 'LB',
            BlockOrientation::RT, BlockOrientation::BL => 'RT',
            BlockOrientation::RB, BlockOrientation::TL => 'TL',
            BlockOrientation::BR, BlockOrientation::LT => 'BR',
        };
    }
}

<?php

/*
 * ENUM FILE
 * since PHP doesn't have an enum type,
 * enums are defined as abstract classes with static members
 */

abstract class Result
{
    const SubmittedImage = 0;
    const SubmittedMessage = 1;
    const ErrorInvalidFileType = 2;
    const ErrorInvalidColorChannel = 3;
}

abstract class Channel
{
    const RED = 'red';
    const GREEN = 'green';
    const BLUE = 'blue';
    const ALL = 'all';

    static function isValidChannel(string $name): bool
    {
        switch ($name) {
            case $name === self::RED:
            case $name === self::BLUE:
            case $name === self::GREEN:
            case $name === self::ALL:
                return true;
            default:
                return false;
        }
    }
}


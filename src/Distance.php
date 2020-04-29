<?php

declare(strict_types=1);

namespace Geokit;

use function json_encode;
use function preg_match;
use function sprintf;

/**
 * Inspired by GeoPy's distance class (https://github.com/geopy/geopy)
 */
final class Distance
{
    public const UNIT_METERS     = 'meters';
    public const UNIT_KILOMETERS = 'kilometers';
    public const UNIT_MILES      = 'miles';
    public const UNIT_YARDS      = 'yards';
    public const UNIT_FEET       = 'feet';
    public const UNIT_INCHES     = 'inches';
    public const UNIT_NAUTICAL   = 'nautical';

    public const DEFAULT_UNIT = self::UNIT_METERS;

    /** @var array<float> */
    private static $units = [
        self::UNIT_METERS => 1.0,
        self::UNIT_KILOMETERS => 1000.0,
        self::UNIT_MILES => 1609.344,
        self::UNIT_YARDS => 0.9144,
        self::UNIT_FEET => 0.3048,
        self::UNIT_INCHES => 0.0254,
        self::UNIT_NAUTICAL => 1852.0,
    ];

    /** @var array<string> */
    private static $aliases = [
        'meter' => self::UNIT_METERS,
        'metre' => self::UNIT_METERS,
        'metres' => self::UNIT_METERS,
        'm' => self::UNIT_METERS,
        'kilometer' => self::UNIT_KILOMETERS,
        'kilometre' => self::UNIT_KILOMETERS,
        'kilometres' => self::UNIT_KILOMETERS,
        'km' => self::UNIT_KILOMETERS,
        'mile' => self::UNIT_MILES,
        'mi' => self::UNIT_MILES,
        'yard' => self::UNIT_YARDS,
        'yd' => self::UNIT_YARDS,
        'foot' => self::UNIT_FEET,
        'ft' => self::UNIT_FEET,
        'nm' => self::UNIT_NAUTICAL,
        'inch' => self::UNIT_INCHES,
        'in' => self::UNIT_INCHES,
        '″' => self::UNIT_INCHES,
        'nauticalmile' => self::UNIT_NAUTICAL,
        'nauticalmiles' => self::UNIT_NAUTICAL,
    ];

    /** @var float */
    private $value;

    public function __construct(float $value, string $unit = self::DEFAULT_UNIT)
    {
        if (!isset(self::$units[$unit])) {
            throw new Exception\InvalidArgumentException(
                sprintf(
                    'Invalid unit %s.',
                    json_encode($unit)
                )
            );
        }

        $this->value = $value * self::$units[$unit];
    }

    public static function fromString(string $input): Distance
    {
        if ((bool) preg_match('/(\-?\d+\.?\d*)\s*((kilo)?met[er]+s?|m|km|miles?|mi|yards?|yd|feet|foot|ft|in(ch(es)?)?|″|nautical(mile)?s?|nm)?$/u', $input, $match)) {
            $unit = self::DEFAULT_UNIT;

            if (isset($match[2])) {
                $unit = $match[2];

                if (!isset(self::$units[$unit])) {
                    $unit = self::$aliases[$unit];
                }
            }

            return new self((float) $match[1], $unit);
        }

        throw new Exception\InvalidArgumentException(
            sprintf(
                'Cannot create Distance from string %s.',
                json_encode($input)
            )
        );
    }

    public function meters(): float
    {
        return $this->value / self::$units[self::UNIT_METERS];
    }

    public function m(): float
    {
        return $this->meters();
    }

    public function kilometers(): float
    {
        return $this->value / self::$units[self::UNIT_KILOMETERS];
    }

    public function km(): float
    {
        return $this->kilometers();
    }

    public function miles(): float
    {
        return $this->value / self::$units[self::UNIT_MILES];
    }

    public function mi(): float
    {
        return $this->miles();
    }

    public function yards(): float
    {
        return $this->value / self::$units[self::UNIT_YARDS];
    }

    public function yd(): float
    {
        return $this->yards();
    }

    public function feet(): float
    {
        return $this->value / self::$units[self::UNIT_FEET];
    }

    public function ft(): float
    {
        return $this->feet();
    }

    public function inches(): float
    {
        return $this->value / self::$units[self::UNIT_INCHES];
    }

    public function in(): float
    {
        return $this->inches();
    }

    public function nautical(): float
    {
        return $this->value / self::$units[self::UNIT_NAUTICAL];
    }

    public function nm(): float
    {
        return $this->nautical();
    }
}

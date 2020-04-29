<?php

declare(strict_types=1);

namespace Geokit;

use ArrayIterator;
use Generator;
use PHPUnit\Framework\TestCase;
use function json_encode;

class PositionTest extends TestCase
{
    public function testConstructor(): void
    {
        $position = Position::fromXY(1.0, 2.0);

        self::assertSame(1.0, $position->x());
        self::assertSame(2.0, $position->y());
        self::assertSame(1.0, $position->longitude());
        self::assertSame(2.0, $position->latitude());
    }

    public function testConstructorWithInts(): void
    {
        $position = Position::fromXY(1, 2);

        self::assertSame(1.0, $position->x());
        self::assertSame(2.0, $position->y());
        self::assertSame(1.0, $position->longitude());
        self::assertSame(2.0, $position->latitude());
    }

    public function testFromCoordinatesWithArray(): void
    {
        $position = Position::fromCoordinates([1, 2]);

        self::assertSame(1.0, $position->x());
        self::assertSame(2.0, $position->y());
        self::assertSame(1.0, $position->longitude());
        self::assertSame(2.0, $position->latitude());
    }

    public function testFromCoordinatesWithIterator(): void
    {
        $position = Position::fromCoordinates(new ArrayIterator([1, 2]));

        self::assertSame(1.0, $position->x());
        self::assertSame(2.0, $position->y());
        self::assertSame(1.0, $position->longitude());
        self::assertSame(2.0, $position->latitude());
    }

    public function testFromCoordinatesWithGenerator(): void
    {
        $position = Position::fromCoordinates((/** @return Generator<float> */ static function (): Generator {
            yield 1;
            yield 2;
        })());

        self::assertSame(1.0, $position->x());
        self::assertSame(2.0, $position->y());
        self::assertSame(1.0, $position->longitude());
        self::assertSame(2.0, $position->latitude());
    }

    public function testFromCoordinatesThrowsExceptionForMissingXCoordinate(): void
    {
        $this->expectException(Exception\MissingCoordinateException::class);

        Position::fromCoordinates([]);
    }

    public function testFromCoordinatesThrowsExceptionForMissingYCoordinate(): void
    {
        $this->expectException(Exception\MissingCoordinateException::class);

        Position::fromCoordinates([1]);
    }

    public function testToCoordinates(): void
    {
        $position = Position::fromXY(1, 2);

        self::assertSame([1.0, 2.0], $position->toCoordinates());
    }

    public function testJsonSerialize(): void
    {
        $position = Position::fromXY(1.1, 2);

        self::assertSame('[1.1,2]', json_encode($position));
    }
}

<?php

declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \fab\date;

final class dateTest extends TestCase
{
    
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            date::class,
            new date
        );
    }
    /*
    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        date::is_zero_date('10-10-2020');
    }
    */
    public function testIsZeroDateTrue(): void
    {
        $this->assertEquals(
            true,
            date::is_zero_date('0000-00-00')
        );
    }
    public function testIsZeroDateFalse(): void
    {
        $this->assertEquals(
            false,
            date::is_zero_date('2019-10-10')
        );
    }
    public function testDateToInvert(): void
    {
        $this->assertEquals(
            '2020-01-20',
            date::date_to_invert('20-01-2020')
        );
    }
    public function testDateToInvertZero(): void
    {
        $this->assertEquals(
            '0000-00-00',
            date::date_to_invert('00-00-0000')
        );
    }
    public function testDateItaZero(): void
    {
        $this->assertEquals(
            '00-00-0000',
            date::date_to_ita('00-00-0000')
        );
    }
    public function testDateSqlZero(): void
    {
        $this->assertEquals(
            '0000-00-00',
            date::date_to_sql('00-00-0000')
        );
    }
}

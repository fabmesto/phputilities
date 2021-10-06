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

    public function testDateToInvertWithSlash(): void
    {
        $this->assertEquals(
            '2020-01-20',
            date::date_to_invert('20/01/2020')
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
    public function testDateItaZeroInvert(): void
    {
        $this->assertEquals(
            '00-00-0000',
            date::date_to_ita('0000-00-00')
        );
    }
    public function testDateSqlZero(): void
    {
        $this->assertEquals(
            '0000-00-00',
            date::date_to_sql('0000-00-00')
        );
    }
    public function testDateSqlZeroInvert(): void
    {
        $this->assertEquals(
            '0000-00-00',
            date::date_to_sql('00-00-0000')
        );
    }

    public function testDateSqlZeroInvertWithHIS(): void
    {
        $this->assertEquals(
            '0000-00-00',
            date::date_to_sql('00-00-0000 00:00:00')
        );
    }


    public function testDateSqlWithSlash(): void
    {
        $this->assertEquals(
            '2021-01-21',
            date::date_to_sql('21/01/2021')
        );
    }

    public function testFormatWithSlash(): void
    {
        $this->assertEquals(
            '2021-01-12',
            date::date_format_to('12/01/2021', 'Y-m-d')
        );
    }

    public function test_date_to_sql_with_time(): void
    {
        $this->assertEquals(
            '2021-01-12 10:24',
            date::date_to_sql('12-01-2021 10:24')
        );
    }

    public function test_date_to_ita_with_time(): void
    {
        $this->assertEquals(
            '12-01-2021 10:24',
            date::date_to_ita('2021-01-12 10:24')
        );
    }

    public function test_date_to_sql_with_time_slash(): void
    {
        $this->assertEquals(
            '2021-01-12 06:10',
            date::date_to_sql('12/01/2021 6:10')
        );
    }

    public function test_date_to_ita_with_time_slash(): void
    {
        $this->assertEquals(
            '12-01-2021 07:03',
            date::date_to_ita('2021/01/12 7:03')
        );
    }

    public function test_date_to_ita_with_time_only_date(): void
    {
        $this->assertEquals(
            '12-01-2021',
            date::date_to_ita('2021/01/12 7:03', true)
        );
    }

    public function test_date_ita_to_ita_with_time_only_date(): void
    {
        $this->assertEquals(
            '12-01-2021',
            date::date_to_ita('12/01/2021 7:03', true)
        );
    }
}

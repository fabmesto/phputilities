<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use fab\date;

final class dateTest extends TestCase
{
    public function testIsZeroDateTrue(): void
    {
        $this->assertTrue(date::is_zero_date('0000-00-00'));
    }

    public function testIsZeroDateFalse(): void
    {
        $this->assertFalse(date::is_zero_date('2019-10-10'));
    }

    public function testDateToInvert(): void
    {
        $this->assertEquals('2020-01-20', date::date_to_invert('20-01-2020'));
    }

    public function testDateToInvertWithSlash(): void
    {
        $this->assertEquals('2020-01-20', date::date_to_invert('20/01/2020'));
    }

    public function testDateToInvertZero(): void
    {
        $this->assertEquals('0000-00-00', date::date_to_invert('00-00-0000'));
    }

    public function testDateItaZero(): void
    {
        $this->assertEquals('00-00-0000', date::date_to_ita('00-00-0000'));
    }

    public function testDateItaZeroInvert(): void
    {
        $this->assertEquals('00-00-0000', date::date_to_ita('0000-00-00'));
    }

    public function testDateSqlZero(): void
    {
        $this->assertEquals('0000-00-00', date::date_to_sql('0000-00-00'));
    }

    public function testDateSqlZeroInvert(): void
    {
        $this->assertEquals('0000-00-00', date::date_to_sql('00-00-0000'));
    }

    public function testDateSqlZeroInvertWithHIS(): void
    {
        $this->assertEquals('0000-00-00', date::date_to_sql('00-00-0000 00:00:00'));
    }

    public function testDateSqlWithSlash(): void
    {
        $this->assertEquals('2021-01-21', date::date_to_sql('21/01/2021'));
    }

    public function testFormatWithSlash(): void
    {
        $this->assertEquals('2021-01-12', date::date_format_to('12/01/2021', 'Y-m-d'));
    }

    public function testDateToSqlWithTime(): void
    {
        $this->assertEquals('2021-01-12 10:24', date::date_to_sql('12-01-2021 10:24'));
    }

    public function testDateToItaWithTime(): void
    {
        $this->assertEquals('12-01-2021 10:24', date::date_to_ita('2021-01-12 10:24'));
    }

    public function testDateToSqlWithTimeSlash(): void
    {
        $this->assertEquals('2021-01-12 06:10', date::date_to_sql('12/01/2021 6:10'));
    }

    public function testDateToItaWithTimeSlash(): void
    {
        $this->assertEquals('12-01-2021 07:03', date::date_to_ita('2021/01/12 7:03'));
    }

    public function testDateToItaWithTimeOnlyDate(): void
    {
        $this->assertEquals('12-01-2021', date::date_to_ita('2021/01/12 7:03', true));
    }

    public function testDateItaToItaWithTimeOnlyDate(): void
    {
        $this->assertEquals('12-01-2021', date::date_to_ita('12/01/2021 7:03', true));
    }

    public function testNiceDatePast(): void
    {
        $data = (new DateTime('-2 days'))->format('Y-m-d H:i:s');
        $this->assertStringContainsString('giorni fa', date::nice_date($data));
    }

    public function testNiceDateFuture(): void
    {
        $data = (new DateTime('+1 day'))->format('Y-m-d H:i:s');
        $this->assertStringContainsString('tra', date::nice_date($data));
    }

    public function testNiceDateZero(): void
    {
        $this->assertEquals('-', date::nice_date('0000-00-00'));
    }

    public function testIsValidDateCorrect(): void
    {
        $this->assertTrue(date::is_valid_date('2022-05-15'));
    }

    public function testIsValidDateIncorrect(): void
    {
        $this->assertFalse(date::is_valid_date('15-15-2022'));
    }

    public function testInvertZeroDateDefaultFormat(): void
    {
        $this->assertEquals('0000-00-00', date::invert_zero_date(null));
    }

    public function testInvertZeroDateSwitching(): void
    {
        $this->assertEquals('00-00-0000', date::invert_zero_date('0000-00-00'));
        $this->assertEquals('0000-00-00', date::invert_zero_date('00-00-0000'));
        $this->assertEquals('00-00-0000', date::invert_zero_date('0000-00-00 00:00:00'));
        $this->assertEquals('0000-00-00', date::invert_zero_date('00-00-0000 00:00:00'));
    }

    public function testDateFormatFromTo(): void
    {
        $this->assertEquals('2024-05-10', date::date_format_from_to('10/05/2024', 'd/m/Y', 'Y-m-d'));
    }
}

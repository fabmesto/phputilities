<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use fab\functions;

final class FunctionsTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(functions::class, new functions);
    }

    public function testParamsFromGet(): void
    {
        $_GET = ['key1' => 'a', 'key2' => 'b'];
        $params = ['key1' => 1, 'key2' => 2, 'key3' => 3];

        $expected = ['key1' => 'a', 'key2' => 'b', 'key3' => 3];
        $this->assertEquals($expected, functions::params_from_get($params));
    }

    public function testParamsFromPost(): void
    {
        $_POST = ['key1' => 'aa', 'key2' => 'bb'];
        $params = ['key1' => 1, 'key2' => 2, 'key3' => 3];

        $expected = ['key1' => 'aa', 'key2' => 'bb', 'key3' => 3];
        $this->assertEquals($expected, functions::params_from_post($params));
    }

    public function testArraymultiToKeysValues(): void
    {
        $input = [
            ['key' => 0, 'text' => 'testo 0'],
            ['key' => 1, 'text' => 'testo 1'],
        ];
        $expected = [0 => 'testo 0', 1 => 'testo 1'];

        $this->assertEquals($expected, functions::arraymulti_to_keys_values($input, 'key', 'text'));
    }

    public function testArraymultiToKeysValuesGroup(): void
    {
        $input = [
            [
                'group' => [
                    ['key' => 0, 'text' => 'testo 0'],
                    ['key' => 1, 'text' => 'testo 1']
                ],
                'g_name' => 'gruppo 1',
            ],
            [
                'group' => [
                    ['key' => 2, 'text' => 'testo 2'],
                    ['key' => 3, 'text' => 'testo 3']
                ],
                'g_name' => 'gruppo 2',
            ]
        ];
        $expected = [
            'gruppo 1' => [0 => 'testo 0', 1 => 'testo 1'],
            'gruppo 2' => [2 => 'testo 2', 3 => 'testo 3'],
        ];

        $this->assertEquals($expected, functions::arraymulti_to_keys_values_group($input, 'group', 'g_name', 'key', 'text'));
    }

    public function testArraymultiToKeys(): void
    {
        $input = [
            ['a' => 1],
            ['a' => 2],
            ['a' => 3],
            ['k' => 11],
        ];
        $expected = [1, 2, 3];

        $this->assertEquals($expected, functions::arraymulti_to_keys($input, 'a'));
    }

    public function testGetInQueryString(): void
    {
        $_GET = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];
        $excluded = ['a', 'b'];
        $expected = 'c=3&d=4&';

        $this->assertEquals($expected, functions::get_in_query_string($excluded));
    }

    public function testSplitComuneProvincia(): void
    {
        $input = 'Bari (Ba)';
        $expected = ['comune' => 'Bari', 'provincia' => 'Ba'];

        $this->assertEquals($expected, functions::split_comune_provincia($input));
    }

    public function testValueByKey(): void
    {
        $array = ['a' => 1, 'b' => 2];
        $this->assertEquals(2, functions::value_by_key($array, 'b'));
    }

    public function testCsvToArray(): void
    {
        $filepath = __DIR__ . '/assets/test.csv';
        $expected = [
            1 => ['id' => '1', 'nome' => 'nico', 'cognome' => 'rossi'],
            2 => ['id' => '2', 'nome' => 'fra', 'cognome' => 'bianchi'],
            3 => ['id' => '3', 'nome' => 'fab', 'cognome' => 'verdi'],
        ];

        $this->assertEquals($expected, functions::csv_to_array($filepath, [], ','));
    }
}

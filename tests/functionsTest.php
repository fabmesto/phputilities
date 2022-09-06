<?php

declare(strict_types=1);

use \PHPUnit\Framework\TestCase;
use \fab\functions;

final class functionsTest extends TestCase
{

    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            functions::class,
            new functions
        );
    }
    /*
    public function testCannotBeCreatedFromInvalidEmailAddress(): void
    {
        $this->expectException(InvalidArgumentException::class);

        date::is_zero_date('10-10-2020');
    }
    */
    public function test_params_from_get(): void
    {
        $_GET['key1'] = 'a';
        $_GET['key2'] = 'b';
        $params = ['key1' => 1, 'key2' => 2, 'key3' => 3];
        $this->assertEquals(
            ['key1' => 'a', 'key2' => 'b', 'key3' => 3],
            functions::params_from_get($params)
        );
        unset($_GET);
    }

    public function test_params_from_post(): void
    {
        $_POST['key1'] = 'aa';
        $_POST['key2'] = 'bb';
        $params = ['key1' => 1, 'key2' => 2, 'key3' => 3];
        $this->assertEquals(
            ['key1' => 'aa', 'key2' => 'bb', 'key3' => 3],
            functions::params_from_post($params)
        );
        unset($_POST);
    }

    public function test_arraymulti_to_keys_values(): void
    {
        $params = [
            ['key' => 0, 'text' => 'testo 0', 'other' => 'other'],
            ['key' => 1, 'text' => 'testo 1', 'other' => 'other']
        ];
        $expected = [0 => 'testo 0', 1 => 'testo 1'];

        $result = functions::arraymulti_to_keys_values($params, 'key', 'text');

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function test_arraymulti_to_keys_values_group(): void
    {
        $params = [
            [
                'group' => [
                    ['key' => 0, 'text' => 'testo 0', 'other' => 'other'],
                    ['key' => 1, 'text' => 'testo 1', 'other' => 'other']
                ],
                'g_name' => 'gruppo 1',
            ],
            [
                'group' => [
                    ['key' => 2, 'text' => 'testo 2', 'other' => 'other'],
                    ['key' => 3, 'text' => 'testo 3', 'other' => 'other']
                ],
                'g_name' => 'gruppo 2',
            ]
        ];
        $expected = [
            'gruppo 1' => [

                0 => 'testo 0',
                1 => 'testo 1',
            ],
            'gruppo 2' => [

                2 => 'testo 2',
                3 => 'testo 3',
            ],
        ];

        $result = functions::arraymulti_to_keys_values_group($params, 'group', 'g_name', 'key', 'text');

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function test_arraymulti_to_keys()
    {
        $params = [
            ['a' => 1],
            ['a' => 2],
            ['a' => 3],
            ['k' => 11],
            ['k' => 21],
            ['k' => 31],
        ];
        $expected = [1, 2, 3];
        $result = functions::arraymulti_to_keys($params, 'a');

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function test_get_in_query_string()
    {
        $expected = 'c=3&d=4&';

        $params = ['a', 'b'];
        $_GET['a'] = 1;
        $_GET['b'] = 2;
        $_GET['c'] = 3;
        $_GET['d'] = 4;
        $result = functions::get_in_query_string($params);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function test_split_comune_provincia()
    {
        $comune = 'Bari';
        $provincia = 'Ba';
        $expected = ['comune' => $comune, 'provincia' => $provincia];
        $params = $comune . " (" . $provincia . ")";
        $result = functions::split_comune_provincia($params);

        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function test_value_by_key()
    {
        $array = ['a' => 1, 'b' => 2];
        $key = 'b';
        $result = functions::value_by_key($array, $key);
        $this->assertEquals(
            2,
            $result
        );
    }

    public function test_csv_to_array()
    {
        $filepath = dirname(__FILE__) . '/assets/test.csv';
        $result = functions::csv_to_array($filepath, [], ',');

        $expected = [
            1 => [
                'id' => '1',
                'nome' => 'nico',
                'cognome' => 'rossi'
            ],
            2 => [
                'id' => '2',
                'nome' => 'fra',
                'cognome' => 'bianchi'
            ],
            3 => [
                'id' => '3',
                'nome' => 'fab',
                'cognome' => 'verdi'
            ],
        ];
        $this->assertEquals(
            $expected,
            $result
        );
    }
}

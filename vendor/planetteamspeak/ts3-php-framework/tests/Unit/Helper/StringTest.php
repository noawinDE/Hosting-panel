<?php
use PHPUnit\Framework\TestCase;

require_once('libraries/TeamSpeak3/Helper/String.php');

class StringTest extends TestCase
{
    public function testReplace()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $string->replace("world", "word");

        $this->assertEquals("Hello word!", (string) $string);


        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $string->replace("hello", "Hey", false);

        $this->assertEquals("Hey world!", (string) $string);
    }

    public function testStartsWith()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertTrue($string->startsWith("Hello"));
        $this->assertFalse($string->startsWith("world"));
    }

    public function testEndsWith()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertTrue($string->endsWith("!"));
        $this->assertFalse($string->endsWith("."));
    }

    public function testFindFirst()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals(2, $string->findFirst("l"));
    }

    public function testFindLast()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals(9, $string->findLast("l"));
    }

    public function testToLower()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals("hello world!", $string->toLower());
    }

    public function testToUpper()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals("HELLO WORLD!", $string->toUpper());
    }

    public function testContains()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertTrue($string->contains(""));
        $this->assertTrue($string->contains("[a-z]{5}", true));
        $this->assertTrue($string->contains("world"));
        $this->assertFalse($string->contains("word"));
    }

    public function testSubstr()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals("ello", $string->substr(1, 4));
        $this->assertEquals("world", $string->substr(-6, 5));
    }

    public function testSplit()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $array  = $string->split('l', 3);
        $this->assertCount(3, $array);
        $this->assertEquals('He', $array[0]);
        $this->assertEmpty($array[1]);
    }

    public function testIsInt()
    {
        $tests = [
            "1"             => true,
            "+1"            => false,
            "-1"            => false,
            "hello"         => false,
            "1.0"           => false,
            ".1"            => false,

            // From https://goo.gl/C5v9wT
            "0x539"         => false,
            "0b10100111001" => false,
            "1337e0"        => false,
            "9.1"           => false,
        ];

        foreach ($tests as $test => $expected) {
            $string = new \TeamSpeak3_Helper_String($test);
            $this->assertSame($string->isInt(), $expected);
        }
    }

    public function testFactory()
    {
        $string = \TeamSpeak3_Helper_String::factory("hello world");

        $this->assertEquals("hello world", $string->toString());
    }

    public function testArg()
    {
        $string = new \TeamSpeak3_Helper_String("%h %w");

        $string->arg(["w" => "world", "h" => "Hello"]);

        $this->assertEquals(
            "Hello world",
            $string->toString()
        );
    }

    public function testAppend()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world");
        $string->append('!');
        $this->assertEquals("Hello world!", $string->toString());
    }

    public function testPrepend()
    {
        $string = new \TeamSpeak3_Helper_String("world!");
        $string->prepend("Hello ");
        $this->assertEquals("Hello world!", $string->toString());
    }

    public function testSection()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");

        $section = $string->section(' ');
        $this->assertEquals("Hello", $section->toString());

        $section = $string->section(' ', 1, 1);
        $this->assertEquals("world!", $section->toString());

        $section = $string->section(' ', 0, 1);
        $this->assertEquals("Hello world!", $section->toString());

        $section = $string->section(' ', 3, 3);
        $this->assertNull($section);
    }

    public function testToCrc32()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals(crc32("Hello world!"), $string->toCrc32());
    }

    public function testToMd5()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals(md5("Hello world!"), $string->toMd5());
    }

    public function testToSha1()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals(sha1("Hello world!"), $string->toSha1());
    }

    public function testIsUtf8()
    {
        $string = new \TeamSpeak3_Helper_String(utf8_encode("??pfel"));
        $this->assertTrue($string->isUtf8());

        $string = new \TeamSpeak3_Helper_String(utf8_decode("??pfel"));
        $this->assertNotTrue($string->isUtf8());
    }

    public function testToUft8()
    {
        $notUtf8 = utf8_decode("??pfel");
        $string  = new \TeamSpeak3_Helper_String($notUtf8);
        $this->assertEquals(utf8_encode($notUtf8), $string->toUtf8());
    }

    public function testToBase64()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals(base64_encode("Hello world!"), $string->toBase64());
    }

    public function testFromBase64()
    {
        $string = \TeamSpeak3_Helper_String::fromBase64(base64_encode("Hello world!"));
        $this->assertEquals("Hello world!", $string->toString());
    }

    public function testToHex()
    {
        \TeamSpeak3::init();
        $string = new \TeamSpeak3_Helper_String("Hello");
        $this->assertEquals("48656C6C6F", $string->toHex());
    }

    public function testFromHex()
    {
        $string = \TeamSpeak3_Helper_String::fromHex("48656C6C6F");
        $this->assertEquals("Hello", $string->toString());
    }

    public function testTransliterate()
    {
        $utf8_accents = array(
            "??" => "a",
            "??" => "o",
            "??" => "d",
            "???" => "f",
            "??" => "e",
            "??" => "s",
            "??" => "o",
            "??" => "ss",
            "??" => "a",
            "??" => "r",
            "??" => "t",
            "??" => "n",
            "??" => "a",
            "??" => "k",
            "??" => "s",
            "???" => "y",
            "??" => "n",
            "??" => "l",
            "??" => "h",
            "???" => "p",
            "??" => "o",
            "??" => "u",
            "??" => "e",
            "??" => "e",
            "??" => "c",
            "???" => "w",
            "??" => "c",
            "??" => "o",
            "???" => "s",
            "??" => "o",
            "??" => "g",
            "??" => "t",
            "??" => "s",
            "??" => "e",
            "??" => "c",
            "??" => "s",
            "??" => "i",
            "??" => "u",
            "??" => "c",
            "??" => "e",
            "??" => "w",
            "???" => "t",
            "??" => "u",
            "??" => "c",
            "??" => "oe",
            "??" => "e",
            "??" => "y",
            "??" => "a",
            "??" => "l",
            "??" => "u",
            "??" => "u",
            "??" => "s",
            "??" => "g",
            "??" => "l",
            "??" => "f",
            "??" => "z",
            "???" => "w",
            "???" => "b",
            "??" => "a",
            "??" => "i",
            "??" => "i",
            "???" => "d",
            "??" => "t",
            "??" => "r",
            "??" => "ae",
            "??" => "i",
            "??" => "r",
            "??" => "e",
            "??" => "ue",
            "??" => "o",
            "??" => "e",
            "??" => "n",
            "??" => "n",
            "??" => "h",
            "??" => "g",
            "??" => "d",
            "??" => "j",
            "??" => "y",
            "??" => "u",
            "??" => "u",
            "??" => "u",
            "??" => "t",
            "??" => "y",
            "??" => "o",
            "??" => "a",
            "??" => "l",
            "???" => "w",
            "??" => "z",
            "??" => "i",
            "??" => "a",
            "??" => "g",
            "???" => "m",
            "??" => "o",
            "??" => "i",
            "??" => "u",
            "??" => "i",
            "??" => "z",
            "??" => "a",
            "??" => "u",
            "??" => "th",
            "??" => "dh",
            "??" => "ae",
            "??" => "u",
            "??" => "e",
            "??" => "oe",
            "??" => "A",
            "??" => "O",
            "??" => "D",
            "???" => "F",
            "??" => "E",
            "??" => "S",
            "??" => "O",
            "??" => "A",
            "??" => "R",
            "??" => "T",
            "??" => "N",
            "??" => "A",
            "??" => "K",
            "??" => "S",
            "???" => "Y",
            "??" => "N",
            "??" => "L",
            "??" => "H",
            "???" => "P",
            "??" => "O",
            "??" => "U",
            "??" => "E",
            "??" => "E",
            "??" => "C",
            "???" => "W",
            "??" => "C",
            "??" => "O",
            "???" => "S",
            "??" => "O",
            "??" => "G",
            "??" => "T",
            "??" => "S",
            "??" => "E",
            "??" => "C",
            "??" => "S",
            "??" => "I",
            "??" => "U",
            "??" => "C",
            "??" => "E",
            "??" => "W",
            "???" => "T",
            "??" => "U",
            "??" => "C",
            "??" => "Oe",
            "??" => "E",
            "??" => "Y",
            "??" => "A",
            "??" => "L",
            "??" => "U",
            "??" => "U",
            "??" => "S",
            "??" => "G",
            "??" => "L",
            "??" => "F",
            "??" => "Z",
            "???" => "W",
            "???" => "B",
            "??" => "A",
            "??" => "I",
            "??" => "I",
            "???" => "D",
            "??" => "T",
            "??" => "R",
            "??" => "Ae",
            "??" => "I",
            "??" => "R",
            "??" => "E",
            "??" => "Ue",
            "??" => "O",
            "??" => "E",
            "??" => "N",
            "??" => "N",
            "??" => "H",
            "??" => "G",
            "??" => "D",
            "??" => "J",
            "??" => "Y",
            "??" => "U",
            "??" => "U",
            "??" => "U",
            "??" => "T",
            "??" => "Y",
            "??" => "O",
            "??" => "A",
            "??" => "L",
            "???" => "W",
            "??" => "Z",
            "??" => "I",
            "??" => "A",
            "??" => "G",
            "???" => "M",
            "??" => "O",
            "??" => "I",
            "??" => "U",
            "??" => "I",
            "??" => "Z",
            "??" => "A",
            "??" => "U",
            "??" => "Th",
            "??" => "Dh",
            "??" => "Ae",
            "??" => "E",
            "??" => "Oe",
        );

        $string = "";
        $result = "";

        foreach ($utf8_accents as $k => $v) {
            $string .= $k;
            $result .= $v;
        }

        $string = new \TeamSpeak3_Helper_String($string);
        $this->assertEquals($result, $string->transliterate()->toString());
    }

    public function testSpaceToPercent()
    {
        $string = new \TeamSpeak3_Helper_String("Hello world!");
        $this->assertEquals("Hello%20world!", $string->spaceToPercent());
    }
}

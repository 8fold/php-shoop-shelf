<?php
namespace Eightfold\Json\Tests;

use Eightfold\Json\Tests\BaseTest;

use Eightfold\Json\Read;

class ReadTest extends BaseTest
{
	private $path = __DIR__ . '/../composer.json';

	public function testNotNull() {
		$json = file_get_contents($this->path);

		$reader = new Read($json);

		$this->assertNotNull($reader);
	}

    public function testGetKeyReturnsExpectedString() {
        $expected = '8fold/php-json';

        $result = Read::fromPath($this->path)->getKey('name')->fetch();

        $this->assertEquality($expected, $result);
    }

    public function testGetNamedKeyReturnsExpectedString() {
        $expected = '8fold/php-json';

        $result = Read::fromPath($this->path)->name()->fetch();

        $this->assertEquality($expected, $result);
    }

    public function testGetKeyFromFetchReturnsExpectedString() {
        $expected = '8fold/php-json';

        $result = Read::fromPath($this->path)->fetch('name');

        $this->assertEquality($expected, $result);
    }

    public function testGetDeepKeyReturnsExpectedString() {
        $expected = 'Can go deep';

        $result = Read::fromPath($this->path)
        	->extra()
        	->getKey('8fold')
        	->tests()
        	->fetchIndex(4);

        $this->assertEquality($expected, $result);
    }
}

<?php
namespace Eightfold\Json\Tests;

use Eightfold\Json\Tests\BaseTest;

use Eightfold\Json\Write;

class WriteTest extends BaseTest
{
    public function tearDown()
    {
        $dest = __DIR__ .'/test.json';
        if (file_exists($dest)) {
            unlink(__DIR__ .'/test.json');
        }
        parent::tearDown();
    }

    public function testCanInstantiateWriter()
    {
        $result = new Write();
        $this->assertNotNull($result);
    }

    public function testCanWriteJsonFile()
    {
        $dest = __DIR__ .'/test.json';
        $writer = new Write();
        $writer->save($dest);

        $this->assertTrue(file_exists($dest));
    }

    public function testCanCompileArrays()
    {
        $arrays = Write::writeJson(
            [
                // Original
                'member' => 'true',
                'next' => 'test'
            ],
            [
                // Draft or Update
                'next' => 'test2'
            ])->compile(true);

        $this->assertEquals($arrays['next'], 'test2');
    }

    public function testCanWriteJson()
    {
        $dest = __DIR__ .'/test.json';
        $json = Write::writeJson(['member' => 'true'])->encode(true);
        $decoded = json_decode($json, true);
        $this->assertTrue(array_key_exists('member', $decoded));
        $this->assertEquals('true', $decoded['member']);
    }

    public function testCanAddMember()
    {
        $array = Write::writeJson()
            ->addMember('member', 'true')
            ->compile(true);
        $this->assertTrue(array_key_exists('member', $array));
    }
}

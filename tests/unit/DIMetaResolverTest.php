<?php

use BigBIT\DIMeta\ArgMetaData;
use BigBIT\DIMeta\DIMetaResolver;
use Symfony\Component\Cache\Simple\ArrayCache;

class DepClass {

}

class TestClass {
    public function __construct(DepClass $depA, string $depB, ?bool $depC = null, $depD = 5)
    {

    }
}

class DIMetaResolverTest extends TestCase
{
    public function testGetClassMeta()
    {
        $cache = new ArrayCache();
        $metaResolver = DIMetaResolver::create($cache);

        $classMeta = $metaResolver->getClassMeta(TestClass::class);

        $this->assertNotEmpty($classMeta);

        $this->assertInstanceOf(ArgMetaData::class, $classMeta[0]);
        $this->assertEquals(false, $classMeta[0]->allowsNull);
        $this->assertEquals(false, $classMeta[0]->isBuiltin);
        $this->assertEquals(DepClass::class, $classMeta[0]->type);
        $this->assertEquals('depA', $classMeta[0]->name);

        $this->assertInstanceOf(ArgMetaData::class, $classMeta[1]);
        $this->assertEquals(false, $classMeta[1]->allowsNull);
        $this->assertEquals(true, $classMeta[1]->isBuiltin);
        $this->assertEquals("string", $classMeta[1]->type);
        $this->assertEquals('depB', $classMeta[1]->name);

        $this->assertInstanceOf(ArgMetaData::class, $classMeta[2]);
        $this->assertEquals(true, $classMeta[2]->allowsNull);
        $this->assertEquals(true, $classMeta[2]->isBuiltin);
        $this->assertEquals('bool', $classMeta[2]->type);
        $this->assertEquals('depC', $classMeta[2]->name);

        $this->assertInstanceOf(ArgMetaData::class, $classMeta[3]);
        $this->assertEquals(true, $classMeta[3]->allowsNull);
        $this->assertEquals(true, $classMeta[3]->isBuiltin);
        $this->assertEquals(null, $classMeta[3]->type);
        $this->assertEquals('depD', $classMeta[3]->name);

        $this->assertFalse(isset($classMeta[4]));
    }
}

<?php

namespace PHPDocsMD\Tests;

use PHPDocsMD\Tests\Fixtures\ExampleClass;
use PHPUnit\Framework\TestCase;

class ReflectorTest extends TestCase {

    /**
     * @var \PHPDocsMD\Reflector
     */
    private $reflector;

    /**
     * @var \PHPDocsMD\ClassEntity
     */
    private $class;

    protected function setUp()
    {
        $this->reflector = new \PHPDocsMD\Reflector('PHPDocsMD\\Tests\\Fixtures\\ExampleClass');
        $this->class = $this->reflector->getClassEntity();
    }

    function testClass()
    {
        $this->assertEquals('\PHPDocsMD\\Tests\\Fixtures\\ExampleClass', $this->class->getName());
        $this->assertEquals('This is a description of this class', $this->class->getDescription());
        $this->assertEquals('Class: \PHPDocsMD\\Tests\\Fixtures\\ExampleClass (abstract)', $this->class->generateTitle());
        $this->assertEquals('class-phpdocsmdtestsfixturesexampleclass-abstract', $this->class->generateAnchor());
        $this->assertFalse($this->class->isDeprecated());
        $this->assertFalse($this->class->hasIgnoreTag());

        $refl = new \PHPDocsMD\Reflector('PHPDocsMD\\Tests\\Fixtures\\ExampleClassDepr');
        $class = $refl->getClassEntity();
        $this->assertTrue($class->isDeprecated());
        $this->assertEquals('This one is deprecated Lorem te ipsum', $class->getDeprecationMessage());
        $this->assertFalse($class->hasIgnoreTag());

        $refl = new \PHPDocsMD\Reflector('PHPDocsMD\\Tests\\Fixtures\\ExampleInterface');
        $class = $refl->getClassEntity();
        $this->assertTrue($class->isInterface());
        $this->assertTrue($class->hasIgnoreTag());
    }

    function testFunctions()
    {

        $functions = $this->class->getFunctions();

        $this->assertNotEmpty($functions);

        $this->assertEquals('Description of a*a', $functions[0]->getDescription());
        $this->assertEquals(false, $functions[0]->isDeprecated());
        $this->assertEquals('funcA', $functions[0]->getName());
        $this->assertEquals('void', $functions[0]->getReturnType());
        $this->assertEquals('public', $functions[0]->getVisibility());

        $this->assertEquals('Description of b', $functions[1]->getDescription());
        $this->assertEquals(false, $functions[1]->isDeprecated());
        $this->assertEquals('funcB', $functions[1]->getName());
        $this->assertEquals('void', $functions[1]->getReturnType());
        $this->assertEquals('public', $functions[1]->getVisibility());


        $this->assertEquals('', $functions[2]->getDescription());
        $this->assertEquals('funcD', $functions[2]->getName());
        $this->assertEquals('void', $functions[2]->getReturnType());
        $this->assertEquals('public', $functions[2]->getVisibility());
        $this->assertEquals(false, $functions[2]->isDeprecated());

        // These function does not declare return type but the return
        // type should be guessable
        $this->assertEquals('mixed', $functions[3]->getReturnType());
        $this->assertEquals('bool', $functions[4]->getReturnType());
        $this->assertEquals('bool', $functions[5]->getReturnType());
        $this->assertTrue($functions[5]->isAbstract());
        $this->assertTrue($this->class->isAbstract());

        // Protected function have been put last
        $this->assertEquals('Description of c', $functions[6]->getDescription());
        $this->assertEquals(true, $functions[6]->isDeprecated());
        $this->assertEquals('This one is deprecated', $functions[6]->getDeprecationMessage());
        $this->assertEquals('funcC', $functions[6]->getName());
        $this->assertEquals('\\PHPDocsMD\\Tests\\Fixtures\\ExampleClass', $functions[6]->getReturnType());
        $this->assertEquals('protected', $functions[6]->getVisibility());

        $this->assertTrue( empty($functions[7]) ); // Should be skipped since tagged with @ignore */
    }

    function testStaticFunc() {
        $reflector = new \PHPDocsMD\Reflector('PHPDocsMD\\Tests\\Fixtures\\ClassWithStaticFunc');
        $functions = $reflector->getClassEntity()->getFunctions();
        $this->assertNotEmpty($functions);
        $this->assertEquals('', $functions[0]->getDescription());
        $this->assertEquals(false, $functions[0]->isDeprecated());
        $this->assertEquals(true, $functions[0]->isStatic());
        $this->assertEquals('', $functions[0]->getDeprecationMessage());
        $this->assertEquals('somStaticFunc', $functions[0]->getName());
        $this->assertEquals('public', $functions[0]->getVisibility());
        $this->assertEquals('float', $functions[0]->getReturnType());
    }

    function testParams()
    {
        $paramA = new \ReflectionParameter(array('PHPDocsMD\\Tests\\Fixtures\\ExampleClass', 'funcD'), 2);
        $paramB = new \ReflectionParameter(array('PHPDocsMD\\Tests\\Fixtures\\ExampleClass', 'funcD'), 3);
        $paramC = new \ReflectionParameter(array('PHPDocsMD\\Tests\\Fixtures\\ExampleClass', 'funcD'), 0);

        $typeA = \PHPDocsMD\Reflector::getParamType($paramA);
        $typeB = \PHPDocsMD\Reflector::getParamType($paramB);
        $typeC = \PHPDocsMD\Reflector::getParamType($paramC);

        $this->assertEmpty($typeC);
        $this->assertEquals('\\stdClass', $typeB);
        $this->assertEquals('\\PHPDocsMD\\Tests\\Fixtures\\ExampleInterface', $typeA);

        $functions = $this->class->getFunctions();

        $this->assertTrue($functions[2]->hasParams());
        $this->assertFalse($functions[5]->hasParams());

        $params = $functions[1]->getParams();
        $this->assertEquals('int', $params[0]->getType());

        $params = $functions[2]->getParams();
        $this->assertEquals(4, count($params));
        $this->assertEquals(false, $params[0]->getDefault());
        $this->assertEquals('$arg', $params[0]->getName());
        $this->assertEquals('mixed', $params[0]->getType());
        $this->assertEquals('array()', $params[1]->getDefault());
        $this->assertEquals('$arr', $params[1]->getName());
        $this->assertEquals('array', $params[1]->getType());
        $this->assertEquals('null', $params[2]->getDefault());
        $this->assertEquals('$depr', $params[2]->getName());
        $this->assertEquals('\\PHPDocsMD\\Tests\\Fixtures\\ExampleInterface', $params[2]->getType());
    }

    function testInheritedDocs()
    {
        $reflector = new \PHPDocsMD\Reflector('PHPDocsMD\\Tests\\Fixtures\\ClassImplementingInterface');
        $functions = $reflector->getClassEntity()->getFunctions();
        $this->assertEquals(4, count($functions));
        $this->assertEquals('aMethod', $functions[0]->getName());
        $this->assertEquals('int', $functions[0]->getReturnType());
        $this->assertFalse($functions[0]->isReturningNativeClass());
        $this->assertEquals('func', $functions[1]->getName());
        $this->assertEquals('\\stdClass', $functions[1]->getReturnType());
        $this->assertFalse($functions[1]->isAbstract());

        $this->assertTrue($functions[2]->isReturningNativeClass());
        $this->assertTrue($functions[3]->isReturningNativeClass());
    }

    function testTraitDocs()
    {
        $reflector = new \PHPDocsMD\Reflector('PHPDocsMD\\Tests\\Fixtures\\ClassUsingTrait');
        $functions = $reflector->getClassEntity()->getFunctions();
        $this->assertCount(5, $functions);
        $this->assertEquals('aMethod', $functions[0]->getName());
        $this->assertEquals('void', $functions[0]->getReturnType());
        $this->assertFalse($functions[0]->isReturningNativeClass());
        $this->assertEquals('func', $functions[1]->getName());
        $this->assertEquals('void', $functions[1]->getReturnType());
        $this->assertFalse($functions[1]->isAbstract());

        $this->assertEquals('funcTrait', $functions[2]->getName());

        $this->assertTrue($functions[3]->isReturningNativeClass());
        $this->assertTrue($functions[4]->isReturningNativeClass());
    }

    function testReferenceToImportedClass()
    {
        $reflector = new \PHPDocsMD\Reflector('PHPDocsMD\\Tests\\Fixtures\\InterfaceReferringToImportedClass');
        $functions = $reflector->getClassEntity()->getFunctions();
        $this->assertEquals('\\PHPDocsMD\\Console\\CLI', $functions[1]->getReturnType());
        $this->assertEquals('\\PHPDocsMD\\Console\\CLI[]', $functions[0]->getReturnType());
    }
}

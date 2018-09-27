<?php

namespace PHPDocsMD\Tests\Fixtures;

class ClassUsingTrait extends SomeClass {
    use ExampleTrait;

    public function func($arg='a') {}

    public function aMethod() {}

    /**
     * @return \FilesystemIterator
     */
    public function methodReturnNativeClass() {}

    /**
     * @return \FilesystemIterator[]
     */
    public function methodReturningArrayNativeClass() {}
}

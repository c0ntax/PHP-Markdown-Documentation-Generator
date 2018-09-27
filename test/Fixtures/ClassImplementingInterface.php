<?php

namespace PHPDocsMD\Tests\Fixtures;

class ClassImplementingInterface extends SomeClass implements ExampleInterface {
    /**
     * @inheritdoc
     */
    public function func($arg='a') {}

    /**
     * @inheritDoc
     */
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

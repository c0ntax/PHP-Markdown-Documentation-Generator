<?php

namespace PHPDocsMD\Tests\Fixtures;

use PHPDocsMD\Console\CLI;

interface InterfaceReferringToImportedClass {

    /**
     * @return CLI
     */
    function theFunc();

    /**
     * @return CLI[]
     */
    function funcReturningArr();

}

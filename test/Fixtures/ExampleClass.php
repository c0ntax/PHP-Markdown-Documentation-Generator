<?php
namespace PHPDocsMD\Tests\Fixtures;

/**
 * This is a description
 * of this class
 *
 * @package PHPDocsMD\Tests\Fixtures
 */
abstract class ExampleClass implements \Reflector {

    /**
     * Description of a*a
     * @param $arg
     * @param array $arr
     * @param int $bool
     */
    public function funcA($arg, array $arr, $bool=10) {

    }

    /**
     * Description of c
     * @deprecated This one is deprecated
     * @param $arg
     * @param array $arr
     * @param int $bool
     * @return \PHPDocsMD\Tests\Fixtures\ExampleClass
     */
    protected function funcC($arg, array $arr, $bool=10) {

    }

    /**
     * Description of b
     *
     * @example
     * <code>
     *  <?php
     *      $lorem = 'te';
     *      $ipsum = 'dolor';
     * </code>
     *
     * @param  int   $arg
     * @param array $arr
     * @param int $bool
     */
    function funcB($arg, array $arr, $bool=10) {

    }

    function funcD($arg, $arr=array(), ExampleInterface $depr=null, \stdClass $class) {

    }

    function getFunc() {}
    function hasFunc() {}
    abstract function isFunc();

    /**
     * @ignore
     */
    function someFunc() {

    }

    private function privFunc() {

    }

}

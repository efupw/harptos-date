<?php

namespace EFUPW\FR;

final class HarptosDateNameTest extends \PHPUnit_Framework_TestCase
{
    public function testOutputsInDaleReckoning() {
        $harptos_date_name = new HarptosDateName;
        $the_date = $harptos_date_name->getDate();
        $this->assertContains('DR', $the_date);
    }
}

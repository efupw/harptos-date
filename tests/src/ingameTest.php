<?php

namespace EFUPW\FR;

final class ingameTest extends \PHPUnit_Framework_TestCase {
    public function testOutputsInDaleReckoning() {
        $ingame = new ingame();
        $the_date = $ingame->getDate();
        $this->assertContains('DR', $the_date);
    }
}

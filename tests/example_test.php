<?php

final class ExampleTest extends CIPHPUnitTest
{
    public function testConfigItem()
    {
        $indexPage = $this->CI->config->item('index_page');
        $this->assertSame('index.php', $indexPage);
    }
}
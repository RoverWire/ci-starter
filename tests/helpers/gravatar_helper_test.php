<?php

final class GravatarHelperTest extends CIPHPUnitTest
{
    public function __construct() 
    {
        parent::__construct();
        $this->CI->load->helper('gravatar');
    }

    public function testGetGravatar()
    {
        $expected  = 'https://www.gravatar.com/avatar/e6d67fed862c439aa6e911ce49c7857d?s=128&d=mm&r=g';
        $generated = get_gravatar('admin@localhost.com', 128);
        $this->assertSame($expected, $generated);
    }

    public function testGravatarImageTag()
    {
        $expected  = '<img src="https://www.gravatar.com/avatar/e6d67fed862c439aa6e911ce49c7857d?s=128&d=mm&r=g" class="foo">';
        $generated = get_gravatar('admin@localhost.com', 128, 'mm', 'g', TRUE, ['class' => 'foo']);
        $this->assertSame($expected, $generated);
    }
}
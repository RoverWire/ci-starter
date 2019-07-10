<?php

final class CrudHelperTest extends CIPHPUnitTest
{
    public function __construct() 
    {
        parent::__construct();
        $this->CI->load->helper('crud');
    }
    
    public function testValidateSelect()
    {
        $this->assertSame('selected', validate_select('test', 'test'));
        $this->assertSame('', validate_select('test', 'other'));
    }

    public function testValidateCheckbox()
    {
        $this->assertSame('checked', validate_checkbox('test', 'test'));
        $this->assertSame('', validate_checkbox('test', 'other'));
    }
}
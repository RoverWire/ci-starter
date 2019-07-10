<?php
use PHPUnit\Framework\TestCase;
final class CrudHelperTest extends TestCase
{
    function __construct() 
    {
        parent::__construct();
        $this->CI = & get_instance();
        $this->CI->load->helper('crud');
    }
    
    public function test_validate_select()
    {
        $this->assertSame('selected', validate_select('test', 'test'));
    }

    public function test_validate_select_not_equal()
    {
        $this->assertSame('', validate_select('test', 'other'));
    }

    public function test_validate_checkbox()
    {
        $this->assertSame('checked', validate_checkbox('test', 'test'));
    }

    public function test_validate_checkbox_not_equal()
    {
        $this->assertSame('', validate_checkbox('test', 'other'));
    }
}
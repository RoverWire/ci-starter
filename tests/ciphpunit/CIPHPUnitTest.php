<?php

abstract class CIPHPUnitTest extends PHPUnit\Framework\TestCase
{
    /**
	 * @var CI_Controller CodeIgniter instance
	 */
    protected $CI = NULL;
    
    public function __construct()
    {
        parent::__construct();
        $this->create_ci_object();
    }

    protected function create_ci_object()
    {
        if ($this->CI === NULL) {
            $this->CI = & get_instance();
        }
    }

    protected function reset_ci_object()
    {
        $this->CI = NULL;
        $this->create_ci_object();
    }
}
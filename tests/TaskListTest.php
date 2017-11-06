<?php
	
/**
 * @author Francis Carreon
 */
class TaskListTest extends PHPUnit\Framework\TestCase
{
    private $CI;
    
    public function setUp()
    {
      $this->CI = &get_instance();
    }
    
    public function compareTasks()
    {
        $tasks  = $this->CI->tasks->all;
        
        $undone = 0;
        
        foreach ($tasks as $task)
            if ($task->status != 2)
                ++$undone;
        
        $this->assertGreaterThan(count($tasks), $undone);
    }
}
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!class_exists('PHPUnit_Framework_TestCase'))
    class_alias('PHPUnit\Framework\TestCase', 'PHPUnit_Framework_TestCase');
	
/**
 * @author Francis Carreon
 */
class TaskListTest extends PHPUnit_Framework_TestCase
{
    public function compareTasks()
    {
        $tasks          = new Tasks();
        $this->tasklist = $tasks->all();
        
        foreach ($this->tasklist as $task)
            if ($task->status != 2)
                $undone[] = $task;
        
        $this->assertGreaterThan(count($this->tasklist), count($undone));
    }
}
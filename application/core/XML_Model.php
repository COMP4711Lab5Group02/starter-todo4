<?php

class XML_Model extends Memory_Model
{
//---------------------------------------------------------------------------
//  Housekeeping methods
//---------------------------------------------------------------------------

	/**
	 * Constructor.
	 * @param string $origin Filename of the CSV file
	 * @param string $keyfield  Name of the primary key field
	 * @param string $entity	Entity name meaningful to the persistence
	 */
	function __construct($origin = null, $keyfield = 'id', $entity = null)
	{
		parent::__construct();
                
		// guess at persistent name if not specified
		if ($origin == null)
			$this->_origin = get_class($this);
		else
			$this->_origin = $origin;

		// remember the other constructor fields
		$this->_keyfield = $keyfield;
		$this->_entity = $entity;

		// start with an empty collection
		$this->_data = array(); // an array of objects
		$this->fields = array(); // an array of strings
		// and populate the collection
		$this->load();
	}

	/**
	 * Load the collection state appropriately, depending on persistence choice.
	 * OVER-RIDE THIS METHOD in persistence choice implementations
	 */
	protected function load() 
        {
            $file = simplexml_load_file($this->_origin)
                    or die("Error: Failed to load file");
            
            $this->_fields =
                    
            array
            (
                "id", "task", "priority", "size", 
                "group", "deadline", "status", "flag"
            );
            
            foreach($file->task as $task)
            {
                $record = new stdClass();
               
                foreach($this->_fields as $field)
                {
                    $record->{$field} = strcmp($field, 'task')?
                    
                    (string)$task->{$field}:
                    (string)$task->desc;
                }
                $key = $record->{$this->_keyfield};
                $this->_data[$key] = $record;
            }
            // rebuild the keys table
            $this->reindex();
        }

        /**
	 * Store the collection state appropriately, depending on persistence choice.
	 * OVER-RIDE THIS METHOD in persistence choice implementations
	 */
	protected function store()
	{
            // rebuild the keys table
            $this->reindex();
            
            $x = new SimpleXMLElement('<tasks></tasks>');
            
            foreach($this->_data as $key=>$record)
            {
                $temp = $x->addChild('task');
                
                foreach($this->_fields as $field)
                    $temp->addChild
                    (
                        strcmp($field, 'task') ? 
                            
                        $field : 'desc', $record->{$field}
                    );
            }
             
            $x->asXML($this->_origin);
	}

}

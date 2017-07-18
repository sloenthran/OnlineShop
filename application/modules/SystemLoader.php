<?php

	function SystemLoader($ClassName)
    {
	
		if(file_exists('./application/system/'.$ClassName.'.php'))
		{

			require_once('./application/system/'.$ClassName.'.php');
			
		}
		
		else
		{
		
			require_once('./application/modules/'.$ClassName.'.php');
		
		}

    }
	
	spl_autoload_register('SystemLoader');

?>
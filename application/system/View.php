<?php

	class View
	{
		
		var $TagList = array();
		var $BodyHTML;

		function Load($FileName)
		{
	
			$File = './view/'.$FileName.'.html';
		
			$FileHandle = fopen($File, "r");
		
			$this->BodyHTML = fread($FileHandle, filesize($File));
		
			fclose($FileHandle);
		
		}

		function Parse()
		{
			
			foreach($this->TagList as $Tag => $Value)
			{
				
				$this->BodyHTML = str_replace($Tag, $Value, $this->BodyHTML);
			
			}
			
			return $this->BodyHTML;
		
		}

		function Add($Tag, $Value)
		{
			
			$Tag = "{" . $Tag . "}";
			
			$this->TagList[$Tag] = $Value;
		
		}

		function Out()
		{
			
			global $MySQL;
			
			$Query = $MySQL->query("SELECT `value` FROM `settings` WHERE `name`='logo'");
			$Fetch = $Query->fetch();
			
			$this->Add('logo', $Fetch['value']);
			
			$this->Parse();
			
			echo $this->BodyHTML;
		
		}

		function Minify($Text)
		{
			
			$Text = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $Text);
			$Text = str_replace(': ', ':', $Text);
			$Text = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $Text);
			
			return $Text;
			
		}

	}
	
?>
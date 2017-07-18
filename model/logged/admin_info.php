<?php

	if($Core->CheckAdmin())
	{

		if($_POST['SAVE'])
		{
	
			$Prepare = $MySQL->prepare("UPDATE `info` SET `value`=:one WHERE `name`=:two");
	
			$Query = $MySQL->query("SELECT `name`, `value` FROM `info`");
		
			while($Fetch = $Query->fetch())
			{
			
				$Value = nl2br($_POST[$Fetch['name']]);
				
				$Prepare->bindValue(':one', $Value, PDO::PARAM_STR);
				$Prepare->bindValue(':two', $Fetch['name'], PDO::PARAM_STR);
			
				$Prepare->execute();
		
			}
			
			$View->Load('info');
			$View->Add('title', 'Teksty zostały zapisane');
			$View->Add('header', 'Teksty zapisane');
			$View->Add('info', 'Teksty zostały poprawnie zapisane!');
			$View->Add('back', 'index.php?pages=admin_info');
			$View->Out();
		
		}
	
		else
		{
			
			$Info .= '<form method="post" action="index.php?pages=admin_info">
		
				<input type="hidden" name="SAVE" value="true">';
	
			$Query = $MySQL->query("SELECT * FROM `info`");
		
			while($Fetch = $Query->fetch())
			{
				
				$Value = str_replace('<br />', '', $Fetch['value']);
		
				$Info .= '<br>'.$Fetch['name'].'<br><br><textarea name="'.$Fetch['name'].'">'.$Value.'</textarea><br>';
			
			}
			
			$Info .= '<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
	
			$View->Load("admin");
			$View->Add("title", "Regulamin & Kontakt");
			$View->Add("header", "Regulamin & Kontakt");
			$View->Add("info", $Info);
			$View->Out();
			
		}
	
	}
	
	else
	{
	
		$View->Load("info");
		$View->Add('title', 'Błąd :: Brak uprawnień');
		$View->Add('header', 'Błąd! Brak uprawnień!');
		$View->Add('info', 'Nie posiadasz uprawnień administracyjnych!');
		$View->Add('back', 'index.php?pages=home');
		$View->Out();
	
	}
	
?>
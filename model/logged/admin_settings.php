<?php

	if($Core->CheckAdmin())
	{

		if($_POST['SAVE'])
		{
	
			$Prepare = $MySQL->prepare("UPDATE `settings` SET `value`=:one WHERE `name`=:two");
	
			$Query = $MySQL->query("SELECT `name`, `value` FROM `settings`");
		
			while($Fetch = $Query->fetch())
			{
			
				$Value = $Core->ClearText($_POST[$Fetch['name']]);
				
				if($Fetch['value'] != $Value)
				{
			
					$Core->AddAdminLogs('Zmieniono wartość ustawienia <b>'.$Fetch['name'].'</b> z <b>'.$Fetch['value'].'</b> na <b>'.$Value.'</b>');
					
				}
		
				$Prepare->bindValue(':one', $Value, PDO::PARAM_STR);
				$Prepare->bindValue(':two', $Fetch['name'], PDO::PARAM_STR);
			
				$Prepare->execute();
		
			}
			
			$View->Load('info');
			$View->Add('title', 'Ustawienia zapisane');
			$View->Add('header', 'Ustawienia zapisane');
			$View->Add('info', 'Ustawienia zostały poprawnie zapisane!');
			$View->Add('back', 'index.php?pages=admin_settings');
			$View->Out();
		
		}
	
		else
		{
			
			$Info .= '<form method="post" action="index.php?pages=admin_settings">
		
				<input type="hidden" name="SAVE" value="true">';
	
			$Query = $MySQL->query("SELECT * FROM `settings`");
		
			while($Fetch = $Query->fetch())
			{
		
				if($Fetch['name'] != 'pay')
				{
			
					$Info .= '<br>'.$Fetch['name'].'<br><br><input type="text" name="'.$Fetch['name'].'" value="'.$Fetch['value'].'"><br>';
				
				}
			
				else
				{
				
					$Info .= '<br>Płatność<br><br><select name="pay">';
				
					$Pay = $Core->GetPay();
				
					foreach($Pay as $Key => $Value)
					{
				
						if($Value == $Fetch['value'])
						{
					
							$Info .= '<option value="'.$Value.'" selected>'.$Value.'</option>';
					
						}
					
						else
						{
					
							$Info .= '<option value="'.$Value.'">'.$Value.'</option>';
					
						}
				
					}
				
					$Info .= '</select><br>';
			
				}
			
			}
			
			$Info .= '<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
	
			$View->Load("admin_settings");
			$View->Add("title", "Ustawienia");
			$View->Add("header", "Ustawienia");
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
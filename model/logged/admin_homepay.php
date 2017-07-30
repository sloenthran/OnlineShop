<?php

	if($Core->CheckAdmin())
	{

		if($_POST['SAVE'])
		{
	
			$Prepare = $MySQL->prepare("UPDATE `homepay` SET `service`=:one WHERE `number`=:two");
	
			$Query = $MySQL->query("SELECT `number` FROM `homepay`");
		
			while($Fetch = $Query->fetch())
			{
			
				$Value = $Core->ClearText($_POST[$Fetch['number']]);
				
				$Prepare->bindValue(':one', $Value, PDO::PARAM_STR);
				$Prepare->bindValue(':two', $Fetch['number'], PDO::PARAM_STR);
			
				$Prepare->execute();
		
			}
			
			$View->Load('info');
			$View->Add('title', 'Ustawienia zapisane');
			$View->Add('header', 'Ustawienia zapisane');
			$View->Add('info', 'Ustawienia zostały poprawnie zapisane!');
			$View->Add('back', 'index.php?pages=admin_homepay');
			$View->Out();
			
			$Core->AddAdminLogs("Zmieniono ID usług HomePay");
		
		}
	
		else
		{
			
			$Info .= '<form method="post" action="index.php?pages=admin_homepay">
		
				<input type="hidden" name="SAVE" value="true">';
	
			$Query = $MySQL->query("SELECT * FROM `homepay`");
		
			while($Fetch = $Query->fetch())
			{
		
				$Info .= '<br>'.$Fetch['number'].'<br><br><input type="text" name="'.$Fetch['number'].'" value="'.$Fetch['service'].'"><br>';
			
			}
			
			$Info .= '<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
	
			$View->Load("admin_settings");
			$View->Add("title", "[ID Usług] HomePay");
			$View->Add("header", "[ID Usług] HomePay");
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
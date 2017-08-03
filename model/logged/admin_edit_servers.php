<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_POST['ID']);
		
		if($ID == '')
		{
			
			$Query = $MySQL->query("SELECT `id`,`name` FROM `servers`");
			
			if($Query->rowCount() > 0)
			{
			
				$Info .= '<form method="post" action="index.php?pages=admin_edit_servers">
				
					<br><select name="ID">';
				
					while($Fetch = $Query->fetch())
					{
						
						$Info .= '<option value="'.$Fetch['id'].'">'.$Fetch['name'].'</option>';
					
					}
				
				$Info .= '</select><br>
					
					<br><button type="submit" class="przycisk">Edytuj <i class="fa fa-chevron-circle-right"></i> </button>
		
				</form>';
		
				$View->Load("admin_servers");
				$View->Add('title', 'Edytuj serwer');
				$View->Add('header', 'Edytuj serwer');
				$View->Add('info', $Info);
				$View->Out();
			
			}
			
			else
			{
			
				$View->Load("info");
				$View->Add("title", "Błąd :: Brak serwerów");
				$View->Add("header", "Błąd! Brak serwerów do edycji!");
				$View->Add("info", "Nie ma żadnych serwerów do edycji!");
				$View->Add("back", "index.php?pages=admin_servers");
				$View->Out();
			
			}
		}
		
		else
		{
			
			if($_POST['SAVE'])
			{
				
				$Name = $Core->ClearText($_POST['NAME']);
				$IP = $Core->ClearText($_POST['IP']);
				$Port = $Core->ClearText($_POST['PORT']);
				
				$Query = $MySQL->prepare("UPDATE `servers` SET `name`=:one, `ip`=:two, `port`=:three WHERE `id`=:four");
				$Query->bindValue(":one", $Name, PDO::PARAM_STR);
				$Query->bindValue(":two", $IP, PDO::PARAM_STR);
				$Query->bindValue(":three", $Port, PDO::PARAM_INT);
				$Query->bindValue(":four", $ID, PDO::PARAM_INT);
				$Query->execute();
				
				$Core->AddAdminLogs("Zmieniono dane serwera ".$Name."");
				
				$View->Load("info");
				$View->Add("title", "Zmiany zapisane");
				$View->Add("header", "Zmiany zapisane!");
				$View->Add("info", "Zmiany zostały poprawnie zapisane!");
				$View->Add("back", "index.php?pages=admin_edit_servers");
				$View->Out();
				
			}
			
			else
			{
			
				$Query = $MySQL->prepare("SELECT * FROM `servers` WHERE `id`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
			
				$Fetch = $Query->fetch();
				
				$Info = '<form method="post" action="index.php?pages=admin_edit_servers">
		
					<input type="hidden" name="SAVE" value="true">
					<input type="hidden" name="ID" value="'.$ID.'">
			
					<br><input type="text" name="NAME" placeholder="Nazwa serwera" value="'.$Fetch['name'].'"><br>
					<br><input type="text" name="IP" placeholder="IP Serwera" value="'.$Fetch['ip'].'"><br>
					<br><input type="text" name="PORT" placeholder="Port serwera" value="'.$Fetch['port'].'"><br>
			
					<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
				</form>';
			
				$View->Load("admin_servers");
				$View->Add('title', 'Edytuj serwer');
				$View->Add('header', 'Edytuj serwer');
				$View->Add('info', $Info);
				$View->Out();
				
			}
			
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
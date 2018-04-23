<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
		$Action = $Core->ClearText($_GET['action']);
		
		if($ID == '' || $Action == '')
		{
			
			$Info .= '<table>
	
			<tr>
				
				<td class="nag">Nazwa</td>
				<td class="nag">Flagi</td>
				<td class="nag">Serwer</td>
				<td class="nag">Opcje</td>
		
			</tr>';
		
			$Query = $MySQL->query("SELECT * FROM `buy` ORDER BY `server` ASC");
			
			while($Fetch = $Query->fetch())
			{
				
				if($Fetch['server'] == 0)
				{
					
					$FetchThree['name'] = 'Wszystkie';
					
				}
				
				else
				{
				
					$QueryThree = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					$QueryThree->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
					$QueryThree->execute();
				
					$FetchThree = $QueryThree->fetch();
					
				}
				
				$Info .= '<tr>
					<td>'.$Fetch['name'].'</td>
					<td>'.$Fetch['flags'].'</td>
					<td>'.$FetchThree['name'].'</td>
					<td><a href="index.php?pages=admin_buy_list&id='.$Fetch['id'].'&action=edit"><i class="fa fa-scissors"></i></a> &nbsp;&nbsp;&nbsp; <a href="index.php?pages=admin_buy_list&id='.$Fetch['id'].'&action=delete"><i class="fa fa-times"></i></a></td>
				</tr>';
				
			}
			
			$Info .= '</table>';
	
			$View->Load("admin_servers");
			$View->Add('title', 'Lista usług');
			$View->Add('header', 'Lista usług');
			$View->Add("info", $Info);
			$View->Out();
			
		}
		
		else
		{
			
			if($Action == 'delete')
			{
				
				$Query = $MySQL->prepare("SELECT `name` FROM `buy` WHERE `id`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
				
				$Fetch = $Query->fetch();
				
				$Query = $MySQL->prepare("DELETE FROM `buy` WHERE `id`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
				
				$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`='1' WHERE `premium_id`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
				
				$Query = $MySQL->prepare("DELETE FROM `service` WHERE `buy_id`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
				
				$View->Load("info");
				$View->Add('title', 'Usługa usunięta');
				$View->Add('header', 'Usługa usunięta!');
				$View->Add('info', 'Usługa została poprawnie usunięta!');
				$View->Add('back', 'index.php?pages=admin_buy_list');
				$View->Out();
				
				$Core->AddAdminLogs("Usunięto usługę ".$Fetch['name']."");
				
			}
			
			else
			{
				
				if($_POST['SAVE'])
				{
					
					$Query = $MySQL->prepare("SELECT `name` FROM `buy` WHERE `id`=:one");
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					$Query->execute();
				
					$Fetch = $Query->fetch();
					
					$Name = $Core->ClearText($_POST['NAME']);
					$Flags = $Core->ClearText($_POST['FLAGS']);
					$Text = nl2br($_POST['TEXT']);
					
					$Query = $MySQL->prepare("UPDATE `buy` SET `name`=:one, `flags`=:three, `description`=:four WHERE `id`=:five");
					$Query->bindValue(":one", $Name, PDO::PARAM_STR);
					$Query->bindValue(":three", $Flags, PDO::PARAM_STR);
					$Query->bindValue(":four", $Text, PDO::PARAM_STR);
					$Query->bindValue(":five", $ID, PDO::PARAM_INT);
					$Query->execute();
					
					$View->Load("info");
					$View->Add('title', 'Zmiany zapisane');
					$View->Add('header', 'Zmiany zapisane!');
					$View->Add('info', 'Zmiany w usłudze zostały poprawnie zapisane!');
					$View->Add('back', 'index.php?pages=admin_buy_list');
					$View->Out();
					
					$Core->AddAdminLogs("Zmieniono szczegóły usługi ".$Fetch['name']."");
					
				}
				
				else
				{
					
					$Query = $MySQL->prepare("SELECT * FROM `buy` WHERE `id`=:one");
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					$Query->execute();
					
					$Fetch = $Query->fetch();
					
					$Value = str_replace('<br />', '', $Fetch['description']);
			
					$Info = '<form method="post" action="index.php?pages=admin_buy_list&id='.$Fetch['id'].'&action=edit">
		
						<input type="hidden" name="SAVE" value="true">
			
						<br><input type="text" name="NAME" placeholder="Nazwa" value="'.$Fetch['name'].'"><br>
						<br><input type="text" name="FLAGS" placeholder="Flagi" value="'.$Fetch['flags'].'"><br>
						<br><textarea name="TEXT" placeholder="Opis">'.$Value.'</textarea><br>
			
						<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
					</form>';
			
					$View->Load("admin_servers");
					$View->Add('title', 'Edycja usługi');
					$View->Add('header', 'Edycja usługi');
					$View->Add('info', $Info);
					$View->Out();
					
				}
				
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
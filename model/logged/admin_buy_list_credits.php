<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
		
		if($ID == '')
		{
			
			$Info .= '<table>
	
			<tr>

				<td class="nag">Serwer</td>
				<td class="nag">Opcje</td>
		
			</tr>';
		
			$Query = $MySQL->query("SELECT * FROM `buy_credits` ORDER BY `server` ASC");
			
			while($Fetch = $Query->fetch())
			{
				
				$QueryThree = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
				$QueryThree->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
				$QueryThree->execute();
				
				$FetchThree = $QueryThree->fetch();
				
				$Info .= '<tr>
					<td>'.$FetchThree['name'].'</td>
					<td><a href="index.php?pages=admin_buy_list_credits&id='.$Fetch['id'].'"><i class="fa fa-times"></i></a></td>
				</tr>';
				
			}
			
			$Info .= '</table>';
	
			$View->Load("admin_servers");
			$View->Add('title', 'Lista usług (kredyty)');
			$View->Add('header', 'Lista usług (kredyty)');
			$View->Add("info", $Info);
			$View->Out();
			
		}
		
		else
		{
			
			$Query = $MySQL->prepare("SELECT `server` FROM `buy_credits` WHERE `id`=:one");
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
			$Query->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
			$Query->execute();
			
			$Fetch = $Query->fetch();
			
			$Query = $MySQL->prepare("DELETE FROM `buy_credits` WHERE `id`=:one");
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			$Query->execute();
			
			$Query = $MySQL->prepare("DELETE FROM `service_credits` WHERE `buy_id`=:one");
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			$Query->execute();
			
			$View->Load("info");
			$View->Add('title', 'Usługa usunięta');
			$View->Add('header', 'Usługa usunięta!');
			$View->Add('info', 'Usługa została poprawnie usunięta!');
			$View->Add('back', 'index.php?pages=admin_buy_list_credits');
			$View->Out();
			
			$Core->AddAdminLogs("Usunięto obsługę kredytów z serwera <b>".$Fetch['name']."</b>");

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
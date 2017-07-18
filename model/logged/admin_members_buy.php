<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
		$Action = $Core->ClearText($_GET['action']);
		
		if($ID == '' || $Action == '')
		{

			$Info .= '<table>
	
			<tr>

				<td class="nag" width="150">Nick lub SID</td>
				<td class="nag">Nazwa usługi</td>
				<td class="nag">Serwer</td>
				<td class="nag" width="100">Czas</td>
				<td class="nag" width="50">Opcje</td>
		
			</tr>';
	
			$Query = $MySQL->query("SELECT * FROM `premium_cache` ORDER BY `server` ASC");
	
			while($Fetch = $Query->fetch())
			{
	
				$OldDate = $Fetch['time'];
		
				$Date = time();
		
				$Diff = $OldDate - $Date;
				$Days = floor($Diff / (24*60*60));

				$Diff  = $Diff - ($Days * 24*60*60);
				$Hours = floor($Diff / (60*60));

				$Diff    = $Diff - ($Hours * 60*60);
				$Minutes = floor($Diff / (60));
	
				$Seconds = $Diff - ($Minutes * 60);
		
				if($Days > 10000)
				{
			
					$Time = 'Bez limitu';
		
				}
				
				else if(time() >= $Fetch['time'])
				{
					
					$Time = 'W trakcie usuwania...';
					
				}
		
				else
				{
			
					$Time = ''.$Days.' dni<br> '.$Hours.' godzin<br> '.$Minutes.' minut<br> '.$Seconds.' sekund'.'';
		
				}
		
				$QueryTwo = $MySQL->prepare("SELECT `name` FROM `buy` WHERE `id`=:one");
				$QueryTwo->bindValue(":one", $Fetch['premium_id'], PDO::PARAM_INT);
				$QueryTwo->execute();
	
				$FetchTwo = $QueryTwo->fetch();
		
				$Data[0] = $FetchTwo['name'];
				$Data[1] = $FetchThree['value'];
		
				$QueryTwo = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
		
				$QueryTwo->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
		
				$QueryTwo->execute();
		
				$FetchTwo = $QueryTwo->fetch();
		
				$Info .= '<tr>
					<td>'.$Fetch['nick'].'</td>
					<td>'.$Data[0].'</td>
					<td>'.$FetchTwo['name'].'</td>
					<td>'.$Time.'</td>
					<td><a href="index.php?pages=admin_members_buy&id='.$Fetch['id'].'&action=edit"><i class="fa fa-scissors"></i></a> <br><br> <a href="index.php?pages=admin_members_buy&id='.$Fetch['id'].'&action=delete"><i class="fa fa-times"></i></a></td>
				</tr>';
	
			}
	
			$Info .= '</table>';
	
			$View->Load("admin_members");
			$View->Add('title', 'Lista usług');
			$View->Add('header', 'Lista usług');
			$View->Add('info', $Info);
			$View->Out();
			
		}
		
		else
		{
			
			if($Action == 'delete')
			{
				
				$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`='1' WHERE `id`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
				
				$View->Load("info");
				$View->Add('title', 'Usługa usunięta');
				$View->Add('header', 'Usługa usunięta!');
				$View->Add('info', 'Usługa została poprawnie usunięta!');
				$View->Add('back', 'index.php?pages=admin_members_buy');
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
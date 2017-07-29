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
				$Days = floor($Diff / 86400);

				$Diff  = $Diff - ($Days * 86400);
				$Hours = floor($Diff / 3600);

				$Diff    = $Diff - ($Hours * 3600);
				$Minutes = floor($Diff / 60);
	
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
			
			$Query = $MySQL->prepare("SELECT * FROM `premium_cache` WHERE `id`=:one");
			$Query->bindValue(":one", $ID, PDO::PARAM_INT);
			$Query->execute();

			$Fetch = $Query->fetch();
			
			if($Action == 'delete')
			{
				
				$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`='1' WHERE `id`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
				
				$Time = date("d-m-Y H:i:s", $Fetch['time']);
				
				$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
				$Query->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
				$Query->execute();
				
				$FetchTwo = $Query->fetch();
				
				$Core->AddAdminLogs('Usunięto usługę <b>#'.$ID.'</b> na nick <b>'.$Fetch['nick'].'</b> ważną do <b>'.$Time.'</b> z serwera <b>'.$FetchTwo['name'].'</b>');
				
				$View->Load("info");
				$View->Add('title', 'Usługa usunięta');
				$View->Add('header', 'Usługa usunięta!');
				$View->Add('info', 'Usługa została poprawnie usunięta!');
				$View->Add('back', 'index.php?pages=admin_members_buy');
				$View->Out();
				
			}
			
			else
			{
				
				if($_POST['SAVE'])
				{
					
					$Nick = $Core->ClearText($_POST['NAME']);
					$Time = $Core->ClearText($_POST['TIME']);
					
					$Time = strtotime($Time);
				
					if($Fetch['nick'] == $Nick)
					{
						
						$Query = $MySQL->prepare("UPDATE `premium_cache` SET `nick`=:one, `time`=:two WHERE `id`=:three");
						$Query->bindValue(":one", $Nick, PDO::PARAM_STR);
						$Query->bindValue(":two", $Time, PDO::PARAM_INT);
						$Query->bindValue(":three", $ID, PDO::PARAM_INT);
						$Query->execute();
					
					}
					
					else
					{
						
						$Query = $MySQL->prepare("UPDATE `premium_cache` SET `time`='1' WHERE `id`=:one");
						$Query->bindValue(":one", $ID, PDO::PARAM_INT);
						$Query->execute();
						
						$Buy = new Buy();
			
						$Buy->AddTimeStampBuy($Nick, $Fetch['premium_id'], $Fetch['user_id'], $Time);
						
					}
					
					$Core->AddAdminLogs('Zmieniono dane usługi <b>#'.$ID.'</b> z nicku <b>'.$Fetch['nick'].'</b> na nick <b>'.$Nick.'</b> oraz czas z <b>'.date("d-m-Y H:i:s", $Fetch['time']).'</b> na <b>'.date("d-m-Y H:i:s", $Time).'</b>');
					
					$View->Load("info");
					$View->Add('title', 'Zmiany zapisane');
					$View->Add('header', 'Zmiany zapisane!');
					$View->Add('info', 'Zmiany zostały poprawnie zapisane!');
					$View->Add('back', 'index.php?pages=admin_members_buy');
					$View->Out();
					
				}
				
				else
				{
					
					$_SESSION['SERVERID'] = $Fetch['server'];
					
					$Time = date("d-m-Y H:i:s", $Fetch['time']);
					
					$Info .= '<form method="post" action="index.php?pages=admin_members_buy&id='.$ID.'&action=edit">
		
						<input type="hidden" name="SAVE" value="true">
			
						<br><input type="text" name="NAME" placeholder="SID lub Nick" value="'.$Fetch['nick'].'"><br>
						<br><input type="text" name="TIME" placeholder="Do kiedy" value="'.$Time.'"><br>
			
						<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
					</form>';
					
					$View->Load("admin_members");
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
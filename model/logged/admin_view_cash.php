<?php

	if($Core->CheckAdmin())
	{
		
		$Info .= '<table>
	
			<tr>
		
				<td class="nag">Serwer</td>
				<td class="nag">Ostatni miesiąc</td>
				<td class="nag">Ostatnie 3 miesiące</td>
				<td class="nag">Ostatni rok</td>
				<td class="nag">Od początku</td>
		
			</tr>';
		
		$Query = $MySQL->query("SELECT `id`, `name` FROM `servers`");
		
		while($Fetch = $Query->fetch())
		{
			
			$OneValue = 0;
			$TwoValue = 0;
			$ThreeValue = 0;
			$AllValue = 0;
			
			$QueryTwo = $MySQL->prepare("SELECT `value` FROM `server_cash` WHERE `server_id`=:one");
			$QueryTwo->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
			$QueryTwo->execute();
			
			while($FetchTwo = $QueryTwo->fetch())
			{
				
				$AllValue += $FetchTwo['value'];
				
			}

			$QueryTwo = $MySQL->prepare("SELECT `value` FROM `server_cash` WHERE DATE_SUB( NOW(), INTERVAL 30 DAY) < `time` AND `server_id`=:one");
			$QueryTwo->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
			$QueryTwo->execute();
			
			while($FetchTwo = $QueryTwo->fetch())
			{
				
				$OneValue += $FetchTwo['value'];
				
			}
			
			$QueryTwo = $MySQL->prepare("SELECT `value` FROM `server_cash` WHERE DATE_SUB( NOW(), INTERVAL 90 DAY) < `time` AND `server_id`=:one");
			$QueryTwo->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
			$QueryTwo->execute();
			
			while($FetchTwo = $QueryTwo->fetch())
			{
				
				$TwoValue += $FetchTwo['value'];
				
			}

			$QueryTwo = $MySQL->prepare("SELECT `value` FROM `server_cash` WHERE DATE_SUB( NOW(), INTERVAL 365 DAY) < `time` AND `server_id`=:one");
			$QueryTwo->bindValue(":one", $Fetch['id'], PDO::PARAM_INT);
			$QueryTwo->execute();
			
			while($FetchTwo = $QueryTwo->fetch())
			{
				
				$ThreeValue += $FetchTwo['value'];
				
			}

			$OneValue = $OneValue / 2;
			$TwoValue = $TwoValue / 2;
			$ThreeValue = $ThreeValue / 2;
			$AllValue = $AllValue / 2;
			
			$GlobalOneValue += $OneValue;
			$GlobalTwoValue += $TwoValue;
			$GlobalThreeValue += $ThreeValue;
			$GlobalAllValue += $AllValue;
			
			$Info .= '<tr>
				<td>'.$Fetch['name'].'</td>
				<td>'.$OneValue.' PLN</td>
				<td>'.$TwoValue.' PLN</td>
				<td>'.$ThreeValue.' PLN</td>
				<td>'.$AllValue.' PLN</td>
			</tr>';
			
		}
		
		$Info .= '<tr>
			<td>Łącznie</td>
			<td>'.$GlobalOneValue.' PLN</td>
			<td>'.$GlobalTwoValue.' PLN</td>
			<td>'.$GlobalThreeValue.' PLN</td>
			<td>'.$GlobalAllValue.' PLN</td>
		</tr>
		
		</table>';
	
		$View->Load("admin");
		$View->Add("title", "Zarobki sklepu");
		$View->Add("header", "Zarobki sklepu");
		$View->Add("info", $Info);
		$View->Out();
	
	}
	
	else
	{
	
		$View->Load("info");
		$View->Add('title', 'Błąd :: Brak uprawnień');
		$View->Add('header', 'Błąd! Brak uprawnień!');
		$View->Add('info', 'Nie posiadasz uprawnień administracyjnych!');
		$View->Add('back', 'home.html');
		$View->Out();
	
	}

?>
<?php

	$Info .= '<table>
	
	<tr>
		
		<td class="nag">Nick lub SID</td>
		<td class="nag">Nazwa usługi</td>
		<td class="nag">Serwer</td>
		<td class="nag">Czas</td>
		<td class="nag">Przedłużenie</td>
		
	</tr>';
	
	$Query = $MySQL->prepare("SELECT * FROM `premium_cache` WHERE `user_id`=:one");
	
	$Query->bindValue(":one", $_SESSION['ID'], PDO::PARAM_INT);
	
	$Query->execute();
	
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
		
		else
		{
			
			$Time = ''.$Days.' dni, '.$Hours.' godzin, '.$Minutes.' minut, '.$Seconds.' sekund'.'';
		
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
			<td><a href="index.php?pages=extension&id='.$Fetch['id'].'"><i class="fa fa-plus"></i></a></td>
		</tr>';
	
	}
	
	$Info .= '</table>';
	
	$View->Load("logged_home");
	$View->Add('title', 'Moje zakupy');
	$View->Add('header', 'Moje zakupy');
	$View->Add('info', $Info);
	$View->Out();

?>
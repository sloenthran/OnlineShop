<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
		$Action = $Core->ClearText($_GET['action']);
		
		if($ID == '' || $Action == '')
		{
			
			$Info .= '<table>
	
			<tr>
				
				<td class="nag">Cena z VAT</td>
				<td class="nag">Usługa</td>
				<td class="nag">Ilość dni</td>
				<td class="nag">Serwer</td>
				<td class="nag">Opcje</td>
		
			</tr>';
		
			$Query = $MySQL->query("SELECT * FROM `service");
			
			while($Fetch = $Query->fetch())
			{
				
				$QueryTwo = $MySQL->prepare("SELECT `vat` FROM `price` WHERE `id`=:one");
				$QueryTwo->bindValue(":one", $Fetch['price_id'], PDO::PARAM_INT);
				$QueryTwo->execute();
				
				$FetchTwo = $QueryTwo->fetch();
				$Price = $FetchTwo['vat'];
				
				$QueryTwo = $MySQL->prepare("SELECT `name`, `server` FROM `buy` WHERE `id`=:one");
				$QueryTwo->bindValue(":one", $Fetch['buy_id'], PDO::PARAM_INT);
				$QueryTwo->execute();
				
				$FetchTwo = $QueryTwo->fetch();
				$Buy = $FetchTwo['name'];
				
				if($FetchTwo['server'] == 0)
				{
					
					$Server = 'Wszystkie';
					
				}
				
				else
				{
					
					$QueryTwo = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					$QueryTwo->bindValue(":one", $FetchTwo['server'], PDO::PARAM_INT);
					$QueryTwo->execute();
					
					$FetchTwo = $QueryTwo->fetch();
					$Server = $FetchTwo['name'];
					
				}
				
				$Info .= '<tr>
					<td>'.$Price.'</td>
					<td>'.$Buy.'</td>
					<td>'.$Fetch['days'].'</td>
					<td>'.$Server.'</td>
					<td><a href="index.php?pages=admin_buy_price&id='.$Fetch['id'].'&action=edit"><i class="fa fa-scissors"></i></a> &nbsp;&nbsp;&nbsp; <a href="index.php?pages=admin_buy_price&id='.$Fetch['id'].'&action=delete"><i class="fa fa-times"></i></a></td>
				</tr>';
				
			}
			
			$Info .= '</table>';
	
			$View->Load("admin_price");
			$View->Add('title', 'Cennik usług');
			$View->Add('header', 'Cennik usług');
			$View->Add("info", $Info);
			$View->Out();
			
		}
		
		else
		{
			
			if($Action == 'delete')
			{
				
				$Query = $MySQL->prepare("DELETE FROM `service` WHERE `id`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
				
				$View->Load("info");
				$View->Add('title', 'Cena usługi usunięta');
				$View->Add('header', 'Cena usługi usunięta!');
				$View->Add('info', 'Cena usługi została poprawnie usunięta!');
				$View->Add('back', 'index.php?pages=admin_buy_price');
				$View->Out();
				
			}
			
			else
			{
				
				if($_POST['SAVE'])
				{
					
					$Buy = $Core->ClearText($_POST['BUY']);
					$Days = $Core->ClearText($_POST['DAYS']);
					$Price = $Core->ClearText($_POST['PRICE']);
					
					$Query = $MySQL->prepare("UPDATE `service` SET `price_id`=:one, `days`=:two, `buy_id`=:three WHERE `id`=:four");
					$Query->bindValue(":one", $Price, PDO::PARAM_INT);
					$Query->bindValue(":two", $Days, PDO::PARAM_STR);
					$Query->bindValue(":three", $Buy, PDO::PARAM_INT);
					$Query->bindValue(":four", $ID, PDO::PARAM_INT);
					$Query->execute();
					
					$View->Load("info");
					$View->Add('title', 'Zmiany zapisane');
					$View->Add('header', 'Zmiany zapisane!');
					$View->Add('info', 'Zmiany w cenie zostały poprawnie zapisane!');
					$View->Add('back', 'index.php?pages=admin_buy_price');
					$View->Out();
					
				}
				
				else
				{
				
					$QueryTwo = $MySQL->prepare("SELECT * FROM `service` WHERE `id`=:one");
					$QueryTwo->bindValue(":one", $ID, PDO::PARAM_INT);
					$QueryTwo->execute();
				
					$FetchTwo = $QueryTwo->fetch();
				
					$Query = $MySQL->query("SELECT `id`, `name` FROM `buy`");
			
					while($Fetch = $Query->fetch())
					{
						
						if($Fetch['id'] == $FetchTwo['buy_id'])
						{
						
							$Buy .= '<option value="'.$Fetch['id'].'" selected>'.$Fetch['name'].'</option>';
							
						}
						
						else
						{
							
							$Buy .= '<option value="'.$Fetch['id'].'">'.$Fetch['name'].'</option>';
							
						}
				
					}
			
					$Query = $MySQL->query("SELECT * FROM `price`");
				
					while($Fetch = $Query->fetch())
					{
					
						if($Fetch['id'] == $FetchTwo['price_id'])
						{
						
							$Price .= '<option value="'.$Fetch['id'].'" selected>'.$Fetch['number'].' ('.$Fetch['vat'].' PLN)</option>';
							
						}
					
						else
						{
						
							$Price .= '<option value="'.$Fetch['id'].'">'.$Fetch['number'].' ('.$Fetch['vat'].' PLN)</option>';
						
						}
				
					}
			
					$Info = '<form method="post" action="index.php?pages=admin_buy_price&id='.$ID.'&action=edit">
		
						<input type="hidden" name="SAVE" value="true">
			
						<br>Usługa<br> <select type="text" name="BUY">'.$Buy.'</select><br>
						<br><input type="text" name="DAYS" placeholder="Ilość dni" value="'.$FetchTwo['days'].'"><br>
						<br>Cena<br> <select type="text" name="PRICE">'.$Price.'</select><br>
			
						<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
					</form>';
			
					$View->Load("admin_price");
					$View->Add('title', 'Edycja ceny usługi');
					$View->Add('header', 'Edycja ceny usługi');
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
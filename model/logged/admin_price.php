<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
		$Action = $Core->ClearText($_GET['action']);
		
		if($ID == '' || $Action == '')
		{
			
			$Info .= '<table>
	
			<tr>
				
				<td class="nag">Numer</td>
				<td class="nag">Wartość z VAT</td>
				<td class="nag">Wartość w sklepie</td>
				<td class="nag">Opcje</td>
		
			</tr>';
		
			$Query = $MySQL->query("SELECT * FROM `price`");
			
			while($Fetch = $Query->fetch())
			{
				
				$Info .= '<tr>
					<td>'.$Fetch['number'].'</td>
					<td>'.$Fetch['vat'].'</td>
					<td>'.$Fetch['value'].'</td>
					<td><a href="index.php?pages=admin_price&id='.$Fetch['id'].'&action=edit"><i class="fa fa-scissors"></i></a> &nbsp;&nbsp;&nbsp; <a href="index.php?pages=admin_price&id='.$Fetch['id'].'&action=delete"><i class="fa fa-times"></i></a></td>
				</tr>';
				
			}
			
			$Info .= '</table>';
	
			$View->Load("admin_price");
			$View->Add('title', 'Cennik');
			$View->Add('header', 'Cennik');
			$View->Add("info", $Info);
			$View->Out();
			
		}
		
		else
		{
			
			if($Action == 'delete')
			{
				
				$Query = $MySQL->prepare("SELECT `id` FROM `buy` WHERE `cash`=:one");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
				
				if($Query->rowCount() > 0)
				{
					
					$View->Load("info");
					$View->Add('title', 'Cena nie usunięta');
					$View->Add('header', 'Cena nie usunięta!');
					$View->Add('info', 'Cena nie została usunięta ponieważ co najmniej jedna usługa z niej korzysta!');
					$View->Add('back', 'index.php?pages=admin_price');
					$View->Out();
					
				}
				
				else
				{
				
					$Query = $MySQL->prepare("DELETE FROM `price` WHERE `id`=:one");
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					$Query->execute();
				
					$View->Load("info");
					$View->Add('title', 'Cena usunięta');
					$View->Add('header', 'Cena usunięta!');
					$View->Add('info', 'Cena została poprawnie usunięta!');
					$View->Add('back', 'index.php?pages=admin_price');
					$View->Out();
					
					$Core->AddAdminLogs("Usunięto cenę o ID #".$ID."");
					
				}
				
			}
			
			else
			{
				
				if($_POST['SAVE'])
				{
					
					$Number = $Core->ClearText($_POST['NUMBER']);
					$VAT = $Core->ClearText($_POST['VAT']);
					$Value = $Core->ClearText($_POST['VALUE']);
					
					$Query = $MySQL->prepare("UPDATE `price` SET `number`=:one, `vat`=:two, `value`=:three WHERE `id`=:four");
					$Query->bindValue(":one", $Number, PDO::PARAM_INT);
					$Query->bindValue(":two", $VAT, PDO::PARAM_STR);
					$Query->bindValue(":three", $Value, PDO::PARAM_INT);
					$Query->bindValue(":four", $ID, PDO::PARAM_INT);
					$Query->execute();
					
					$View->Load("info");
					$View->Add('title', 'Zmiany zapisane');
					$View->Add('header', 'Zmiany zapisane!');
					$View->Add('info', 'Zmiany w cenie zostały poprawnie zapisane!');
					$View->Add('back', 'index.php?pages=admin_price');
					$View->Out();
					
					$Core->AddAdminLogs("Zmieniono cenę o ID #".$ID."");
					
				}
				
				else
				{
					
					$Query = $MySQL->prepare("SELECT * FROM `price` WHERE `id`=:one");
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					$Query->execute();
					
					$Fetch = $Query->fetch();
			
					$Info = '<form method="post" action="index.php?pages=admin_price&id='.$Fetch['id'].'&action=edit">
		
						<input type="hidden" name="SAVE" value="true">
			
						<br><input type="text" name="NUMBER" placeholder="Numer" value="'.$Fetch['number'].'"><br>
						<br><input type="text" name="VAT" placeholder="Wartość z VAT" value="'.$Fetch['vat'].'"><br>
						<br><input type="text" name="VALUE" placeholder="Wartość w sklepie" value="'.$Fetch['value'].'"><br>
			
						<br><button type="submit" class="przycisk">Zapisz <i class="fa fa-chevron-circle-right"></i> </button>
		
					</form>';
			
					$View->Load("admin_price");
					$View->Add('title', 'Edycja ceny');
					$View->Add('header', 'Edycja ceny');
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
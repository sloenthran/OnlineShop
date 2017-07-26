<?php

	if($Core->CheckAdmin())
	{
		
		$ID = $Core->ClearText($_GET['id']);
		
		if($ID == '' || !$ID)
		{
			
			$Query = $MySQL->query("SELECT `name`, `id` FROM `servers`");
			
			while($Fetch = $Query->fetch())
			{
				
				$Info .= '<a href="index.php?pages=admin_members&id='.$Fetch['id'].'"><button class="przycisk">'.$Fetch['name'].'</button></a><br>';
				
			}
			
			$View->Load("admin_members");
			$View->Add('title', 'Wybierz serwer');
			$View->Add('header', 'Wybierz serwer');
			$View->Add('info', $Info);
			$View->Out();
			
		}
		
		else
		{
			
			if($_POST['ADD'])
			{
				
				$Nick = $Core->ClearText($_POST['NAME']);
				$PremiumID = $Core->ClearText($_POST['BUY']);
				$UserID = $Core->ClearText($_POST['USER']);
				$Days = $Core->ClearText($_POST['DAYS']);
				
				$Buy = new Buy();
				
				$_SESSION['SERVERID'] = $ID;
				
				if($Nick == '' || $PremiumID == '' || $Days == '')
				{
				
					$View->Load("info");
					$View->Add("title", "Błąd :: Puste pola");
					$View->Add("header", "Błąd! Puste pola!");
					$View->Add("info", "Pola formularza nie mogą być puste!");
					$View->Add("back", "index.php?pages=admin_add_buy");
					$View->Out();
				
				}
				
				else
				{
			
					$Buy->AddBuy($Nick, $PremiumID, $UserID, $Days);
				
					$Query = $MySQL->prepare("SELECT `login` FROM `users` WHERE `id`=:one");
					$Query->bindValue(":one", $UserID, PDO::PARAM_INT);
					$Query->execute();
				
					$Fetch = $Query->fetch();
					$User = $Fetch['login'];
				
					$Query = $MySQL->prepare("SELECT `name` FROM `buy` WHERE `id`=:one");
					$Query->bindValue(":one", $PremiumID, PDO::PARAM_INT);
					$Query->execute();
				
					$Fetch = $Query->fetch();
					$Service = $Fetch['name']
				
					$Query = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
					$Query->bindValue(":one", $ID, PDO::PARAM_INT);
					$Query->execute();
				
					$Fetch = $Query->fetch();
					
					$Core->AddAdminLogs("Dodano użytkownikowi <b>".$User."</b> usługę ".$Service."  na serwerze <b>".$Fetch['name']."</b> (<b>".$Nick."</b> | ".$Days." dni)");
				
					$View->Load("info");
					$View->Add("title", "Usługa dodana");
					$View->Add("header", "Usługa dodany!");
					$View->Add("info", "Usługa została poprawnie dodana!");
					$View->Add("back", "index.php?pages=admin_members&id=".$ID."");
					$View->Out();
				
				}
				
			}
			
			else
			{
				
				$Query = $MySQL->query("SELECT `id`, `login` FROM `users`  ORDER BY `login` ASC");
		
				$User .= '<option value="0">SKLEP</option>';
				
				while($Fetch = $Query->fetch())
				{
					
					$User .= '<option value="'.$Fetch['id'].'">'.$Fetch['login'].'</option>';
					
				}
				
				$Query = $MySQL->prepare("SELECT `id`, `name` FROM `buy` WHERE `server`=:one OR `server`='0'");
				$Query->bindValue(":one", $ID, PDO::PARAM_INT);
				$Query->execute();
	
				while($Fetch = $Query->fetch())
				{
					
					$Buy .= '<option value="'.$Fetch['id'].'">'.$Fetch['name'].'</option>';
					
				}
				
				$Info = '<form method="post" action="index.php?pages=admin_members&id='.$ID.'">
		
					<input type="hidden" name="ADD" value="true">
			
					<br><input type="text" name="NAME" placeholder="SID lub Nick"><br>
					<br><input type="text" name="DAYS" placeholder="Ilość dni"><br>
					<br><select name="USER">'.$User.'</select><br>
					<br><select name="BUY">'.$Buy.'</select><br>
			
					<br><button type="submit" class="przycisk">Dodaj <i class="fa fa-chevron-circle-right"></i> </button>
		
				</form>';
					
				$View->Load("admin_members");
				$View->Add('title', 'Dodaj usługę');
				$View->Add('header', 'Dodaj usługę');
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
		$View->Add('back', 'home.html');
		$View->Out();
	
	}
	
?>
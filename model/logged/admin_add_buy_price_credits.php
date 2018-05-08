<?php

	if($Core->CheckAdmin())
	{
	
		if($_POST['ADD'])
		{
			
			$Buy = $Core->ClearText($_POST['BUY']);
			$Amount = $Core->ClearText($_POST['AMOUNT']);
			$Price = $Core->ClearText($_POST['PRICE']);
			
			if($Buy == '' || $Amount == '' || $Price == '')
			{
		
				$View->Load("info");
				$View->Add("title", "Błąd :: Puste pola");
				$View->Add("header", "Błąd! Puste pola!");
				$View->Add("info", "Pola formularza nie mogą być puste!");
				$View->Add("back", "index.php?pages=admin_add_buy_price_credits");
				$View->Out();
		
			}
	
			else
			{
		
				$Query = $MySQL->prepare("INSERT INTO `service_credits` VALUES(NULL, :one, :two, :three)");
				
				$Query->bindValue(":one", $Price, PDO::PARAM_STR);
				$Query->bindValue(":two", $Amount, PDO::PARAM_STR);
				$Query->bindValue(":three", $Buy, PDO::PARAM_INT);
				
				$Query->execute();
				
				$View->Load("info");
				$View->Add("title", "Cena dodana");
				$View->Add("header", "Cena dodana!");
				$View->Add("info", "Cena została poprawnie dodana!");
				$View->Add("back", "index.php?pages=admin_add_buy_price_credits");
				$View->Out();
				
				$Core->AddAdminLogs("Dodano nową cenę kredytów");
			
			}
			
		}
		
		else
		{
			
			$Query = $MySQL->query("SELECT `id`, `server` FROM `buy_credits`");
			
			while($Fetch = $Query->fetch())
			{
				
				$ID = $Fetch['id'];
				
				$QueryTwo = $MySQL->prepare("SELECT `name` FROM `servers` WHERE `id`=:one");
				$QueryTwo->bindValue(":one", $Fetch['server'], PDO::PARAM_INT);
				$QueryTwo->execute();
				
				$FetchTwo = $QueryTwo->fetch();
				
				$Buy .= '<option value="'.$ID.'">'.$FetchTwo['name'].'</option>';
				
			}
			
			$Query = $MySQL->query("SELECT * FROM `price`");
			
			while($Fetch = $Query->fetch())
			{
				
				$Price .= '<option value="'.$Fetch['id'].'">'.$Fetch['number'].' ('.$Fetch['vat'].' PLN)</option>';
				
			}
			
			$Info = '<form method="post" action="index.php?pages=admin_add_buy_price_credits">
		
				<input type="hidden" name="ADD" value="true">
			
				<br>Serwer<br> <select type="text" name="BUY">'.$Buy.'</select><br>
				<br><input type="text" name="AMOUNT" placeholder="Ilość kredytów"><br>
				<br>Cena<br> <select type="text" name="PRICE">'.$Price.'</select><br>
			
				<br><button type="submit" class="przycisk">Dodaj cenę <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
			
			$View->Load("admin_price");
			$View->Add('title', 'Dodaj cenę kredytów');
			$View->Add('header', 'Dodaj cenę kredytów');
			$View->Add('info', $Info);
			$View->Out();
	
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
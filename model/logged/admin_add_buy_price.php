<?php

	if($Core->CheckAdmin())
	{
	
		if($_POST['ADD'])
		{
			
			$Buy = $Core->ClearText($_POST['BUY']);
			$Days = $Core->ClearText($_POST['DAYS']);
			$Price = $Core->ClearText($_POST['PRICE']);
			
			if($Buy == '' || $Days == '' || $Price == '')
			{
		
				$View->Load("info");
				$View->Add("title", "Błąd :: Puste pola");
				$View->Add("header", "Błąd! Puste pola!");
				$View->Add("info", "Pola formularza nie mogą być puste!");
				$View->Add("back", "index.php?pages=admin_add_buy_price");
				$View->Out();
		
			}
	
			else
			{
		
				$Query = $MySQL->prepare("INSERT INTO `service` VALUES('', :one, :two, :three)");
				
				$Query->bindValue(":one", $Price, PDO::PARAM_STR);
				$Query->bindValue(":two", $Days, PDO::PARAM_STR);
				$Query->bindValue(":three", $Buy, PDO::PARAM_INT);
				
				$Query->execute();
				
				$View->Load("info");
				$View->Add("title", "Cena dodana");
				$View->Add("header", "Cena dodana!");
				$View->Add("info", "Cena została poprawnie dodana!");
				$View->Add("back", "index.php?pages=admin_add_price");
				$View->Out();
			
			}
			
		}
		
		else
		{
			
			$Query = $MySQL->query("SELECT `id`, `name` FROM `buy`");
			
			while($Fetch = $Query->fetch())
			{
				
				$Buy .= '<option value="'.$Fetch['id'].'">'.$Fetch['name'].'</option>';
				
			}
			
			$Query = $MySQL->query("SELECT * FROM `price`");
			
			while($Fetch = $Query->fetch())
			{
				
				$Price .= '<option value="'.$Fetch['id'].'">'.$Fetch['number'].' ('.$Fetch['vat'].' PLN)</option>';
				
			}
			
			$Info = '<form method="post" action="index.php?pages=admin_add_buy_price">
		
				<input type="hidden" name="ADD" value="true">
			
				<br>Usługa<br> <select type="text" name="BUY">'.$Buy.'</select><br>
				<br><input type="text" name="DAYS" placeholder="Ilość dni"><br>
				<br>Cena<br> <select type="text" name="PRICE">'.$Price.'</select><br>
			
				<br><button type="submit" class="przycisk">Dodaj cenę <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
			
			$View->Load("admin_price");
			$View->Add('title', 'Dodaj cenę usługi');
			$View->Add('header', 'Dodaj cenę usługi');
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
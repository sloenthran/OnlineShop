<?php

	if($Core->CheckAdmin())
	{
	
		if($_POST['ADD'])
		{
			
			$Number = $Core->ClearText($_POST['NUMBER']);
			$VAT = $Core->ClearText($_POST['VAT']);
			$Value = $Core->ClearText($_POST['VALUE']);
			
			if($Number == '' || $VAT == '' || $Value == '')
			{
		
				$View->Load("info");
				$View->Add("title", "Błąd :: Puste pola");
				$View->Add("header", "Błąd! Puste pola!");
				$View->Add("info", "Pola formularza nie mogą być puste!");
				$View->Add("back", "index.php?pages=admin_add_price");
				$View->Out();
		
			}
	
			else
			{
		
				$Query = $MySQL->prepare("INSERT INTO `price` VALUES(NULL, :one, :two, :three)");
				
				$Query->bindValue(":one", $VAT, PDO::PARAM_STR);
				$Query->bindValue(":two", $Value, PDO::PARAM_STR);
				$Query->bindValue(":three", $Number, PDO::PARAM_INT);
				
				$Query->execute();
				
				$Core->AddAdminLogs("Dodano cenę na numer ".$Number." (".$Value." | ".$VAT.")");
				
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
			
			$Info = '<form method="post" action="index.php?pages=admin_add_price">
		
				<input type="hidden" name="ADD" value="true">
			
				<br><input type="text" name="NUMBER" placeholder="Numer"><br>
				<br><input type="text" name="VAT" placeholder="Wartość z VAT"><br>
				<br><input type="text" name="VALUE" placeholder="Wartość w sklepie"><br>
			
				<br><button type="submit" class="przycisk">Dodaj cenę <i class="fa fa-chevron-circle-right"></i> </button>
		
			</form>';
			
			$View->Load("admin_price");
			$View->Add('title', 'Dodaj cenę');
			$View->Add('header', 'Dodaj cenę');
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
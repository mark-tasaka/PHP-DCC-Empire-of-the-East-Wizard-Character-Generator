<!DOCTYPE html>
<html>
<head>
<title>DCC Empire of the East Wizard Character Generator </title>
 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
	<meta charset="UTF-8">
	<meta name="description" content="Dungeon Crawl Classics Wizard Character Generator..">
	<meta name="keywords" content="Dungeon Crawl Classics,,HTML5,CSS,JavaScript">
	<meta name="author" content="Mark Tasaka 2021">
    
    <link rel="icon" href="../../../../images/favicon/icon.png" type="image/png" sizes="16x16"> 
		

	<link rel="stylesheet" type="text/css" href="css/wizard.css">
    
    
    
    
</head>
<body>
    
    <!--PHP-->
    <?php
    
    include 'php/checks.php';
    include 'php/weapons.php';
    include 'php/gear.php';
    include 'php/classDetails.php';
    include 'php/abilityScoreGen.php';
    include 'php/randomName.php';
    include 'php/xp.php';
    include 'php/diceRoll.php';
    include 'php/luckySign.php';
    include 'php/zeroLvOccupation.php';
    include 'php/wealth.php';
    include 'php/familiar.php';
    

        if(isset($_POST["theCharacterName"]))
        {
            $characterName = $_POST["theCharacterName"];
    
        }

        
        if(isset($_POST["thePlayerName"]))
        {
            $playerName = $_POST["thePlayerName"];
    
        }
        
        
        if(isset($_POST["theGender"]))
        {
            $gender = $_POST["theGender"];
        }


        if(isset($_POST['theCheckBoxRandomName']) && $_POST['theCheckBoxRandomName'] == 1) 
        {
            $characterName = getRandomName($gender) . " " . getSurname();
        } 

        if(isset($_POST["theAlignment"]))
        {
            $alignment = $_POST["theAlignment"];
        }
    
        if(isset($_POST["theLevel"]))
        {
            $level = $_POST["theLevel"];
        
        } 

        
        $xpNextLevel = getXPNextLevel ($level);
        
        if(isset($_POST["theAbilityScore"]))
        {
            $abilityScoreGen = $_POST["theAbilityScore"];
        
        }
        
        
        if(isset($_POST["theWealth"]))
        {
            $wealthOption = $_POST["theWealth"];
        
        }  

        $wealth = getWealth($wealthOption);

        
        $abilityScoreArray = array();
        
        for($i = 0; $i < 6; ++$i)
        {
            $abilityScore = rollAbilityScores ($abilityScoreGen);

            array_push($abilityScoreArray, $abilityScore);

        }       

        $strength = $abilityScoreArray[0];
        $agility = $abilityScoreArray[1];
        $stamina = $abilityScoreArray[2];
        $personality = $abilityScoreArray[3];
        $intelligence = $abilityScoreArray[4];
        $luck = $abilityScoreArray[5];
        
        $strengthMod = getAbilityModifier($strength);
        $agilityMod = getAbilityModifier($agility);
        $staminaMod = getAbilityModifier($stamina);
        $personalityMod = getAbilityModifier($personality);
        $intelligenceMod = getAbilityModifier($intelligence);
        $luckMod = getAbilityModifier($luck);


    $generationMessage = generationMesssage ($abilityScoreGen);
    
    
       $speed = 30;

       $baseArmourClass = 10 + $agilityMod;

       $armourClass = $baseArmourClass;

       $criticalDie = criticalDie($level);

       $spellCheck = $intelligenceMod + $level;

       $actionDice = actionDice($level);

       $attackBonus = attackBonus($level);

       $luckySign = array();
       $luckySign = getBirthAugur();

       $ref = savingThrowReflex($level);
       $ref += $agilityMod;
       $refLuckSign = getRefLuckBonus($luckMod, $luckySign[0]);
       $ref += $refLuckSign;
       
       $fort = savingThrowFort($level);
       $fort += $staminaMod;
       $fortLuckSign = getFortLuckBonus($luckMod, $luckySign[0]);
       $fort += $fortLuckSign;
       
       $will = savingThrowWill($level);
       $will += $personalityMod;
       $willLuckSign = getWillLuckBonus($luckMod, $luckySign[0]);
       $will += $willLuckSign;

       $speed = getSpeed($luckMod, $luckySign[0]);

       $title = title($level, $alignment);

       $initiative = getInit($agilityMod, $luckMod, $luckySign[0]);

       $languages = getLanguages($intelligenceMod, $luckMod, $luckySign[0]);

       //Hit Points
       $hitPoints = getHitPoints($level, $staminaMod);

       $hitPointLuckySign = getHitPointLuck($luckMod, $luckySign[0]);

       $levelMultiplier = $level + 1;

       $bonusHitPoints = ($hitPointLuckySign * $levelMultiplier);
       
       $hitPoints += $bonusHitPoints;


       $meleeHitLuckyBonus = meleeAttackLuckSign($luckMod, $luckySign[0]);

       $meleeToHit = $attackBonus + $meleeHitLuckyBonus + $strengthMod;

       $meleeDamageLuckyBonus = meleeDamageLuckSign($luckMod, $luckySign[0]);

       $meleeToDamage = $meleeDamageLuckyBonus + $strengthMod;

       
        $missileHitLuckyBonus = missileAttackLuckSign($luckMod, $luckySign[0]);

        $missileToHit = $attackBonus + $missileHitLuckyBonus + $agilityMod;

        $missileDamageLuckyBonus = missileDamageLuckSign($luckMod, $luckySign[0]);

        $missileToDamage = $missileDamageLuckyBonus;

       
       $professionNum = getOccupationNumber(); 
        
       $occupationArray = array();
       
       $occupationArray = getOccupationArray($professionNum);
       
       $profession = $occupationArray[0];


       $trainedWeapon = $occupationArray[1];

       //tradegoods array

       $tradegoods = array();

       array_push($tradegoods, $occupationArray[3]);

       if($occupationArray[4] != '')
       {
            array_push($tradegoods, ', ');
            array_push($tradegoods, $occupationArray[4]);

       }

    
       $knownSpells = knownSpells($level);
       $maxSpellLevel = maxSpellLevel($level);


$familiarForm = array();
        
//Familiar
if(isset($_POST['theFamiliar']) && $_POST['theFamiliar'] == 1) 
{
    $familiarForm = getFamiliar($alignment);

    $familiarFormAnimal = $familiarForm[0];
    $familiarFormDescription = $familiarForm[1];

    $familiarTypeName = getFamiliarType($alignment);
    $familiarType = 'Type: ' . $familiarTypeName;

    $familiarHitPoints = getFamiliarHitPoints($familiarTypeName);
    $familiarHitDice = getFamiliarHitDice($familiarTypeName); 

    $familiarHp = 'HP: ' . $familiarHitPoints . ' ' . $familiarHitDice;

    $familiarPersonalityShort = familiarPersonality();
    $familiarPersonality = 'Personality: ' . $familiarPersonalityShort;

}
else
{
    $familiarFormAnimal = "";
    $familiarFormDescription = "";
    $familiarType = "";
    $familiarHp = "";
    $familiarPersonality = "";

}
        

        $weaponArray = array();
        $weaponNames = array();
        $weaponDamage = array();
    
    //For Random Select weapon
    if(isset($_POST['thecheckBoxRandomWeaponsV3']) && $_POST['thecheckBoxRandomWeaponsV3'] == 1) 
    {
        $weaponArray = getRandomWeapons();

    }
    else
    {
        if(isset($_POST["theWeapons"]))
        {
            foreach($_POST["theWeapons"] as $weapon)
            {
                array_push($weaponArray, $weapon);
            }
        }
    }


    
    
    foreach($weaponArray as $select)
    {
        array_push($weaponNames, getWeapon($select)[0]);
    }
        
    foreach($weaponArray as $select)
    {
        array_push($weaponDamage, getWeapon($select)[1]);
    }
        
        $gearArray = array();
        $gearNames = array();
    
    

    //For Random Select gear
    if(isset($_POST['theCheckBoxRandomGear']) && $_POST['theCheckBoxRandomGear'] == 1) 
    {
        $gearArray = getRandomGear();

        $weaponCount = count($weaponArray);


        for($i = 0; $i < $weaponCount; ++$i)
        {

            if($weaponArray[$i] == "4")
            {
                array_push($gearArray, 26);
            }

            if($weaponArray[$i] == "18")
            {
                array_push($gearArray, 27);
            }

        }

    }
    else
    {
        //For Manually select gear
        if(isset($_POST["theGear"]))
            {
                foreach($_POST["theGear"] as $gear)
                {
                    array_push($gearArray, $gear);
                }
            }

    }

    
        foreach($gearArray as $select)
        {
            array_push($gearNames, getGear($select)[0]);
        }
    
    
    ?>

    
	
<!-- JQuery -->
  <img id="character_sheet"/>
   <section>
       
		<span id="profession">
        <?php
            echo $profession;
            ?></span>
           
        <span id="strength">
        <?php
            echo $strength;
            ?>
        </span>

        
        <span id="strengthMod">
        <?php
            $strengthMod = getModSign($strengthMod);
            echo $strengthMod;
            ?>
        </span>

		<span id="agility">
        <?php
            echo $agility;
            ?>
        </span>

          <span id="agilityMod">
        <?php
            $agilityMod = getModSign($agilityMod);
            echo $agilityMod;
            ?>
        </span>

           
		<span id="stamina">
        <?php
            echo $stamina;
            ?>
        </span>

          <span id="staminaMod">
        <?php
            $staminaMod = getModSign($staminaMod);
            echo $staminaMod;
            ?>
        </span>

		<span id="personality">
        <?php
            echo $personality;
            ?>
        </span>

         <span id="personalityMod">
        <?php
            $personalityMod = getModSign($personalityMod);
            echo $personalityMod;
            ?>
        </span>

		<span id="intelligence">
        <?php
            echo $intelligence;
            ?>
        </span>

         <span id="intelligenceMod">
        <?php
            $intelligenceMod = getModSign($intelligenceMod);
            echo $intelligenceMod;
            ?>
        </span>

		<span id="luck">
        <?php
            echo $luck;
            ?>
        </span>

         <span id="luckMod">
        <?php
            $luckMod = getModSign($luckMod);
            echo $luckMod;
            ?>
        </span>


       <span id="reflex">
        <?php
                $ref = getModSign($ref);
                echo $ref;
           ?>
       </span>

       <span id="fort">
        <?php
                $fort = getModSign($fort);
                echo $fort;
           ?>
       </span>

       <span id="will">
        <?php
                $will = getModSign($will);
                echo $will;
           ?>
       </span>
		  
       
       <span id="gender">
           <?php
           echo $gender;
           ?>
       </span>
       
       
       <span id="knownSpells">
           <?php
           echo $knownSpells;
           ?>
       </span>
       
       <span id="maxSpellLevel">
           <?php
           echo $maxSpellLevel;
           ?>
       </span>
       
       
       <span id="class">Wizard</span>
       
       <span id="armourClass">
           <?php
           echo $armourClass;
           ?>
           </span>

       
           <span id="armourClassBase">
           <?php
           echo '(' . $baseArmourClass . ')';
           ?>
           </span>
       
       <span id="hitPoints">
           <?php
           echo $hitPoints;
           ?>
           </span>

       <span id="languages">
           <?php
           echo $languages;
           ?>
       </span>
       
       <span id="trainedWeapon">
           <?php
           echo $trainedWeapon . ' / ';

           foreach($tradegoods as $td)
           {
               echo $td;
           }
           ?></span>


       <span id="wealth">
       <?php
           echo $wealth;
           ?>
       </span>

       
       <span id="level">
           <?php
                echo $level;
           ?>
        </span>

        
       <span id="xpNextLevel">
           <?php
                echo $xpNextLevel;
           ?>
        </span>

       

       
       <span id="characterName">
           <?php
                echo $characterName;
           ?>
        </span>

              
       <span id="playerName">
           <?php
                echo $playerName;
           ?>
        </span>
       
       
       
              
         <span id="alignment">
           <?php
                echo $alignment;
           ?>
        </span>
        
        <span id="speed">
           <?php
                echo $speed . '\'';
           ?></span>
        
        
        <span id="attackBonus">
        <?php
                $attackBonus = getModSign($attackBonus);
                echo $attackBonus;
           ?>
           </span>

        
           <span id="familiarForm">
            <?php
                echo  $familiarFormAnimal . '   ' . $familiarFormDescription . '<br/><br/>' . $familiarType . '<br/><br/>' . $familiarHp . '<br/><br/>' . $familiarPersonality;
            ?>
        </span>

              


        <span id="criticalDieTable">
            <?php
                echo $criticalDie;
            ?>
        </span>
        
        <span id="spellCheck">
            <?php
                $spellCheck = getModSign($spellCheck);
                echo $spellCheck;
            ?>
        </span>

        <span id="initiative">
            <?php
                $initiative = getModSign($initiative);
                echo $initiative;
            ?>
        </span>
        
        <span id="actionDice">
            <?php
                echo $actionDice;
            ?>
        </span>

        
        <span id="title">
            <?php
                echo $title;
            ?>
        </span>

        
		<p id="birthAugur">
            <?php
                echo $luckySign[1] . ': ' . $luckySign[2] . ' (' . $luckMod . ')';
            ?>
            </p>


        
        <span id="melee">
            <?php
                $meleeToHit = getModSign($meleeToHit);
                echo $meleeToHit;
            ?>
            </span>

        <span id="range">
            <?php
                $missileToHit = getModSign($missileToHit);
                echo $missileToHit;
            ?>
            </span>
        
        <span id="meleeDamage">
            <?php
                $meleeToDamage = getModSign($meleeToDamage);
                echo $meleeToDamage;
            ?>
            </span>

        <span id="rangeDamage">
            <?php
                $missileToDamage = getModSign($missileToDamage);
                echo $missileToDamage;
            ?>
            </span>

   
        
       
       <span id="weaponsList">
           <?php
           
           foreach($weaponNames as $theWeapon)
           {
               echo $theWeapon;
               echo "<br/>";
           }
           
           ?>  
        </span>

       <span id="weaponsList2">
           <?php
           foreach($weaponDamage as $theWeaponDam)
           {
               echo $theWeaponDam;
               echo "<br/>";
           }
           ?>        
        </span>
       

       <span id="gearList">
           <?php

           $gearCount = count($gearNames);
           $counter = 1;
           
           foreach($gearNames as $theGear)
           {
              echo $theGear;

              if($counter == $gearCount-1)
              {
                  echo " & ";
              }
              elseif($counter > $gearCount-1)
              {
                  echo ".";
              }
              else
              {
                  echo ", ";
              }

              ++$counter;
           }
           ?>
       </span>


       <span id="abilityScoreGeneration">
            <?php
           echo $generationMessage;
           ?>
       </span>


       
	</section>
	

		
  <script>
      

  
       let imgData = "images/wizard.png";
      
        $("#character_sheet").attr("src", imgData);
      

    
	 
  </script>
		
	
    
</body>
</html>
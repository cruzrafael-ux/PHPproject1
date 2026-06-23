<!DOCTYPE html>
<?php
    // html special characters
$first_name = htmlspecialchars(filter_input(INPUT_POST, 'fname'));
$last_name = htmlspecialchars(filter_input(INPUT_POST, 'lname'));
$grossIncome = $_POST['gross_income'] ?? null;
$totalD = $_POST['total_ded'] ?? null;

if(isset($_POST["submit"])) {
 
    // Validation 
    $grossIncome = filter_input(INPUT_POST, "gross_income", FILTER_VALIDATE_FLOAT);
    $totalD = filter_input(INPUT_POST, "total_ded", FILTER_VALIDATE_FLOAT);
     
    if ($grossIncome === false || $totalD === false) {
        echo "Please enter valid numbers.";
    } else
    
    // Standard Deduction
    $standardD = 15000;
    
    if($totalD < $standardD) {
        $totalD = $standardD;
    }
    
    // Adjusted Gross Income
    $agi = $grossIncome - $totalD;
    
    if ($agi < 0) {
        $agi = 0;
    }
    
    // Wealth Brackets + Math
    $wealthB = [
            ["limit" => 12400, "rate" => 0.10],
            ["limit" => 50400, "rate" => 0.12],
            ["limit" => 105700, "rate" => 0.22],
            ["limit" => 201775, "rate" => 0.24],
            ["limit" => 256225, "rate" => 0.32],
            ["limit" => 640600, "rate" => 0.35],
            ["limit" => PHP_FLOAT_MAX, "rate" => 0.37]
        ];
    
    $taxesByBracket = [];
    $totalTax = 0;
    $previousLimit = 0;
    $remainingIncome = $agi;

    foreach ($wealthB as $wealthB) {

        $currentLimit = $wealthB["limit"];
        $rate = $wealthB["rate"];

        $taxableInBracket = min(
            max($agi - $previousLimit, 0),
            $currentLimit - $previousLimit
        );

        $tax = $taxableInBracket * $rate;

        $taxesByBracket[] = [
            "rate" => $rate * 100,
            "tax" => $tax
        ];

        $totalTax += $tax;
        $previousLimit = $currentLimit;
    }

    $grossPercent = ($grossIncome > 0)
        ? ($totalTax / $grossIncome) * 100
        : 0;

    $agiPercent = ($agi > 0)
        ? ($totalTax / $agi) * 100
        : 0;    
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="index.php" method="post">
            <label> FName: </label>
            <input type="text" name="fname"/><br/>
            <label> LName: </label>
            <input type="text" name="lname"/><br/>
            <label> Gross Income: </label>
            <input type="text" name="gross_income"/><br/>
            <label> Total Deductions: </label>
            <input type="text" name="total_ded"/><br/>
            <label>&nbsp;</label>
            <input type="submit" name="submit" value="submit"/>
        </form>
        
        <?php
        echo "<h2>Tax Calculator Results for {$first_name} {$last_name}</h2>";
        echo "Gross Income: $" . number_format($grossIncome, 2) . "<br>";
        echo "Total Deductions: $" . number_format($totalD, 2) . "<br>";
        echo "Adjusted Gross Income: $" . number_format($agi, 2) . "<br><br>";

        foreach ($taxesByBracket as $bracketTax) {
            echo "Taxes Owed at {$bracketTax['rate']}% bracket: $" .
                 number_format($bracketTax['tax'], 2) . "<br>";
        }

        echo "<br>";
        echo "Total Taxes Owed: $" . number_format($totalTax, 2) . "<br>";
        echo "Taxes Owed as percentage of Gross Income: " .
             number_format($grossPercent, 2) . "%<br>";
        echo "Taxes Owed as percentage of Adjusted Gross Income: " .
             number_format($agiPercent, 2) . "%";
        ?>
    </body>
</html>
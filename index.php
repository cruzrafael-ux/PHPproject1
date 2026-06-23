<!DOCTYPE html>
<?php
$payRate = $_POST['pay_rate'];
$hours = $_POST['num_hrs'];
$taxRate = $_POST['tax_rate'];

if ($hours > 40) {
    $regularPay = 40 * $payRate;
    $overtimePay = ($hours - 40) * ($payRate * 1.5);
} else {
    $regularPay = $hours * $payRate;
    $overtimePay = 0;
}

$grossPay = $regularPay + $overtimePay;
$taxes = $grossPay * ($taxRate / 100);
$netPay = $grossPay - $taxes;

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form action="index.php" method="post">
            <label> Pay Rate: </label>
            <input type="number" name="pay_rate"/><br/>
            <label> Number of Hours: </label>
            <input type="number" name="num_hrs"/><br/>
            <label> Tax Rate (%): </label>
            <input type="number" name="tax_rate"/><br/>
            <label>&nbsp;</label>
            <input type="submit" value="submit"/>
        </form>
        
        <?php
        echo "<h2>Pay Stub</h2>";
        echo "Regular Pay: $" . number_format($regularPay, 2) . "<br>";
        echo "Overtime Pay: $" . number_format($overtimePay, 2) . "<br>";
        echo "Gross Pay: $" . number_format($grossPay, 2) . "<br>";
        echo "Taxes: $" . number_format($taxes, 2) . "<br>";
        echo "Net Pay: $" . number_format($netPay, 2) . "<br>";
        ?>
    </body>
</html>
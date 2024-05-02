<?php
// Check if an ID is provided and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Define customer data based on the ID
    switch ($id) {
        case 1:
            $full_name = "John Doe";
            $birthdate = "1990-01-01";
            $email = "john.doe@example.com";
            $invoice_ID = "INV19424857";
            $iban = "DE67 3364 7979 9856 2916 54";
            break;
        case 2:
            $full_name = "Jane Smith";
            $birthdate = "1985-05-15";
            $email = "jane.smith@example.com";
            $invoice_ID = "INV134583609";
            $iban = "DE14 4041 3185 2492 4817 49";
            break;
        case 3:
            $full_name = "Alice Johnson";
            $birthdate = "1992-08-24";
            $email = "alice.johnson@example.com";
            $invoice_ID = "INV19424857";
            $iban = "DE15 8146 5000 4724 6510 88";
            break;
            case 4:
                $full_name = "Benjamin Clark";
                $birthdate = "1987-03-15";
                $email = "benjamin.clark@example.com";
                $invoice_ID = "INV19424858";
                $iban = "DE21 1234 5678 9101 1121 31";
                break;
            
            case 5:
                $full_name = "Samantha Ray";
                $birthdate = "1990-07-22";
                $email = "samantha.ray@example.com";
                $invoice_ID = "INV19424859";
                $iban = "DE34 5678 9101 1121 3141 52";
                break;
            
            case 6:
                $full_name = "Oliver Miller";
                $birthdate = "1982-12-30";
                $email = "oliver.miller@example.com";
                $invoice_ID = "INV19424860";
                $iban = "DE56 2345 7891 0111 2131 64";
                break;
            
            case 7:
                $full_name = "Emma Wilson";
                $birthdate = "1995-05-18";
                $email = "emma.wilson@example.com";
                $invoice_ID = "INV19424861";
                $iban = "DE78 3456 7891 0111 2131 76";
                break;
            
            case 8:
                $full_name = "Noah Harris";
                $birthdate = "1989-11-09";
                $email = "noah.harris@example.com";
                $invoice_ID = "INV19424862";
                $iban = "DE90 4567 8910 1112 1318 87";
                break;
            
            case 9:
                $full_name = "Mia Turner";
                $birthdate = "1993-02-23";
                $email = "mia.turner@example.com";
                $invoice_ID = "INV19424863";
                $iban = "DE09 1234 5678 9101 1213 99";
                break;
            
            case 10:
                $full_name = "Lucas Brown";
                $birthdate = "1996-08-14";
                $email = "lucas.brown@example.com";
                $invoice_ID = "INV19424864";
                $iban = "DE12 3456 7891 0112 1314 10";
                break;
            
            case 11:
                $full_name = "Chloe Davis";
                $birthdate = "1991-10-25";
                $email = "chloe.davis@example.com";
                $invoice_ID = "INV19424865";
                $iban = "DE23 4567 8910 1112 1314 21";
                break;
            
            case 12:
                $full_name = "Ethan Lopez";
                $birthdate = "1985-06-19";
                $email = "ethan.lopez@example.com";
                $invoice_ID = "INV19424866";
                $iban = "DE45 6789 1011 1213 1415 32";
                break;
            
            case 13:
                $full_name = "Sophia White";
                $birthdate = "1988-09-05";
                $email = "sophia.white@example.com";
                $invoice_ID = "INV19424867";
                $iban = "DE67 8910 1112 1314 1516 43";
                break;
            
        default:
            $content = "No valid ID provided. Please specify an ID in the URL, like ?id=1";
            break;
    }
    $content = "<h2>Customer Information</h2>
                <p><strong>Full Name:</strong> $full_name</p>
                <p><strong>Birthdate:</strong> $birthdate</p>
                <p><strong>Email Address:</strong> $email</p>
                <p><strong>Invoice ID:</strong> $invoice_ID</p>
                <p><strong>Email Address:</strong> $iban</p>";
} else {
    $content = "No valid ID provided. Please specify an ID in the URL, like ?id=1";
}



// HTML output
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Customer Portal</title>
</head>
<body>
    <h1>Customer Portal</h1>
    <div>$content</div>
</body>
</html>";
?>

<?php
// if someone opens this page directly without using the form, send them back
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.html");
    exit;
}

// turns the 11 form values into one CSV line
// it only works with what gets passed in, it never touches $_POST
function buildCsvRecord($firstName, $lastName, $email, $phone, $age, $street, $city, $state, $zipCode, $accountType, $interests) {
    // put everything in an array in the same order as the columns
    $fields = array(
        $firstName,
        $lastName,
        $email,
        $phone,
        $age,
        $street,
        $city,
        $state,
        $zipCode,
        $accountType,
        $interests
    );

    // same rule as project 4, fix any commas or quotes so the columns don't shift
    for ($i = 0; $i < count($fields); $i++) {
        $value = (string) $fields[$i];

        // a quote inside the value becomes two quotes
        $value = str_replace('"', '""', $value);

        // if there's a comma, quote or line break, wrap the whole thing in quotes
        if (strpos($value, ',') !== false || strpos($value, '"') !== false ||
            strpos($value, "\n") !== false || strpos($value, "\r") !== false) {
            $value = '"' . $value . '"';
        }

        $fields[$i] = $value;
    }

    return implode(",", $fields);
}

// grab everything from the form, trim it, and use an empty string if something is missing
$firstName   = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
$lastName    = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
$email       = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone       = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$age         = isset($_POST['age']) ? trim($_POST['age']) : '';
$street      = isset($_POST['street']) ? trim($_POST['street']) : '';
$city        = isset($_POST['city']) ? trim($_POST['city']) : '';
$state       = isset($_POST['state']) ? trim($_POST['state']) : '';
$zipCode     = isset($_POST['zipCode']) ? trim($_POST['zipCode']) : '';
$accountType = isset($_POST['accountType']) ? trim($_POST['accountType']) : '';

// checkboxes only show up in $_POST if at least one was checked
if (isset($_POST['interests']) && is_array($_POST['interests'])) {
    // semicolons instead of commas so all the interests stay in one column
    $interests = implode("; ", $_POST['interests']);
} else {
    $interests = "None";
}

// double check on the server in case the browser's required checks got skipped
$errors = array();
if ($firstName === '' || $lastName === '' || $email === '' || $phone === '' || $age === '' ||
    $street === '' || $city === '' || $state === '' || $zipCode === '' || $accountType === '') {
    $errors[] = "Some required fields were left empty.";
}
if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "The email address doesn't look right.";
}

// only build the CSV line if everything checked out
$csvOutput = "";
if (count($errors) === 0) {
    $csvOutput = buildCsvRecord(
        $firstName,
        $lastName,
        $email,
        $phone,
        $age,
        $street,
        $city,
        $state,
        $zipCode,
        $accountType,
        $interests
    );
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ACME Inc - Customer CSV Output</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <header>
            <h1>ACME Inc</h1>
        </header>

        <main>
<?php if (count($errors) > 0): ?>
            <h2>Something Went Wrong</h2>
            <p>The record couldn't be converted. Please go back and check the form.</p>

            <div id="converter-container">
                <ul class="error-list">
<?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
<?php endforeach; ?>
                </ul>

                <div class="button-group result-actions">
                    <a href="index.html" class="converter-btn link-btn">&larr; Back to the Form</a>
                </div>
            </div>
<?php else: ?>
            <h2>Customer Record Converted</h2>
            <p>Thank you, <strong><?php echo htmlspecialchars($firstName . " " . $lastName); ?></strong>. Your customer record is ready for database import.</p>

            <div id="converter-container">
                <div class="input-group">
                    <label for="outputRecord">Generated CSV Line (11 Fields):</label>
                    <textarea
                        id="outputRecord"
                        rows="4"
                        cols="60"
                        readonly
                    ><?php echo htmlspecialchars($csvOutput); ?></textarea>
                </div>

                <div class="button-group result-actions">
                    <a href="index.html" class="converter-btn link-btn">&larr; Add Another Customer</a>
                </div>
            </div>
<?php endif; ?>
        </main>

        <footer>
            <p>&copy; 2026 Peter Tran</p>
        </footer>
    </body>
</html>
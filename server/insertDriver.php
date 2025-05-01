<?php
// register_driver.php
require 'connection.php';

// Only handle POST submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: new_driver.html');
    exit;
}

// 1) Collect and sanitize inputs
$person_phone_number = strtolower(trim($_POST['person_phone_number'] ?? ''));
$first               = strtolower(trim($_POST['first_name']         ?? ''));
$last                = strtolower(trim($_POST['last_name']          ?? ''));
$licenseNo           = strtolower(trim($_POST['license_no']         ?? ''));
$licenceType         = strtolower(trim($_POST['licence_type']       ?? ''));
$department          = strtolower(trim($_POST['department']         ?? ''));
$site                = strtolower(trim($_POST['site']               ?? ''));
$issueDate           = trim($_POST['issue_date']                    ?? '');
$expiryFrequency     = trim($_POST['expiry_frequency']             ?? '');  // NEW
$card_id    = trim($_POST['card_id'] ?? '');
$createdBy  = 'the main admin';

// 2) Basic validation
$errors = [];
if ($card_id === '')        $errors[] = 'Card ID is required.';
if ($first === '')          $errors[] = 'First name is required.';
if ($last === '')           $errors[] = 'Last name is required.';
//if ($expiryFrequency === '') $errors[] = 'Expiry frequency is required.';

if ($errors) {
    foreach ($errors as $e) {
        echo "<p style='color:red;'>" . htmlspecialchars($e) . "</p>\n";
    }
    echo '<p><a href="new_driver.html">Go Back</a></p>';
    exit;
}

// 3) Handle uploaded image (blob)
$imgData = null;
if (
    !empty($_FILES['driver_img']['tmp_name']) && 
    is_uploaded_file($_FILES['driver_img']['tmp_name'])
) {
    $imgData = file_get_contents($_FILES['driver_img']['tmp_name']);
}

// 4) Prepare INSERT statement
$sql = "
    INSERT INTO drivers (
        person_phone_number,
        first_name,
        last_name,
        license_no,
        licence_type,
        department,
        site,
        issue_date,
        expiry_frequency,
        driver_img,
        createdBy,
        card_id
    ) VALUES (
        :phone,
        :first,
        :last,
        :license_no,
        :licence_type,
        :department,
        :site,
        :issue_date,
        :expiry_frequency,
        :driver_img,
        :createdBy,
        :card_id
    )
";
$stmt = $conn->prepare($sql);

// 5) Bind parameters
$stmt->bindValue(':phone',             $person_phone_number, PDO::PARAM_STR);
$stmt->bindValue(':first',             $first,               PDO::PARAM_STR);
$stmt->bindValue(':last',              $last,                PDO::PARAM_STR);
$stmt->bindValue(':license_no',        $licenseNo,           PDO::PARAM_STR);
$stmt->bindValue(':licence_type',      $licenceType,         PDO::PARAM_STR);
$stmt->bindValue(':department',        $department,          PDO::PARAM_STR);
$stmt->bindValue(':site',              $site,                PDO::PARAM_STR);
$stmt->bindValue(':issue_date',        $issueDate,           PDO::PARAM_STR);
$stmt->bindValue(':expiry_frequency',  $expiryFrequency,     PDO::PARAM_STR);
$stmt->bindValue(':createdBy',         $createdBy,           PDO::PARAM_STR);
$stmt->bindValue(':card_id',           $card_id,             PDO::PARAM_STR);

// For BLOB: if no image, bind null
if ($imgData !== null) {
    $stmt->bindValue(':driver_img', $imgData, PDO::PARAM_LOB);
} else {
    $stmt->bindValue(':driver_img', null,    PDO::PARAM_NULL);
}

// 6) Execute
try {
    $stmt->execute();
    header('Location: ../index.php?message=Success');
    exit;
} catch (PDOException $e) {
    echo '<p style="color:red;">Error inserting driver: '
         . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><a href="index.php">Go Back</a></p>';
    exit;
}

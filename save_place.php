<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function uploadImage($inputName, $targetFolder) {
        if (!isset($_FILES[$inputName])) {
            die("File input '$inputName' not found.");
        }

        if ($_FILES[$inputName]["error"] !== 0) {
            die("Upload error in '$inputName'. Error code: " . $_FILES[$inputName]["error"]);
        }

        if (!is_dir($targetFolder)) {
            if (!mkdir($targetFolder, 0777, true)) {
                die("Failed to create folder: " . $targetFolder);
            }
        }

        $originalName = basename($_FILES[$inputName]["name"]);
        $safeName = time() . "_" . preg_replace("/[^A-Za-z0-9._-]/", "_", $originalName);
        $targetPath = $targetFolder . $safeName;

        if (!move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetPath)) {
            die("Failed to move uploaded file for '$inputName' to: " . $targetPath);
        }

        return $targetPath;
    }

    $title_en = $_POST["title_en"] ?? "";
    $title_si = $_POST["title_si"] ?? "";
    $title_ta = $_POST["title_ta"] ?? "";

    $location_en = $_POST["location_en"] ?? "";
    $location_si = $_POST["location_si"] ?? "";
    $location_ta = $_POST["location_ta"] ?? "";

    $category_en = $_POST["category_en"] ?? "";
    $category_si = $_POST["category_si"] ?? "";
    $category_ta = $_POST["category_ta"] ?? "";

    $description_en = $_POST["description_en"] ?? "";
    $description_si = $_POST["description_si"] ?? "";
    $description_ta = $_POST["description_ta"] ?? "";

    $price = $_POST["price"] ?? 0;
    $rating = $_POST["rating"] ?? 0;
    $tab = $_POST["tab"] ?? "";

    $main_image = uploadImage("main_image", "uploads/main/");
    $photo1 = uploadImage("photo1", "uploads/gallery/");
    $photo2 = uploadImage("photo2", "uploads/gallery/");
    $photo3 = uploadImage("photo3", "uploads/gallery/");

    $stmt = $conn->prepare("INSERT INTO places (
        title_en, title_si, title_ta,
        location_en, location_si, location_ta,
        category_en, category_si, category_ta,
        description_en, description_si, description_ta,
        price, rating, tab,
        main_image, photo1, photo2, photo3
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssssssssddsssss",
        $title_en, $title_si, $title_ta,
        $location_en, $location_si, $location_ta,
        $category_en, $category_si, $category_ta,
        $description_en, $description_si, $description_ta,
        $price, $rating, $tab,
        $main_image, $photo1, $photo2, $photo3
    );

    if ($stmt->execute()) {
        echo "<script>alert('Place saved successfully'); window.location.href='add-place.html';</script>";
    } else {
        echo "Database insert failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Invalid request.";
}
?>
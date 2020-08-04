<?php
    session_start();

    if (!$_SESSION['userID']) {
        header("location: /login.php");
        die();
    }
    require_once 'db.php';

    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $categoryID = $conn->real_escape_string($_POST['categoryID']);
        $parentID = $conn->real_escape_string($_POST['parentID']);
        $parentID = $parentID ? $parentID : 0;
        $name = $conn->real_escape_string($_POST['name']);
        $description = $conn->real_escape_string($_POST['description']);
        if ($categoryID) {
            $sql = "UPDATE `categories` SET `name` = '$name', `description`='$description'" .
                ($categoryID ? 'WHERE `id` = ' . $categoryID : '');
            $conn->query($sql);
            header("Refresh:0");
        } else {
            $sql = "INSERT INTO `categories`(`name`, `description`, `parentID`) VALUES ('$name', '$description', $parentID) ";
            $conn->query($sql);
            $lastID = $conn->insert_id;
            header("location: /dashboard.php?categoryID=" . $parentID);
        }
    }

    $categoryID = $conn->real_escape_string($_GET['categoryID']);
    $parentID = $conn->real_escape_string($_GET['parentID']);
    if ($categoryID) {
        $sql = "SELECT * from `categories` WHERE `id`=" . $categoryID;
        $res = $conn->query($sql);
        if (!$res->num_rows)
            die();
        $category = $res->fetch_assoc();
    }
?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <title>Categories</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/assets/css/main.css">
    </head>
    <body>
        <?php require_once('header.php'); ?>
        <div class="testbox">
            <form method="POST">
                <input type="hidden" name="categoryID" value="<?php echo $categoryID; ?>">
                <input type="hidden" name="parentID" value="<?php echo $parentID; ?>">
                <?php if (!$categoryID): ?>
                    <h1>Add category</h1>
                <?php else: ?>
                    <h1>Edit category(<?php echo $categoryID; ?>)</h1>
                <?php endif; ?>
                <hr/>
                <div class="item">
                    <p>Name</p>
                    <input type="text" name="name" required autofocus
                        value="<?php echo $category['name']; ?>"/>
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="item complaint-details">
                    <p>Description</p>
                    <div class="complaint-details-item">
                        <textarea rows="5" name="description" required><?php echo $category['description']; ?></textarea>
                    </div>
                </div>
                <div class="btn-block">
                    <button class="upsertCategory" type="submit" href="/"><a>Save</a></button>
                </div>
            </form>
        </div>
    </body>
</html>


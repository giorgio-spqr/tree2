<?php
    session_start();

    if (!$_SESSION['userID']) {
        header("location: /login.php");
        die();
    }

    require_once 'db.php';
    if (isset($_GET['removeItem'])) {
        $categoryID = $conn->real_escape_string($_GET['categoryID']);
        $sql = 'DELETE FROM `categories` WHERE `id`=' . $categoryID . ' OR `parentID` = ' . $categoryID;
        $result = $conn->query($sql);
        header('Location: ' . explode('?', $_SERVER['HTTP_REFERER'])[0]);
    }
    $parentID = $conn->real_escape_string($_GET['categoryID']);
    $sql = 'SELECT * from `categories` WHERE `parentID` = ' . ($parentID ? $parentID : 0);
    $result = $conn->query($sql);
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
        <main>
            <?php if ($result->num_rows): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($category = $result->fetch_assoc()) { ?>

                            <tr>
                                <input type="hidden" name="userID" value="<?php echo $category['id']; ?>">
                                <td><?php echo $category['id']; ?></td>
                                <td><?php echo $category['name']; ?></td>
                                <td><?php echo $category['description']; ?></td>
                                <td>
                                    <button type="button" name="edit" class="edit"><a href="/dashboard.php?categoryID=<?php echo $category['id']; ?>"><i class="fa fa-child"></i></a></button>
                                    <button name="remove" class="remove"><a href="/dashboard.php?removeItem=True&categoryID=<?php echo $category['id']; ?>"><i class="fa fa-trash"></i></a></button>
                                    <button type="button" name="edit" class="edit"><a href="/upsertCategory.php?categoryID=<?php echo $category['id']; ?>"><i class="fa fa-edit"></i></a></button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <button class="add-new"><a href="/upsertCategory.php?parentID=<?php echo $parentID; ?>">Add</a></button>
            <?php else: ?>
                <p class="centeredElement">no data found</p>
                <div class="actions-row">
                    <button class="go-back" onclick="history.go(-1);"><a>Go back</a></button>
                    <button class="add-new"><a href="/upsertCategory.php?parentID=<?php echo $parentID; ?>">Add</a></button>
                </div>
            <?php endif; ?>
        </main>
    </body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- PHP Flash -->
    <div>
        <?php if (isset($flashMessage)) echo $flashMessage; ?>
    </div>

    <h1>Add yourself to my EO list!</h1>

    <form action="/" method="post">
        <label for="first_name">First name</label>
        <input type="text" name="first_name">
        <br>
        <label for="last_name">Last name</label>
        <input type="text" name="last_name">
        <br>
        <label for="email">Email</label>
        <input type="email" name="email">
        <br>
        <!-- List email lists -->
        <label for="list_id">Which list?</label>
        <br>
        <select name="list_id">
            <?php
                foreach ($lists as $list) {
                    $id = htmlspecialchars($list['id']);
                    $name = htmlspecialchars($list['name']);
                    echo "<option value='{$id}'>$name</option>";
                }
            ?>
        </select>
        <br>
        <button type="submit">Add me!</button>
    </form>
</body>
</html>

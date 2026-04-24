<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="index.php" method="$_GET">
        <input type="text" name="username" id="username">
        <label for="username">UserName</label>
        <input type="password" name="password" id="password">
        <label for="password">PassWord</label>
        <input type="submit" value="Log IN">
    </form>
</body>
</html>


<?php
echo "{$_GET["username"]}<br>";


?>
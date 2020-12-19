<?php
$db = require_once '../dbstart.php';
$users = $db->getAll('users');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>

<body>

    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="">Users</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/about">About us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
            <br>
            <a href="/create" class="btn btn-success">Add new user</a>
            <hr>

            <?php if (Flash::exists('success')):?>
            <div class="alert alert-success">
                <?php echo Flash::display('success');?>
            </div>
            <?php endif;?>

                <table class="table">
                    <thead class="table-dark">
                        <th>#</th>
                        <th>User name</th>
                        <th>Email</th>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $user):?>
                        <tr>
                            <td><?php echo $user['id'];?></td>
                            <td><?php echo $user['username'];?></td>
                            <td><?php echo $user['email'];?></td>
                            <td><a href="/edit?id=<?php echo $user['id'];?>"class="btn btn-warning">Edit</a></td>
                            <td><a href="/delete?id=<?php echo $user['id'];?>"class="btn btn-danger" onclick="return confirm('Delete this user?');">Delete</a></td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
</body>

</html>
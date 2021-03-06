<!doctype html>
<html lang="en" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Stock analyzer</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jquery-ui.min.css" rel="stylesheet">
    </head>
    <body class="d-flex flex-column h-100">
        <main class="flex-shrink-0">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1 class="mt-5">Stock analyzer</h1>
                    </div>
                    <?php if ($_SERVER['REQUEST_URI'] != '/'): ?>
                    <div class="col-auto mt-5">
                        <a href="/" type="button" class="btn btn-link">Home</a>
                    </div>
                    <?php endif;?>
                </div>
                <hr>

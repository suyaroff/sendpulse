<?php
require 'vendor/autoload.php';


use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;


define('API_USER_ID', 'тут ваш ID');
define('API_SECRET', 'тут ваш SECRET');
define('PATH_TO_ATTACH_FILE', __FILE__);

$SPApiClient = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());

$book_id = isset($_GET['book_id']) ? $_GET['book_id'] : 88973410;

// обработка формы добавления
$result = '';
if (isset($_POST['add'])) {
    $book_id = $_POST['add'];

    $emails = array(
        array(
            'email' => $_POST['email'],
            'variables' => $_POST['variable']
        )
    );

    $result = $SPApiClient->addEmails($book_id, $emails);
    sleep(2); // специально
}



// Список книг
$books = $SPApiClient->listAddressBooks();
// информация о книге 
$book_selected = $SPApiClient->getBookInfo($book_id);
// Параметры книги 
$books_variables = $SPApiClient->getBookVariables($book_id);
// список email 100 c 0
$book_emails = $SPApiClient->getEmailsFromBook($book_id, 100, 0);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <title>With Love From Suyaroff</title>
    <style>
        body {
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Список книг</h2>
        <table class="table table-bordered table-sm">
            <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">name</th>
                    <th scope="col">all_email_qty</th>
                    <th scope="col">active_email_qty</th>
                    <th scope="col">inactive_email_qty</th>
                    <th scope="col">creationdate</th>
                    <th scope="col">status</th>
                    <th scope="col">status_explain</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $b) { ?>
                    <tr>
                        <th scope="row">
                            <a href="?book_id=<?= $b->id; ?>" class="btn btn-sm btn-primary"><?= $b->id; ?></a>
                        </th>
                        <td><?= $b->name; ?></td>
                        <td><?= $b->all_email_qty; ?></td>
                        <td><?= $b->active_email_qty; ?></td>
                        <td><?= $b->inactive_email_qty; ?></td>
                        <td><?= $b->creationdate; ?></td>
                        <td><?= $b->status; ?></td>
                        <td><?= $b->status_explain; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="row mb-5">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Информация о книге ID <?= $book_id; ?></h5>
                        <p class="card-text">
                            <pre><? print_r($book_selected); ?></pre>
                            <hr>
                            <h5>Параметры</h5>
                            <pre><? print_r($books_variables); ?></pre>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Добавить в книгу запись </h5>
                        <div class="card-text">
                            <form method="post">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input name="email" type="email" class="form-control" id="exampleInputEmail1">
                                </div>

                                <div class="form-group">
                                    <label>Параметр name</label>
                                    <input name="variable[name]" type="text" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Параметр phone</label>
                                    <input name="variable[phone]" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Параметр skype</label>
                                    <input name="variable[skype]" type="text" class="form-control">
                                </div>



                                <input type="hidden" name="add" value="<?= $book_id; ?>">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                            <?php var_dump($result); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Список контактов в книге <?= $book_selected[0]->name; ?></h5>
                        <p class="card-text">
                            <table class="table table-bordered table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">email</th>
                                        <th scope="col">status</th>
                                        <th scope="col">status_explain</th>
                                        <th scope="col">phone</th>
                                        <th scope="col">variables</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($book_emails as $be) { ?>
                                        <tr>
                                            <td><?= $be->email; ?></td>
                                            <td><?= $be->status; ?></td>
                                            <td><?= $be->status_explain; ?></td>
                                            <td><?= $be->phone; ?></td>
                                            <td>
                                                <pre><? print_r($be->variables); ?></pre>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>
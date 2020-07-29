<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otometa - Metadata Export to Excel or Spreadsheet</title>
    <link rel="shortcut icon" href="https://avatars3.githubusercontent.com/u/11769214" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .form-control,
        .btn,
        .alert {
            border-radius: 0;
        }

        a {
            color: initial;
            transition: color 0.5s ease;
        }

        a:hover {
            text-decoration: none;
        }

        h1.lead {
            font-size: 2em;
        }

        .url-list .url:nth-child(1) .btn-delete-url {
            visibility: hidden;
        }
    </style>
</head>
<body>

    <div class="container py-5 my-5">
        <h1 class="lead text-uppercase">Convert your site metadata into spreadsheet</h1>
        <hr>
        <h2 class="lead mt-5 mb-3">Input Your URL</h2>
        <form action="process.php" method="post">
            <div class="url-list">
                <div class="url row form-group">
                    <div class="col">
                        <input type="text" name="url[]" class="form-control" placeholder="http://your.url" autocomplete="off">
                    </div>
                    <div class="col-auto pl-0">
                        <button type="button" class="btn-delete-url btn btn-danger rounded-circle font-weight-bolder" title="Delete this url" tabindex="-1">&times;</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="button" class="btn-add-url btn btn-secondary mr-3">Add URL</button>
                <button type="submit" class="btn-submit btn btn-success">Export</button>
            </div>
        </form>
        <br>
        <br>
        <br>
        <br>
        <p class="mb-2">Made with &hearts; by <a href="https://www.instagram.com/arix.wap/" target="blank" class="font-weight-bolder">Arix Wap</a></p>
        <div class="row">
            <div class="col-auto">
                <p class="mb-1">Credit : </p>
            </div>
            <div class="col">
                <ul class="list-unstyled">
                    <li><a href="https://github.com/PHPOffice/PhpSpreadsheet" target="blank">PhpSpreadsheet by PHPOffice</a></li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>

        // Define your maximum number can input URL
        let maxUrl = 100;

        // Button remove url
        $(document).on('click', '.btn-delete-url', function() {
            $(this).closest('.row.url').remove();
        });

        // Button add url
        $(document).on('click', '.btn-add-url', function() {

            let elements = $('.url-list .url');

            if ( elements.length < maxUrl ) {
                let element = elements.first().clone();
                element.find('input').val('');
                $('.url-list').append(element);
            } else if ( $('.alert-max-url').length <= 0 ) {
                $('form').after('<div class="alert-max-url alert alert-warning alert-dismissible fade show" role="alert">Cannot add url more than ' + maxUrl + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            }
        });

        // Ajax Process - Send URL Data
        $('form').on('submit', function(event) {

            event.preventDefault();
            let targetUrl = $(this).attr('action');
            let urlList = $(this).serializeArray();
            console.log(urlList);

            // Start Loop Send Ajax
            urlList.forEach( function(item, i) {
                console.log(item, i);
                $.ajax({
                    async: false,
                    url: targetUrl,
                    data: [item],
                    method: 'post',
                    beforeSend: function() {},
                    success: function(response, status, xhr) {
                        console.log(response);
                    },
                    error: function(xhr, status) {
                        console.log(status);
                        console.log(xhr);
                    }
                })
            });

            // Open link excel file
            console.log('Open Link Excel');

        });

    </script>
</body>
</html>

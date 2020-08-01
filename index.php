<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta Sheet - Metadata Exporter to Excel Spreadsheet</title>
    <link rel="shortcut icon" href="https://avatars3.githubusercontent.com/u/11769214" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

        .main-title {
            font-size: 2em;
            line-height: 1.5;
        }

        .url-list .url:nth-child(1) .btn-delete-url {
            visibility: hidden;
        }

        @media (max-width: 768px) {
            .main-content {
                min-height: calc(100vh - 280px);
            }

            .main-title {
                font-size: 1.3em;
            }
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="main-content py-5 mb-0 mb-md-5">
            <header>
                <h1 class="main-title lead text-uppercase">Convert your site metadata into spreadsheet</h1>
                <hr>
            </header>
            <h2 class="form-title lead mt-5 mb-3">Input Your URL</h2>
            <form action="process.php" method="post">
                <div class="url-list">
                    <div class="url row form-group">
                        <div class="col">
                            <input type="hidden" name="number[]" value="1">
                            <input type="url" name="url[]" class="form-control" autocomplete="off" required>
                        </div>
                        <div class="col-auto pl-0">
                            <button type="button" class="btn-delete-url btn btn-link text-danger shadow-none" title="Delete this url" tabindex="-1"><span class="fa fa-times"></span></button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="button" class="btn-add-url btn btn-secondary mr-3">Add URL</button>
                    <button type="submit" class="btn-submit btn btn-success">Export</button>
                </div>
            </form>

            <div class="progress-export progress d-none">
                <div class="progress-bar" role="progressbar"></div>
            </div>
        </div>

        <footer>
            <hr>
            <p class="mb-3">Made with &hearts; by <a href="https://www.instagram.com/arix.wap/" target="blank" class="font-weight-bolder">Arix Wap</a></p>
            <p class="font-weight-bolder mb-0">Credit : </p>
            <ul class="list-unstyled">
                <li><a href="https://stackoverflow.com/questions/5151167/getting-meta-tags-info-using-curl-and-get-meta-tags" target="blank">Get meta tags using curl</a></li>
                <li><a href="https://github.com/PHPOffice/PhpSpreadsheet" target="blank">PhpSpreadsheet by PHPOffice</a></li>
                <li><a href="https://stackoverflow.com/questions/15774669/list-all-files-in-one-directory-php" target="blank">List directory in php</a></li>
                <li><a href="https://getbootstrap.com" target="blank">Bootstrap</a></li>
                <li><a href="https://jquery.com" target="blank">jQuery</a></li>
                <li><a href="https://fontawesome.com/v4.7.0" target="blank">Font Awesome 4.7.0</a></li>
            </ul>
        </footer>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>

        // Define your maximum number can input URL
        let maxUrl = 100;

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

            orderInputNumber();
        });

        // Button remove url
        $(document).on('click', '.btn-delete-url', function() {
            $(this).closest('.row.url').remove();
            orderInputNumber();
        });

        // Ordering Input Number Value
        function orderInputNumber() {
            $('.url-list .url input[type=hidden]').each( function(i, element) {
                $(element).val( i + 1 );
            });
        }

        // Ajax Process - Send URL Data
        $('form').on('submit', function(event) {

            event.preventDefault();

            $('form').css('opacity', '0.5');
            $('form input, form button').attr('disabled', 'disabled');
            $('.progress-export').removeClass('d-none');
            $('.progress-export .progress-bar').css('width', '0%');

            // Start Loop Send Ajax
            let i = 1;
            let progress = 0;
            let targetUrl = $(this).attr('action');
            let urlList = $('.url-list .url');

            urlList.each( function(indexElement, element) {

                let dataUrl = [];
                $(element).find('input').each( function(indexInput, input) {
                    dataUrl.push({
                        name: $(input).attr('name'),
                        value: $(input).val()
                    })
                });

                $.ajax({
                    async: true,
                    url: targetUrl,
                    data: dataUrl,
                    method: 'post',
                    beforeSend: function() {},
                    success: function(response, status, xhr) {
                        // console.log(status);
                        // console.log(response);

                        progress = parseInt( ( i / urlList.length ) * 100 );
                        $('.progress-export .progress-bar').css('width', progress + '%');
                        i++;

                        // Check if process is complete
                        if ( i > urlList.length )  {
                            window.location.href = '/download.php'; // Open link excel file
                            $('form').removeAttr('style');
                            $('form input, form button').removeAttr('disabled');
                        }
                    },
                    error: function(xhr, status) {
                        console.log(status);
                        console.log(xhr);
                    }
                })

            });

        });

    </script>
</body>
</html>

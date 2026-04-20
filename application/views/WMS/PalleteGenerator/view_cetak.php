<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Nomor Pallet</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <style>
    * {
        margin: 0;
        padding: 0;
        /* box-sizing: border-box; */
    }

    html {
        position: relative;
        min-width: 180mm;
        min-height: 100mm;
    }

    table {
        margin: 0 auto;
        /* or margin: 0 auto 0 auto */
    }
    </style>

</head>

<body>
    <div class="text-center">
        <?php foreach ($header as $data) { ?>
        <div
            style="display: inline-block; margin: 1rem; text-align:center; padding-top: 20%;  page-break-inside: avoid;">
            <div style="border: 1px dashed black; padding: 10px;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=<?= $data->pg_kode ?>&amp;size=216x216" />
                <p class="text-center" style="margin-top:10px; font-size: 20px"><strong><?= $data->pg_kode ?></strong>
                </p>
            </div>
        </div>
        <?php } ?>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"
        integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous">
    </script>

    <script>
    $(document).ready(function() {
        window.print();
    });
    </script>
</body>

</html>
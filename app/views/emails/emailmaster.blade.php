<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8" />
        <style type="text/css">
            body {
                font-family: Arial, Helvetica, Calibri;
                font-size:10pt;
            }
            h1 {
                font-size:14pt;
            }
            table {
                border-spacing: 0;
                border-collapse: collapse;
                margin:0;
            }
            td {
                padding: 0;
            }
            .right {
                text-align: right;
            }
            span.small, span.small a, td.small, td.small a {
                font-size: 8pt !important;
            }

        </style>
    </head>
    <body>
        <table>
            <tr>
                <td><img src="<?php echo $message->embed(public_path() . '/img/logos/' . $mandant->logo); ?>"></td>
            </tr>
            <tr>
                <td>@yield('content')</td>
            </tr>
        </table>
    </body>
</html>
<?php $page_error_code = $e->getStatusCode() ?>

        <!DOCTYPE html>
<html>
<head>
    <title>Service Unavailable</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
<style type="text/css">
    html {
        height: 100%;
    }

    body {
        padding: 0px;
        margin: 0px;
        width: 100%;
        height: 80%;
        text-align: center;
        background-color: #f5f5f5;
        font-family: 'Segoe UI', 'Open Sans', sans-serif;
        display: table;
    }

    .error-content {
        overflow: hidden;
        display: table-cell;
        vertical-align: middle;
        width: 100%;
    }

    .error-content > .error-box {
        border: 1px solid #d43f3a;
        border-radius: 5px;
        padding: 0 40px 40px;
        width: 50%;
        margin: auto;
        background-color: #fff;
    }

    .error-content > h1 {
        color: #575757;
        text-transform: uppercase;
        font-size: 40pt;
        margin: 0 10px 10px 10px;
    }

    .error-box > h2 {
        color: #d9534f;
        background-color: #fff;
    }

    .error-box > span {
        font-style: bold;
        color: #575757;
        margin-bottom: 10px;
    }

    .error-box > ul {
        margin: 20px 0;
        padding: 0;
        list-style-type: none
    }

    .error-box > ul > li {
        margin: 10px auto;
        color: #1c75BC;
    }

    #sf-resetcontent {
        width: 100% !important;
    }

</style>
<div class="error-content">
    <h1 class="error-title">{!! $page_error_code !!} Error</h1>
    <div class="error-box">
        @if($page_error_code == '404')
            <h2>Opps! The page you tried cannot be found.</h2>
            <span style="font-weight: bold;">Possible reason(s):</span>

            <ul>
                @if(Config::get('app.debug'))
                    {!! $raw !!}
                @else
                    <li>You may have typed the address incorrectly.</li>
                    <li>You may have used an outdated link.</li>
                    <li>The page may have been moved or deleted.</li>
                @endif
            </ul>

        @elseif($page_error_code == '503')
            <h2>Service Temperarily Unavailable</h2>
            <span>Opps! Something unexpected happened. Our support team has been notified. Please try again shortly.</span>
        @else
            <h2>Permission Denied</h2>
            <span>Opps! You do not have permission to access the page. Please contact with the administrator of the site to have permission granted.</span>
        @endif
    </div>
</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
<style>

    table {
        font-family: sans-serif, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue';
        border-collapse: collapse;
        width: 100%;
        font-size:12px;
    }

    .border {
        border: 1px solid black;
    }

    td, th {
        text-align: left;
        padding: 2px;
        font-size: 12px;
        border: 1px solid #d2d2d2;
        border-collapse: collapse;
        padding: 7px 8px;
    }

    tr:nth-child(even) {
        background-color: #eeeeee;
    }

    .head tr:nth-child(even) {
        background-color: white;
    }

    

</style>
</head>
<body>
    @yield('content')
</body>
</html>
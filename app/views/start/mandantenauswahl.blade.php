<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        @yield('head_pre')
        {{ HTML::style('/css/globals.css') }}
        <title>Web2Print</title>
        @yield('head')
    </head>
    <body>
        <ul>
        @foreach($mandanten as $m)
        <li><a href="http://{{$m->hostname}}">{{{$m->name}}}</a></li>
        @endforeach
        </ul>
    </body>
</html>



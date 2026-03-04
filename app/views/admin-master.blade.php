<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        @yield('head_pre')
        {{ HTML::style('/css/globals.css') }}
        {{ HTML::style('/css/admin.css') }}
        <title>Web2Print<?php if(isset($title))echo ' - ',htmlspecialchars($title);?></title>
        @yield('head')
    </head>
    <body>
        <div id="outercontainer">
            <div id="container">
                @if(isset($registered_user))
                <div style="float:right">
                    <a href="/logout" class="logout" title="abmelden"><img src="/img/logout.png"></a>
                </div>
                @endif
                <div class="header">
                    <div class="logo"></div>
                </div>
                <div id="main_shadow">
                    <div id="main">
                        <div class="content">
                            @if(isset($tabs))
                            <div class="tabs">
                                <ul>
                                    @foreach($tabs as $name=>$tab)
                                    @if($tab['display'])
                                    <li<?php echo $tab['href'] == '#' || Request::is(substr($tab['href'], 1)) ? ' class="active"' : ''; ?>><a href="{{$tab['href']}}">{{$tab['text']}}</a></li>
                                    @endif
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            @yield('content')
                        </div>
                        <p style="clear: both;"></p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
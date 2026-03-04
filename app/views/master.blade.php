<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        @yield('head_pre')
        {{ HTML::style('/css/globals.css') }}
        @if(!Request::is('alter_browser'))
        {{ HTML::script('/js/browsercheck.js') }}
        @endif
        <title>Web2Print<?php if(isset($title))echo ' - ',htmlspecialchars($title);?></title>
        @yield('head')
        <!--[if IE 9]>{{ HTML::style('/css/globals_ie9.css') }}<![endif]-->
        @if(isset($mandant))
        {{$mandant->getCSS()}}<?php /*

        @if($_id=$mandant->piwik_id)
        <script type="text/javascript">
            var _paq = _paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u = (("https:" === document.location.protocol) ? "https" : "http") + "://piwik.bairle.de/";
                _paq.push(['setTrackerUrl', u + 'piwik.php']);
                _paq.push(['setSiteId', 1]);
                var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
                g.type = 'text/javascript';
                g.defer = true;
                g.async = true;
                g.src = u + 'piwik.js';
                s.parentNode.insertBefore(g, s);
            })();
        </script>
        <noscript><p><img src="http://piwik.bairle.de/piwik.php?idsite={{$_id}}" style="border:0;" alt="" /></p></noscript>
        @endif
         */?>
        @endif
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
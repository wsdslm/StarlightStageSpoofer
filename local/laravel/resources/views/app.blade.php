<!doctype html>
<html>
    <head>
        <title>Starlight Stage Spoofer</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <script type="text/javascript">
            window.base_url = "{{ url("/") }}";
            window.user = {!! isset($user) ? json_encode($user) : "null" !!};
            window.pubnub_options = {
                subscribeKey: "{!! env('PUBNUB_SUBSCRIBE_KEY') !!}",
                presenceTimeout: 1400,
                heartbeatInterval: 60
            };
        </script>
    </head>
    <body>
        <div id="root"></div>
        <script src="{{ url("/dist/bundle.js") }}"></script>
    </body>
</html>

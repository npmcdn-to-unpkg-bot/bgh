<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>ArtVenue Installer</title>
    {!!  HTML::style('static/css/main.css')  !!}
    {!! HTML::style('static/css/style.min.css')  !!}
</head>
<body>
<div class="container">
    @if(!extension_loaded('imagick') && !extension_loaded('gd'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Please enable Imagick or GD php extension</strong>
        </div>
    @endif
    @if(!extension_loaded('fileinfo'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Please enable fileinfo php extension</strong>
        </div>
    @endif
    @if(!extension_loaded('curl'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Please enable CURL php extension</strong>
        </div>
    @endif
    @if(!extension_loaded('openssl'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Please enable openssl php extension</strong>
        </div>
    @endif
    @if(!extension_loaded('mbstring'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Please enable Mbstring php extension</strong>
        </div>
    @endif
    @if(!extension_loaded('tokenizer'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>Please enable Mbstring php extension</strong>
        </div>
    @endif
    @if(Session::has('flashError'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>{{ Session::get('flashError') }}</strong>
        </div>
    @endif

    @if(Session::has('errors'))
        <div class="alert alert-danger fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <strong>{{ Session::get('errors')->first() }}</strong>
        </div>
    @endif

    <h1>Installer</h1>
    <hr>
    <h3>MYSQL Settings</h3>
    <hr>
    <form role="form" method="post">
        <div class="form-group">
            <label for="host">Host</label>
            <input type="text" name="host" class="form-control" placeholder="Host" value="localhost">
        </div>

        <div class="form-group">
            <label for="host">Database Name</label>
            <input type="text" name="dbname" class="form-control" placeholder="Database Name">
        </div>

        <div class="form-group">
            <label for="host">Database User</label>
            <input type="text" name="dbuser" class="form-control" placeholder="Database User">
        </div>
        <div class="form-group">
            <label for="host">Database Password</label>
            <input type="password" name="dbpassword" class="form-control" placeholder="Database Password">
        </div>


        <hr>
        <h3>Site Admin Settings</h3>
        <hr>
        <div class="form-group">
            <label for="siteusername">Your Username</label>
            <input type="text" name="siteusername" class="form-control" placeholder="Your username on your site">
        </div>
        <div class="form-group">
            <label for="sitepassword">Your Password</label>
            <input type="password" name="sitepassword" class="form-control" placeholder="Your password">
        </div>
        <div class="form-group">
            <label for="sitefullname">Your Fullname</label>
            <input type="text" name="sitefullname" class="form-control" placeholder="Your Fullname">
        </div>
        <div class="form-group">
            <label for="siteemail">Your Email</label>
            <input type="text" name="siteemail" class="form-control" placeholder="Your email address">
        </div>


        <hr>
        <h3>Envato Account
            <small>( codecanyon )</small>
        </h3>
        <hr>
        <div class="form-group">
            <label for="username">Envato Username</label>
            <input type="text" name="username" class="form-control" value="Nulled-By-fire2000" Readonly>
        </div>
        <div class="form-group">
            <label for="itemcode">Item Purchase Code</label>
            <input type="text" name="itemcode" class="form-control" value="Nulled-By-fire2000" Readonly>
        </div>
        <button type="submit" class="btn btn-success">Install</button>
    </form>
    <hr>
</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Core Login</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Style Sheet -->
    <link href="{{ asset('/build/library/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/build/library/jquery-scrollbar/jquery.scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('/build/app/css/app.css') }}" rel="stylesheet">

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

    <!-- Scripts -->
    <script src="{{ asset('/build/library/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('/build/library/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('/build/library/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
</head>
<body>
    <div id="wrapper" class="scrollbar-macosx">
        @include('cms::partial.header')
        <!-- Site Content -->
        <div id="page-wrapper" class="page-wrapper-scroll ">
            <div id="login-page">
                <div class="login-header">
                    <div class="logo">
                        <h1>Back Office</h1><small>admin</small>
                    </div>
                    <div class="text-right"><h2 class="title"><i class="fa fa-user fa-fw"></i>Sign In</h2></div>
                    
                </div>
                <form id ="admin-login-form" method="POST" role="form" action="{!! route('authenticate.postLogin') !!}">
                    <!-- Form Title -->
                    <span class="form-title">Welcome to Admin Back Office</span>
                    <hr>
                    <?php echo csrf_field(); ?>
                    <!-- Flash Notice  -->
                    @include('cms::partial.flash')
                    <!-- Email field -->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group input-group-icon">
                            <input class="form-control input-lg" placeholder="E-mail" type="email" name="email" autofocus>
                            <span class="input-group-addon"><span class="icon icon-lg"><i class="fa fa-user"></i></span></span>
                        </div>
                        
                    </div>
                    <!-- Password field -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <span class="pull-right"><a href="#">Forgot Password</a></span>
                        <div class="input-group input-group-icon">
                            <input class="form-control input-lg" placeholder="Password" type="password" name="password" value="">
                            <span class="input-group-addon"><span class="icon icon-lg"><i class="fa fa-lock"></i></span></span>
                        </div> 
                    </div>
                    <!-- Remember me -->
                    <div class="checkbox">
                        <input type="checkbox" id="remember_me" name="remember" value="Remember Me">
                        <label for="remember_me">Remember Me</label> 
                    </div>
                    <!-- Submit Button -->
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary login-btn"><i class="fa fa-arrow-circle-right fa-fw"></i> Sign In</button>
                    </div>
                </form>
            </div>
            <div class="copyright text-center">© 2015 EXE Corp. ALL RIGHTS RESERVED.</div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('/build/library/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('/build/library/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('/build/library/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
</body>
</html>
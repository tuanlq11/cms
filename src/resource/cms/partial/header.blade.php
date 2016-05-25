<!-- Header -->
<nav id="main-navigator" class="navbar navbar-fixed-top">
    <!-- Brand Logo -->
    <div class="navbar-brand">
        <a href="{!! route('dashboard.index') !!}">
            <img class="brand-logo" src="{{asset('/build/app/images/logo.png')}}"/>
            <span>Core Back Office</span>
        </a>
    </div>
    <?php if(Auth::check()):?>
            <!-- Mini Right Menu -->
    <div class="nav navbar-right top-nav">
        <span class="top-seperator"></span>

        <!-- Language Box -->
        <div class="language-box">
            <?php
            $supportedLang = $controller->supportedLang();
            $currentLocale = $controller->getCurrentLocale();
            $currentLang = array_get($supportedLang, $currentLocale, 'English');
            ?>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <span class="name">{{ $currentLang }}</span>
                <i class="fa fa-chevron-down"></i>
            </a>

            <ul class="dropdown-menu">
                <li class="divider"></li>
                @foreach($supportedLang as $locale => $name)
                    <li>
                        <a href="{{route('switch_lang', ['locale' => $locale])}}"
                           class="{{ $locale == $currentLocale?"selected":"" }}"
                           data-locale="{{ $locale }}">{{ $name }}</a>
                    </li>
                @endforeach
            </ul>

        </div>

        <span class="top-seperator"></span>
        <!-- User Profile Box -->
        <div class="userbox">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <figure class="profile-image">
                    <img class="img-circle" src="{!! asset('build/app/images/profile-default.png') !!}"
                         alt="Profile Image"/>
                </figure>
                <div class="profile-info">
                    <span class="name">{{ucfirst(Auth::user()->first_name)}} {{ucfirst(Auth::user()->last_name)}}</span>
                </div>
                <i class="fa fa-chevron-down"></i>
            </a>
            <ul class="dropdown-menu">
                <li class="divider"></li>
                <li>
                    <a href="#">
                        <i class="fa fa-fw fa-user"></i>Profile
                    </a>
                </li>
                <li>
                    <a href="{!! route('auth.logout') !!}">
                        <i class="fa fa-fw fa-power-off"></i>Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <?php endif;?>
</nav>

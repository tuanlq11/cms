<?php
$successes = (array)Session::get('success', []);
$errors = (array)Session::get('error', []);
$warnings = (array)Session::get('warning', []);
?>

<div class="row">
    <div class="col-lg-12">
        @foreach($successes as $message)
            <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                {{ $message }}
            </div>
        @endforeach

        @foreach($warnings as $message)
            <div class="alert alert-warning" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                {{ $message }}
            </div>
        @endforeach

        @foreach($errors as $message)
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                {{ $message }}
            </div>
        @endforeach
    </div>
</div>

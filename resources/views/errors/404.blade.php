@extends('layouts.error')

@section('body')
  <div class="row text-center">
    <div class="error-template">
      <h1> Oops!</h1>
      <h2> 404 Not Found</h2>
      <div class="container-fluid my-4">
        <h5> Sorry, an error has occured, Requested page not found! </h5>
        <img src="/images/morty.gif" class="img-fluid ms-auto my-4 w-75" alt="morty-confused">
      </div>
      <div class="error-actions">
        <a href="/" class="btn btn-primary btn-lg"> Country roads?<br>Take me Home</a>
        <a href="#" class="btn btn-default btn-lg opacity-90">Contact <br>Support <i
            class="fas fa-ticket-alt text-blue-400"></i> </a>
      </div>
    </div>
  </div>
@endsection

@extends('layouts.error')
@section('body')
  <div class="row text-center">
    <div class="error-template">
      <h1>Oops!</h1>
      <h2>403 Forbidden</h2>
      <div class="container-fluid my-4">
        <h5>Seems like you don't have permissions to perform this request!</h5>
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

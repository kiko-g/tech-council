@extends('layouts.profile', ['user' => $user, 'user_questions' => $user_questions])

@section('content')
  @include('partials.filters.profile')
  <section>
    @foreach ($user_questions as $question)
      @include('partials.question.card', ['question' => $question, 'include_comments' => false])
    @endforeach
  </section>
  <nav>
    <ul class="pagination justify-content-center">
      <li class="page-item">
        <a class="page-link blue" href="#" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">Previous</span>
        </a>
      </li>

      <li class="page-item"><a class="page-link blue" href="#">1</a></li>
      <li class="page-item"><a class="page-link blue active" href="#">2</a></li>
      <li class="page-item"><a class="page-link blue" href="#">3</a></li>

      <li class="page-item">
        <a class="page-link blue" href="#" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Next</span>
        </a>
      </li>
    </ul>
  </nav>
@endsection

@section('aside')
  @include('partials.user.aside')
@endsection

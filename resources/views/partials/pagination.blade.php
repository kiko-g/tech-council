@php
  $pages = ceil($count/$rpp);
@endphp
<nav id="{{$type ?? ''}}pagination">
  <ul class="pagination justify-content-center">
    <li class="page-item">
      <a id="{{$type ?? ''}}previous" class="page-link blue" href="#" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>

    <li class="page-item">
      <a id="{{$type ?? ''}}current" class="page-link blue active" page="{{$page}}" pages="{{$pages}}" rpp="{{$rpp ?? 6}}" href="#">
        {{$page}} / {{$pages}}
      </a>
    </li>

    <li class="page-item">
      <a id="{{$type ?? ''}}next" class="page-link blue" href="#" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>
</nav>

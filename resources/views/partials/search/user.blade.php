<section id="search-user-results">
  @php
    use App\Models\User;
    $user_amount = count($users);
    $rows_amount = ceil($user_amount / 3);
  @endphp
  @if ($user_amount === 0)
    @include('partials.search.noresults')
  @else
    @for ($row = 0; $row < $rows_amount; $row++)
      <div class="row">
        @php
          $cols = $user_amount - $row * 3;
          if ($cols > 3) {
              $cols = 3;
          }
        @endphp
        @for ($i = 0; $i < 3; $i++)
          <div class="col">
            @if($i < $cols)
              @include('partials.user.card', ['user' => $users[$i + ($cols*$row)]])
            @endif
          </div>
        @endfor
      </div>
    @endfor
    @include('partials.pagination', ['type' => "search-user-"])
  @endif
</section>

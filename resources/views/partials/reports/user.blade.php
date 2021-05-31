<div class="tag-table-border">
  <table class="table table-responsive table-light tag-table table-hover">
    <thead class="table table-dark petrol">
      <tr>
        <th scope="col col-auto">#</th>
        <th scope="col col-auto">User</th>
        <th scope="col col-auto">Reporter</th>
        <th scope="col col-auto">Message</th>
        <th scope="col col-auto">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($user_reports as $user_report)
        <tr>
          <th scope="row">{{ $user_report->report->id }}</th>
          <td>
            <a href="{{ route('user', ['id' => $user_report->reported_user->id]) }}">
              {{ $user_report->reported_user->name }}
            </a>
          </td>
          <td>
            <a href="{{ route('user', ['id' => $user_report->report->reporter->id]) }}">
              {{ $user_report->report->reporter->name }}
            </a>
            @if ($user_report->report->reporter->moderator)
              @include('partials.moderator-badge')
            @elseif ($user_report->report->reporter->expert)
              @include('partials.expert-badge')
            @endif
          </td>
          <td>
            <p class="mb-0">{{ $user_report->report->description }}</p>
          </td>
          <td class="">
            <button type="button" class="btn btn-sm teal d-inline-flex align-items-center"> Solve </button>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

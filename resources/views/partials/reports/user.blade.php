<div class="tag-table-border">
  <table class="table table-responsive table-light tag-table table-hover">
    <thead class="table table-dark petrol">
      <tr>
        <th class="col col-auto">#</th>
        <th class="col col-auto">User</th>
        <th class="col col-auto">Reporter</th>
        <th class="col col-auto">Message</th>
        <th class="col col-auto">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($user_reports as $user_report)
        @if (!$user_report->report->solved)
        <tr id="report-{{ $user_report->report->id }}">
          <th class="row">{{ $user_report->report->id }}</th>
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
              @include('partials.icons.moderator')
            @elseif ($user_report->report->reporter->expert)
              @include('partials.icons.medal')
            @endif
          </td>
          <td>
            <p class="mb-0">{{ $user_report->report->description }}</p>
          </td>
          <td class="">
            <button type="button" class="solve-report btn btn-sm teal d-inline-flex align-items-center" data-report-id="{{ $user_report->report->id }}"
              id="solve-report-{{ $user_report->report->id }}">
              Solve
            </button>
          </td>
        </tr>
        @endif
      @endforeach
    </tbody>
  </table>
</div>

<div class="tag-table-border row justify-content-center">
  <table class="table table-responsive table-light tag-table table-hover">
    <thead class="table table-dark petrol">
      <tr>
        <th scope="col col-auto">#</th>
        <th scope="col col-auto">Content type</th>
        <th scope="col col-auto">Content creator</th>
        <th scope="col col-auto">Message</th>
        <th scope="col col-auto">Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($content_reports as $content_report)
        <tr>
          <th scope="row">{{ $content_report->report->id }}</th>
          <td>
            @php $type = $content_report->content_type_reported($content_report->content_id) @endphp
            <a href="{{ route(strtolower($type), ['id' => $content_report->get_question_id($type, $content_report->content_id)]) }}">
              {{ $type }}
            </a>
          </td>
          <td>
            <a href="{{ route('user', ['id' => $content_report->report->reporter->id]) }}">
              {{ $content_report->report->reporter->name }}
            </a>
            @if ($content_report->report->reporter->moderator)
              @include('partials.icons.moderator')
            @elseif ($content_report->report->reporter->expert)
              @include('partials.icons.medal')
            @endif
          </td>
          <td>
            <p class="mb-0">{{ $content_report->report->description }}</p>
          </td>
          <td>
            <button type="button" class="btn btn-sm teal d-inline-flex align-items-center">
              Solve
            </button>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="tag-table-border">
    <table class="table table-light tag-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Content type</th>
                <th scope="col">Content</th>
                <th scope="col">Message</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($content_reports as $content_report)
                <tr>
                    <th scope="row">{{ $content_report->report->id }}</th>
                    <td>
                        <a href="{{ route('question', ['id' => $content_report->get_question_id($content_report->content_id)]) }}">
                            {{ $content_report->content_type_reported($content_report->content_id) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('user', ['id' => $content_report->report->reporter->id]) }}">
                            {{ $content_report->report->reporter->name }}
                        </a>
                        @if ($content_report->report->reporter->moderator)
                            @include('partials.moderator-badge')
                        @elseif ($content_report->report->reporter->expert)
                            @include('partials.expert-badge')
                        @endif
                    </td>
                    <td>
                        <p>{{ $content_report->report->description }}</p>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm teal d-inline-flex align-items-center">
                            Mark as solved
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="tag-table-border">
    <table class="table table-light tag-table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">User</th>
                <th scope="col">Reporter</th>
                <th scope="col">Message</th>
                <th scope="col">Actions</th>
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
                        <p>{{ $user_report->report->description }}</p>
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

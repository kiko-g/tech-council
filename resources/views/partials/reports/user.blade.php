<div class="tag-table-border">
    <table class="table table-light tag-table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Tag</th>
          <th scope="col">Created by</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($displayed_tags as $tag)
        <tr>
          <th scope="row">{{ $tag->id }}</th>
          <td>
            <a href="{{ route('tag', ['id' => $tag->id]) }}" class="btn blue-alt border-0 my-btn-pad2">{{ $tag->name }}</a>
          </td>
          <td>
            <span>{{ $tag->author->name }}</span>
            @if ($tag->author->moderator)
              @include('partials.moderator-badge')
            @elseif ($tag->author->expert)
              @include('partials.expert-badge')
            @endif
          </td>
          <td>
            @include('partials.tag.edit-buttons')
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  
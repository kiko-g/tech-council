<div class="tag-table-border">
  <table id="tag-table" class="table table-light tag-table">
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
          <a href="{{ route('tag', ['id' => $tag->id]) }}" id="tag-{{ $tag->id }}-redirect" class="collapse show tag-{{ $tag->id }}-edit btn blue-alt border-0 my-btn-pad2 flex-column">{{ $tag->name }}</a>
          <input id="tag-{{ $tag->id }}-name" type="text" class="collapse form-control shadow-sm border border-2 bg-light tag-{{ $tag->id }}-edit" placeholder="Tag name" value="{{ $tag->name }}" required>
          <textarea id="tag-{{ $tag->id }}-description" name="main" class="collapse tag-{{ $tag->id }}-edit form-control shadow-sm border border-2 bg-light" rows="2" placeholder="Type your answer">{{ $tag->description }}</textarea>
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
          @include('partials.tag.edit-buttons', ['tag' => $tag])
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

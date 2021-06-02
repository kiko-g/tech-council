{{--  @php
  if (!isset($text)) $text = 'Tooltip info';
  if (!isset($position)) $position = 'top';
@endphp
<a id="exemplo" data-toggle="tooltip" data-bs-placement="{{ $position }}" title="{{ $text }}">Hover here</a>
<span id="exemplo" href="#" data-toggle="tooltip" title="Hooray!"><i class="fas fa-info-circle"></i></span>
<script> let tooltip = new bootstrap.Tooltip(document.getElementById('exemplo')); </script>  --}}
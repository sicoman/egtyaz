<ul class="breadCpanel">
  @if( isset($BREADCRUMBS) )
    @foreach ($BREADCRUMBS as $elem)
      <li><a href="{{$elem['url']}}">{{$elem['name']}}</a></li>
    @endforeach
  @endif
</ul>
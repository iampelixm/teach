@php
if (!isset($items)) {
    $items = [];
}
if (!$items) {
    return '';
}
$items = collect($items);
if (!$items->first()) {
    return '';
}
$header = $items->first()->toArray();
if (!isset($link)) {
    $link = '';
}
if (!isset($link_item_key)) {
    $link_item_key = '';
}

if (!isset($captions)) {
    $captions = [];
}
if (!isset($show_fields)) {
    $show_fields = array_keys($header);
}

if (!empty($captions)) {
    $buf_arr = [];
    $show_fields = [];
    foreach ($header as $header_key => $header_item) {
        if (!empty($captions[$header_key])) {
            $show_fields[] = $header_key;
            $buf_arr[$captions[$header_key]] = $captions[$header_key];
        }
    }
    $header = $buf_arr;
    unset($buf_arr);
}
@endphp
<table class="table data-table table-striped table-hover table-responsive-md sortable">
    <thead>
        @foreach ($header as $header_item_key => $header_item_value)
            <th scope="col">{{ $header_item_key }}</th>
        @endforeach
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr data-id="{{ $item->$link_item_key }}">
                @foreach ($item->toArray() as $item_key => $item_value)
                    @if (in_array($item_key, $show_fields))
                        <td data-field="{{ $item_key }}">
                            @if (!empty($link))
                                <a href="{{ $link }}{{ $link_item_key ? $item[$link_item_key] : '' }}">
                            @endif
                            {{ $item_value }}
                            @if ($link)
                                </a>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>

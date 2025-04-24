@extends('googleContact/dashboard')
@section('content')

<table class="min-w-full border-collapse border border-gray-300">
    <thead>
        <tr>
            <th class="border-t border-l border-b border-gray-300 px-4 py-2 text-left bg-gray-100">
                Header 1
            </th>
            <th class="border-t border-b border-gray-300 px-4 py-2 text-left bg-gray-100">
                Header 2
            </th>
            <th class="border-t border-b border-gray-300 px-4 py-2 text-left bg-gray-100">
                Header 3
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="border border-gray-300 px-4 py-2">Row 1, Cell 1</td>
            <td class="border border-gray-300 px-4 py-2">Row 1, Cell 2</td>
            <td class="border border-gray-300 px-4 py-2">Row 1, Cell 3</td>
        </tr>
        <tr>
            <td class="border border-gray-300 px-4 py-2">Row 2, Cell 1</td>
            <td class="border border-gray-300 px-4 py-2">Row 2, Cell 2</td>
            <td class="border border-gray-300 px-4 py-2">Row 2, Cell 3</td>
        </tr>
    </tbody>
</table>
@endsection
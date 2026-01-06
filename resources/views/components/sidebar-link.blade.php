@props(['active'])

@php
$classes = ($active ?? false)
            ? 'group flex items-center px-3 py-2.5 text-sm font-bold bg-brand-800 text-white rounded-lg transition ease-in-out duration-150 shadow-inner border-l-4 border-white'
            : 'group flex items-center px-3 py-2.5 text-sm font-medium text-brand-100/90 rounded-lg hover:text-white hover:bg-brand-800/50 transition ease-in-out duration-150 border-l-4 border-transparent hover:border-brand-400';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

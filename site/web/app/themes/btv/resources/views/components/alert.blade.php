@props([
  'type' => null,
  'message' => null,
])

@php($class = match ($type) {
  'success' => 'text-green-50 bg-green-400',
  'caution' => 'text-center',
  'warning' => 'text-red-50 bg-red-400 text-2xl warning text-center p-4',
  default => 'text-indigo-50 bg-indigo-400',
})

<div {{ $attributes->merge(['class' => "px-2 py-1 {$class}"]) }}>
  {!! $message ?? $slot !!}
</div>

<span @class([
    'py-1',
    'px-3',
    'rounded-2xl',
    'italic',
    'bg-yellow-400' => $status === 'scheduling',
    'bg-gray-200' => $status === 'draft',
    'bg-green-400' => $status === 'submitted',
])>{{ $status }}</span>
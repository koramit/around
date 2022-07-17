<span @class([
    'text-sm md:text-base',
    'py-1 px-2 md:px-3',
    'rounded-2xl',
    'italic',
    'border border-yellow-400 md:border-none md:bg-yellow-400' => $status === 'scheduling',
    'border border-slate-200 md:border-none md:bg-slate-200' => $status === 'draft',
    'border border-green-400 md:border-none md:bg-green-400' => $status === 'submitted',
])>{{ $status }}</span>

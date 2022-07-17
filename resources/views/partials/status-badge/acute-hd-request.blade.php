<span @class([
    'text-sm md:text-base',
    'py-1 px-2 md:px-3',
    'rounded-2xl',
    'italic',
    'bg-white md:bg-yellow-400 text-yellow-400 md:text-white' => $status === 'disapproved',
    'bg-white md:bg-slate-200 text-slate-200 md:text-gray-700' => $status === 'pending',
    'bg-white md:bg-green-400 text-green-400 md:text-white' => $status === 'approved',
])>{{ $status }}</span>

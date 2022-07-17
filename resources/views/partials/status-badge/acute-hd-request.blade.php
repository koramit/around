<span @class([
    'text-sm md:text-base',
    'py-1 px-2 md:px-3',
    'rounded-2xl',
    'italic',
    'border border-yellow-400 md:border-none  md:bg-yellow-400 md:text-white' => $status === 'disapproved',
    'border border-slate-700 md:border-none  md:bg-slate-700 md:text-gray-200' => $status === 'canceled',
    'border border-slate-200 md:border-none  md:bg-slate-200 md:text-gray-700' => $status === 'pending',
    'border border-green-400 md:border-none  md:bg-green-400 md:text-white' => $status === 'approved',
])>{{ $status }}</span>

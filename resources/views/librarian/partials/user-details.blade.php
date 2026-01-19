<!-- User Profile -->
<div class="mb-6 pb-6 border-b border-[#9aa6c7]/10">
    <div class="flex items-start gap-4 mb-4">
        @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->username }}" class="w-16 h-16 rounded-lg object-cover">
        @else
            <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-[#00d4ff] to-[#a855f7] flex items-center justify-center font-bold text-xl text-[#050714]">
                {{ strtoupper(substr($user->username, 0, 1)) }}
            </div>
        @endif
        <div>
            <h3 class="text-2xl font-bold text-white">{{ $user->username }}</h3>
            <p class="text-[#9aa6c7]">{{ $user->email }}</p>
            <div class="flex gap-2 mt-2">
                @if($user->membership)
                    <span class="px-3 py-1 bg-green-900/30 text-green-400 rounded-full text-xs font-semibold">Premium Member</span>
                @else
                    <span class="px-3 py-1 bg-blue-900/30 text-blue-400 rounded-full text-xs font-semibold">Standard Member</span>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- User Statistics -->
<div class="grid grid-cols-3 gap-3 mb-6">
    <div class="bg-white/5 p-3 rounded-lg border border-[#9aa6c7]/10">
        <p class="text-xs text-[#9aa6c7]">Total Borrowed</p>
        <p class="text-2xl font-bold text-white mt-1">{{ $user->borrows()->count() }}</p>
    </div>
    <div class="bg-white/5 p-3 rounded-lg border border-[#9aa6c7]/10">
        <p class="text-xs text-[#9aa6c7]">Active Borrows</p>
        <p class="text-2xl font-bold text-white mt-1">{{ $user->borrows()->whereNull('returned_at')->count() }}</p>
    </div>
    <div class="bg-white/5 p-3 rounded-lg border border-[#9aa6c7]/10">
        <p class="text-xs text-[#9aa6c7]">Member Since</p>
        <p class="text-lg font-bold text-white mt-1">{{ $user->created_at->format('M Y') }}</p>
    </div>
</div>

<!-- All Borrowing History -->
<div>
    <h4 class="text-lg font-bold text-white mb-3">Complete Borrowing History</h4>
    
    @if($user->borrows()->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#9aa6c7]/10">
                        <th class="px-3 py-2 font-semibold text-[#9aa6c7]">Book Title</th>
                        <th class="px-3 py-2 font-semibold text-[#9aa6c7]">Author</th>
                        <th class="px-3 py-2 font-semibold text-[#9aa6c7]">Borrowed</th>
                        <th class="px-3 py-2 font-semibold text-[#9aa6c7]">Returned</th>
                        <th class="px-3 py-2 font-semibold text-[#9aa6c7]">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->borrows()->latest()->get() as $borrow)
                        <tr class="border-b border-[#9aa6c7]/5 hover:bg-white/5 transition">
                            <td class="px-3 py-2 text-[#00d4ff]">{{ $borrow->book->title }}</td>
                            <td class="px-3 py-2 text-[#9aa6c7]">{{ $borrow->book->author }}</td>
                            <td class="px-3 py-2 text-[#9aa6c7]">{{ $borrow->created_at->format('M d, Y') }}</td>
                            <td class="px-3 py-2 text-[#9aa6c7]">
                                @if($borrow->returned_at)
                                    {{ $borrow->returned_at->format('M d, Y') }}
                                @else
                                    <span class="text-[#a855f7]">—</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                @if($borrow->returned_at)
                                    <span class="inline-block px-2 py-1 bg-green-900/30 text-green-400 rounded text-xs font-semibold">Returned</span>
                                @else
                                    <span class="inline-block px-2 py-1 bg-blue-900/30 text-blue-400 rounded text-xs font-semibold">Active</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-[#9aa6c7] text-center py-4">No borrowing history</p>
    @endif
</div>

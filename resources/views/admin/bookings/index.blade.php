{{-- resources/views/admin/bookings/index.blade.php --}}
@extends('layouts.admin') {{-- Ensure this layout exists and is correctly named --}}

@section('title', 'Manage Bookings')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manage Bookings</h1>
        {{-- Optional: Link to create a new booking if admin has this functionality --}}
        {{-- <a href="{{ route('admin.bookings.create') }}" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">Create Booking</a> --}}
    </div>

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif
    @if(session('info'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4 rounded-md shadow" role="alert">
            <p>{{ session('info') }}</p>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.bookings.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search_term" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" name="search_term" id="search_term" value="{{ request('search_term') }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Booking #, User, Car">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Booking Status</label>
                    <select name="status" id="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All</option>
                        @foreach($bookingStatuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                    <select name="payment_status" id="payment_status" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All</option>
                         @foreach($paymentStatuses as $pStatus)
                            <option value="{{ $pStatus }}" {{ request('payment_status') == $pStatus ? 'selected' : '' }}>
                                {{ ucfirst($pStatus) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit"
                            class="flex-grow inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Filter
                    </button>
                     @if(request()->has('search_term') || request()->has('status') || request()->has('payment_status'))
                        <a href="{{ route('admin.bookings.index') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>


    <div class="bg-white shadow-lg rounded-xl overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking #</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($bookings as $index => $booking)
                    <tr>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $bookings->firstItem() + $index }} {{-- Sequential number considering pagination --}}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{-- Make Booking # clickable to a detail page (if you create one) --}}
                            <a href="" class="text-indigo-600 hover:text-indigo-800 hover:underline" title="View Booking Details">
                                {{ $booking->booking_number }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $booking->user->name ?? 'N/A' }} <br>
                            <span class="text-xs text-gray-500">{{ $booking->user->email ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex items-center">
                                @if($booking->car && $booking->car->featuredImage)
                                    <img src="{{ Storage::url($booking->car->featuredImage->path) }}" alt="{{ $booking->car->make }} {{ $booking->car->model }}" class="w-16 h-10 object-cover rounded-md mr-3 shadow">
                                @elseif($booking->car && $booking->car->images->isNotEmpty())
                                     <img src="{{ Storage::url($booking->car->images->first()->path) }}" alt="{{ $booking->car->make }} {{ $booking->car->model }}" class="w-16 h-10 object-cover rounded-md mr-3 shadow">
                                @else
                                    <div class="w-16 h-10 bg-gray-200 rounded-md mr-3 flex items-center justify-center text-gray-400 shadow">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div>
                                    {{ $booking->car->make ?? 'N/A' }} {{ $booking->car->model ?? '' }}
                                    <span class="block text-xs text-gray-500">({{ $booking->car->year ?? ''}})</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $booking->start_date->format('M d, Y') }}<br>
                            <span class="text-xs text-gray-500">to {{ $booking->end_date->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-semibold">${{ number_format($booking->total_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ route('admin.bookings.updateStatus', array_merge(['booking' => $booking->id], request()->query())) }}" method="POST" class="inline-flex items-center">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="text-sm p-1 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500
                                    @if($booking->status == 'pending') bg-yellow-100 text-yellow-800 ring-1 ring-yellow-300
                                    @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800 ring-1 ring-blue-300
                                    @elseif($booking->status == 'active') bg-green-100 text-green-800 ring-1 ring-green-300
                                    @elseif($booking->status == 'completed') bg-gray-200 text-gray-800 ring-1 ring-gray-400
                                    @elseif($booking->status == 'cancelled') bg-red-100 text-red-800 ring-1 ring-red-300
                                    @endif"
                                    onchange="this.form.submit()"
                                    @if($booking->status === 'completed' || $booking->status === 'cancelled') disabled @endif {{-- Disable if completed/cancelled --}}
                                    >
                                    @foreach($bookingStatuses as $status)
                                        <option value="{{ $status }}" {{ $booking->status == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                <noscript><button type="submit" class="ml-1 text-xs p-1 bg-gray-200 rounded">Save</button></noscript>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                             <form action="{{ route('admin.bookings.updateStatus', array_merge(['booking' => $booking->id], request()->query())) }}" method="POST" class="inline-flex items-center">
                                @csrf
                                @method('PATCH')
                                <select name="payment_status" class="text-sm p-1 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500
                                    @if($booking->payment_status == 'pending') bg-yellow-100 text-yellow-800 ring-1 ring-yellow-300
                                    @elseif($booking->payment_status == 'paid') bg-green-100 text-green-800 ring-1 ring-green-300
                                    @elseif($booking->payment_status == 'failed') bg-red-100 text-red-800 ring-1 ring-red-300
                                    @elseif($booking->payment_status == 'refunded') bg-purple-100 text-purple-800 ring-1 ring-purple-300
                                    @endif"
                                    onchange="this.form.submit()"
                                     @if($booking->status === 'cancelled' || $booking->payment_status === 'refunded') disabled @endif {{-- Disable if booking cancelled or payment refunded --}}
                                    >
                                     @foreach($paymentStatuses as $pStatus)
                                        <option value="{{ $pStatus }}" {{ $booking->payment_status == $pStatus ? 'selected' : '' }}>
                                            {{ ucfirst($pStatus) }}
                                        </option>
                                    @endforeach
                                </select>
                                 <noscript><button type="submit" class="ml-1 text-xs p-1 bg-gray-200 rounded">Save</button></noscript>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            {{-- View button already handled by clickable Booking # --}}
                            @if($booking->status !== 'cancelled' && $booking->status !== 'completed')
                                <button type="button" onclick="openCancelModal({{ $booking->id }}, '{{ $booking->booking_number }}')"
                                        class="text-red-600 hover:text-red-800" title="Cancel Booking">
                                    Cancel
                                </button>
                            @else
                                <span class="text-gray-400 italic text-xs">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-sm text-gray-500">
                            No bookings found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($bookings->hasPages())
        <div class="mt-8">
            {{ $bookings->links() }} {{-- Renders Tailwind pagination links by default in L9+ --}}
        </div>
    @endif
</div>

<!-- Cancel Booking Modal (remains the same) -->
<div id="cancelBookingModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- ... Modal content ... (no changes needed here from previous version) --}}
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="cancelBookingForm" method="POST"> {{-- Action will be set by JS --}}
                @csrf
                @method('PATCH')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Cancel Booking <span id="modalBookingNumber" class="font-bold"></span>
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to cancel this booking? This action cannot be undone.
                                    Please provide a reason for cancellation (optional).
                                </p>
                                <label for="cancellation_reason_admin" class="block text-sm font-medium text-gray-700 mt-3">Cancellation Reason (Admin)</label>
                                <textarea name="cancellation_reason_admin" id="cancellation_reason_admin" rows="3"
                                          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirm Cancel
                    </button>
                    <button type="button" onclick="closeCancelModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Back
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const cancelBookingModal = document.getElementById('cancelBookingModal');
    const cancelBookingForm = document.getElementById('cancelBookingForm');
    const modalBookingNumberSpan = document.getElementById('modalBookingNumber');
    const cancellationReasonTextarea = document.getElementById('cancellation_reason_admin');

    function openCancelModal(bookingId, bookingNumber) {
        let actionUrl = "{{ url('admin/bookings') }}/" + bookingId + "/cancel";
        const currentQueryParams = window.location.search;
        if (currentQueryParams) {
            actionUrl += currentQueryParams;
        }
        cancelBookingForm.action = actionUrl;
        modalBookingNumberSpan.textContent = '#' + bookingNumber;
        cancellationReasonTextarea.value = '';
        cancelBookingModal.classList.remove('hidden');
    }

    function closeCancelModal() {
        cancelBookingModal.classList.add('hidden');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape" && !cancelBookingModal.classList.contains('hidden')) {
            closeCancelModal();
        }
    });
</script>
@endpush
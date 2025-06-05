{{-- resources/views/components/booking-details.blade.php --}}
@props([
    'booking',
    'bookingStatuses' => null, // Nullable for non-admin views
    'paymentStatuses' => null, // Nullable for non-admin views
    'indexQueryFilters' => [], // For admin views to preserve filters on form submission
    'viewCarUrl' => null,      // Optional URL for the "View Full Car Details" link
])

<div> {{-- Add a root div for the component itself --}}
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

    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="md:flex">
            <!-- Left Panel: Booking Info & Status Updates -->
            <div class="md:w-2/3 p-6 border-b md:border-b-0 md:border-r border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Booking Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <span class="font-medium text-gray-600">Booking Number:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->booking_number }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Booked On:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Start Date:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->start_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">End Date:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->end_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Duration:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->total_days ?? ($booking->start_date && $booking->end_date ? $booking->start_date->diffInDays($booking->end_date) + 1 : 'N/A') }} day(s)</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Total Amount:</span>
                        <span class="text-gray-800 ml-2 font-semibold">${{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                     @if($booking->confirmed_at)
                    <div>
                        <span class="font-medium text-gray-600">Confirmed At:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->confirmed_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                    @if($booking->pickup_at)
                    <div>
                        <span class="font-medium text-gray-600">Picked Up At:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->pickup_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                    @if($booking->returned_at)
                    <div>
                        <span class="font-medium text-gray-600">Returned At:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->returned_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                    @if($booking->cancelled_at)
                    <div>
                        <span class="font-medium text-gray-600">Cancelled At:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->cancelled_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-600">Cancellation Reason:</span>
                        <span class="text-gray-800 ml-2">{{ $booking->cancellation_reason ?: 'Not provided' }}</span>
                    </div>
                    @endif
                </div>

                {{-- Admin specific status update forms --}}
                @auth
                    @if (Auth::user()->hasRole('admin')) {{-- Or your preferred admin check --}}
                        <hr class="my-6">
                        {{-- Booking Status Update --}}
                        @if(isset($bookingStatuses) && is_array($bookingStatuses))
                        <form action="{{ route('admin.bookings.updateStatus', array_merge(['booking' => $booking->id], $indexQueryFilters)) }}" method="POST" class="mb-6">
                            @csrf
                            @method('PATCH')
                            <label for="status-{{ $booking->id }}" class="block text-sm font-medium text-gray-700 mb-1">Booking Status:</label>
                            <div class="flex items-center space-x-3">
                                <select name="status" id="status-{{ $booking->id }}"
                                    @class([
                                        'mt-1 block w-full sm:w-auto px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                        'bg-yellow-50 text-yellow-800 ring-1 ring-yellow-300' => $booking->status == 'pending',
                                        'bg-blue-50 text-blue-800 ring-1 ring-blue-300' => $booking->status == 'confirmed',
                                        'bg-green-50 text-green-800 ring-1 ring-green-300' => $booking->status == 'active',
                                        'bg-gray-100 text-gray-800 ring-1 ring-gray-400' => $booking->status == 'completed',
                                        'bg-red-50 text-red-800 ring-1 ring-red-300' => $booking->status == 'cancelled',
                                    ])
                                    @if($booking->status === 'completed' || $booking->status === 'cancelled') disabled @endif
                                >
                                    @foreach($bookingStatuses as $statusKey => $statusValue)
                                        <option value="{{ $statusKey }}" {{ $booking->status == $statusKey ? 'selected' : '' }}>
                                            {{ $statusValue }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!($booking->status === 'completed' || $booking->status === 'cancelled'))
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Update Booking Status
                                </button>
                                @else
                                <span class="px-4 py-2 bg-gray-300 text-gray-600 text-sm font-medium rounded-md cursor-not-allowed">Update Booking Status</span>
                                @endif
                            </div>
                        </form>
                        @endif

                        {{-- Payment Status Update --}}
                        @if(isset($paymentStatuses) && is_array($paymentStatuses))
                        <form action="{{ route('admin.bookings.updateStatus', array_merge(['booking' => $booking->id], $indexQueryFilters)) }}" method="POST"> {{-- Assuming same route handles payment_status too --}}
                            @csrf
                            @method('PATCH')
                            <label for="payment_status-{{ $booking->id }}" class="block text-sm font-medium text-gray-700 mb-1">Payment Status:</label>
                             <div class="flex items-center space-x-3">
                                <select name="payment_status" id="payment_status-{{ $booking->id }}"
                                    @class([
                                        'mt-1 block w-full sm:w-auto px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
                                        'bg-yellow-50 text-yellow-800 ring-1 ring-yellow-300' => $booking->payment_status == 'pending',
                                        'bg-green-50 text-green-800 ring-1 ring-green-300' => $booking->payment_status == 'paid',
                                        'bg-red-50 text-red-800 ring-1 ring-red-300' => $booking->payment_status == 'failed',
                                        'bg-purple-50 text-purple-800 ring-1 ring-purple-300' => $booking->payment_status == 'refunded',
                                    ])
                                     @if($booking->status === 'cancelled' || $booking->payment_status === 'refunded') disabled @endif
                                >
                                    @foreach($paymentStatuses as $pStatusKey => $pStatusValue)
                                        <option value="{{ $pStatusKey }}" {{ $booking->payment_status == $pStatusKey ? 'selected' : '' }}>
                                            {{ $pStatusValue }}
                                        </option>
                                    @endforeach
                                </select>
                                 @if(!($booking->status === 'cancelled' || $booking->payment_status === 'refunded'))
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Update Payment Status
                                </button>
                                @else
                                <span class="px-4 py-2 bg-gray-300 text-gray-600 text-sm font-medium rounded-md cursor-not-allowed">Update Payment Status</span>
                                @endif
                            </div>
                        </form>
                        @endif
                    @endif
                @endauth
            </div>

            <!-- Right Panel: User & Car Details -->
            <div class="md:w-1/3 p-6">
                @if($booking->user)
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">User Details</h3>
                    <p class="text-sm text-gray-600"><span class="font-medium">Name:</span> {{ $booking->user->name }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Email:</span> {{ $booking->user->email }}</p>
                    {{-- Add link to user profile for admin if needed --}}
                </div>
                @endif

                @if($booking->car)
                <div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">Car Details</h3>
                    @if($booking->car->featuredImage)
                        <img src="{{ Storage::url($booking->car->featuredImage->path) }}" alt="{{ $booking->car->make }} {{ $booking->car->model }}" class="w-full h-48 object-cover rounded-lg mb-3 shadow">
                    @elseif($booking->car->images->isNotEmpty())
                        <img src="{{ Storage::url($booking->car->images->first()->path) }}" alt="{{ $booking->car->make }} {{ $booking->car->model }}" class="w-full h-48 object-cover rounded-lg mb-3 shadow">
                    @else
                        <div class="w-full h-48 bg-gray-200 rounded-lg mb-3 flex items-center justify-center text-gray-400 shadow">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <p class="text-lg font-medium text-gray-800">{{ $booking->car->make }} {{ $booking->car->model }}</p>
                    <p class="text-sm text-gray-600">{{ $booking->car->year }} • {{ ucfirst($booking->car->color) }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">License Plate:</span> {{ $booking->car->license_plate }}</p>

                    @php
                        $carRoute = $viewCarUrl; // Use the passed URL if available
                        if (!$carRoute) {
                            // Fallback to role-based routing if $viewCarUrl is not provided
                            if (Auth::check() && Auth::user()->hasRole('admin')) {
                                $carRoute = route('admin.cars.show', $booking->car);
                            } else {
                                $carRoute = route('cars.show', $booking->car); // Assuming a public car show route
                            }
                        }
                    @endphp
                    <a href="{{ $carRoute }}"
                       class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-800 hover:underline">
                        View Full Car Details
                    </a>
                </div>
                @endif
            </div>
        </div>

        {{-- Action Bar for Cancel Button (Admin or User if allowed) --}}
        @if($booking->status !== 'cancelled' && $booking->status !== 'completed')
            @auth
                @if (Auth::user()->hasRole('admin') || (Auth::id() == $booking->user_id && $booking->canBeCancelledByUser()))
                    <div class="p-6 border-t border-gray-200 bg-gray-50 text-right">
                         <button type="button" onclick="openShowPageCancelModal_{{ $booking->id }}()"
                                class="px-6 py-2 bg-red-600 text-white font-semibold rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Cancel This Booking
                        </button>
                    </div>
                @endif
            @endauth
        @endif
    </div>
</div>


<!-- Cancel Booking Modal (for Show Page) -->
@if($booking->status !== 'cancelled' && $booking->status !== 'completed')
    @auth
        @if (Auth::user()->hasRole('admin') || (Auth::id() == $booking->user_id && $booking->canBeCancelledByUser()))
            <div id="showPageCancelBookingModal-{{ $booking->id }}" class="fixed z-20 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title-show-{{ $booking->id }}" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form id="showPageCancelBookingForm-{{ $booking->id }}"
                              action="{{ Auth::user()->hasRole('admin') ? route('admin.bookings.cancel', array_merge(['booking' => $booking->id], $indexQueryFilters)) : route('bookings.cancel', $booking->id) }}"
                              method="POST">
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
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-show-{{ $booking->id }}">
                                            Cancel Booking #{{ $booking->booking_number }}
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Are you sure you want to cancel this booking? This action cannot be undone.
                                                @if(Auth::user()->hasRole('admin'))
                                                    Please provide a reason for cancellation (optional for admin).
                                                @else
                                                    Please provide a reason for cancellation.
                                                @endif
                                            </p>
                                            <label for="show_page_cancellation_reason-{{ $booking->id }}" class="block text-sm font-medium text-gray-700 mt-3">Cancellation Reason</label>
                                            <textarea name="{{ Auth::user()->hasRole('admin') ? 'cancellation_reason_admin' : 'cancellation_reason_user' }}" id="show_page_cancellation_reason-{{ $booking->id }}" rows="3"
                                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                      @if(!Auth::user()->hasRole('admin')) required @endif></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Confirm Cancel
                                </button>
                                <button type="button" onclick="closeShowPageCancelModal_{{ $booking->id }}()"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Back
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                if (typeof openShowPageCancelModal_{{ $booking->id }} !== 'function') {
                    function openShowPageCancelModal_{{ $booking->id }}() {
                        const modalElement = document.getElementById('showPageCancelBookingModal-{{ $booking->id }}');
                        if (modalElement) {
                            const reasonTextarea = document.getElementById('show_page_cancellation_reason-{{ $booking->id }}');
                            if (reasonTextarea) reasonTextarea.value = '';
                            modalElement.classList.remove('hidden');
                        }
                    }
                }

                if (typeof closeShowPageCancelModal_{{ $booking->id }} !== 'function') {
                    function closeShowPageCancelModal_{{ $booking->id }}() {
                        const modalElement = document.getElementById('showPageCancelBookingModal-{{ $booking->id }}');
                        if (modalElement) {
                            modalElement.classList.add('hidden');
                        }
                    }
                }

                document.addEventListener('keydown', function (event) {
                    if (event.key === "Escape") {
                        const modalElement = document.getElementById('showPageCancelBookingModal-{{ $booking->id }}');
                        if (modalElement && !modalElement.classList.contains('hidden')) {
                            closeShowPageCancelModal_{{ $booking->id }}();
                        }
                    }
                });
            </script>
        @endif
    @endauth
@endif
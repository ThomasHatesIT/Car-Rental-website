{{-- resources/views/gmails/booked-notif.blade.php --}}

{{-- This part is for the admin, but using the customer-facing template as requested --}}
<p>Dear Admin (Notification for Booking by: {{ $booking->user?->name ?? ($booking->user?->email ?? 'N/A') }}),</p>

<p>A new booking has been made with the following details:</p>

{{-- This is the section you asked to fill --}}
<p><strong>Booking Details:</strong></p>
<ul>
    <li><strong>Booking Number:</strong> {{ $booking->booking_number }}</li>
    @if($booking->car)
        <li><strong>Car:</strong> {{ $booking->car->make ?? 'N/A' }} {{ $booking->car->model ?? 'N/A' }} ({{ $booking->car->year ?? 'N/A' }})</li>
    @else
        <li><strong>Car:</strong> Information Unavailable</li>
    @endif
    <li><strong>Booked By User ID:</strong> {{ $booking->user_id }}</li>
    <li><strong>User Name:</strong> {{ $booking->user?->name ?? 'N/A' }}</li>
    <li><strong>User Email:</strong> {{ $booking->user?->email ?? 'N/A' }}</li>
    <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($booking->start_date)->format('F j, Y') }} at {{ $booking->pickup_time?->format('H:i A') ?? $booking->pickup_time }}</li>
    <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($booking->end_date)->format('F j, Y') }} at {{ $booking->return_time?->format('H:i A') ?? $booking->return_time }}</li>
    <li><strong>Pickup Location:</strong> {{ $booking->pickup_location }}</li>
    <li><strong>Return Location:</strong> {{ $booking->return_location }}</li>
    <li><strong>Total Days:</strong> {{ $booking->total_days }}</li>
    <li><strong>Daily Rate:</strong> ${{ number_format($booking->daily_rate, 2) }}</li>
    <li><strong>Subtotal:</strong> ${{ number_format($booking->subtotal, 2) }}</li>
    @if($booking->tax_amount > 0)
    <li><strong>Tax Amount:</strong> ${{ number_format($booking->tax_amount, 2) }}</li>
    @endif
    @if($booking->discount_amount > 0)
    <li><strong>Discount Amount:</strong> ${{ number_format($booking->discount_amount, 2) }}</li>
    @endif
    <li><strong>Total Amount:</strong> ${{ number_format($booking->total_amount, 2) }}</li>
    @if($booking->notes)
    <li><strong>Notes:</strong> {{ $booking->notes }}</li>
    @endif
</ul>

<p>
    {{-- Since this is an admin notification, you might link to the admin panel booking view --}}
    @if(Route::has('admin.bookings.show'))
        You can view this booking in the admin panel:
        <a href="{{ route('admin.bookings.show', $booking->id) }}">View Booking in Admin</a>
    @else
        The booking ID is {{ $booking->id }}.
    @endif
</p>
{{-- End of the filled section --}}

<p>This is an automated notification.</p>

<p>
    Regards,<br>
    {{ config('app.name') }}
</p>
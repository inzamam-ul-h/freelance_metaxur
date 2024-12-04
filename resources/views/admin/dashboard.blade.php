@extends('admin.layouts.app')
@if (auth()->user()->role == 'admin')
@section('content-admin')
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif


<div class="container">
    <h2 class="text-center mb-4">Create Availability</h2>


    <form id="availabilityForm" action="{{ route('availability.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="date" class="form-label">Select Date</label>
                <input type="date" name="availability_date" id="availability_date" class="form-control"
                    value="{{ old('availability_date') ?? date('Y-m-d') }}" required>
                @error('availability_date')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="startTime" class="form-label">Select Start Time</label>
                <input type="time" class="form-control" name="availability_start_time" id="availability_start_time"
                    value="{{ old('availability_start_time') }}" required>
                @error('availability_start_time')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="endTime" class="form-label">Select End Time</label>
                <input type="time" class="form-control" name="availability_end_time" id="availability_end_time"
                    value="{{ old('availability_end_time') }}" required>
                @error('availability_end_time')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Recurrent Button -->
        <button type="button" class="btn btn-warning" id="recurrentButton"
            style="margin-bottom: 20px">Recurrent</button>

        <!-- Hidden field to track recurrence selection -->
        <input type="hidden" id="isRecurrent" name="isRecurrent" value="0">

        <!-- Recurrence Section, hidden by default -->
        <div id="recurrenceSection" style="display: none;">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="recurrence" class="form-label">Recurrence Duration</label>
                    <select name="recurrence" id="recurrence" class="form-select">
                        <option value="" disabled selected>Select Duration</option>
                        <option value="1_week">1 Week</option>
                        <option value="2_weeks">2 Weeks</option>
                        <option value="1_month">1 Month</option>
                        <option value="2_months">2 Months</option>
                    </select>
                    @error('recurrence')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>


                <!-- Days Checkbox Row -->
                <div class="col-md-8">
                    <label class="form-label">Exclude Days (if any)</label>
                    <div class="row">
                        <div class="col"><input type="checkbox" id="sunday" name="excluded_days[]" value="sunday">
                            <label for="sunday">Sunday</label>
                        </div>
                        <div class="col"><input type="checkbox" id="monday" name="excluded_days[]" value="monday">
                            <label for="monday">Monday</label>
                        </div>
                        <div class="col"><input type="checkbox" id="tuesday" name="excluded_days[]" value="tuesday">
                            <label for="tuesday">Tuesday</label>
                        </div>
                        <div class="col"><input type="checkbox" id="wednesday" name="excluded_days[]" value="wednesday">
                            <label for="wednesday">Wednesday</label>
                        </div>
                        <div class="col"><input type="checkbox" id="thursday" name="excluded_days[]" value="thursday">
                            <label for="thursday">Thursday</label>
                        </div>
                        <div class="col"><input type="checkbox" id="friday" name="excluded_days[]" value="friday">
                            <label for="friday">Friday</label>
                        </div>
                        <div class="col"><input type="checkbox" id="saturday" name="excluded_days[]" value="saturday">
                            <label for="saturday">Saturday</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-bottom: 20px">Create Availability</button>
    </form>


    <!-- Booker Details Modal -->
    <div id="booker-details-modal" class="modal fade" tabindex="-1" aria-labelledby="bookerDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookerDetailsModalLabel">Booker Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="booker-name">N/A</span></p>
                    <p><strong>Email:</strong> <span id="booker-email">N/A</span></p>
                    <p><strong>Contact:</strong> <span id="booker-contact">N/A</span></p>
                    <p><strong>Address:</strong> <span id="booker-address">N/A</span></p>
                    <p><strong>Message:</strong> <span id="booker-message">N/A</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="calendar" style="background-color: lightgray;"></div>

</div>
<!-- Modal for Displaying Booker Details -->
<div id="booker-details-modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booker Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="booker-name"></span></p>
                <p><strong>Email:</strong> <span id="booker-email"></span></p>
                <p><strong>Contact:</strong> <span id="booker-contact"></span></p>
                <p><strong>Address:</strong> <span id="booker-address"></span></p>
                <p><strong>Message:</strong> <span id="booker-message"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
{{-- <script>
    $(document).ready(function () {
        // Initialize DataTable
        const table = $('#availabilityTable').DataTable();

        $('#availabilityForm').on('submit', function (e) {
            e.preventDefault();

            // Get values from the form
            const availability_date = $('#availability_date').val();
            const availability_start_time = $('#availability_start_time').val();
            const availability_end_time = $('#availability_end_time').val();

            const today = new Date();
            const selectedDate = new Date(availability_date);
            const currentTime = today.toTimeString().split(' ')[0]; // Current time in "HH:MM:SS" format

            // Ensure date is in the future
            if (selectedDate <= today.setHours(0, 0, 0, 0)) {
                alert('The selected date must be later than today.');
                return;
            }

            // If the selected date is today, check if start time is greater than current time
            if (selectedDate.toDateString() === today.toDateString() && availability_start_time <= currentTime) {
                alert('Start time must be greater than the current time for today\'s date.');
                return;
            }

            // Ensure end time is greater than start time
            if (availability_end_time <= availability_start_time) {
                alert('End time must be greater than start time.');
                return;
            }

            // Calculate the time difference
            const startTime = new Date(`1970-01-01T${availability_start_time}`);
            const endTime = new Date(`1970-01-01T${availability_end_time}`);
            const timeDifference = endTime - startTime;

            // Ensure the minimum duration is 10 minutes (600,000 milliseconds)
            if (timeDifference < 10 * 60 * 1000) {
                alert('The duration between start time and end time must be at least 10 minutes.');
                return;
            }

            // Ensure the maximum duration is 12 hours
            const maxDuration = 12 * 60 * 60 * 1000; // 12 hours in milliseconds
            if (timeDifference > maxDuration) {
                alert('The maximum duration between start time and end time is 12 hours.');
                return;
            }

            // If all validations pass, submit the form
            this.submit();
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    // Event listener for the "View Booker" button
    document.querySelectorAll('.view-booker-details').forEach(button => {
        button.addEventListener('click', function () {
            // Get data attributes
            const bookerName = button.getAttribute('data-booker-name');
            const bookerEmail = button.getAttribute('data-booker-email');
            const bookerContact = button.getAttribute('data-booker-contact');
            const bookerAddress = button.getAttribute('data-booker-address');
            const bookerMessage = button.getAttribute('data-booker-message');

            // Set the values in the modal
            document.getElementById('booker-name').textContent = bookerName;
            document.getElementById('booker-email').textContent = bookerEmail;
            document.getElementById('booker-contact').textContent = bookerContact;
            document.getElementById('booker-address').textContent = bookerAddress;
            document.getElementById('booker-message').textContent = bookerMessage;
        });
    });
});


 // Toggle recurrence section and set isRecurrent value
 document.getElementById('recurrentButton').addEventListener('click', function() {
        const recurrenceSection = document.getElementById('recurrenceSection');
        const isRecurrent = document.getElementById('isRecurrent');
        if (recurrenceSection.style.display === 'none') {
            recurrenceSection.style.display = 'block';
            isRecurrent.value = '1';  // Mark as recurrent
        } else {
            recurrenceSection.style.display = 'none';
            isRecurrent.value = '0';  // Reset to non-recurrent
        }
    });

</script> --}}


<script>
    $(document).ready(function() {
                // Initialize DataTable
                const table = $('#availabilityTable').DataTable();

                $('#availabilityForm').on('submit', function(e) {
                    e.preventDefault();

                    // Get values from the form
                    const availability_date = $('#availability_date').val();
                    const availability_start_time = $('#availability_start_time').val();
                    const availability_end_time = $('#availability_end_time').val();

                    const today = new Date();
                    const selectedDate = new Date(availability_date);
                    const currentTime = today.toTimeString().split(' ')[0]; // Current time in "HH:MM:SS" format

                    // Ensure date is in the future
                    if (selectedDate <= today.setHours(0, 0, 0, 0)) {
                        alert('The selected date must be later than today.');
                        return;
                    }

                    // If the selected date is today, check if start time is greater than current time
                    if (selectedDate.toDateString() === today.toDateString() && availability_start_time <=
                        currentTime) {
                        alert('Start time must be greater than the current time for today\'s date.');
                        return;
                    }

                    // Ensure end time is greater than start time
                    if (availability_end_time <= availability_start_time) {
                        alert('End time must be greater than start time.');
                        return;
                    }

                    // Calculate the time difference
                    const startTime = new Date(`1970-01-01T${availability_start_time}`);
                    const endTime = new Date(`1970-01-01T${availability_end_time}`);
                    const timeDifference = endTime - startTime;



                    // Ensure the minimum duration is 30 minutes (600,000 milliseconds)
                    if (timeDifference < 30 * 60 * 1000) {
                        alert('The duration between start time and end time must be at least 30 minutes.');
                        return;
                    }

                    // Ensure the maximum duration is 12 hours
                    const maxDuration = 12 * 60 * 60 * 1000; // 12 hours in milliseconds
                    if (timeDifference > maxDuration) {
                        alert('The maximum duration between start time and end time is 12 hours.');
                        return;
                    }

                    // If all validations pass, submit the form
                    this.submit();
                });
            });


            //display expired slots in this code

            // document.addEventListener('DOMContentLoaded', function() {
            //     const calendarEl = document.getElementById('calendar');
            //     const calendar = new FullCalendar.Calendar(calendarEl, {
            //         initialView: 'dayGridMonth',
            //         headerToolbar: {
            //             left: 'prev,next today',
            //             center: 'title',
            //             right: 'dayGridMonth,timeGridWeek,timeGridDay'
            //         },
            //         events: [
            //             @foreach ($slots as $slot)
            //                 {
            //                     id: '{{ $slot->id }}',
            //                     title: (() => {
            //                         const startDateTime = new Date(
            //                             '{{ $slot->availability->availability_date . 'T' . $slot->slot_start_time }}'
            //                             );
            //                         return (startDateTime < new Date()) ? 'Expired' : (
            //                             '{{ $slot->slot_status == 1 ? 'Available' : 'Booked' }}'
            //                             );
            //                     })(),
            //                     start: '{{ $slot->availability->availability_date . 'T' . $slot->slot_start_time }}',
            //                     end: '{{ $slot->availability->availability_date . 'T' . $slot->slot_end_time }}',
            //                     backgroundColor: (() => {
            //                         const startDateTime = new Date(
            //                             '{{ $slot->availability->availability_date . 'T' . $slot->slot_start_time }}'
            //                             );
            //                         return (startDateTime < new Date()) ? "#e2e3e5" : (
            //                             '{{ $slot->slot_status == 1 ? '#d4edda' : '#f8d7da' }}'
            //                             );
            //                     })(),
            //                     borderColor: (() => {
            //                         const startDateTime = new Date(
            //                             '{{ $slot->availability->availability_date . 'T' . $slot->slot_start_time }}'
            //                             );
            //                         return (startDateTime < new Date()) ? "#6c757d" : (
            //                             '{{ $slot->slot_status == 1 ? '#28a745' : '#dc3545' }}'
            //                             );
            //                     })(),
            //                     textColor: (() => {
            //                         const startDateTime = new Date(
            //                             '{{ $slot->availability->availability_date . 'T' . $slot->slot_start_time }}'
            //                             );
            //                         return (startDateTime < new Date()) ? "#ffffff" : (
            //                             '{{ $slot->slot_status == 1 ? '#155724' : '#721c24' }}'
            //                             );
            //                     })(),
            //                     extendedProps: {
            //                         bookerName: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->name : '' }}',
            //                         bookerEmail: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->email : '' }}',
            //                         bookerContact: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->contact_number : '' }}',
            //                         bookerAddress: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->address : '' }}',
            //                         bookerMessage: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->message : '' }}'
            //                     }
            //                 },
            //             @endforeach
            //         ],
            //         eventClick: function(info) {
            //             const props = info.event.extendedProps;

            //             // If slot is booked, display details in the modal
            //             if (props.bookerName) {
            //                 document.getElementById('booker-name').textContent = props.bookerName;
            //                 document.getElementById('booker-email').textContent = props.bookerEmail;
            //                 document.getElementById('booker-contact').textContent = props.bookerContact;
            //                 document.getElementById('booker-address').textContent = props.bookerAddress;
            //                 document.getElementById('booker-message').textContent = props.bookerMessage;

            //                 // Show the modal
            //                 const modal = new bootstrap.Modal(document.getElementById(
            //                     'booker-details-modal'));
            //                 modal.show();
            //             } else {
            //                 alert('This slot is available for booking.');
            //             }
            //         }
            //     });

            //     calendar.render();
            // });

    //not display expired slots in this code
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            @foreach ($slots as $slot)
                (function() {
                    const startDateTime = new Date(
                        '{{ $slot->availability->availability_date . 'T' . $slot->slot_start_time }}'
                    );

                    // If the slot is expired, skip it (don't display on the calendar)
                    if (startDateTime < new Date()) return null;

                    return {
                        id: '{{ $slot->id }}',
                        title: '{{ $slot->slot_status == 1 ? 'Available' : 'Booked' }}',
                        start: '{{ $slot->availability->availability_date . 'T' . $slot->slot_start_time }}',
                        end: '{{ $slot->availability->availability_date . 'T' . $slot->slot_end_time }}',
                        backgroundColor: '{{ $slot->slot_status == 1 ? '#d4edda' : '#f8d7da' }}',
                        borderColor: '{{ $slot->slot_status == 1 ? '#28a745' : '#dc3545' }}',
                        textColor: '{{ $slot->slot_status == 1 ? '#155724' : '#721c24' }}',
                        extendedProps: {
                            bookerName: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->name : '' }}',
                            bookerEmail: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->email : '' }}',
                            bookerContact: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->contact_number : '' }}',
                            bookerAddress: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->address : '' }}',
                            bookerMessage: '{{ $slot->booking && $slot->booking->user ? $slot->booking->user->message : '' }}'
                        }
                    };
                })(),
            @endforeach
        ].filter(Boolean), // Filter out any null (expired) slots

        eventClick: function (info) {
            const props = info.event.extendedProps;

            // If slot is booked, display details in the modal
            if (props.bookerName) {
                document.getElementById('booker-name').textContent = props.bookerName;
                document.getElementById('booker-email').textContent = props.bookerEmail;
                document.getElementById('booker-contact').textContent = props.bookerContact;
                document.getElementById('booker-address').textContent = props.bookerAddress;
                document.getElementById('booker-message').textContent = props.bookerMessage;

                // Show the modal
                const modal = new bootstrap.Modal(document.getElementById('booker-details-modal'));
                modal.show();
            } else {
                alert('This slot is available for booking.');
            }
        }
    });

    calendar.render();
});


document.getElementById('recurrentButton').addEventListener('click', function() {
                const recurrenceSection = document.getElementById('recurrenceSection');
                const isRecurrent = document.getElementById('isRecurrent');
                if (recurrenceSection.style.display === 'none') {
                    recurrenceSection.style.display = 'block';
                    isRecurrent.value = '1'; // Mark as recurrent
                } else {
                    recurrenceSection.style.display = 'none';
                    isRecurrent.value = '0'; // Reset to non-recurrent
                }
 });
</script>
@endsection
@endif

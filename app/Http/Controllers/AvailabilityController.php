<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all slots along with their availability data
        $slots = Slot::with('availability')->get();

        // Return the view with all slots
        return view('admin.dashboard', compact('slots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     // Validate the request data
    //     $this->validateAvailability($request);

    //     // Get the authenticated user
    //     $user = Auth::user();

    //     if ($user && $user->role === 'admin') {
    //         // Check for existing slots
    //         if ($this->hasExistingSlot($user->id, $request)) {
    //             return redirect()->back()->with('error', 'An availability slot already exists for the selected date and time range.');
    //         }

    //         // Store the availability in the database
    //         $availability = Availability::create([
    //             'user_id' => $user->id,
    //             'availability_date' => $request->availability_date,
    //             'availability_start_time' => $request->availability_start_time,
    //             'availability_end_time' => $request->availability_end_time,
    //         ]);

    //         // Generate slots based on the available time range
    //         $this->generateSlots($availability->id, $request->availability_date, $request->availability_start_time, $request->availability_end_time);

    //         return redirect()->route('admin.dashboard')->with('success', 'Availability saved and slots generated.');
    //     }
    // }

    // protected function validateAvailability(Request $request)
    // {
    //     $request->validate([
    //         'availability_date' => 'required|date|after_or_equal:today',
    //         'availability_start_time' => 'required|date_format:H:i',
    //         'availability_end_time' => 'required|date_format:H:i|after:availability_start_time',
    //     ]);

    //     // Ensure that the availability time is greater than 30 minutes
    //     $startDateTime = strtotime($request->availability_date . ' ' . $request->availability_start_time);
    //     $endDateTime = strtotime($request->availability_date . ' ' . $request->availability_end_time);

    //     // Calculate the time difference in minutes
    //     $timeDifference = ($endDateTime - $startDateTime) / 60;

    //     if ($timeDifference < 30) {
    //         throw ValidationException::withMessages([
    //             'availability_end_time' => 'The availability duration must be at least 30 minutes.',
    //         ]);
    //     }
    // }

    // protected function hasExistingSlot($userId, Request $request)
    // {
    //     return Slot::whereHas('availability', function ($query) use ($userId, $request) {
    //         $query->where('user_id', $userId)
    //             ->where('availability_date', $request->availability_date)
    //             ->where(function ($query) use ($request) {
    //                 $query->whereBetween('availability_start_time', [$request->availability_start_time, $request->availability_end_time])
    //                     ->orWhereBetween('availability_end_time', [$request->availability_start_time, $request->availability_end_time])
    //                     ->orWhere(function ($query) use ($request) {
    //                         $query->where('availability_start_time', '<=', $request->availability_start_time)
    //                             ->where('availability_end_time', '>=', $request->availability_end_time);
    //                     });
    //             });
    //     })->exists();
    // }

    // protected function generateSlots($availabilityId, $date, $startTime, $endTime)
    // {
    //     $start = strtotime($date . ' ' . $startTime);
    //     $end = strtotime($date . ' ' . $endTime);

    //     // Create 30-minute slots within the available time range
    //     while ($start < $end) {
    //         $nextSlotTime = strtotime('+30 minutes', $start);

    //         // Ensure the next slot does not exceed the available end time
    //         if ($nextSlotTime <= $end) {
    //             Slot::create([
    //                 'availability_id' => $availabilityId,
    //                 'slot_start_time' => date('H:i', $start),
    //                 'slot_end_time' => date('H:i', $nextSlotTime),
    //                 'slot_status' => '1',
    //             ]);
    //         }

    //         $start = $nextSlotTime; // Move to the next slot
    //     }
    // }



    public function store(Request $request)
    {
        // Validate the request data
        $this->validateAvailability($request);

        // Get the authenticated user
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            // Check for existing slots
            if ($this->hasExistingSlot($user->id, $request)) {
                return redirect()->back()->with('error', 'An availability slot already exists for the selected date and time range.');
            }

            // Get the start date for availability
            $startDate = Carbon::parse($request->availability_date);
            $excludedDays = array_map('strtolower', $request->excluded_days ?? []); // Convert excluded days to lowercase

            // Check if recurrence is selected
            $recurrence = $request->recurrence;
            $endDate = null;

            if ($recurrence) {
                // Determine the end date based on recurrence
                switch ($recurrence) {
                    case '1_week':
                        $endDate = $startDate->copy()->addWeek();
                        break;
                    case '2_weeks':
                        $endDate = $startDate->copy()->addWeeks(2);
                        break;
                    case '1_month':
                        $endDate = $startDate->copy()->addMonth();
                        break;
                    case '2_months':
                        $endDate = $startDate->copy()->addMonths(2);
                        break;
                    default:
                        return redirect()->back()->with('error', 'Invalid recurrence duration.');
                }

                // Loop through each day within the recurrence period
                for ($date = $startDate->copy(); $date->lessThanOrEqualTo($endDate); $date->addDay()) {
                    $currentDay = strtolower($date->format('l'));

                    // Check if the day is excluded
                    if (!in_array($currentDay, $excludedDays)) {
                        // Store the availability for this date
                        $availability = Availability::create([
                            'user_id' => $user->id,
                            'availability_date' => $date->format('Y-m-d'),
                            'availability_start_time' => $request->availability_start_time,
                            'availability_end_time' => $request->availability_end_time,
                        ]);

                        // Generate slots for the available time range on this date
                        $this->generateSlots($availability->id, $date->format('Y-m-d'), $request->availability_start_time, $request->availability_end_time);
                    }
                }
            } else {
                // If no recurrence is selected, create a single availability slot for the selected date only
                $availability = Availability::create([
                    'user_id' => $user->id,
                    'availability_date' => $startDate->format('Y-m-d'),
                    'availability_start_time' => $request->availability_start_time,
                    'availability_end_time' => $request->availability_end_time,
                ]);

                // Generate slots for the selected date
                $this->generateSlots($availability->id, $startDate->format('Y-m-d'), $request->availability_start_time, $request->availability_end_time);
            }

            return redirect()->route('admin.dashboard')->with('success', 'Availability saved and slots generated.');
        }
    }




    protected function validateAvailability(Request $request)
    {
        $request->validate([
            'availability_date' => 'required|date|after_or_equal:today',
            'availability_start_time' => 'required|date_format:H:i',
            'availability_end_time' => 'required|date_format:H:i|after:availability_start_time',

        ]);

        if ($request->isRecurrent == '1') {
            $request->validate([
                'recurrence' => 'required',
                'excluded_days' => 'array',
            ]);
        }
        // Ensure that the availability time is greater than 30 minutes
        $startDateTime = strtotime($request->availability_date . ' ' . $request->availability_start_time);
        $endDateTime = strtotime($request->availability_date . ' ' . $request->availability_end_time);

        // Calculate the time difference in minutes
        $timeDifference = ($endDateTime - $startDateTime) / 60;

        if ($timeDifference < 30) {
            throw ValidationException::withMessages([
                'availability_end_time' => 'The availability duration must be at least 30 minutes.',
            ]);
        }
    }

    protected function hasExistingSlot($userId, Request $request)
    {
        return Slot::whereHas('availability', function ($query) use ($userId, $request) {
            $query->where('user_id', $userId)
                ->where('availability_date', $request->availability_date)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('availability_start_time', [$request->availability_start_time, $request->availability_end_time])
                        ->orWhereBetween('availability_end_time', [$request->availability_start_time, $request->availability_end_time])
                        ->orWhere(function ($query) use ($request) {
                            $query->where('availability_start_time', '<=', $request->availability_start_time)
                                ->where('availability_end_time', '>=', $request->availability_end_time);
                        });
                });
        })->exists();
    }

    protected function generateSlots($availabilityId, $date, $startTime, $endTime)
    {
        $start = strtotime($date . ' ' . $startTime);
        $end = strtotime($date . ' ' . $endTime);

        // Create 30-minute slots within the available time range
        while ($start < $end) {
            $nextSlotTime = strtotime('+30 minutes', $start);

            // Ensure the next slot does not exceed the available end time
            if ($nextSlotTime <= $end) {
                Slot::create([
                    'availability_id' => $availabilityId,
                    'slot_start_time' => date('H:i', $start),
                    'slot_end_time' => date('H:i', $nextSlotTime),
                    'slot_status' => '1',
                ]);
            }

            $start = $nextSlotTime; // Move to the next slot
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\Slot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        return view('home');
    }
    public function getSlots(Request $request)
    {
        $date = $request->get('date');
        $today = Carbon::today(); // Get today's date

        // Fetch availabilities by date, eager load related slots, and filter only today's or future dates
        $availabilities = Availability::with(['slots' => function ($query) {
                $query->where('slot_status', 1); // Only active slots
            }])
            ->where('availability_date', '>=', $today)
            ->where('availability_date', $date)
            ->get();

        $slots = []; // Initialize the slots array

        if ($availabilities->isNotEmpty()) {
            foreach ($availabilities as $availability) {
                foreach ($availability->slots as $slot) {
                    $slots[] = [
                        'value' => $slot->id, // Assuming 'id' is the slot identifier
                        'label' => Carbon::parse($slot->slot_start_time)->format('h:i A'),
                    'label1' => Carbon::parse($slot->slot_end_time)->format('h:i A'),
                    ];
                }
            }
        }

        return response()->json($slots);

    }

    public function storeBooking(Request $request)
    {
         // Validate the incoming request
         $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'date' => 'required|date',
            'options' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'message' => 'nullable|string|max:500',
        ]);

        // Check if the user already exists or create a new user
        $user = User::firstOrCreate(
            ['email' => Str::lower($validatedData['email'])], // Search for existing user by email
            [
                'name' => $validatedData['name'],
                'contact_number'=>$validatedData['contact_number'],
                'address' => $validatedData['address'],
                'message'=>$validatedData['message'],

                 'role'=>'user'
            ]
        );

        // Fetch availability and slot IDs based on the selected options (assumed as 'options')
        $availability = Availability::where('availability_date', $validatedData['date'])->first();
        $slot = Slot::find($validatedData['options']); // Assuming 'options' is the slot ID

        if ($slot) {
            // Check if a booking already exists for this slot
            $existingBooking = Booking::where('slot_id', $slot->id)->where('booking_status', '0')->first(); // Check if slot is booked

            if ($existingBooking) {
                // If a booking already exists, return an error response
                return redirect()->back()->with('error', 'This slot has already been booked.');
            }

            // Create a new booking if no existing booking is found
           $newBooking= Booking::create([
                'slot_id' => $slot->id,
                'user_id' => $user->id, // Associate the booking with the user
                'booking_status' => '0' // Assuming '0' means booked
            ]);

            // Update the slot status to indicate it's now booked
            $slot->update([
                'slot_status' => '0' // Assuming '0' means booked; update according to your business logic
            ]);

          // Fetch the booked slot details
        $bookedSlot = Slot::with('availability', 'booking.user')->find($newBooking->slot_id);

        // Send confirmation email to the user
        $this->sendConfirmationEmail($user, $bookedSlot);
            // Return a success response
            return redirect()->back()->with('success', 'Booking has been successfully created.');
        } else {
            // Return an error response if the slot is not found
            return redirect()->back()->with('error', 'Booking has not been created. Slot not found.');
        }



    }



    /**
 * Send confirmation email to the user.
 */
protected function sendConfirmationEmail($user, $bookedSlot)
{
    $details = [
        'name' => $user->name,
        'email' => $user->email,
        'date' => $bookedSlot->availability->availability_date,
        'start_time' => $bookedSlot->slot_start_time,
        'end_time' => $bookedSlot->slot_end_time,
    ];

    try {
        Mail::send('emails.slot_booking_confirmation', $details, function ($message) use ($user) {
            $message->from('support@metaxur.com', 'MetaXur');
            $message->to($user->email, $user->name);
            $message->cc('support@metaxur.com', 'MetaXur Support');
            $message->subject('Slot Booked Successfully');
        });

        Log::info("Confirmation email sent to {$user->email} and support@metaxur.com");

    } catch (\Exception $e) {
        Log::error("Failed to send email to {$user->email}: " . $e->getMessage());
    }
}


}

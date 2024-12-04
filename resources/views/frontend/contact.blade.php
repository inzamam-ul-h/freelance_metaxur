@extends('layouts.app')
@section('content')
<section class="page-header" >
            <div class="bg-item">
                <div class="bg-img" data-background="assets/img/bg-img/bg-contact.png"></div>
                <div class="overlay"></div>
                <div class="shapes">
                    <div class="shape shape-1"><img src="assets/img/shapes/page-header-shape-1.png" alt="shape"></div>
                    <div class="shape shape-2"><img src="assets/img/shapes/page-header-shape-2.png" alt="shape"></div>
                    <div class="shape shape-3"><img src="assets/img/shapes/page-header-shape-3.png" alt="shape"></div>
                </div>
            </div>
            <div class="container">
                <div class="page-header-content">
                    <h1 class="title">Contact Us</h1>
                    <h4 class="sub-title"><a class="home" href="javascript:void(0)">Home </a><span class="icon">/</span><a class="inner-page" href="javascript:void(0)"> Contact Us</a></h4>
                </div>
            </div>
        </section>

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show"
            style="margin-inline-start: 100px; margin-inline-end: 100px; padding: 10px;" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show"
            style="margin-inline-start: 100px; margin-inline-end: 100px; padding: 10px;" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
<section class="contact-section pt-120 pb-120">
    <div class="container">
        <div class="row gy-lg-0 gy-5">
            <div class="col-lg-7">
                <div class="blog-contact-form contact-form">
                    <h2 class="title mb-0">Leave A Reply</h2>
                    <p class="mb-30 mt-10">We’re always here to chat! Reach out to us with any questions or concerns you may have, and we’ll be happy to help.</p>
                    <div class="request-form">
                        <form action="{{ route('booking') }}" method="POST" class="form-horizontal">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="form-item">
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Your Name" value="{{ old('name') }}" required>
                                        <div class="icon"><i class="fa-regular fa-user"></i></div>
                                    </div>
                                    @error('name')
                                    <div class="text-danger"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-item">
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Your Email" value="{{ old('email') }}" required>
                                        <div class="icon"><i class="fa-regular fa-envelope"></i></div>
                                    </div>
                                    @error('email')
                                    <div class="text-danger"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="form-item">
                                        <input type="date" id="date" name="date" class="form-control" required>
                                        <div class="icon"><i class="fa-regular"></i></div>
                                    </div>
                                    @error('date')
                                    <div class="text-danger"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-item">
                                        <select name="options" class="form-control" id="options">
                                            <option value="" disabled selected>Select an Option</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="form-item">
                                        <input type="text" id="address" name="address" class="form-control" placeholder="Your Address" value="{{ old('address') }}" required>
                                        <div class="icon"><i class="fa-regular fa-user"></i></div>
                                    </div>
                                    @error('address')
                                    <div class="text-danger"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="form-item">
                                        <input type="number" id="contact_number" name="contact_number" class="form-control" placeholder="Your Contact" value="{{ old('contact_number') }}" required>
                                        <div class="icon"><i class="fa-regular fa-phone"></i></div>
                                    </div>
                                    @error('contact_number')
                                    <div class="text-danger"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <div class="form-item message-item">
                                        <textarea id="message" name="message" cols="30" rows="5" class="form-control address" placeholder="Message">{{ old('message') }}</textarea>
                                        <div class="icon"><i class="fa-regular fa-comment-alt"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btn">
                                <button id="submit" class="ed-primary-btn" type="submit">Book Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <div class="contact-content">
                    <div class="contact-top">
                        <h3 class="title">Contact Information</h3>

                    </div>
                    <div class="contact-list">
                        <div class="list-item">
                            <div class="icon">
                                <i class="fa-sharp fa-solid fa-envelope"></i>
                            </div>
                            <div class="content">
                                <h4 class="title">Email Us</h4>

                                <span><a href="mailto:support@metaxur.com">support@metaxur.com</a></span>
                            </div>
                        </div>
                        <div class="list-item">
                            <div class="icon">
                                <i class="fa-sharp fa-solid fa-location-dot"></i>
                            </div>
                            <div class="content">
                                <h4 class="title">Our Office Address</h4>
                                <p>4935 Dowson Dr, Atlanta, GA, USA</p>
                            </div>
                        </div>
                        <div class="list-item">
                            <div class="icon">
                                <i class="fa-sharp fa-solid fa-clock"></i>
                            </div>
                            <div class="content">
                                <h4 class="title">Official Work Time</h4>
                                <span>Monday - Friday: 09:00 - 20:00</span>
                                <span>Sunday & Saturday: 10:30 - 22:00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ./ contact-section -->

@endsection


@section('scripts')
<script>
    const getSlotsUrl = "{{ route('get-slots') }}";

    $(document).ready(function () {
        const $options = $('#options');
        const $date = $('#date');

        // Initialize dropdown state
        $options.prop('disabled', true);

        // Fetch available slots when date changes
        $date.change(function () {
            const selectedDate = $(this).val();

            // Clear existing options and disable dropdown
            $options.empty()
                .append('<option value="" disabled>Loading...</option>')
                .prop('disabled', true);

            // Fetch slots via AJAX
            $.ajax({
                url: getSlotsUrl,
                method: 'GET',
                data: { date: selectedDate },
                success: function (data) {
                        // Clear previous options
                        $('#options').empty();
                        $('#options').append(
                            '<option value="" disabled selected>Select an Option</option>');



                        // Populate options
                        $.each(data, function(index, option) {
                            $('#options').append('<option value="' + option.value +
                                '">' + option.label + ' - ' + option.label1 +
                                '</option>');
                        });
                        $options.prop('disabled', false);
                    },

                error: function () {
                    alert('Error fetching slots. Please try again later.');
                    $options.empty()
                        .append('<option value="" disabled>Error loading options</option>')
                        .prop('disabled', true);
                }
            });
        });

        // Validate form on submit
        $('form').on('submit', function (e) {
            if (!$options.val()) {
                alert('Please select an option before submitting the form.');
                $options.focus();
                e.preventDefault(); // Prevent form submission
            }
        });
    });
</script>



@endsection


<section class="streamline-section" id="contact">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                <div id="content_block_33">
                    <div class="content-box">
                        <h2>Join the Waitlist for My Prospect Tracker</h2>
                        <div class="text">Be the first to try My Prospect Tracker and start organising your network marketing business.</div>
                        <form action="/waitlist" method="POST" class="max-w-md mx-auto">
                            @csrf

                            <div class="mb-3">
                                <input type="text"
                                       name="first_name"
                                       required
                                       placeholder="Your first name"
                                       class="form-control w-100"
                                       style="padding: 0.75rem 1rem; border-radius: 0.375rem; border: 1px solid #ccc; color: #1f2937;">
                            </div>

                            <div class="mb-3">
                                <input type="email"
                                       name="email"
                                       required
                                       placeholder="Your email"
                                       class="form-control w-100"
                                       style="padding: 0.75rem 1rem; border-radius: 0.375rem; border: 1px solid #ccc; color: #1f2937;">
                            </div>

                            <button type="submit"
                                    class="btn custom-outline-primary w-100">
                                Join Now
                            </button>
                        </form>
                    @if(session('success'))
                            <div class="alert alert-success mt-2">{{ session('success') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger mt-2">
                                {{ $errors->first() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                <div id="image_block_33">
                    <div class="image-box wow slideInRight" data-wow-delay="300ms" data-wow-duration="1500ms">
                        <figure class="image clearfix js-tilt"><img src="images/resource/mpt-5.png" alt="My Prospect Tracker Preview" class="img-fluid rounded shadow" /></figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="streamline-section" id="contact">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                <div id="content_block_33">
                    <div class="content-box">
                        <h2>Join the Waitlist for My Prospect Tracker</h2>
                        <div class="text">Be the first to try My Prospect Tracker and start organising your network marketing business.</div>
                        <form action="/waitlist" method="POST" class="max-w-md mx-auto d-flex flex-column flex-sm-row gap-2 align-items-start">
                            @csrf
                            <input type="email"
                                   name="email"
                                   required
                                   placeholder="Your email"
                                   class="form-control me-sm-2 mb-2 mb-sm-0 w-100"
                                   style="padding: 0.75rem 1rem; border-radius: 0.375rem; border: 1px solid #ccc; color: #1f2937;">

                            <button type="submit"
                                    class="btn custom-outline-primary">
                                Join Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                <div id="image_block_33">
                    <div class="image-box wow slideInRight" data-wow-delay="300ms" data-wow-duration="1500ms">
                        <figure class="image clearfix js-tilt"><img src="images/resource/mpt-5.png" alt=""></figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

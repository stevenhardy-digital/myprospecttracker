<section class="clients-section home-12">
    <div class="container">
        <div class="sec-title">
            <h2>Trusted By Companies</h2>
            <p>Trusted by over 400,000 Webmasters worldwide to Research, Monitor<br />& Drive traffic to their websites.</p>
        </div>
        <div class="clients-carousel owl-carousel owl-theme owl-dots-none">
            @foreach (range(1, 4) as $i)
                @for ($j = 0; $j < 3; $j++)
                    <figure class="image-box"><a href="#"><img src="{{ asset("images/clients/client-$i.png") }}" alt=""></a></figure>
                @endfor
            @endforeach
        </div>
    </div>
</section>

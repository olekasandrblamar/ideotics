<!DOCTYPE html>
<html lang="{{ getLang() }}">

<head>
    @section('title', $SeoConfiguration->title ?? '')
    @include('frontend.global.includes.head')
    @push('styles_libs')
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/aos/aos.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/extra/dashboard.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/owl/owl.carousel.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/owl/owl.transitions.css') }}">
    @endpush
    @include('frontend.global.includes.styles')
</head>

<body>
    <header class="header">
        @include('frontend.includes.navbar')
    </header>

    <div id="owl-demo" class="owl-carousel owl-theme">
        <div class="item">
            <img src="{{ asset('images/dashboard/sld1.jpg') }}">
        </div>
        <div class="item">
            <img src="{{ asset('images/dashboard/sld3b.jpg') }}">
        </div>
        <div class="item">
            <img src="{{ asset('images/dashboard/s1.jpg') }}">
        </div>
        <div class="item">
            <img src="{{ asset('images/dashboard/s2.jpg') }}">
        </div>
        <div class="item">
            <img src="{{ asset('images/dashboard/s3.jpg') }}">
        </div>
        <div class="item">
            <img src="{{ asset('images/dashboard/s4.jpg') }}">
        </div>
    </div>

    <div class="container">
        <div class="row" style="margin-top: 30px;">
            <div class="col-lg-6">
                <div class="col-lg-12 text-center " style="margin-top: 10px;">
                    <img src="{{ asset('images/dark-logo.jpg') }}" style="height: 60px;margin-top: 20px;display: inline-block;">
                    <h1 class="text-upper font-size-33px margin-top-20px">{{ lang('Your one-stop Retail Operations Solution', 'dashboard') }}</h1>
                    <p class="margin-bottom-15px">
                        <a href="/register" class="btn btn-new-primary btn-lg margin-py-10px">{{ lang('Sign-up for a free pilot today!', 'dashboard') }}</a>
                    </p>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="col-lg-6 small text-right text-muted">
                <video autoplay controls="" style="width: 100%">
                {{ lang("Sorry, your browser doesn't support embedded videos,
                but don't worry, you can ", 'dashboard') }}
                <a href="">{{ lang('download it', 'dashboard') }}</a>
                {{ lang(' and watch it with your favorite video player!', 'dashboard') }}
                
                <source src="{{ asset('assets/Ideotics_Lo.mp4') }}" type="video/mp4">

                </video>
            </div>
            <div class="col-lg-8 offset-lg-2">
                    <hr>
                </div>
            <div class="col-lg-12">
                <div class="col-lg-8 offset-lg-2" style="margin-top: 20px;">
                <h4 class="text-center font-size-19px padding-py-15px">{{ lang('IDEOtics uses your CCTV footage to give you digital dashboards that capture everything about your store', 'dashboard') }}</h4>
            </div>
            <!-- 4 features starts here -->
            <div class="row">
            <div class="col-lg-2 offset-lg-2 text-center padding-py-30px">
                <div class="mycircle">
                    <img src="{{ asset('images/dashboard/shopper-icon.png') }}" style="top: 27px; left: 43px;">
                </div>
                <h5>{{ lang('Your Shoppers', 'dashboard') }}</h5>
            </div>
            <div class="col-lg-2 text-center padding-py-30px">
                <div class="mycircle">
                    <img src="{{ asset('images/dashboard/salesperson-icon.png') }}" style="top: 27px; left: 23px;">
                </div>
                <h5>{{ lang('Your Salesmen', 'dashboard') }}</h5>
            </div>
            <div class="col-lg-2 text-center padding-py-30px">
                <div class="mycircle">
                    <img src="{{ asset('images/dashboard/stock-icon.png') }}" style="top: 27px; left: 33px;">
                </div>
                <h5>{{ lang('Your Stock', 'dashboard') }}</h5>
            </div>
            <div class="col-lg-2 text-center padding-py-30px">
                <div class="mycircle">
                    <img src="{{ asset('images/dashboard/floor-icon.png') }}" style="top: 27px; left: 23px;">
                </div>
                <h5>{{ lang('Your Space', 'dashboard') }}</h5>
            </div>
            </div>

        </div>
        </div>
        <!-- 4 features ends here -->
    </div>

    <div class="greenblock">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 offset-lg-2">
                <img class="" src="{{ asset('images/dashboard/price-tag.png') }}">
                </div>
                <div class="col-lg-6 white">
                    <h1 class="text-upper font-size-33px margin-bottom-10px margin-top-20px">No additional investment</h1>
                    <p class="lead">
                    {{ lang('You read that right! No additional investment.', 'dashboard') }}
                    </p>
                    <p class="margin-bottom-15px font-size-15px">
                    {{ lang('IDEOtics innovative platform extracts every “bit” of valuable information in your store
                        and gives it back to you with actionable analytics, on insightful dashboards.', 'dashboard') }}
                    </p>
                    <p class="margin-bottom-15px font-size-15px">
                    {{ lang('For no additional investment! Yes! You read that right!. No additional investment.
                        IDEOtics takes your CCTV footage, converts the video into analyzable data and gives
                        you actionable dashboards!', 'dashboard') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="offset-lg-2 col-lg-8 text-center">
                <h1 class="text-center p-20 font-size-33px text-upper margin-bottom-10px margin-top-20px">How we do it</h1>
                <p class="lead">
                {{ lang('IDEOtics’ proprietary Iris™ application combines sophisticated Image Recognition algorithms,
                    Machine Learning , Deep Learning &amp; Artificial Intelligence technologies to convert videos to analyzable data.', 'dashboard') }}
                </p>
            </div>
            <div class="col-lg-12">
                <img class="img-responsive" src="{{ asset('images/dashboard/process-how-it-works-ideotics.jpg') }}" style="padding: 30px 0px; visibility: visible;">
            </div>
            <div class="offset-lg-2 col-lg-8 text-center" style="margin-bottom: 30px;">
                <p class="margin-bottom-15px">
                {{ lang('This data is then confirmed and enriched with the help of Language Independent systems and trained analysts. These analysts
                    are able to combine the data so extracted with any other relevant data such as Point of Sale
                    data, social media or loyalty card data that may be available, besides adding other semantic
                    data', 'dashboard') }}
                </p>
            </div>
        </div>
    </div>

    <div class="greyblock">
        <div class="container">
            <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                <h1 class="font-size-33px text-upper margin-top-20px margin-bottom-10px">Our Dashboards</h1>
                <p class="lead">
                {{ lang('IDEOtics’ provides both standardized and customized dashboards, as required by our retailer clients.', 'dashboard') }}
                </p>
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-2">
                    <img src="{{ asset('images/dashboard/chart1.png') }}" class="img-responsive" >
                </div>
                <div class="col-lg-2">
                    <img src="{{ asset('images/dashboard/chart2.png') }}" style="margin-top: 30px;">
                    <img src="{{ asset('images/dashboard/chart3.png') }}" style="margin-top: 30px;">
                </div>
            </div>

            <div class="clearfix"></div>

            <div style="margin-top: 40px;" class="col-lg-8 offset-lg-2 text-center">
                <p class="margin-top-15px">
                {{ lang('During the initial “Understand Retailer Requirements” phase, IDEOtics meets your
                    stake holders to understand their perspective and works with them to develop customized
                    dashboards, which are then submitted at agreed frequencies.', 'dashboard') }}
                </p>
            </div>
        </div>
    </div>

    <div class="container" style="padding: 30px 0px;">
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <h1 class="font-size-33px text-upper">{{ lang('About us', 'dashboard') }}</h1>
                <p class="lead">
                {{ lang('IDEOtics is powered by SigMax-e, a prominent provider of
                    Global Business Services, based in Malaysia.', 'dashboard') }}
                </p>
                <p class="margin-bottom-15px">
                {{ lang('It represents what SigMax-e has learned from its clients –
                    hi-tech start-ups from Silicon valley since 2002.', 'dashboard') }}
                </p>
                <p class="margin-bottom-15px">
                {{ lang('Picking up from simple image processing, involving back-ground separation to complex video
                    analytics, the team at SigMax-e realised that more and more analog data in the shape of digital
                    files would form the content of the internet. From YouTube videos to digital photographs on
                    millions of websites – all would remain “dumb” till technology evolved to read an image
                    or an audio file as effectively as it does a text file. Whether it is for searching for words
                    one is interested in or analysing or comparing content across files, clearly there was an
                    urgent need for extracting intelligence from such analog data hiding in digital files.', 'dashboard') }}
                </p>
                <p class="lead">
                {{ lang('Enter A2D: the low-profile project to look for means to convert analog to digital.', 'dashboard') }}
                </p>
                <p class="margin-bottom-15px">
                {{ lang('Aided by the rapid development of Computer Vision, Machine Learning, Artificial
                    Intelligence, Big Data and Data Science, our little project became a distinct possibility.', 'dashboard') }}
                </p>
                <p class="margin-bottom-15px">
                {{ lang('Our long years of experience in sourcing talent as well as managing processes was an added advantage.
                    Eventually, we put all this together to develop IDEOtics, a multi-application, cross-technology
                    platform that is designed to take CCTV footage, extract every ‘bit’ and pixel of information it
                    contained and place that in an analysable database for text-like analysis.  Conscious that the
                    vast scope of the idea could very easily drain our modest resources, we focused on the brick &amp;
                    mortar retail sector – a sector that has been badly hit by on-liners, but clearly offers immense
                    distinct value to consumers.', 'dashboard') }}
                </p>
                <p class="margin-bottom-15px">
                {{ lang('Our team of engineers and experts from the fields of Computer Vision, Data Science,
                    Software programming and Retail Operations are proud to present IDEOtics – a cost effective
                    and high-return solution for understanding everything that happens in the brick &amp; mortar store of today!', 'dashboard') }}
                </p>
                <hr>
                <p class="text-center margin-bottom-15px">
                    <a href="/register" class="btn btn-new-primary btn-lg">
                        {{ lang('Sign up for a free pilot today!', 'dashboard') }}
                    </a>
                </p>
            </div>
        </div>
    </div>

    @if(settings('website_contact_form_status'))
    <div class="greenblock white">
        <section id="contact"
            class="section p-0 {{ $faqs->count() > 0 && $settings['website_faq_status'] ? 'bg-white' : '' }}">
            <div class="container-lg">
                <div class="section-inner">
                    <div class="section-header">
                        <div class="section-title white">
                            <h5 class="white">{{ lang('Contact Us', 'home page') }}</h5>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="contact-us" data-aos="zoom-in" data-aos-duration="1000">
                            @include('frontend.includes.contact-form')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @endif


    @include('frontend.global.includes.footer')
    @push('scripts_libs')
        <script src="{{ asset('assets/vendor/libs/aos/aos.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/clipboard/clipboard.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/owl/owl.carousel.js') }}"></script>
    @endpush
    @include('frontend.configurations.config')
    @include('frontend.configurations.widgets')
    @include('frontend.global.includes.scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#owl-demo").owlCarousel({
                dots: true,
                navigation : true, // Show next and prev buttons
                slideSpeed : 300,
                paginationSpeed : 400,
                singleItem:true,
                autoPlay: true,
                navigation: false,
                stopOnHover: true
            });

          //$('video').play();

        });
    </script>
</body>

</html>

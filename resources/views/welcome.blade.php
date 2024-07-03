<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Iyke63 | Home</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-poppins">
<header>
    <div>

    </div>
    <nav class="mobile-nav">
        <div x-data="{ open: false }">
            <div class="lg:hidden flex justify-between items-center">
                <img class="w-1/4 rounded-full" src="{{ asset('images/logo.svg') }}" alt="">
                <a href="/early-access" wire:navigate class="bg-blue-900 px-4 py-2 text-white font-bold rounded-2xl inline-block">Get an Early Access</a>
                <img class="hamburger-menu w-20" src="{{ asset('images/hamburger.svg') }}" alt="" @click="open = !open">
            </div>
            <div class="mobile-nav"
                 x-bind:class="{ 'hidden': !open }"
                 x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90">
                <div class="text-2xl font-medium space-y-5 py-5 w-4/5 mx-auto text-center">
                    <a class="hover-green block text-gray-500" href="#how">How does it work</a>
                    <a class="hover-green block text-gray-500" href="#what">What do you get</a>
                    <a class="hover-green block text-gray-500" href="#pricing">Plans & Pricing</a>
                    <a class="hover-green block text-gray-500 pb-3 " href="#testimonials">What others say</a>
                    {{-- <a href="/login" wire:navigate class="bg-blue-800 text-white text-lg px-7 py-2 rounded-lg">Sign In</a> --}}
                </div>
            </div>
        </div>
    </nav>
    <nav class="hidden lg:flex justify-between items-center px-5">
        <figure class="w-1/12">
            <img class="rounded-full" src="{{ asset('images/logo.svg') }}" alt="">
        </figure>
        <div class="space-x-4 flex items-center">
            <a class="hover:text-green-600 text-xl text-gray-500" href="#how">How does it work</a>
            <a class="hover:text-green-600 text-xl text-gray-500" href="#what">What do you get</a>
            <a class="hover:text-green-600 text-xl text-gray-500" href="#pricing">Plans & Pricing</a>
            <a class="hover:text-green-600 text-xl text-gray-500" href="#testimonials">What others say</a>
            <div class="space-x-5">
                {{-- <a href="/login" wire:navigate class="bg-blue-800 text-white text-lg px-7 py-2 rounded-lg">Sign In</a> --}}
                <a href="/early-access" wire:navigate class="bg-blue-800 text-white text-lg px-5 py-2 rounded-lg">Get an Early Access</a>
            </div>
        </div>
    </nav>
    <div class="banner">
        <img class="lg:hidden" width="100%" src="{{ asset('images/banner_mobile.webp') }}" alt="">
        <img class="hidden lg:block" width="100%" src="{{ asset('images/banner_desktop.webp') }}" alt="">
    </div>
</header>
<section>
    <div class="flex">
        <div class="py-16 xl:w-1/2 xl:flex items-center justify-center xl:pl-32">
            <div class="space-y-5 px-10">
                <h1 class="text-2xl xl:text-5xl font-bold">Everyone deserves an <br> opportunity to win lottery</h1>
                <p>Your one stop application to get lotto forcast and game predictions from professional <br> book makers to guarantee your wins</p>

                <a href="/early-access" wire:navigate class="bg-blue-900 px-4 py-2 text-white font-bold rounded-2xl inline-block">Get an Early Access</a>
            </div>
        </div>
        <div class="hidden xl:block w-1/3">
            <img src="{{ asset('images/early_access.jpg') }}" alt="">
        </div>
    </div>
</section>
<section">
<div id="how" class="w-full py-10 lg:py-48 text-center bg-purple-100">
    <h2 class="font-bold text-3xl xl:text-5xl">How does it work?</h2>
    <p class="text-gray-600 text-md xl:text-xl py-5">Creating a safe place for your lotto predictions</p>
    <div class="w-full space-y-10 my-10 xl:px-48">
        <div class="space-y-10 xl:space-y-0 xl:flex flex-wrap justify-center">
            <div class="bg-white w-11/12 xl:w-1/3 shadow-lg mx-auto xl:mx-16 py-5 rounded-2xl px-5">
                <figure class="mb-5">
                    <img src="{{ asset('images/request.jpg') }}" alt="Call image">
                </figure>
                <h2 class="text-2xl font-bold">Request Stake</h2>
                <p class="text-gray-600 text-lg text-left">As a subscribed member, you can directly request us to stake a game for you. All you have to do is login into your account and request the numbers you want to stake, the type of game and the amount.</p>
            </div>
            <div class="bg-white w-11/12 lg:w-1/3 shadow-lg mx-auto xl:mx-16 py-5 rounded-2xl px-5">
                <figure class="mb-5">
                    <img src="{{ asset('images/prediction.jpg') }}" alt="Call image">
                </figure>
                <h2 class="text-2xl font-bold xl:pt-3">Get free Predictions</h2>
                <p class="text-gray-600 text-lg text-left">As a subscribed member, you will be getting free predictions from our professional book makers for the various lotto we cover in other to assure your winnings </p>
            </div>
        </div>
        <div class="space-y-10 xl:space-y-0 xl:flex flex-wrap justify-center">
            <div class="bg-white w-11/12 lg:w-1/3 shadow-lg mx-auto xl:mx-16 py-5 rounded-2xl px-5">
                <figure class="mb-5">
                    <img src="{{ asset('images/monitor.jpg') }}" alt="Call image">
                </figure>
                <h2 class="text-2xl font-bold">Check daily draws</h2>
                <p class="text-gray-600 text-lg text-left">Get access to daily draws as well as historical draws from the various games we cover for your analysis.</p>
            </div>
            <div class="bg-white w-11/12 lg:w-1/3 shadow-lg mx-auto xl:mx-16 py-5 rounded-2xl px-5">
                <figure class="mb-5">
                    <img src="{{ asset('images/videos.jpg') }}" alt="Call image">
                </figure>
                <h2 class="text-2xl font-bold">Daily focast videos</h2>
                <p class="text-gray-600 text-lg text-left">As a subscribed member, you will have access to daily analysis and prediction videos from our book makers explaining the games and the forcast numbers for the various lotto we cover </p>
            </div>
        </div>
    </div>
</div>
<div id="what" class="w-full pt-20 lg:pt-48 px-10 text-center lg:mx-auto">
    <h2 class="font-bold text-3xl lg:text-5xl">What games do we cover</h2>
    <p class="text-gray-600 text-md lg:text-xl py-5">Check out the games we offer predictions on</p>
    <div class="w-full my-10 flex flex-wrap justify-between lg:justify-center items-center">
        <figure class="w-2/5"><img class="mx-auto" src="{{ asset('images/nla-logo.jpg') }}" alt="NLA Logo"></figure>
        <figure class="w-2/5"><img class="mx-auto" src="{{ asset('images/uk49s-logo.jpeg') }}" alt="UK49s Logo"></figure>
        <figure class="w-2/5"><img class="mx-auto" src="{{ asset('images/afriluck-logo.png') }}" alt="Afriluck Logo"></figure>
        <figure class="w-2/5"><img class="mx-auto" width="250" src="{{ asset('images/alpha-logo.png') }}" alt="Alpha Logo"></figure>
    </div>
</div>
</section>
<section id="pricing">
    <div class="bg-gray-100 pt-20 lg:py-44 px-5 xl:px-48">
        <h2 class="text-3xl text-center font-bold lg:text-5xl">Pick your Perfect Plan</h2>
        <p class="text-center text-md lg:text-xl py-5">Choose from our customized plans for your needs</p>
        <div class="w-full py-10 space-y-10 xl:space-y-0 xl:flex">
            <div class="bg-blue-800 pt-3 w-11/12 xl:w-1/4 mx-auto rounded-t-lg shadow-lg">
                <div class="bg-white px-10 py-10">
                    <div>
                        <h2 class="text-4xl text-left font-bold">Basic</h2>
                        <p class="text-left text-gray-500">Most Popular</p>
                    </div>
                    <div class="mt-10 space-y-10">
                        <h2 class="text-4xl font-black">GH₵70.00<span class=" font-light text-sm">/3 months</span></h2>
                        <a href="#" wire:navigate class="block bg-blue-800 w-10/12 py-3 px-5 rounded-lg text-white text-xl font-semibold">Choose Plan</a>
                    </div>
                    <div class="mt-10 mb-24 text-left">
                        <h3 class="font-bold">What's included?</h3>
                        <ul class="mt-5 space-y-5 text-sm">
                            <li>3 Months Subscription</li>
                            <li>Daily lotto forcast</li>
                            <li>Daily Prediction Video</li>
                            <li>Betting Request</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bg-blue-800 pt-3 w-11/12 xl:w-1/4 mx-auto rounded-t-lg shadow-lg">
                <div class="bg-white px-10 py-10">
                    <div>
                        <h2 class="text-4xl text-left font-bold">Deluxe</h2>
                        <p class="text-left text-gray-500">Regular Star</p>
                    </div>
                    <div class="mt-10 space-y-10">
                        <h2 class="text-4xl font-black">GH₵120.00<span class=" font-light text-sm">/6 months</span></h2>
                        <a href="#" wire:navigate class="block bg-blue-800 w-10/12 py-3 px-5 rounded-lg text-white text-xl font-semibold">Choose Plan</a>
                    </div>
                    <div class="mt-10 mb-24 text-left">
                        <h3 class="font-bold">What's included?</h3>
                        <ul class="mt-5 space-y-5 text-sm">
                            <li>6 Months Subscription</li>
                            <li>Daily lotto forcast</li>
                            <li>Daily Prediction Video</li>
                            <li>Betting Request</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="bg-blue-800 pt-3 w-11/12 xl:w-1/4 mx-auto rounded-t-lg shadow-lg">
                <div class="bg-white px-10 py-10">
                    <div>
                        <h2 class="text-4xl text-left font-bold">Gold</h2>
                        <p class="text-left text-gray-500">Most Economical</p>
                    </div>
                    <div class="mt-10 space-y-10">
                        <h2 class="text-4xl font-black">GH₵150.00<span class=" font-light text-sm">/year</span></h2>
                        <a href="#" wire:navigate class="block bg-blue-800 w-10/12 py-3 px-5 rounded-lg text-white text-xl font-semibold">Choose Plan</a>
                    </div>
                    <div class="mt-10 mb-24 text-left">
                        <h3 class="font-bold">What's included?</h3>
                        <ul class="mt-5 space-y-5 text-sm">
                            <li>1 Year Subscription</li>
                            <li>Daily lotto forcast</li>
                            <li>Daily Prediction Video</li>
                            <li>Betting Request</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="testimonials">
    <div class="py-20 xl:py-48">
        <h2 class="text-3xl xl:text-5xl font-bold text-center">Experience with Iyke63</h2>
        <p class="text-center text-md xl:text-xl py-5 text-gray-500">Read what people are saying about Iyke63</p>
        <div class="mt-10 space-y-10 xl:space-y-0 lg:flex justify-center">
            <div class="bg-pink-100 w-10/12 xl:w-1/4 rounded-2xl mx-auto xl:mx-10 px-5 py-10">
                <h2 class="text-2xl font-bold text-center">Bev, subscriber of IKY63</h2>
                <p class="text-gray-600">Hello dear lovers of lottery games, I invite you to massively follow one of the largest tipsters ever known under the name of IYK63. With IYK63 Lottery, it is insurance because you can win more than 2 or 3 per week. With IYK63 it's 90% victory</p>
                <div class="mt-3 flex items-center">
                    <img class="w-16 rounded-full mr-3" src="{{ asset('images/testimonials1.jpeg') }}" alt="">
                    <div>
                        <h2 class="text-xl text-gray-500">Bev</h2>
                    </div>
                </div>
            </div>
            <div class="bg-pink-100 w-10/12 xl:w-1/4 rounded-2xl mx-auto xl:mx-10 px-5 py-10">
                <h2 class="text-2xl font-bold text-center">Eshun, Subscriber of IYKE63</h2>
                <p class="text-gray-600">I joined IYKE63 when I was having some financial challenges and I can tell you for a fact that I've never looked back since. I win lotto every single week and what's more is they provide forcast videos not only are they giving tips but also they teach you the game</p>
                <div class="mt-3 flex items-center">
                    <img class="w-16 rounded-full mr-3" src="{{ asset('images/testimonials2.jpeg') }}" alt="">
                    <div>
                        <h2 class="text-xl text-gray-500">Eshun</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<footer class="bg-blue-900 h-32 flex justify-center items-center">
    <h1 class="text-white xl:text-xl">Copyright iyke63.com, 2024</h1>
</footer>
@livewireScripts
<wireui:scripts />
</body>
</html>

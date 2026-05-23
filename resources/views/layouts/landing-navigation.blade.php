<nav class="sticky top-0 z-50 border-b border-borderColor bg-bg/90 backdrop-blur-xl">

    <div class="mx-auto flex h-[70px] max-w-7xl items-center justify-between px-6 lg:px-12">

        <a href="/" class="font-fraunces text-2xl font-black text-primary">
            GASSIN
        </a>

        <div class="hidden items-center gap-9 md:flex">

            @if (request()->routeIs('landing-pages'))
                <a href="#fitur" class="text-sm font-semibold text-grayText transition hover:text-primary">
                    Fitur
                </a>

                <a href="#cara" class="text-sm font-semibold text-grayText transition hover:text-primary">
                    Cara Kerja
                </a>

                <a href="#driver" class="text-sm font-semibold text-grayText transition hover:text-primary">
                    Jadi Driver
                </a>

                <a href="#download" class="text-sm font-semibold text-grayText transition hover:text-primary">
                    Download
                </a>
            @endif

            <a href="{{ route('privacy-policy') }}"
                class="text-sm font-semibold text-grayText transition hover:text-primary">
                Privacy Policy
            </a>

            <a href="{{ route('terms-of-service') }}"
                class="text-sm font-semibold text-grayText transition hover:text-primary">
                Terms of Service
            </a>

        </div>

        <a href="#download"
            class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-sm font-bold text-white transition hover:bg-primaryDark">

            <i class="fa-brands fa-google-play"></i>

            Download App

        </a>

    </div>

</nav>

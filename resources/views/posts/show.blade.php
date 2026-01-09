<x-layout title="PIXL - Feed">
    <!-- Navigation -->
    @include("partials.navigation")

    <!-- Content -->
    <main class="-mx-4 flex grow flex-col gap-4 overflow-y-auto px-4 py-4">
        <div>
            <nav class="overflow-x-auto [scrollbar-width:none]">
                <ul class="flex min-w-max justify-end gap-8 text-sm">
                    <li><a href="#">For you</a></li>
                    <li>
                        <a class="text-pixl-light/60 hover:text-pixl-light/80" href="#">Idea streams</a>
                    </li>
                    <li>
                        <a class="text-pixl-light/60 hover:text-pixl-light/80" href="#">Following</a>
                    </li>
                </ul>
            </nav>
        </div>

        <ol class="mt-4">
            <x-post :post="$post" show-replies="true"/>
        </ol>

        <footer class="mt-30 ml-14">
            <p class="text-center">That's all, folks!</p>
            <hr class="border-pixl-light/10 my-4"/>
            <!-- White noise -->
            <div class="h-20 bg-[url(/images/white-noise.gif)]"></div>
        </footer>
    </main>

    <!-- Sidebar -->
    @include('partials.aside')
</x-layout>

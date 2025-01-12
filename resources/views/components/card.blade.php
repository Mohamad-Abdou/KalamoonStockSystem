<section {{ $attributes->merge(['class' => 'overflow-hidden shadow-xl sm:rounded-lg p-5 z-10']) }}>
    <h3 class="text-center text-white font-bold">
        {{ $header }}
    </h3>
    {{ $slot }}
</section>


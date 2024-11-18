<section {{ $attributes->merge(['class' => 'bg-primary overflow-hidden shadow-xl sm:rounded-lg p-5']) }}>
    <h3 class="text-center text-white font-semibold">
        {{ $header }}
    </h3>
    {{ $slot }}
</section>


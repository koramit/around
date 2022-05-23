<script>
    window.translations = {!! App\Services\TranslationGenerator::get(session('locale', 'en'))->toJson() !!}
</script>
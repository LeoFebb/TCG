<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Marketplace --}}
    <url>
        <loc>{{ url('/marketplace') }}</loc>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Permute --}}
    <url>
        <loc>{{ url('/permute') }}</loc>
        <changefreq>hourly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Categorie --}}
    @foreach (['pokemon', 'mtg', 'yugioh', 'onepiece', 'dragon_ball_super', 'naruto'] as $cat)
        <url>
            <loc>{{ url('/categoria/' . $cat) }}</loc>
            <changefreq>daily</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    {{-- Validatori --}}
    <url>
        <loc>{{ url('/validatori') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>

    {{-- Privacy e Termini --}}
    <url>
        <loc>{{ url('/privacy') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>{{ url('/termini') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>

    {{-- Carte singole --}}
    @foreach ($cards as $card)
        <url>
            <loc>{{ url('/card/' . $card->id) }}</loc>
            <lastmod>{{ $card->updated_at->format('Y-m-d') }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

</urlset>


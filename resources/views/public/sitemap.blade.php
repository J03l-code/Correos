<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <!-- Home Page -->
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Legal Pages -->
    <url>
        <loc>{{ url('/politica-de-privacidad') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>

    <!-- Public Links (excluding protected links and search index excluded links) -->
    @foreach($links as $link)
        @if(!$link->access_code_hash && $link->redirect_mode !== 'automatic')
            <url>
                <loc>{{ route('public.access', $link->slug) }}</loc>
                <lastmod>{{ $link->updated_at->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.5</priority>
            </url>
        @endif
    @endforeach
</urlset>

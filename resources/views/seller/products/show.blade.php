{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/seller/products/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->nom . ' — Détails du produit | BidMaster')
@section('page-title', '')
@section('breadcrumb', '')

@section('content')
<div class="stellar-product-container">
    {{-- ABSTRACT AMBIENT BACKGROUND --}}
    <div class="nebula-dust">
        <div class="dust-core-1"></div>
        <div class="dust-core-2"></div>
        <div class="dust-core-3"></div>
    </div>

    {{-- DYNAMIC ORBITING ELEMENTS (PURE CSS) --}}
    <div class="floating-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="orb orb-4"></div>
    </div>

    {{-- MAIN PRODUCT SHOWCASE CARD --}}
    <div class="product-astral-card">
        <div class="card-cosmic-glow"></div>
        
        <div class="astral-grid">
            {{-- LEFT: IMMERSIVE GALLERY --}}
            <div class="gallery-nebula">
                @php
                    $photos = is_array($product->photos) ? $product->photos : json_decode($product->photos ?? '[]', true);
                    $mainImage = !empty($photos) ? Storage::url($photos[0]) : 'https://via.placeholder.com/800x600/1a202c/ffffff?text=✨+Aucune+image+✨';
                @endphp
                <div class="primary-image-zone" id="primaryImageZone">
                    <div class="image-lens">
                        <img src="{{ $mainImage }}" alt="{{ $product->nom }}" id="mainProductImage">
                        <div class="image-aura"></div>
                    </div>
                    <div class="glass-badge collection-badge">
                        <i class="fas fa-camera"></i> 
                        <span>{{ count($photos) }} vue(s) produit</span>
                    </div>
                </div>

                @if(count($photos) > 1)
                <div class="thumbnail-orbit" id="thumbnailGallery">
                    @foreach($photos as $index => $photo)
                        <div class="thumbnail-star {{ $index === 0 ? 'active-thumb' : '' }}" data-image="{{ Storage::url($photo) }}">
                            <img src="{{ Storage::url($photo) }}" alt="Miniature {{ $index+1 }}">
                            <div class="thumb-glow"></div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- RIGHT: PRODUCT COSMIC INFO --}}
            <div class="info-galactic">
                <div class="product-etiquette">
                    <div class="category-pulse">
                        <i class="fas fa-tag"></i> 
                        {{ $product->sousCategorie?->categorie?->nom ?? $product->sousCategorie?->nom ?? 'Objet rare' }}
                    </div>
                    <div class="condition-badge">
                        @switch($product->etat)
                            @case('NEUF') <span><i class="fas fa-gem"></i> Neuf · Premium</span> @break
                            @case('TRES_BON_ETAT') <span><i class="fas fa-star"></i> Très bon état</span> @break
                            @case('BON_ETAT') <span><i class="fas fa-check-circle"></i> Bon état</span> @break
                            @default <span><i class="fas fa-recycle"></i> État correct</span>
                        @endswitch
                    </div>
                </div>

                <h1 class="product-title-celestial">{{ $product->nom }}</h1>
                
                <div class="product-description-cosmic">
                    <div class="description-head">
                        <i class="fas fa-quote-left"></i> 
                        <span>Description authentique</span>
                    </div>
                    <p>{{ $product->description ?: 'Aucune description renseignée. Ce produit vous attend pour révéler tous ses mystères.' }}</p>
                </div>

                <div class="specs-stardust">
                    <div class="spec-row">
                        <div class="spec-icon"><i class="fas fa-copyright"></i></div>
                        <div class="spec-info">
                            <span class="spec-label">Marque</span>
                            <span class="spec-value">{{ $product->marque ?? 'Non spécifiée' }}</span>
                        </div>
                    </div>
                    <div class="spec-row">
                        <div class="spec-icon"><i class="fas fa-microchip"></i></div>
                        <div class="spec-info">
                            <span class="spec-label">Modèle</span>
                            <span class="spec-value">{{ $product->modele ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="spec-row">
                        <div class="spec-icon"><i class="fas fa-folder-tree"></i></div>
                        <div class="spec-info">
                            <span class="spec-label">Sous-catégorie</span>
                            <span class="spec-value">{{ $product->sousCategorie?->nom ?? 'Générique' }}</span>
                        </div>
                    </div>
                    <div class="spec-row">
                        <div class="spec-icon"><i class="fas fa-calendar-alt"></i></div>
                        <div class="spec-info">
                            <span class="spec-label">Référence vendeur</span>
                            <span class="spec-value">#PD{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>

                {{-- VENDEUR STATS CARD (COSMIC INSIGHTS) --}}
                @php
                    $seller = auth()->user()->client?->vendeur;
                    $totalSales = $seller?->nombre_ventes ?? 0;
                    $sellerRating = $seller?->note_moyenne ?? 0;
                    $totalProducts = $seller?->produits()->count() ?? 0;
                @endphp
                <div class="seller-meteor-card">
                    <div class="seller-avatar-cosmic">
                        <span>{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}</span>
                    </div>
                    <div class="seller-stats-cosmic">
                        <div class="stat-meteor">
                            <i class="fas fa-chart-line"></i>
                            <span>{{ $totalSales }} ventes</span>
                        </div>
                        <div class="stat-meteor">
                            <i class="fas fa-star"></i>
                            <span>{{ number_format($sellerRating, 1) }} ★</span>
                        </div>
                        <div class="stat-meteor">
                            <i class="fas fa-boxes"></i>
                            <span>{{ $totalProducts }} produits</span>
                        </div>
                    </div>
                </div>

                {{-- DYNAMIC AUCTION SECTION IF ANY ACTIVE ANNOUNCE --}}
                @php
                    $activeAnnonce = $product->annonces()->where('statut', 'ACTIVE')->where('date_fin', '>', now())->orderBy('created_at', 'desc')->first();
                    $endedAnnonce = $product->annonces()->where('statut', 'CLOTUREE')->orderBy('date_fin', 'desc')->first();
                @endphp

                @if($activeAnnonce)
                <div class="live-auction-banner">
                    <div class="live-badge"><i class="fas fa-circle"></i> Enchère active</div>
                    <div class="auction-telemetry">
                        <div class="telemetry-block">
                            <span class="tele-label">Prix actuel</span>
                            <span class="tele-value">{{ number_format($activeAnnonce->getMontantActuel(), 0) }} MAD</span>
                        </div>
                        <div class="telemetry-block">
                            <span class="tele-label">Enchères</span>
                            <span class="tele-value">{{ $activeAnnonce->encheres()->count() }}</span>
                        </div>
                        <div class="telemetry-block">
                            <span class="tele-label">Fin dans</span>
                            <span class="tele-value time-remaining" data-end="{{ $activeAnnonce->date_fin }}"></span>
                        </div>
                    </div>
                    <a href="{{ route('annonces.show', $activeAnnonce) }}" class="cosmic-cta-btn small">
                        <i class="fas fa-eye"></i> Voir l'enchère
                    </a>
                </div>
                @elseif($endedAnnonce)
                <div class="past-auction-glimpse">
                    <i class="fas fa-history"></i> Dernière enchère clôturée · Prix final : {{ number_format($endedAnnonce->prix_final ?? $endedAnnonce->prix_depart, 0) }} MAD
                </div>
                @endif

                <div class="stellar-actions">
                    <a href="{{ route('seller.products.index') }}" class="action-ghost">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <a href="{{ route('seller.products.edit', $product) }}" class="action-primary">
                        <i class="fas fa-pen-fancy"></i> Éditer le produit
                        <span class="btn-stardust"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- RECOMMENDATION GALAXY (other products from same seller) --}}
    @php
        $otherProducts = $seller?->produits()->where('id', '!=', $product->id)->limit(3)->get();
    @endphp
    @if($otherProducts && $otherProducts->count())
    <div class="related-nova">
        <div class="nova-header">
            <i class="fas fa-star-of-life"></i>
            <h3>Autres créations cosmiques</h3>
            <div class="cosmic-divider"></div>
        </div>
        <div class="mini-product-grid">
            @foreach($otherProducts as $related)
                <a href="{{ route('seller.products.show', $related) }}" class="mini-product-card">
                    @php 
                        $relPhotos = is_array($related->photos) ? $related->photos : json_decode($related->photos ?? '[]', true);
                        $relImg = !empty($relPhotos) ? Storage::url($relPhotos[0]) : 'https://via.placeholder.com/100/2d3748/ffffff?text=📦';
                    @endphp
                    <div class="mini-img" style="background-image: url('{{ $relImg }}');"></div>
                    <div class="mini-info">
                        <strong>{{ Str::limit($related->nom, 35) }}</strong>
                        <span>{{ $related->sousCategorie?->nom ?? 'Artéfact' }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* ------------------------------------------------------------
   STELLAR PRODUCT PAGE - PURE CSS, NO EXTERNAL DEPENDENCIES
   Immersive, futuristic design with gradient #667eea → #764ba2
   ------------------------------------------------------------ */
:root {
    --primary-deep: #667eea;
    --primary-bright: #764ba2;
    --gradient-aurora: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-sunset: linear-gradient(125deg, #f093fb 0%, #f5576c 100%);
    --glass-bg: rgba(255, 255, 255, 0.88);
    --glass-border: rgba(102, 126, 234, 0.22);
    --shadow-3d: 0 35px 60px -25px rgba(0, 0, 0, 0.35);
    --shadow-glow: 0 0 25px rgba(102, 126, 234, 0.35);
    --radius-cosmic: 40px;
    --radius-meteor: 28px;
}

.stellar-product-container {
    position: relative;
    max-width: 1440px;
    margin: 2rem auto 4rem;
    padding: 0 2rem;
    z-index: 2;
}

/* NEBULA DUST BACKGROUND */
.nebula-dust {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -3;
    pointer-events: none;
    overflow: hidden;
}

.dust-core-1, .dust-core-2, .dust-core-3 {
    position: absolute;
    border-radius: 50%;
    filter: blur(90px);
    opacity: 0.5;
    animation: floatDust 28s infinite alternate;
}
.dust-core-1 {
    width: 70vw;
    height: 70vw;
    background: radial-gradient(circle, rgba(102,126,234,0.25), transparent);
    top: -20%;
    left: -20%;
}
.dust-core-2 {
    width: 60vw;
    height: 60vw;
    background: radial-gradient(circle, rgba(118,75,162,0.2), transparent);
    bottom: -30%;
    right: -10%;
    animation-duration: 35s;
}
.dust-core-3 {
    width: 50vw;
    height: 50vw;
    background: radial-gradient(circle, rgba(102,126,234,0.12), transparent);
    top: 40%;
    left: 40%;
    animation-duration: 22s;
    opacity: 0.3;
}

@keyframes floatDust {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(5%, 5%) scale(1.08); }
}

/* ORBITING DECORATIONS */
.floating-orbs .orb {
    position: fixed;
    background: rgba(255,255,240,0.1);
    backdrop-filter: blur(3px);
    border-radius: 50%;
    pointer-events: none;
    z-index: -1;
    box-shadow: 0 0 12px rgba(102,126,234,0.3);
    animation: orbitSpin 20s linear infinite;
}
.orb-1 { width: 240px; height: 240px; top: 15%; left: -60px; animation-duration: 35s;}
.orb-2 { width: 180px; height: 180px; bottom: 10%; right: 5%; animation-duration: 28s; opacity: 0.4;}
.orb-3 { width: 130px; height: 130px; top: 65%; left: 85%; animation-duration: 40s; background: rgba(118,75,162,0.15);}
.orb-4 { width: 300px; height: 300px; bottom: -80px; left: -80px; animation-duration: 50s; opacity: 0.2;}

@keyframes orbitSpin {
    0% { transform: rotate(0deg) translateX(20px) rotate(0deg);}
    100% { transform: rotate(360deg) translateX(20px) rotate(-360deg);}
}

/* MAIN CARD */
.product-astral-card {
    position: relative;
    background: var(--glass-bg);
    backdrop-filter: blur(12px);
    border-radius: var(--radius-cosmic);
    border: 1px solid var(--glass-border);
    box-shadow: var(--shadow-3d);
    overflow: hidden;
    transition: transform 0.35s ease, box-shadow 0.4s;
}
.product-astral-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 40px 70px -20px rgba(0,0,0,0.45);
}
.card-cosmic-glow {
    position: absolute;
    inset: 0;
    background: var(--gradient-aurora);
    opacity: 0.03;
    pointer-events: none;
}

.astral-grid {
    display: grid;
    grid-template-columns: 1fr 1.1fr;
    gap: 2rem;
    padding: 2rem;
}

/* GALLERY */
.gallery-nebula {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}
.primary-image-zone {
    position: relative;
    border-radius: 32px;
    overflow: hidden;
    background: #0f172a10;
    box-shadow: 0 20px 35px -18px rgba(0,0,0,0.2);
}
.image-lens {
    position: relative;
    width: 100%;
    padding-bottom: 75%;
    overflow: hidden;
}
.image-lens img {
    position: absolute;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.2, 0.9, 0.4, 1.1);
}
.image-lens:hover img {
    transform: scale(1.03);
}
.image-aura {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 50% 30%, rgba(102,126,234,0.15), transparent 70%);
    pointer-events: none;
}
.glass-badge {
    position: absolute;
    bottom: 1rem;
    right: 1rem;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(12px);
    border-radius: 60px;
    padding: 0.4rem 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: white;
    letter-spacing: 0.3px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    z-index: 2;
}
.thumbnail-orbit {
    display: flex;
    gap: 0.8rem;
    flex-wrap: wrap;
}
.thumbnail-star {
    width: 85px;
    height: 85px;
    border-radius: 20px;
    overflow: hidden;
    cursor: pointer;
    position: relative;
    border: 2px solid transparent;
    transition: all 0.25s;
    background: #f1f5f9;
}
.thumbnail-star img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.thumbnail-star.active-thumb {
    border-color: var(--primary-deep);
    box-shadow: 0 0 0 3px rgba(102,126,234,0.3);
    transform: scale(0.98);
}
.thumb-glow {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle, rgba(102,126,234,0.2), transparent);
    opacity: 0;
    transition: 0.2s;
}
.thumbnail-star:hover .thumb-glow { opacity: 1; }

/* RIGHT INFO */
.info-galactic {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.product-etiquette {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
}
.category-pulse {
    background: rgba(102,126,234,0.12);
    padding: 0.25rem 1rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--primary-deep);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.condition-badge {
    background: rgba(0,0,0,0.05);
    border-radius: 50px;
    padding: 0.25rem 1rem;
    font-size: 0.75rem;
    font-weight: 600;
}
.product-title-celestial {
    font-size: 2.7rem;
    font-weight: 800;
    line-height: 1.2;
    background: var(--gradient-aurora);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin: 0;
}
.product-description-cosmic {
    background: rgba(248,250,252,0.7);
    padding: 1rem 1.2rem;
    border-radius: 28px;
    border-left: 4px solid var(--primary-deep);
}
.description-head {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.8rem;
}
.description-head i { color: var(--primary-deep);}
.product-description-cosmic p {
    color: #2d3a4e;
    line-height: 1.5;
    margin: 0;
    font-size: 0.95rem;
}
.specs-stardust {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    background: white;
    border-radius: 32px;
    padding: 1rem 1.2rem;
    box-shadow: 0 6px 14px rgba(0,0,0,0.02);
}
.spec-row {
    display: flex;
    align-items: center;
    gap: 0.8rem;
}
.spec-icon {
    width: 36px;
    height: 36px;
    background: var(--gradient-aurora);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
.spec-info {
    display: flex;
    flex-direction: column;
}
.spec-label {
    font-size: 0.65rem;
    text-transform: uppercase;
    font-weight: 700;
    color: #6c757d;
}
.spec-value {
    font-weight: 700;
    color: #1e293b;
}
.seller-meteor-card {
    margin-top: 0.5rem;
    background: rgba(102,126,234,0.05);
    border-radius: 28px;
    padding: 1rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.2rem;
    flex-wrap: wrap;
    border: 1px solid rgba(102,126,234,0.2);
}
.seller-avatar-cosmic {
    width: 54px;
    height: 54px;
    background: var(--gradient-aurora);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 800;
    font-size: 1.5rem;
}
.seller-stats-cosmic {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}
.stat-meteor {
    background: white;
    border-radius: 60px;
    padding: 0.4rem 1rem;
    font-size: 0.8rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
}
.stat-meteor i { color: var(--primary-deep);}

/* LIVE AUCTION BANNER */
.live-auction-banner {
    background: linear-gradient(115deg, #1e293b 0%, #0f172a 100%);
    border-radius: 32px;
    padding: 1rem 1.5rem;
    color: white;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}
.live-badge {
    background: #f43f5e;
    border-radius: 80px;
    padding: 0.2rem 1rem;
    font-size: 0.7rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.live-badge i { font-size: 0.6rem; animation: pulse-live 1.2s infinite; }
@keyframes pulse-live { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
.auction-telemetry {
    display: flex;
    gap: 1.5rem;
}
.telemetry-block {
    text-align: center;
}
.tele-label {
    font-size: 0.7rem;
    opacity: 0.7;
    display: block;
}
.tele-value {
    font-weight: 800;
    font-size: 1.1rem;
}
.cosmic-cta-btn {
    background: var(--gradient-aurora);
    border: none;
    border-radius: 40px;
    padding: 0.5rem 1.4rem;
    font-weight: 600;
    color: white;
    text-decoration: none;
    transition: 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.cosmic-cta-btn.small { padding: 0.4rem 1rem; font-size: 0.8rem; }
.cosmic-cta-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(102,126,234,0.5); color: white; }
.past-auction-glimpse {
    background: #f1f5f9;
    border-radius: 80px;
    padding: 0.6rem 1.2rem;
    font-size: 0.8rem;
    text-align: center;
    color: #334155;
}
.stellar-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}
.action-ghost {
    background: transparent;
    border: 1px solid #cbd5e1;
    padding: 0.7rem 1.8rem;
    border-radius: 60px;
    font-weight: 600;
    text-decoration: none;
    color: #334155;
    transition: all 0.2s;
    text-align: center;
}
.action-ghost:hover {
    border-color: var(--primary-deep);
    color: var(--primary-deep);
    transform: translateY(-2px);
}
.action-primary {
    background: var(--gradient-aurora);
    border: none;
    padding: 0.7rem 2rem;
    border-radius: 60px;
    font-weight: 700;
    text-decoration: none;
    color: white;
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    position: relative;
    overflow: hidden;
    transition: all 0.2s;
    box-shadow: 0 10px 20px -8px rgba(102,126,234,0.5);
}
.action-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 30px -12px rgba(102,126,234,0.6);
}
.btn-stardust {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}
.action-primary:hover .btn-stardust { left: 100%; }

/* RELATED PRODUCTS */
.related-nova {
    margin-top: 3rem;
    background: rgba(255,255,255,0.7);
    backdrop-filter: blur(12px);
    border-radius: 40px;
    padding: 1.8rem;
    border: 1px solid var(--glass-border);
}
.nova-header {
    display: flex;
    align-items: baseline;
    gap: 1rem;
    margin-bottom: 1.8rem;
}
.nova-header i {
    font-size: 1.8rem;
    color: var(--primary-deep);
}
.nova-header h3 {
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0;
    background: var(--gradient-aurora);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}
.cosmic-divider {
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, var(--primary-deep), transparent);
}
.mini-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
    gap: 1.2rem;
}
.mini-product-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: white;
    border-radius: 28px;
    padding: 0.8rem;
    text-decoration: none;
    transition: all 0.25s;
    box-shadow: 0 5px 12px rgba(0,0,0,0.03);
}
.mini-product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    border: 1px solid rgba(102,126,234,0.3);
}
.mini-img {
    width: 60px;
    height: 60px;
    border-radius: 20px;
    background-size: cover;
    background-position: center;
    background-color: #eef2ff;
}
.mini-info strong {
    display: block;
    font-weight: 800;
    color: #0f172a;
    font-size: 0.9rem;
}
.mini-info span {
    font-size: 0.7rem;
    color: #5b6e8c;
}

/* RESPONSIVE STELLAR */
@media (max-width: 950px) {
    .astral-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 1.5rem;
    }
    .product-title-celestial { font-size: 2rem; }
    .stellar-product-container { padding: 0 1rem 2rem; margin: 1rem auto; }
    .thumbnail-star { width: 65px; height: 65px; }
    .seller-meteor-card { flex-direction: column; align-items: flex-start; }
    .live-auction-banner { flex-direction: column; align-items: stretch; }
}
@media (max-width: 550px) {
    .specs-stardust { grid-template-columns: 1fr; }
    .action-primary, .action-ghost { justify-content: center; flex: 1; }
    .stellar-actions { flex-wrap: wrap; }
}
</style>
@endpush

@push('scripts')
    <!-- Icônes Font Awesome (chargement robuste) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
<script>
    (function() {
        // IMAGE THUMBNAIL GALLERY (vanilla JS)
        const mainImage = document.getElementById('mainProductImage');
        const thumbElements = document.querySelectorAll('.thumbnail-star');
        if (thumbElements.length && mainImage) {
            thumbElements.forEach(thumb => {
                thumb.addEventListener('click', function(e) {
                    const newSrc = this.getAttribute('data-image');
                    if (newSrc) {
                        mainImage.src = newSrc;
                        thumbElements.forEach(t => t.classList.remove('active-thumb'));
                        this.classList.add('active-thumb');
                    }
                });
            });
        }

        // Countdown timer for active auction (if any)
        const timeElements = document.querySelectorAll('.time-remaining');
        if (timeElements.length) {
            function updateTimers() {
                timeElements.forEach(el => {
                    const endDate = el.getAttribute('data-end');
                    if (!endDate) return;
                    const end = new Date(endDate).getTime();
                    const now = new Date().getTime();
                    const distance = end - now;
                    if (distance < 0) {
                        el.innerText = 'Terminée';
                        return;
                    }
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (86400000)) / (3600000));
                    const minutes = Math.floor((distance % 3600000) / 60000);
                    if (days > 0) el.innerText = `${days}j ${hours}h`;
                    else if (hours > 0) el.innerText = `${hours}h ${minutes}m`;
                    else el.innerText = `${minutes} min`;
                });
            }
            updateTimers();
            setInterval(updateTimers, 60000);
        }
    })();
</script>
@endpush
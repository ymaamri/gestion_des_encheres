{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Notifications')
@section('page-title', 'Mes Notifications')
@section('breadcrumb', 'Notifications')

@push('styles')
    <style>
        .notification-card {
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .notification-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            border-color: rgba(102, 126, 234, 0.2);
        }

        .notification-icon-wrapper {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .bg-light-success {
            background-color: rgba(45, 206, 137, 0.1);
            color: #2dce89;
        }

        .bg-light-warning {
            background-color: rgba(251, 99, 64, 0.1);
            color: #fb6340;
        }

        .bg-light-info {
            background-color: rgba(17, 205, 239, 0.1);
            color: #11cdef;
        }

        .bg-light-primary {
            background-color: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .empty-state-icon {
            font-size: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            opacity: 0.5;
            margin-bottom: 20px;
        }

        .unread-indicator {
            width: 10px;
            height: 10px;
            background-color: #667eea;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
        }

        .max-w-500 {
            max-width: 500px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <!-- Premium Header -->
        <div class="row mb-5">
            <div class="col-12">
                <div
                    class="card card-custom bg-gradient-theme text-white shadow-lg overflow-hidden position-relative rounded-4">
                    <!-- Decorative Elements -->
                    <div class="position-absolute"
                        style="top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;">
                    </div>
                    <div class="position-absolute"
                        style="bottom: -50px; left: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;">
                    </div>

                    <div
                        class="card-body p-5 position-relative z-index-1 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="d-flex align-items-center mb-4 mb-md-0">
                            <div class="bg-white p-3 rounded-circle d-flex align-items-center justify-content-center shadow"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-bell text-primary" style="font-size: 2.5rem;"></i>
                            </div>
                            <div class="ms-4">
                                <h2 class="text-white mb-1 fw-bold">Centre de Notifications</h2>
                                <p class="mb-0 text-white opacity-8">Restez informé de vos enchères et activités</p>
                            </div>
                        </div>

                        @if($notifications->count() > 0 && $unreadCount > 0)
                            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                @csrf
                                <button type="submit"
                                    class="btn btn-light text-primary fw-bold rounded-pill shadow px-4 py-2 mb-0 d-flex align-items-center gap-2">
                                    <i class="fas fa-check-double"></i> Tout marquer comme lu
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="row">
            <div class="col-12">
                @if($notifications->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($notifications as $notification)
                                <div
                                    class="card notification-card {{ !$notification->lue ? 'bg-white shadow-sm' : 'bg-transparent border-0' }} rounded-4">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center flex-column flex-sm-row text-center text-sm-start">
                                            <!-- Icon -->
                                            <div class="notification-icon-wrapper flex-shrink-0 mx-auto mx-sm-0 mb-3 mb-sm-0 {{ 
                                                                        $notification->type == 'VICTOIRE' ? 'bg-light-success' :
                            ($notification->type == 'SURENCHERE' ? 'bg-light-warning' :
                                ($notification->type == 'FIN_ENCHERE' ? 'bg-light-info' : 'bg-light-primary')) 
                                                                    }}">
                                                <i class="fas fa-{{ 
                                                                            $notification->type == 'VICTOIRE' ? 'trophy' :
                            ($notification->type == 'SURENCHERE' ? 'arrow-trend-up' :
                                ($notification->type == 'FIN_ENCHERE' ? 'clock' : 'bell')) 
                                                                        }} fa-lg"></i>
                                            </div>

                                            <!-- Content -->
                                            <div class="ms-sm-4 flex-grow-1 mb-3 mb-sm-0">
                                                <div
                                                    class="d-flex align-items-center justify-content-center justify-content-sm-start mb-1">
                                                    @if(!$notification->lue)
                                                        <span class="unread-indicator"></span>
                                                    @endif
                                                    <h6 class="mb-0 {{ !$notification->lue ? 'fw-bold text-dark' : 'text-secondary' }}">
                                                        {{ $notification->message }}
                                                    </h6>
                                                </div>
                                                <p class="text-sm text-muted mb-0">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $notification->created_at->format('d/m/Y H:i:s') }}
                                                    <span class="opacity-7">({{ $notification->created_at->diffForHumans() }})</span>
                                                </p>
                                            </div>

                                            <!-- Actions -->
                                            <div class="ms-sm-3 text-center text-sm-end flex-shrink-0">
                                                @if(!$notification->lue)
                                                    <form method="POST" action="{{ route('notifications.mark', $notification) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill mb-0 px-4"
                                                            title="Marquer comme lue">
                                                            <i class="fas fa-check me-1"></i> Lu
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-light text-secondary rounded-pill border px-3 py-2"><i
                                                            class="fas fa-check-double me-1"></i> Lue</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                    </div>

                    <div class="mt-5 d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="card card-custom border-0 shadow text-center py-5 rounded-4">
                        <div class="card-body py-5">
                            <i class="fas fa-bell-slash empty-state-icon"></i>
                            <h3 class="text-dark fw-bold mb-3">Aucune notification</h3>
                            <p class="text-muted mb-4 max-w-500 mx-auto" style="font-size: 1.1rem;">Vous n'avez pas encore de
                                notifications. Participez aux enchères, suivez des produits et revenez ici pour voir vos mises à
                                jour !</p>
                            <a href="{{ route('auctions.active') }}"
                                class="btn btn-gradient px-4 py-3 rounded-pill shadow mt-2">
                                <i class="fas fa-search me-2"></i> Explorer les enchères
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .bg-gradient-theme {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
    </style>
@endpush
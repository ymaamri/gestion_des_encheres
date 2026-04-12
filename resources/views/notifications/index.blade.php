{{-- /opt/lampp/htdocs/gestion_des_encheres/resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes Notifications')
@section('page-title', 'Mes Notifications')
@section('breadcrumb', 'Notifications')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3 mb-0">Centre de Notifications</h6>
                    @if($notifications->count() > 0 && $unreadCount > 0)
                        <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="me-3">
                            @csrf
                            <button type="submit" class="btn btn-sm bg-gradient-light text-dark mb-0">
                                <i class="material-symbols-rounded">mark_email_read</i> Tout marquer comme lu
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body px-0 pb-2">
                @if($notifications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($notifications as $notification)
                            <div class="list-group-item border-bottom-0 {{ !$notification->lue ? 'bg-gradient-light' : '' }}">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar avatar-md bg-gradient-{{ 
                                            $notification->type == 'VICTOIRE' ? 'success' : 
                                            ($notification->type == 'SURENCHERE' ? 'warning' : 
                                            ($notification->type == 'FIN_ENCHERE' ? 'info' : 'primary')) 
                                        }} border-radius-lg">
                                            <i class="material-symbols-rounded text-white">
                                                @switch($notification->type)
                                                    @case('VICTOIRE')
                                                        emoji_events
                                                        @break
                                                    @case('SURENCHERE')
                                                        trending_up
                                                        @break
                                                    @case('FIN_ENCHERE')
                                                        schedule
                                                        @break
                                                    @default
                                                        notifications
                                                @endswitch
                                            </i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 {{ !$notification->lue ? 'font-weight-bolder' : '' }}">
                                                    {{ $notification->message }}
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="material-symbols-rounded" style="font-size: 12px;">schedule</i>
                                                    {{ $notification->created_at->format('d/m/Y H:i:s') }}
                                                    ({{ $notification->created_at->diffForHumans() }})
                                                </p>
                                            </div>
                                            @if(!$notification->lue)
                                                <form method="POST" action="{{ route('notifications.mark', $notification) }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-link text-secondary">
                                                        <i class="material-symbols-rounded">check_circle</i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="badge badge-sm bg-gradient-secondary">Lu</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr class="my-0">
                            @endif
                        @endforeach
                    </div>
                    <div class="px-3 pt-3">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="material-symbols-rounded" style="font-size: 64px;">notifications_none</i>
                        <h5 class="mt-3">Aucune notification</h5>
                        <p class="text-muted">Vous n'avez pas encore de notifications. Participez aux enchères pour recevoir des mises à jour !</p>
                        <a href="{{ route('auctions.active') }}" class="btn bg-gradient-primary mt-2">
                            Explorer les enchères
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
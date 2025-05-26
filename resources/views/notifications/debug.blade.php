@extends('layouts.app')

@section('title', 'Debug Notifiche')
@section('page-title', 'Debug Sistema Notifiche')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informazioni di Debug</h3>
            </div>
            <div class="card-body">
                <h4>Utente Corrente</h4>
                <p>ID: {{ auth()->id() }}</p>
                <p>Nome: {{ auth()->user()->name }}</p>
                <p>Email: {{ auth()->user()->email }}</p>
                
                <h4 class="mt-4">Notifiche nel Database</h4>
                @php
                    $allNotifications = \DB::table('notifications')->count();
                    $myNotifications = auth()->user()->notifications()->count();
                    $unreadNotifications = auth()->user()->unreadNotifications()->count();
                @endphp
                <p>Totale notifiche nel sistema: {{ $allNotifications }}</p>
                <p>Le mie notifiche: {{ $myNotifications }}</p>
                <p>Notifiche non lette: {{ $unreadNotifications }}</p>
                
                <h4 class="mt-4">Ultime 5 Notifiche</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo</th>
                                <th>Dati</th>
                                <th>Letta</th>
                                <th>Creata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(auth()->user()->notifications()->take(5)->get() as $notification)
                            <tr>
                                <td>{{ substr($notification->id, 0, 8) }}...</td>
                                <td>{{ $notification->type }}</td>
                                <td><pre>{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</pre></td>
                                <td>{{ $notification->read_at ? 'Sì' : 'No' }}</td>
                                <td>{{ $notification->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <h4 class="mt-4">Test Connessione WebSocket</h4>
                <div id="websocket-status" class="alert alert-info">
                    Verificando connessione WebSocket...
                </div>
                
                <h4 class="mt-4">Configurazione Broadcasting</h4>
                <p>Driver: {{ config('broadcasting.default') }}</p>
                <p>Reverb Host: {{ env('REVERB_HOST') }}</p>
                <p>Reverb Port: {{ env('REVERB_PORT') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusDiv = document.getElementById('websocket-status');
    
    // Test WebSocket connection
    if (window.Echo) {
        statusDiv.innerHTML = 'Echo è inizializzato. Tentativo di connessione...';
        
        try {
            window.Echo.connector.pusher.connection.bind('connected', function() {
                statusDiv.className = 'alert alert-success';
                statusDiv.innerHTML = '✓ WebSocket connesso con successo!';
            });
            
            window.Echo.connector.pusher.connection.bind('failed', function() {
                statusDiv.className = 'alert alert-danger';
                statusDiv.innerHTML = '✗ Connessione WebSocket fallita. Assicurati che il server Reverb sia in esecuzione.';
            });
            
            window.Echo.connector.pusher.connection.bind('error', function(error) {
                statusDiv.className = 'alert alert-danger';
                statusDiv.innerHTML = '✗ Errore WebSocket: ' + error.message;
            });
        } catch (e) {
            statusDiv.className = 'alert alert-warning';
            statusDiv.innerHTML = '⚠ Echo non configurato correttamente: ' + e.message;
        }
    } else {
        statusDiv.className = 'alert alert-danger';
        statusDiv.innerHTML = '✗ Laravel Echo non è caricato';
    }
});
</script>
@endsection
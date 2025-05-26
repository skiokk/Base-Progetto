@extends('layouts.app')

@section('title', 'Test Notifiche')
@section('page-title', 'Test Sistema Notifiche')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Invia Notifica di Test</h3>
            </div>
            <div class="card-body">
                <form id="notification-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select class="form-select" id="notification-type">
                            <option value="info">Info</option>
                            <option value="success">Successo</option>
                            <option value="warning">Avviso</option>
                            <option value="error">Errore</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Titolo</label>
                        <input type="text" class="form-control" id="notification-title" placeholder="Inserisci il titolo" value="Notifica di Test">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Messaggio</label>
                        <textarea class="form-control" id="notification-message" rows="3" placeholder="Inserisci il messaggio">Questa è una notifica di test del sistema push.</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Modalità di invio
                            <button type="button" class="btn btn-sm btn-ghost-info ms-2" data-bs-toggle="modal" data-bs-target="#infoModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9" />
                                    <line x1="12" y1="8" x2="12.01" y2="8" />
                                    <polyline points="11 12 12 12 12 16 13 16" />
                                </svg>
                                Info
                            </button>
                        </label>
                        <div>
                            <label class="form-check">
                                <input type="checkbox" name="channels[]" value="database" class="form-check-input" checked>
                                <span class="form-check-label">Database (Polling)</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="channels[]" value="broadcast" class="form-check-input">
                                <span class="form-check-label">WebSocket (Real-time)</span>
                            </label>
                        </div>
                        <small class="form-hint">Seleziona una o entrambe le modalità di invio</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Tipo di visualizzazione
                            <button type="button" class="btn btn-sm btn-ghost-info ms-2" data-bs-toggle="modal" data-bs-target="#displayModal">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <circle cx="12" cy="12" r="9" />
                                    <line x1="12" y1="8" x2="12.01" y2="8" />
                                    <polyline points="11 12 12 12 12 16 13 16" />
                                </svg>
                                Info
                            </button>
                        </label>
                        <div>
                            <label class="form-check">
                                <input type="checkbox" name="display[]" value="toast" class="form-check-input" checked>
                                <span class="form-check-label">Toast (In-app)</span>
                            </label>
                            <label class="form-check">
                                <input type="checkbox" name="display[]" value="browser" class="form-check-input" checked>
                                <span class="form-check-label">Browser</span>
                            </label>
                        </div>
                        <small class="form-hint">Seleziona come mostrare le notifiche agli utenti</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Invia Notifica</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Seleziona Destinatari</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="users-table" class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th class="w-1">
                                    <input type="checkbox" class="form-check-input m-0 align-middle" id="selectAll">
                                </th>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th class="text-center">Data Creazione</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- I dati verranno caricati tramite DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Informativo -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="infoModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md text-info me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12.01" y2="8" />
                        <polyline points="11 12 12 12 12 16 13 16" />
                    </svg>
                    <span>Come funzionano le modalità di invio</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Solo Database -->
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary-subtle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-primary text-white rounded me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <ellipse cx="12" cy="6" rx="8" ry="3"></ellipse>
                                            <path d="M4 6v6a8 3 0 0 0 16 0v-6"></path>
                                            <path d="M4 12v6a8 3 0 0 0 16 0v-6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">Solo Database (Polling)</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-success me-2">✓</span> Notifica salvata nel database
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-warning me-2">⏱️</span> L'utente la riceve al prossimo polling (entro 10 secondi)
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-info me-2">📋</span> Appare nell'elenco delle notifiche
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-primary me-2">🔔</span> Il contatore sulla campanella si aggiorna
                                    </div>
                                </div>
                                <div class="alert alert-primary mb-0 mt-3">
                                    <strong>Usa quando:</strong> Vuoi notifiche persistenti che l'utente può vedere anche dopo.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Solo WebSocket -->
                    <div class="col-12">
                        <div class="card border-success">
                            <div class="card-header bg-success-subtle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-success text-white rounded me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M5 7l5 5l-5 5"></path>
                                            <line x1="12" y1="19" x2="19" y2="19"></line>
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">Solo WebSocket (Real-time)</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-success me-2">⚡</span> Notifica istantanea in tempo reale
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-danger me-2">❌</span> NON salvata nel database
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-danger me-2">❌</span> NON appare nell'elenco notifiche
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-danger me-2">❌</span> Il contatore NON si aggiorna
                                    </div>
                                </div>
                                <div class="alert alert-success mb-0 mt-3">
                                    <strong>Usa quando:</strong> Vuoi notifiche temporanee/effimere che non necessitano di persistenza.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Entrambe -->
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info-subtle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-info text-white rounded me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M3 7v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1 -1v-4" />
                                            <circle cx="15" cy="17" r="4" />
                                            <path d="M11 17v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1 -1v-4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 d-flex align-items-center">
                                            Database + WebSocket
                                            <span class="badge bg-info text-white ms-2">Consigliato</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-success me-2">✓</span> Notifica salvata nel database
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-success me-2">⚡</span> L'utente la riceve istantaneamente via WebSocket
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-info me-2">🎯</span> Il sistema evita duplicati automaticamente
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-info me-2">📋</span> Appare nell'elenco delle notifiche
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-primary me-2">🔔</span> Il contatore si aggiorna immediatamente
                                    </div>
                                </div>
                                <div class="alert alert-info mb-0 mt-3">
                                    <strong>Usa quando:</strong> Vuoi il meglio di entrambi i mondi - velocità e persistenza.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sistema Anti-Duplicati -->
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-start">
                            <div class="me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                    <path d="M12 9h.01" />
                                    <path d="M11 12h1v4h1" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="alert-title">Sistema Anti-Duplicati</h4>
                                <div class="text-secondary">
                                    Il sistema è intelligente: quando ricevi una notifica via WebSocket, viene marcata come "già mostrata". 
                                    Se il polling la trova successivamente, non viene mostrata di nuovo, evitando fastidiosi duplicati.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tipologie Visualizzazione -->
<div class="modal fade" id="displayModal" tabindex="-1" aria-labelledby="displayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center" id="displayModalLabel">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-md text-info me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="12" r="9" />
                        <line x1="12" y1="8" x2="12.01" y2="8" />
                        <polyline points="11 12 12 12 12 16 13 16" />
                    </svg>
                    <span>Tipologie di visualizzazione notifiche</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Toast In-App -->
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary-subtle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-primary text-white rounded me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <rect x="3" y="5" width="18" height="14" rx="2" />
                                            <path d="M7 15v-6l2 2l2 -2v6" />
                                            <path d="M14 13h2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">Toast (In-app)</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-info me-2">📍</span> Appare nell'angolo in alto a destra della pagina
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-success me-2">👁️</span> Visibile solo quando l'utente è nella pagina
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-primary me-2">🎨</span> Stile integrato con il tema dell'applicazione
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-warning me-2">⏱️</span> Scompare automaticamente dopo 5 secondi
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-secondary me-2">🚫</span> Non richiede permessi speciali
                                    </div>
                                </div>
                                <div class="alert alert-primary mb-0 mt-3">
                                    <strong>Usa quando:</strong> L'utente è attivo nell'applicazione e vuoi notifiche non invasive.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Browser Notification -->
                    <div class="col-12">
                        <div class="card border-success">
                            <div class="card-header bg-success-subtle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-success text-white rounded me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                                            <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">Browser (Sistema)</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-info me-2">🖥️</span> Notifica del sistema operativo
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-success me-2">🔔</span> Visibile anche con browser in background
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-primary me-2">📱</span> Funziona su desktop e dispositivi mobili
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-warning me-2">🔐</span> Richiede il permesso dell'utente
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-secondary me-2">🔊</span> Può includere suoni di notifica
                                    </div>
                                </div>
                                <div class="alert alert-success mb-0 mt-3">
                                    <strong>Usa quando:</strong> Vuoi raggiungere l'utente anche quando non sta guardando l'applicazione.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Entrambe -->
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info-subtle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-info text-white rounded me-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <circle cx="9" cy="7" r="4" />
                                            <path d="M3 7v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1 -1v-4" />
                                            <circle cx="15" cy="17" r="4" />
                                            <path d="M11 17v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1 -1v-4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0 d-flex align-items-center">
                                            Toast + Browser
                                            <span class="badge bg-info text-white ms-2">Consigliato</span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-success me-2">✓</span> Massima visibilità garantita
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-info me-2">🎯</span> Toast se l'utente è nella pagina
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-info me-2">🔔</span> Browser notification se è in background
                                    </div>
                                    <div class="list-group-item px-0">
                                        <span class="badge bg-primary me-2">💡</span> L'utente non perde mai una notifica
                                    </div>
                                </div>
                                <div class="alert alert-info mb-0 mt-3">
                                    <strong>Usa quando:</strong> Vuoi garantire che l'utente veda sempre le notifiche importanti.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Note sui permessi -->
                    <div class="col-12">
                        <div class="alert alert-warning d-flex align-items-start">
                            <div class="me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 9v2m0 4v.01" />
                                    <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="alert-title">Nota sui permessi</h4>
                                <div class="text-secondary">
                                    Le notifiche Browser richiedono il permesso esplicito dell'utente. 
                                    Se l'utente non ha concesso il permesso, verranno mostrati solo i Toast in-app.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<style>
/* Copia esatta degli stili dalla pagina users */
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_length {
    padding-top: 10px;
    padding-bottom: 10px;
}

.dataTables_wrapper .dataTables_filter {
    padding-right: 10px;
}

.dataTables_wrapper .row:first-child {
    margin-bottom: 10px;
}

/* Fix per evitare che i controlli vengano tagliati */
.dataTables_wrapper {
    padding-top: 10px;
    padding-right: 10px;
}

/* Aumenta dimensioni e stile dei controlli */
.dataTables_filter input {
    min-width: 300px;
    height: 38px;
    padding: 8px 12px;
    font-size: 16px;
    border-radius: 4px;
    border: 1px solid #ced4da;
}

.dataTables_length select {
    min-width: 80px;
    height: 38px;
    padding: 8px;
    font-size: 16px;
    border-radius: 4px;
    border: 1px solid #ced4da;
}

/* Migliora la paginazione con margini laterali */
.dataTables_wrapper .dataTables_paginate .paginate_button {
    margin-left: 3px !important;
    margin-right: 3px !important;
    border-radius: 4px;
}

/* Stile per il pulsante attivo */
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #467fd0 !important;
    border-color: #467fd0 !important;
    color: white !important;
}

/* Allinea la paginazione con il testo informativo */
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    margin-top: 10px;
    margin-bottom: 5px;
    padding-top: 0 !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('notification-form');
    
    // Inizializza DataTable per gli utenti
    const usersTable = $('#users-table').DataTable({
        processing: false,
        serverSide: false,
        ajax: {
            url: '/users/data',
            type: 'GET',
            dataSrc: 'data'
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `<input type="checkbox" class="form-check-input m-0 align-middle user-checkbox" value="${row.id}">`;
                }
            },
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { 
                data: 'created_at',
                className: 'text-center',
                render: function(data) {
                    return new Date(data).toLocaleString('it-IT');
                }
            }
        ],
        order: [[1, 'desc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/it-IT.json'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tutti"]],
        drawCallback: function() {
            // Mantieni lo stato delle checkbox dopo il ridisegno
            updateCheckboxStates();
        }
    });
    
    // Gestione checkbox "Seleziona tutto"
    $('#selectAll').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.user-checkbox').prop('checked', isChecked);
    });
    
    // Gestione checkbox singole
    $(document).on('change', '.user-checkbox', function() {
        const allChecked = $('.user-checkbox').length === $('.user-checkbox:checked').length;
        $('#selectAll').prop('checked', allChecked);
    });
    
    function updateCheckboxStates() {
        // Funzione vuota mantenuta per compatibilità
    }
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const selectedUsers = [];
        
        $('.user-checkbox:checked').each(function() {
            selectedUsers.push($(this).val());
        });
        
        if (selectedUsers.length === 0) {
            alert('Seleziona almeno un destinatario');
            return;
        }
        
        const selectedChannels = [];
        $('input[name="channels[]"]:checked').each(function() {
            selectedChannels.push($(this).val());
        });
        
        if (selectedChannels.length === 0) {
            alert('Seleziona almeno una modalità di invio');
            return;
        }
        
        const selectedDisplay = [];
        $('input[name="display[]"]:checked').each(function() {
            selectedDisplay.push($(this).val());
        });
        
        if (selectedDisplay.length === 0) {
            alert('Seleziona almeno un tipo di visualizzazione');
            return;
        }
        
        const baseData = {
            title: document.getElementById('notification-title').value,
            message: document.getElementById('notification-message').value,
            type: document.getElementById('notification-type').value,
            channels: selectedChannels,
            display: selectedDisplay
        };
        
        try {
            if (selectedUsers.length === 1 && selectedUsers[0] == {{ auth()->id() }}) {
                const response = await axios.post('/notifications/send', {
                    ...baseData,
                    user_id: {{ auth()->id() }}
                });
            } else {
                const response = await axios.post('/notifications/send-to-users', {
                    ...baseData,
                    user_ids: selectedUsers
                });
            }
            
        } catch (error) {
            console.error('Errore:', error);
            // Mostra solo errori, non conferme
            alert('Errore nell\'invio della notifica: ' + (error.response?.data?.message || 'Errore sconosciuto'));
        }
    });
});
</script>

<style>
.table-muted {
    opacity: 0.6;
}
</style>
@endpush
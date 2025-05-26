@extends('layouts.app')

@section('title', 'Notifiche')
@section('page-title', 'Notifiche')

@section('content')
<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Elenco Notifiche</h3>
                <div class="btn-list">
                    <button class="btn btn-danger" id="deleteSelectedBtn" style="display: none;" onclick="deleteSelectedNotifications()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <line x1="4" y1="7" x2="20" y2="7" />
                            <line x1="10" y1="11" x2="10" y2="17" />
                            <line x1="14" y1="11" x2="14" y2="17" />
                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                        </svg>
                        Elimina selezionate (<span id="selectedCount">0</span>)
                    </button>
                    <button class="btn btn-primary" onclick="window.notificationManager.markAllAsRead()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                        Segna tutte come lette
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="notifications-table" class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th class="w-1">
                                    <input type="checkbox" class="form-check-input m-0 align-middle" id="selectAll">
                                </th>
                                <th>Tipo</th>
                                <th>Titolo</th>
                                <th>Messaggio</th>
                                <th class="text-center">Data</th>
                                <th class="text-center">Stato</th>
                                <th class="text-center">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Le notifiche verranno caricate qui via DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal per visualizzare il messaggio completo -->
<div class="modal modal-blur fade" id="notificationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-header-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modal-message"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-primary" id="modal-mark-read" style="display: none;">Segna come letta</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal di conferma eliminazione -->
<div class="modal modal-sm fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body text-center py-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 9v2m0 4v.01"/>
                    <path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"/>
                </svg>
                <h3>Sei sicuro?</h3>
                <div class="text-muted" id="deleteModalMessage">Vuoi davvero eliminare questa notifica? Questa azione non può essere annullata.</div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button class="btn w-100" data-bs-dismiss="modal">Annulla</button>
                        </div>
                        <div class="col">
                            <button class="btn btn-danger w-100" id="confirmDelete">Elimina</button>
                        </div>
                    </div>
                </div>
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

/* Sistemazione layout per mettere info e paginazione sulla stessa riga */
.dataTables_wrapper .bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Stili per i pulsanti azioni */
.btn-icon.btn-sm {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-icon.btn-sm .icon {
    width: 18px;
    height: 18px;
}

.btn-list {
    gap: 0.25rem;
}

/* STILI SPECIFICI PER NOTIFICHE */
/* Stile personalizzato per le righe non lette */
.table-info {
    background-color: #e7f3ff !important;
}

/* Allineamento badge */
.badge .icon {
    stroke: white;
}

/* Stile per il messaggio */
.text-muted {
    max-width: 400px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    const table = $('#notifications-table').DataTable({
        processing: false,
        serverSide: false,
        ajax: {
            url: '/notifications',
            type: 'GET',
            dataSrc: 'data',
            error: function(xhr, error, thrown) {
                console.error('Error loading notifications:', error);
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `<input type="checkbox" class="form-check-input m-0 align-middle notification-checkbox" value="${row.id}">`;
                }
            },
            {
                data: 'data.type',
                render: function(data, type, row) {
                    const typeClass = data || 'info';
                    return `<span class="badge bg-${typeClass}">${window.notificationManager.getNotificationIcon(typeClass)}</span>`;
                }
            },
            {
                data: 'data.title',
                render: function(data, type, row) {
                    return `<strong>${escapeHtml(data)}</strong>`;
                }
            },
            {
                data: 'data.message',
                render: function(data, type, row) {
                    return `<div class="text-muted">${escapeHtml(data)}</div>`;
                }
            },
            {
                data: 'created_at',
                className: 'text-center',
                render: function(data, type, row) {
                    const date = new Date(data);
                    return date.toLocaleString('it-IT', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            },
            {
                data: 'read_at',
                className: 'text-center',
                render: function(data, type, row) {
                    return data ? 
                        '<span class="badge bg-secondary">Letta</span>' : 
                        '<span class="badge bg-primary">Non letta</span>';
                }
            },
            {
                data: null,
                className: 'text-center',
                orderable: false,
                render: function(data, type, row) {
                    let buttons = '<div class="btn-list justify-content-center">';
                    
                    // Pulsante visualizza
                    buttons += `
                        <button class="btn btn-icon btn-sm btn-outline-info" onclick='showNotificationModal(${JSON.stringify(row).replace(/'/g, "&apos;")})' data-bs-toggle="tooltip" data-bs-placement="top" title="Visualizza">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <circle cx="12" cy="12" r="2" />
                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                            </svg>
                        </button>`;
                    
                    if (!row.read_at) {
                        buttons += `
                            <button class="btn btn-icon btn-sm btn-outline-primary" onclick="markAsRead('${row.id}')" data-bs-toggle="tooltip" data-bs-placement="top" title="Segna come letta">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10" />
                                </svg>
                            </button>`;
                    }
                    
                    buttons += `
                        <button class="btn btn-icon btn-sm btn-outline-danger" onclick="deleteNotification('${row.id}')" data-bs-toggle="tooltip" data-bs-placement="top" title="Elimina">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="4" y1="7" x2="20" y2="7" />
                                <line x1="10" y1="11" x2="10" y2="17" />
                                <line x1="14" y1="11" x2="14" y2="17" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </button>
                    </div>`;
                    
                    return buttons;
                }
            }
        ],
        order: [[3, 'desc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/it-IT.json'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Tutte"]],
        responsive: true,
        createdRow: function(row, data, dataIndex) {
            if (!data.read_at) {
                $(row).addClass('table-info');
            }
        }
    });
    
    // Refresh automatico ogni 30 secondi
    setInterval(function() {
        table.ajax.reload(null, false);
    }, 30000);
    
    // Funzione per aggiornare la tabella dopo un'azione
    window.refreshNotificationsTable = function() {
        table.ajax.reload(null, false);
    };
    
    // Inizializza i tooltip dopo che la tabella è stata caricata
    table.on('draw', function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
        updateSelectedCount();
    });
    
    // Gestione checkbox "Seleziona tutto"
    $('#selectAll').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.notification-checkbox').prop('checked', isChecked);
        updateSelectedCount();
    });
    
    // Gestione checkbox singole
    $(document).on('change', '.notification-checkbox', function() {
        updateSelectedCount();
        // Aggiorna lo stato del checkbox "Seleziona tutto"
        const allChecked = $('.notification-checkbox').length === $('.notification-checkbox:checked').length;
        $('#selectAll').prop('checked', allChecked);
    });
});

async function markAsRead(notificationId) {
    await window.notificationManager.markAsRead(notificationId);
    window.refreshNotificationsTable();
}

let notificationToDelete = null;

function deleteNotification(notificationId) {
    notificationToDelete = notificationId;
    // Imposta il messaggio per eliminazione singola
    $('#deleteModalMessage').text('Vuoi davvero eliminare questa notifica? Questa azione non può essere annullata.');
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Handler per il pulsante di conferma eliminazione
$('#confirmDelete').on('click', async function() {
    if (notificationToDelete) {
        await window.notificationManager.deleteNotification(notificationToDelete);
        window.refreshNotificationsTable();
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        notificationToDelete = null;
    }
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

let currentNotificationId = null;

function showNotificationModal(notification) {
    currentNotificationId = notification.id;
    
    // Formatta la data
    const date = new Date(notification.created_at);
    const formattedDate = date.toLocaleString('it-IT', {
        day: '2-digit',
        month: '2-digit', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // Imposta il titolo del modal con badge tipo, titolo e data
    const typeClass = notification.data.type || 'info';
    const badgeHtml = `<span class="badge bg-${typeClass} me-2">${window.notificationManager.getNotificationIcon(typeClass)}</span>`;
    $('#modal-header-title').html(`${badgeHtml}${notification.data.title} - ${formattedDate}`);
    
    // Imposta solo il messaggio nel body
    $('#modal-message').text(notification.data.message);
    
    // Gestisci il pulsante "Segna come letta"
    if (notification.read_at) {
        $('#modal-mark-read').hide();
    } else {
        $('#modal-mark-read').show();
    }
    
    // Mostra il modal
    const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
    modal.show();
}

// Handler per il pulsante "Segna come letta" nel modal
$('#modal-mark-read').on('click', async function() {
    if (currentNotificationId) {
        await window.notificationManager.markAsRead(currentNotificationId);
        window.refreshNotificationsTable();
        bootstrap.Modal.getInstance(document.getElementById('notificationModal')).hide();
    }
});

// Funzione per aggiornare il conteggio delle notifiche selezionate
function updateSelectedCount() {
    const selectedCount = $('.notification-checkbox:checked').length;
    $('#selectedCount').text(selectedCount);
    
    if (selectedCount > 0) {
        $('#deleteSelectedBtn').show();
    } else {
        $('#deleteSelectedBtn').hide();
    }
}

// Funzione per eliminare le notifiche selezionate
function deleteSelectedNotifications() {
    const selectedIds = [];
    $('.notification-checkbox:checked').each(function() {
        selectedIds.push($(this).val());
    });
    
    if (selectedIds.length === 0) return;
    
    // Imposta il messaggio per eliminazione multipla
    const count = selectedIds.length;
    const message = count === 1 
        ? 'Vuoi davvero eliminare questa notifica? Questa azione non può essere annullata.'
        : `Vuoi davvero eliminare ${count} notifiche? Questa azione non può essere annullata.`;
    $('#deleteModalMessage').text(message);
    
    // Mostra il modal di conferma per eliminazione multipla
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
    
    // Modifica temporaneamente il comportamento del pulsante di conferma
    $('#confirmDelete').off('click').on('click', async function() {
        for (const id of selectedIds) {
            await window.notificationManager.deleteNotification(id);
        }
        window.refreshNotificationsTable();
        $('#selectAll').prop('checked', false);
        updateSelectedCount();
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        
        // Ripristina il comportamento normale del pulsante
        $('#confirmDelete').off('click').on('click', async function() {
            if (notificationToDelete) {
                await window.notificationManager.deleteNotification(notificationToDelete);
                window.refreshNotificationsTable();
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                notificationToDelete = null;
            }
        });
    });
}
</script>
@endpush
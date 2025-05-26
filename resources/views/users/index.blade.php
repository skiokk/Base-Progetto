@extends('layouts.app')

@section('title', 'Gestione Utenti')
@section('page-title', 'Gestione Utenti')

@section('content')
<div class="row row-cards">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Elenco Utenti</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal" onclick="openAddModal()">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
          Aggiungi Utente
        </button>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="users-table" class="table table-vcenter card-table table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th class="text-center">Data Creazione</th>
                <th class="text-center">Azioni</th>
              </tr>
            </thead>
            <tbody>
              <!-- I dati verranno caricati tramite AJAX -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal per Aggiungi/Modifica Utente -->
<div class="modal modal-blur fade" id="userModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Aggiungi Utente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="userForm">
        @csrf
        <input type="hidden" id="userId" name="id">
        <input type="hidden" id="method" name="_method" value="POST">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" id="userName" name="name" required>
            <div class="invalid-feedback"></div>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="userEmail" name="email" required>
            <div class="invalid-feedback"></div>
          </div>
          <div class="mb-3" id="passwordGroup">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" id="userPassword" name="password">
            <div class="invalid-feedback"></div>
            <small class="form-hint">Lascia vuoto per mantenere la password attuale</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Annulla</button>
          <button type="submit" class="btn btn-primary">Salva</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal per Conferma Eliminazione -->
<div class="modal modal-blur fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body text-center py-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
          <path d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z"/>
          <path d="M12 9v4"/>
          <path d="M12 17h.01"/>
        </svg>
        <h3>Sei sicuro?</h3>
        <div class="text-secondary">Vuoi davvero eliminare questo utente? Questa azione non può essere annullata.</div>
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

<!-- Toast Container -->
<div id="toast-container"></div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
<style>
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
    padding-top: 0 !important;  /* Rimuove il padding top predefinito */
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
  
  /* Hover effect per i pulsanti */
  .btn-outline-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(70, 127, 208, 0.25);
  }
  
  .btn-outline-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(214, 57, 57, 0.25);
  }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
  let usersTable;
  let deleteUserId;
  
  $(document).ready(function() {
    // Inizializza DataTable
    usersTable = $('#users-table').DataTable({
      processing: true,
      serverSide: false,
      ajax: "{{ route('users.data') }}",
      columns: [
        { data: 'id', name: 'id' },
        { data: 'name', name: 'name' },
        { data: 'email', name: 'email' },
        { 
          data: 'created_at', 
          name: 'created_at',
          className: 'text-center',
          render: function(data) {
            return new Date(data).toLocaleString('it-IT');
          }
        },
        {
          data: null,
          orderable: false,
          searchable: false,
          className: 'text-center',
          render: function(data, type, row) {
            return `
              <div class="btn-list justify-content-center">
                <button class="btn btn-icon btn-sm btn-outline-primary" onclick="openEditModal(${row.id})" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifica">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"/>
                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"/>
                    <path d="M16 5l3 3"/>
                  </svg>
                </button>
                <button class="btn btn-icon btn-sm btn-outline-danger" onclick="openDeleteModal(${row.id})" data-bs-toggle="tooltip" data-bs-placement="top" title="Elimina">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <line x1="4" y1="7" x2="20" y2="7"/>
                    <line x1="10" y1="11" x2="10" y2="17"/>
                    <line x1="14" y1="11" x2="14" y2="17"/>
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"/>
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"/>
                  </svg>
                </button>
              </div>
            `;
          }
        }
      ],
      language: {
        url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/it-IT.json'
      },
      dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rt<"bottom"ip>',
      drawCallback: function() {
        // Reinizializza i tooltip dopo ogni draw
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
      }
    });
    
    // Submit form
    $('#userForm').on('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      const userId = $('#userId').val();
      const url = userId ? `/users/${userId}` : '/users';
      const method = userId ? 'PUT' : 'POST';
      
      // Reset validation errors
      $('.form-control').removeClass('is-invalid');
      $('.invalid-feedback').text('');
      
      $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
          'X-HTTP-Method-Override': method
        },
        success: function(response) {
          $('#userModal').modal('hide');
          usersTable.ajax.reload();
          showToast('success', response.message || 'Operazione completata con successo');
        },
        error: function(xhr) {
          if (xhr.status === 422) {
            // Validation errors
            const errors = xhr.responseJSON.errors;
            for (let field in errors) {
              $(`#user${field.charAt(0).toUpperCase() + field.slice(1)}`).addClass('is-invalid');
              $(`#user${field.charAt(0).toUpperCase() + field.slice(1)}`).next('.invalid-feedback').text(errors[field][0]);
            }
          } else {
            showToast('error', xhr.responseJSON?.message || 'Si è verificato un errore');
          }
        }
      });
    });
    
    // Confirm delete
    $('#confirmDelete').on('click', function() {
      $.ajax({
        url: `/users/${deleteUserId}`,
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          $('#deleteModal').modal('hide');
          usersTable.ajax.reload();
          showToast('success', response.message || 'Utente eliminato con successo');
        },
        error: function(xhr) {
          showToast('error', xhr.responseJSON?.message || 'Si è verificato un errore durante l\'eliminazione');
        }
      });
    });
  });
  
  // Open add modal
  function openAddModal() {
    $('#modalTitle').text('Aggiungi Utente');
    $('#userForm')[0].reset();
    $('#userId').val('');
    $('#method').val('POST');
    $('#passwordGroup small').hide();
    $('#userPassword').attr('required', true);
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
  }
  
  // Open edit modal
  function openEditModal(id) {
    $('#modalTitle').text('Modifica Utente');
    $('#userId').val(id);
    $('#method').val('PUT');
    $('#passwordGroup small').show();
    $('#userPassword').attr('required', false);
    $('.form-control').removeClass('is-invalid');
    $('.invalid-feedback').text('');
    
    // Load user data
    $.get(`/users/${id}`, function(response) {
      $('#userName').val(response.data.name);
      $('#userEmail').val(response.data.email);
      $('#userPassword').val('');
      $('#userModal').modal('show');
    });
  }
  
  // Open delete modal
  function openDeleteModal(id) {
    deleteUserId = id;
    $('#deleteModal').modal('show');
  }
  
  // Show toast notification
  function showToast(type, message) {
    const toastContainer = document.getElementById('toast-container');
    if (!toastContainer) return;

    // Map type to correct class
    const typeMap = {
      'success': 'success',
      'error': 'error',
      'danger': 'error'
    };
    const toastType = typeMap[type] || 'info';

    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${toastType}`;
    toast.innerHTML = `
      <div class="toast-header">
        <strong>${type === 'success' ? 'Successo' : 'Errore'}</strong>
        <button class="toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
      </div>
      <div class="toast-body">
        ${escapeHtml(message)}
      </div>
    `;

    toastContainer.appendChild(toast);

    setTimeout(() => {
      toast.classList.add('show');
    }, 100);

    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 300);
    }, 5000);
  }
  
  // Escape HTML helper
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
</script>
@endpush
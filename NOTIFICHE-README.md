# Sistema di Notifiche Real-time con Laravel Reverb

Questo progetto implementa un sistema completo di notifiche push real-time utilizzando **Laravel Reverb**, una soluzione WebSocket self-hosted che non richiede servizi esterni.

## Caratteristiche Principali

- **🚀 Real-time**: Notifiche istantanee tramite WebSocket
- **💾 Persistenti**: Salvate nel database per storico e consultazione
- **👥 Multi-utente**: Invio a singoli, gruppi o broadcast
- **🎨 UI Moderna**: Toast notifications con Tabler UI
- **🔔 Browser Notifications**: Supporto notifiche native del browser
- **📊 Dashboard**: Gestione completa delle notifiche
- **🔒 Sicuro**: Canali privati per utente con autenticazione

## Installazione e Configurazione

### 1. Configurazione Environment

Le variabili d'ambiente sono già configurate in `.env`:

```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=123456
REVERB_APP_KEY=laravelreverb
REVERB_APP_SECRET=secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

### 2. Avvio del Sistema

#### Metodo 1: Script automatico
```bash
./start-reverb.sh
```

#### Metodo 2: Comando manuale
```bash
php artisan reverb:start --debug
```

Il server WebSocket sarà disponibile su `http://localhost:8080`

### 3. Verifica Funzionamento

1. Accedi all'applicazione con le credenziali di test
2. Apri la console del browser (F12)
3. Dovresti vedere: "Echo connected successfully"
4. Vai alla pagina "Test Notifiche" per inviare una notifica di prova

## Utilizzo

### Invio Notifiche via UI

1. **Menu laterale** → **Test Notifiche**
2. Seleziona i destinatari:
   - Solo a me stesso
   - Tutti gli utenti
   - Utenti specifici (selezione multipla)
3. Inserisci titolo e messaggio
4. Clicca "Invia Notifica"

### Invio Notifiche via Codice

```php
// Notifica singolo utente
$user->notify(new GeneralNotification(
    'Titolo notifica',
    'Messaggio della notifica'
));

// Notifica multipla
Notification::send($users, new GeneralNotification(
    'Titolo',
    'Messaggio'
));
```

### API Endpoints

| Metodo | Endpoint | Descrizione |
|--------|----------|-------------|
| GET | `/notifications` | Lista notifiche utente corrente |
| POST | `/notifications/send` | Invia a utente singolo |
| POST | `/notifications/send-to-all` | Broadcast a tutti |
| POST | `/notifications/send-to-users` | Invia a utenti multipli |
| POST | `/notifications/mark-read/{id}` | Segna come letta |
| POST | `/notifications/mark-all-read` | Segna tutte come lette |
| GET | `/notifications/test` | Pagina test notifiche |

## Architettura Tecnica

### Stack Tecnologico

- **Backend**: Laravel 11 + Reverb WebSocket Server
- **Frontend**: Laravel Echo + Pusher JS (compatibile Reverb)
- **Database**: SQLite (configurabile per MySQL/PostgreSQL)
- **UI**: Tabler CSS Framework

### Flusso Dati

1. **Invio**: Controller → Notification → Database + Broadcast
2. **Ricezione**: WebSocket → Laravel Echo → JavaScript → UI Update
3. **Persistenza**: Tutte le notifiche vengono salvate in `notifications` table

### Struttura File

```
app/
├── Notifications/
│   └── GeneralNotification.php    # Classe notifica base
├── Http/Controllers/
│   └── NotificationController.php  # Gestione notifiche
└── Models/
    └── Notification.php           # Modello Eloquent

resources/
├── js/
│   └── notifications.js           # Client-side logic
├── css/
│   └── notifications.css          # Stili custom
└── views/notifications/
    ├── index.blade.php           # Dashboard notifiche
    └── test.blade.php            # Pagina test

config/
├── broadcasting.php              # Config canali
└── reverb.php                   # Config WebSocket
```

## Troubleshooting

### Le notifiche non arrivano?

1. **Verifica Reverb sia attivo**:
   ```bash
   ps aux | grep reverb
   ```

2. **Controlla i log**:
   ```bash
   tail -f storage/logs/reverb.log
   ```

3. **Console browser**: Cerca errori WebSocket

### Errori comuni

- **"WebSocket connection failed"**: Porta 8080 bloccata o Reverb non attivo
- **"Broadcasting auth failed"**: Utente non autenticato
- **"No notifications received"**: Verifica i permessi del canale

### Debug Mode

Per debug dettagliato:
```bash
php artisan reverb:start --debug --port=8080
```

## Produzione

### Deployment con Supervisor

Crea `/etc/supervisor/conf.d/reverb.conf`:

```ini
[program:reverb]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan reverb:start --port=8080
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/reverb.log
```

### Configurazione Nginx

Per WebSocket proxy:

```nginx
location /app {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
}
```

### SSL/HTTPS

Per HTTPS, modifica in `.env`:
```env
REVERB_SCHEME=https
REVERB_PORT=443
```

## Estensioni Future

- [ ] Notifiche email oltre che real-time
- [ ] Notifiche SMS
- [ ] Scheduling notifiche
- [ ] Template notifiche personalizzabili
- [ ] Analytics e statistiche
- [ ] Notifiche con azioni (bottoni)
- [ ] Raggruppamento notifiche simili

## Note Tecniche

- Il sistema usa broadcasting privati per sicurezza
- Ogni utente ha il proprio canale: `notifications.{userId}`
- Le notifiche sono soft-delete per mantenere lo storico
- Il badge contatore si aggiorna automaticamente
- Supporta markdown nel contenuto delle notifiche
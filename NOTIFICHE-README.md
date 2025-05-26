# Sistema di Notifiche Push Self-Hosted

Questo progetto utilizza **Laravel Reverb** per le notifiche push real-time, una soluzione completamente self-hosted che non richiede servizi esterni.

## Come avviare il sistema

### 1. Avviare il server WebSocket Reverb

In un terminale separato, esegui:

```bash
./start-reverb.sh
```

O manualmente:

```bash
php artisan reverb:start --debug
```

Il server WebSocket sarà disponibile su `http://localhost:8080`

### 2. Testare le notifiche

1. Accedi all'applicazione
2. Vai su **Test Notifiche** nel menu laterale
3. Seleziona i destinatari:
   - **Solo me stesso**: invia a te stesso
   - **Tutti gli utenti**: invia a tutti gli utenti registrati
   - **Utenti specifici**: seleziona dalla lista
4. Compila il form e clicca "Invia Notifica"

### Caratteristiche

- **Real-time**: Le notifiche appaiono istantaneamente grazie a WebSocket
- **Persistenza**: Salvate nel database per consultazione futura
- **Multi-destinatario**: Invia a singoli utenti, gruppi o tutti
- **Toast notifications**: Popup eleganti che appaiono in alto a destra
- **Browser notifications**: Notifiche native del browser (richiede permesso)
- **Badge contatore**: Numero di notifiche non lette sulla campanella

### Configurazione

Il sistema è già configurato e pronto all'uso. Le impostazioni principali sono in:

- `.env`: Configurazione Reverb (REVERB_*)
- `config/reverb.php`: Configurazione del server WebSocket
- `config/broadcasting.php`: Configurazione broadcasting

### Troubleshooting

**Le notifiche non arrivano in tempo reale?**
- Assicurati che il server Reverb sia in esecuzione
- Controlla la console del browser per errori WebSocket
- Verifica che la porta 8080 non sia bloccata

**Errore di connessione WebSocket?**
- Se usi HTTPS, potrebbe essere necessario configurare SSL per Reverb
- Controlla le impostazioni del firewall

### Architettura

1. **Backend**: Laravel con Reverb WebSocket server
2. **Frontend**: Laravel Echo + Pusher JS (compatibile con Reverb)
3. **Database**: SQLite per memorizzare le notifiche
4. **Broadcasting**: Canali privati per utente per sicurezza

### API Endpoints

- `GET /notifications` - Lista notifiche dell'utente
- `POST /notifications/send` - Invia a singolo utente
- `POST /notifications/send-to-all` - Invia a tutti
- `POST /notifications/send-to-users` - Invia a utenti multipli
- `POST /notifications/mark-read/{id}` - Segna come letta
- `POST /notifications/mark-all-read` - Segna tutte come lette

### Sviluppo

Per aggiungere nuovi tipi di notifiche:

1. Crea una nuova classe notifica: `php artisan make:notification NomeNotifica`
2. Implementa i metodi `via()`, `toArray()` e `toBroadcast()`
3. Usa la notifica: `$user->notify(new NomeNotifica($dati))`
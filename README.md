# Base Progetto Laravel

Questo è un progetto base Laravel con sistema di autenticazione e notifiche real-time già configurato.

## Caratteristiche

- **Laravel 11** - L'ultima versione del framework PHP
- **Sistema di Autenticazione** - Login/Logout già implementato
- **Notifiche Real-time** - Sistema di notifiche tramite Laravel Reverb (WebSocket)
- **UI con Tabler** - Framework CSS moderno e responsive
- **Database SQLite** - Per sviluppo rapido (configurabile con MySQL/PostgreSQL)
- **Gestione Utenti** - CRUD base per la gestione utenti

## Requisiti

- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite (o altro database supportato)

## Installazione

1. Clona il repository:
```bash
git clone https://github.com/skiokk/Base-Progetto.git
cd Base-Progetto
```

2. Installa le dipendenze PHP:
```bash
composer install
```

3. Installa le dipendenze JavaScript:
```bash
npm install
```

4. Copia il file di configurazione:
```bash
cp .env.example .env
```

5. Genera la chiave dell'applicazione:
```bash
php artisan key:generate
```

6. Esegui le migrazioni e i seeder:
```bash
php artisan migrate --seed
```

7. Compila gli asset:
```bash
npm run build
```

## Avvio dell'Applicazione

Per avviare l'applicazione in sviluppo, esegui questi comandi in terminali separati:

1. Server Laravel:
```bash
php artisan serve
```

2. Server WebSocket (Reverb) per le notifiche:
```bash
php artisan reverb:start
```

3. Compilazione asset in watch mode (opzionale):
```bash
npm run dev
```

## Credenziali di Accesso

L'applicazione viene pre-popolata con un utente amministratore:

- **Email**: admin@example.com
- **Password**: password

## Struttura del Progetto

```
app/
├── Http/Controllers/       # Controller dell'applicazione
├── Models/                 # Modelli Eloquent
├── Notifications/          # Classi per le notifiche
└── Providers/             # Service Provider

resources/
├── css/                   # File CSS
├── js/                    # File JavaScript
└── views/                 # Template Blade

database/
├── migrations/            # Migrazioni database
└── seeders/              # Seeder per dati di test

routes/
├── web.php               # Route web
└── channels.php          # Canali broadcast
```

## Funzionalità Principali

### Sistema di Autenticazione
- Login/Logout
- Protezione route con middleware
- Gestione sessioni

### Sistema di Notifiche
- Notifiche real-time via WebSocket
- API per inviare notifiche programmaticamente
- Dashboard per visualizzare le notifiche

### Gestione Utenti
- Lista utenti
- Creazione/modifica/eliminazione utenti
- Ruoli e permessi (da implementare)

## Deployment

Per il deployment in produzione:

1. Configura le variabili d'ambiente nel file `.env`
2. Esegui le ottimizzazioni:
```bash
composer install --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Configura il web server (Nginx/Apache)
4. Configura il processo Reverb come servizio

## Note

- Gli asset Tabler sono già compilati nella cartella `public/dist/`
- In produzione non sono necessari file aggiuntivi oltre al progetto Laravel

## Licenza

Questo progetto è basato su Laravel, che è un software open-source con licenza [MIT](https://opensource.org/licenses/MIT).
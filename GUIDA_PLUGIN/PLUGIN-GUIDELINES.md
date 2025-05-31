# Linee Guida Sistema Plugin

## 🎯 Principi Fondamentali

### 1. **Isolamento**
Ogni plugin deve operare in modo indipendente senza interferire con altri plugin o il core.

### 2. **Estensibilità**
I plugin devono estendere funzionalità, non modificare il comportamento core.

### 3. **Retrocompatibilità**
Le modifiche al sistema plugin devono mantenere compatibilità con plugin esistenti.

### 4. **Performance First**
I plugin non devono degradare significativamente le performance dell'applicazione.

## 📏 Standard di Codice

### Naming Convention

#### Plugin Name
- **Formato**: `kebab-case`
- **Esempi**: `whatsapp-notifications`, `stripe-payments`, `user-analytics`
- **Evitare**: Underscore, spazi, caratteri speciali

#### Namespace PHP
- **Formato**: `PascalCase`
- **Pattern**: `VendorName\PluginName`
- **Esempio**: `Acme\WhatsappNotifications`

#### Tabelle Database
- **Prefisso**: Nome plugin + underscore
- **Esempio**: `whatsapp_messages`, `whatsapp_settings`

#### Config Keys
- **Formato**: `plugin-name.key`
- **Esempio**: `whatsapp-notifications.api_key`

### Struttura Codice

#### Single Responsibility
```php
// ❌ ERRATO - Troppe responsabilità
class WhatsappPlugin {
    public function sendMessage() { }
    public function parseWebhook() { }
    public function renderSettings() { }
    public function validateLicense() { }
}

// ✅ CORRETTO - Responsabilità separate
class WhatsappPlugin { }
class WhatsappService { }
class WhatsappWebhookHandler { }
class WhatsappSettingsController { }
```

#### Dependency Injection
```php
// ❌ ERRATO - Dipendenze hard-coded
class MyService {
    public function process() {
        $logger = new Logger();
        $cache = new Cache();
    }
}

// ✅ CORRETTO - Dependency injection
class MyService {
    public function __construct(
        protected Logger $logger,
        protected Cache $cache
    ) {}
}
```

## 🔒 Sicurezza

### 1. Validazione Input
```php
// Sempre validare input utente
$validated = $request->validate([
    'email' => 'required|email|max:255',
    'message' => 'required|string|max:1000',
    'webhook_url' => 'required|url|active_url',
]);
```

### 2. Autorizzazione
```php
// Verificare sempre i permessi
public function settings() {
    $this->authorize('manage-plugin-settings');
    // oppure
    if (!auth()->user()->can('configure-whatsapp')) {
        abort(403);
    }
}
```

### 3. Sanitizzazione Output
```php
// Escape output HTML
{{ e($userInput) }}
{!! clean($htmlContent) !!}

// Query sicure
$users = DB::table('users')
    ->where('plugin_id', $pluginId)
    ->where('status', $status)
    ->get();
```

### 4. File Security
```php
// Validare upload
$request->validate([
    'file' => 'required|file|mimes:pdf,doc|max:2048'
]);

// Storage sicuro
$path = $request->file->store('plugins/documents', 'private');
```

## ⚡ Performance

### 1. Caching Strategy
```php
// Cache dati costosi
public function getExpensiveData() {
    return Cache::remember('plugin.expensive.data', 3600, function () {
        return $this->calculateExpensiveOperation();
    });
}

// Cache invalidation
public function updateData($data) {
    $this->repository->update($data);
    Cache::forget('plugin.expensive.data');
}
```

### 2. Query Optimization
```php
// ❌ ERRATO - N+1 queries
foreach ($users as $user) {
    $orders = $user->orders()->get();
}

// ✅ CORRETTO - Eager loading
$users = User::with('orders')->get();
```

### 3. Lazy Loading
```php
public function boot() {
    // Carica solo se necessario
    if ($this->shouldLoadAdminFeatures()) {
        $this->loadAdminRoutes();
        $this->loadAdminViews();
    }
}
```

### 4. Asset Optimization
```php
// Registra asset solo dove servono
public function registerAssets() {
    // Solo in pagine specifiche
    if (request()->is('admin/plugin/*')) {
        Asset::add('plugin-admin-css', 'plugins/mio/admin.css');
        Asset::add('plugin-admin-js', 'plugins/mio/admin.js');
    }
}
```

## 🧪 Testing Requirements

### Test Coverage Minimo
- **Unit Tests**: 80% coverage
- **Feature Tests**: Tutti gli endpoint principali
- **Integration Tests**: Hook e interazioni core

### Test Structure
```
tests/
├── Unit/
│   ├── Services/
│   ├── Models/
│   └── Helpers/
├── Feature/
│   ├── Api/
│   ├── Web/
│   └── Console/
└── Integration/
    ├── Hooks/
    └── Events/
```

### Test Example
```php
class PluginTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->enablePlugin('mio-plugin');
    }
    
    public function test_plugin_loads_correctly()
    {
        $this->assertTrue(PluginManager::isLoaded('mio-plugin'));
    }
    
    public function tearDown(): void
    {
        $this->disablePlugin('mio-plugin');
        parent::tearDown();
    }
}
```

## 📚 Documentazione

### README.md Minimo
```markdown
# Nome Plugin

## Installazione
[Istruzioni step-by-step]

## Configurazione
[Parametri e opzioni]

## Uso
[Esempi pratici]

## API Reference
[Se applicabile]

## Changelog
[Versioni e modifiche]

## Support
[Come ottenere aiuto]
```

### Inline Documentation
```php
/**
 * Processa il webhook di WhatsApp
 * 
 * @param Request $request Request contenente il payload del webhook
 * @return JsonResponse
 * @throws WebhookException Se il webhook non è valido
 */
public function handleWebhook(Request $request): JsonResponse
{
    // Implementazione
}
```

## 🔄 Versioning

### Semantic Versioning
- **MAJOR.MINOR.PATCH** (es: 2.1.3)
- **MAJOR**: Breaking changes
- **MINOR**: Nuove features retrocompatibili
- **PATCH**: Bug fixes

### Changelog Format
```markdown
## [2.1.0] - 2024-01-15
### Added
- Supporto per WhatsApp Business API v2
- Nuova opzione per template messaggi

### Fixed
- Risolto bug timeout su invii massivi
- Corretta validazione numeri internazionali

### Changed
- Migliorata performance del 30%
- Aggiornate dipendenze
```

## ⚠️ Cose da Evitare

### 1. **MAI Modificare Core Files**
```php
// ❌ MAI fare questo
// Modifica in app/Http/Controllers/Controller.php
```

### 2. **MAI Usare Exit/Die**
```php
// ❌ ERRATO
if (!$valid) {
    die('Invalid request');
}

// ✅ CORRETTO
if (!$valid) {
    throw new ValidationException('Invalid request');
}
```

### 3. **MAI Hardcode Credenziali**
```php
// ❌ ERRATO
$apiKey = 'sk_test_1234567890';

// ✅ CORRETTO
$apiKey = config('mio-plugin.api_key');
```

### 4. **MAI Ignorare Errori**
```php
// ❌ ERRATO
try {
    $this->riskyOperation();
} catch (\Exception $e) {
    // Ignora silenziosamente
}

// ✅ CORRETTO
try {
    $this->riskyOperation();
} catch (\Exception $e) {
    Log::error('Plugin error: ' . $e->getMessage());
    throw new PluginException('Operation failed', 0, $e);
}
```

## 🏆 Best Practices Checklist

### Prima del Release
- [ ] Tutti i test passano
- [ ] Nessun `dd()`, `dump()`, `console.log()`
- [ ] Nessuna credenziale hardcoded
- [ ] README.md completo
- [ ] CHANGELOG.md aggiornato
- [ ] Licenza inclusa
- [ ] Dipendenze minime necessarie
- [ ] Compatible con ultime 2 versioni Laravel
- [ ] Testato su PHP 8.1+
- [ ] Asset minificati
- [ ] Cache pulita
- [ ] Migration reversibili
- [ ] Documentazione API (se presente)
- [ ] Screenshot/demo (se UI)
- [ ] Istruzioni disinstallazione

### Code Review Checklist
- [ ] Segue PSR-12
- [ ] Nomi variabili significativi
- [ ] Commenti dove necessario
- [ ] DRY (Don't Repeat Yourself)
- [ ] SOLID principles
- [ ] Error handling appropriato
- [ ] Logging significativo
- [ ] Nessun TODO dimenticato
- [ ] Type hints ovunque possibile
- [ ] Return types specificati

## 🤝 Contribuire al Core

### Proporre Nuovi Hook
1. Apri issue su GitHub
2. Descrivi use case
3. Proponi implementazione
4. Attendi feedback

### Esempio Proposta
```markdown
## Nuovo Hook Richiesto: `payment.processed`

### Use Case
Necessito di eseguire azioni dopo che un pagamento è completato.

### Implementazione Proposta
```php
// In PaymentController dopo successo
app('plugins')->doAction('payment.processed', $payment, $user);
```

### Plugin che ne beneficerebbero
- Invoice generator
- Email notifications
- Analytics
- Loyalty points
```

## 📞 Supporto e Community

### Canali Ufficiali
- **Documentazione**: `/docs/plugins`
- **Forum**: `community.example.com`
- **Discord**: `discord.gg/example`
- **GitHub Issues**: Per bug e feature requests

### Getting Help
1. Controlla documentazione
2. Cerca nel forum
3. Chiedi su Discord
4. Apri issue su GitHub

### Contributing Back
- Condividi i tuoi plugin
- Scrivi tutorial
- Aiuta altri sviluppatori
- Segnala e fix bug
- Suggerisci miglioramenti
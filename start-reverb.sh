#!/bin/bash
# Script per avviare il server Reverb WebSocket

echo "Avvio del server Reverb WebSocket..."
echo "Il server sarà disponibile su http://localhost:8080"
echo "Premi Ctrl+C per fermare il server"
echo ""

php artisan reverb:start --debug
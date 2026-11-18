import { Controller } from '@hotwired/stimulus';
import Sortable from 'sortablejs';

export default class extends Controller {
    static targets = ['list']; // 'item' brauchen wir hier eigentlich nicht, 'list' reicht
    static values = {
        updateUrl: String
    };

    connect() {
        // Sicherer Fallback: Nimm listTarget, oder falls das fehlt, einfach das tbody im Controller-Element
        const targetElement = this.hasListTarget ? this.listTarget : this.element.querySelector('tbody');

        if (!targetElement) {
            console.error('Sortable: Weder listTarget noch tbody gefunden!');
            return;
        }

        this.sortable = Sortable.create(targetElement, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            onEnd: this.onEnd.bind(this)
        });
    }

    async onEnd(event) {
        // KORREKTUR 1: Wenn sich visuell nichts geändert hat, nichts tun!
        // Das verhindert, dass Gedmo bei versehentlichem "Droppen" am gleichen Platz verwirrt wird.
        if (event.oldIndex === event.newIndex) {
            console.log("Keine Änderung, Abbruch.");
            return;
        }

        const itemId = event.item.dataset.id;

        // KORREKTUR 2: Die Position berechnen, statt blind "+1" zu machen.
        // EasyAdmin rendert manchmal unsichtbare Zeilen. Wir zählen NUR die Zeilen mit einer ID.
        // indexOf liefert uns garantiert einen sauberen 0-basierten Index (0, 1, 2, 3...)
        const allDataRows = Array.from(this.element.querySelectorAll('tr[data-id]'));
        const correctPosition = allDataRows.indexOf(event.item);

        console.log("Sende an Backend -> ID: ", itemId, " | Neue POS: ", correctPosition);

        try {
            const response = await fetch(this.updateUrlValue, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    id: itemId,
                    position: correctPosition // <-- Hier liegt der Hund begraben: Kein "+1" mehr!
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                console.log("PRIMA! Erfolgreich gespeichert.");
                event.item.classList.add('position-saved');
                setTimeout(() => event.item.classList.remove('position-saved'), 1000);
            } else {
                console.error("Backend Fehler:", result);
                alert('Fehler: ' + (result.error || 'Unbekannter Fehler'));
                window.location.reload();
            }
        } catch (error) {
            console.error('Netzwerk-Fehler:', error);
            alert('Die Position konnte nicht gespeichert werden.');
            window.location.reload();
            // Wichtig: Sortable intern zurücksetzen, damit es nicht blockiert
            this.sortable.sort(this.sortable.toArray(), true);
        }
    }
}
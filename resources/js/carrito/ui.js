import { CarritoStore } from './store.js';
import { lockSeat, checkout, fetchLocks, syncCarrito } from './api.js';
import { requireLogin } from './auth.js';

let dropdownAbierto = false;

function toggleDropdown() {
    const el = document.getElementById('dropdown-carrito');
    if (!el) return;

    dropdownAbierto = !dropdownAbierto;
    el.style.display = dropdownAbierto ? 'block' : 'none';
}
function startLoading() {
    const overlay = document.getElementById('loadingOverlay');
    const bar = document.getElementById('progressBar');

    if (!overlay || !bar) return;

    overlay.style.display = 'flex';

    let progress = 0;

    overlay._interval = setInterval(() => {
        progress += 2;
        bar.style.width = progress + '%';

        if (progress >= 100) {
            clearInterval(overlay._interval);
        }
    }, 50);
}

function stopLoading() {
    const overlay = document.getElementById('loadingOverlay');
    const bar = document.getElementById('progressBar');

    if (!overlay) return;

    clearInterval(overlay._interval);

    overlay.style.display = 'none';
    bar.style.width = '0%';
}
function getEventoId() {
    const id = document.body.dataset.eventoId;
    
    if (!id || id === "null") {
        return null;
    }

    return id;
}
function applySeatState(data) {
    const locked = new Set(data.locked.map(Number));
    const sold = new Set(data.sold.map(Number));
    const mySet = new Set(CarritoStore.get().map(i => Number(i.asiento_id)));
    document.querySelectorAll('.asiento-btn').forEach(btn => {
        const id = Number(btn.dataset.id);

        btn.classList.remove('seat--free', 'seat--locked', 'seat--sold', 'seat--mine');
        btn.disabled = false;

        if (sold.has(id)) {
            btn.classList.add('seat--sold');
            btn.disabled = true;
            return;
        }

	if ( mySet.has(id)) {
                btn.classList.add('seat--mine');
                return;
        }



        if (locked.has(id)) {
            btn.classList.add('seat--locked');
            btn.disabled = true;
            return;
        }


        btn.classList.add('seat--free');
    });
}

function showTooltip(btn) {
    const tooltip = document.createElement('div');

    tooltip.className = 'seat-tooltip';
    tooltip.innerHTML = `
        <strong>Sector:</strong> ${btn.dataset.sector}<br>
        <strong>Fila:</strong> ${btn.dataset.fila}<br>
        <strong>Asiento:</strong> ${btn.dataset.numero}<br>
        <strong>Precio:</strong> ${btn.dataset.precio} €
    `;

    document.body.appendChild(tooltip);

    const rect = btn.getBoundingClientRect();

    tooltip.style.position = 'fixed';
    tooltip.style.left = rect.left + 'px';
    tooltip.style.top = (rect.top - 80) + 'px';

    btn._tooltip = tooltip;
}

function hideTooltip(btn) {
    if (btn._tooltip) {
        btn._tooltip.remove();
        btn._tooltip = null;
    }
}

document.addEventListener('mouseover', (e) => {
    const seat = e.target.closest('.asiento-btn');
    if (!seat) return;
    showTooltip(seat);
});

document.addEventListener('mouseout', (e) => {
    const seat = e.target.closest('.asiento-btn');
    if (!seat) return;
    hideTooltip(seat);
});
async function fetchSeatState(eventoId) {


    const res = await fetch(`/api/seat/state/${eventoId}`);

    return await res.json();
}

function render() {
    const carrito = CarritoStore.get() || [];

    const contenedor = document.getElementById('carrito-items');
    const totalEl = document.getElementById('carrito-total');
    const contador = document.getElementById('contador-carrito');

    if (!contenedor) return;

    contenedor.innerHTML = '';

    let total = 0;

    carrito.forEach(item => {
        total += Number(item.precio);

        contenedor.innerHTML += `
            <div class="py-2 border-bottom">
                <strong>${item.sector}</strong><br>
                Fila ${item.fila} - ${item.numero}
                <div>${item.precio} €</div>
                <button class="btn btn-sm btn-danger btn-remove" data-id="${item.asiento_id}">
                    Eliminar
                </button>
            </div>
        `;
    });

    if (totalEl) totalEl.textContent = total.toFixed(2) + ' €';
    if (contador) contador.textContent = carrito.length;
}

async function pintarLocks() {
    const locks = await fetchLocks();

    const lockedSet = new Set(locks.map(Number));
    const mySet = new Set(CarritoStore.get().map(i => Number(i.asiento_id)));

    document.querySelectorAll('.asiento-btn').forEach(btn => {
        const id = Number(btn.dataset.id);

        // no tocar backend estados
        btn.classList.remove('btn-success');

        if (mySet.has(id)) {
            btn.classList.add('btn-success'); // solo selección usuario
        }
    });
}
async function startRealtimeSeats() {
    const eventoId = getEventoId();

    if (!eventoId) {
            console.warn("No eventoId, realtime desactivado");

        return;
    }

    async function update() {
        try {
            const data = await fetchSeatState(eventoId);
            applySeatState(data);
        } catch (e) {
            console.error("seat state error", e);
        }
    }

    update();
    setInterval(update, 5000);
}
document.addEventListener('click', async (e) => {

    // ========================
    // CLICK ASIENTO
    // ========================


    const seat = e.target.closest('.asiento-btn');
    if (seat) {
	e.preventDefault();
        if (!(await requireLogin())) return;

        const id = seat.dataset.id;

        const carrito = CarritoStore.get();
        const exists = carrito.some(i => String(i.asiento_id) === String(id));

        const item = {
            asiento_id: id,
            sector: seat.dataset.sector,
            fila: seat.dataset.fila,
            numero: seat.dataset.numero,
            precio: seat.dataset.precio
        };

        try {
            if (exists) {
                CarritoStore.remove(id);
            } else {
                await lockSeat(id, getEventoId());
                CarritoStore.add(item);
            }
            const data = await fetchSeatState(getEventoId());
	    applySeatState(data);
//            await pintarLocks();
            render();

        } catch (err) {
            alert(err.message);
        }

        return;
    }

    // ========================
    // ELIMINAR
    // ========================
    const remove = e.target.closest('.btn-remove');
    if (remove) {
        const id = remove.dataset.id;

        CarritoStore.remove(id);
	const data = await fetchSeatState(getEventoId());
applySeatState(data);
  //      await pintarLocks();
        render();
        return;
    }

    // ========================
    // COMPRAR
    // ========================
    const buy = e.target.closest('#btn-comprar');
    if (!buy) return;

    if (!requireLogin()) return;
    startLoading();
    try {
        const carrito = CarritoStore.get();
        const eventoId = getEventoId();

        const result = await checkout(eventoId, carrito);
        stopLoading();
        alert('Compra OK');

        CarritoStore.set([]);

        await fetch('/api/seat/unlock-all', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
	const data = await fetchSeatState(getEventoId());
applySeatState(data);
    //    await pintarLocks();
        render();

        window.location.href = '/mis-entradas';

    } catch (err) {
	stopLoading();
        alert(err.message || 'Error checkout');
    }
});

document.addEventListener('DOMContentLoaded', async () => {
    render();
    await pintarLocks();

    const eventoId = getEventoId();
    if (eventoId) { startRealtimeSeats();}

    if (eventoId && CarritoStore.get().length > 0) {
        await syncCarrito(eventoId, CarritoStore.get());
    }
    const btn = document.getElementById('boton-carrito');

    if (btn) {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleDropdown();
        });
    }

    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('dropdown-carrito');
        const btn = document.getElementById('boton-carrito');

        if (!dropdown || !btn) return;

        if (!dropdown.contains(e.target) && e.target !== btn) {
            dropdown.style.display = 'none';
            dropdownAbierto = false;
        }
    });
});


window.addEventListener('beforeunload', () => {
	navigator.sendBeacon('/api/seat/unlock-all');
});

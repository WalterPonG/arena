import { requireLogin } from './auth.js';
export async function fetchLocks() {
    const res = await fetch('/api/seat/locks');
    return await res.json();
}

export async function lockSeat(asiento_id, evento_id) {
    const res = await fetch('/api/seat/lock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ asiento_id, evento_id })
    });

    if (!res.ok) {
        const data = await res.json();
        throw new Error(data.message || 'Error lock');
    }

    return true;
}

export async function unlockSeat(id) {
    await fetch('/api/seat/unlock', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ asiento_id: id })
    });
}

export async function checkout(eventoId, carrito) {
    console.log("CARRITO ENVIADO:", carrito);
    const res = await fetch('/api/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            evento_id: eventoId,
            carrito
        })
    });
     const data = await res.json();

    if (!res.ok) {
        throw new Error(data.message || 'Error checkout');
    }

    return data;
}
export async function syncCarrito(eventoId, carrito) {

    const res = await fetch('/api/carrito/sync', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
	    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            evento_id: eventoId,
            carrito
        })
    });

	// usuario no autenticado
    if (res.status === 401) {
        window.location.href = '/login';
        return;
    }


	const data = await res.json();

    if (!res.ok) {
        throw new Error(data.message || 'Error sync carrito');
    }

    return data;
}

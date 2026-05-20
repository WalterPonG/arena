console.log("🟢 carrito UI cargado");

let dropdownAbierto = false;

/*
|--------------------------------------------------------------------------
| INIT
|--------------------------------------------------------------------------
*/
document.addEventListener('DOMContentLoaded', () => {
    initDropdown();
    render();
});

/*
|--------------------------------------------------------------------------
| EVENTO GLOBAL
|--------------------------------------------------------------------------
*/
document.addEventListener('carrito:updated', () => {
    render();
    pintarAsientos();
    if (dropdownAbierto) {
        openDropdown();
    }
});

/*
|--------------------------------------------------------------------------
| DROPDOWN CONTROL
|--------------------------------------------------------------------------
*/
function initDropdown() {

    const boton = document.getElementById('boton-carrito');

    if (!boton) return;

    boton.addEventListener('click', (e) => {
        e.stopPropagation();

        dropdownAbierto = !dropdownAbierto;

        if (dropdownAbierto) {
            openDropdown();
        } else {
            closeDropdown();
        }
    });

    document.addEventListener('click', (e) => {

        const dropdown = document.getElementById('dropdown-carrito');

        if (!dropdown) return;

        if (!dropdown.contains(e.target) && e.target.id !== 'boton-carrito') {
            closeDropdown();
        }
    });
}

function openDropdown() {
    const dropdown = document.getElementById('dropdown-carrito');
    if (!dropdown) return;

    dropdown.style.display = 'block';
    render();
}

function closeDropdown() {
    const dropdown = document.getElementById('dropdown-carrito');
    if (!dropdown) return;

    dropdown.style.display = 'none';
}

/*
|--------------------------------------------------------------------------
| RENDER
|--------------------------------------------------------------------------
*/
function render() {

    const carrito = window.Carrito.get();

    const contenedor = document.getElementById('carrito-items');
    const totalEl = document.getElementById('carrito-total');
    const contador = document.getElementById('contador-carrito');

    if (!contenedor || !totalEl || !contador) return;

    contenedor.innerHTML = '';

    let total = 0;

    if (carrito.length === 0) {

        contenedor.innerHTML = `<p class="text-muted">Carrito vacío</p>`;
        totalEl.innerText = '0 €';
        contador.innerText = '0';

        pintarAsientos();
        return;
    }

    carrito.forEach(item => {

        total += Number(item.precio);

        contenedor.innerHTML += `
            <div class="border-bottom border-secondary py-3">

                <h6>${item.sector}</h6>

                <p class="mb-1">
                    Fila ${item.fila} - Asiento ${item.numero}
                </p>

                <p class="text-warning fw-bold">
                    ${item.precio} €
                </p>

                <button class="btn btn-sm btn-danger eliminar-item"
                        data-id="${item.asiento_id}">
                    Eliminar
                </button>

            </div>
        `;
    });

    totalEl.innerText = total.toFixed(2) + ' €';
    contador.innerText = carrito.length;

    pintarAsientos();
}

/*
|--------------------------------------------------------------------------
| ELIMINAR
|--------------------------------------------------------------------------
*/
document.addEventListener('click', (e) => {

    const btn = e.target.closest('.eliminar-item');
    if (!btn) return;

    let carrito = window.Carrito.get();

    const id = btn.dataset.id;

    carrito = carrito.filter(
        item => String(item.asiento_id) !== String(id)
    );

    window.Carrito.set(carrito);
});

/*
|--------------------------------------------------------------------------
| PINTAR ASIENTOS
|--------------------------------------------------------------------------
*/
function pintarAsientos() {

    const carrito = window.Carrito.get();

    document.querySelectorAll('.asiento-btn').forEach(btn => {

        const id = btn.dataset.id;

        const existe = carrito.some(
            item => String(item.asiento_id) === String(id)
        );

        btn.classList.remove('btn-success', 'btn-outline-light');

        btn.classList.add(existe ? 'btn-success' : 'btn-outline-light');
    });
}

// carrito.js

function getCarrito() {
    return JSON.parse(localStorage.getItem('carrito')) || [];
}

function setCarrito(carrito) {
    localStorage.setItem('carrito', JSON.stringify(carrito));

    document.dispatchEvent(new Event('carrito:updated'));
}

window.Carrito = {
    get: getCarrito,
    set: setCarrito
};

/*
|--------------------------------------------------------------------------
| CLICK ASIENTOS
|--------------------------------------------------------------------------
*/
document.addEventListener('click', (e) => {

    const btn = e.target.closest('.asiento-btn');
    if (!btn) return;

    let carrito = getCarrito();

    const id = btn.dataset.id;

    const existe = carrito.some(
        item => String(item.asiento_id) === String(id)
    );

    if (existe) {
        carrito = carrito.filter(
            item => String(item.asiento_id) !== String(id)
        );
    } else {
        carrito.push({
            asiento_id: id,
            sector: btn.dataset.sector,
            fila: btn.dataset.fila,
            numero: btn.dataset.numero,
            precio: btn.dataset.precio
        });
    }

    setCarrito(carrito);
});

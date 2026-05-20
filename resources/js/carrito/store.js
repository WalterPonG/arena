const KEY = 'carrito';

export const CarritoStore = {

    get() {
        return JSON.parse(localStorage.getItem(KEY)) || [];
    },

    set(items) {
        localStorage.setItem(KEY, JSON.stringify(items));
    },

    add(item) {
        const current = this.get();
        current.push(item);
        this.set(current);
    },

    remove(id) {
        const current = this.get().filter(i => String(i.asiento_id) !== String(id));
        this.set(current);
    },

    clear() {
        this.set([]);
    }
};

/*export const CarritoStore = (() => {

    const KEY = 'carrito';

    const get = () => JSON.parse(localStorage.getItem(KEY)) || [];

    const set = (items) => {
        localStorage.setItem(KEY, JSON.stringify(items));
        window.dispatchEvent(new Event('carrito:updated'));
    };

    const add = (item) => {
        const current = get();
        const exists = current.some(i => String(i.asiento_id) === String(item.asiento_id));
        if (exists) return;
        set([...current, item]);
    };

    const remove = (id) => {
        const current = get();
        set(current.filter(i => String(i.asiento_id) !== String(id)));
    };

    const clear = () => set([]);

    return { get, set, add, remove, clear };
})();
*/

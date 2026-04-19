document.addEventListener('DOMContentLoaded', () => {
    
    // --- Global Utility ---
    // Format angka ke Rupiah
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    // --- 1. HOME & MENU PAGE LOGIC ---
    
    // Logika Cart Sementara (InMemory)
    let tempCart = [];
    
    const addToCartBtns = document.querySelectorAll('.js-add-to-cart');
    const cartFloater = document.getElementById('cartFloater');
    const cartCountEl = document.getElementById('cartCount');

    const updateCartFloater = () => {
        if (!cartFloater) return; // Keluar jika bukan di halaman home
        
        if (tempCart.length > 0) {
            cartFloater.classList.add('active');
            cartCountEl.innerText = `${tempCart.length} Items in Basket`;
        } else {
            cartFloater.classList.remove('active');
        }
    };

    if (addToCartBtns.length > 0) {
        addToCartBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const card = e.target.closest('.product-card');
                const id = card.dataset.id;
                const title = card.dataset.title;
                const price = parseInt(card.dataset.price);

                // Tambahkan item simpel
                tempCart.push({ id, title, price });
                
                // Ubah teks tombol sementara
                const originalText = btn.innerText;
                btn.innerText = 'Added!';
                btn.classList.remove('btn-primary');
                btn.style.backgroundColor = '#4caf50'; // Green
                btn.style.color = 'white';
                
                setTimeout(() => {
                    btn.innerText = originalText;
                    btn.classList.add('btn-primary');
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                }, 1000);

                updateCartFloater();
            });
        });
    }


    // --- 2. RINCIAN PESANAN PAGE LOGIC ---
    
    const cartContainer = document.querySelector('.js-cart-container');
    const subtotalEl = document.getElementById('checkoutSubtotal');
    const totalEl = document.getElementById('checkoutTotal');

    const updateCheckoutTotals = () => {
        if (!cartContainer) return;

        let subtotal = 0;
        const itemRows = cartContainer.querySelectorAll('.cart-item-card');

        itemRows.forEach(row => {
            const price = parseInt(row.dataset.price);
            const qty = parseInt(row.querySelector('.qty-num').innerText);
            subtotal += price * qty;
        });

        // PPN (misal 11%)
        const ppn = subtotal * 0.11;
        const total = subtotal + ppn;

        // Update UI
        subtotalEl.innerText = formatRupiah(subtotal);
        // Di gambar ada biaya pengantaran, kita buat hardcode misal 25rb
        // PPN-nya kita update juga kalau mau sedetail gambar
        // Untuk simpelnya, kita langsung update Total Akhir
        totalEl.innerText = formatRupiah(total);
    };

    // Logika tombol Qty (Plus/Minus)
    if (cartContainer) {
        cartContainer.addEventListener('click', (e) => {
            if (e.target.closest('.js-qty-plus')) {
                const qtyNumEl = e.target.closest('.qty-controls').querySelector('.qty-num');
                let qty = parseInt(qtyNumEl.innerText);
                qtyNumEl.innerText = ++qty;
                updateCheckoutTotals();
            }
            
            if (e.target.closest('.js-qty-minus')) {
                const qtyNumEl = e.target.closest('.qty-controls').querySelector('.qty-num');
                let qty = parseInt(qtyNumEl.innerText);
                if (qty > 1) {
                    qtyNumEl.innerText = --qty;
                    updateCheckoutTotals();
                }
            }
            
            // Logika hapus item
            if (e.target.closest('.btn-remove-item')) {
                if(confirm('Hapus item ini?')) {
                    e.target.closest('.cart-item-card').remove();
                    updateCheckoutTotals();
                }
            }
        });
        
        // Initial calc
        updateCheckoutTotals();
    }
});
// Ambil elemen dari HTML
const toggleIcon = document.getElementById('toggleIcon');
const passInput = document.getElementById('passInput');
const loginForm = document.getElementById('loginForm');
const userInput = document.getElementById('userInput');

// 1. Fungsi agar Ikon Mata bisa diklik (Show/Hide Password)
toggleIcon.addEventListener('click', function() {
    const isPassword = passInput.getAttribute('type') === 'password';
    passInput.setAttribute('type', isPassword ? 'text' : 'password');
    
    // Ganti ikon mata
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
});

// 2. Fungsi saat tombol "LOGIN SEKARANG" diklik
loginForm.addEventListener('submit', function(e) {
    e.preventDefault(); // Supaya halaman tidak refresh
    
    const emailValue = userInput.value;
    const passValue = passInput.value;

    if(emailValue === "" || passValue === "") {
        alert("Silahkan isi email dan password terlebih dahulu!");
    } else {
        // Menampilkan pesan sesuai apa yang kamu isi
        alert("Halo Admin!\nKamu mengisi Email: " + emailValue + "\nDan Password: " + passValue);
    }
});
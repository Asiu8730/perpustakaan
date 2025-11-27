function openConfirmModal(id, title, borrower, type) {
    const modal = document.getElementById('confirmModal');
    const loanIdInput = document.getElementById('loan_id');
    const bookTitle = document.getElementById('book_title');
    const borrowerName = document.getElementById('borrower_name');
    const actionType = document.getElementById('action_type');
    const returnDateContainer = document.getElementById('returnDateContainer');
    const returnDateInput = document.getElementById('return_date');
    const methodInput = document.getElementById('method');

    loanIdInput.value = id;
    bookTitle.value = title;
    borrowerName.value = borrower;
    actionType.value = type;

    // Default method = web
    methodInput.value = 'web';

    if (type === "peminjaman") {
        // tampilkan input tanggal dan wajibkan pengisian
        returnDateContainer.style.display = "block";
        returnDateInput.required = true;
        returnDateInput.disabled = false;
        returnDateInput.value = ""; // kosongkan agar admin isi
        document.getElementById('modalTitle').textContent = "Konfirmasi Peminjaman Buku";
    } else {
        // pengembalian / pengembalian langsung: sembunyikan input tanggal,
        // nonaktifkan required agar form bisa submit langsung
        returnDateContainer.style.display = "none";
        returnDateInput.required = false;
        returnDateInput.disabled = true;
        // untuk konfirmasi pengembalian langsung kita bisa set method atau biarkan server set return_date
        document.getElementById('modalTitle').textContent = (type === 'pengembalian') ? "Konfirmasi Pengembalian Buku" : "Konfirmasi";
    }

    modal.style.display = "block";
}

function closeConfirmModal() {
    document.getElementById('confirmModal').style.display = 'none';
}

// Tutup modal ketika klik di luar isi modal
window.addEventListener('click', function(e) {
    const modal = document.getElementById('confirmModal');
    if (!modal) return;
    if (e.target === modal) modal.style.display = 'none';
});

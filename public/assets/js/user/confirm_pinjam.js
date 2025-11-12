// ✅ Tambahkan konfirmasi pinjam buku
    document.addEventListener("DOMContentLoaded", () => {
        const borrowForm = document.getElementById("borrowForm");
        const borrowBtn = borrowForm.querySelector('button[name="add_to_cart"]');

        borrowBtn.addEventListener("click", (e) => {
            e.preventDefault(); // cegah submit langsung

            // Buat pop-up konfirmasi
            const confirmBox = document.createElement("div");
            confirmBox.innerHTML = `
                <div class="confirm-overlay">
                    <div class="confirm-box">
                        <p>Anda yakin meminjam buku ini?</p>
                        <div class="confirm-actions">
                            <button id="confirmYes">Iya</button>
                            <button id="confirmNo">Tidak</button>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(confirmBox);

            // Style popup
            const overlay = document.querySelector(".confirm-overlay");
            Object.assign(overlay.style, {
                position: "fixed",
                top: 0,
                left: 0,
                width: "100%",
                height: "100%",
                backgroundColor: "rgba(0,0,0,0.4)",
                display: "flex",
                justifyContent: "center",
                alignItems: "center",
                zIndex: 9999
            });

            const box = document.querySelector(".confirm-box");
            Object.assign(box.style, {
                background: "#fff",
                padding: "20px 30px",
                borderRadius: "12px",
                boxShadow: "0 5px 15px rgba(0,0,0,0.3)",
                textAlign: "center",
                width: "300px",
                fontFamily: "Poppins, sans-serif"
            });

            const actions = document.querySelector(".confirm-actions");
            Object.assign(actions.style, {
                display: "flex",
                justifyContent: "space-around",
                marginTop: "15px"
            });

            const yesBtn = document.getElementById("confirmYes");
            const noBtn = document.getElementById("confirmNo");

            yesBtn.style.cssText = "background:#4CAF50;color:white;border:none;padding:8px 15px;border-radius:8px;cursor:pointer;";
            noBtn.style.cssText = "background:#f44336;color:white;border:none;padding:8px 15px;border-radius:8px;cursor:pointer;";

            // Klik tombol
            yesBtn.onclick = () => {
                document.body.removeChild(confirmBox);
                borrowForm.submit(); // lanjut submit form
            };

            noBtn.onclick = () => {
                document.body.removeChild(confirmBox);
            };
        });
    });
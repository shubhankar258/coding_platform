document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ JavaScript Loaded!"); // Check if script runs

    let uploadForm = document.getElementById("uploadForm");

    if (!uploadForm) {
        console.error("❌ Error: Form element not found!");
        return;
    }

    uploadForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent page reload

        let formData = new FormData(this);
        console.log("✅ Sending Form Data:", formData); // Check if form data is being sent

        fetch("../../backend/dashboards/upload_notes.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text()) // Read raw response
        .then(text => {
            console.log("✅ Raw Response:", text); // Show raw response

            let data;
            try {
                data = JSON.parse(text);
            } catch (error) {
                console.error("❌ JSON Parse Error:", error);
                return;
            }

            if (data.success) {
                console.log("✅ Upload Success:", data.success);
                showPopup("✅ " + data.success);
                uploadForm.reset();
            } else if (data.error) {
                console.error("❌ Upload Error:", data.error);
                showPopup("❌ " + data.error);
            }
        })
        .catch(error => console.error("❌ Fetch Error:", error));
    });

    function showPopup(message) {
        let popup = document.getElementById("successPopup");
        if (!popup) {
            console.error("❌ Error: Popup element not found!");
            return;
        }
        popup.querySelector("p").innerText = message;
        popup.style.display = "block";
    }

    window.closePopup = function () {
        let popup = document.getElementById("successPopup");
        if (popup) {
            popup.style.display = "none";
        }
    };
});

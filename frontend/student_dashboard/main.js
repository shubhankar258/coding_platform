document.addEventListener("DOMContentLoaded", function () {
    loadSubjects();
});

// Fetch Available Subjects
function loadSubjects() {
    console.log("📢 Fetching subjects...");
    fetch('../../backend/dashboards/get_subjects.php')
        .then(response => response.text()) // Read raw text response
        .then(text => {
            console.log("📜 Raw Subjects Response:", text); // Debugging log
            let data;
            try {
                data = JSON.parse(text);
            } catch (error) {
                console.error("❌ JSON Parse Error:", error);
                document.getElementById('subjects-container').innerHTML = `<p style="color: red;">Error loading subjects. Please check console.</p>`;
                return;
            }

            let subjectsContainer = document.getElementById('subjects-container');
            subjectsContainer.innerHTML = ""; // Clear previous content

            if (!Array.isArray(data) || data.length === 0) {
                subjectsContainer.innerHTML = "<p>No subjects available.</p>";
                return;
            }

            data.forEach(subject => {
                let subjectElement = document.createElement('button');
                subjectElement.innerText = subject;
                subjectElement.classList.add("subject-btn"); // Add CSS class for styling
                subjectElement.onclick = function () {
                    loadNotesBySubject(subject);
                };
                subjectsContainer.appendChild(subjectElement);
            });
        })
        .catch(error => {
            console.error("❌ Fetch Error:", error);
            document.getElementById('subjects-container').innerHTML = `<p style="color: red;">Failed to load subjects.</p>`;
        });
}

// Fetch Notes for a Selected Subject
function loadNotesBySubject(subject) {
    console.log(`📢 Fetching notes for: ${subject}`);
    
    let notesContainer = document.getElementById('notes-container');
    let notesList = document.getElementById('notes-list');
    let subjectTitle = document.getElementById('subject-title');

    notesContainer.style.display = "block";
    document.getElementById('subjects-container').style.display = "none";

    subjectTitle.innerText = `📂 Notes for ${subject}`;
    notesList.innerHTML = "<p>Loading notes...</p>"; // Show loading text

    fetch(`../../backend/dashboards/get_notes_by_subject.php?subject=${encodeURIComponent(subject)}`)
        .then(response => response.text()) // Read raw text response
        .then(text => {
            console.log("📜 Raw Notes Response:", text); // Debugging log
            let data;
            try {
                data = JSON.parse(text);
            } catch (error) {
                console.error("❌ JSON Parse Error:", error, text);
                notesList.innerHTML = `<p style="color: red;">Error loading notes. Please check console.</p>`;
                return;
            }

            notesList.innerHTML = ""; // Clear previous content

            if (!Array.isArray(data) || data.length === 0) {
                notesList.innerHTML = `<p>No notes available for ${subject}.</p>`;
                return;
            }

            data.forEach(note => {
                let noteElement = document.createElement('div');
                noteElement.classList.add("note-item"); // Add CSS class for styling
                noteElement.innerHTML = `
                    <h3>📌 ${note.title}</h3>
                    <p><strong>Uploaded by:</strong> ${note.uploaded_by}</p>
                    <a href="${note.file_path}" target="_blank">📂 Download</a>
                `;
                notesList.appendChild(noteElement);
            });
        })
        .catch(error => {
            console.error("❌ Fetch Error:", error);
            notesList.innerHTML = `<p style="color: red;">Failed to load notes.</p>`;
        });
}

// Function to Go Back to Subject List
function goBack() {
    document.getElementById('notes-container').style.display = "none";
    document.getElementById('subjects-container').style.display = "block";
}

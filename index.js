const noteHolder = document.getElementById("stickyNoteHolder");
let topZIndex = 0;

document.getElementById("createStickyNote").addEventListener("click", createStickyNoteButton);
document.getElementById("saveStickyNotes").addEventListener("click", saveButton);
document.getElementById("getStickyNotes").addEventListener("click", loadNotesButton);

function createStickyNote({left, top, zIndex, data}) {
    let stickyNote = document.createElement("div");
    stickyNote.classList.add("sticky-note");

    let stickyNoteHeader = document.createElement("div");
    stickyNoteHeader.classList.add("sticky-note-header", "d-flex", 
        "justify-content-between", "align-items-center", "p-1");

    let textarea = document.createElement("textarea");
    textarea.classList.add("sticky-note-textarea"); 
    textarea.maxLength = 255;
    textarea.value = data;

    let draggableIcon = document.createElement("img");
    draggableIcon.src = "assets/drag_indicator.svg";
    draggableIcon.classList.add("unselectable");
    
    let deleteButton = document.createElement("button");
    deleteButton.innerHTML = "X";
    deleteButton.classList.add("p-0", "sticky-note-delete");
    deleteButton.addEventListener("click", () => {
        stickyNote.remove();
    });

    stickyNote.appendChild(stickyNoteHeader);
    stickyNote.appendChild(textarea);
    stickyNoteHeader.appendChild(draggableIcon);
    stickyNoteHeader.appendChild(deleteButton);
    noteHolder.appendChild(stickyNote);

    stickyNote.style.width = "250px";
    stickyNote.style.height = "250px";
    stickyNote.style.left = left + "px";
    stickyNote.style.top = top + "px";

    stickyNote.style.zIndex = zIndex;

    makeGrabbable(stickyNote, stickyNoteHeader);
}

function makeGrabbable(stickyNote, stickyNoteHeader) {
    let isGrabbing = false;
    let offsetX = 0;
    let offsetY = 0;
    
    stickyNoteHeader.addEventListener("mousedown", (e) => {
        e.preventDefault();

        isGrabbing = true;

        stickyNote.style.zIndex = topZIndex++;

        const rect = stickyNote.getBoundingClientRect();
        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;

        document.body.style.userSelect = "none";
    });

    document.addEventListener("mousemove", (e) => {
        if (!isGrabbing) return;

        const containerRect = noteHolder.getBoundingClientRect();

        const maxLeft = containerRect.width - stickyNote.offsetWidth;
        const maxTop = containerRect.height - stickyNote.offsetHeight;

        let newLeft = e.clientX - containerRect.left - offsetX;
        let newTop = e.clientY - containerRect.top - offsetY;

        newLeft = clamp(newLeft, 0, maxLeft);
        newTop = clamp(newTop, 0, maxTop);

        stickyNote.style.left = newLeft + "px";
        stickyNote.style.top = newTop + "px";
    });

    stickyNoteHeader.addEventListener("mouseup", (e) => {
        e.preventDefault();

        document.body.style.userSelect = "auto";

        isGrabbing = false;
    });
}

function createStickyNoteButton() {
    newNote = {
        left: 32, 
        top: 64, 
        zIndex: topZIndex++,
        data: ""
    };

    createStickyNote(newNote);
}

function loadNotesButton() {
    let answer = confirm("Kaikki muistilaput poistetaan ja tallennetut ladataan. Haluatko jatkaa?");
    if(answer == false){
        return;
    }

    loadNotesFromDb();
}

function loadNotesFromDb() {
    noteHolder.innerHTML = "";

    fetch('load_notes.php')
        .then(res => {
            if (!res.ok) {
                throw new Error(`Server error: ${res.status} ${res.statusText}`);
            }
            return res.json();
        })
        .then(notes => {
            if (!Array.isArray(notes)) {
                throw new Error('Invalid data format received from server');
            }

            notes.forEach(note => {
                if (topZIndex < note.z_index) 
                    topZIndex = note.z_index;

                createStickyNote({
                    left: note.pos_left, 
                    top: note.pos_top, 
                    zIndex: note.z_index,
                    data: note.data || "" 
                });
            });
        })
        .catch(error => {
            console.error('Failed to load notes:', error);
        });
}

function saveButton() {
    let answer = confirm("Haluatko varmasti tallentaa?");
    if(answer == false){
        return;
    }

    saveToDb();
}

function collectNotes() {
    const notesArray = [];
    const stickyNotes = noteHolder.querySelectorAll(".sticky-note");
    stickyNotes.forEach(note => {
        const style = window.getComputedStyle(note);
        const noteData = {
            left: parseInt(style.left),
            top: parseInt(style.top),
            z_index: parseInt(style.zIndex) || 0,
            data: note.getElementsByClassName("sticky-note-textarea")[0].value.trim()
        };

        notesArray.push(noteData);
    });

    return notesArray;
}

function saveToDb() {
    fetch('save_notes.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(collectNotes())
    })
    .then(response => response.text()) 
    .then(result => {
      console.log('Server response:', result);
    })
    .catch(error => {
      console.error('Error:', error);
    });
}

function clamp(num, min, max) {
    return num <= min 
      ? min 
      : num >= max 
        ? max 
        : num
}

loadNotesFromDb()
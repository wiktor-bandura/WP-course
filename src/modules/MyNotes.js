class MyNotes {
    constructor() {
        this.deleteButton = document.querySelector('.delete-note');
        this.editButton = document.querySelector('.edit-note');
        this.updateButton = document.querySelector('.update-note');
        this.events();
    }

    events = () => {
        this.deleteButton.addEventListener('click', this.deleteNote);
        this.editButton.addEventListener('click', this.editNote);
        this.updateButton.addEventListener('click', this.updateNote);
    }

    // Methods

    deleteNote = (e) => {

        const thisNote = e.target.parentElement;

        fetch(`${themeData.root_url}/wp-json/wp/v2/note/${thisNote.getAttribute('data-id')}`, {
            method: 'DELETE',
            headers: {
                'X-WP-Nonce': themeData.nonce,
                }}
            )
            .then((res) => {
                if(res.status == 200) {
                    thisNote.style.display = 'none';
                }
            })
            .catch((err) => console.error(err));
    }

    updateNote = (e) => {

        const thisNote = e.target.parentElement;

        fetch(`${themeData.root_url}/wp-json/wp/v2/note/${thisNote.getAttribute('data-id')}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': themeData.nonce,
            },
            body: JSON.stringify({
                    'title': thisNote.querySelector('.note-title-field').value,
                    'content': thisNote.querySelector('.note-body-field').value,
            })
          })
            .then((res) => {
                if(res.status == 200) {
                    this.makeNoteReadOnly(thisNote, e);
                }
            })
            .catch((err) => console.error(err));
    }

    editNote = (e) => {

        const thisNote = e.target.parentElement;

        if(thisNote.getAttribute('data-state') === 'editable') {
            this.makeNoteReadOnly(thisNote, e);
        } else {
            this.makeNoteEditable(thisNote, e);
        }
    }

    makeNoteEditable = (note, event) => {
        const input = note.querySelector('.note-title-field');
        const area = note.querySelector('.note-body-field');
        this.updateButton.classList.add('update-note--visible');
        note.setAttribute('data-state', 'editable');
        event.target.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i> Cancel ';

        const fields = [input, area];

        fields.forEach((field) => {
            field.removeAttribute('readonly');
            field.classList.add('note-active-field');
        });
    }

    makeNoteReadOnly = (note, event) => {
        const input = note.querySelector('.note-title-field');
        const area = note.querySelector('.note-body-field');
        this.updateButton.classList.remove('update-note--visible');
        note.setAttribute('data-state', '');
        event.target.innerHTML = '<i class="fa fa-pencil" aria-hidden="true"></i> Edit note: ';

        const fields = [input, area];

        fields.forEach((field) => {
            field.setAttribute('readonly', '');
            field.classList.remove('note-active-field');
        });
    }

}

export default MyNotes;
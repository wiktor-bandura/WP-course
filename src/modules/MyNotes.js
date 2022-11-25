class MyNotes {
    constructor() {
        this.events();
    }

    events = () => {
        document.querySelectorAll('.delete-note').forEach(btn => btn.addEventListener('click', this.deleteNote));
        document.querySelectorAll('.edit-note').forEach(btn => btn.addEventListener('click', this.editNote));
        document.querySelectorAll('.update-note').forEach(btn => btn.addEventListener('click', this.editNote));
        document.querySelector('.submit-note').addEventListener('click', this.createNote);
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
                console.log(res);
                    thisNote.style.display = 'none';
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
                console.log(res)
                    this.makeNoteReadOnly(thisNote, e);
            })
            .catch((err) => console.error(err));
    }

    createNote = (e) => {

        fetch(`${themeData.root_url}/wp-json/wp/v2/note/`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': themeData.nonce,
            },
            body: JSON.stringify({
                'title': document.querySelector('.new-note-title').value,
                'content': document.querySelector('.new-note-body').value,
            })
        })
            .then((res) => res.json())
                .then(data => {
                    console.log(data);

                    document.querySelector('.new-note-title').value = '';
                    document.querySelector('.new-note-body').value = '';

                    let html = `
                                <label>
                                    <input readonly class="note-title-field" value="${data.title.raw}">
                                </label>
                                <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit note: </span>
                                <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete note: </span>
                                <label>
                                    <textarea readonly class="note-body-field">${data.content.raw}</textarea>
                                </label>
                                <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save note: </span>
                    `;

                    const newItem = document.createElement('li');
                    newItem.setAttribute('data-id', data.id);
                    newItem.innerHTML = html;

                    document.querySelector('#my-notes').insertBefore(newItem, document.querySelector('#my-notes').firstChild);
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
        note.querySelector('.update-note').classList.add('update-note--visible');
        note.setAttribute('data-state', 'editable');
        event.target.innerHTML = '<i class="fa fa-times" aria-hidden="true"></i> Cancel ';

        input.removeAttribute('readonly');
        input.classList.add('note-active-field');
        area.removeAttribute('readonly');
        area.classList.add('note-active-field');
    }

    makeNoteReadOnly = (note, event) => {
        const input = note.querySelector('.note-title-field');
        const area = note.querySelector('.note-body-field');
        note.querySelector('.update-note').classList.remove('update-note--visible');
        note.setAttribute('data-state', '');
        event.target.innerHTML = '<i class="fa fa-pencil" aria-hidden="true"></i> Edit note: ';

        input.removeAttribute('readonly');
        input.classList.remove('note-active-field');
        area.removeAttribute('readonly');
        area.classList.remove('note-active-field');
    }
}

export default MyNotes;
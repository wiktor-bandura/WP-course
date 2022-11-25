class MyNotes {
    constructor() {
        this.deleteButton = document.querySelector('.delete-note');
        this.editButton = document.querySelector('.edit-note');
        this.events();
    }

    events = () => {
        this.deleteButton.addEventListener('click', this.deleteNote);
        this.editButton.addEventListener('click', this.editNote);
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

    editNote = (e) => {

        const input = e.target.parentElement.querySelector('.note-title-field');
        const area = e.target.parentElement.querySelector('.note-body-field');
        e.target.parentElement.querySelector('.update-note').classList.add('update-note--visible');

        const fields = [input, area];

        fields.forEach((field) => {
            field.removeAttribute('readonly');
            field.classList.add('note-active-field');

        });
    }

}

export default MyNotes;